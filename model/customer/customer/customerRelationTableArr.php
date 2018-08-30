<?php
/**
 * 客户关联业务表数组
 * key 为业务表名
 * value 为关联的字段关系
 */
$customerRelationTableArr=array(
	"sale"=>
	array(
		// 销售线索
		"oa_sale_clues"=>array("销售线索","id"=>"customerId","Name"=>"customerName"),
		//联系人
		"oa_customer_linkman"=>array("联系人","id"=>"customerId"),
		//商机
		"oa_sale_chance"=>array("商机","id"=>"customerId","Name"=>"customerName"),
		//借试用
		"oa_borrow_borrow"=>array("借试用","id"=>"customerId","Name"=>"customerName"),
		//赠送
		"oa_present_present"=>array("赠送","id"=>"customerNameId","Name"=>"customerName"),
		//销售合同
		"oa_contract_contract"=>array("合同","id"=>"customerId","Name"=>"customerName")
		//服务合同
//		"oa_sale_service"=>array("服务合同","id"=>"cusNameId","Name"=>"cusName"),
//		//租赁合同
//		"oa_sale_lease"=>array("租赁合同","id"=>"tenantId","Name"=>"tenant"),
//		//研发合同
//		"oa_sale_rdproject"=>array("研发合同","id"=>"cusNameId","Name"=>"cusName")
	),
	"delivery"=>
	array(
		//邮寄信息
		"oa_mail_info"=>array("邮寄信息","id"=>"customerId","Name"=>"customerName"),
		//发货计划
		"oa_stock_outplan"=>array("发货计划","id"=>"customerId","Name"=>"customerName"),
		//发货单
		"oa_stock_ship"=>array("发货单","id"=>"customerId","Name"=>"customerName")
	),
//	"production"=>
//	array(
//		//生产任务
//		"oa_produce_protask"=>array("生产任务","id"=>"customerId","Name"=>"customerName")
//	),
	"finance"=>
	array(
		//开票记录
		"oa_finance_invoice"=> array("开票记录","id"=>'invoiceUnitId',"Name"=>'invoiceUnitName'),
		//开票申请
		"oa_finance_invoiceapply" => array("开票申请","id"=>'customerId',"Name"=>'customerName'),
		//到款记录
		"oa_finance_income" => array("到款记录","id"=>'incomeUnitId',"Name"=>'incomeUnitName')
	),
	"stock"=>
	array(
		//入库
		"oa_stock_instock"=>array("入库单","id"=>'clientId',"Name"=>'clientName'),
		//出库
		"oa_stock_outstock"=>array("出库单","id"=>'customerId',"Name"=>'customerName'),
		//调拨
		"oa_stock_allocation"=>array("调拨单","id"=>'customerId',"Name"=>'customerName')
	)
);
?>