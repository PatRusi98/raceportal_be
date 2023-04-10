<?php

namespace App\Enums;

enum RoleEnum:string
{
    case ADMIN = 'ADMIN';
    case MANAGER = 'MANAGER';
    case DRIVER = 'DRIVER';
}
