<?php
/**
 * @author Show
 * @Date 2013年7月15日 15:15:40
 * @version 1.0
 * @description:回款奖惩期间 Model层
 */
class model_contract_config_periodconfig extends model_base {
	function __construct() {
		$this->tbl_name = "oa_contract_periodconfig";
		$this->sql_map = "contract/config/periodconfigSql.php";
		parent :: __construct();
	}

    //数据字典字段处理
    public $datadictFieldArr = array(
        'periodType'
    );

    /**
     * 添加对象
     */
    function add_d($object) {
        $object = $this->processDatadict($object);
        return parent::add_d($object);
    }

    /**
     * 添加对象
     */
    function edit_d($object) {
        $object = $this->processDatadict($object);
        return parent::edit_d($object);
    }

    /**
     * 传入两个日期，判断付款条件处于那个阶段中
     * @param T日
     * @param 核销日期
     */
    function getPeriod_d($tDay,$checkDate){
		$sql = "select id as periodId,periodName from oa_contract_periodconfig
			where
				if(beginDays = 0,1,(UNIX_TIMESTAMP('$checkDate') - UNIX_TIMESTAMP('$tDay'))/86400 > beginDays) and
				if(endDays = -1,1,(UNIX_TIMESTAMP('$checkDate') - UNIX_TIMESTAMP('$tDay'))/86400 < endDays)";
		$rs = $this->_db->getArray($sql);
		if($rs){
			return $rs[0];
		}else{
			return array(
				'periodId' => '',
				'periodName' => ''
			);
		}
    }
}
?>