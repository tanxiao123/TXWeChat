<?php
/**
 *  author: 谭潇
 *  create: 2020-07-23 15:06
 *  description:
 */

namespace app\lib;


use app\lib\admin\Auth;
use think\Db;
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

        // 过滤参数
        $urls = explode('?', $this->request->url() );
        $url = $urls[0];
        if (!$this->auth->check($url, session('admin')->id )){
            $this->error('无权限访问',config('admin.homeUrl') );
        }

        // 当前登录错误次数
        $login_times = Db::table(config('auth.auth_config.auth_user') )->value('login_times');
        if ($login_times > config('admin.loginTimes') ){
            $this->error('登录失败次数超过'.config('admin.loginTimes').'次，请联系管理员操作', config('admin.homeUrl')  );
        }
    }
}