<?php
/**
 *  author: 谭潇
 *  email: 483586199@qq.com
 *  create: 2020-07-23 14:42
 *  description:
 */

namespace app\admin\controller;


use app\common\model\Admin;
use app\common\model\AuthRule;
use think\Controller;
use think\facade\Validate;
use think\Request;

class Index extends Controller
{

    protected $middleware = [
        'AdminAuthMiddleware' => ['except'=>['login','doLogin','loginOut'] ]
    ];

    /**
     * 加载首页
     * @param AuthRule $authRule
     * @return mixed
     */
    public function index(AuthRule $authRule)
    {
        $menus = $authRule->getTree();
        //return json($menus);
        $this->assign('menus', $menus);
        return $this->fetch('index/index');
    }

    /**
     * 跳转登录
     * @return mixed
     */
    public function login()
    {
        return $this->fetch('login/index');
    }

    /**
     * 登录操作
     * @param Request $request
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
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
            Admin::addLoginError($admin->id);
            $this->error('密码错误');
        }

        // 当前登录错误次数
        $login_number = Admin::where('id', $admin->id )->value('login_number');
        $sys_login_number = sysconf('login_number');
        if ($login_number > $sys_login_number ){
            $this->error('登录失败次数超过'.$sys_login_number.'次，请联系管理员操作');
        }

        session('admin', $admin);
        $this->success('登录成功');
    }


    /**
     * 退出登录
     * @param Request $request
     * @return \think\response\Redirect
     */
    public function loginOut(Request $request)
    {
        session('admin', null);
        return redirect('/admin/login');
    }

    public function test()
    {
        return '这是测试界面1';
    }

    public function test2()
    {
        return '这是测试界面2';
    }

}
