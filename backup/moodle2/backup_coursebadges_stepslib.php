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
 * Define all the backup steps that will be used by the backup_coursebadges_activity_task.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class backup_coursebadges_activity_structure_step.
 *
 * Define the complete coursebadges structure for backup, with file and id annotations.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_coursebadges_activity_structure_step extends backup_activity_structure_step {
    protected function define_structure() {

        // Define each element separated.
        $coursebadges = new backup_nested_element('coursebadges', ['id'],
            ['type', 'name', 'intro', 'introformat','course',
            'assessed', 'notification', 'badgesminrequired', 'badgesmaxrequired',
            'allowmodificationschoice', 'showawardedresults', 'timemodified']);

        if ($this->get_setting_value('badges')) {
            $selection_badges = new backup_nested_element('selection_badges');
            $selection_badge = new backup_nested_element('selection_badge', ['id'],
                ['coursebadgeid', 'badgeid', 'timemodified']);
        }

        // Build the tree
        if ($this->get_setting_value('badges')) {
            $coursebadges->add_child($selection_badges);
            $selection_badges->add_child($selection_badge);
        }

        // Define sources
        $coursebadges->set_source_table('coursebadges', ['id' => backup::VAR_ACTIVITYID]);

        if ($this->get_setting_value('badges')) {
            // Need posts ordered by id so parents are always before childs on restore
            $selection_badge->set_source_table('coursebadges_available_bdg', ['coursebadgeid' => backup::VAR_PARENTID], 'id ASC');

            // Define id annotations
            $selection_badge->annotate_ids('badge', 'badgeid');
        }

        // Define file annotations
        $coursebadges->annotate_files('mod_coursebadges', 'intro', null); // This file area hasn't itemid

        // Return the root element (coursebadges), wrapped into standard activity structure
        return $this->prepare_activity_structure($coursebadges);
    }
}