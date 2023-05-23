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
 * EHS Block Renderer.
 *
 * @package    block_ehs
 * @copyright  2023 Ravi Kumar
 * @author     Ravi Kumar <ravi.kumar18011991@gmail.com>
 */

namespace block_ehs\output;

use plugin_renderer_base;

class renderer extends plugin_renderer_base {

    /**
     * Return the main content for the block.
     *
     */
    public function render_main(main $main) {
        return $this->render_from_template('block_ehs/main',
            $main->export_for_template($this));
    }
}
