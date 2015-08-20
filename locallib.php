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

function block_mystats_get_dashboard() {

    $dashboard = new Object;
    $dashboard->courses = block_mystats_mycourses();
    $dashboard->statistics = block_mystats_get_statistics($dashboard->courses);
    return $dashboard;
}

function block_mystats_mycourses() {
    global $USER;
    $mycourses = array();
    $count = 0;

    $enrolledcourses = enrol_get_users_courses($USER->id);
    foreach ($enrolledcourses as $course) {

        if (isset($USER->lastcourseaccess[$course->id])) {
            $course->lastaccess = $USER->lastcourseaccess[$course->id];
        } else {
            $course->lastaccess = 0;
        }
        $mycourses[$course->id] = $course;
        $count++;
    }

    if (is_enabled_auth('mnet')) {
        $remotecourses = get_my_remotecourses();
        foreach ($remotecourses as $course) {
            $mycourses[$course->id] = $course;
            $count++;
        }
    }
    return $mycourses;
}

function block_mystats_get_statistics($courses) {
    global $DB, $USER, $OUTPUT, $CFG;

    $statistics = new Object;

    $statistics->totalvideos = 0;
    $statistics->totaltime = 0;
    $statistics->viewcount = 0;
    $statistics->timeviewed = 0;
    $statistics->totaldiems = 0;
    $statistics->dmearned = 0;
    $statistics->totalquizzes = 0;
    $statistics->quizzes = 0;

    if (count($courses) == 0) {
        return $statistics;
    }

    // Get DB viewcount and totaltime
    $courseids = array();
    foreach ($courses as $course) {
        $courseids[] = $course->id;
    }
    $courseidlist = implode(",",$courseids);

    $query = "SELECT w.id, w.course, s.duration
                FROM {webvideo} w
           LEFT JOIN {webvideo_store} s
                  ON w.providervideoid = s.providervideoid
                WHERE w.course in ($courseidlist)";
    $results = $DB->get_records_sql($query);   
    $statistics->totalvideos = count($results);
    $totaltime = 0;
    $totaldiems = 0;
    foreach ($results as $result) {
        $totaltime+= $result->duration;
        $totaldiems+= 100;
    }
    $statistics->totaltime = $totaltime;

    $query = "SELECT w.id, w.course, v.viewcount, s.duration, w.providervideoid 
                     FROM {webvideo} w
                LEFT JOIN {webvideo_views} v
                  ON w.id = v.webvideo
                LEFT JOIN {webvideo_store} s
                  ON w.providervideoid = s.providervideoid
                  WHERE v.userid = ?";
    $results = $DB->get_records_sql($query, array($USER->id));
    

    $statistics->viewcount = count($results);

    $timeviewed = 0;
    foreach ($results as $result) {
        $timeviewed+= $result->duration;
    }

    $statistics->timeviewed = $timeviewed;

    // Get diems
    $statistics->totaldiems = $totaldiems;
    $statistics->dmearned = $OUTPUT->completion_points();

    // Get Quiz attempts
    $query = "SELECT id, course
                FROM {quiz}
                WHERE course in ($courseidlist)";
    $results = $DB->get_records_sql($query);
    foreach ($results as $result) {
        $totaldiems += 100;
    }
    $statistics->totalquizzes = count($results);
    $query = "SELECT DISTINCT quiz 
                         FROM {quiz_attempts} 
                        WHERE userid = ?";
    $results = $DB->get_records_sql($query, array($USER->id));

    $statistics->quizzes = count($results);

    return $statistics;
}

function secondsToTime($seconds) {
    $dtF = new DateTime("@0");
    $dtT = new DateTime("@$seconds");
    if ($seconds > (60 * 60 * 24)) {
        return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
    } else if ( $seconds > (60 * 60)) {
        return $dtF->diff($dtT)->format('%h hours, %i minutes and %s seconds');
    } else {
        return $dtF->diff($dtT)->format('%i minutes and %s seconds');
    }
}