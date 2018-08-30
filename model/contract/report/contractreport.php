<?php
/**
 * @author LiuB
 * @Date 2012��3��26��9:49:32
 * @version 1.0
 * @description: ��ͬ����
 */
 class model_contract_report_contractreport extends model_base {

    public $db;
	function __construct() {
		parent::__construct ();
	}



	/***********��ͬͼ�λ�����ʼ*****************/
	/**
	 * ������������ͳ�ƺ�ͬ����/���
	 */
	function getContractByArea($countField,$sqlPlus){
		$contractDao=new model_contract_contract_contract();
		$contractDao->groupBy="o.areaPrincipal";
		$sql="select areaPrincipal as '1',$countField as '2' from oa_contract_contract o where  (o.ExaStatus='���' or o.ExaStatus='���������')and(o.state!=0 and o.state!=5 and o.state!=6 and o.isTemp=0) $sqlPlus";
		//echo $sql;
		$rows = $contractDao->listBySql($sql);
		return $rows;
	}

	/**
	 * ���ݺ�ͬ����ͳ�ƺ�ͬ����/���
	 */
	function getContractByType($countField,$sqlPlus){
		$contractDao=new model_contract_contract_contract();
		$contractDao->groupBy="o.contractType";
		//��ǩ������ͳ��
		$sql="select  case  " .
				"when o.contractType='HTLX-XSHT' then '���ۺ�ͬ' " .
				"when o.contractType='HTLX-FWHT' then '���޺�ͬ'" .
				"when o.contractType='HTLX-ZLHT' then '�����ͬ'" .
				"when o.contractType='HTLX-YFHT' then '�з���ͬ'" .
				"else '' end  '1'" .
				",$countField as '2' from oa_contract_contract o where  (o.ExaStatus='���' or o.ExaStatus='���������')and(o.state!=0 and o.state!=5 and o.state!=6 and o.isTemp=0) $sqlPlus";
		$rows = $contractDao->listBySql($sql);
		return $rows;
	}

	/**
	 * ���ݺ�ͬ״̬ͳ�ƺ�ͬ����/���
	 *0 δ�ύ
	 *1 ������
	 *2 ִ����
	 *3 �ѹر�
	 *4 ��ִ��
	 *5 �Ѻϲ�
	 *6 �Ѳ��
	 *7 �쳣�ر�
	 */
	function getContractByStatus($countField,$sqlPlus){
		$contractDao=new model_contract_contract_contract();
		$hasContractCondition="(o.sign='1' and o.isTemp=0)";//��ǩ����ͬ
		$notContractCondition="(o.sign='0' and o.isTemp=0)";//δǩ����ͬ

		//DeliveryStatus 7 δ���� 8�ѷ��� 10���ַ���
//		$hasDelivery="s.DeliveryStatus=8";
//		$noDelivery="s.DeliveryStatus=7";
//		$partDelivery="s.DeliveryStatus=10";

		//$plusSql=" and (s.ExaStatus='���' or s.ExaStatus='���������') and(o.state!=0 and o.state!=5 and s.state!=6) $sqlPlus";
		//$joinOrderView="left join oa_contract_contract o on o.id=s.id";

		//��ǩ����ͬ������û�н�������ʵʩ����ɵ�
		//��ǩ��ͬ,δ���ִ��
		$sql="select $countField as num from oa_contract_contract o  where $hasContractCondition and o.state=2";
		$num1 = $contractDao->queryCount($sql);

		//�Ѿ���������ʵʩ����ɣ���û��ǩ����ͬ�ģ�
		//δǩ��ͬ,�����ִ��
		$sql="select $countField as num from oa_contract_contract o  where $notContractCondition and o.state=4";
		$num2 = $contractDao->queryCount($sql);

		//���ڽ�����������ʵʩ������û��ǩ����ͬ�ģ�
		$sql="select $countField as num from oa_contract_contract o  where $notContractCondition  and o.state=2";

		$num3 = $contractDao->queryCount($sql);
		//�Ѿ�ǩ����ͬ��Ҳ�Ѿ���ɽ�������ʵʩ������û����ȫ��Ʊ�ģ�
		$sql="select $countField as num from oa_contract_contract o  left join financeview_is_03_sumorder f on o.id=f.objId " .
				" where $hasContractCondition  and  o.state=4 and (o.contractMoney>f.invoiceMoney or f.invoiceMoney is null)";
		$num4 = $contractDao->queryCount($sql);

		//�Ѿ���Ʊ����û����ɽ�������ʵʩ���ĺ�ͬ��
//		$sql="select $countField as num from shipments_oa_order s $joinOrderView left join financeview_is_03_sumorder f on o.orgid=f.objId and o.tablename=f.orderObjType" .
//				" where $hasContractCondition  and s.DeliveryStatus in(7,10) $plusSql and o.contractMoney<=f.invoiceMoney";
		$countField1="count(f.objId)";
		if(strpos($countField,"contractMoney")>0){
			$countField1=$countField;
		}
//		$sql="select $countField1 as num from " .
//			"(select objId,objType,sum(if(isRed = 0,invoiceMoney,-invoiceMoney)) as invoiceMoney from financeView_invoice group by objId,objType) f ".
//			"left join  oa_contract_contract o on o.orgid=f.objId and o.tablename=f.objType ".
//			"where  o.contractMoney<=f.invoiceMoney and o.id in " .
//			"(select s.id from shipments_oa_order s  " .
//			"where $hasContractCondition and s.DeliveryStatus in(7,10) $plusSql )";
////echo $sql;
//
//
//		$num5 = $this->queryCount($sql);
		//�Ѿ���Ʊ3���¡�6���¡�һ�����ϣ���û������տ�ĺ�ͬ��
//		$sql="select $countField as num from oa_contract_contract o  left join financeview_is_03_sumorder f on o.orgid=f.objId and o.tablename=f.orderObjType" .
//				" left join (select objId,objType,max(invoiceTime) as maxInvoiceTime from oa_finance_invoice group by objId,objType) as i on i.objId=f.objId and i.objType=f.objType".
//				" where (o.ExaStatus='���' or o.ExaStatus='���������') and f.invoiceMoney>f.incomeMoney and (TO_DAYS(now())-TO_DAYS(i.maxInvoiceTime))>90";
//				//echo $sql;
//		$num6 = $this->queryCount($sql);
		$rows=array(
			array(
				"","��ǩ��ͬ,δ���ִ��",$num1
			),
			array(
				"","��ǩ��ͬ,�����ִ��,δ��ȫ��Ʊ",$num4
			),
			array(
				"","δǩ��ͬ,�����ִ��",$num2
			),
			array(
				"","δǩ��ͬ,δ���ִ��",$num3
			)
//			array(
//				"","�ѿ�Ʊδ����",$num5
//			),
//			array(
//				"","�ѿ�Ʊ3��������δ����տ�",$num6
//			)
		);
		return $rows;
	}
	/***********��ͬͼ�λ��������*****************/

 }
?>