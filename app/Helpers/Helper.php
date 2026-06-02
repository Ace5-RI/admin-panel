<?php

namespace App\Helpers;

use App\Models\Setting;

class Helper
{
    public static function companyName()
    {
        return Setting::get('company_name', 'Bali Solution Biz');
    }
}