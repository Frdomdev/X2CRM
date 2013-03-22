<?php

/*****************************************************************************************
 * X2CRM Open Source Edition is a customer relationship management program developed by
 * X2Engine, Inc. Copyright (C) 2011-2013 X2Engine Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY X2ENGINE, X2ENGINE DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact X2Engine, Inc. P.O. Box 66752, Scotts Valley,
 * California 95067, USA. or at email address contact@x2engine.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * X2Engine" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by X2Engine".
 *****************************************************************************************/

/**
 * Miscellaneous file system utilities. It is not a child class of CComponent or 
 * the like in order to be portable/stand-alone (i.e. so it can be used outside
 * the app by the installer).
 * 
 * @package X2CRM.components 
 */
class FileUtil {

	public static $_finfo;
	public static $alwaysCurl = false;

	/**
	 * Copies a file. 
	 * 
	 * If the local filesystem directory to where the file will be copied does 
	 * not exist yet, it will be created automatically. Furthermore, if a remote
	 * URL is being accessed and allow_url_fopen isn't set, it will attempt to
	 * use CURL instead.
	 * 
	 * @param string $source The source file
	 * @param strint $target The destination path.
	 * @return boolean 
	 */
	public static function ccopy($source, $target) {

		$pieces = explode('/', $target);
		unset($pieces[count($pieces)]);
		for ($i = 0; $i < count($pieces); $i++) {
			$str = "";
			for ($j = 0; $j < $i; $j++) {
				$str.=$pieces[$j] . '/';
			}

			if (!is_dir($str) && $str != "") {
				mkdir($str);
			}
		}
		if (self::tryCurl($source)) {
			// Fall back on the getContents method, which will try using CURL
			$ch = self::curlInit($source);
			$contents = curl_exec($ch);
			return (bool) @file_put_contents($target, $contents);
		} else
			return @copy($source, $target);
	}
	
	public static function curlInit($url) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		return $ch;
	}

	/**
	 * Wrapper for file_get_contents that attempts to use CURL if allow_url_fopen is disabled.
	 * 
	 * @param type $source
	 * @param type $url
	 * @return type
	 * @throws Exception 
	 */
	public static function getContents($source,$use_include_path=false,$context=null) {
		if (self::tryCurl($source)) {
			$ch = self::curlInit($source);
			return @curl_exec($ch);
		} else {
			// Use the usual copy method
			return @file_get_contents($source,$use_include_path,$context);
		}
	}

	/**
	 * Format a path so that it is platform-independent. Doesn't return false
	 * if the path doesn't exist (so unlike realpath() it can be used to create 
	 * new filess).
	 * 
	 * @param string $path
	 * @return string 
	 */
	public static function rpath($path) {
		return implode(DIRECTORY_SEPARATOR, explode('/', $path));
	}

	/**
	 * Recursively remove a directory.
	 * 
	 * Walks a directory structure, removing files recursively. An optional
	 * exclusion pattern can be included. If a directory contains a file that
	 * matches the exclusion pattern, the directory and its ancestors will not 
	 * be deleted.
	 * 
	 * @param string $path 
	 * @param string $noDelPat PCRE pattern for excluding files in deletion.
	 */
	public static function rrmdir($path, $noDelPat = null) {
		$useExclude = $noDelPat != null;
		$special = '/.*\/?\.+\/?$/';
		$excluded = false;
		if (!realpath($path))
			return false;
		$path = realpath($path);
		if (filetype($path) == 'dir') {
			$objects = scandir($path);
			foreach ($objects as $object) {
				if (!preg_match($special, $object)) {
					if ($useExclude) {
						if (!preg_match($noDelPat, $object)) {
							$excludeThis = self::rrmdir($path . DIRECTORY_SEPARATOR . $object, $noDelPat);
							$excluded = $excluded || $excludeThis;
						} else {
							$excluded = true;
						}
					} else
						self::rrmdir($path . DIRECTORY_SEPARATOR . $object, $noDelPat);
				}
			}
			reset($objects);
			if (!$excluded)
				if (!preg_match($special, $path))
					rmdir($path);
		} else
			unlink($path);
		return $excluded;
	}

	/**
	 * Create/return finfo resource handle
	 * 
	 * @return resource
	 */
	public static function finfo() {
		if (!isset(self::$_finfo))
			if(extension_loaded('fileinfo'))
				self::$_finfo = finfo_open();
			else
				self::$_finfo =  false;
		return self::$_finfo;
	}

	/**
	 * Create human-readable size string
	 * 
	 * @param type $bytes
	 * @return type 
	 */
	public static function formatSize($bytes, $places = 0) {
		$sz = 'BKMGTP';
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$places}f ", $bytes / pow(1024, $factor)) . @$sz[$factor] . "B";
	}

	/**
	 * The criteria for which CURL should be used.
	 * @return type 
	 */
	public static function tryCurl($source) {
		$try = preg_match('/^https?:\/\//', $source) && (ini_get('allow_url_fopen')==0 || self::$alwaysCurl);
		if ($try)
			if (!extension_loaded('curl'))
				throw new Exception('No HTTP methods available; tried accessing a remote URL, but allow_url_fopen is not enabled and the CURL extension is not loaded.');
		return $try;
	}

}

?>
