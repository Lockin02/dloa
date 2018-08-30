<?php
/**
 * @author show
 * @Date 2014年09月01日
 * @version 1.0
 * @description:资产转物料申请 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.requireCode ,c.applyDate ,c.applyId ,c.applyName ,c.applyDeptId ,c.applyDeptName ,
			c.ExaStatus ,c.ExaDT ,c.inStockStatus ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,
			c.updateTime ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName 
		from oa_asset_requireout c where 1=1 ",
	"select_detail" => "select c.requireCode ,c.applyDate ,c.applyId ,c.applyName ,c.applyDeptId ,c.applyDeptName ,
			c.ExaStatus ,c.ExaDT ,c.inStockStatus ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,
			c.updateTime ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,i.id ,i.mainId ,i.assetId ,i.assetCode ,
			i.assetName ,i.salvage ,i.productId ,i.productCode ,i.productName ,i.spec ,i.number ,i.executedNum ,i.remark as detaiRemark
		from oa_asset_requireout c inner join oa_asset_requireoutitem i on c.id = i.mainId where 1=1"
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
   array(
   		"name" => "requireCode",
   		"sql" => " and c.requireCode like CONCAT('%',#,'%') "
   	  ),
	array(
		"name" => "applyDate",
		"sql" => " and c.applyDate=# "
	),
   array(
   		"name" => "applyId",
   		"sql" => " and c.applyId=# "
   	  ),
   array(
   		"name" => "applyName",
   		"sql" => " and c.applyName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "applyDeptId",
   		"sql" => " and c.applyDeptId=# "
   	  ),
   array(
   		"name" => "applyDeptName",
   		"sql" => " and c.applyDeptName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
	array(
		"name" => "ExaStatusArr",
		"sql" => " and c.ExaStatus in(arr) "
	),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
   array(
		"name" => "inStockStatus",
		"sql" => " and c.inStockStatus=# "
	  ),
	array(
		"name" => "inStockStatusArr",
		"sql" => " and c.inStockStatus in(arr) "
	),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  ),
   array(
   		"name" => "formBelong",
   		"sql" => " and c.formBelong=# "
   	  ),
   array(
   		"name" => "formBelongName",
   		"sql" => " and c.formBelongName=# "
   	  ),
   array(
   		"name" => "businessBelong",
   		"sql" => " and c.businessBelong=# "
   	  ),
   array(
   		"name" => "businessBelongName",
   		"sql" => " and c.businessBelongName=# "
   	  ),
	//从表搜索条件
	array(
		"name" => "assetCode",
		"sql" => " and i.assetCode like CONCAT('%',#,'%') "
	),
	array(
		"name" => "assetName",
		"sql" => " and i.assetName like CONCAT('%',#,'%') "
	),
	array(
		"name" => "productCode",
		"sql" => " and i.productCode like CONCAT('%',#,'%') "
	),
	array(
		"name" => "productName",
		"sql" => " and i.productName like CONCAT('%',#,'%') "
	),
	//自定义条件
   array(
		"name" => "condition",
		"sql" => "$"
	  )
)
?>