<?php
/**
 *
 * ҵ��ģ�鰲ȫ���ã���������ÿ��ҵ��ģ�鰲ȫ���ʼ��ܹ���
 * ������µİ�ȫ���⣺
 * ���磺contract?id=1 �����û�����ͨ������id=*ֱ�Ӳ鿴������ͬ��Ϣ
 * ��Ҫ��contract?id=1&key=****����һ����Կ����̨���������Կ���бȽϣ������ͬ����Է��ʣ����򲻿��Է���
 * ������Կ�����ɿ�����Ա���壬���Ҽ�����md5���ܣ�������ͨ�û������޷��²�ó���
 * һ�㽨��ʹ������+���룬�ٴ��н�ȡһ�������ַ�����Ϊ��Կ
 * @author chengl
 *
 */

class model_common_securityUtil extends model_base {


	//����ҵ��ģ�����
	function __construct($code=null) {
		include (WEB_TOR."model/common/securityRegister.php");
		if(isset($securityRule[$code])){
			$this->rule=$securityRule[$code];
		}else{
			return;
		}

		if(isset($rule)){
			$ruleArr=explode(",",$rule);
			$this->rule[1]=$ruleArr[0];
			$this->rule[2]=$ruleArr[1];
		}
		$this->isNeedSub=isset($this->rule[1]);
		$this->keyArr=explode(",",$this->rule[0]);
		parent::__construct ();

	}

	/**
	 * �Դ���ĵ������ݰ������õĹ�����м���
	 * @param  $row �����ҵ�����
	 * @param  $keyFields ����ҵ���������ֶ����ԣ���������´��룬���б�Ϊ��ͼ
	 * @param  $modelName ҵ��������ƣ���customer ������ʹ�������ļ�
	 * ���ؼ��ܴ�
	 */
	function md5Row($row,$keyFields=null,$modelName=null){
		if(!empty($keyFields)){
			$this->keyArr=explode(",",$keyFields);
		}
		if(!isset($this->rule)){
			return null;
		}
		$key_="";
//		foreach ( $this->keyArr as $k => $v ) {
//			$key_.=$row[$v];
//		}
		$key_=$row[$this->keyArr[0]];
		$key_=md5($key_);
		if($this->isNeedSub){
			$key_=substr($key_,$this->rule[1],$this->rule[2]);
		}
		return $key_;
	}

	/**
	 * �Դ�������ݰ������õĹ�����м���
	 * @param  $rows �����ҵ���������
	 * @param  $keyFields ����ҵ���������ֶ����ԣ���������´��룬���б�Ϊ��ͼ
	 * @param  $modelName ҵ��������ƣ���customer ������ʹ�������ļ�
	 */
	function md5Rows($rows,$keyFields=null,$modelName=null) {
		if(!empty($keyFields)){
			$this->keyArr=explode(",",$keyFields);
		}
		if(!isset($this->rule)){
			return $rows;
		}
		if(is_array($rows)){
			foreach ( $rows as $rowkey => $val ) {
				$key_="";
				$i=0;
				foreach ( $this->keyArr as $k => $v ) {
					$key_= isset($val[$v]) ? md5($val[$v]) : "";
					if($this->isNeedSub){
						$key_=substr($key_,$this->rule[1],$this->rule[2]);
					}
					if($i>0){
						$rows[$rowkey]['skey_'.$i]=$key_;
					}else{
						$rows[$rowkey]['skey_']=$key_;//Ϊ�˲���ҵ�����������ֶγ�ͻ�����˸��»��ߣ������ҵ����������Ҳ�ܳ�ͻ��������������...
					}
					$i++;
				}
			}
		}
		return $rows;
	}

	/**
	 * �������ݹ��������޷���Ȩ��
	 * @param  $row �����ҵ�����
	 * @param  $key �������Կ
	 * @param  $keyField �����ֶ� �������������ļ���ĵ�һ���ֶ�
	 */
	function permCheck($row,$key_=null,$keyField=null) {
		if(!isset($this->rule)){
			return true;
		}
		if(empty($keyField)){
			$keyField=$this->keyArr[0];
		}
		$key=md5($row[$keyField]);
		if($this->isNeedSub){
			$key=substr($key,$this->rule[1],$this->rule[2]);
		}
//        echo $key_;
//        echo '==';
//        echo $key;
		return ($key_==$key);
	}

}
?>