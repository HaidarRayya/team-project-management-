<?php

namespace App\Enums;

enum EmployeeRole: string
{
    case MANAGER  = 'manager';
    case TESTER = 'tester';
    case DEVELOPER = 'developer';
}