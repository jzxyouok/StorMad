<?php
/*********************************************************************************
 * InitPHP 2.0 国产PHP开发框架  Dao-Nosql-Redis
 *-------------------------------------------------------------------------------
 * 版权所有: CopyRight By initphp.com
 * 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
 *-------------------------------------------------------------------------------
 * $Author:zhuli
 * $Dtime:2011-10-09
***********************************************************************************/
class Redis_init {

    public $redis; //redis对象

    /**
     * 初始化Redis
     * $config = array(
     *  'server' => '127.0.0.1' 服务器
     *  'port'   => '6379' 端口号
     * )
     * @param array $config
     */
    public function __construct($config=Array()) {
        $host = $config['host'];            // <-- redis host
        $password = $config['password'];    // <-- redis password
        $port = $config['port'];            // <-- redis port

        $this->redis = new Redis();
        $this->redis->connect($host, $port);
        $this->redis->auth($password);
        #return $this->redis;
    }

    /**
     * 设置值
     * @param string $key KEY名称
     * @param string|array $value 获取得到的数据
     * @param int $timeOut 时间 s
     */
    public function set($key, $value, $timeOut = 0) {
        $retRes = $this->redis->set($key, $value);
        if ($timeOut > 0) $this->redis->setTimeout($key, $timeOut);
        return $retRes;
    }

    /**
     * 通过KEY获取数据
     * @param string $key KEY名称
     */
    public function get($key) {
        return $this->redis->get($key);
    }

    /**
     * 通过正则KEY获取数据
     * @param string $key KEY名称
     */
    public function keys($key) {
        return $this->redis->keys($key);
    }

    /**
     * 删除一条数据
     * @param string $key KEY名称
     */
    public function delete($key) {
        return $this->redis->delete($key);
    }

    /**
     * 清空数据
     */
    public function flushAll() {
        return $this->redis->flushAll();
    }

    /**
     * 数据入队列
     * @param string $key KEY名称
     * @param string|array $value 获取得到的数据
     * @param bool $right 是否从右边开始入
     */
    public function push($key, $value ,$right = false) {
        return $right ? $this->redis->rPush($key, $value) : $this->redis->lPush($key, $value);
    }

    /**
     * 数据出队列
     * @param string $key KEY名称
     * @param bool $left 是否从左边开始出数据
     */
    public function pop($key , $left = true) {
        $val = $left ? $this->redis->lPop($key) : $this->redis->rPop($key);
        return $val;
    }

    /**
     * 获取List数据个数
     * @param string $key KEY名称
     */
    public function len($key) {
        $val = $this->redis->lLen($key);
        return $val;
    }

    /**
     * 获取部分List数据
     * @param string $key KEY名称
     * @param int $offset 起始下表
     * @param int $count 元素个数
     */
    public function range($key,$offset,$count) {
        $val = $this->redis->lRange($key,$offset,$count);
        return $val;
    }

    /**
     * 数据自增
     * @param string $key KEY名称
     */
    public function increment($key) {
        return $this->redis->incr($key);
    }

    /**
     * 数据自减
     * @param string $key KEY名称
     */
    public function decrement($key) {
        return $this->redis->decr($key);
    }

    /**
     * key是否存在，存在返回ture
     * @param string $key KEY名称
     */
    public function exists($key) {
        return $this->redis->exists($key);
    }

    /**
     * 根据参数 count 的值，移除列表中与参数 value 相等的元素
     * @param string $key KEY名称
     * @param int $count 移除的个数
     * @param string $value 需要移除的值
     */
    public function rem( $key, $count, $value ) {
        return $this->redis->lRem( $key, $value, $count );
    }

    /**
     * 返回redis对象
     * redis有非常多的操作方法，我们只封装了一部分
     * 拿着这个对象就可以直接调用redis自身方法
     */
    public function redis() {
        return $this->redis;
    }
}

