<?php
/**
 *  author: 谭潇
 *  create: 2020-07-27 17:30
 *  description: API接口验权
 */

namespace app\lib;


use app\lib\validate\ApiAuthValidate;
use think\Request;

/**
 * 接口授权类
 * Class WxAppAuth
 * @package app\lib
 */
class WxAppAuth
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('apiAuth.apiKey');
    }

    public function start(Request $request)
    {
        //$member = new Member();
        $response = new ServerResponse();

        $validate = new ApiAuthValidate();

        $data = $request->only(['openid', 'timestamp', 'memberId','signStr']);
        if (!$validate->check($data) ){
            $response->msg($validate->getError())->error();
        }

        if ((time() - $data['timestamp']) > config('apiAuth.timeout')){
            $response->msg('请求超时')->error();
        }

//        if ($member->isRegisterById($data['memberId']) ){
//            $response->msg('会员信息不存在')->error();
//        }

        $requestSign = $data['signStr'];
        unset($data['signStr']);
        $sign = $this->generateSign($data);

        if ($sign != $requestSign){
            $response->msg('签名错误')->error();
        }
    }

    /**
     * 生成签名
     * @param array $data
     * @return string
     */
    public function generateSign(array $data)
    {
        $openSSL = new OpensslEncrypt();
        $str = '';
        foreach($data as $key => $value){
            $str .= $key . '=' . $value . '&';
        }
        $result = substr($str,0,strlen($str) -1 );
        $desResult = $openSSL->encrypt($result,$this->apiKey,$this->apiKey );
        $md5Result = md5($desResult);
        $strMd5Result1 = substr($md5Result, 0, 24);
        $strMd5Result2 = substr($md5Result, strlen($md5Result) - 8, strlen($md5Result));
        $sign = $strMd5Result2 . $strMd5Result1;
        return $sign;
    }
}