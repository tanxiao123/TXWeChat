<?php
/**
 *  author: 谭潇
 *  create: 2020-07-27 14:12
 *  description:
 */

namespace app\admin\controller;


use app\common\model\AuthRule;
use think\Request;
use tx\controller\AdminController;
use tx\ServerResponse;

class Rule extends AdminController
{

    protected $middleware = ['AdminAuthMiddleware'];

    protected $table = "auth_rule";

    public function index()
    {
        $this->assign('title','菜单管理');
        return $this->fetch();
    }

    public function getRuleList(Request $request, ServerResponse $response)
    {
        $count = AuthRule::where('status','<>',9)->count();
        $result = AuthRule::where('status','<>',9)->select();
        $result = arr2table($result->toArray());
        $response->layResponse($result,$count);
    }

    public function add()
    {
        return $this->_form($this->table, 'form');
    }

    public function _form_filter(&$vo)
    {
        if ($this->request->isGet() ){
            if (empty($vo['pid']) && $this->request->get('pid', 0) ){
                $vo['pid'] = $this->request->get('pid', 0);
            }
            $menus = $this->app->db()->name($this->table)->where(['status'=>1])->order('id desc')->column('id,pid,rule,title');
            $menus = arr2table(array_merge($menus, [['id'=>'0', 'pid'=>-1, 'rule'=>'#', 'title'=>'顶部菜单']]));

            if (isset($vo['id']) ){
                foreach ($menus as $key => $menu){
                    if ($menu['id'] === $vo['id']){
                        $vo = $menu;
                    }
                }
            }
            foreach($menus as $key => &$menu ) {
                if ($menu['spt'] >= 3 && $menu['rule'] !==  '#'){
                    unset($menus[$key]);
                }
                if (isset($vo['spt']) && $vo['spt'] <= $menu['spt']){
                    unset($menu[$key]);
                }
            }

            $this->assign('menus', $menus);

        }
    }

//    public function _form_result(&$vo)
//    {
//        if ($this->request->isPost() ){
//            if (!empty($this->request->post('where') )){
//                if (!empty($this->request->post('form_data'))){
//                    $this->app->db()->name($this->table)
//                        ->where($this->request->post('where') )
//                        ->update($this->request->post('form_data') );
//                }else{
//                    $this->app->db()->name($this->table)
//                        ->where($this->request->post('where') )
//                        ->delete();
//                }
//            }else if( empty($this->request->post('where')) && !empty($this->request->post('form_data') ) ){
//                $this->app->db()->name($this->table)->insertGetId($this->request->post());
//            }
//        }
//    }

}