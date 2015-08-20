<?php
// This file is part of the my modules block
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
 * Language file for block "mystats"
 *
 * @package    block_mystats
 * @copyright  2014 Bas Brands, www.basbrands.nl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Bas Brands, basbrands.nl
 */

defined('MOODLE_INTERNAL') || die();


/**
 * Displays recent mystats
 */
class block_mystats extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_mystats');
    }

    public function instance_allow_multiple() {
        return true;
    }

    public function has_config() {
        return true;
    }

    public function hide_header() {
        $config = get_config('block_mystats');
        if (isset($config->hideblockheader) && $config->hideblockheader == 1) {
            return true;
        } else {
            return false;
        }
        
    }

    public function instance_allow_config() {
        return true;
    }

    public function applicable_formats() {
        return array(
                'admin' => false,
                'site-index' => false,
                'course-view' => false,
                'mod' => false,
                'my' => true
        );
    }

    public function specialization() {
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_mystats');
        } else {
            $this->title = $this->config->title;
        }
    }

    public function get_content() {
        global $PAGE, $CFG;

        require_once($CFG->dirroot . '/blocks/mystats/locallib.php');

        if ($this->content !== null) {
            return $this->content;
        }

        $config = get_config('block_mystats');

        // Create empty content.
        $this->content = new stdClass();
        $this->content->text = '';


        $courseid = $this->page->course->id;
        if ($courseid == SITEID) {
            $courseid = null;
        }

        if ($mystats = block_mystats_get_dashboard()) {
            $renderer = $this->page->get_renderer('block_mystats');
            $this->content->text .= $renderer->statistics($mystats);
        }

        return $this->content;
    }
}
