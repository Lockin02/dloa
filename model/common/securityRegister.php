<?php
$rule="2,10";//并截取从第二位开始，长度为10位(建议10位以上)
/**
 * 业务模块校验规则注册数组
 */
$securityRule = array (
		/**
		 * 客户模块，一般取与action中的objName相同,对id及名称两个字段（可以任意多个）进行md5编码(注意设置以,隔开)
		 * 在获取的时候通过row['skey_'],row['skey_1']....获取对应加密key
		 */
		"customer" => array ("id,Name" )  ,

		//采购开始
		"purchasecontract"=>array("id"),
		"contractchange"=>array("id"),
		"basic"=>array("id"),
		"inquirysheet"=>array("id"),
		"arrival" => array("id"),
		"delivered" => array("id"),
		//采购结束

		//合同开始
		"order" => array("id"),
		"serviceContract" => array("id"),
		"rentalcontract" => array("id"),
		"rdproject" => array("id"),
		"share" => array("orderId,id"),
		"other" => array("id"),
		"outsourcing" => array("id"),
		//合同结束

        //线索开始
        "clues" => array("id"),
        "track" => array("id"),
        //线索结束
        //商机开始
        "chance" => array("id"),
        "borrow" => array("id"),
        //商机结束
        //销售退货
        "return" => array("id"),
        //销售退货用结束
        //赠送
        "present" => array("id"),

        //财务应收账款
        'invoice' => array("id,applyId"),
        'invoiceapply' => array("id,applyId"),
        'receviable' => array("objId"),
        'income' => array("id"),
        'payables' => array("id,applyId"),
        'payablesapply' => array("id,applyId"),
        'payable' => array("objId"),
        'invpurchase' => array("id"),
        'baseinfo' => array("hookMainId")//勾稽关系表
        //财务结束

        //服务管理
		,'tstask' => array("id")
		,'accessorder'=>array("id")
		,'repairapply'=>array("id")
		,'applyitem'=>array("id")
		,'repairquote'=>array("id")
		,'repaircheck'=>array("id")
		,'reduceapply'=>array("id")
		,'reduceitem'=>array("id")
		,'changeapply'=>array("id")
		,'reduceitem'=>array("id")
        //服务管理


        //发货开始
        ,'outplan'=>array("id",3 , 16)
        ,'ship'=>array("id",2 , 18)
        ,'lock'=>array("id",4 , 18)
        ,'external'=>array("id",3,17)
        ,'protask'=>array("id",2,17)
        ,'present'=>array("id",3,19)
        //发货结束


		//仓存管理开始
		,"stockin"=>array("id")
		,"stockout"=>array("id")
		,"allocation"=>array("id")
		,"fillup"=>array("id")
		,"checkinfo"=>array("id")
		//仓存管理结束

		//供应商管理开始
		,"temporary"=>array("id")
		,"flibrary"=>array("id")
		,"assessment"=>array("id")
		,"supasses"=>array("id")
		//供应商管理结束

		//人力资源管理
		,"personnel"=>array("id")
);
?>