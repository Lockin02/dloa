<?php
/*
 * Created on 2012-5-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include( WEB_TOR . 'model/finance/compensate/icompensate.php');

/**
 * ������Ŀ����
 */
class model_finance_compensate_strategy_swithdraw implements icompensate{
	//��Ӧҵ����
	private $thisClass = 'model_stock_withdraw_withdraw';
	//��ϸ
	private $detailClass = 'model_stock_withdraw_equ';

	/**
	 * ҵ�����ݻ�ȡ
	 */
	function businessGet_i($relId,$mainDao){
		//��Ӧҵ��
		$thisDao = new $this->thisClass();
		$thisObj = $thisDao->get_d($relId);

		$thisObj['objCode'] = $thisObj['docCode'];
		$thisObj['objId'] = $thisObj['docId'];
		$thisObj['objType'] = 'PCYWLX-02';
		$thisObj['chargerName'] = $thisObj['createName'];
		$thisObj['chargerId'] = $thisObj['createId'];
		$thisObj['applyTypeName'] = '';
		$thisObj['applyType'] = '';
		$thisObj['relDocCode'] = $thisObj['planCode'];

        //��ȡ������Ϣ
        $otherdataDao = new model_common_otherdatas();
        $userInfo = $otherdataDao->getUserDatas($thisObj['createId']);
        $thisObj['deptName'] = $userInfo['DEPT_NAME'];
        $thisObj['deptId'] = $userInfo['DEPT_ID'];

        //�̻���Ϣ
		$thisObj['chanceId'] = '';
		$thisObj['chanceCode'] = '';
		$thisObj['chanceName'] = '';
		$thisObj['province'] = '';
		$thisObj['salemanId'] = '';
		$thisObj['salemanName'] = '';
		$thisObj['qualityObjType'] = "ZJSQYDHH";

		return $thisObj;
	}

    /**
     * ��ȡ�⳥����ϸ
     */
    function businessGetDetail_i($condition){
        $detailDao = new $this->detailClass();
        $applyType = $condition['applyType'];//��������
        unset($condition['applyType']);
        $condition['mainId'] = $condition['relDocId'];//Դ��ID
        //���������黹
        $condition['numSql'] = 'sql:and (c.compensateNum < c.qBackNum)';
        $detailDao->getParam($condition);
        $rows = $detailDao->list_d();

        //���ݴ���
        if($rows){
            foreach($rows as &$val){
                $val['productNo'] = $val['productCode'];
                $val['returnequId'] = $val['id'];

				if(!isset($compesateDetailDao)){
					$compesateDetailDao = new model_finance_compensate_compensatedetail();
				}
				//��ѯ�´���������
				$compensateNum = $compesateDetailDao->getCompensateNum_d($val['returnequId']);

				$val['allowNum'] = $val['qBackNum'] - $compensateNum;
				$val['number'] = $val['allowNum'];
            }
        }

        return $rows;
    }

    /**
     * ��ȡ�⳥����ϸ
     */
    function getSerialNos_i($condition,$mainDao){
        return array();
    }

	/**
	 * ����ҵ����
	 */
	function businessAdd_i($obj,$detail,$mainDao){
		//����Դ��״̬Ϊ ִ����
		$detailDao = new $this->detailClass();
		//��Ӧҵ��
		$thisDao = new $this->thisClass();

		try{
			$detailDao->start_d();

			//����Դ���������
			foreach($detail as $val){
				$detailDao->editCompensateNumber($val['returnequId'],$val['number']);
			}

			//��������ҵ��
			$thisDao->updateStateAuto_d($obj['relDocId']);

			$detailDao->commit_d();
			return true;
		}catch(Exception $e){
			$detailDao->rollBack();
			throw $e;
			return false;
		}
	}
}