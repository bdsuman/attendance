<?php

if (! function_exists('convert_time_to_seconds')) {
    /**
     * Convert a time string to seconds.
     *
     * Supported input formats:
     * - "HH:MM" or "H:MM"       => hours and minutes
     * - "HH:MM:SS"               => hours, minutes and seconds
     * - numeric string (e.g. "90") => treated as minutes
     * - integer (e.g. 3600)       => treated as seconds
     *
     * Returns an integer number of seconds (0 on invalid input).
     *
     * @param mixed $time
     * @return int
     */
    function convert_time_to_seconds($time): int
    {
        if (is_null($time) || $time === '') {
            return 0;
        }

        // If it's already numeric and contains no colon, treat as minutes
        if (is_numeric($time) && strpos((string)$time, ':') === false) {
            return (int)$time * 60;
        }

        $time = trim((string) $time);

        if (strpos($time, ':') !== false) {
            $parts = array_map('intval', explode(':', $time));

            // HH:MM:SS
            if (count($parts) === 3) {
                return ($parts[0] * 3600) + ($parts[1] * 60) + $parts[2];
            }

            // HH:MM
            if (count($parts) === 2) {
                return ($parts[0] * 3600) + ($parts[1] * 60);
            }

            // fallback: single numeric part
            return (int) $parts[0];
        }

        // Not recognized -> 0
        return 0;
    }
}

if (! function_exists('seconds_to_hhmm')) {
    /**
     * Convert seconds to HH:MM format.
     * Returns null for null/empty input.
     *
     * @param mixed $seconds
     * @return string|null
     */
    function seconds_to_hhmm($seconds): ?string
    {
        if ($seconds === null || $seconds === '') {
            return null;
        }

        $s = (int) $seconds;
        $hours = floor($s / 3600);
        $minutes = floor(($s % 3600) / 60);
        return sprintf('%02d:%02d', $hours, $minutes);
    }
}
