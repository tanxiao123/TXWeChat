<?php
/**
 *  author: 谭潇
 *  create: 2020-07-28 14:42
 *  description:
 */

namespace tx;


use think\App;
use think\Container;
use tx\controller\AdminController;

abstract class Service
{
    protected $app;

    protected $controller;

    public function __construct(AdminController $controller, App $app)
    {
        $this->app = $app;

        $this->controller = $controller;
    }

    protected function buildQuery($dbQuery)
    {
        return is_string($dbQuery) ? $this->app->db()->name($dbQuery) : $dbQuery;
    }

    public static function instance(...$args): Service
    {
        return Container::getInstance()->invokeClass(static::class, $args);
    }
}