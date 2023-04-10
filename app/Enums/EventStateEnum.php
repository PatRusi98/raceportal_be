<?php

namespace App\Enums;

enum EventStateEnum:string
{
    case UPCOMING = 'UPCOMING';
    case UNOFFICIAL = 'UNOFFICIAL';
    case OFFICIAL = 'OFFICIAL';
}
