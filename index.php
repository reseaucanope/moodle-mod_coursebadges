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
 * Displays information about all coursebadges modules in the requested course.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(__DIR__)).'/config.php');
require_once(__DIR__.'/lib.php');

$id = required_param('id', PARAM_INT);

$course = $DB->get_record('course', ['id' => $id], '*', MUST_EXIST);
require_course_login($course);

$strsectionname = get_string('sectionname', 'format_'.$course->format);
$strname = get_string('name');
$strintro = get_string('moduleintro');
$strlastmodified = get_string('lastmodified');

$coursecontext = context_course::instance($course->id);

$event = \mod_coursebadges\event\course_module_instance_list_viewed::create(['context' => $coursecontext]);
$event->add_record_snapshot('course', $course);
$event->trigger();

$PAGE->set_url('/mod/coursebadges/index.php', ['id' => $id]);
$PAGE->set_title(format_string($course->fullname));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($coursecontext);

echo $OUTPUT->header();

$modulenameplural = get_string('modulenameplural', 'mod_coursebadges');
echo $OUTPUT->heading($modulenameplural);

$allcoursebadges = get_all_instances_in_course('coursebadges', $course);

if (empty($allcoursebadges)) {
    notice(get_string('thereareno', 'moodle'), new moodle_url('/course/view.php', ['id' => $course->id]));
    exit;
}

$usesections = course_format_uses_sections($course->format);

$table = new html_table();
$table->attributes['class'] = 'generaltable mod_index';

if ($usesections) {
    $table->head = array($strsectionname, $strname, $strintro);
    $table->align = array('center', 'left', 'left');
} else {
    $table->head = array($strlastmodified, $strname, $strintro);
    $table->align = array('left', 'left', 'left');
}

$modinfo = get_fast_modinfo($course);

foreach ($allcoursebadges as $coursebadges) {
    $attributes = [];
    if (!$coursebadges->visible) {
        $attributes['class'] = 'dimmed';
    }
    $link = html_writer::link(
        new moodle_url('/mod/coursebadges/view.php', ['id' => $coursebadges->coursemodule]),
        format_string($coursebadges->name, true),
        $attributes);

    if ($usesections) {
        $printsection = '';
        if ($coursebadges->section) {
            $printsection = get_section_name($course, $coursebadges->section);
        }
    } else {
        $printsection = '<span class="smallinfo">'.userdate($coursebadges->timemodified)."</span>";
    }
    $cm = $modinfo->cms[$coursebadges->coursemodule];
    $intro = format_module_intro('coursebadges', $coursebadges, $cm->id);
    $table->data[] = [$printsection, $link, $intro];
}

echo html_writer::table($table);
echo $OUTPUT->footer();

