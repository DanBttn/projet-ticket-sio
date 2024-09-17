<?php

namespace App\Entity;

enum Type: String
{
    case Application = 'application';
    case Messaging = 'messaging';
    case Databases = 'databases';
    case Networks = 'networks';
    case Servers = 'servers';

    case Storage = 'storage';

    case Security = 'security';
    case Others = 'others';
}
