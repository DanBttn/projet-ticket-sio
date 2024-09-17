<?php

namespace App\Entity;

enum Status: String
{
    case NEW = 'NEW';
    case IN_PROGRESS = 'IN_PROGRESS';
    case RESOLVED = 'RESOLVED';
    case REJECTED = 'REJECTED';

}
