<?php

namespace App\controllers;

use \App\items\Days;
use \App\items\Rankings;
use \App\core\Debug;
use \App\core\Langs;

class Standings
{    
    /**
     * Monthly ranking list from all Days from the month
     *
     * @return array
     */
    static function standings_list() : array
    {
        $days_list = [];
        foreach (Days::days_list() as $value) {
            $day_date = explode('-', $value['date']);
            if ($day_date[1] === self::get_month() && $day_date[2] === self::get_year()) {
                $days_list[] = $value['id'];
            }
        }
        $standings_list = Rankings::rankings_month_list($days_list);
        return $standings_list;
    }
    
    /**
     * Get the current month or the month from url date param
     *
     * @return string
     */
    static function get_month() : string
    {
        if (!isset($_GET['date']) || $_GET['date'] === '') {
            $day = new \DateTime();
            $month = $day->format('m');
        } else {
            $day = explode('-', $_GET['date']);
            $month = $day[0];
        }
        return $month;
    }

    /**
     * Get the current year or the year from url date param
     *
     * @return string
     */
    static function get_year() : string
    {
        if (!isset($_GET['date']) || $_GET['date'] === '') {
            $day = new \DateTime();
            $year = $day->format('Y');
        } else {
            $day = explode('-', $_GET['date']);
            $year = $day[1];
        }
        return $year;
    }
    
    /**
     * Build the options for select from all Days date
     *
     * @return array
     */
    static function month_select() : array
    {
        foreach(Langs::get_lang_files() as $file) {
            include $file;
        }
        $dates = [];
        foreach (array_reverse(Days::days_list()) as $key => $value) {
            $day_date = explode('-', $value['date']);
            $dates[] = $day_date[1] . '-' . $day_date[2];
        }
        $dates = array_unique($dates);
        $options = [];
        foreach ($dates as $value) {
            $date = explode('-', $value);
            $selected = isset($_GET['date']) && $_GET['date'] === $value ? ' selected' : '';
            $options[] = '<option value="' . $value . '"' . $selected . '>' . $lang['month.' . $date[0]] . ' ' . $date[1] . '</option>';
        }
        return $options;
    }
    
    /**
     * Build Standings h1 label ( for french )
     *
     * @param  string $month
     * @return string
     */
    static function month_label($month) : string
    {
        foreach(Langs::get_lang_files() as $file) {
            include $file;
        }
        $vowel = ['A', 'E', 'I', 'O', 'U', 'Y'];
        $month_label = $lang['month.' . $month];
        if (in_array($month_label[0], $vowel)) {
            return $label = str_replace(':de', 'd\'', $lang['standings.title']) . $month_label;
        } else {
            return $label = str_replace(':de', 'de ', $lang['standings.title']) . $month_label;
        }
        
    }
}