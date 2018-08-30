<?php
header("Content-type: text/html; charset=gb2312");
/**
 * Ӧ���˿�model����
 */
class model_finance_receviable_receviable extends model_base {

	function __construct() {
		//$this->tbl_name = "oa_finance_receviable";
		$this->sql_map = "finance/receviable/receviableSql.php";
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊ���ýӿڷ���,����Ϊ����ģ��������------------------------------
	 *************************************************************************************************/

	/**
	 * ��ȡ��ϸ - ����ͳ��
	 */
	function getPayDetail_d($object){
		$rs = array(); //��������
		$countRow = array( 'id' => 'balance', 'deptName' => '�ڳ����:' ); //ͳ��������
		$rsKey = null; //������
		$markKey = null;  //�������
		$periodAmount = 0; //���ںϼ�
		$periodPayed = 0;  //����ʵ��
		$periodBalance = 0;  //�������
		$periodWord = '���ںϼ�:';
		$yearAmount = 0;  //����ϼ�
		$yearPayed = 0;  //����ʵ��
		$yearWord = '�����ۼ�:';
		$nowAmount = 0;

		//����
		$balanceDao = new model_finance_balance_balance();

		$balanceRow = $balanceDao->rtBalance_d($object['year'],$object['beginMonth'],0,$object['customerId']);
		if($balanceRow){
			$countRow['amount'] = $balanceRow['needPay'];
			$countRow['trueReceive'] = $balanceRow['payed'];
			$countRow['balance'] = $balanceRow['balance'];

			$periodAmount = $balanceRow['needPay'];
			$periodPayed = $balanceRow['payed'];
			$periodBalance = $balanceRow['balance'];

			$isInPeriod = $balanceDao->isBigThanPeriod_d($object['year'],$object['beginMonth']);
//			print_r($isInPeriod);
			if($isInPeriod){
				$thisRs = $this->getFormCountByMonth_d($object,$isInPeriod);
//				print_r($thisRs);
				$countRow['amount'] += $thisRs['amount'];
				$countRow['trueReceive'] += $thisRs['trueReceive'];
				$countRow['balance'] += $thisRs['balance'];

				$periodAmount += $thisRs['amount'];
				$periodPayed += $thisRs['trueReceive'];
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
				$countRow['deptName'] = $periodWord;
				$countRow['amount'] = $periodAmount;
				$countRow['trueReceive'] = $periodPayed;
				$rs[] = $countRow;

				//ͳ�Ʊ���,�����������
				$yearAmount += $periodAmount;
				$yearPayed += $periodPayed;
				$countRow['id'] = 'year'.$rsKey;
				$countRow['deptName'] = $yearWord;
				$countRow['amount'] = $yearAmount;
				$countRow['trueReceive'] = $yearPayed;
				$rs[] = $countRow;

				$markKey = $rsKey;
				$periodAmount = 0;
				$periodPayed = 0;
			}else if(empty($markKey)){//�޳�ʼ��¼ֵ,���г�ʼ��ֵ
				$markKey = $rsKey ;
			}
			$periodAmount += $val['amount'] ;
			$periodPayed += $val['trueReceive'] ;
			if(empty($val['amount'])){//�жϣ������Ӧ��������Ӧ�����������Ӧ�����
				$periodBalance -= $val['trueReceive'] ;
			}else{
				$periodBalance += $val['amount'] ;
			}
			$val['balance'] = $periodBalance;
			$rs[] = $val;
		}

		//�����������һ�ڵĽ��
		$countRow['id'] = 'period'.$rsKey;
		$countRow['deptName'] = $periodWord;
		$countRow['amount'] = $periodAmount;
		$countRow['trueReceive'] = $periodPayed;
		$rs[] = $countRow;

		//�����������һ��Ľ��
		$yearAmount += $periodAmount;
		$yearPayed += $periodPayed;
		$countRow['id'] = 'year'.$rsKey;
		$countRow['deptName'] = $yearWord;
		$countRow['amount'] = $yearAmount;
		$countRow['trueReceive'] = $yearPayed;
		$rs[] = $countRow;
		return $rs;
	}

	/**
	 * ��ȡ�ڳ��������
	 */
	function getFormCountByMonth_d($object,$isInPeriod){
		$this->searchArr['formDate'] = $isInPeriod['thisDate'];
		$this->searchArr['formDateMonth'] = $object['beginMonth'];
		if(!empty($object['customerId'])){
			$this->searchArr['customerId'] = $object['customerId'];
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
	 * ͳ�ƺ�ͬӦ�պ͵���,��������
	 */
	function getInvoiceAndIncome_d($id,$contractType){
		$sql = "select
				sum(moneyDetail.invoiceMoney) as invoiceMoney,sum(moneyDetail.incomeMoney) as incomeMoney ,
				sum(moneyDetail.softMoney) as softMoney,sum(moneyDetail.hardMoney) as hardMoney,
				sum(moneyDetail.serviceMoney) as serviceMoney,sum(moneyDetail.repairMoney) as repairMoney,
				sum(moneyDetail.applyedMoney) as applyedMoney
			from (
				select sum(if(c.isRed = 0 ,c.invoiceMoney,-c.invoiceMoney)) as invoiceMoney,0 as incomeMoney,sum(if( c.isRed = 0,c.softMoney,-c.softMoney)) as softMoney,sum(if( c.isRed = 0,c.hardMoney,-c.hardMoney)) as hardMoney,
					sum(if( c.isRed = 0,c.serviceMoney,-c.serviceMoney)) as serviceMoney,sum(if(c.isRed = 0 ,c.repairMoney,-c.repairMoney)) as repairMoney,0 as applyedMoney
				from financeView_invoice c where c.objId = $id and c.objType = '$contractType'
				union all
				select 0 as invoiceMoney,sum(if(i.formType = 'YFLX-TKD', -c.money,c.money)) as incomeMoney,0 as softMoney,0 as hardMoney,0 as serviceMoney,0 as repairMoney,0 as applyedMoney
				from financeview_income_allot c left join oa_finance_income i on c.incomeId = i.id where c.objId = $id and c.objType = '$contractType'
				union all
				select 0 as invoiceMoney,0 as incomeMoney,0 as softMoney,0 as hardMoney,0 as serviceMoney,0 as repairMoney,sum(invoiceMoney) as applyedMoney
				from oa_finance_invoiceapply c inner join financeview_invoiceapply_relate r on c.id = r.applyId where c.ExaStatus <> '���' and r.objId = $id  and r.objType = '$contractType'
			) moneyDetail limit 1";
		$rs = $this->_db->getArray($sql);
		if(!$rs){
			return array( "invoiceMoney" => 0 , "incomeMoney" => 0 );
		}
		return $rs[0];
	}

    /**
     * ������ϸ��
     */
    function filterArr_d($rows){
        $rtArr = array();
        $incomeMoney = $allotAble = $money = 0;
        if($rows){
            $markId = null;
            foreach($rows as $key => $val){
                if($markId != $val['id']){
                    //���ֵ����¼id����ʱ��ֱ�ӽ����鸳�赽����������
                    $rtArr[$key] = $val;
                    $markId = $val['id'];

                    $incomeMoney = bcadd($incomeMoney,$val['incomeMoney'],2);
                    $unIncomeMoney = bcadd($unIncomeMoney,$val['unIncomeMoney'],2);
                    $invoiceMoney = bcadd($invoiceMoney,$val['invoiceMoney'],2);
                    $unInvoiceMoney = bcadd($unInvoiceMoney,$val['unInvoiceMoney'],2);
                    $allotAble = bcadd($allotAble,$val['allotAble'],2);
                    $money = bcadd($money,$val['thisOrderMoney'],2);
                }else{
                	$rtArr[$key]['objId'] = $val['objId'];
                    $rtArr[$key]['objType'] = $val['objType'];
                    $rtArr[$key]['objCode'] = $val['objCode'];
                    $rtArr[$key]['money'] = $val['money'];
                    $rtArr[$key]['id'] = $val['id'];
                    $money = bcadd($money,$val['thisOrderMoney'],2);
                    $incomeMoney = bcadd($incomeMoney,$val['incomeMoney'],2);
                    $unIncomeMoney = bcadd($unIncomeMoney,$val['unIncomeMoney'],2);
                    $invoiceMoney = bcadd($invoiceMoney,$val['invoiceMoney'],2);
                    $unInvoiceMoney = bcadd($unInvoiceMoney,$val['unInvoiceMoney'],2);
                }
            }
        }
        array_push($rtArr,array('id' => 'noId' , 'areaPrincipal' => '�ϼƣ�','thisOrderMoney' => $money  ,'incomeMoney' => $incomeMoney,'unIncomeMoney'=>$unIncomeMoney ,'invoiceMoney' => $invoiceMoney,'unInvoiceMoney'=>$unInvoiceMoney));
        return $rtArr;
    }

	/**
	 * ��ȡ��ǰ������
	 */
	function rtThisPeriod_d(){
		$periodDao = new model_finance_period_period();
		return $periodDao->rtThisPeriod_d();
	}


	/*************************** �º�ͬ���� **************************/
	/**
	 * ��ʼ��
	 */
	function initIncomeAnalysis_d($object){
		$sql = "select
				i.incomeDate,year(i.incomeDate) as thisYear,month(i.incomeDate) as thisMonth,i.customerId,i.customerName,
				i.objId,i.objType,
				sum(i.incomeMoney) as incomeMoney,
				i.incomeDetail,
				if(f.invoiceMoney is null,0,f.invoiceMoney) as invoiceMoney
			from
			(
			select
				i.incomeDate,i.customerId,i.customerName,
				i.objId,i.objType,
				sum(i.incomeMoney) as incomeMoney,
				group_concat(concat(cast(i.incomeDate as char charset gbk),' , ',cast(i.incomeMoney as char charset gbk)) separator ' ; ') as incomeDetail,
				0 as invoiceMoney
			from
				(
				select
					i.incomeNo,i.incomeUnitId as customerId,i.incomeUnitName as customerName,i.incomeDate,
					a.objId,a.objType,
					if(a.objId is null,i.incomeMoney ,if(i.formType <> 'YFLX-TKD' ,a.money,-a.money) ) as incomeMoney
				from
					oa_finance_income i
					left join
					oa_finance_income_allot a on i.id = a.incomeId
				where date_format(i.incomeDate,'%Y%m') between date_format('2012-01-01','%Y%m') and date_format('2012-04-30','%Y%m')
				union all
				select
					i.incomeNo,i.incomeUnitId as customerId,i.incomeUnitName as customerName,i.incomeDate,
					null as objId,null as objType,
					if(i.formType <> 'YFLX-TKD' ,i.allotAble,-i.allotAble) as incomeMoney
				from
					oa_finance_income i
				where i.status = 'DKZT-BFFP' and date_format(i.incomeDate,'%Y%m') between date_format('2012-01-01','%Y%m') and date_format('2012-04-30','%Y%m')
				) i
			group by date_format(i.incomeDate,'%Y%m'),i.objType,i.objId,i.customerName

			) i left join (
				select
					i.objId,i.objType,sum(i.invoiceMoney) as invoiceMoney
				from
					oa_finance_invoice i
				where i.objId <> 0
				group by i.objType,i.objId
				) f on i.objId = f.objId and i.objType = f.objType
			group by date_format(i.incomeDate,'%Y%m'),i.objType,i.objId,i.customerName";
	}
}
?>