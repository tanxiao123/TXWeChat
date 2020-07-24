<?php

namespace app\lib\admin;

use think\Db;

class Auth
{
    public $_config = array(
        'auth_group' => 'auth_group',
        'admin_group_access' => 'auth_group_access',
        'auth_rule' => 'auth_rule',
        'auth_user' => 'admin'
    );

    public function __construct()
    {
        $prefix = config('database.prefix');
        $this->_config['auth_group'] = $prefix.'auth_group';
        $this->_config['admin_group_access'] = $prefix.'admin_group_access';
        $this->_config['auth_rule'] = $prefix.'auth_rule';
        $this->_config['auth_user'] = $prefix.'admin';
    }

    public function check($name, $uid)
    {
        $authList = $this->getAuthList($uid);
        if (is_string($name) ){
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = array($name);
            }
        }

        $list = array();
        foreach ( $authList as $auth ) {
            $query = preg_replace('/^.+\?/U','',$auth);
            if ( $query!=$auth ) {
                parse_str($query,$param); //解析规则中的param
                $intersect = array_intersect_assoc(unserialize( strtolower(serialize($_REQUEST)) ),$param);
                $auth = preg_replace('/\?.*$/U','',$auth);
                if ( in_array($auth,$name) && $intersect==$param ) {  //如果节点相符且url参数满足
                    $list[] = $auth ;
                }
            }else if (in_array($auth , $name)){
                $list[] = $auth ;
            }
        }
        return !empty($list) ? true : false;
    }


    public function getGroups($uid)
    {
        static $groups = array();
        if (isset($groups[$uid]) ){
            return $groups[$uid];
        }
        $user_groups = Db::table($this->_config['admin_group_access'])
            ->alias('A')
            ->leftJoin($this->_config['auth_group'].' B', 'A.group_id = B.id')
            ->where(['A.uid'=>$uid,'B.status'=>1])
            ->field('uid, group_id, title, rules')
            ->select();
        $groups[$uid] = $user_groups ?: array();
        return $groups[$uid];
    }
    
    protected function getAuthList($uid)
    {
        static $_authList = array(); // 保存用户验证通过的权限列表
        if (isset($_authList[$uid]) ){
            return $_authList[$uid];
        }
        $groups = $this->getGroups($uid);

        $ids = array();
        foreach($groups as $group) {
            $ids = array_merge($ids, explode(',', trim($group['rules']) ) );
        }
        if (in_array("all", $ids) ){
            $rules = Db::table($this->_config['auth_rule'])->field('title, rule, pid')->select();
            $authList = array();
            foreach($rules as $rule){
                $authList[] = strtolower($rule['rule']);
            }
            $_authList[$uid] = $authList;
            return array_unique($authList);
        }else{
            $ids = array_unique($ids);
            if (empty($ids)){
                $_authList[$uid] = array();
                return array();
            }
            $where[] = ['id','in', $ids];
            $where[] = ['status','=',1];
            // 读取用户组所有的权限规则
            $rules = Db::table($this->_config['auth_rule'])->where($where)->field('title, rule, pid')->select();
            // 循环规则 判断结果
            $authList = array();
            foreach($rules as $rule){
                $authList[] = strtolower($rule['rule']);
            }

            $_authList[$uid] = $authList;

            return array_unique($authList);
        }

    }
}