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

$this->menu=array(array('label'=>'Dashboard','url'=>'admin'));

?>
<h2><center>Side Settings</center></h2>
<?php echo "<p><center>Below you will find a listing of the current settings for the sidebar in other portions of the site. To edit the order and display of these settings, please click on an entry name to edit its settings.</center></p>\n"; ?> <br />
<?php
$uid = Yii::app()->user->getId();
$attributes = Dashboard::attributeLabels();
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'baseScriptUrl'=>Yii::app()->theme->getBaseUrl().'/css/gridview',
    'enableSorting'=>false,
    'columns'=>array(
        array(
            'name'=>$attributes['dispNAME'],
            'value'=>'CHtml::link($data->dispNAME,array("update", "id"=>$data->id))',
            'type'=>'raw'
        ),
        array(
           'name'=>$attributes['adminALLOWS'],
           'value'=>'(($data->adminALLOWS ? "Yes" : "No"))',
           'type'=>'raw'
        ),
        array(
            'name'=>$attributes['posPROF'],
            'value'=>'$data->posPROF',
            'type'=>'raw'
        ),
        array(
            'name'=>$attributes['userALLOWS'],
            'value'=>'(($data->userALLOWS ? "Yes" : "No"))',
            'type'=>'raw'
        )
    ),
));?>

