<?php
/**
 *  author: 谭潇
 *  create: 2020-07-27 17:36
 *  description:
 */

namespace tx\validate;


use think\Validate;

class ApiAuthValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'openid' => 'require|checkUndefined',
        'timestamp' => 'require|checkUndefined|number',
        'signStr' => 'require|checkUndefined',
        'memberId' => 'require|checkUndefined',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */
    protected $message = [
        'openid.require' => 'openId为空',
        'timestamp.require' => '时间戳为空',
        'timestamp.number' => 'timestamp非法数字串',
        'signStr.require' => '签名为空',
        'memberId.require' => '会员ID为空',
    ];

    protected function checkUndefined($value, $rule, $data=[])
    {
        reset($data);
        return $value == 'undefined' ? key($data).' is undefined ' : true;
    }
}