<?php
/**
 * @author Administrator
 * @Date 2013��11��11�� ����һ 22:22:42
 * @version 1.0
 * @description:��Ʒ������ϸ�� sql�����ļ�
 */
$sql_arr = array (
         "select_default"=>"select *  from oa_stockup_application_matter c where 1=1 ",
         "jsonEdit"=>"select *  from oa_stockup_application_matter c where 1=1 ",
         "pageItem"=>"select *  from oa_stockup_application_matter c where 1=1 ",
         //�������ܲ�ѯ
         "select_pageAll"=>"select a.id,b.id as pid,b.listNo,b.createId,b.createName,b.createTime,b.ExaDT,a.stockNum,a.needsNum,a.stockupNum,a.expectAmount,a.productId,a.productName
							from oa_stockup_application_matter a left join oa_stockup_application b on a.appId=b.id where 1=1"
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
    array(
   		"name" => "bid",
   		"sql" => " and b.id IS NOT NULL "
        ),
   array(
   		"name" => "appId",
   		"sql" => " and c.appId=# "
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "listNo",
   		"sql" => " and c.listNo=# "
   	  ),
   array(
   		"name" => "listNoS",
   		"sql" => " and b.listNo like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "productNum",
   		"sql" => " and c.productNum=# "
   	  ),
   array(
   		"name" => "productConfig",
   		"sql" => " and c.productConfig=# "
   	  ),
   array(
   		"name" => "exDeliveryDate",
   		"sql" => " and c.exDeliveryDate=# "
   	  ),
   array(
   		"name" => "appDeptId",
   		"sql" => " and c.appDeptId=# "
   	  ),
   array(
   		"name" => "appDeptName",
   		"sql" => " and c.appDeptName=# "
   	  ),
   array(
   		"name" => "appUserId",
   		"sql" => " and c.appUserId=# "
   	  ),
   array(
   		"name" => "appUserName",
   		"sql" => " and c.appUserName=# "
   	  ),
   array(
   		"name" => "productCodeS",
   		"sql" => " and a.productCode like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "productNameS",
   		"sql" => " and a.productName like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "allS",
   		"sql" => " and (b.listNo like concat('%',#,'%') or a.productName like concat('%',#,'%') or a.productName like concat('%',#,'%')) "
   	  )
)
?>