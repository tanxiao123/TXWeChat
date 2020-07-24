<?php

namespace app\common\model;

use think\Model;

class Config extends Model
{
    protected $table = "tx_config";

    public static function getVarValue($var_name)
    {
        return static::where('var_name', $var_name)
            ->where('status',1)
            ->value('var_value');
    }
}
