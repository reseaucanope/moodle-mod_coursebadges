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
 * Course_badges library tests class.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
global $CFG;

require_once($CFG->dirroot . '/mod/coursebadges/lib.php');
require_once($CFG->dirroot . '/mod/coursebadges/classes/course_badges.php');

/**
 * Class mod_coursebadges_lib_testcase.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_coursebadges_lib_testcase extends advanced_testcase {
    /**
     * @var testing_data_generator|null $generator
     */
    public $generator = null;

    /**
     * @var object|null $course
     */
    public $course = null;

    protected function setUp(){

        // Course creation.
        $this->course = $this->getDataGenerator()->create_course();
    }

    /**
     * Convenience function to create a instance of an coursebadges.
     *
     * @param object|null $course course to add the module to
     * @param array $params Array of parameters to pass to the generator
     * @param array $options Array of options to pass to the generator
     * @return array($context, $cm, $instance) Testable wrapper around the assign class.
     * @throws coding_exception
     * @throws moodle_exception
     */
    protected function create_instance($course = null, $params = [], $options = []) {
        if (!$course) {
            $course = $this->course;
        }
        $params['course'] = $course->id;
        $params['showcoursebadgesbloc'] = 0;
        $options['visible'] = 1;
        $options['completionvalidatedbadges'] = 0;

        $instance = $this->getDataGenerator()->create_module('coursebadges', $params, $options);
        list($course, $cm) = get_course_and_cm_from_instance($instance, 'coursebadges');
        $context = context_module::instance($cm->id);

        return array($context, $cm, $instance);
    }

    /**
     * Get the corresponding form data
     *
     * @param object $coursebadgesactivity the current coursebadges activity
     * @param object|null $course the course or null (taken from $this->course if null)
     * @return mixed
     * @throws coding_exception
     */
    protected function get_form_data_from_instance($coursebadgesactivity, $course = null) {
        if (!$course) {
            $course = $this->course;
        }
        $this->setAdminUser();
        $coursebadgesactivitycm = get_coursemodule_from_instance('coursebadges', $coursebadgesactivity->id);
        $data = get_moduleinfo_data($coursebadgesactivitycm, $course);
        return $data;
    }

    public function test_coursebadges_add_instance() {
        $this->resetAfterTest();
        list($coursebadgesactivitycontext, $coursebadgesactivitycm, $coursebadgesactivity) = $this->create_instance();
        $coursebadgesactivity->showcoursebadgesbloc = 0;
        $coursebadgesactivity->completionvalidatedbadges = 0;
        $id = coursebadges_add_instance($coursebadgesactivity);
        $this->assertNotNull($id);
    }

    public function test_coursebadges_update_instance() {
        $this->resetAfterTest();
        list($coursebadgesactivitycontext, $coursebadgesactivitycm, $coursebadgesactivity) = $this->create_instance();
        $coursebadgesactivitycm = get_coursemodule_from_instance('coursebadges', $coursebadgesactivity->id);
        $coursebadgesactivitycm->showcoursebadgesbloc = 0;
        $coursebadgesformdata = $this->get_form_data_from_instance($coursebadgesactivity);

        $result = coursebadges_update_instance($coursebadgesactivitycm, $coursebadgesformdata);
        $this->assertTrue($result);
    }

    public function test_coursebadges_delete_instance() {
        $this->resetAfterTest();
        list($coursebadgesactivitycontext, $coursebadgesactivitycm, $coursebadgesactivity) = $this->create_instance();
        $result = coursebadges_delete_instance($coursebadgesactivity->id);
        $this->assertTrue($result);
    }
}