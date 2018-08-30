<?php
/**
 * @author Show
 * @Date 2011年11月25日 星期五 13:59:48
 * @version 1.0
 * @description:资源目录(oa_esm_baseinfo_resource) sql配置文件 status
0 启用
1.禁用
 */
$sql_arr = array (
    "select_default" => "select c.id ,c.longWeekNo,c.weekNo,c.beginDate,c.beginTime,c.endDate,c.endTime  from oa_esm_baseinfo_week c where 1=1 "
);

$condition_arr = array (
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "longWeekNo",
        "sql" => " and c.longWeekNo=# "
    ),
    array(
        "name" => "weekNo",
        "sql" => " and c.weekNo=# "
    ),
    array(
        "name" => "longWeekNoSearch",
        "sql" => " and c.longWeekNo like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "weekNoSearch",
        "sql" => " and c.weekNo like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "yearsSearch",
        "sql" => " and date_format(c.beginDate,'%Y') like CONCAT('%',#,'%') "
    )
);