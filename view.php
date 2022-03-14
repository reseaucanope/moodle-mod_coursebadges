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
 * Display the course badges module containing with badges chosen by user.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(__DIR__)).'/config.php');
require_once($CFG->dirroot.'/mod/coursebadges/lib.php');
require_once($CFG->dirroot.'/mod/coursebadges/classes/course_badges.php');
require_once($CFG->dirroot.'/mod/coursebadges/classes/course_badges_notification.php');
require_once($CFG->dirroot.'/mod/coursebadges/form/choicebadges_form.php');

$id = required_param('id', PARAM_INT); // Course Module ID

if (!$cm = get_coursemodule_from_id('coursebadges', $id)) {
    print_error('Course Module ID was incorrect'); // NOTE this is invalid use of print_error, must be a lang string id
}
if (!$course = $DB->get_record('course', ['id'=> $cm->course])) {
    print_error('course is misconfigured');  // NOTE As above
}
if (!$course_badge = $DB->get_record('coursebadges', ['id'=> $cm->instance])) {
    print_error('course module is incorrect'); // NOTE As above
}

$params = [];
if ($id) {
    $params['id'] = $id;
}

$PAGE->set_url('/mod/coursebadges/view.php', $params);
require_course_login($course, true, $cm);

$context = context_module::instance($cm->id);
$PAGE->set_context($context);

$PAGE->set_title($course_badge->name);
$PAGE->set_heading($course->fullname);

$formData = ['id' => $id,
    'contextid' => $context->id,
    'coursebadgesid' => $course_badge->id
];

$form = new choicebadges_form(null, $formData);
if ($form->get_data()) {
    $cb = new course_badges($course_badge->id, $form->get_data());
    $cb_data = $cb->get_course_badges_instance();

    $minbadgerequired = $cb_data->badgesminrequired;
    $maxbadgerequired = $cb_data->badgesmaxrequired;

    $cbselected = explode(",", $form->get_data()->rightlistids);

    if ((count($cbselected) < $minbadgerequired && $minbadgerequired > 0)
        || (count($cbselected) > $maxbadgerequired) && $maxbadgerequired > 0) {
        $message = "";
        $a = new stdClass();
        $a->ruleminallowbadge = "";
        $a->rulemaxallowbadge = "";
        if (count($cbselected) < $minbadgerequired) {
            $a->ruleminallowbadge = get_string('error_rule_min_allow_badge', 'coursebadges', $minbadgerequired);
        }
        if (count($cbselected) > $maxbadgerequired) {
            $a->rulemaxallowbadge = get_string('error_rule_max_allow_badge', 'coursebadges', $maxbadgerequired);
        }
        $message = get_string('error_configuration_activity_notif', 'coursebadges', $a);

        \core\notification::add($message, \core\notification::ERROR);
    } else {
        if ($cb->create_selected_badges_instances()) {
            $notif_badges = new course_badges_notification($course_badge->id);
            $notif_badges->send_notification();
            $action = new moodle_url('/mod/coursebadges/view.php', array('id' => $cm->id));
            $action = $action->out(false);
            \core\notification::fetch();
            redirect($action, get_string('notification_add_choice', 'coursebadges'), null, \core\output\notification::NOTIFY_SUCCESS);
        }
    }
}

echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($course_badge->name), 2);

if (!empty($course_badge->intro)) {
    echo $OUTPUT->box(format_module_intro('coursebadges', $course_badge, $cm->id), 'generalbox', 'intro');
}

echo $form->render();

$canviewbadgesoverview = has_capability('mod/coursebadges:viewbadgesoverview', $context);

if ($canviewbadgesoverview) {
    $url = new moodle_url('/mod/coursebadges/overview/badges.php', ['id' => $cm->id]);
    echo html_writer::link($url, get_string('badgesoverviewlink', 'mod_coursebadges'));
    echo html_writer::empty_tag('br');
}

if (has_capability('mod/coursebadges:viewusersoverview', $context)) {
    $canviewusersoverview = true;
} else {
    if ($course_badge->showawardedresults == course_badges::ALWAYS_SHOW_RESULTS) {
        $canviewusersoverview = true;
    } else if ($course_badge->showawardedresults == course_badges::SHOW_RESULTS_AFTER_RESPONSE) {
        if (course_badges::has_selected_badges($course_badge->id)) {
            $canviewusersoverview = true;
        } else {
            $canviewusersoverview = false;
        }
    } else {
        $canviewusersoverview = false;
    }
}

if ($canviewusersoverview) {
    $url = new moodle_url('/mod/coursebadges/overview/users.php', ['id' => $cm->id]);
    echo html_writer::link($url, get_string('usersoverviewlink', 'mod_coursebadges'));
    echo html_writer::empty_tag('br');
}

echo $OUTPUT->footer($course);
