<?php
/**
 *  author: 谭潇
 *  create: 2020-07-23 15:06
 *  description:
 */

namespace app\lib;


use app\lib\admin\Auth;
use think\Db;
use think\facade\View;
use think\Request;
use traits\controller\Jump;

class AdminAuth
{

    use Jump;

    private $request;

    private $auth;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->auth = new Auth();
    }

    public function start()
    {

        if (empty(session('admin')) ){
            $this->error('请重新登录');
        }

        // 过滤参数
        $urls = explode('?', $this->request->url() );
        $url = $urls[0];
        if (!$this->auth->check($url, session('admin')->id )){
            $this->error('无权限访问');
        }

        $this->getMenus();

    }


    /**
     * 获取菜单操作
     */
    public function getMenus()
    {

        // TODO: 校验是否拥有全部权限
        $isAll = false;


        $menuIds = array();
        // 初始化菜单信息
        $groups = $this->auth->getGroups(session('admin')->id);
        foreach($groups as $group) {
            if ($group['rules'] == 'all'){
                $isAll = true;
                break;
            }
        }

        // 校验是否拥有所有权限
        if ($isAll){
            $menuIds = Db::table($this->auth->_config['auth_rule'])->column('id');
        }else{
            foreach($groups as $group) {
                if (is_string($group['rules']) ){
                    $menuIds = array_merge($menuIds, explode(',', $group['rules'] ) );
                }
            }
        }

        // 获取顶级全部菜单
        $sysMenu['headerMenus'] = Db::table($this->auth->_config['auth_rule'])
            ->where(['id'=>$menuIds])
            ->where(['status'=>1, 'pid'=>0, 'level'=>1, 'type'=>1])
            ->field('id, title, rule, icon')
            ->order('id','asc')
            ->select();

        $parentWhere = array();
        if (!empty($this->request->get('mid') ) ){
            $parentWhere['pid'] = $this->request->get('id');
            $sysMenu['headerMenus']['active'] = $this->request->get('mid');
        }else{
            $parentWhere['pid'] = $sysMenu['headerMenus'][0]['id'];
            $sysMenu['headerMenus']['active'] = empty($sysMenu['headerMenus'][0]) ? 0 : $sysMenu['headerMenus'][0]['id'];
        }

        // 获取二级菜单列表
         $parentMenus = Db::table($this->auth->_config['auth_rule'])
            ->alias('A')
            ->field([
                'A.*',
                '(select count(id) from '.$this->auth->_config['auth_rule'].' where pid = A.id AND type = 1 AND level = 3) as counts'
            ])->where(['id'=> $menuIds, 'status'=>1, 'level'=> 2, 'type'=>1])
            ->where($parentWhere)
            ->order('id', 'asc');

        $parentMenuIds = $parentMenus->column('id');
        $sysMenu['leftMenus']['parentMenus'] = $parentMenus->select();

        // 获取三级菜单列表
        $sysMenu['leftMenus']['childrenMenu'] = Db::table($this->auth->_config['auth_rule'])
            ->where([
                'status' => 1,
                'level' => 3,
                'type'=>1
            ])
            ->where(['pid'=>$parentMenuIds])
            ->order('id','asc')
            ->select();


        View::share('sysMenu', $sysMenu);
    }
}