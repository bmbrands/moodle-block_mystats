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
        //echo '<pre>' . print_r($statistics, true) . '</pre>';

        if ($statistics->viewcount == 0) {
            $perc_viewed = 0;
        } else {
            $perc_viewed = round(100 * ($statistics->viewcount / $statistics->totalvideos));
        }
        if ($statistics->dmearned == 0) {
            $perc_diems = 0;
        } else {
            $perc_diems = round(100 * ($statistics->dmearned / $statistics->totaldiems));
        }
        if ($statistics->quizzes == 0) {
            $perc_quizzes = 0;
        } else {
            $perc_quizzes = round(100 * ($statistics->quizzes / $statistics->totalquizzes));
        }
        if ($statistics->timeviewed == 0) {
            $perc_time = 0;    
        } else {
            $perc_time = round(100 * ($statistics->timeviewed / $statistics->totaltime));
        }
        $output = '
        <div class="statistics">
            <div id="pie-charts" class="row dm-bluegreen">
                <div class=" col-sm-6">
                    
                    <div class="clearfix"></div>
                    
                    <div class="text-center p-20">
                        <div class="easy-pie main-pie" data-percent="'.$perc_viewed.'">
                            <div class="percent">'.$perc_viewed.'</div>
                            <div class="pie-title">Videos Viewed</div>
                        </div>
                    </div>
                </div>
                
                <div class="p-t-20 p-b-20 m-t-25 text-left col-sm-6">
                    <div class="text-center easy-pie sub-pie-1" data-percent="'.$perc_diems.'">
                        <div class="percent">'.$perc_diems.'</div>
                        <div class="pie-title">Diems earned</div>
                    </div>
                    <div class="text-center easy-pie sub-pie-2" data-percent="'.$perc_time.'">
                        <div class="percent">'.$perc_time.'</div>
                        <div class="pie-title">Time viewed</div>
                    </div>
                    <div class="text-center easy-pie sub-pie-3" data-percent="'.$perc_quizzes.'">
                        <div class="percent">'.$perc_quizzes.'</div>
                        <div class="pie-title">Quizzes completed</div>
                    </div>
                </div>
            </div>
        </div>';
        return $output;
    }
}