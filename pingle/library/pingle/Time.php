<?php

/**
 * Pingle
 * 
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */

namespace library\pingle;

Class Time {

    //generate the time stamp
    function getTimeStamp($session_time) {
        $time_difference = time() - $session_time;
        $seconds = $time_difference;
        $minutes = round($time_difference / 60);
        $hours = round($time_difference / 3600);
        $days = round($time_difference / 86400);
        $weeks = round($time_difference / 604800);
        $months = round($time_difference / 2419200);
        $years = round($time_difference / 29030400);
        // Seconds
        if ($seconds <= 60) {
            $timepassed = "$seconds seconds ago";
        }
        //Minutes
        else if ($minutes <= 60) {

            if ($minutes == 1) {
                $timepassed = "one minute ago";
            } else {
                $timepassed = "$minutes minutes ago";
            }
        }
        //Hours
        else if ($hours <= 24) {

            if ($hours == 1) {
                $timepassed = "one hour ago";
            } else {
                $timepassed = "$hours hours ago";
            }
        }
        //Days
        else if ($days <= 7) {

            if ($days == 1) {
                $timepassed = "one day ago";
            } else {
                $timepassed = "$days days ago";
            }
        }
        //Weeks
        else if ($weeks <= 4) {

            if ($weeks == 1) {
                $timepassed = "one week ago";
            } else {
                $timepassed = "$weeks weeks ago";
            }
        }
        //Months
        else if ($months <= 12) {

            if ($months == 1) {
                $timepassed = "one month ago";
            } else {
                $timepassed = "$months months ago";
            }
        }
        //Years
        else {

            if ($years == 1) {
                $timepassed = "one year ago";
            } else {
                $timepassed = "$years years ago";
            }
        }
        return $timepassed;
    }

    //convert time
    function convertTime($time, $outputformat = 'd M Y h:i a', $inputformat = 'Y-m-d H:i:s') {
        $datetime = \DateTime::createFromFormat($inputformat, $time);
        if ($datetime instanceof \DateTime) {
            $exactdatetime = $datetime->format($outputformat);
            return $exactdatetime;
        }
        return false;
    }

    //get the time stamp
    function dateTimeStamp() {
        $current_time = date('Y-m-d H:i:s');
        return $current_time;
    }

}
