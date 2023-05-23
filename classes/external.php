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
 * EHS Block External API
 *
 * @package    block_ehs
 * @copyright  2023 Ravi Kumar
 * @author     Ravi Kumar <ravi.kumar18011991@gmail.com>
 */
defined('MOODLE_INTERNAL') || die;

use core_completion\progress;

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/course/externallib.php');
require_once($CFG->dirroot . '/report/outline/locallib.php');
require_once($CFG->dirroot . '/completion/classes/progress.php');


class block_ehs_external extends core_course_external {

    /**
     * Returns description of method parameters
     *
     */
    public static function get_course_modules_parameters() {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT, 'Current Course Id', VALUE_DEFAULT, 0),
        ]);
    }

    /**
     * Get Current Course Modules
     *
     * @param int $courseid Current Course Id
     *
     * @return  array list of module in the course
     */
    public static function get_course_modules($courseid) {
        global $DB, $USER;
        $allmodules = $activitylist = array();
        // Validating parameters.
        $params = self::validate_parameters(self::get_course_modules_parameters(), [
            'courseid' => $courseid
        ]);
        // Course object.
        $params = array('id' => $courseid);
        $course = $DB->get_record('course', $params, '*', MUST_EXIST);
        // Get information about course modules.
        $modinfo = get_fast_modinfo($course);
        $modules = $modinfo->get_cms();
        foreach ($modules as $module) {
            if (!$module->uservisible || $module->is_stealth() || empty($module->url)) {
                continue;
            }
            $modname = $module->get_formatted_name();
            if (!$module->visible) {
                $modname .= ' ' . get_string('hiddenwithbrackets');
            }
            $linkurl = new moodle_url($module->url, array());
            $views = report_outline_user_outline($USER->id, $module->id, $modname, $module->instance);
            if ($views->info == null) {
                $viewed = '0 views';
            } else {
                $viewed = $views->info;
            }
            $aststus = $DB->get_field('course_modules_completion', 'completionstate',
            array('coursemoduleid' => $module->id, 'userid' => $USER->id));
            if ($aststus == 1) {
                $status = 'Complete';
            } else {
                $status = 'Not Complete';
            }
            $course = get_course($courseid);
            $progress = (float) progress::get_course_progress_percentage($course, $USER->id);
            $allmodules[] = [
                'id' => $module->id,
                'name' => $modname,
                'url' => $linkurl->out(false),
                'added' => userdate($module->added, '%d-%m-%Y'),
                'views' => $viewed,
                'astatus' => $status,
                'cprogress' => round($progress),
                'coursename' => $course->fullname
            ];
        }
        return $allmodules;
    }

    /**
     * Returns result value
     *
     */
    public static function get_course_modules_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_TEXT, 'Module ID'),
                    'name' => new external_value(PARAM_TEXT, 'Module Name'),
                    'url' => new external_value(PARAM_RAW, 'Module URL'),
                    'added' => new external_value(PARAM_TEXT, 'Module Added on'),
                    'views' => new external_value(PARAM_TEXT, 'Module Views'),
                    'astatus' => new external_value(PARAM_TEXT, 'Module astatus'),
                    'cprogress' => new external_value(PARAM_TEXT, 'Module cprogress'),
                    'coursename' => new external_value(PARAM_TEXT, 'Module coursename'),
                )
            )
        );
    }
}
