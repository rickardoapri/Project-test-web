<?php

if (!function_exists('formatNumber')) {
    /**
     * Format number with thousand separator.
     *
     * @param  float|int  $number
     * @return string
     */
    function formatNumber($number)
    {
        return number_format($number, 0, '.', ',');
    }
}
