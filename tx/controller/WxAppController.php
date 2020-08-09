<?php


namespace tx\controller;


use EasyWeChat\Factory;
use think\App;
use think\Controller;

class WxAppController extends Controller
{

    /**
     * 数据池
     * @var array|object
     */
    private $vars;

    public function __construct(App $app = null)
    {
        bind('tx\controller\WxAppController', $this);
        $config = config('wechat.mini_program.default');
        $this->app = Factory::miniProgram($config);
        $this->initialize();
        parent::__construct($app);
    }

    public function initialize()
    {
        // 将数据库配置信息注入数据池
        $this->sys = sysconf();
    }

    public function __get($name)
    {
        if (!isset($this->$name) ){
            return isset($this->vars['sys'][$name]) ? $this->vars['sys'][$name] : sysconf($name);
        }
        return $this->$name;
    }

    public function __set($name, $value)
    {
        if (!isset($this->$name) ) $this->vars[$name] = $value;
        $this->$name = $value;
    }

    protected function auth($code)
    {
        $this->app->auth->session($code);
    }
}