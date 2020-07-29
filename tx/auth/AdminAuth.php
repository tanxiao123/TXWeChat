<?php
/**
 *  author: 谭潇
 *  create: 2020-07-23 15:06
 *  description:
 */

namespace tx\auth;


use think\Request;
use traits\controller\Jump;
use tx\extend\AdminAuthExtend;

class AdminAuth
{

    use Jump;

    private $request;

    private $auth;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->auth = new AdminAuthExtend();
    }

    public function start()
    {
        if (empty(session('admin')) ){
            $this->error('请重新登录');
        }
        // 过滤参数
        $module = $this->request->module();
        $controller = strtolower($this->request->controller());
        $action = $this->request->action();
        $url = "/".$module."/".$controller."/".$action;
        if ($url == "/admin/index/index"){
            return true;
        }
        if (!$this->auth->check($url, session('admin')->id )){
            $this->error('无权限访问');
        }

    }
}