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

    //���Զ�Ӧ��
    private $thisClass = 'model_outsourcing_contract_rentcar';

    //��Ӧ����
    private $thisCode = 'YFQTYD03';

    //Դ����Ϣ��ȡ
    function getObjInfo_d($obj, $formCode = null) {
        $innerObjDao = new $this->thisClass();
        if($formCode){
        	$innerObj = $innerObjDao->find(array('orderCode' => $formCode));
        }else{
        	$innerObj = $innerObjDao->get_d($obj['objId']);
        }

        //���õ���Դ������
        $innerObj['sourceType'] = $this->thisCode;

        $datadictDao = new model_system_datadict_datadict();
        $innerObj['sourceTypeCN'] = $datadictDao->getDataNameByCode($this->thisCode);

        //��ȡ��ǰ��¼�˲���
        $otherDataDao = new model_common_otherdatas();
        $deptRows = $otherDataDao->getUserDatas($innerObj['createId'], array('DEPT_NAME', 'DEPT_ID'));
        $innerObj['deptName'] = $deptRows['DEPT_NAME'];
        $innerObj['deptId'] = $deptRows['DEPT_ID'];

        return $innerObj;
    }

    /**
     * ��ȡ�ʼ���չ����
     * @param $objCode
     */
    function getMailExpend_d($objCode) {
        return '';
    }
}