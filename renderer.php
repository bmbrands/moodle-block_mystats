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
 *
 * @package    block_mystats
 * @copyright  2014 Bas Brands, www.basbrands.nl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Bas Brands, basbrands.nl
 */

defined('MOODLE_INTERNAL') || die();

class block_mystats_renderer extends plugin_renderer_base {


    public function statistics($dashboard) {
        $statistics = $dashboard->statistics;

        $output = '
        <div class="statistics">
            <div id="pie-charts" class="row dm-bluegreen">
                <div class=" col-sm-6">
                    
                    <div class="clearfix"></div>
                    
                    <div class="text-center p-20">
                        <div class="easy-pie main-pie" >
                            <div class="percent counter">'.$statistics->viewcount.'</div>
                            <div class="pie-title">Videos Viewed</div>
                            <div class="circle"></div>
                        </div>
                    </div>
                </div>
                
                <div class="p-t-20 p-b-20 m-t-25 text-left col-sm-6">
                    <div class="text-center easy-pie sub-pie-1" >
                        <div class="percent counter">'.$statistics->dmearned.'</div>
                        <div class="pie-title">Diems earned</div>
                        <div class="circle"></div>
                    </div>
                    <div class="text-center easy-pie sub-pie-2" >
                        <div class="percent">'.$this->secondsToTime($statistics->timeviewed).'</div>
                        <div class="pie-title">Time viewed</div>
                        <div class="circle"></div>
                    </div>
                    <div class="text-center easy-pie sub-pie-3">
                        <div class="percent counter">'.$statistics->quizzes.'</div>
                        <div class="pie-title">Quizzes completed</div>
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>';
        return $output;
    }

    function secondsToTime($seconds) {
        $dtF = new DateTime("@0");
        $dtT = new DateTime("@$seconds");
        if ($seconds > (60 * 60 * 24)) {
            return $dtF->diff($dtT)->format('%a d: %hh: %i m');
        } else if ( $seconds > (60 * 60)) {
            return $dtF->diff($dtT)->format('%hh %im');
        } else {
            return $dtF->diff($dtT)->format('%i min');
        }
    }
}

