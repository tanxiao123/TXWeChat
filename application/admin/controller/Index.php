<?php
/**
 *  author: 谭潇
 *  email: 483586199@qq.com
 *  create: 2020-07-23 14:42
 *  description:
 */

namespace app\admin\controller;


use app\common\model\Admin;
use think\Controller;
use think\Db;
use think\facade\Validate;
use think\Request;

class Index extends Controller
{

    public function index()
    {
        return $this->fetch('index/index');
    }

    public function login()
    {
        return $this->fetch('login/index');
    }

    public function doLogin(Request $request)
    {
        $data = $request->only(['username','password']);
        $validate = Validate::make([
            'username'=>'require',
            'password'=>'require'
        ]);
        if (!$validate->check($data) ){
            $this->error($validate->getError() );
        }
        $admin = Admin::where('username', $data['username'])->find();
        if (!$admin){
            $this->error('管理员不存在');
        }
        $dpPassword = crypt($data['password'], $admin->password_reset_token);
        if ($admin->password != $dpPassword){
            $this->error('密码错误');
        }

        // 当前登录错误次数
        $login_times = Admin::where('id', session('admin')->id )->value('login_times');
        $sys_login_times = var_config('login_times');
        if ($login_times > $sys_login_times ){
            $this->error('登录失败次数超过'.$sys_login_times.'次，请联系管理员操作');
        }

        session('admin', $admin);
        $this->success('登录成功');
    }


    public function loginOut(Request $request)
    {
        session('admin', null);
        return redirect('/admin/login');
    }
}