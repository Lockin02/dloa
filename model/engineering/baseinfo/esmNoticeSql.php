<?php
/**
 * @author show
 * @Date 2014年5月13日 15:39:29
 * @version 1.0
 * @description:项目操作记录 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select * from oa_esm_notice c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "contentSearch",
   		"sql" => " and c.content like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "noticeTitleSearch",
   		"sql" => " and c.noticeTitle like CONCAT('%',#,'%') "
    ),
    array (//自定义条件
        "name" => "limitOffice",
        "sql" => " and (officeIds = '' # "
    ),
    array(
        "name" => "nullOffice",
        "sql" => " and c.officeIds = '' "
    )
);