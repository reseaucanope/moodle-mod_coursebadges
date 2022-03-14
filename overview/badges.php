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
 * Display the badges overview.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once($CFG->dirroot.'/mod/coursebadges/overview/form/badges_overview_form.php');

$id = required_param('id', PARAM_INT); // cm id

if (!$cm = get_coursemodule_from_id('coursebadges', $id)) {
    print_error('Course Module ID was incorrect'); // NOTE this is invalid use of print_error, must be a lang string id
}
if (!$course = $DB->get_record('course', ['id'=> $cm->course])) {
    print_error('course is misconfigured');  // NOTE As above
}

require_course_login($course, true, $cm);

if (!$course_badge = $DB->get_record('coursebadges', ['id'=> $cm->instance])) {
    print_error('course module is incorrect'); // NOTE As above
}

$params = [];
if ($id) {
    $params['id'] = $id;
}

$context = context_module::instance($cm->id);

$canviewbadgesoverview = has_capability('mod/coursebadges:viewbadgesoverview', $context);
if (!$canviewbadgesoverview) {
    print_error('nobadgeoverview', 'coursebadges', $CFG->wwwroot.'/course/view.php?id='.$course->id);
}

$PAGE->set_pagelayout('incourse');

$PAGE->set_url('/mod/coursebadges/overview/badges.php', $params);
$PAGE->set_title($course_badge->name);
$PAGE->set_heading($course->fullname);

$canviewbadgesoverview = has_capability('mod/coursebadges:viewbadgesoverview', $context);

$PAGE->navbar->add(get_string('badgeoverviewtitle', 'mod_coursebadges'));

$ajaxbaseurl = new moodle_url('/mod/coursebadges/overview/ajax.php', [
    'id' => $id,
    'courseid' => $course->id,
    'action' => 'list_badges'
]);

$PAGE->requires->js_call_amd('mod_coursebadges/course_badges', 'initJtable', [
    'jtablecolumns', 'results', $ajaxbaseurl->out(false), 'name ASC'
]);

$PAGE->requires->js_call_amd('mod_coursebadges/course_badges', 'initSelectOverview');

$badges_overview_form = new badges_overview_form(null, ['cmid' => $cm->id, 'courseid' => $cm->course]);

echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($course_badge->name), 2);

$badges_overview_form->display();

echo $OUTPUT->footer($course);
