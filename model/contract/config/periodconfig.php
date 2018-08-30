<?php
/**
 * @author Show
 * @Date 2013��7��15�� 15:15:40
 * @version 1.0
 * @description:�ؿ���ڼ� Model��
 */
class model_contract_config_periodconfig extends model_base {
	function __construct() {
		$this->tbl_name = "oa_contract_periodconfig";
		$this->sql_map = "contract/config/periodconfigSql.php";
		parent :: __construct();
	}

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
        'periodType'
    );

    /**
     * ��Ӷ���
     */
    function add_d($object) {
        $object = $this->processDatadict($object);
        return parent::add_d($object);
    }

    /**
     * ��Ӷ���
     */
    function edit_d($object) {
        $object = $this->processDatadict($object);
        return parent::edit_d($object);
    }

    /**
     * �����������ڣ��жϸ������������Ǹ��׶���
     * @param T��
     * @param ��������
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