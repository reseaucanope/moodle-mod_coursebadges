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
 * Mod filters class file.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot.'/lib/badgeslib.php');

/**
 * Class mod_filters.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_filters {
    public static function get_list_badges($cmid) {
        global $DB;

        return $DB->get_records_sql('SELECT b.id, b.name  
FROM {badge} b
INNER JOIN {coursebadges_available_bdg} csb ON csb.badgeid=b.id
INNER JOIN {course_modules} cm ON cm.instance=csb.coursebadgeid
WHERE cm.module=(SELECT id FROM {modules} WHERE name = "coursebadges")
AND cm.id=?
AND (b.status=? OR b.status=?)
ORDER BY b.name', [$cmid, BADGE_STATUS_ACTIVE, BADGE_STATUS_ACTIVE_LOCKED]);

    }

    public static function get_list_mod_badges($courseid) {
        global $DB;

        return $DB->get_records('coursebadges', ['course' => $courseid], 'name ASC', 'id,name');
    }

    public static function get_groups_list($courseid, $cmid = null) {
        global $DB, $USER;
        
        if (!$cm = get_coursemodule_from_id('coursebadges', $cmid, $courseid)) {
            print_error('invalidcoursemodule');
        }

        $context = context_module::instance($cm->id);

        $groupmode = groups_get_activity_groupmode($cm);
        if ($groupmode == SEPARATEGROUPS && !has_capability('moodle/site:accessallgroups', $context)) {
            return groups_get_all_groups($cm->course, $USER->id, $cm->groupingid);
        }

        return $DB->get_records('groups', ['courseid' => $courseid], 'name ASC', 'id,name');
    }
    
}