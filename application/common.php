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
    foreach ($list as $vo){
        if (isset($vo[$pid]) && isset($tmp[$vo[$pid]])){
            $tmp[$vo[$pid]][$sub][] = &$tmp[$vo[$cid]];
        }else{
            $tree[] = &$tmp[$vo[$cid]];
        }
    }
    unset($tmp, $list);
    return $tree;
}

function getViewTree($list,$checked, $cid = 'id', $pid = 'pid', $sub = 'sub', $node = 'node')
{
    list($tree, $tmp) = [[], array_combine(array_column($list, $cid), array_values($list))];
    $checked = array_combine(array_column($checked, $cid), array_values($checked));
    foreach ($list as $vo){
        $tmp[$vo[$cid]]['checked'] = !empty($checked[$vo[$cid]]);
        $tmp[$vo[$cid]]['pnode'] = !empty($vo[$pid]) ? $checked[$vo[$pid]][$cid] : "";
        if (isset($vo[$pid]) && isset($tmp[$vo[$pid]])){
            $tmp[$vo[$pid]][$sub][] = &$tmp[$vo[$cid]];
        }else{
            $tree[] = &$tmp[$vo[$cid]];
        }
    }
    unset($tmp, $list);
    return $tree;
}

/**
 * 一维数组生成数据树
 * @param array $list 待处理数据
 * @param string $cid 自己的主键
 * @param string $pid 上级的主键
 * @param string $cpath 当前 PATH
 * @param string $ppath 上级 PATH
 * @return array
 */
function arr2table(array $list, $cid = 'id', $pid = 'pid', $cpath = 'path', $ppath = '')
{
    $tree = [];
    foreach (arr2tree($list, $cid, $pid) as $attr) {
        $attr[$cpath] = "{$ppath}-{$attr[$cid]}";
        $attr['sub'] = $attr['sub'] ?? [];
        $attr['spt'] = substr_count($ppath, '-');
        $attr['spl'] = str_repeat("　├　", $attr['spt']);
        $sub = $attr['sub'];
        unset($attr['sub']);
        $tree[] = $attr;
        if (!empty($sub)) $tree = array_merge($tree, arr2table($sub, $cid, $pid, $cpath, $attr[$cpath]));
    }
    return $tree;
}



