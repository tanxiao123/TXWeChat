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
use think\captcha\Captcha;
use think\Controller;
use think\facade\Validate;
use think\Request;
use tx\middleware\AdminAuthMiddleware;

class Index extends Controller
{

    protected $middleware = [
        AdminAuthMiddleware::class => ['except'=>['login','doLogin','loginOut'] ]
    ];

    /**
     * 加载首页
     * @param AuthRule $authRule
     * @return mixed
     */
    public function index(AuthRule $authRule)
    {
        $menus = $authRule->getTree();
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
        $data = $request->only(['username','password','captcha']);
        $validate = Validate::make([
            'username'=>'require',
            'password'=>'require',
            'captcha'=>'require|captcha'
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

    /**
     * 生成验证码
     * @return \think\Response
     */
    public function verify()
    {
        $config = [
            // 验证码字体大小
            'fontSize'    =>    15,
            // 验证码位数
            'length'      =>    3,
            // 关闭验证码杂点
            'useNoise'    =>    false,
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }

    /**
     * 基础资料模块
     * @param Request $request
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function basics(Request $request)
    {
        $aid = $request->param('id');
        $admin = Admin::where('id', $aid)->find();
        if (empty($admin) ){
            $this->error('admin not found!');
        }
        if ($request->isPost() ){
            $data = $request->only(['account','username','password','confirmPassword']);
            $validate = Validate::make(['account'=>'require','username'=>'require','password'=>'require','confirmPassword'=>'require|confirm:password']);
            if (!$validate->check($data) ){
                $this->error($validate->getError());
            }
            $data['password'] = $admin['password'] == $data['password'] ? $admin['password'] : crypt($data['password'], $admin->password_reset_token);
            $upData = [
                'account' => $data['account'],
                'username' => $data['username'],
                'password' => $data['password'],
                'status' => 1,
                'update_time' => date('Y-m-d H:i:s')
            ];
            !empty($request->param('phone')) && $upData['phone'] = $request->param('phone');
            !empty($request->param('email')) && $upData['email'] = $request->param('email');
            !empty($request->param('login_number')) && $upData['login_number'] = $request->param('login_number');
            Admin::where('id', $aid)->update($upData);
            session('admin', Admin::where('id', $aid)->find() );
            $this->success('SUCCESS');
        }
        $this->assign('vo', $admin);
        return $this->fetch();
    }
}
