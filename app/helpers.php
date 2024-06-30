<?php

/**
 * Date formater
 *
 * @param string $date
 * @param string $format
 */
if (!function_exists('dateFormat')) {
    function dateFormat($date, $format = 'd M Y')
    {
        return date($format, strtotime($date));
    }
}