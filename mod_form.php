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
 * The main mod_coursebadges configuration form.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/coursebadges/lib.php');
require_once($CFG->dirroot.'/mod/coursebadges/classes/dual_list.php');
require_once($CFG->dirroot.'/mod/coursebadges/classes/course_badges.php');
require_once($CFG->libdir.'/badgeslib.php');

/**
 * Class mod_coursebadges_mod_form.
 * Create course badges instance form.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_coursebadges_mod_form extends moodleform_mod {

    function definition() {
        global $COURSE, $CFG;

        $mform =& $this->_form;

        $mform->addElement('header', 'generalheader', get_string('general'));
        $mform->setExpanded('generalheader');

        $mform->addElement('text', 'name', get_string('txt_course_badges', 'coursebadges'), ['size'=>'64']);
        $mform->setType('name', PARAM_CLEANHTML);
        $mform->addRule('name', get_string('error'), 'required');

        // Adding the standard "intro" and "introformat" fields
        $this->standard_intro_elements();

        $mform->addElement('header', 'actionbadgesmanagement', get_string('label_badges_management', 'coursebadges'));
        $mform->setExpanded('actionbadgesmanagement');

        $mform->addElement('hidden', 'leftlistids', '');
        $mform->setType('leftlistids', PARAM_TEXT);

        $mform->addElement('hidden', 'rightlistids', '');
        $mform->setType('rightlistids', PARAM_TEXT);

        $mform->addElement('hidden', 'hascoursebadgesbloc', 0);
        $mform->setType('hascoursebadgesbloc', PARAM_BOOL);

        if (count(badges_get_badges(BADGE_TYPE_COURSE, $COURSE->id)) < 1) {
            $a = new stdClass();
            $a->linkbadges = $CFG->wwwroot . '/badges/index.php?type='.BADGE_TYPE_COURSE.'&id='.$COURSE->id;
            $a->linkcourse = $CFG->wwwroot . '/course/view.php?id='.$COURSE->id;
            $message = get_string('pleasesetonebadgeor', 'coursebadges', $a);
            \core\notification::add($message, \core\notification::WARNING);
            print_error('nobadgeincourse', 'coursebadges', new moodle_url('/course/view.php?id='.$COURSE->id), $a);
        }

        $course_badges = new course_badges($this->get_instance());
        $all_user_choices = $course_badges->get_all_user_choices_by_course_badges_instance();

        $extraclass = "";
        if(count($all_user_choices) > 0){
            $extraclass = "no-modif";
        }

        if(count($all_user_choices) > 0){
            $availablefromgroup = [];
            $availablefromgroup[] = $mform->createElement('button', 'changebadgeselections', get_string('btn_change_badge_selections', 'coursebadges'));
            $availablefromgroup[] = $mform->createElement('checkbox', 'showchangebadgeselectionsbutton', '', get_string('enable'));
            $mform->addGroup($availablefromgroup, 'changebadgeselectionsgroup', get_string('label_change_badge_selections', 'coursebadges'), ' ', false);
            $mform->addHelpButton('changebadgeselectionsgroup', 'changebadgeselectionsgroup', 'coursebadges');
            $mform->disabledIf('changebadgeselectionsgroup', 'showchangebadgeselectionsbutton');

            $dialog = '
                <div id="dialog_change_badge_selections" style="display:none;">
                    <table style="font-size: 12px; color: black;">
                        <tr><td>Attention ! Cette opération va supprimer l\'ensemble des choix de badges de chaque utilisateur de ce parcours.</td></tr>
                        <tr><td>Souhaitez-vous continuer ?</td></tr>
                    </table>
                </div>';

            $mform->addElement('html', $dialog);
        }

        $duallist = new dual_list();
        $available_badges = new stdClass();
        $available_badges->cls = "course-badges list-left ";
        $available_badges->extracls = $extraclass;
        $available_badges->title = "course_badges";
        $available_badges->id_name = "course_badges";
        $available_badges->badges = $course_badges->get_available_badges_in_course($COURSE->id);
        $duallist->add_column(dual_list::LEFT_COLUMN, $available_badges);

        if(count($all_user_choices) > 0){
            $duallist->add_column(dual_list::BLANK_COLUMN, []);
        } else {
            $duallist->add_column(dual_list::ACTION_BUTTONS, []);
        }

        $pre_select_badges = new stdClass();
        $pre_select_badges->cls = "pre-select-badges list-right ";
        $pre_select_badges->extracls = $extraclass;
        $pre_select_badges->title = "pre_select_badges";
        $pre_select_badges->id_name = "pre_select_badges";
        $pre_select_badges->badges = $course_badges->get_all_pre_select_badges_instance();
        $duallist->add_column(dual_list::RIGHT_COLUMN, $pre_select_badges);

        $mform->addElement('html', $duallist->generate_html());

        $mform->addElement('header', 'activitymngtheader', get_string('label_activity_management', 'coursebadges'));
        $mform->setExpanded('activitymngtheader');

        if (is_course_badges_block_available()) {
            $mform->addElement('checkbox', 'showcoursebadgesbloc', get_string('cbx_show_course_badges_block', 'coursebadges'));
            $mform->setDefault('showcoursebadgesbloc', 1);
            $mform->disabledIf('showcoursebadgesbloc', 'hascoursebadgesbloc', 'eq', 1);
        } else {
            $mform->addElement('hidden', 'showcoursebadgesbloc', 0);
            $mform->setType('showcoursebadgesbloc', PARAM_BOOL);
        }

        $options = [
            course_badges::VALIDATED_CHOICE_NOTIF => get_string('cbx_validated_choice_notification', 'coursebadges'),
            course_badges::UPDATED_CHOICE_NOTIF => get_string('cbx_updated_choice_notification', 'coursebadges'),
            course_badges::NO_NOTIF => get_string('cbx_no_notification', 'coursebadges')
        ];
        $select = $mform->addElement('select', 'notification', get_string('label_notification', 'coursebadges'), $options);
        $select->setSelected(course_badges::NO_NOTIF);

        $mform->addElement('text', 'badgesminrequired', get_string('txt_badges_min_required', 'coursebadges'), ['size' => '3']);
        $mform->setType('badgesminrequired', PARAM_INT);

        $mform->addElement('text', 'badgesmaxrequired', get_string('txt_badges_max_required', 'coursebadges'), ['size' => '3']);
        $mform->setType('badgesmaxrequired', PARAM_INT);

        $mform->addElement('checkbox', 'allowmodificationschoice', get_string('cbx_allow_modifications_choice', 'coursebadges'));
        $mform->setDefault('allowmodificationschoice', 1);

        $options = [
            course_badges::NO_RESULT => get_string('cbx_no_publish_results', 'coursebadges'),
            course_badges::SHOW_RESULTS_AFTER_RESPONSE => get_string('cbx_show_results_after_response', 'coursebadges'),
            course_badges::ALWAYS_SHOW_RESULTS => get_string('cbx_always_show_results', 'coursebadges')
        ];
        $select = $mform->addElement('select', 'showawardedresults', get_string('label_show_awarded_results', 'coursebadges'), $options);
        $select->setSelected(course_badges::NO_RESULT);

        $badge_management_html =  html_writer::start_div('form-group row fitem', ['id' => 'id_linktobadgesmanagement']);
        $badge_management_html .= html_writer::start_div('col-md-3 col-form-label d-flex pb-0 pr-md-0');
        $badge_management_html .= html_writer::label(get_string('label_manage_badges', 'coursebadges'), 'id_linktobadgesmanagement', true, array('class' => 'd-inline word-break'));
        $badge_management_html .= html_writer::div('', 'ml-1 ml-md-auto d-flex align-items-center align-self-start');
        $badge_management_html .= html_writer::end_div();
        $badge_management_html .= html_writer::start_div('col-md-9 form-inline align-items-start felement');
        $badge_management_link  = new moodle_url('/badges/index.php', ['type' => BADGE_TYPE_COURSE, 'id' => $COURSE->id]);
        $badge_management_html .= html_writer::link($badge_management_link, get_string('manage_badges_link', 'coursebadges'));
        $badge_management_html .= html_writer::end_div();
        $badge_management_html .= html_writer::end_div();

        $mform->addElement('html', $badge_management_html);

        //-------------------------------------------------------------------------------
        $this->standard_coursemodule_elements();
        //-------------------------------------------------------------------------------
        $this->add_action_buttons();
    }

    function data_preprocessing(&$default_values) {
        global $PAGE;
        $PAGE->requires->jquery();
        $PAGE->requires->jquery_plugin('ui');
        $PAGE->requires->jquery_plugin('ui-css');
        $PAGE->requires->js_call_amd("mod_coursebadges/dual_list", "init");
        if ($this->get_instance()){
            if(isset($_GET['dbs'])){
                course_badges::delete_all_user_choices_by_course_badges_instance($this->get_instance());
                redirect($PAGE->url, get_string('notification_delete_user_choice', 'coursebadges'), null, \core\output\notification::NOTIFY_INFO);
            }
            $course_badge = new course_badges($this->get_instance());
            $rightlistids = course_badges::badge_ids_array_to_string($course_badge->get_all_pre_select_badges_instance());
            $default_values['rightlistids'] = $rightlistids;

            $leftlistids = course_badges::badge_ids_array_to_string($course_badge->get_available_badges_in_course($this->get_course()->id));
            $default_values['leftlistids'] = $leftlistids;
        }
        if(course_badges::has_block_course_badges_instance()){
            $default_values['hascoursebadgesbloc'] = 1;
        }
    }

    function get_data() {
        if (!$data = parent::get_data()) {
            return false;
        }
        $data->timemodified = time();
        if (!isset($data->allowmodificationschoice)) {
            $data->allowmodificationschoice = 0;
        }
        if (!isset($data->showcoursebadgesbloc)) {
            $data->showcoursebadgesbloc = 0;
        }

        return $data;
    }

    /**
     * Add any custom completion rules to the form.
     *
     * @return array Contains the names of the added form elements
     */
    public function add_completion_rules() {
        $mform =& $this->_form;

        $mform->addElement('checkbox', 'completionvalidatedbadges', '', get_string('completionvalidatedbadges', 'coursebadges'));
        return array('completionvalidatedbadges');
    }

    /**
     * Determines if completion is enabled for this module.
     *
     * @param array $data
     * @return bool
     */
    public function completion_rule_enabled($data) {
        return !empty($data['completionvalidatedbadges']);
    }
}