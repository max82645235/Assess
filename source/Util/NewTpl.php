<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-11
 * Time: 上午10:43
 */

class NewTpl{
    private $_tpl;
    private $_data = array();

    /**
     * 初始化模板
     *
     * @param sting $tpl
     * @param array $data
     */
    public function __construct( $tpl='', $data=array() ){
        //模板目录
        $tpl_dir = P_TEMPLATE;
        $this->_tpl = BATH_PATH."$tpl_dir/$tpl";
        $this->_data = $data;
    }

    /**
     * 设置模板变量
     *
     * @access public
     * @param string $key
     * @param mixed $value
     */
    public function __set( $key, $value ){
        $this->_data[$key] = $value;
    }

    /**
     * 输出模板
     * @access public
     *
     */
    public function __toString(){
        return $this->getRender();
    }

    /**
     * 输出模板的内容
     * @access public
     */
    public function render(){
        extract($this->_data);
        try {
            require_once($this->_tpl);
        }catch (Exception $ex){
           echo "模板不存在";
            die();
        }
    }

    /**
     * 获得模板输出的内容
     *
     * @return string
     */
    public function getRender(){
        ob_start();
        extract($this->_data);
        require_once($this->_tpl);
        return ob_get_clean();
    }

    /**
     * 获取tpl代码
     *
     * @return string
     */
    public function get_tpl(){
        return $this->_tpl;
    }

    public function set_tpl($file){
        $this->_tpl = $file;
    }

    public function set_data($data){
        $this->_data = $data;
    }

    public function get_widget_instance(){}
}