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
 * Dual_list class file.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot.'/badges/renderer.php');

/**
 * Class dual_list.
 *
 * This class contain the dual list management functions for the plugin.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class dual_list {

    const LEFT_COLUMN = 'left';
    const RIGHT_COLUMN = 'right';
    const READ_ONLY_COLUMN = 'read-only';
    const ACTION_BUTTONS = 'action-buttons';
    const BLANK_COLUMN = 'blank-column';

    private $datacolumn;
    private $readonlycolumn;

    function __construct() {
        $this->datacolumn = [];
        $this->readonlycolumn = [];
    }

    public function add_column($name, $data) {
        if($name != dual_list::READ_ONLY_COLUMN){
            $this->datacolumn[$name] = $data;
        } else {
            $this->readonlycolumn[$name] = $data;
        }
    }

    public function generate_html() {
        global $PAGE;

        $badges_renderer = new core_badges_renderer($PAGE, '');

        $html = html_writer::start_tag('table', ['class' => 'badges-management']);
        $html .= html_writer::start_tag('tr');
        foreach ($this->datacolumn as $key => $column) {
            if ($key == dual_list::ACTION_BUTTONS) {
                // Action buttons.
                $html .= html_writer::start_tag('td', ['class' => 'list-arrows']);

                // Move right button.
                $html .= html_writer::start_tag('button', ['class' => 'btn move-right', 'type' => 'button']);
                $html .= html_writer::tag('i','', ['class' => 'fas fa fa-angle-right']);
                $html .= html_writer::end_tag('button');

                // Move left button.
                $html .= html_writer::start_tag('button', ['class' => 'btn move-left', 'type' => 'button']);
                $html .= html_writer::tag('i','', ['class' => 'fas fa fa-angle-left']);
                $html .= html_writer::end_tag('button');

                $html .= html_writer::end_tag('td');
            } else if ($key == dual_list::BLANK_COLUMN) {
                // blank column
                $html .= html_writer::tag('td','', ['class' => 'blank-column']);
            } else {
                $html .= html_writer::start_tag('td', ['class' => $column->cls.' dual-list '.$column->extracls]);

                // Main HTML column content.
                $html .= html_writer::start_div('content');

                // Main column title.
                $html .= html_writer::start_div('title');
                $html .= html_writer::tag('h4', get_string($column->title, 'coursebadges'));
                $html .= html_writer::end_div();

                $html .= html_writer::start_tag('ul', ['class' => 'list-group']);
                foreach ($column->badges as $badge) {
                    $badge = new badge($badge->id);
                    $image_url = course_badges::get_imageurl_for_badge($badge->id)->out();
                    $html .= html_writer::start_tag('li',
                        ['class' => 'list-group-item',
                        'id' => $column->id_name.'_'.$badge->id,
                        'value' => $badge->id]);
                    $html .= html_writer::start_div('badge-information');
                    $html .= html_writer::start_div('badge-content');
                    if (empty($column->extracls)) {
                        $html .= html_writer::checkbox('chx_'.$column->id_name.'_'.$badge->id, 0, false);
                    }
                    $html .= html_writer::img($image_url,$badge->name);
                    $html .= html_writer::span($badge->name,"badge-title");
                    $html .= html_writer::end_div();
                    $html .= html_writer::start_div('action-btn');
                    $html .= html_writer::tag('i','', ['class' => 'fas fa-sort-up fa fa-sort-asc']);
                    $html .= html_writer::end_div();
                    $html .= html_writer::end_div();
                    $html .= html_writer::start_div('badge-detail');
                    $html .= html_writer::start_div('badge-description');
                    $html .= html_writer::span(get_string('description', 'coursebadges'),"badge-description-title");
                    $html .= html_writer::tag('p',$badge->description);
                    $html .= html_writer::span(get_string('criteria', 'coursebadges'),"badge-criteria-title");
                    $html .= html_writer::tag('p',$badges_renderer->print_badge_criteria($badge));
                    $html .= html_writer::end_div();
                    $html .= html_writer::end_div();
                    $html .= html_writer::end_tag('li');
                }
                $html .= html_writer::end_tag('ul');
                $html .= html_writer::end_div();
                $html .= html_writer::end_tag('td');
            }
        }
        foreach ($this->readonlycolumn as $key => $column) {
            $html .= html_writer::start_tag('td', ['class' => $column->cls.' dual-list '.$column->extracls]);

            // Main HTML column content.
            $html .= html_writer::start_div('content');

            // Main column title.
            $html .= html_writer::start_div('title');
            $html .= html_writer::tag('h4', get_string($column->title, 'coursebadges'));
            $html .= html_writer::end_div();

            $html .= html_writer::start_tag('ul', ['class' => 'list-group']);
            foreach ($column->badges as $badge) {
                $badge = new badge($badge->id);
                $image_url = course_badges::get_imageurl_for_badge($badge->id)->out();
                $html .= html_writer::start_tag('li',
                    ['class' => 'list-group-item',
                        'id' => $column->id_name.'_'.$badge->id,
                        'value' => $badge->id]);
                $html .= html_writer::start_div('badge-information');
                $html .= html_writer::start_div('badge-content');
                $html .= html_writer::img($image_url, $badge->name);
                $html .= html_writer::span($badge->name,"badge-title");
                $html .= html_writer::end_div();
                $html .= html_writer::start_div('action-btn');
                $html .= html_writer::tag('i','', ['class' => 'fas fa-sort-up fa fa-sort-asc']);
                $html .= html_writer::end_div();
                $html .= html_writer::end_div();
                $html .= html_writer::start_div('badge-detail');
                $html .= html_writer::start_div('badge-description');
                $html .= html_writer::span(get_string('description', 'coursebadges'),"badge-description-title");
                $html .= html_writer::tag('p', $badge->description);
                $html .= html_writer::span(get_string('criteria', 'coursebadges'),"badge-criteria-title");
                $html .= html_writer::tag('p', $badges_renderer->print_badge_criteria($badge));
                $html .= html_writer::end_div();
                $html .= html_writer::end_div();
                $html .= html_writer::end_tag('li');
            }
            $html .= html_writer::end_tag('ul');
            $html .= html_writer::end_div();
            $html .= html_writer::end_tag('td');
        }

        $html .= html_writer::end_tag('tr');
        $html .= html_writer::tag('tr','',['class'=> 'footer']);
        $html .= html_writer::end_tag('table');
        return $html;
    }
}