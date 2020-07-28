<?php
/**
 *  author: 谭潇
 *  create: 2020-07-23 15:06
 *  description:
 */

namespace tx\helper;


use think\Request;
use traits\controller\Jump;
use tx\auth\AdminAuth;

class AdminAuthHelper
{

    use Jump;

    private $request;

    private $auth;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->auth = new AdminAuth();
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

    }
}