<?php

namespace App\Enums;

enum SeriesStateEnum:string
{
    case ACTIVE = 'ACTIVE';
    case FINISHED = 'FINISHED';
    case PREPARING = 'PREPARING';
}
