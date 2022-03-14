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
 * Utils class file.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class utils.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class utils {
    public static function get_img_url_badge($badgeid, $contextid) {
        $imageurl = moodle_url::make_pluginfile_url($contextid, 'badges', 'badgeimage', $badgeid, '/', 'f1', false);
        // Appending a random parameter to image link to forse browser reload the image.
        $imageurl->param('refresh', rand(1, 10000));

        return $imageurl;
    }
    
    public static function get_url_badge($badgeid) {
        $url =  new moodle_url('/badges/overview.php', ['id' => $badgeid]);
        return $url;
    }
    
    public static function get_url_earned_badge($cmid, $badgeid) {
        $url =  new moodle_url('/mod/coursebadges/overview/users.php', ['id' => $cmid, 'badgeid' => $badgeid, 'status' => users_overview_data::EARNED_BADGES]);
        return $url;
    }
    
    public static function get_url_selected_badge($cmid, $badgeid) {
        $url =  new moodle_url('/mod/coursebadges/overview/uesrs.php', ['id' => $cmid, 'badgeid' => $badgeid, 'status' => users_overview_data::SELECTED_BADGES]);
        return $url;
    }

    public static function html_input_data($mform, $name, $data) {
        $data = json_encode($data);

        $mform->addElement('hidden', $name, $data);
        $mform->setType($name, PARAM_RAW);
    }
}

