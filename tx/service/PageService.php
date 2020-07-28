<?php


namespace tx\service;


use think\App;
use think\Db;
use think\db\Query;

class PageService
{
    /**
     * 查询对象
     * @var Query
     */
    protected $query;

    /**
     * @var App
     */
    protected $app;

    /**
     * 当前页数
     * @var int
     */
    protected $page;

    /**
     * 总条数
     * @var int
     */
    protected $total;

    /**
     * 每页显示条数
     * @var int
     */
    protected $limit;

    /**
     * WHERE条件
     * @var array
     */
    protected $where = array();

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function where($param = array() )
    {
        if (!isset($param) )return $this;
        if (!empty($this->where) ){
            array_merge($this->where, $param);
        }else{
            $this->where = $param;
        }
        return $this;
    }

    public function alias($name)
    {
        if (!isset($name) ) return $this;
        $this->query->alias($name);
        return $this;
    }

    public function join($array)
    {
        if (!isset($array) ) return $this;
        $this->query = $this->query->join($array['table'] || '', $array['condition'] || '');
        return $this;
    }

    public function orm($dbQuery, $page = 1, $limit = 10)
    {
        $this->page = $page <= 0 ? 1 : $page;
        $this->query = is_string($dbQuery) ? Db::name($dbQuery) : $dbQuery;
        $this->limit = $limit;

        $this->total = $this->query->where($this->where)->count();

        $result = $this->query->where($this->where)
            ->limit(($this->page * $this->limit),$this->limit)
            ->select();
        return [$result, $this->page, $this->limit, $this->total];
    }

    public function query($sql, $page, $limit)
    {
        $this->page = $page <= 0 ? 1 : $page;
        $this->limit = $limit;
        $this->total = count(Db::query($sql) );
        $sql = $sql. ' LIMIT '.($this->page * $this->limit).' , '.$this->limit;
        $result = Db::query($sql);
        return [$result,$this->page, $this->limit, $this->total];
    }
}