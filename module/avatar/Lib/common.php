<?php

!defined('IN_INTER') && exit('Fobbiden!');

/**
 * ================================================================================
 * ���ļ��д�������˿�ʢ���루�������Ƽ����޹�˾Discuz!/UCenter�Ĵ��롣�������Э��Ĺ涨��
 *     ����ֹ�� Discuz! / UCenter ��������κβ��ֻ������Է�չ�κ������汾���޸İ汾��������汾�������·ַ�����
 * ���ڴ��������£�
 *     �������Ϊ����ѧϰ���о�����ں������˼���ԭ�����������ӯ��ΪĿ�ģ�ͬʱҲ�����ַ��������������/��˾��Ȩ�档
 *     �����ַ�Ȩ�棬�뷢�ʼ���֪���ڱ��˽ӻ�֪ͨ��48Сʱ֮�ڽ�����Լ��������Ĵ�����г��ز�����
 *     ͬʱ���ѵ����������ߺ�ʹ����ʹ����Щ����ʱ���Ǳ�����ķ��ɷ��գ������������ߺ�ʹ���ߵ�һ����Ϊ�뱾���޹ء�
 * 
 * Discuz!/UCenterͷ�ļ�ע�ͣ�
 * (C)2001-2009 Comsenz Inc.
 * This is NOT a freeware, use is subject to license terms
 * ================================================================================
 * 
 * ���ֹ��ú����ļ����࣬��ʹ�þ�̬��������
 * ���ļ��Ĳο������³����ڴ�һ����л��
 *     - ��̳����Discuz! {@link http://www.discuz.net/}
 *     - Comsenz UCenter {@link http://www.comsenz.com}
 *     - PHP���Zend Framework{@link http://framework.zend.com/}
 *
 * @author Horse Luke<horseluke@126.com>
 * @license Mixed License. See the description above. 
 * @version $Id: common.php 128 2010-07-05 13:45:44Z horseluke@126.com $
 */
class common{
    
    //�洢����ʵ��
    protected static $_objectInstance = array();
    
    /**
     * dz����ӽ��ܺ���
     * ��Դ��Discuz! 7.0
     * �����ԣ��ɶ�����ȡʹ��
     *
     * @param string $string Ҫ����/���ܵ��ַ���
     * @param string $operation �������ͣ���ѡΪ'DECODE'��Ĭ�ϣ�����'ENCODE'
     * @param string $key ��Կ�����봫�룬�����ж�php�ű����С�
     * @param int $expiry ��Ч��
     * @return string
     */
    public static function authcode($string, $operation = 'DECODE', $key, $expiry = 0) {

        $ckey_length = 4;	// �����Կ���� ȡֵ 0-32;
        // ���������Կ���������������κι��ɣ�������ԭ�ĺ���Կ��ȫ��ͬ�����ܽ��Ҳ��ÿ�β�ͬ�������ƽ��Ѷȡ�
        // ȡֵԽ�����ı䶯����Խ�����ı仯 = 16 �� $ckey_length �η�
        // ����ֵΪ 0 ʱ���򲻲��������Կ

        //ȡ��UC_KEY����Ϊ���봫��$key��������
        if(empty($key)){
            exit('PARAM $key IS EMPTY! ENCODE/DECODE IS NOT WORK!');
        }else{
            $key = md5($key);
        }


        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }

    }

    
    /**
     * ��ȡ$_GET/$_POST/$_COOKIE/$_REQUEST�����ָ����������(ok)
     * ��Դ��Ucenter
     * �����ԣ��ɶ�����ȡʹ��
     *
     * @param string $k ָ������
     * @param string $var ��ȡ��Դ��Ĭ��Ϊ'R'����$_REQUEST������ѡֵ'G'/'P'/'C'����Ӧ$_GET/$_POST/$_COOKIE��
     * @return mixed
     */
    public static function getgpc($k, $var='R') {
        switch($var) {
            case 'G': $var = &$_GET; break;
            case 'P': $var = &$_POST; break;
            case 'C': $var = &$_COOKIE; break;
            case 'R': $var = &$_REQUEST; break;
        }
        return isset($var[$k]) ? $var[$k] : NULL;
    }
    
    /**
     * ת�崦���Ķ���daddslashes����(ok)
     * ��Դ��Ucenter
     * �����ԣ���Ҫ�޸Ĳ��ܶ���ʹ��
     * 
     * @param string $string
     * @param int $force
     * @param bool $strip
     * @return mixed
     */
    public static function addslashes($string, $force = 0, $strip = FALSE) {

        if(!ini_get('magic_quotes_gpc') || $force) {
            if(is_array($string)) {
                $temp = array();
                foreach($string as $key => $val) {
                    $key = addslashes($strip ? stripslashes($key) : $key);
                    $temp[$key] = self::addslashes($val, $force, $strip);
                }
                $string = $temp;
                unset($temp);
            } else {
                $string = addslashes($strip ? stripslashes($string) : $string);
            }
        }
        return $string;
    }
    
    /**
     * �����ļ�����չ��
     * ��Դ��Discuz!
     * �����ԣ��ɶ�����ȡʹ��
     * 
     * @param string $filename �ļ���
     * @return string
     */
    public static function fileext($filename) {
        return trim(substr(strrchr($filename, '.'), 1, 10));
    }
    
    
    /**
     * ��ȡָ���������ָ�����������ʵ����û�����½�һ�����Ҵ洢������
     *
     * @param string $classname ����
     * @param string $index ������Ĭ�ϵ�ͬ��$classname
     */
    public static function getInstanceOf( $classname , $index = null ){
        if( null === $index ){
            $index = $classname;
        }
        if( isset( self::$_objectInstance[$index] ) ){
            $instance = self::$_objectInstance[$index];
            if( !($instance instanceof $classname) ){
                throw new Exception( "Key {$index} has been tied to other thing." );
            }
        }else{
            $instance = new $classname();
            self::$_objectInstance[$index] = $instance;
        }
        return $instance;
    }

}