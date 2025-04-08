<?php

/**
 * Convert boolean is_due_checked status to readable text
 * 
 * @param bool|null $isChecked
 * @param string $doneText
 * @param string $dueText
 * @return string
 */
function status_text($isChecked, $doneText = 'Done', $dueText = 'Due')
{
    if ($isChecked === null) {
        return $dueText; // Default jika null dianggap Due
    }
    
    return $isChecked ? $doneText : $dueText;
}

/**
 * Get badge HTML based on is_due_checked status
 * 
 * @param bool|null $isChecked
 * @param string $doneClass
 * @param string $dueClass
 * @return string
 */
function status_badge($isChecked, $doneClass = 'bg-green-100 text-green-800', $dueClass = 'bg-red-100 text-red-800')
{
    $status = status_text($isChecked);
    $class = $isChecked ? $doneClass : $dueClass;
    
    return '<span class="px-2 py-1 rounded-full text-xs font-medium '.$class.'">'.$status.'</span>';
}