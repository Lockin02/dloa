<?php
/**
 * Created on 2011-5-5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include(WEB_TOR . 'model/finance/invother/iinvother.php');

class model_finance_invother_strategy_outsourcing extends model_base implements iinvother
{

    //���Զ�Ӧ��
    private $thisClass = 'model_contract_outsourcing_outsourcing';

    //��Ӧ����
    private $thisCode = 'YFQTYD01';

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