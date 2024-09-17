<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PINDING  = 'pinding';
    case APPOINTED = 'appointed';
    case STARTWORK = 'startWork';
    case ENDWORK = 'endwork';
    case STARTTEST = 'starttest';
    case ENDTEST = 'endtest';
    case ENDED = 'ended';
    case FALIED = 'falied';
}
