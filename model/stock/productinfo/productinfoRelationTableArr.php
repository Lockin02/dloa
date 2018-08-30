<?php
/**
 * 物料关联业务表数组
 * key 为业务表名
 * value 为关联的字段关系
 */
$productRelationTableArr=array(
	"sale"=>
	array(
		//销售合同物料从表
		"oa_contract_equ" => array(
			"销售合同",
			"id" => "productId",
			"productName" => "productName",
			"productCode" => "productCode",
            "pattern"=> "productModel",
            "unitName"=>"unitName"
		),
		//租赁合同物料从表
//		"oa_lease_equ" => array(
//			"租赁合同",
//			"id" => "productId",
//			"productName" => "productName",
//			"productCode" => "productNo",
//            "pattern"=> "productModel",
//            "unitName"=>"unitName"
//
//		),
//		//研发合同物料从表
//		"oa_rdproject_equ" => array(
//			"研发合同",
//			"id" => "productId",
//			"productName" => "productName",
//			"productCode" => "productNo",
//            "pattern"=> "productModel",
//            "unitName"=>"unitName"
//             ),
//		//服务合同物料从表
//		"oa_service_equ" => array(
//			"服务合同",
//			"id" => "productId",
//			"productName" => "productName",
//			"productCode" => "productCode",
//            "pattern"=> "productModel",
//            "unitName"=>"unitName"
//
//		),
//		//商机物料从表
//		"oa_sale_chance_equ" => array(
//			"商机",
//			"id" => "productId",
//			"productName" => "productName",
//			"productCode" => "productCode",
//            "pattern"=> "productModel",
//            "unitName"=>"unitName"
//
//		),
		//赠送物料从表
		"oa_present_equ" => array(
			"赠送",
			"id" => "productId",
			"productName" => "productName",
			"productCode" => "productNo",
            "pattern"=> "productModel",
            "unitName"=>"unitName"

		),
		//退货物料从表
		"oa_sale_return_equ" => array(
			"退货",
			"id" => "productId",
			"productName" => "productName",
			"productCode" => "productNo",
            "pattern"=> "productModel",
            "unitName"=>"unitName"

		),
		//借试用物料从表
		"oa_borrow_equ" => array(
			"借试用",
			"id" => "productId",
			"productName" => "productName",
			"productCode" => "productNo",
            "pattern"=> "productModel",
            "unitName"=>"unitName"
            )
	),
	"delivery"=>
	array(
		//发货计划物料明细
		'oa_stock_outplan_product'=>array(
			"发货计划物料明细",
			"id"=>"productId",
			"productName"=>"productName",
			"productCode"=>"productNo",
			"pattern"=>"productModel",
			"unitName"=>"unitName"
		),
		//发货单物料明细
		'oa_stock_ship_product'=>array(
			"发货单物料明细",
			"id"=>"productId",
			"productName"=>"productName",
			"productCode"=>"productNo",
			"pattern"=>"productModel",
			"unitName"=>"unitName"
		),
		//邮寄信息物料明细
		'oa_mail_products'=>array(
			"邮寄信息物料明细",
			"id"=>"productId",
			"productName"=>"productName",
			"productCode"=>"productNo"
		)
	),
//	"production"=>
//	array(
//		//生产任务物料明细
//		'oa_produce_protaskequ'=>array(
//			"生产任务物料明细",
//			"id"=>"productId",
//			"productName"=>"productName",
//			"productCode"=>"productNo",
//			"pattern"=>"productModel",
//			"unitName"=>"unitName"
//		)
//	),
	"purch"=>
	array(
		//采购申请物料清单
		'oa_purch_plan_equ'=>array(
			"采购申请",
			'id'=>'productId',
			'productCode'=>'productNumb',
			'productName'=>'productName',
			'pattern'=>'pattem',
			'unitName'=>'unitName'
		),
		//采购任务物料清单
		'oa_purch_task_equ'=>array(
			"采购任务",
			'id'=>'productId',
			'productCode'=>'productNumb',
			'productName'=>'productName',
			'pattern'=>'pattem',
			'unitName'=>'unitName'
		),
		//采购询价物料清单
		'oa_purch_inquiry_equ'=>array(
			"采购询价",
			'id'=>'productId',
			'productCode'=>'productNumb',
			'productName'=>'productName',
			'pattern'=>'pattem',
			'unitName'=>'units'
		),
		//采购询价报价物料清单
		'oa_purch_inquiry_suppequ'=>array(
			"采购询价报价",
			'id'=>'productId',
			'productCode'=>'productNumb',
			'productName'=>'productName',
			'pattern'=>'pattem',
			'unitName'=>'units'
		),
		//采购订单物料清单
		'oa_purch_apply_equ'=>array(
			"采购订单",
			'id'=>'productId',
			'productCode'=>'productNumb',
			'productName'=>'productName',
			'pattern'=>'pattem',
			'unitName'=>'units'
		),
		//收料物料清单
		'oa_purchase_arrival_equ'=>array(
			"收料",
			'id'=>'productId',
			'productCode'=>'sequence',
			'productName'=>'productName',
			'pattern'=>'pattem',
			'unitName'=>'units'
		),
		//退料物料清单
		'oa_purchase_delivered_equ'=>array(
			"退料",
			'id'=>'productId',
			'productCode'=>'productNumb',
			'productName'=>'productName',
			'pattern'=>'pattem',
			'unitName'=>'units'
		),
		//供应商运营库物料清单
		'oa_supp_prod'=>array(
			"供应商运营库",
			'id'=>'productId',
			'productName'=>'productName'
		),
		//供应商临时库物料清单
		'oa_supp_prod_temp'=>array(
			"供应商临时库",
			'id'=>'productId',
			'productName'=>'productName'
		)
	),
	"finance"=>
	array(
		//余额调整单
		'oa_finance_adjustment_detail'=>array(
			"余额调整单",
			"id"=>"productId",
			"productName"=>"productName",
			"productCode"=>"productNo",
			'pattern' => 'productModel'
		),
		//补差单
		'oa_finance_costajust_detail'=>array(
			"补差单",
			"id"=>"productId",
			"productName"=>"productName",
			"productCode"=>"productNo",
			'pattern' => 'productModel'
		),
		//采购发票
		'oa_finance_invpurchase_detail'=>array(
			"采购发票",
			"id"=>"productId",
			"productName"=>"productName",
			"productCode"=>"productNo",
			'pattern' => 'productModel',
			'unitName' => 'unit'
		),
		//钩稽日志
		'oa_finance_related_detail'=>array(
			"钩稽日志",
			"id"=>"productId",
			"productName"=>"productName",
			"productCode"=>"productNo",
			'pattern' => 'productModel',
			'unitName' => 'unit'
		),
		//存货余额
		'oa_finance_stockbalance'=>array(
			"存货余额",
			"id"=>"productId",
			"productName"=>"productName",
			"productCode"=>"productNo",
			'pattern' => 'productModel',
			'unitName' => 'units'
		)
	),
	"stock"=>
	array(
		//期初库存
		'oa_stock_inventory_info'=>array(
			"期初库存",
			'id'=>'productId',
			'productCode'=>'productCode',
			'productName'=>'productName',
			'pattern'=>'pattern',
			'unitName'=>'unitName'
		),
		//仓库锁定记录
		'oa_stock_lock'=>array(
			"仓库锁定记录",
			'id'=>'productId',
			'productCode'=>'productNo',
			'productName'=>'productName'
		),
		//入库单物料清单
		'oa_stock_instock_item'=>array(
			"入库单",
			'id'=>'productId',
			'productCode'=>'productCode',
			'productName'=>'productName',
			'pattern'=>'pattern',
			'unitName'=>'unitName'
		),
		//出库单物料清单
		'oa_stock_outstock_item'=>array(
			"出库单",
			'id'=>'productId',
			'productCode'=>'productCode',
			'productName'=>'productName',
			'pattern'=>'pattern',
			'unitName'=>'unitName'
		),
		//出库单物料额外配套清单
		'oa_stock_stockout_extraitem'=>array(
			"出库单物料额外配套清单",
			'id'=>'productId',
			'productCode'=>'productCode',
			'productName'=>'productName',
			'pattern'=>'pattern'
		),
		//物料序列号台账
		'oa_stock_product_serialno'=>array(
			"物料序列号台账",
			'id'=>'productId',
			'productCode'=>'productCode',
			'productName'=>'productName',
			'pattern'=>'pattern'
		),
		//物料批次号台账
		'oa_stock_product_batchno'=>array(
			"物料批次号台账",
			'id'=>'productId',
			'productCode'=>'productCode',
			'productName'=>'productName'
		),
		//盘点物料清单
		'oa_stock_check_item'=>array(
			"盘点",
			'id'=>'productId',
			'productCode'=>'productCode',
			'productName'=>'productName',
			'pattern'=>'pattern',
			'unitName'=>'unitName'
		),
		//补库计划物料清单
		'oa_stock_fillup_detail'=>array(
			"补库计划",
			'id'=>'productId',
			'productCode'=>'sequence',
			'productName'=>'productName',
			'pattern'=>'pattern',
			'unitName'=>'unitName'
		),
		//调拨单物料清单
		'oa_stock_allocation_item'=>array(
			"调拨单",
			'id'=>'productId',
			'productCode'=>'productCode',
			'productName'=>'productName',
			'pattern'=>'pattern',
			'unitName'=>'unitName'
		),
		//物料配件
		'oa_stock_product_configuration'=>array(
			"物料配件",
			'id'=>'configId',
			'productCode'=>'configCode',
			'productName'=>'configName',
			'pattern'=>'configPattern',
			'condition'=>" and configType<>'proconfig' "
		)
	)
//	,"asset"=>
//	array(
//		//资产卡片
//		'oa_asset_card'=>array(
//			"资产卡片",
//			'id'=>'productId',
//			'productCode'=>'productCode',
//			'productName'=>'productName'
//		),
//		//资产卡片临时新增表
//		'oa_asset_card_temp'=>array(
//			"资产卡片临时新增表",
//			'id'=>'productId',
//			'productCode'=>'productCode',
//			'productName'=>'productName'
//		)
//	)

);

?>
