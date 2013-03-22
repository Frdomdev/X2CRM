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

Yii::import('application.models.X2Model');

/**
 * This is the model class for table "x2_groups".
 * @package X2CRM.modules.groups.models
 */
class Groups extends X2Model {
	/**
	 * Returns the static model of the specified AR class.
	 * @return Groups the static model class
	 */
	public static function model($className=__CLASS__) { return parent::model($className); }

	/**
	 * @return string the associated database table name
	 */
	public function tableName() { return 'x2_groups'; }

	public function behaviors() {
		return array_merge(parent::behaviors(),array(
			'X2LinkableBehavior'=>array(
				'class'=>'X2LinkableBehavior',
				'module'=>'groups'
			)
		));
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>259),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name', 'safe', 'on'=>'search'),
		);
	}
	
	public static function getNames() {
		
		$groupNames = array();
		$data = Yii::app()->db->createCommand()->select('id,name')->from('x2_groups')->order('name ASC')->queryAll(false);
		foreach($data as $row)
			$groupNames[$row[0]] = $row[1];
		
		return $groupNames;
		
		// $groupArray = X2Model::model('Groups')->findAll();
		// $names = array();
		// foreach ($groupArray as $group) {
			// $names[$group->id] = $group->name;
		// }
		// return $names;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'name' => 'Name',
		);
	}
	
	// public static function getLink($id) {
		// $groupName = Yii::app()->db->createCommand()->select('name')->from('x2_groups')->where('id='.$id)->queryScalar();

		// if(isset($groupName))
			// return CHtml::link($groupName,array('/groups/'.$id));
		// else
			// return '';
	// }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider('Groups', array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'name DESC'	// true = ASC
			),
		));
	}
	
	/* inGroup
	 *
	 * Find out if a user belongs to a group
	 */
	public static function inGroup($userId, $groupId) {
		return GroupToUser::model()->exists("userId=$userId AND groupId=$groupId");
	}

	/* Looks up groups to which the specified user belongs.
	 * Uses cache to lookup/store groups.
	 * 
	 * @param Integer $userId user to look up groups for
	 * @param Boolean $cache whether to use cache
	 * @return Array array of groupIds
	 */
	public static function getUserGroups($userId,$cache=true) {
		// check the app cache for user's groups
		if($cache === true && ($userGroups = Yii::app()->cache->get('user_groups')) !== false) {
			if(isset($userGroups[$userId]))
				return $userGroups[$userId];
		} else {
			$userGroups = array();
		}
		
		$userGroups[$userId] = Yii::app()->db->createCommand()	// get array of groupIds
			->select('groupId')
			->from('x2_group_to_user')
			->where('userId=' . $userId)->queryColumn();
		
		if($cache === true)
			Yii::app()->cache->set('user_groups',$userGroups,259200); // cache user groups for 3 days
		
		return $userGroups[$userId];
	}
}