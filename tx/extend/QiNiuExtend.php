<?php


namespace tx\extend;


use app\common\model\Config;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use Qiniu\Zone;
use think\Exception;
use think\File;

class QiNiuExtend
{
    private $auth;

    private $bucketName;

    private $uploadManager;

    private $bucketManager;

    private $img_url;

    public function __construct()
    {
        $this->bucketName = config('qiniu.bucketName');
        $this->auth = new Auth(config('qiniu.accessKey'), config('qiniu.secretKey') );
        $this->img_url = config('qiniu.img_url');
        $zone = new Zone(array('upload-dg.qiniup.com') );
        $config  = new Config($zone);
        $this->uploadManager = new UploadManager($config);
        $this->bucketManager = new BucketManager($this->auth, $config);
    }

    /**
     * 通过ThinkFile对象进行上传文件
     * @param File $file
     * @return array
     * @throws \Exception
     */
    public function uploadTpFile(File $file)
    {
        $response = array();
        $filePath = $file->getRealPath();
        $token = $this->auth->uploadToken($this->bucketName);
        $file_name = uniqid(config('file_prefix') );
        try {
            list($ret, $err) = $this->uploadManager->putFile($token, $file_name, $filePath);
            if ($err != null){
                $response['code'] = -1;
                $response['msg'] = json_encode($err);
            }else{
                $response['code'] = 1;
                $response['msg'] = "SUCCESS";
                $response['data'] = $this->img_url.$file_name;
            }
            return $response;
        }catch (Exception $exception){
            $response['code'] = -2;
            $response['msg'] = $exception->getMessage();
        }
    }
}