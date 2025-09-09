<?php

namespace App\Models;

enum ModelMorphEnum: string
{
    case USER = 'User';
    case CLIENT = 'Client';
    case PROJECT = 'Project';
    case TICKET = 'Ticket';
    case LEAD = 'Lead';
    case COMPANY = 'Company';
    case VISIT = 'Visit';
}
