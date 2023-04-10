<?php

namespace App\Enums;

enum EntryStateEnum:string
{
    case WAITING = 'WAITING';
    case APPROVED = 'APPROVED';
    case BANNED = 'BANNED';
    case CANCELED = 'CANCELED';
}
