<?php

namespace app\common\model;

use think\Model;

class AuthRule extends Model
{

    public function getTree()
    {
        $result = $this->where(['status'=> '1'])->order('id asc')->select();
        return arr2tree($this->buildData($result->toArray() ));
    }

    public function buildData(array $menus)
    {
        $admin = session('admin');
        if (!$admin) return [];
        $ruleStr = AuthGroup::getGroupRules($admin->id);
        if ($ruleStr == 'all'){
            return $menus;
        }
        $rules = explode(',', $ruleStr);
        foreach($menus as $key => $val) {
            if (!in_array($val['id'], $rules) ){
                unset($menus[$key]);
            }
        }
        return $menus;
    }


}
