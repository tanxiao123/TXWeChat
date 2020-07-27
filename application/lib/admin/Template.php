<?php
/**
 *  author: 谭潇
 *  create: 2020-07-27 15:56
 *  description: 模板类
 */

namespace app\lib\admin;


use think\template\TagLib;

class Template extends TagLib
{
    protected $tags = [
        'table' => ['attr'=> 'headers,body,option,page', 'close'=>0],
        'tableScript' => ['close'=>0]
    ];

    protected $tableParseStr;

    public function tagTable($tag)
    {

        $this->tableParseStr = "<table class='layui-table'>\n";
        // TODO: 头部标签开始
        $this->tableParseStr .= '<thead><tr>';
        if (strpos($tag['headers'],'$') === 0){
            $headers = $tag['headers'];
            $this->autoBuildVar($headers);
        }else{
            $headers = $tag['headers'];
        }
        $this->tableParseStr .= "<?php foreach(".$headers." as \$header){ ?>";
        $this->tableParseStr .= "<th class='text-left nowrap'><?php echo \$header['title']; ?></th>";
        $this->tableParseStr .= "<?php } ?>";
        $this->tableParseStr .= '</tr></thead>';
        // TODO: 内容标签开始
        $this->tableParseStr .= '<tbody>';

        if (strpos($tag['body'],'$') === 0){
            $body = $tag['body'];
            $this->autoBuildVar($body);
        }else{
            $body = $tag['body'];
        }

        $this->tableParseStr .= "<?php foreach(".$body." as \$b){ ?>";

        $this->tableParseStr .= '<tr>';
            $this->tableParseStr .= "<?php foreach(\$b as \$k => \$v){ ?>";
                $this->tableParseStr .= "<td><?php echo \$v ?></td>";
            $this->tableParseStr .= "<?php } ?>";
        $this->tableParseStr .= "</tr>";

        $this->tableParseStr .= "<?php } ?>";
        $option = $tag['option'];
        $page = $tag['page'];
        // 加载表格模板

        $this->tableParseStr .= '</table>';
        return $this->tableParseStr;
    }

    private function makeTableHeader()
    {

    }


    private function makeTableBody()
    {

    }
}