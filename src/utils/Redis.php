<?php


namespace Cium\WeWorkApi\utils;

class Redis
{
    private static $instance;

    private $redis;

    private $config = [
        'host'     => '127.0.0.1',
        'port'     => 6379,
        'password' => '',
        'timeout'  => 0,
    ];

    static public function getInstance($config = [])
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public function __construct($config = [])
    {
        if (!empty($config)) {
            $this->config = $config;
        }
        $this->redis = new \Redis();
        $this->redis->connect($this->config['host'], $this->config['port'], $this->config['timeout']);
        $this->redis->auth($this->config['password']);
    }

    /**
     * Redis server
     *
     * @return \Redis
     */
    public function server()
    {
        return $this->redis;
    }

    /**
     * 在名称为key的list左边（头）添加值为value的 元素
     *
     * @param       $key
     * @param mixed ...$value
     *
     * @return bool|int
     */
    public function lPush($key, ...$value)
    {
        return $this->redis->lPush($key, $value);
    }

    /**
     * 在名称为key的list右边（尾）添加值为value的 元素
     *
     * @param       $key
     * @param mixed ...$value
     *
     * @return bool|int
     */
    public function rPush($key, ...$value)
    {
        return $this->redis->rPush($key, $value);
    }


    /**
     * 输出名称为key的list左(头)起的第一个元素，删除该元素
     *
     * @param $key
     *
     * @return bool|mixed
     */
    public function lPop($key)
    {
        return $this->redis->lPop($key);
    }

    /**
     * 输出名称为key的list右（尾）起起的第一个元素，删除该元素
     *
     * @param $key
     *
     * @return bool|mixed
     */
    public function rPop($key)
    {
        return $this->redis->rPop($key);
    }

    /**
     * 返回名称为key的list中start至end之间的元素（end为 -1 ，返回所有）
     *
     * @param $key
     * @param $start
     * @param $end
     *
     * @return array
     */
    public function lrange($key, $start, $end)
    {
        return $this->redis->lrange($key, $start, $end);
    }

    /**
     * @param     $key
     * @param     $value
     * @param int $timeout
     *
     * @return bool
     */
    public function set($key, $value, $timeout = 0)
    {
        return $this->redis->set($key, $value, $timeout);
    }

    /**
     * @param $key
     *
     * @return bool|mixed|string
     */
    public function get($key)
    {
        return $this->redis->get($key);
    }

    /**
     * @param       $key
     * @param mixed ...$otherKeys
     *
     * @return int
     */
    public function del($key, ...$otherKeys)
    {
        return $this->redis->del($key, $otherKeys);
    }

    /**
     * @param $key
     *
     * @return bool|int
     */
    public function exists($key)
    {
        return $this->redis->exists($key);
    }
}