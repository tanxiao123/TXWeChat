<?php


namespace tx\controller;


use EasyWeChat\Factory;
use think\App;
use think\Controller;

class WxAppController extends Controller
{

    /**
     * 微信小程序Application
     * @var \EasyWeChat\MiniProgram\Application
     */
    protected $app;

    /**
     * 数据池
     * @var array|object
     */
    private $vars;

    public function __construct(App $app = null)
    {
        bind('tx\controller\WxAppController', $this);
        $config = config('mini_program.default');
        $this->app = Factory::miniProgram($config);
        parent::__construct($app);
    }

    public function __get($name)
    {
        if (!isset($this->$name) ){
            return isset($this->vars[$name]) ? $this->vars[$name] : sysconf($name);
        }
        return $this->$name;
    }

    protected function auth($code)
    {
        $this->app->auth->session($code);
    }


}