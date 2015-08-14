<?php
/**
 * 的实例的方法 :$cache=Mcache:getInstance();
 *
 */
class Mcache{
    private static $_instance;
    private $_memcache;

    /**
     *私有化构造函数 ，禁止使用关键字new来实例化Mcache类
     */
    private function __construct(){
        if(!class_exists('Memcached')){
            throw new Exception('Class Memcached not exists ');
        }
        $this->_memcache = new Memcached();
        $host = '127.0.0.1';
        $post = '1122';
        $this->_memcache->addServer($host,$post);
    }
    /**
     *克隆私有化,禁止克隆实例
     */
    function __clone(){}
    /**
     *类的入口，通过此类的静态方法对类进行实例化
     */
    public static function getInstance(){
        if (!self::$_instance instanceof self){
            self::$_instance=new self();
        }
        return self::$_instance;
    }

    /**
     *把数据添加到缓存
     *param string $key 缓存的key
     *param string |arrary|int ....$value 缓存的数据
     *param int $expire_time 缓存时间
     */
    public function set($key,$value,$expire_time=0){
        if($expire_time>0){
            $this->_memcache->set($key,$value,0,$expire_time);
        }else{
            $this->_memcache->set($key,$value);
        }
    }

    /**
     * 从缓存读取数据
     * @package string|array|int ....$key
     */
    public function get($key){
        return $this->_memcache->get($key);
    }
    /**
     * 从缓存中删除数据
     * @param sting|array|int...$key
     */
    public function del($key){

        return $this->_memcache->delete($key);
    }

    public function close(){
        $this->_memcache->quit();
    }

    function __destruct()
    {
        $this->close();
    }
}
?>