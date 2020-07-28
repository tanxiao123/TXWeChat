<?php
/**
 *  author: 谭潇
 *  create: 2020-07-27 17:31
 *  description:
 */

namespace tx;

/**
 * 消息解密类
 * Class OpensslEncrypt
 * @package app\tx
 */
class OpensslEncrypt
{
    /**
     * 解密字符串
     * @param string $aesCipher 字符串
     * @param string $key 加密KEY
     * @param string $iv
     * @return string
     */
    public function decrypt($aesCipher, $key, $iv)
    {
        $decrypted = openssl_decrypt(base64_decode($aesCipher), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }

    /**
     * 加密字符串
     * @param $data
     * @param $key
     * @param $iv
     * @return string
     */
    public function encrypt($data, $key, $iv)
    {
        return base64_encode(openssl_encrypt($data,'AES-128-CBC',$key, OPENSSL_RAW_DATA, $iv));
    }
}