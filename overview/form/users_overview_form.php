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
 * Users_overview form file.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/mod/coursebadges/overview/classes/users_overview_data.php');
require_once($CFG->dirroot.'/mod/coursebadges/overview/classes/mod_filters.php');

/**
 * Class users_overview_form.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class users_overview_form extends moodleform {

    const BADGEID_FIELD = 'badgeid';
    const MODID_FIELD = 'modid';
    const STATUS_FIELD = 'status';
    const GROUPID_FIELD = 'groupid';
    const USERNAME_FIELD = 'username';

    public function definition() {
        global $COURSE;

        $mform =& $this->_form;

        $mform->disable_form_change_checker();

        $cmid = $this->_customdata['cmid'];

        $mform->addElement('header', 'filter', get_string('useroverviewtitle', 'coursebadges'));

        $mform->addElement('text', self::USERNAME_FIELD, get_string('usernamefield', 'coursebadges'));
        $mform->setType(self::USERNAME_FIELD, PARAM_TEXT);

        $groups = mod_filters::get_groups_list($COURSE->id, $cmid);
        $this->add_select(self::GROUPID_FIELD, $groups, 'groupfieldsearch');

        $badges = mod_filters::get_list_badges($cmid);
        $this->add_select(self::BADGEID_FIELD, $badges, 'badgefieldsearch');

        $options = [
            -1 => get_string('all'),
            users_overview_data::EARNED_BADGES => get_string('earnedbadgelabel', 'coursebadges'),
            users_overview_data::SELECTED_BADGES => get_string('selectedbadgelabel', 'coursebadges'),
        ];
        $mform->addElement('select', self::STATUS_FIELD, get_string('statuslabel', 'coursebadges'), $options);

        Utils::html_input_data($mform, 'jtablecolumns', users_overview_data::get_jtable_columns());

        $mform->addElement('hidden', 'id', $cmid);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'courseid', $this->_customdata['courseid']);
        $mform->setType('courseid', PARAM_INT);

        $mform->addElement('html', '<div id="results"></div>');
    }

    private function add_select($name, $list, $label) {
        $options = [-1 => get_string('all')];
        foreach ($list as $l) {
            $options[$l->id] = $l->name;
        }
        $this->_form->addElement('select', $name, get_string($label, 'coursebadges'), $options);
    }
}
