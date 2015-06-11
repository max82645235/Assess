<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-11
 * Time: ����10:43
 */

class NewTpl{
    private $_tpl;
    private $_data = array();

    /**
     * ��ʼ��ģ��
     *
     * @param sting $tpl
     * @param array $data
     */
    public function __construct( $tpl='', $data=array() ){
        //ģ��Ŀ¼
        $tpl_dir = P_TEMPLATE;
        $this->_tpl = BATH_PATH."$tpl_dir/$tpl";
        $this->_data = $data;
    }

    /**
     * ����ģ�����
     *
     * @access public
     * @param string $key
     * @param mixed $value
     */
    public function __set( $key, $value ){
        $this->_data[$key] = $value;
    }

    /**
     * ���ģ��
     * @access public
     *
     */
    public function __toString(){
        return $this->getRender();
    }

    /**
     * ���ģ�������
     * @access public
     */
    public function render(){
        extract($this->_data);
        try {
            require_once($this->_tpl);
        }catch (Exception $ex){
           echo "ģ�岻����";
            die();
        }
    }

    /**
     * ���ģ�����������
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
     * ��ȡtpl����
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