<?php

!defined('IN_INTER') && exit('Fobbiden!');
/**
 * ������
 * ���ļ��ο������³����ڴ�һ����л��
 *     - PHP���LotusPHP{@link http://code.google.com/p/lotusphp/}
 *
 * @author Horse Luke<horseluke@126.com>
 * @copyright Horse Luke, 2009
 * @license the Apache License, Version 2.0 (the "License"). {@link http://www.apache.org/licenses/LICENSE-2.0}
 * @version $Id: config.php 128 2010-07-05 13:45:44Z horseluke@126.com $
 * @package Inter_PHP_Framework
 */

class config extends ArrayObject{

    /**
     * ��������
     *
     */
    public function __construct(){
    }

    /**
     * ��dz6.1fͬ������ֵ
     */
    /*
    public function syncFromDZ(){
        
    }
    */
    
    /**
     * �Բ�����������(ok)
     *
     * @param array $newConfig �µĲ�������
     */
    public function set( $newConfig = array() ){
        foreach ($newConfig as $key => $value){
            $this->$key = $value;
        }
    }
    
    public function __get($name){
        $this->$name = null;
        return null;
    }
}

