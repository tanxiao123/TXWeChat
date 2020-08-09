<?php

namespace app\common\model;

use think\Model;

class Config extends Model
{
    protected $table = "tx_config";

    public static function getVarValue($var_name)
    {
        if (isset($var_name) ){
            return static::where('var_name', $var_name)
                ->where('status',1)
                ->value('var_value');
        }
        $array = array();
        $result = static::where('status', 1)->field('var_name, var_value')->select();
        foreach($result as $value){
            $array[$value['var_name'] ] = $value['var_value'];
        }
        return $array;
    }
}
