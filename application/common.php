<?php

// 读取系统配置
function sysconf($name)
{
    return \app\common\model\Config::getVarValue($name);
}

/**
 * 一维数组生成数据树
 * @param array $list 待处理数据
 * @param string $cid 自己的主键
 * @param string $pid 上级的主键
 * @param string $sub 子数组名称
 * @return array
 */
function arr2tree($list, $cid = 'id', $pid = 'pid', $sub = 'sub')
{
    list($tree, $tmp) = [[], array_combine(array_column($list, $cid), array_values($list))];
    foreach ($list as $vo) isset($vo[$pid]) && isset($tmp[$vo[$pid]]) ? $tmp[$vo[$pid]][$sub][] = &$tmp[$vo[$cid]] : $tree[] = &$tmp[$vo[$cid]];
    unset($tmp, $list);
    return $tree;
}