<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case WAITING  = 'waiting';
    case STARTWOEK = 'startWork';
    case ENDWORK = 'endwork';
    case ENDED = 'ended';
}
