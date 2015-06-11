<?php
class checker{
// ��������
    var $array_data="";     //Ҫ��֤����������
    var $var_key="";     //��ǰҪ��֤�����ݵ�key
    var $var_value="";     //��ǰҪ��֤�����ݵ�ֵ
    var $is_empty="";     //Ҫ��֤��ֵ����Ϊ��
    var $array_info="";     //��ʾ��Ϣ�ռ�
    var $array_errors=array();   //������Ϣ�ռ�

//--------------------->���캯��<------------
    function checker($date){
        $this->array_data=$date;
    }
//--------------------->���ݼ��麯��<-------------
    function check($array_datas){
        foreach($array_datas as $value_key => $value_v){
            $temp1=explode('|',$value_v);
            if($temp1[0]=="i_empty" and empty($this->array_data[$value_key])){
                ;
            }else{
                foreach($temp1 as $temp_key => $value_con){
                    //$data_temp=$this->array_data;
                    //var_dump($data_temp['birthday']);
                    //echo "--".$value_key."--<br>";
                    $this->var_key=$value_key;
                    $this->var_value=$this->array_data[$value_key];
                    $temp2=explode(':',$value_con);
                    switch(count($temp2)){
                        case 0:
                            $this->array_errors[$this->var_key]="��ֵ����֤���󲻴���";
                            break;
                        case 1:
                            //����û�û��ָ����֤����
                            if(empty($temp2[0])){
                                $this->array_errors[$this->var_key]="��ֵ����֤���󲻴���";
                                break;
                            }else{
                                $this->$temp2[0]();   //�������ֵΪ�ǣ��Ͳ��ý�����һ����֤
                                break;
                            }
                        case 2:
                            $this->$temp2[0]($temp2[1]);
                            break;
                        case 3:
                            $this->$temp2[0]($temp2[1],$temp2[2]);
                            break;
                    }
                }
            }
        }
    }
    function i_empty(){
        $this->is_empty=1;  //���ֵûʲô�ã�ֻ��˵��Ҫ��֤��ֵ�����ǿ�ֵ
    }

//-------------------->�Ƿ�Ϊ��--------------------
	function i_isempty($value){
        if( empty( $value ) ) {
			return true;
		} else {
			return false;
		}
    }

//-------------------->������֤--------------------
    function i_isint($value){
        if( $value === intval($value) ) {
			return false;
		} else {
			return true;
		}    
    }

//-------------------->ͳ������--------------------
	function i_countwords($value, $min = 0, $max){
        $len = strlen($value);
        if(empty($len)){
            return "����Ϊ��ֵ";
        }
        if ($len < $min) {
            return "����Ĵ�̫����";
        }
        if ($max != -1) {
            if ($len > $max) {
                return "����Ĵ�̫����";
            }
        }
        return false;
    }

//�������ݡ��ʼ���ַ���������ݡ�������IP��ַ���ַ��������ֵ����Сֵ���ַ������ȡ�������URL
//-------------------->������֤--------------------
    function i_date(){
        //Լ����ʽ��2000-02-01���ߣ�2000-5-4
        if (!eregi("^[1-9][0-9][0-9][0-9]-[0-9]+-[0-9]+$", $this->var_value)) {
            $this->array_errors[$this->var_key]="���ڸ�ʽ����";
            return false;
        }
        $time = strtotime($this->var_value);
        if ($time === -1) {
            $this->array_errors[$this->var_key]="���ڸ�ʽ����";
            return false;
        }
        $time_e = explode('-', $this->var_value);
        $time_ex = explode('-', Date('Y-m-d', $time));
        for ($i = 0; $i < count($time_e); $i++) {
            if ((int)$time_e[$i] != (int)$time_ex[$i]) {
                $this->array_errors[$this->var_key]="���ڸ�ʽ����";
                return false;
            }
        }
        return true;
    }
//-------------------->ʱ����֤--------------------
    function i_time() {
        if (!eregi('^[0-2][0-3](:[0-5][0-9]){2}$', $this->var_value)) {
            $this->array_errors[$this->var_key]="ʱ���ʽ����";
            return false;
        }
        return true;
    }
//-------------------->email��֤--------------------
    function i_email(){
        if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]
+(\.[a-z0-9-]+)*$", $this->var_value))
            $this->array_errors[$this->var_key]="�ʼ���ʽ����<br>";
        //echo $this->var_value;
        return true;
    }
//-------------------->��������֤--------------------
    function i_float(){
//if(!is_float($this->var_value))
        if(!ereg("^[1-9][0-9]?\.([0-9])+$",$this->var_value))
            $this->array_errors[$this->var_key]="�ⲻ��һ��С��";
    }
//-------------------->�ַ�����֤--------------------
    function i_string(){
        if(empty($this->var_value))    //����Ϊ��
            return true;
        if(!is_string($this->var_value))
            $this->array_errors[$this->var_key]="�ⲻ��һ���ַ���";
        return true;
    }
//-------------------->�ַ���������֤--------------------
    function len($minv,$maxv=-1){
        $len = strlen($this->var_value);
        if($len==0){
            $this->array_errors[$this->var_key]="����Ϊ��ֵ";
            return false;
        }
        if ($len < $minv) {
            $this->array_errors[$this->var_key]="����Ĵ�̫����";
            return false;
        }
        if ($maxv != -1) {
            if ($len > $maxv) {
                $this->array_errors[$this->var_key]="����Ĵ�̫����";
                return false;
            }
        }
        return true;
    }
//-------------------->������֤--------------------
    function i_int(){
        if(!ereg("^[0-9]*$",$this->var_value))
            $this->array_errors[$this->var_key]="�ⲻ��һ������";
    }
//-------------------->IP��ַ��֤--------------------
    function i_ip(){
        if(!ereg("^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$", $this->var_value)){
            $this->array_errors[$this->var_key]="�����IP��ַ";
        }else{
            //ÿ��������255
            $array_temp=preg_split("/\./",$this->var_value);
            foreach($array_temp as $ip_value){
                if((int)$ip_value >255)
                    $this->array_errors[$this->var_key]="�����IP��ַ";
            }
        }
        return true;
    }
//-------------------->���ֵ��֤--------------------
    function i_max($maxv){
        if($this->var_value >= $maxv){
            $this->array_errors[$this->var_key]="����ֵ̫��";
            return false;
        }
        return true;
    }
//-------------------->��Сֵ��֤--------------------
    function i_min($minv){
        if($this->var_value <= $minv){
            $this->array_errors[$this->var_key]="����ֵ̫С";
            return false;
        }
        return true;
    }
//-------------------->������֤--------------------
    function i_domain() {
        if(!eregi("^@([0-9a-z\-_]+\.)+[0-9a-z\-_]+$", $this->var_value))
            $this->array_errors[$this->var_key]="���������";
        return eregi("^@([0-9a-z\-_]+\.)+[0-9a-z\-_]+$", $this->var_value);
    }
//-------------------->URL��֤--------------------
    function i_url(){
        if(!eregi('^(http://|https://){1}[a-z0-9]+(\.[a-z0-9]+)+$' , $this->var_value))
            $this->array_errors[$this->var_key]="�����WEB��ַ";
        return true;
    }
//-------------------->�Զ�������У��--------------------
    function check_own($user_pattern){
//�Զ���У�顣������false��ƥ�䷵��1����ƥ�䷵��0
        $tempvar=preg_match($user_pattern,$this->var_value);
        if($tempvar!=1)
            $this->array_errors[$this->var_key]="���ݲ��Ϸ�";
    }
}
?>