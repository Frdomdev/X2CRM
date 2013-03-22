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
 * This is the model class for table "x2_actions".
 * @package X2CRM.modules.actions.models
 */
class Actions extends X2Model {
	/**
	 * Returns the static model of the specified AR class.
	 * @return Actions the static model class
	 */
	public static function model($className=__CLASS__) { return parent::model($className); }

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'x2_actions';
	}
	
	public function behaviors() {
		return array_merge(parent::behaviors(),array(
			'X2LinkableBehavior'=>array(
				'class'=>'X2LinkableBehavior',
				'module'=>'actions'
			),
			'ERememberFiltersBehavior' => array(
				'class'=>'application.components.ERememberFiltersBehavior',
				'defaults'=>array(),
				'defaultStickOnClear'=>false
			)
		));
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
			array('actionDescription','required','on'=>'insert'),	// code-generated actions may not have a description
			array('allDay','boolean'),
			array('createDate, completeDate, lastUpdated', 'numerical', 'integerOnly'=>true),
			array('id,assignedTo,actionDescription,visibility,associationId,associationType,associationName,dueDate,
				priority,type,createDate,complete,reminder,completedBy,completeDate,lastUpdated,updatedBy,color','safe')
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		return array_merge(parent::relations(),array(
			'workflow'=>array(self::BELONGS_TO, 'Workflow', 'workflowId'),
		));
	}

	/**
	 * Fixes up record association, parses dates (since this doesn't use {@link X2Model::setX2Fields()})
	 * @return boolean whether or not to save
	 */
	public function beforeSave() {
		if($this->scenario !== 'workflow') {
			$association = self::getAssociationModel($this->associationType, $this->associationId);
			
			if($association === null) {
				$this->associationName = 'None';
				$this->associationId = 0;
			} else {
				if($association->hasAttribute('name'))
					$this->associationName = $association->name;
				$association->updateLastActivity();
			}
			
			if($this->associationName == 'None' && $this->associationType != 'none')
				$this->associationName = ucfirst($this->associationType);
			
			$this->dueDate = self::parseDateTime($this->dueDate);
			$this->completeDate = self::parseDateTime($this->completeDate);
		}
		
		return parent::beforeSave();
	}

	/**
	 * Creates an action reminder event.
	 * Fires the onAfterCreate event in {@link X2Model::afterCreate} 
	 */
	public function afterCreate() {
		if(empty($this->type) && $this->complete !== 'Yes') {
			$event = new Events;
			$event->timestamp = $this->dueDate;
			$event->visibility = $this->visibility;
			$event->type = 'action_reminder';
			$event->associationType = 'Actions';
			$event->associationId = $this->id;
			$event->user = $this->assignedTo;
			$event->save();
		}
		parent::afterCreate();
	}
	
	/**
	 * Deletes the action reminder event, if any
	 * Fires the onAfterDelete event in {@link X2Model::afterDelete} 
	 */
	public function afterDelete() {
		CActiveRecord::model('Events')->deleteAllByAttributes(array('associationType'=>'Actions','associationId'=>$this->id,'type'=>'action_reminder'));
		parent::afterDelete();
	}

	/**
	 * return an array of possible colors for an action
	 */
	public static function getColors() {
		return array(
		    'Green'=>Yii::t('actions', 'Green'),
		    '#3366CC'=>Yii::t('actions', 'Blue'),
		    'Red'=>Yii::t('actions', 'Red'),
		    'Orange'=>Yii::t('actions', 'Orange'),
		    'Black'=>Yii::t('actions', 'Black'),
		);
	}
	
	/**
	 * Marks the action complete and updates the record.
	 * @param string $completedBy the user completing the action (defaults to currently logged in user)
	 * @return boolean whether or not the action updated successfully
	 */
	public function complete($completedBy=null) {
		if($completedBy === null)
			$completedBy = Yii::app()->user->getName();
		
		$this->complete = 'Yes';
		$this->completedBy = $completedBy;
		$this->completeDate = time();
		
		$this->disableBehavior('changelog');
		
		if($result = $this->update()) {
		
			X2Flow::trigger('action_completed',array(
				'model'=>$this,
				'user'=>$completedBy
			));
			
			// delete the action reminder event
			CActiveRecord::model('Events')->deleteAllByAttributes(array('associationType'=>'Actions','associationId'=>$this->id,'type'=>'action_reminder'),'timestamp > NOW()');
			
			$event = new Events;
			$event->type = 'action_complete';
			$event->visibility = $this->visibility;
			$event->associationType = 'Actions';
			$event->user=Yii::app()->user->getName();
			$event->associationId = $this->id;
			
			// notify the admin
			if($event->save() && Yii::app()->user->getName() !== 'admin') {
				$notif = new Notification;
				$notif->type = 'action_complete';
				$notif->modelType = 'Actions';
				$notif->modelId = $this->id;
				$notif->user = 'admin';
				$notif->createdBy = $completedBy;
				$notif->createDate = time();
				$notif->save();
			}
		}
		$this->enableBehavior('changelog');
		
		return $result;
	}
	
	/**
	 * Marks the action incomplete and updates the record.
	 * @return boolean whether or not the action updated successfully
	 */
	public function uncomplete() {
		$this->complete = 'No';
		$this->completedBy = null;
		$this->completeDate = null;
		
		$this->disableBehavior('changelog');
		
		if($result = $this->update()) {
			X2Flow::trigger('action_uncompleted',array(
				'model'=>$this,
				'user'=>Yii::app()->user->getName()
			));
		}
		$this->enableBehavior('changelog');
		
		return $result;
	}

	public function getName() {
		return $this->actionDescription;
	}
	
	public function getLink($length = 30) {
	
		$text = $this->owner->name;
		if($length && strlen($text) > $length)
			$text = CHtml::encode(mb_substr($text,0,$length,'UTF-8').'...');
		return CHtml::link($text,array($this->viewRoute.'/'.$this->owner->id));
	}
	
	public function getAssociationLink() {
		$model = self::getAssociationModel($this->associationType, $this->associationId);
		if($model !== null)
			return $model->getLink();
		return false;
	}
	
	public static function parseStatus($dueDate) {

		if (empty($dueDate))	// there is no due date
			return false;
		if (!is_numeric($dueDate))
			$dueDate = strtotime($dueDate);	// make sure $date is a proper timestamp

		//$due = getDate($dueDate);
		//$dueDate = mktime(23,59,59,$due['mon'],$due['mday'],$due['year']); // if there is no time, give them until 11:59 PM to finish the action
		
		//$dueDate += 86399;	
	
		$timeLeft = $dueDate - time();	// calculate how long till due date
		if ($timeLeft < 0)
			return Yii::t('actions','Overdue {time}',array('{time}'=>Actions::formatDate($dueDate)));	// overdue by X hours/etc

		else
			return Yii::t('actions','Due {date}',array('{date}'=>Actions::formatDate($dueDate)));
	}
		
	public static function formatTimeLength($seconds) {
		$seconds = abs($seconds);
		if($seconds < 60)
			return Yii::t('app','{n} second|{n} seconds',$seconds);	// less than 1 min
		if($seconds < 3600)
			return Yii::t('app','{n} minute|{n} minutes',floor($seconds/60));	// minutes (less than an hour)
		if($seconds < 86400)
			return Yii::t('app','{n} hour|{n} hours',floor($seconds/3600));	// hours (less than a day)
		if($seconds < 5184000)
			return Yii::t('app','{n} day|{n} days',floor($seconds/86400));	// days (less than 60 days)
		else
			return Yii::t('app','{n} month|{n} months',floor($seconds/2592000));	// months (more than 90 days)
	}
	
	// finds record for the "owner" of a action, using the owner type and ID
	public static function getOwnerModel($ownerType,$ownerId) {
		if(!(empty($ownerType) || empty($ownerId)) && isset(X2Model::$associationModels[$ownerType])) {	// both ID and type must be set
			return X2Model::model(X2Model::$associationModels[$ownerType])->findByPk($ownerId);
		
			// if($ownerType=='projects')
				// return X2Model::model('ProjectChild')->findByPk($ownerId);
			// if($ownerType=='contacts')
				// return X2Model::model('Contacts')->findByPk($ownerId);
			// if($ownerType=='accounts')
				// return X2Model::model('Accounts')->findByPk($ownerId);
			// if($ownerType=='cases')
				// return X2Model::model('CaseChild')->findByPk($ownerId);
			// if($ownerType=='opportunities')
				// return X2Model::model('Opportunity')->findByPk($ownerId);
		}
		return null;	// either the type is unkown, or there simply is no owner
	}
	
	// creates virtual attribute for owner's name, if exists
	public function getOwnerName() {
		$ownerModel = Actions::getOwnerModel($this->ownerType,$this->ownerId);
		if($ownerModel !== null)
			return $ownerModel->name;	// get name of owner
		else
			return false;
	}
	
	
	public function search() {
		$criteria=new CDbCriteria;
        $parameters=array('condition'=>"(assignedTo='Anyone' OR assignedTo='".Yii::app()->user->getName()."' OR assignedTo='' OR assignedTo IN (SELECT groupId FROM x2_group_to_user WHERE userId='".Yii::app()->user->getId()."')) AND dueDate <= '".mktime(23,59,59)."'",'limit'=>ceil(ProfileChild::getResultsPerPage()/2));
        $criteria->scopes=array('findAll'=>array($parameters));
		return $this->searchBase($criteria);
	}

	public function searchComplete() {
		$criteria=new CDbCriteria;
        if(!Yii::app()->user->checkAccess('ActionsAdmin')){
            $parameters=array("condition"=>"completedBy='".Yii::app()->user->getName()."' AND complete='Yes'","limit"=>ceil(ProfileChild::getResultsPerPage()/2));
            $criteria->scopes=array('findAll'=>array($parameters));
        }
		return $this->searchBase($criteria);
	}

	public function searchAll() {
		$criteria=new CDbCriteria;
        $parameters=array("condition"=>"(assignedTo='".Yii::app()->user->getName()."' OR assignedTo IN (SELECT groupId FROM x2_group_to_user WHERE userId='".Yii::app()->user->getId()."'))",'limit'=>ceil(ProfileChild::getResultsPerPage()/2));
        $criteria->scopes=array('findAll'=>array($parameters));
		return $this->searchBase($criteria);
	}
	
	public function searchGroup() {
		$criteria=new CDbCriteria;
        if(!Yii::app()->user->checkAccess('ActionsAdmin')){
            $parameters=array("condition"=>"(visibility='1' OR assignedTo='".Yii::app()->user->getName()."' OR assignedTo IN (SELECT groupId FROM x2_group_to_user WHERE userId='".Yii::app()->user->getId()."')) AND complete!='Yes'",'limit'=>ceil(ProfileChild::getResultsPerPage()/2));
            $criteria->scopes=array('findAll'=>array($parameters));
        }
		return $this->searchBase($criteria);
	}
	
	public function searchAllGroup() {
		$criteria=new CDbCriteria;
        if(!Yii::app()->user->checkAccess('ActionsAdmin')){
            $parameters=array("condition"=>"(visibility='1' OR assignedTo='".Yii::app()->user->getName()."' OR assignedTo IN (SELECT groupId FROM x2_group_to_user WHERE userId='".Yii::app()->user->getId()."'))",'limit'=>ceil(ProfileChild::getResultsPerPage()/2));
            $criteria->scopes=array('findAll'=>array($parameters));
        }
		return $this->searchBase($criteria);
	}

	public function searchAllComplete() {
		$criteria=new CDbCriteria;
        if(!Yii::app()->user->checkAccess('ActionsAdmin')){
            $parameters=array("condition"=>"(visibility='1' OR assignedTo='".Yii::app()->user->getName()."' OR assignedTo IN (SELECT groupId FROM x2_group_to_user WHERE userId='".Yii::app()->user->getId()."')) AND complete='Yes'",'limit'=>ceil(ProfileChild::getResultsPerPage()/2));
            $criteria->scopes=array('findAll'=>array($parameters));
        }
		return $this->searchBase($criteria);
	}

	public function searchAdmin() {
		$criteria=new CDbCriteria;

		return $this->searchBase($criteria);
	}
	
	public function searchBase($criteria) {
		
		$fields=Fields::model()->findAllByAttributes(array('modelName'=>'Actions'));
		foreach($fields as $field){
			$fieldName=$field->fieldName;
			switch($field->type){
				case 'boolean':
					$criteria->compare($field->fieldName,$this->compareBoolean($this->$fieldName), true);
					break;
				case 'link':
					$criteria->compare($field->fieldName,$this->compareLookup($field, $this->$fieldName), true);
					break;
				case 'assignment':
					$criteria->compare($field->fieldName,$this->compareAssignment($this->$fieldName), true);
					break;
				default:
					$criteria->compare($field->fieldName,$this->$fieldName,true);
			}
			
		}
		
		$criteria->addCondition('(type != "workflow" AND type!="email" AND type!="event" AND type!="emailFrom") OR type IS NULL');
		
		
		$dataProvider=new SmartDataProvider('Actions', array(
			'sort'=>array(
				'defaultOrder'=>'completeDate DESC, dueDate DESC',
			),
			'pagination'=>array(
				'pageSize'=>ceil(ProfileChild::getResultsPerPage())
			),
			'criteria'=>$criteria
		));
	
		return $dataProvider;
	}
	protected function compareLookup($field, $data){
		if(is_null($data) || $data=="") return null; 
		$type=ucfirst($field->linkType);
		if($type=='Contacts'){
			eval("\$lookupModel=$type::model()->findAllBySql('SELECT * FROM x2_$field->linkType WHERE CONCAT(firstName,\' \', lastName) LIKE \'%$data%\'');");
		}else{
			eval("\$lookupModel=$type::model()->findAllBySql('SELECT * FROM x2_$field->linkType WHERE name LIKE \'%$data%\'');");
		}
		if(isset($lookupModel) && count($lookupModel)>0){
			$arr=array();
			foreach($lookupModel as $model){
				$arr[]=$model->id;
			}
			return $arr;
		}else
			return -1;
	}
	
	protected function compareBoolean($data){
		if(is_null($data) || $data=='') return null;
		if(is_numeric($data)) return $data;
		if($data==Yii::t('actions',"Yes"))
			return 1;
		elseif($data==Yii::t('actions',"No"))
			return 0;
		else
			return -1;
	}
	
	protected function compareAssignment($data){
		if(is_null($data)) return null;
		if(is_numeric($data)){
			$models=Groups::model()->findAllBySql("SELECT * FROM x2_groups WHERE name LIKE '%$data%'");
			$arr=array();
			foreach($models as $model){
				$arr[]=$model->id;
			}
			return count($arr)>0?$arr:-1;
		}else{
			$models=User::model()->findAllBySql("SELECT * FROM x2_users WHERE CONCAT(firstName,' ',lastName) LIKE '%$data%'");
			$arr=array();
			foreach($models as $model){
				$arr[]=$model->username;
			}
			return count($arr)>0?$arr:-1;
		}
	}

	public function syncGoogleCalendar($operation) {
		$profiles = array();
		
		if(!is_numeric($this->assignedTo)) {	// assigned to user
			$profiles[] = CActiveRecord::model('Profile')->findByAttributes(array('username'=>$this->assignedTo));
		} else {	// Assigned to group
			$groups = Yii::app()->db->createCommand()
				->select('userId')
				->from('x2_group_to_user')
				->where('groupId=:assignedTo',array(':assignedTo'=>$this->assignedTo))
				->queryAll();
			foreach($groups as $group)
				$profile[] = CActiveRecord::model('Profile')->findByPk($group['userId']);
		}
		
		foreach($profiles as &$profile) {
			if($profile !== null) {
				if($operation === 'create')
					$profile->syncActionToGoogleCalendar($this);	// create action to Google Calendar
				elseif($operation === 'update')
					$profile->deleteGoogleCalendarEvent($this);	// update action to Google Calendar
				elseif($operation === 'delete')
					$profile->updateGoogleCalendarEvent($this); // delete action in Google Calendar
			}
		}
	}
}
