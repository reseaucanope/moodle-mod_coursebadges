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
 * Badges_overview form file.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/mod/coursebadges/overview/classes/badges_overview_data.php');
require_once($CFG->dirroot.'/mod/coursebadges/overview/classes/mod_filters.php');
require_once($CFG->dirroot.'/mod/coursebadges/overview/classes/utils.php');

/**
 * Class badges_overview_form.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class badges_overview_form extends moodleform {

    const MODNAME_SEARCH_FIELD = 'modnamesearch';

    public function definition() {
        $mform =& $this->_form;

        $cmid = $this->_customdata['cmid'];

        $mform->disable_form_change_checker();
        
        $mform->addElement('header', 'filter', get_string('badgeoverviewtitle', 'mod_coursebadges'));

        utils::html_input_data($mform, 'jtablecolumns', badges_overview_data::get_jtable_columns());

        $mform->addElement('hidden', 'id', $cmid);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'courseid', $this->_customdata['courseid']);
        $mform->setType('courseid', PARAM_INT);

        $mform->addElement('html', '<div id="results"></div>');
    }

}
