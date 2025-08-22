<?php

if (! function_exists('activeClass')) {
    /**
     * Get the active class if the condition is not falsy.
     *
     * @param  $condition
     * @param string $activeClass
     * @param string $inactiveClass
     * @return string
     */
    function activeClass($condition, string $activeClass = 'active', string $inactiveClass = ''): string
    {
        return $condition ? $activeClass : $inactiveClass;
    }
}

