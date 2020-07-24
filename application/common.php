<?php

// 读取系统配置
function sysconf($name)
{
    return \app\common\model\Config::getVarValue($name);
}