<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Language EN strings for the coursebadges module.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['available_badges'] = 'Available badges';

$string['btn_change_badge_selections'] = 'Edit badges';
$string['btn_save_selected_badge'] = 'Save my choice';

$string['cbx_allow_modifications_choice'] = 'Allow the edit of choice (by default : yes)';
$string['cbx_always_show_results'] = 'Always show the result for users.';
$string['cbx_no_notification'] = 'No notification';
$string['cbx_no_publish_results'] = 'Don\'t publish the results for users.';
$string['cbx_show_course_badges_block'] = 'Show the course badges block';
$string['cbx_show_results_after_response'] = 'Show the results for users after their answer';
$string['cbx_updated_choice_notification'] = 'When a user edit his choice';
$string['cbx_validated_choice_notification'] = 'When a user validates his choice';
$string['changeselectedbadges'] = 'Change selected badges';
$string['changebadgeselectionsgroup'] = 'Change selected badges';
$string['changebadgeselectionsgroup_help'] = 'Warning! This action will remove all the chosen badges for every users in this course.';
$string['completionvalidatedbadges'] = 'Users must validate their course badges choice';
$string['course_badges'] = 'Course badges';
$string['coursebadges:addinstance'] = 'Add a new course badges instance';
$string['coursebadges:choose'] = 'Save a choice';
$string['coursebadges:deletechoice'] = 'Delete answers';
$string['coursebadges:viewbadgesoverview'] = 'See overview of badges' ;
$string['coursebadges:viewusersoverview'] = 'See overview of users';
$string['coursebadges_notification_task'] = 'Notification system for the course badges activity';

$string['criteria'] = 'Criteria';
$string['description'] = 'Description';

$string['email_updated_message_intro'] = '<p>Hello {$a->username}, <br/>
You have received this notification because the user <b>{$a->affectedusername}</b> has changed his badge choices in the the course "{$a->coursename}" by choosing the following badges : <br/></p>';
$string['email_validated_message_intro'] = '<p>Hello {$a->username}, <br/>
You have received this notification because the user <b>{$a->affectedusername}</b> has validated his badge choices in the course "{$a->coursename}" with the following badges : <br/></p>';
$string['email_message_outro'] = '<br/><br/>
<p>You can go to the activity concerned by clicking on the following link : <a href="{$a->activitylink}">{$a->activityname}</a><br/>
We hope to see you soon. <br/><br/></p>';

$string['error_configuration_activity_notif'] = 'The form could not be validated because the condition(s) were not met : <ul>
{$a->ruleminallowbadge}
{$a->rulemaxallowbadge}
</ul>';
$string['error_rule_min_allow_badge'] = '<li>The number of badge choices must be less than or equal to {$a}.</li>';
$string['error_rule_max_allow_badge'] = '<li>The number of badge choices must be greater than or equal to {$a}.</li>';

$string['dateformat'] = 'mm-dd-yy';

$string['label_activity_management'] = 'Activity settings';
$string['label_badges_management'] = 'Badges management';
$string['label_change_badge_selections'] = 'Change selected badges';
$string['label_manage_badges'] = 'Badges management';
$string['label_notification'] = 'Notification (by default : No notification)';
$string['label_show_awarded_results'] = 'Publish the results (show users who have selected badges)';

$string['manage_badges_link'] = 'Manage badges of course';
$string['modulename'] = 'Course badges';
$string['modulename_help'] = 'The course badges module allows users to set objectives for obtaining one or more badges. 
The teacher sets the badge(s) that can be chosen by the students from among those available in the course. 
Users can follow the obtaining of the badges they have retained. Depending on the settings, they also have access to the choices of the other users. 
Teachers have access to a synthetic view of the badges chosen and obtained by the users. 
Depending on his settings, this activity is associated with a block that is automatically added in the side columns of the course.';
$string['modulenameplural'] = 'Course badges';

