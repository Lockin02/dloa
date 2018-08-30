<?php
/**
 * @author Administrator
 * @Date 2012年3月14日 9:36:12
 * @version 1.0
 * @description:合同 发货清单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.proId,c.id ,c.productName ,c.productId,c.isBorrowToorder,c.toBorrowId,c.toBorrowequId ," .
         		"c.productCode ,c.productModel ,c.productType ,c.projArraDate ," .
         		"c.contractId ,c.contractTypeName ,c.contractType ,c.contractCode ," .
         		"c.contractName ,c.conProductId ,c.conProductName ,c.version ,c.number ," .
         		"c.remark ,c.price ,c.unitName ,c.money ,c.warrantyPeriod ,c.warrantyPeriod as warranty,c.deploy ,c.license ," .
         		"c.backNum ,c.isSell ,c.issuedShipNum ,c.executedNum ,c.onWayNum ," .
         		"c.purchasedNum ,c.issuedPurNum ,c.issuedProNum  ,c.encryptionNum ," .
         		"c.isTemp ,c.originalId ,c.isDel ,c.isCon ,c.isConfig ," .
         		"c.isNeedDelivery ,c.outStockDate ,c.isDefault,c.arrivalPeriod,c.linkId,c.changeTips,c.isAddFromConfig," .
         		"(c.executedNum-c.backNum) as actNum ,c.onlyProductId,c.tempId,c.isMeetProduction,c.meetProductionRemark,c.proportion,c.priceTax,c.moneyTax" .
         		" from oa_contract_equ c " .
         		" where 1=1 ",
         "select_closemat"=>"select c.id , c.productCode as productNo , c.productName,c.productId,c.number as contractNum ," .
         		"c.backNum ,c.isSell ,c.issuedShipNum ,c.executedNum ,c.onWayNum ,c.purchasedNum ,c.issuedPurNum ,c.issuedProNum  ,c.encryptionNum ," .
         		"c.isTemp ,c.originalId ,c.isDel ,c.isCon ,c.isConfig ,c.isNeedDelivery ,c.outStockDate ,c.isDefault,c.arrivalPeriod,c.linkId,c.changeTips,(c.executedNum-c.backNum) as actNum," .
         		"(c.number-c.executedNum+c.backNum) as contNum ,c.isDel as closeopenVal ,c.isDel as isClose" .
         		" from oa_contract_equ c where 1=1 and (number-executedNum+backNum)=number "
);


$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "proId",
   		"sql" => " and c.proId=# "
        ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName=# "
   	  ),
    array(
        "name" => "productNameSearch",
        "sql" => " and c.productName like concat('%',#,'%') "
    ),
   array(
   		"name" => "parentEquId",
   		"sql" => " and c.parentEquId=# "
   	  ),
   array(
         "name" => "productId",
         "sql" => " and c.productId=# "
        ),
   array(
         "name" => "noProductIds",
         "sql" => " and c.productId not in(arr) "
        ),
   array(
   		"name" => "productCode",
   		"sql" => " and c.productCode=# "
   	  ),
    array(
        "name" => "productCodeSearch",
        "sql" => " and c.productCode like concat('%',#,'%') "
    ),
   array(
   		"name" => "productModel",
   		"sql" => " and c.productModel=# "
   	  ),
   array(
   		"name" => "productType",
   		"sql" => " and c.productType=# "
   	  ),
   array(
   		"name" => "projArraDate",
   		"sql" => " and c.projArraDate=# "
   	  ),
   array(
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
   	  ),
   array(
   		"name" => "contractTypeName",
   		"sql" => " and c.contractTypeName=# "
   	  ),
   array(
   		"name" => "contractType",
   		"sql" => " and c.contractType=# "
   	  ),
   array(
   		"name" => "contractCode",
   		"sql" => " and c.contractCode=# "
   	  ),
   array(
   		"name" => "contractName",
   		"sql" => " and c.contractName=# "
   	  ),
   array(
   		"name" => "noContProductId",
   		"sql" => " and (c.conProductId is null or c.conProductId=0) "
   	  ),
   array(
   		"name" => "conProductId",
   		"sql" => " and c.conProductId=# "
   	  ),
   array(
   		"name" => "conProductName",
   		"sql" => " and c.conProductName=# "
   	  ),
   array(
   		"name" => "version",
   		"sql" => " and c.version=# "
   	  ),
   array(
   		"name" => "number",
   		"sql" => " and c.number=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price=# "
   	  ),
   array(
   		"name" => "unitName",
   		"sql" => " and c.unitName=# "
   	  ),
   array(
   		"name" => "money",
   		"sql" => " and c.money=# "
   	  ),
   array(
   		"name" => "warrantyPeriod",
   		"sql" => " and c.warrantyPeriod=# "
   	  ),
   array(
   		"name" => "deploy",
   		"sql" => " and c.deploy=# "
   	  ),
   array(
   		"name" => "license",
   		"sql" => " and c.license=# "
   	  ),
   array(
   		"name" => "backNum",
   		"sql" => " and c.backNum=# "
   	  ),
   array(
   		"name" => "isSell",
   		"sql" => " and c.isSell=# "
   	  ),
   array(
   		"name" => "issuedShipNum",
   		"sql" => " and c.issuedShipNum=# "
   	  ),
   array(
   		"name" => "executedNum",
   		"sql" => " and c.executedNum=# "
   	  ),
   array(
   		"name" => "onWayNum",
   		"sql" => " and c.onWayNum=# "
   	  ),
   array(
   		"name" => "purchasedNum",
   		"sql" => " and c.purchasedNum=# "
   	  ),
   array(
   		"name" => "issuedPurNum",
   		"sql" => " and c.issuedPurNum=# "
   	  ),
   array(
   		"name" => "issuedProNum",
   		"sql" => " and c.issuedProNum=# "
   	  ),
   array(
   		"name" => "uniqueCode",
   		"sql" => " and c.uniqueCode=# "
   	  ),
   array(
   		"name" => "productLine",
   		"sql" => " and c.productLine=# "
   	  ),
   array(
   		"name" => "productLineName",
   		"sql" => " and c.productLineName=# "
   	  ),
   array(
   		"name" => "isTemp",
   		"sql" => " and c.isTemp=# "
   	  ),
    array(
   		"name" => "temp",
   		"sql" => " and c.isTemp!=0 "
   	  ),
   array(
   		"name" => "originalId",
   		"sql" => " and c.originalId=# "
   	  ),
   array(
   		"name" => "notDel",
   		"sql" => " and (c.isDel=0 or c.isDel='') "
   	  ),
   array(
   		"name" => "isDel",
   		"sql" => " and (c.isDel=# or c.isDel = '') "
   	  ),
   array(
   		"name" => "isCon",
   		"sql" => " and c.isCon=# "
   	  ),
   array(
   		"name" => "isConfig",
   		"sql" => " and c.isConfig=# "
   	  ),
   array(
   		"name" => "isNeedDelivery",
   		"sql" => " and c.isNeedDelivery=# "
   	  ),
   array(
   		"name" => "outStockDate",
   		"sql" => " and c.outStockDate=# "
   	  ),
   array(
   		"name" => "isDefault",
   		"sql" => " and c.isDefault=# "
   	  ),
   array(
   		"name" => "linkId",
   		"sql" => " and c.linkId=# "
   	  ),
   array(
        "name" => "isBorrowToorder",
        "sql" => " and c.isBorrowToorder=#"
      ),
   array(
        "name" => "isDel",
        "sql" => " and c.isDel=#"
      ),
   array(
        "name" => "equIds",
        "sql" => " and c.id in(arr)"
      ),
   array(
        "name" => "maxNum",
        "sql" => " and (c.executedNum-c.backNum)>#"
      )
)
?>