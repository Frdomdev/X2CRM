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

Yii::app()->clientScript->registerScript('deleteActionJs',"
function deleteAction(actionId) {

	if(confirm('".Yii::t('app','Are you sure you want to delete this item?')."')) {
		$.ajax({
			url: '" . CHtml::normalizeUrl(array('/actions/actions/delete')) . "/'+actionId+'?ajax=1',
			type: 'POST',
			//data: 'id='+actionId,
			success: function(response) {
				if(response=='Success')
					$('#history-'+actionId).fadeOut(200,function() { $('#history-'+actionId).remove(); });
				}
		});
	}
}
",CClientScript::POS_HEAD);
$themeUrl = Yii::app()->theme->getBaseUrl();
if(empty($data->type)) {
	if($data->complete=='Yes')
		$type = 'complete';
	else if($data->dueDate < time())
		$type = 'overdue';
	else
		$type = 'action';
} else
	$type = $data->type;

if($type == 'workflow') {

	$workflowRecord = X2Model::model('Workflow')->findByPk($data->workflowId);
	$stageRecords = X2Model::model('WorkflowStage')->findAllByAttributes(
		array('workflowId'=>$data->workflowId),
		new CDbCriteria(array('order'=>'id ASC'))
	);
	
	// see if this stage even exists; if not, delete this junk
	if($workflowRecord === null || $data->stageNumber < 1 || $data->stageNumber > count($stageRecords)) {
		$data->delete();
		return;
	}
}

// if($type == 'call') {
	// $type = 'note';
	// $data->type = 'note';
// }

?>



<div class="view" id="history-<?php echo $data->id; ?>">
	<!--<div class="deleteButton">
		<?php //echo CHtml::link('[x]',array('deleteNote','id'=>$data->id)); //,array('class'=>'x2-button') ?>
	</div>-->
	<div class="icon <?php echo $type; ?>"></div>
	<div class="header">
		<?php
		if(empty($data->type) || $data->type=='weblead') {
			if ($data->complete=='Yes') {
				echo CHtml::link(Yii::t('actions','Action').':',array('/actions/'.$data->id)).' ';
				echo Yii::t('actions','Completed {date}',array('{date}'=>Actions::formatCompleteDate($data->completeDate)));
			} else {
				echo '<b>'.CHtml::link(Yii::t('actions','Action').':',array('/actions/'.$data->id)).' ';
				echo Actions::parseStatus($data->dueDate).'</b>';
			}
		} elseif ($data->type == 'attachment') {
			if($data->completedBy=='Email')
				echo Yii::t('actions','Email Message:').' '.Actions::formatCompleteDate($data->completeDate);
			else
				echo Yii::t('actions','Attachment:').' '.Actions::formatCompleteDate($data->completeDate);
				//User::getUserLinks($data->completedBy);
				
			echo ' ';
			
			//if ($data->complete=='Yes')
				//echo Actions::formatDate($data->completeDate);
			//else
				//echo Actions::parseStatus($data->dueDate);
		} elseif ($data->type == 'workflow') {
			// $actionData = explode(':',$data->actionDescription);
			echo Yii::t('workflow','Workflow:').'<b> '.$workflowRecord->name .'/'.$stageRecords[$data->stageNumber-1]->name.'</b> ';
		} elseif(in_array($data->type,array('email','emailFrom'))) {
			echo Yii::t('actions','Email Message:').' '.Actions::formatCompleteDate($data->completeDate);
		} elseif($data->type == 'quotes') {
			echo Yii::t('actions','Quote:').' '.Actions::formatCompleteDate($data->createDate);
		} elseif($data->type == 'emailOpened') {
			echo Yii::t('actions', 'Email Opened:'). ' '.Actions::formatCompleteDate($data->completeDate);
		} elseif($data->type == 'webactivity') {
			echo Yii::t('actions','This contact visited your website');
		} elseif($data->type == 'note') {
			echo Actions::formatCompleteDate($data->completeDate);
		} elseif($data->type == 'call') {
			echo Yii::t('actions','Call:').' '.Actions::formatCompleteDate($data->completeDate); //Yii::app()->dateFormatter->format(Yii::app()->locale->getDateFormat("medium"),$data->completeDate);
		} elseif($data->type == 'event') {
			echo '<b>'.CHtml::link(Yii::t('calendar','Event').':',array('/actions/'.$data->id)).' ';
			if($data->allDay) {
				echo Yii::app()->controller->formatLongDate($data->dueDate);
				if($data->completeDate)
					echo ' - '. Yii::app()->controller->formatLongDate($data->completeDate);
			} else {
				echo X2Model::formatLongDateTime($data->dueDate);
				if($data->completeDate)
					echo ' - '. X2Model::formatLongDateTime($data->completeDate);
			}
			echo '</b>';
		}
		?>
		<div class="buttons">
			<?php
			if (empty($data->type) || $data->type=='weblead') {
				if ($data->complete=='Yes')
					echo CHtml::link(CHtml::image($themeUrl.'/images/icons/Uncomplete.png'),array('/actions/actions/uncomplete','id'=>$data->id,'redirect'=>1),array());
				else {
					echo CHtml::link(CHtml::image($themeUrl.'/images/icons/Complete.png'),array('/actions/actions/complete','id'=>$data->id,'redirect'=>1),array());
				}
			}
			if ($data->type != 'workflow'){
				echo $data->type!='attachment'?' '.CHtml::link(CHtml::image($themeUrl.'/images/icons/Edit.png'),array('/actions/actions/update','id'=>$data->id,'redirect'=>1),array()) . ' ':"";
				echo ' '.CHtml::link(CHtml::image($themeUrl.'/images/icons/Delete_Activity.png'),'#',array('onclick'=>'deleteAction('.$data->id.'); return false'));
			}
			?>
		</div>
	</div>
	<div class="description">
		<?php
		if($type=='attachment' && $data->completedBy!='Email')
			echo MediaChild::attachmentActionText(Yii::app()->controller->convertUrls($data->actionDescription),true,true);
		else if($type=='workflow') {
		
			if(!empty($data->stageNumber) && !empty($data->workflowId) && $data->stageNumber <= count($stageRecords)) {
				if($data->complete == 'Yes')
					echo ' <b>'.Yii::t('workflow','Completed').'</b> '.date('Y-m-d H:i:s',$data->completeDate);
				else
					echo ' <b>'.Yii::t('workflow','Started').'</b> '.date('Y-m-d H:i:s',$data->createDate);
			}
			if(isset($data->actionDescription))
				echo '<br>'.$data->actionDescription;
			
		} elseif($type=='webactivity') {
			if(!empty($data->actionDescription))
				echo $data->actionDescription,'<br>';
			echo date('Y-m-d H:i:s',$data->completeDate);
		} elseif(in_array($data->type,array('email','emailFrom')) || $type=='emailOpened') { 
            preg_match('/<b>(.*?)<\/b>(.*)/mis',$data->actionDescription,$matches);
            if(!empty($matches)) {
                $subject = $matches[1];
				$body = '';
			} else {
                $subject = "No subject found";
				$body = "(Error displaying email)";
			}
            if($type=='emailOpened'){
                echo "Contact has opened the following email:<br />";
            }
            echo '<strong>'.$subject.'</strong> '.$body;
			echo '<br /><br />'.CHtml::link('[View email]','#',array('onclick'=>'return false;','id'=>$data->id,'class'=>'email-frame'));
        } elseif($data->type == 'quotes') {
			echo CHtml::link('[View quote]', '#', array('onclick' => 'return false;', 'id' => $data->id, 'class' => 'quote-frame'));
		} else 
			echo Yii::app()->controller->convertUrls(CHtml::encode($data->actionDescription));	// convert LF and CRLF to <br />
		?>
	</div>
	<div class="footer">
	<?php if(empty($data->type) || $data->type=='weblead' || $data->type=='workflow') {
		if($data->complete == 'Yes') {
			echo Yii::t('actions','Completed by {name}',array('{name}'=>User::getUserLinks($data->completedBy)));
		} else {
			$userLink = User::getUserLinks($data->assignedTo);
			$userLink = empty($userLink)? Yii::t('actions','Anyone') : $userLink;
			echo Yii::t('actions','Assigned to {name}',array('{name}'=>$userLink));
		}
	} else if($data->type == 'note' || $data->type == 'call' || $data->type == 'emailOpened') {
		echo User::getUserLinks($data->completedBy);
		// echo ' '.Actions::formatDate($data->completeDate);
	} else if($data->type == 'attachment' && $data->completedBy!='Email') {
		echo Yii::t('media','Uploaded by {name}',array('{name}'=>User::getUserLinks($data->completedBy)));
	} else if(in_array($data->type,array('email','emailFrom')) && $data->completedBy!='Email') {
		echo Yii::t('media',($data->type=='email'?'Sent by {name}':'Sent to {name}'),array('{name}'=>User::getUserLinks($data->completedBy)));
	}
	?>
	</div>

</div>
<script>
    
</script>