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
INSERT INTO x2_dropdowns (`id`, `name`, `options`) VALUES

(100,	'Product Status',	'{"Active":"Active","Inactive":"Inactive"}'),
(101,	'Currency List',	'{"USD":"USD","EUR":"EUR","GBP":"GBP","CAD":"CAD","JPY":"JPY","CNY":"CNY","CHF":"CHF","INR":"INR","BRL":"BRL"}'),
(102,	'Lead Type',		'{"None":"None","Web":"Web","In Person":"In Person","Phone":"Phone","E-Mail":"E-Mail"}'),
(103,	'Lead Source',		'{"None":"None","Google":"Google","Facebook":"Facebook","Walk In":"Walk In"}'),
(104,	'Lead Status',		'{"Unassigned":"Unassigned","Assigned":"Assigned","Accepted":"Accepted","Working":"Working","Dead":"Dead","Rejected":"Rejected"}'),
(105,	'Sales Stage',		'{"Working":"Working","Won":"Won","Lost":"Lost"}'),
(106,	'Quote Status',		'{"Draft":"Draft","Presented":"Presented","Issued":"Issued","Won":"Won"}'),
(107,	'Campaign Type',	'{"Email":"Email","Call List":"Call List","Physical Mail":"Physical Mail"}'),
(108,	'Case Impact', 		'{"1 - Severe":"1 - Severe"," 2 - Critical":" 2 - Critical","3 - Moderate":"3 - Moderate","4 - Minor":"4 - Minor"}'),
(109,	'Case Status', 		'{"New":"New","WIP":"WIP","Waiting for response":"Waiting for response","Needs more info":"Needs more info","Escalated":"Escalated","Reopened":"Reopened","Work around provided, waiting for fix":"Work around provided, waiting for fix","Program Manager investigation":"Program Manager investigation","Closed - Resolved":"Closed - Resolved","Closed - No Response":"Closed - No Response"}'),
(110,	'Case Main Issue',	'{"Hardware":"Hardware","Software":"Software","Internet Connection":"Internet Connection","LMS":"LMS","General Request":"General Request"}'),
(111,	'Case Sub Issue', 	'{"Laptop":"Laptop","Desktop":"Desktop","WiFi":"WiFi","Loss Connection":"Loss Connection","Windows OS":"Windows OS","MS Office":"MS Office","Class Access":"Class Access","Lost Password":"Lost Password","Download\\/Upload":"Download\\/Upload","Other":"Other"}'),
(112,	'Case Origin', 		'{"Email":"Email","Web":"Web","Phone":"Phone"}'),
(113,	'Social Subtypes',	'{"Social Post":"Social Post","Link":"Link","Announcement":"Announcement","Product Info":"Product Info","Competitive Info":"Competitive Info","Confidential":"Confidential"}'),
(114,	'Invoice Status',	'{"Pending":"Pending","Issued":"Issued","Paid":"Paid","Open":"Open","Canceled":"Canceled","Other":"Other"}'),
(115,	'Bug Status',       '{"Unconfirmed":"Unconfirmed","Confirmed":"Confirmed","In Progress":"In Progress","Closed (Resolved Internally)":"Closed (Resolved Internally)","Closed (Unable to Reproduce)":"Closed (Unable to Reproduce)","Closed (Duplicate)":"Closed (Duplicate)","Merged Into Base Code":"Merged Into Base Code"}'),
(116,	'Bug Severity',     '{"5":"Blocker","4":"Critical","3":"Major","2":"Normal","1":"Minor","0":"Feature Request"}');
/*&*/
ALTER TABLE x2_profile CHANGE `language` language varchar(40) DEFAULT '{language}', CHANGE `timeZone` timeZone varchar(100) DEFAULT '{timezone}';
/*&*/
ALTER TABLE x2_admin CHANGE `emailFromAddr` emailFromAddr varchar(255) NOT NULL DEFAULT '{bulkEmail}';
/*&*/
INSERT INTO x2_users (firstName, lastName, username, password, emailAddress, status, lastLogin, userKey) 
        VALUES ('web','admin','admin','{adminPass}','{adminEmail}','1', '0', '{adminUserKey}');
/*&*/
INSERT INTO x2_users (firstName, lastName, username, password, emailAddress, status, lastLogin) 
        VALUES ('API','User','api','{apiKey}','{adminEmail}' ,'0', '0');
/*&*/
INSERT INTO x2_profile (fullName, username, emailAddress, status) 
		VALUES ('Web Admin', 'admin', '{adminEmail}','1');
/*&*/
INSERT INTO x2_profile (fullName, username, emailAddress, status)
		VALUES ('API User', 'api', '{adminEmail}','0');
/*&*/
INSERT INTO x2_social (`type`, `data`) VALUES ('motd', 'Please enter a message of the day!');
/*&*/
INSERT INTO x2_admin (timeout,webLeadEmail,emailFromAddr,currency,installDate,updateDate,quoteStrictLock,unique_id,edition,serviceCaseFromEmailAddress,serviceCaseFromEmailName,serviceCaseEmailSubject,serviceCaseEmailMessage,eventDeletionTime,eventDeletionTypes) VALUES (
	'3600',
	'{adminEmail}',
	'{bulkEmail}',
	'{currency}',
	'{time}',
	0,
	0,
	'{unique_id}',
	'{edition}',
	'{adminEmail}',
	'Tech Support',
	'Tech Support',
	'Hello {first} {last},\n\nJust wanted to check in with you about the support case you created. It is number {case}. We will get back to you as soon as possible.',
    7,
    '["record_create","record_deleted","action_reminder","action_complete","calendar_event","case_escalated","email_opened","email_sent","notif","weblead_create","web_activity","workflow_complete","workflow_revert","workflow_start"]'
);
/*&*/
UPDATE x2_profile SET `widgets`='0:1:1:1:1:0:0:0:0:0:0:0:0',
	`widgetOrder`='OnlineUsers:TimeZone:GoogleMaps:TagCloud:TwitterFeed:MessageBox:ChatBox:QuickContact:NoteBox:ActionMenu:MediaBox:DocViewer:TopSites';
/*&*/
UPDATE `x2_auth_item` SET `bizrule`="return Yii::app()->user->name === '{adminUsername}';" WHERE `name`='admin';
/*&*/
UPDATE `x2_users` SET `username`='{adminUsername}' WHERE `username`='admin';
/*&*/
UPDATE `x2_profile` SET `username`='{adminUsername}' WHERE `username`='admin';
/*&*/
UPDATE `x2_modules` SET `visible`=0
/*&*/
UPDATE `x2_modules` SET `visible`=1 WHERE `name` IN {visibleModules}