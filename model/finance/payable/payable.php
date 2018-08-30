<?php

/**
 * Ӧ���˿�model����
 */
class model_finance_payable_payable extends model_base {

	function __construct() {
//		$this->tbl_name = "oa_finance_payable";
		$this->sql_map = "finance/payable/payableSql.php";
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊ���ýӿڷ���,����Ϊ����ģ��������------------------------------
	 *************************************************************************************************/

	/**
	 * ��ȡ��ϸ - ����ͳ��
	 */
	function getPayDetail_d($object){
		//���searchArr
//		unset($this->searchArr);

		$rs = array(); //��������
		$countRow = array( 'id' => 'balance', 'departments' => '�ڳ����:' ); //ͳ��������
		$rsKey = null; //������
		$markKey = null;  //�������
		$periodNeedPay = 0; //���ںϼ�
		$periodPayed = 0;  //����ʵ��
		$periodBalance = 0;  //�������
		$periodWord = '���ںϼ�:';
		$yearNeedPay = 0;  //����ϼ�
		$yearPayed = 0;  //����ʵ��
		$yearWord = '�����ۼ�:';
		$nowAmount = 0;
		//����
		$balanceDao = new model_finance_balance_balance();

		$balanceRow = $balanceDao->rtBalance_d($object['year'],$object['beginMonth'],1,$object['supplierId']);
		if($balanceRow){
			$countRow['amount'] = $balanceRow['needPay'];
			$countRow['needPay'] = $balanceRow['payed'];
			$countRow['balance'] = $balanceRow['balance'];

			$periodNeedPay = $balanceRow['needPay'];
			$periodPayed = $balanceRow['payed'];
			$periodBalance = $balanceRow['balance'];

			$isInPeriod = $balanceDao->isBigThanPeriod_d($object['year'],$object['beginMonth'],1);
			if($isInPeriod){
				$thisRs = $this->getFormCountByMonth_d($object,$isInPeriod);
//				print_r($thisRs);
				$countRow['amount'] += $thisRs['amount'];
				$countRow['needPay'] += $thisRs['needPay'];
				$countRow['balance'] += $thisRs['balance'];

				$periodNeedPay += $thisRs['amount'];
				$periodPayed += $thisRs['needPay'];
				$periodBalance += $thisRs['balance'];
			}
		}
		$rs[] = $countRow;
		unset($countRow['balance']);

		//��ϸ����
		$this->getParam($object);
		$row = $this->listBySqlId('detail_union');
		foreach($row as $key => $val){
			$rsKey = date( 'm' ,strtotime($val['formDate'] ));
			if($rsKey == $markKey){//�·���ͬ,����Ҫ���⴦��

			}else if(!empty($markKey) && $rsKey != $markKey){//�·ݲ�ͬ,��Ҫ��ͳ�ƴ���

				//ͳ�Ʊ���,�����ڽ�����
				$countRow['id'] = 'period'.$rsKey;
				$countRow['departments'] = $periodWord;
				$countRow['amount'] = $periodNeedPay;
				$countRow['needPay'] = $periodPayed;
				$rs[] = $countRow;

				//ͳ�Ʊ���,�����������
				$yearNeedPay += $periodNeedPay;
				$yearPayed += $periodPayed;
				$countRow['id'] = 'year'.$rsKey;
				$countRow['departments'] = $yearWord;
				$countRow['amount'] = $yearNeedPay;
				$countRow['needPay'] = $yearPayed;
				$rs[] = $countRow;

				$markKey = $rsKey;
				$periodNeedPay = 0;
				$periodPayed = 0;
			}else if(empty($markKey)){//�޳�ʼ��¼ֵ,���г�ʼ��ֵ
				$markKey = $rsKey ;
			}
			$periodNeedPay += $val['amount'] ;
			$periodPayed += $val['needPay'] ;
			if(empty($val['amount'])){//�жϣ������Ӧ��������Ӧ�����������Ӧ�����
				$periodBalance -= $val['needPay'] ;
			}else{
				$periodBalance += $val['amount'] ;
			}
			$val['balance'] = $periodBalance ;
			$rs[] = $val;
		}

		//�����������һ�ڵĽ��
		$countRow['id'] = 'period'.$rsKey;
		$countRow['departments'] = $periodWord;
		$countRow['amount'] = $periodNeedPay;
		$countRow['needPay'] = $periodPayed;
		$rs[] = $countRow;

		//�����������һ��Ľ��
		$yearNeedPay += $periodNeedPay;
		$yearPayed += $periodPayed;
		$countRow['id'] = 'year'.$rsKey;
		$countRow['departments'] = $yearWord;
		$countRow['amount'] = $yearNeedPay;
		$countRow['needPay'] = $yearPayed;
		$rs[] = $countRow;
		return $rs;
	}

	/**
	 * ��ȡ�ڳ��������
	 */
	function getFormCountByMonth_d($object,$isInPeriod){
		$this->searchArr['formDate'] = $isInPeriod['thisDate'];
		$this->searchArr['formDateMonth'] = $object['beginMonth'];
		if(!empty($object['supplierId'])){
			$this->searchArr['supplierIds'] = $object['supplierId'];
		}
		if(!empty($object['deptId'])){
			$this->searchArr['deptIds'] = $object['deptId'];
		}
		if(!empty($object['salesmanId'])){
			$this->searchArr['salesmanId'] = $object['salesmanId'];
			$this->groupBy = 'salesmanId';
		}
		$rs = $this->listBySqlId('sum_union');
		return $rs[0];
	}


	/**
	 * Ӧ����������ݻ�ȡ����
	 */
	function getCount_d($object){
		$addRow = array();      //���ݸ�������
		$rtRows = array();      //��������
		$countRows = array();   //ͳ������

		$periodBeginBalance = 0; //�ڳ����
		$periodEndBalance = 0;   //��ĩ���
		$periodNeedPay = 0;      //����Ӧ��
		$periodPayed = 0;        //����ʵ��

		$yearNeedPay = 0;        //�����ۼ�Ӧ��
		$yearPayed = 0;          //�����ۼ�ʵ��

		$thisSpuulierId = 0;     //��ǰ��Ӧ��id

		$beginDeal = 0;          //ǰ�������Ƿ��Ѵ���
		$endDeal = 0;            //�������Ƿ��Ѵ���

		/********ͳ���������ɲ���***************/
		$this->getParam($object);
		$this->groupBy = 'supplierId,month(formDate)';//���ݹ�Ӧ��id��ȡ����
		$this->sort = 'supplierId';
		$this->asc = false;
		$rs = $this->listBySqlId('count_union');

		//�������ݴ���
		foreach($rs as $key => $val){
			$rtRows[] = $val;
		}

		$rtRows = array_merge( $rtRows , $countRows);
		return $rtRows;
	}
}
?>