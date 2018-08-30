<?php
/**
 * Created on 2011-5-5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include(WEB_TOR . 'model/finance/invother/iinvother.php');

class model_finance_invother_strategy_rentcar extends model_base implements iinvother
{

    //策略对应类
    private $thisClass = 'model_outsourcing_contract_rentcar';

    //对应编码
    private $thisCode = 'YFQTYD03';

    //源单信息获取
    function getObjInfo_d($obj, $formCode = null) {
        $innerObjDao = new $this->thisClass();
        if($formCode){
        	$innerObj = $innerObjDao->find(array('orderCode' => $formCode));
        }else{
        	$innerObj = $innerObjDao->get_d($obj['objId']);
        }

        //设置单据源单类型
        $innerObj['sourceType'] = $this->thisCode;

        $datadictDao = new model_system_datadict_datadict();
        $innerObj['sourceTypeCN'] = $datadictDao->getDataNameByCode($this->thisCode);

        //获取当前登录人部门
        $otherDataDao = new model_common_otherdatas();
        $deptRows = $otherDataDao->getUserDatas($innerObj['createId'], array('DEPT_NAME', 'DEPT_ID'));
        $innerObj['deptName'] = $deptRows['DEPT_NAME'];
        $innerObj['deptId'] = $deptRows['DEPT_ID'];

        return $innerObj;
    }

    /**
     * 获取邮件扩展内容
     * @param $objCode
     */
    function getMailExpend_d($objCode) {
        return '';
    }
}