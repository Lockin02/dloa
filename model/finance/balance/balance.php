<?php
/**
 * @author Show
 * @Date 2011��5��19�� ������ 19:34:41
 * @version 1.0
 * @description:�ڳ�����(Ӧ��Ӧ��) Model�� ������� formType
                                   0. Ӧ��
                                   1. Ӧ��
 */
class model_finance_balance_balance extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_balance";
		$this->sql_map = "finance/balance/balanceSql.php";
		parent::__construct ();
	}

	//Ӧ��Ӧ������
	private $innerArr = array (
		'Ӧ���˿�','Ӧ���˿�'
	);

	//Ӧ��Ӧ��code����
	private $codeArr = array(
		'income','pay'
	);

	//Ӧ��Ӧ��val����
	private $valArr = array(
		'��','��'
	);

	//����Ӧ��Ӧ��
	public function rtBalanceType_d( $val = 0 ){
		return $this->innerArr[$val];
	}

	//����Ӧ��Ӧ��code
	public function rtBalanceCode_d( $val = 0 ){
		return $this->codeArr[$val];
	}

	//����Ӧ��Ӧ��ֵ
	public function rtBalanceVal_d( $val = 0 ){
		return $this->valArr[$val];
	}

   	/**
     * �����·ݷ����ڳ����,���������,�򷵻�0
     * ���� 1 ��
     * ���� 2 ��
     * ���� 3 �������
     * ���� 4 ��Ӧ��/�ͻ� id��
     */
	function rtBalance_d($thisYear = null ,$thisMonth = null ,$formType = 1,$objIds = null ){
		$this->searchArr['thisYear'] = $thisYear;
		$this->searchArr['thisMonth'] = $thisMonth;
		$this->searchArr['formType'] = $formType;
//		$this->searchArr['isUsing'] = 1;
		$this->asc = true;
		$this->sort = 'c.thisMonth';
		if(!empty($objIds)){
			$this->searchArr['objectIds'] = $objIds;
		}
		$this->groupBy = 'c.thisMonth';
		$rs = $this->listBySqlId('count_list');
		if($rs){
			return $rs[0];
		}else{
			return 0;
		}
    }

    /**
     * �жϵ�ǰ��ѯ�·��Ƿ���ڵ�ǰ��������   ���� Ӧ��Ӧ������ʹ��
     */
    function isBigThanPeriod_d($thisYear,$thisMonth,$formType = 0){
    	$isBig = 0;
		$rs = $this->find(array('isUsing' => 1 ,'formType' => $formType ),null , 'thisYear,thisMonth,thisDate');
		if($rs){
			if($thisYear > $rs['thisYear']){
				$isBig = $rs;
			}else{
				if($thisMonth > $rs['thisMonth']){
					$isBig = $rs;
				}
			}
		}
		return $isBig;
    }

	/**
	 * �жϵ�ǰ�������Ƿ���ǰ������
	 */
	function isFirstPeriod_d($formType = 0){
		$rs = $this->find(array( 'formType' => $formType),'thisDate asc','isUsing');
		if($rs){
			return $rs['isUsing'];
		}else{
			return 1;
		}
	}

	/**
	 * �жϵ�ǰ�������Ƿ���������
	 */
	function isLastPeriod_d($formType = 0){
		$rs = $this->find(array( 'formType' => $formType),'thisDate desc,thisYear desc,thisMonth desc','isUsing');
		if($rs){
			return $rs['isUsing'];
		}else{
			return 1;
		}
	}


	/**
	 * ��ȡ��ǰ����������Ϣ
	 */
	function getThisPeriod_d($formType = 0){
		$rs = $this->find(array( 'isUsing' => 1,'formType' => $formType),null,'thisYear,thisMonth,thisDate');
		return $rs;
	}

	/**
	 * ɾ��ʱ���ļ�¼
	 */
	function deletePeriod_d($year,$month,$formType){
		return $this->delete(array('thisYear'=> $year ,'thisMonth' => $month , 'formType'=> $formType));
	}

	/**
	 * ��������,���ʵ�������ͬʱΪ0�����������
	 */
	function filterArr_d($object){
		if($object){
			foreach($object as $key => $val){
				if($val['payed'] == 0 && $val['balance'] == 0 ){
					unset($object[$key]);
				}
			}
		}
		return $object;
	}


    /************************���㲿��**********************/
	/**
	 * ���㷽��
	 */
    function checkout_d($formType = 0){
		//��ȡ��ǰ��������
    	$object = $this->getThisPeriod_d($formType);

    	try{
			$this->start_d();

			//�޸ĵ�ǰ������Ϊδʹ��
			$this->updateField(array('isUsing' => 1 ,'formType'=> $formType),'isUsing',0);
			//�޸�ǰһ������Ϊ��ʹ��

			if($object['thisMonth'] != 12){
				$newMonth = $object['thisMonth'] + 1;
				$this->updateField(array('thisYear' => $object['thisYear'],'thisMonth' => $newMonth ,'formType'=> $formType ),'isUsing',1);
			}else{
				$newYear = $object['thisYear'] + 1;
				$this->updateField(array('thisYear' => $newYear,'thisMonth' => 1,'formType'=> $formType ),'isUsing',1);
			}
			//�޸�ǰһ�ڲ����Ӧ����Ϊ����

			$this->commit_d();
			return true;
    	}catch(exception $e){
    		$this->rollBack();
    		return false;
    	}
    }

	/**
	 * ������
	 */
    function balanceCount_d($formType = 0){
    	$thisRs = array();		//����Ӧ���˿�����

		//��ȡ��ǰ��������
    	$object = $this->getThisPeriod_d($formType);

		//��ȡ��ǰӦ��/Ӧ���˿�
		$thisRs = $this->countBalance_d($object['thisYear'],$object['thisMonth'],$formType);

		if($object['thisMonth'] != 12){
			$newMonth = $object['thisMonth'] + 1;
			$newYear = $object['thisYear'] ;
		}else{
			$newYear = $object['thisYear'] + 1;
			$newMonth = 1 ;
		}

		//�������飬������ʵ�ս��Ϊ0ʱ����
		$thisRs = $this->filterArr_d($thisRs);
    	try{
			$this->start_d();

			$this->deletePeriod_d($newYear,$newMonth,$formType);
			//��������
			$this->createBatch($thisRs);

//    		$this->rollBack();
			$this->commit_d();
			return true;
    	}catch(exception $e){
    		$this->rollBack();
    		return false;
    	}
    }


    /**
     * ������
     */
    function uncheckout_d($formType = 0){
		//��ȡ��ǰ��������
    	$object = $this->getThisPeriod_d($formType);
		try{
			$this->start_d();

			//�޸ĵ�ǰ������Ϊδʹ��
			$this->updateField(array('isUsing' => 1 ,'formType'=> $formType),'isUsing',0);
			//�޸�ǰһ������Ϊ��ʹ��

			if($object['thisMonth'] != 1){
				$newMonth = $object['thisMonth'] - 1;
				$this->updateField(array('thisYear' => $object['thisYear'],'thisMonth' => $newMonth ,'formType'=> $formType ),'isUsing',1);
			}else{
				$newYear = $object['thisYear'] - 1;
				$this->updateField(array('thisYear' => $newYear,'thisMonth' => 12,'formType'=> $formType ),'isUsing',1);
			}
			//�޸�ǰһ�ڲ����Ӧ����Ϊ����

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
    		return false;
		}

    }


    /**
     * ����ʱ���ڵĶ�Ӧ���
     */
    function countBalance_d($year,$month,$formType = 0){
    	if($formType){
			$sql = "select
						sum(needPay) as needPay,sum(payed) as payed , sum(balance) as balance ,formType ,objectId ,objectName ,directions ,directionsName ,if(thisMonth = 12,thisYear + 1,thisYear) as thisYear,if(thisMonth = 12 ,1,thisMonth +1 ) as thisMonth, now() as thisDate
					from (
					select 1 as formType,a.objectId,a.objectName ,'J' as directions ,'��' as directionsName,sum(a.amount) as needPay,sum(a.needPay) as payed , sum(a.balance) as balance,thisYear ,thisMonth, now() as thisDate from (
						select
						c.supplierName as objectName,c.supplierId as objectId,c.formDate as formDate,c.departmentsId as deptId ,c.salesmanId as salesmanId,c.formType as formType,
						if(c.formType = 'blue' , c.amount ,-c.amount ) as amount , 0 as needPay ,if(c.formType = 'blue' , c.amount ,-c.amount ) as balance ,
						year(c.formDate) as thisYear , month(c.formDate) as thisMonth
						from oa_finance_invpurchase c where c.status <> 'CGFPZT-WSH' and year(formDate) = $year and month(formDate) = $month
						union
						select
						b.supplierName  as objectName,b.supplierId as objectId ,b.formDate as formDate ,b.deptId as deptId,b.salesmanId as salesmanId,b.formType as formType,
						0 as amount,if(b.formType = 'CWYF-03', -b.amount ,b.amount ) as needPay ,if(b.formType = 'CWYF-03', b.amount ,-b.amount ) as balance ,
						year(b.formDate) as thisYear , month(b.formDate) as thisMonth
						from oa_finance_payables b where year(formDate) = $year and month(formDate) = $month
					) a where 1=1 group by objectId
					union
					select ba.formType ,ba.objectId ,ba.objectName ,ba.directions ,ba.directionsName ,ba.balance as needPay,0 as payed ,ba.balance as balance,ba.thisYear ,ba.thisMonth ,ba.thisDate
						from oa_finance_balance ba where ba.formType = 1 and ba.thisYear = $year and ba.thisMonth = $month
					)  db group by objectId";
    	}else{
			$sql = "select
				       sum(needPay) as needPay,sum(payed) as payed , sum(balance) as balance ,formType ,objectId ,objectName ,directions ,directionsName ,if(thisMonth = 12,thisYear + 1,thisYear) as thisYear,if(thisMonth = 12 ,1,thisMonth +1 ) as thisMonth, now() as thisDate
				    from (
				    	select
							0 as formType ,customerId as objectId,customerName as objectName,'D' as directions , '��'  as directionsName, sum(db.amount) as needPay,sum(db.trueReceive) as payed, sum(db.balance ) as balance ,year(formDate) as thisYear,
							month(formDate) as thisMonth , formDate as thisDate
							from
							(select
								i.invoiceTime as formDate ,i.invoiceUnitId as customerId ,i.invoiceUnitName as customerName ,i.invoiceMoney as amount ,0 as trueReceive,i.invoiceMoney as balance
								from oa_finance_invoice i where year(i.invoiceTime) = $year and month(i.invoiceTime) = $month
						    union
							select
								c.incomeDate as formDate ,c.incomeUnitId as customerId ,c.incomeUnitName as customerName,0 as amount ,if( c.formType = 'YFLX-TKD' , -c.incomeMoney,c.incomeMoney) as trueReceive,
								if( c.formType = 'YFLX-TKD' , c.incomeMoney, - c.incomeMoney) as balance
							from
						 		oa_finance_income c where year(c.incomeDate) = $year and month(c.incomeDate) = $month
								) db group By customerId
						union
						select c.formType ,c.objectId ,c.objectName ,c.directions ,c.directionsName ,c.balance as needPay,0 as payed ,c.balance as balance,c.thisYear ,c.thisMonth ,c.thisDate  from oa_finance_balance c where c.formType = $formType and c.thisYear = $year and c.thisMonth = $month
					) thisValue group by objectId ";
    	}
		$this->sort = 'objectId';
		return $this->listBySql($sql);
    }
}
?>