$string['nobadgeincourse'] = 'No badge exists in this course .';
$string['notification_add_choice'] = 'The badge choices has been made.';
$string['notification_delete_user_choice'] = 'Badge choices for this activity have been successfully removed.';
$string['notification_error_add_choice'] = 'You don\'t have the required rights to add a badge choices';
$string['notification_error_delete_choice'] = 'You don\'t have the required rights to remove a badge choices';
$string['notification_updated_choice'] = 'Editing badges on the activity {$a}';
$string['notification_validated_choice'] = 'Validation of badges on the activity {$a}';
$string['obtained_badges'] = 'Badges earned';
$string['pleasesetonebadgeor'] = 'Please create at least one badge for this course.
<ul>
<li><a href="{$a->linkbadges}">Manage badges</a></li>
<li><a href="{$a->linkcourse}">Back to the course</a></li>
</ul>';

$string['pluginadministration'] = 'Administration Course badges';
$string['pluginname'] = 'Course badges';

$string['pre_select_badges'] = 'Pre-selected badges';
$string['privacy:metadata:coursebadges_usr_select_bdg'] = 'Information about pre-selected badges in this activity.';
$string['privacy:metadata:coursebadges_usr_select_bdg:userid'] = 'User ID who pre-selected the badge.';
$string['privacy:metadata:coursebadges_usr_select_bdg:selectionbadgeid'] = 'Pre-selected badge ID chosen by the user.';

$string['selected_badges'] = 'Selected badges';

$string['txt_badges_max_required'] = 'Maximum required number of badges to select (by default : none)';
$string['txt_badges_min_required'] = 'Minimum required number of badges to select (by default : none)';
$string['txt_course_badges'] = 'Activity name';

$string['warning_configuration_activity_notif'] = 'This activity has been configured as follows: 
<ul>
{$a->ruleallowmodif}
{$a->ruleminallowbadge}
{$a->rulemaxallowbadge}
</ul>';
$string['warning_rule_allow_modif'] = '<li>Once the badge choices has been validated, the modification of the badge choices is refused .</li>';
$string['warning_rule_max_allow_badge'] = '<li>The maximum number of authorized badge choices is  {$a}.</li>';
$string['warning_rule_min_allow_badge'] = '<li>The minimum number of authorized badge choices is  {$a}.</li>';

/***********************/
/* Overview Traduction */
/***********************/

$string['badgefieldsearch'] = 'Badge';
$string['badgesoverviewlink'] = 'Badges overview';
$string['badgeoverviewtitle'] = 'Badges overview';
$string['descriptioncolumn'] = 'Description';
$string['earnedbadgescolumn'] = 'Badges earned';
$string['earnedbadgelabel'] = 'Earned';
$string['filtertitle'] = 'Global badges overview';
$string['firstnamecolumn'] = 'Firstname';
$string['groupfieldsearch'] = 'Group';
$string['groupnamecolumn'] = 'Group';
$string['imagecolumn'] = 'Image';
$string['lastnamecolumn'] = 'Lastname';
$string['namecolumn'] = 'Name';
$string['modcolumn'] = 'Course badges activity\'';
$string['modnamefieldsearch'] = 'Course badges activity\'';
$string['nobadgeoverview'] = '<p>You cannot enter the badges overview.</p>
<p>You don\'t have the required rights to view this page.</p>';
$string['nouseroverview'] = '<p>You cannot enter the users overview.</p>
<p>You don\'t have the required rights to view this page.</p>';
$string['usersoverviewlink'] = 'Users overview';
$string['useroverviewtitle'] = 'Users overview';
$string['percentcolumn'] = 'Achievement of objectives';
$string['ratiocolumn'] = 'Earned';
$string['selectedbadgescolumn'] = 'Selected badges';
$string['selectedbadgelabel'] = 'Selected';
$string['statuslabel'] = 'Status';
$string['usernamefield'] = 'Lastname/Firstname';
