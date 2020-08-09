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
use tx\middleware\AdminAuthMiddleware;
use tx\ServerResponse;
use tx\service\PageService;

class Group extends AdminController
{
    protected $table = "auth_group";

    protected $middleware = [AdminAuthMiddleware::class];

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
        if (!$validate->check($this->request->param() ) ){
            $this->error('权限ID不可为空');
        }
        if (input('action') === 'get'){
            $list = $this->app->db()->name('auth_rule')->field('id,title,rule as node,pid')->select();
            $rules = $this->app->db()->name($this->table)->where('id', $this->request->param('id'))->value('rules');
            if (!empty($rules) && "all" == strtolower($rules) ){
                $checked = $list;
            }else{
                $checked = $this->app->db()->name($this->table)->whereIn('id', implode(',',$rules) )->select();
            }
            $list = getViewTree($list,$checked);

            $this->success('成功','',$list);
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