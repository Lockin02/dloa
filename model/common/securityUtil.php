<?php
/**
 *
 * 业务模块安全配置：用于配置每个业务模块安全访问加密规则
 * 解决如下的安全问题：
 * 比如：contract?id=1 这样用户可以通过更换id=*直接查看其他合同信息
 * 需要在contract?id=1&key=****加上一个密钥，后台根据这个密钥进行比较，如果相同则可以访问，否则不可以访问
 * 由于密钥规则由开发人员定义，并且加入了md5加密，所以普通用户根本无法猜测得出。
 * 一般建议使用名称+编码，再从中截取一定长度字符串作为密钥
 * @author chengl
 *
 */

class model_common_securityUtil extends model_base {


	//传入业务模块编码
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
	 * 对传入的单条数据按照设置的规则进行加密
	 * @param  $row 传入的业务对象
	 * @param  $keyFields 传入业务对象加密字段属性，特殊情况下传入，如列表为视图
	 * @param  $modelName 业务对象名称，如customer 不传则使用配置文件
	 * 返回加密串
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
	 * 对传入的数据按照设置的规则进行加密
	 * @param  $rows 传入的业务对象数组
	 * @param  $keyFields 传入业务对象加密字段属性，特殊情况下传入，如列表为视图
	 * @param  $modelName 业务对象名称，如customer 不传则使用配置文件
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
						$rows[$rowkey]['skey_']=$key_;//为了不与业务对象的其他字段冲突，加了个下划线，如果你业务属性这样也能冲突，那是你命苦了...
					}
					$i++;
				}
			}
		}
		return $rows;
	}

	/**
	 * 按照数据规则检查有无访问权限
	 * @param  $row 传入的业务对象
	 * @param  $key 传入的密钥
	 * @param  $keyField 加密字段 不传则用配置文件里的第一个字段
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