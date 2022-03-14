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
 * Choicebadges_form class file.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/mod/coursebadges/lib.php');
require_once($CFG->dirroot.'/mod/coursebadges/classes/dual_list.php');
require_once($CFG->dirroot.'/mod/coursebadges/classes/course_badges.php');

/**
 * Class choicebadges_form.
 *
 * Create a form which allows a user to choose one or more badges among those which are configured in the activity.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class choicebadges_form extends moodleform {
    public function __construct($action = null, $customdata = null, $method = 'post', $target = '', $attributes = null, $editable = true, array $ajaxformdata = null) {
        $action = new moodle_url('/mod/coursebadges/view.php', array('id' => $customdata['id']));

        parent::__construct($action, $customdata, $method, $target, $attributes, $editable, $ajaxformdata);
    }

    public function definition() {
        $mform =& $this->_form;

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->setDefault('id', $this->_customdata['id']);
        $mform->addElement('hidden', 'contextid');
        $mform->setType('contextid', PARAM_INT);
        $mform->setDefault('contextid', $this->_customdata['contextid']);
        $mform->addElement('hidden', 'coursebadgesid');
        $mform->setType('coursebadgesid', PARAM_INT);
        $mform->setDefault('coursebadgesid', $this->_customdata['coursebadgesid']);
        $mform->addElement('hidden', 'leftlistids', '');
        $mform->setType('leftlistids', PARAM_TEXT);
        $mform->addElement('hidden', 'rightlistids', '');
        $mform->setType('rightlistids', PARAM_TEXT);

        $course_badges = new course_badges($this->_customdata['coursebadgesid']);
        $cb_data = $course_badges->get_course_badges_instance();
        $all_selected_badges = $course_badges->get_all_selected_badges_instance_by_user();
        $extraclass = "";
        if (($cb_data->allowmodificationschoice == false && $all_selected_badges)) {
            $extraclass = "no-modif";
        }
        $dual_list = new dual_list();

        $pre_select_badges = new stdClass();
        $pre_select_badges->cls = "pre-select-badges list-left ";
        $pre_select_badges->extracls = $extraclass;
        $pre_select_badges->title = "pre_select_badges";
        $pre_select_badges->id_name = "pre_select_badges";
        $pre_select_badges->badges = $course_badges->get_all_pre_select_badges_instance_by_user();
        $dual_list->add_column(dual_list::LEFT_COLUMN, $pre_select_badges);

        if (($cb_data->allowmodificationschoice == false && !$all_selected_badges)
            || $cb_data->allowmodificationschoice == true) {
            $dual_list->add_column(dual_list::ACTION_BUTTONS,[]);
        } else {
            $dual_list->add_column(dual_list::BLANK_COLUMN, []);
        }

        $selected_badges = new stdClass();
        $selected_badges->cls = "selected-badges list-right ";
        $selected_badges->extracls = $extraclass;
        $selected_badges->title = "selected_badges";
        $selected_badges->id_name = "selected_badges";
        $selected_badges->badges = $all_selected_badges;
        $dual_list->add_column(dual_list::RIGHT_COLUMN, $selected_badges);

        $dual_list->add_column(dual_list::BLANK_COLUMN, []);

        $obtained_badges = new stdClass();
        $obtained_badges->cls = "obtained-badges read-only ";
        $obtained_badges->extracls = $extraclass;
        $obtained_badges->title = "obtained_badges";
        $obtained_badges->id_name = "obtained_badges";
        $obtained_badges->badges = $course_badges->get_obtained_badges();
        $dual_list->add_column(dual_list::READ_ONLY_COLUMN, $obtained_badges);

        $mform->addElement('html', $dual_list->generate_html());

        if (($cb_data->allowmodificationschoice == false && !$all_selected_badges)
            || $cb_data->allowmodificationschoice == true) {
            $this->add_action_buttons(false, get_string('btn_save_selected_badge', 'coursebadges'));
        }
    }

    function definition_after_data() {
        global $PAGE;
        $mform = $this->_form;

        if ($this->_customdata['coursebadgesid']){
            $show_notif = false;
            $a = new stdClass();
            $a->ruleallowmodif = "";
            $a->ruleminallowbadge = "";
            $a->rulemaxallowbadge = "";
            $course_badge = new course_badges($this->_customdata['coursebadgesid']);
            $cb_data = $course_badge->get_course_badges_instance();
            $all_selected_badges = $course_badge->get_all_selected_badges_instance_by_user();

            if (($cb_data->allowmodificationschoice == false && !$all_selected_badges)
                || $cb_data->allowmodificationschoice == true) {
                $PAGE->requires->js_call_amd("mod_coursebadges/dual_list", "init");
            }

            if ($cb_data->allowmodificationschoice == false && !$all_selected_badges) {
                $show_notif = true;
                $a->ruleallowmodif = get_string('warning_rule_allow_modif', 'coursebadges');
            }
            if ($cb_data->badgesminrequired > 0) {
                $show_notif = true;
                $a->ruleminallowbadge = get_string('warning_rule_min_allow_badge', 'coursebadges', $cb_data->badgesminrequired);
            }
            if ($cb_data->badgesmaxrequired > 0) {
                $show_notif = true;
                $a->rulemaxallowbadge = get_string('warning_rule_max_allow_badge', 'coursebadges', $cb_data->badgesmaxrequired);
            }

            if ($show_notif) {
                $message = get_string('warning_configuration_activity_notif', 'coursebadges', $a);
                \core\notification::warning($message);
            }
            $elementleftlistids = $mform->getElement('leftlistids');
            $leftlistids = course_badges::badge_ids_array_to_string($course_badge->get_all_pre_select_badges_instance_by_user());
            $elementleftlistids->setValue($leftlistids);

            $elementrightlistids = $mform->getElement('rightlistids');
            $rightlistids = course_badges::badge_ids_array_to_string($all_selected_badges);
            $elementrightlistids->setValue($rightlistids);
        }
    }

}