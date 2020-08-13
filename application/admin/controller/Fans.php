<?php
/**
 *  author: 谭潇
 *  create: 2020-08-13 14:37
 *  description:
 */

namespace app\admin\controller;

use tx\controller\AdminController;
use tx\ServerResponse;
use tx\service\PageService;

class Fans extends AdminController
{
    protected $table = "fans";

    public function index()
    {
        $this->assign('title','粉丝管理');
        return $this->fetch();
    }

    public function getFansList(ServerResponse $response)
    {
        $result = PageService::instance()->setQuery($this->table)->orm();
        $response->layResponse($result['result'], $result['total']);
    }
}