<?php

namespace app\common\model;

use think\Model;

class AdminGroupAccess extends Model
{
    public static function getGroupIdByUid($uid)
    {
        return self::where('uid', $uid)->value('group_id');
    }
}
