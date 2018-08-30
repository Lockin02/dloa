<?php
/**
 * @author LiuB
 * @Date 2012年3月26日9:49:32
 * @version 1.0
 * @description: 合同报表
 */
 class model_contract_report_contractreport extends model_base {

    public $db;
	function __construct() {
		parent::__construct ();
	}



	/***********合同图形化报表开始*****************/
	/**
	 * 根据区域负责人统计合同数量/金额
	 */
	function getContractByArea($countField,$sqlPlus){
		$contractDao=new model_contract_contract_contract();
		$contractDao->groupBy="o.areaPrincipal";
		$sql="select areaPrincipal as '1',$countField as '2' from oa_contract_contract o where  (o.ExaStatus='完成' or o.ExaStatus='变更审批中')and(o.state!=0 and o.state!=5 and o.state!=6 and o.isTemp=0) $sqlPlus";
		//echo $sql;
		$rows = $contractDao->listBySql($sql);
		return $rows;
	}

	/**
	 * 根据合同类型统计合同数量/金额
	 */
	function getContractByType($countField,$sqlPlus){
		$contractDao=new model_contract_contract_contract();
		$contractDao->groupBy="o.contractType";
		//按签收类型统计
		$sql="select  case  " .
				"when o.contractType='HTLX-XSHT' then '销售合同' " .
				"when o.contractType='HTLX-FWHT' then '租赁合同'" .
				"when o.contractType='HTLX-ZLHT' then '服务合同'" .
				"when o.contractType='HTLX-YFHT' then '研发合同'" .
				"else '' end  '1'" .
				",$countField as '2' from oa_contract_contract o where  (o.ExaStatus='完成' or o.ExaStatus='变更审批中')and(o.state!=0 and o.state!=5 and o.state!=6 and o.isTemp=0) $sqlPlus";
		$rows = $contractDao->listBySql($sql);
		return $rows;
	}

	/**
	 * 根据合同状态统计合同数量/金额
	 *0 未提交
	 *1 审批中
	 *2 执行中
	 *3 已关闭
	 *4 已执行
	 *5 已合并
	 *6 已拆分
	 *7 异常关闭
	 */
	function getContractByStatus($countField,$sqlPlus){
		$contractDao=new model_contract_contract_contract();
		$hasContractCondition="(o.sign='1' and o.isTemp=0)";//已签订合同
		$notContractCondition="(o.sign='0' and o.isTemp=0)";//未签订合同

		//DeliveryStatus 7 未发货 8已发货 10部分发货
//		$hasDelivery="s.DeliveryStatus=8";
//		$noDelivery="s.DeliveryStatus=7";
//		$partDelivery="s.DeliveryStatus=10";

		//$plusSql=" and (s.ExaStatus='完成' or s.ExaStatus='变更审批中') and(o.state!=0 and o.state!=5 and s.state!=6) $sqlPlus";
		//$joinOrderView="left join oa_contract_contract o on o.id=s.id";

		//已签订合同，但还没有交付（或实施）完成的
		//已签合同,未完成执行
		$sql="select $countField as num from oa_contract_contract o  where $hasContractCondition and o.state=2";
		$num1 = $contractDao->queryCount($sql);

		//已经交付（或实施）完成，还没有签订合同的；
		//未签合同,已完成执行
		$sql="select $countField as num from oa_contract_contract o  where $notContractCondition and o.state=4";
		$num2 = $contractDao->queryCount($sql);

		//正在交付（或正在实施），还没有签订合同的；
		$sql="select $countField as num from oa_contract_contract o  where $notContractCondition  and o.state=2";

		$num3 = $contractDao->queryCount($sql);
		//已经签订合同，也已经完成交付（或实施），还没有完全开票的；
		$sql="select $countField as num from oa_contract_contract o  left join financeview_is_03_sumorder f on o.id=f.objId " .
				" where $hasContractCondition  and  o.state=4 and (o.contractMoney>f.invoiceMoney or f.invoiceMoney is null)";
		$num4 = $contractDao->queryCount($sql);

		//已经开票，还没有完成交付（或实施）的合同；
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
		//已经开票3个月、6个月、一年以上，还没有完成收款的合同；
//		$sql="select $countField as num from oa_contract_contract o  left join financeview_is_03_sumorder f on o.orgid=f.objId and o.tablename=f.orderObjType" .
//				" left join (select objId,objType,max(invoiceTime) as maxInvoiceTime from oa_finance_invoice group by objId,objType) as i on i.objId=f.objId and i.objType=f.objType".
//				" where (o.ExaStatus='完成' or o.ExaStatus='变更审批中') and f.invoiceMoney>f.incomeMoney and (TO_DAYS(now())-TO_DAYS(i.maxInvoiceTime))>90";
//				//echo $sql;
//		$num6 = $this->queryCount($sql);
		$rows=array(
			array(
				"","已签合同,未完成执行",$num1
			),
			array(
				"","已签合同,已完成执行,未完全开票",$num4
			),
			array(
				"","未签合同,已完成执行",$num2
			),
			array(
				"","未签合同,未完成执行",$num3
			)
//			array(
//				"","已开票未交付",$num5
//			),
//			array(
//				"","已开票3个月以上未完成收款",$num6
//			)
		);
		return $rows;
	}
	/***********合同图形化报表结束*****************/

 }
?>