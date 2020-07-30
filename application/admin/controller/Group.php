<?php
/**
 *  author: 谭潇
 *  create: 2020-07-27 13:26
 *  description:
 */

namespace app\admin\controller;


use think\Controller;
use think\Request;
use think\Validate;
use tx\controller\AdminController;
use tx\ServerResponse;
use tx\service\PageService;

class Group extends AdminController
{


    public $table = "auth_group";

    protected $middleware = ['AdminAuthMiddleware'];

    public function index(Request $request)
    {
        $this->assign('title','访问权限管理');
        return $this->fetch();
    }

    public function getRuleList(ServerResponse $response)
    {
        $result = PageService::instance()->setQuery($this->table)->queryWhere('status','<>',9)->orm();
        $result['result'] = arr2table($result['result']);
        $response->layResponse($result['result'], $result['total']);
    }

    public function edit()
    {
        return $this->_form($this->table,'form');
    }

    public function apply()
    {
        $validate = Validate::make(['id'=>'require','id.require'=>'权限ID不可为空']);
        if (!$validate->check($this->request->get() ) ){
            $this->error('权限ID不可为空');
        }
        if (input('action') === 'get'){
            $checked = $this->app->db()->name($this->table)->where('id', $this->request->get('id') )->value('rules');
            $list = $this->app->db()->name('auth_rule')->whereIn('id', $checked)->select();
            $list = arr2tree($list);
            $this->assign('list', $list);
            $this->success('成功');
        }elseif(input('action') === 'save'){

        }else{
            $this->assign('title', '权限配置节点');
            return $this->_form($this->table, 'apply');
        }
    }

    public function _form_filter(&$vo)
    {

    }
}