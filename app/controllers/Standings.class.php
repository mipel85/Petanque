<?php

namespace App\controllers;

use \App\items\Days;
use \App\items\Rankings;
use \App\core\Debug;

class Standings
{
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

    static function get_month()
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

    static function get_year()
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

    static function month_label(string $month) : string
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