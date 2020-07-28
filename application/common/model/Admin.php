<?php

namespace app\common\model;

use think\Model;

class Admin extends Model
{
    public static function addLoginError($adminId)
    {
        self::where('id', $adminId)->setInc('login_number', 1);
    }
}
