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
 * This API use the classes badges_overview_data and users_overview_data to transmit data to the Frontend.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_once($CFG->dirroot.'/mod/coursebadges/overview/classes/badges_overview_data.php');
require_once($CFG->dirroot.'/mod/coursebadges/overview/form/badges_overview_form.php');
require_once($CFG->dirroot.'/mod/coursebadges/overview/form/users_overview_form.php');

require_login();

$action = required_param('action', PARAM_TEXT);

if ($action == 'list_badges') {
    $so = required_param('so', PARAM_TEXT);
    $si = required_param('si', PARAM_INT);
    $ps = required_param('ps', PARAM_INT);
    $id = required_param('id', PARAM_INT);
    $courseid = required_param('courseid', PARAM_INT);

    if ($si < 0) {
        die;
    }

    if ($ps < 1) {
        die;
    }

    $users_overview_data = new badges_overview_data($id, $courseid, $si, $ps, $so);

    $data = $users_overview_data->execute_sql();

    $jtable_result = [];
    $jtable_result['Result'] = "OK";
    $jtable_result['TotalRecordCount'] = $users_overview_data->get_result_count();
    $jtable_result['Records'] = array_values($data);

    echo json_encode($jtable_result);
    die;
}

if ($action == 'list_users') {
    $so = required_param('so', PARAM_TEXT);
    $si = required_param('si', PARAM_INT);
    $ps = required_param('ps', PARAM_INT);
    $id = required_param('id', PARAM_INT);
    $courseid = required_param('courseid', PARAM_INT);

    if ($si < 0) {
        die;
    }

    if ($ps < 1) {
        die;
    }

    $badgeid = optional_param(users_overview_form::BADGEID_FIELD, -1,PARAM_INT);
    $status = optional_param(users_overview_form::STATUS_FIELD, -1,PARAM_INT);
    $groupid = optional_param(users_overview_form::GROUPID_FIELD, -1,PARAM_INT);
    $username = optional_param(users_overview_form::USERNAME_FIELD, "",PARAM_TEXT);

    $users_overview_data = new users_overview_data($id, $courseid, $si, $ps, $so);

    if ($badgeid > 0) {
        $users_overview_data->set_badgeid($badgeid);
    }

    if ($status > 0) {
        $users_overview_data->set_status($status);
    }

    if ($groupid > 0) {
        $users_overview_data->set_groupid($groupid);
    }

    if ($username) {
        $users_overview_data->set_username($username);
    }

    if ($status != -1) {
        $users_overview_data->set_status($status);
    }

    $data = $users_overview_data->execute_sql();

    $jtable_result = [];
    $jtable_result['Result'] = "OK";
    $jtable_result['TotalRecordCount'] = $users_overview_data->get_result_count();
    $jtable_result['Records'] = array_values($data);

    echo json_encode($jtable_result);
    die;
}