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
 * Privacy class for requesting user data.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_coursebadges\privacy;

use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\userlist;
use core_privacy\local\request\writer;
use core_privacy\local\metadata\collection;

/**
 * Privacy class for requesting user data.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
    // This plugin has data.
    \core_privacy\local\metadata\provider,

    // This plugin currently implements the original plugin\provider interface.
    \core_privacy\local\request\plugin\provider,

    // This plugin is capable of determining which users have data within it.
    \core_privacy\local\request\core_userlist_provider {

    /**
     * Return meta data about this plugin.
     *
     * @param collection $collection A list of information to add to.
     * @return collection Return the collection after adding to it.
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table(
            'coursebadges_usr_select_bdg', [
                'userid' => 'privacy:metadata:coursebadges_usr_select_bdg:userid',
                'selectionbadgeid' => 'privacy:metadata:coursebadges_usr_select_bdg:selectionbadgeid'
            ],
            'privacy:metadata:coursebadges_usr_select_bdg'
        );

        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid The user to search.
     * @return  contextlist   $contextlist  The contextlist containing the list of contexts used in this plugin.
     */
    static public function get_contexts_for_userid(int $userid): contextlist {
        $sql = "
            SELECT ctx.id FROM {coursebadges_notification} cn 
            JOIN {context} ctx ON ctx.instanceid = cn.userid AND ctx.contextlevel = :userlevel
            WHERE cn.userid = :userid";
        $params = [
            'userlevel' => CONTEXT_USER,
            'userid' => $userid,
        ];
        $contextlist = new contextlist();
        $contextlist->add_from_sql($sql, $params);

        $sql = "
            SELECT ctx.id FROM {coursebadges_usr_select_bdg} usb 
            JOIN {context} ctx ON ctx.instanceid = usb.userid AND ctx.contextlevel = :userlevel
            WHERE usb.userid = :userid";
        $params = [
            'userlevel' => CONTEXT_USER,
            'userid' => $userid,
        ];
        $contextlist->add_from_sql($sql, $params);

        return $contextlist;
    }

    /**
     * Get the list of users who have data within a context.
     *
     * @param userlist $userlist The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {
        $context = $userlist->get_context();

        if ($context instanceof \context_user) {
            $sql = "
                SELECT u.userid
                FROM {user} u 
                WHERE u.id = :uid";

            $params = [
                'uid' => $context->instanceid,
            ];
            $userlist->add_from_sql('userid', $sql, $params);
        }

        if ($context instanceof \context_course) {
            // Fetch all choice answers.
            $sql = "
                SELECT sb.userid
                FROM {coursebadges} cb 
                JOIN {coursebadges_notification} sb ON sb.coursebadgeid = cb.id
                WHERE cb.course = :cid";

            $params = [
                'cid' => $context->instanceid,
            ];

            $userlist->add_from_sql('userid', $sql, $params);

            $sql = "
                SELECT uc.userid
                FROM {coursebadges} cb 
                JOIN {coursebadges_available_bdg} sb ON sb.coursebadgeid = cb.id
                JOIN {coursebadges_usr_select_bdg} uc ON uc.selectionbadgeid = sb.id
                WHERE cb.course = :cid";

            $params = [
                'cid' => $context->instanceid,
            ];

            $userlist->add_from_sql('userid', $sql, $params);
        }
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        $userid = $contextlist->get_user()->id;

        $coursebadges_usr_select_bdg = $DB->get_records_sql('
            SELECT usb.*, cm.id as coursemoduleid
            FROM {coursebadges_usr_select_bdg} usb
            LEFT JOIN {coursebadges_available_bdg} ab ON ab.id =  usb.selectionbadgeid
            LEFT JOIN {coursebadges} cb ON cb.id =  ab.coursebadgeid
            LEFT JOIN {course_modules} cm ON cm.instance = cb.id AND cm.module = (SELECT id FROM {modules} WHERE name = "coursebadges")  
            WHERE usb.userid = :userid', ['userid' => $userid]);

        $sortbymodule = [];
        foreach ($coursebadges_usr_select_bdg as $record){
            $sortbymodule[$record->coursemoduleid][] = $record;
        }

        foreach ($sortbymodule as $key => $item) {
            $coursebadges_usr_select_bdgdata = [];
            foreach ($item as $record) {
                $coursebadges_usr_select_bdgdata[] = [
                    'selectionbadgeid' => $record->selectionbadgeid,
                    'userid' => $record->userid,
                    'timemodified' => $record->timemodified
                ];
            }
            writer::with_context(\context_module::instance($key))->export_data(["coursebadges_usr_select_bdg"], (object) $coursebadges_usr_select_bdgdata);
        }

        $coursebadges_notification = $DB->get_records_sql('
            SELECT cn.*, cm.id as coursemoduleid
            FROM {coursebadges_notification} cn
            LEFT JOIN {coursebadges} cb ON cb.id =  cn.coursebadgeid
            LEFT JOIN {course_modules} cm ON cm.instance = cb.id AND cm.module = (SELECT id FROM {modules} WHERE name = "coursebadges")  
            WHERE cn.userid = :userid', ['userid' => $userid]);

        $sortbymodule = [];
        foreach ($coursebadges_notification as $record){
            $sortbymodule[$record->coursemoduleid][] = $record;
        }

        foreach ($sortbymodule as $key => $item) {
            $coursebadges_notificationdata = [];
            foreach ($item as $record) {
                $coursebadges_notificationdata[] = [
                    'coursebadgeid' => $record->coursebadgeid,
                    'userid' => $record->userid,
                    'badges' => $record->badges,
                    'type' => $record->type,
                    'sended' => $record->sended,
                    'timemodified' => $record->timemodified
                ];
            }
            writer::with_context(\context_module::instance($key))->export_data(["coursebadges_notification"], (object) $coursebadges_notificationdata);
        }
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param \context $context The specific context to delete data for.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;

        // Check that this is a context_module.
        if (!$context instanceof \context_module) {
            return;
        }

        // Get the course module.
        if (!$cm = get_coursemodule_from_id('coursebadges', $context->instanceid)) {
            return;
        }

        $coursebadgeid = $cm->instance;

        $DB->delete_records_select(
            'coursebadges_usr_select_bdg',
            "selectionbadgeid IN (SELECT id FROM {coursebadges_available_bdg} WHERE coursebadgeid = :coursebadge)",
            ['coursebadge' => $coursebadgeid]
        );

       $DB->delete_records("coursebadges_notification",  ['coursebadge' => $coursebadgeid]);
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts and user information to delete information for.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;
        $user = $contextlist->get_user();
        $userid = $user->id;

        foreach ($contextlist as $context) {
            // Get the course module.
            $cm = $DB->get_record('course_modules', ['id' => $context->instanceid]);
            if($cm){
                $coursebadge = $DB->get_record('coursebadges', ['id' => $cm->instance]);
                if($coursebadge){
                    $DB->delete_records_select(
                        'coursebadges_usr_select_bdg',
                        "userid = :userid AND selectionbadgeid IN (SELECT id FROM {coursebadges_available_bdg} WHERE coursebadgeid = :coursebadge)",
                        [
                            'userid' => $userid,
                            'coursebadge' => $coursebadge->id,
                        ]
                    );

                    $DB->delete_records("coursebadges_notification",  ['userid' => $userid,'coursebadge' => $coursebadge->id]);
                }
            }
        }
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param approved_userlist $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;

        $context = $userlist->get_context();
        $cm = $DB->get_record('course_modules', ['id' => $context->instanceid]);
        if($cm){
            $coursebadge = $DB->get_record('coursebadges', ['id' => $cm->instance]);
            if($coursebadge){
                $userids = $userlist->get_userids();
                foreach ($userids as $userid) {
                    $DB->delete_records_select(
                        'coursebadges_usr_select_bdg',
                        "userid = :userid AND selectionbadgeid IN (SELECT id FROM {coursebadges_available_bdg} WHERE coursebadgeid = :coursebadge)", [
                            'userid' => $userid,
                            'coursebadge' => $coursebadge->id,
                        ]
                    );

                    $DB->delete_records("coursebadges_notification",  ['userid' => $userid,'coursebadge' => $coursebadge->id]);
                }
            }
        }
    }

}