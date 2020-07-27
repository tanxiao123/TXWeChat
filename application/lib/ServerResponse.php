<?php
/**
 *  author: 谭潇
 *  create: 2020-07-27 17:34
 *  description:
 */

namespace app\lib;


use think\exception\HttpResponseException;
use think\facade\Response;

/**
 * 请求响应类
 * Class ServerResponse
 * @package app\lib
 */
class ServerResponse
{
    private $header = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Headers' => 'X-Requested-With,Content-Type',
        'Access-Control-Allow-Methods' => 'GET,POST,PATCH,PUT,DELETE,OPTIONS'
    ];

    private $responseType = 'json';

    private $msg;

    private $status;

    private $data = [];


    public function responseType($type)
    {
        $this->responseType = $type;
        return $this;
    }

    public function msg($msg)
    {
        $this->msg = $msg;
        return $this;
    }

    public function status($status)
    {
        $this->status = $status;
        return $this;
    }

    public function success($data = [])
    {
        $result['status'] = empty($this->status) ? 1 : $this->status;
        $result['msg'] = empty($this->msg) ? 'SUCCESS' : $this->msg;
        $result['data'] = $data;
        $response = Response::create($result, $this->responseType)->header($this->header);
        throw new HttpResponseException($response);
    }

    public function error($data = [])
    {
        $result['status'] = empty($this->status) ? -1 : $this->status;
        $result['msg'] = empty($this->msg) ? 'ERROR' : $this->msg;
        $result['data'] = $data;
        $response = Response::create($result, $this->responseType)->header($this->header);
        throw new HttpResponseException($response);
    }

    public function response()
    {
        $result['status'] = $this->status;
        $result['msg'] = $this->msg;
        $result['data'] = $this->data;
        $response = Response::create($result, $this->responseType)->header($this->header);
        throw new HttpResponseException($response);
    }
}