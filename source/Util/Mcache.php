<?php
/**
 * ��ʵ���ķ��� :$cache=Mcache:getInstance();
 *
 */
class Mcache{
    private static $_instance;
    private $_memcache;

    /**
     *˽�л����캯�� ����ֹʹ�ùؼ���new��ʵ����Mcache��
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
     *��¡˽�л�,��ֹ��¡ʵ��
     */
    function __clone(){}
    /**
     *�����ڣ�ͨ������ľ�̬�����������ʵ����
     */
    public static function getInstance(){
        if (!self::$_instance instanceof self){
            self::$_instance=new self();
        }
        return self::$_instance;
    }

    /**
     *��������ӵ�����
     *param string $key �����key
     *param string |arrary|int ....$value ���������
     *param int $expire_time ����ʱ��
     */
    public function set($key,$value,$expire_time=0){
        if($expire_time>0){
            $this->_memcache->set($key,$value,0,$expire_time);
        }else{
            $this->_memcache->set($key,$value);
        }
    }

    /**
     * �ӻ����ȡ����
     * @package string|array|int ....$key
     */
    public function get($key){
        return $this->_memcache->get($key);
    }
    /**
     * �ӻ�����ɾ������
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