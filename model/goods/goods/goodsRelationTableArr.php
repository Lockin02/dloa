<?php
/**
 * 产品关联业务表数组
 * key 为业务表名
 * value 为关联的字段关系
 */
$goodsRelationTableArr=array(
	"goodsRequired"=>array(
			"oa_borrow_product"=>array(
			     "借试用产品",
			     "id" => "conProductId",
			     "goodsName" => "conProductName",
			     "unitName" => "unitName",
			),
			"oa_contract_exchange_product"=>array(
			     "换货产品",
			     "id" => "conProductId",
			     "goodsName" => "conProductName",
			     "unitName" => "unitName",
			),
			"oa_contract_product"=>array(
			     "合同产品",
			     "id" => "conProductId",
			     "goodsName" => "conProductName",
			     "unitName" => "unitName",
			),
			"oa_present_product"=>array(
			     "赠送产品 ",
			     "id" => "conProductId",
			     "goodsName" => "conProductName",
			     "unitName" => "unitName",
			),
			"oa_sale_chance_product"=>array(
			     "商机产品信息",
			     "id" => "conProductId",
			     "goodsName" => "conProductName",
			     "unitName" => "unitName",
			),
			"oa_sale_chance_goods"=>array(
			     "商机详细产品",
			     "id" => "goodsId",
			     "goodsName" => "goodsName",
			     "unitName" => "",
			)
		)
);

?>
