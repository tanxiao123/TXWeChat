<?php
/**
 *  author: Administrator
 *  create: 2020-07-28 14:42
 *  description:
 */

namespace tx;


use think\App;

abstract class Service
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->initialize();
    }

    public function initialize()
    {
        return $this;
    }

    public static function instance()
    {
        
    }
}