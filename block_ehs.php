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
 * EHS block.
 *
 * @package    block_ehs
 * @copyright  2023 Ravi Kumar
 * @author     Ravi Kumar <ravi.kumar18011991@gmail.com>
 */

/**
 * EHS block definition class.
 *
 */

class block_ehs extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_ehs');
    }

    public function get_content() {
        global $OUTPUT, $CFG;

        if ($this->content !== null) {
            return $this->content;
        }

        $renderable = new \block_ehs\output\main();
        $renderer = $this->page->get_renderer('block_ehs');
        $this->content = (object) [
            'text' => $renderer->render($renderable),
        ];
        return $this->content;
    }
    public function applicable_formats() {
        return array('course' => true);
    }
    public function has_config() {
        return true;
    }
}
