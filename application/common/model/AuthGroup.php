<?php

namespace app\common\model;

use think\Model;

class AuthGroup extends Model
{
    public static function getGroupRules($uid)
    {
        $gid = AdminGroupAccess::getGroupIdByUid($uid);
        $ruleIds = self::where('id', $gid)->value('rules');
        return $ruleIds;
    }
}
