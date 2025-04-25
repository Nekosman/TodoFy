<?php

/**
 * Get status text based on card status
 */
function status_text($status, $doneText = 'Completed', $dueText = 'Pending', $lateText = 'Late')
{
    return match($status) {
        'completed' => $doneText,
        'late' => $lateText,
        default => $dueText,
    };
}

/**
 * Get badge HTML based on card status
 */
function status_badge($status, $doneClass = 'bg-green-500 text-white', $dueClass = 'bg-yellow-500 text-white', $lateClass = 'bg-red-500 text-white')
{
    $class = match($status) {
        'completed' => $doneClass,
        'late' => $lateClass,
        default => $dueClass,
    };
    
    return '<span class="px-2 py-1 rounded-full text-xs font-medium '.$class.'">'.status_text($status).'</span>';
}

function format_date($date, $format = 'Y-m-d H:i', $fallback = '')
{
    if (empty($date)) {
        return $fallback;
    }

    try {
        if (!$date instanceof DateTime) {
            $date = new DateTime($date);
        }
        return $date->format($format);
    } catch (Exception $e) {
        return $fallback;
    }
}

function human_date($date, $fallback = '')
{
    if (empty($date)) {
        return $fallback;
    }

    try {
        if (!$date instanceof DateTime) {
            $date = new DateTime($date);
        }
        
        $now = new DateTime();
        $diff = $now->diff($date);
        
        if ($diff->days === 0) {
            return 'Today at ' . $date->format('H:i');
        } elseif ($diff->days === 1 && $date > $now) {
            return 'Tomorrow at ' . $date->format('H:i');
        } elseif ($diff->days === 1 && $date < $now) {
            return 'Yesterday at ' . $date->format('H:i');
        } elseif ($diff->days < 7) {
            return $date->format('l \\a\\t H:i');
        } else {
            return $date->format('M j, Y \\a\\t H:i');
        }
    } catch (Exception $e) {
        return $fallback;
    }
}