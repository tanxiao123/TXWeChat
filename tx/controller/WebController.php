<?php


namespace tx\controller;


use EasyWeChat\Factory;
use Naixiaoxin\ThinkWechat\Middleware\OauthMiddleware;
use think\App;
use think\Controller;
use tx\middleware\WebOauthMiddleware;

class WebController extends Controller
{
    /**
     * 数据池
     * @var array|object
     */
    private $vars;

    /**
     * @var \EasyWeChat\OfficialAccount\Application
     */
    protected $officialAccount;

    /**
     * 默认注册EasyWechat服务中间件进行Web授权
     * @var array
     */
    protected $middleware = [OauthMiddleware::class, WebOauthMiddleware::class];

    protected $user;

    public function __construct(App $app = null)
    {
        bind('tx\controller\WxAppController', $this);
        $config = config('wechat.official_account.default');
        $this->officialAccount = Factory::officialAccount($config);
        $this->user = session('wechat_oauth_user_default');
        parent::__construct($app);
    }
}