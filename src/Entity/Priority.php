<?php

namespace App\Entity;

enum Priority: String
{
   case Low = 'Low';
   case Medium = 'Medium';
   case High = 'High';
   case Critical = 'Critical';
}
