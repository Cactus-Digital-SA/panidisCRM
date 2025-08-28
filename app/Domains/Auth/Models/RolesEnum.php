<?php

namespace App\Domains\Auth\Models;

enum RolesEnum: int
{
    case SuperAdmin = 1;
    case Administrator = 2;
    case API = 3;

    case FINANCE = 5;

    case RND_DIRECTOR = 11;
    case RND_ENG = 12;

    case SALES_DIRECTOR = 21;
    case SALES_SKG = 22;
    case SALES_ATH = 23;

    case LOGISTICS_SKG = 31;
    case LOGISTICS_ATH = 32;

}
