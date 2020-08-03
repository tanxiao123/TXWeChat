<?php


namespace tx\controller;


use EasyWeChat\Factory;
use EasyWeChat\Kernel\Exceptions\Exception;
use EasyWeChat\MiniProgram\Application;
use think\App;
use think\Controller;

class WxAppController extends Controller
{

    /**
     * 数据池
     * @var array|object
     */
    private $vars;

    /**
     * @var Application
     */
    protected $microMerchant;

    public function __construct(App $app = null)
    {
        bind('tx\controller\WxAppController', $this);
        $config = config('mini_program.default');
        $this->microMerchant = Factory::miniProgram($config);
        parent::__construct($app);
    }

    public function __get($name)
    {
        if (!isset($this->$name) ){
            return isset($this->vars[$name]) ? $this->vars[$name] : sysconf($name);
        }
        return $this->$name;
    }

    public function __set($name, $value)
    {
        if (!isset($this->$name) ){
            $this->vars[$name] = $value;
        }
    }

    protected function auth($code, $iv = null, $encryptedData = null)
    {
        try{
            $result = $this->microMerchant->auth->session($code);
            $openId = $result['openid'];
            $sessionKey = $result['session_key'];
            if (empty($openId)) {
                $this->error('openid not found！','',$result);
            } elseif (empty($sessionKey)) {
                $this->error('session_key not found!','',$result);
            }
            // 查询粉丝数据信息
            $fans = $this->app->db()->name('fans')->where('openid', $openId)->allowEmpty()->find();
            if (empty($fans) ){
                if (!isset($sessionKey) || !isset($encryptedData)) return $fans;
                $decryptedData = $this->microMerchant->encryptor->decryptData($sessionKey, $iv, $encryptedData);
                $fansData = array(
                    'openid' => $openId,
                    'nickname' => $decryptedData['nickname'],
                    'avatar' => $decryptedData['avatarUrl'],
                    'sex' => $decryptedData['sex'],
                    'language' => $decryptedData['language'],
                    'city' => $decryptedData['city'],
                    'province' => $decryptedData['province'],
                    'country' => $decryptedData['country'],
                    'create_time' => date('Y-m-d H:i:s')
                );
                $fansId = $this->app->db()->name('fans')->insertGetId($fansData);
                $fansData['id'] = $fansId;
                return $fansData;
            }
            return $fans;
        }catch (Exception $exception){
            $this->error($exception->getMessage() );
        }
    }
}