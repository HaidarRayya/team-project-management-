<?php

namespace App\Enums;

enum TaskPriority: string
{
    case NORMAL  = 'normal';
    case MEDIUM = 'medium';
    case IMPORTANT = 'important';
}
