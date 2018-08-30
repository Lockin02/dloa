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
 * ����controller���Ķ���UCenter base��
 * ���ļ��Ĳο������³����ڴ�һ����л��
 *     - Comsenz UCenter {@link http://www.comsenz.com}
 *
 * @author Horse Luke<horseluke@126.com>
 * @license Mixed License. See the description above. 
 * @version $Id: Base.php 128 2010-07-05 13:45:44Z horseluke@126.com $
 */

class Controller_Base{
    
    public $input = array();
    
    public $config;
    
    /**
     * ���캯������ʼ������(ok)
     *
     */
    public function __construct(){
        $this->config = common::getInstanceOf('config');
    }

    /**
     * ��ʼ�����루ok��
     *
     * @param string $getagent ָ����agent
     */
    public function init_input($getagent = '') {
        $input = common::getgpc('input', 'R');
        if($input) {
            $input = common::authcode($input, 'DECODE', $this->config->authkey);
            parse_str($input, $this->input);
            $this->input = common::addslashes($this->input, 1, TRUE);
            $agent = $getagent ? $getagent : $this->input['agent'];

            if(($getagent && $getagent != $this->input['agent']) || (!$getagent && md5($_SERVER['HTTP_USER_AGENT']) != $agent)) {
                exit('Access denied for agent changed');
            } elseif(time() - $this->input('time') > 3600) {
                exit('Authorization has expired');
            }
        }
        if(empty($this->input)) {
            exit('Invalid input');
        }
    }
    
    /**
     * ����$this->input�Ƿ����ָ�������ı�������ok��
     *
     * @param string $k Ҫ���ҵ�����
     * @return mixed
     */
	public function input($k) {
		return isset($this->input[$k]) ? (is_array($this->input[$k]) ? $this->input[$k] : trim($this->input[$k])) : NULL;
	}
    
}