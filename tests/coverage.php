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
 * Coverage information for the mod_bigbluebuttonbn component.
 *
 * @package   mod_coursebadges
 * @copyright 2022 Reseau-Canope
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Class coursebadges_coverage information for the core subsystem.
 *
 * @package   mod_coursebadges
 * @copyright 2022 Reseau-Canope
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class coursebadges_coverage extends phpunit_coverage_info {
    /** @var array The list of folders relative to the plugin root to whitelist in coverage generation. */
    protected $whitelistfolders = array();

    /** @var array The list of files relative to the plugin root to whitelist in coverage generation. */
    protected $whitelistfiles = array('lib.php');

    /** @var array The list of folders relative to the plugin root to excludelist in coverage generation. */
    protected $excludelistfolders = array();

    /** @var array The list of files relative to the plugin root to excludelist in coverage generation. */
    protected $excludelistfiles = array();
};
return new coursebadges_coverage;
