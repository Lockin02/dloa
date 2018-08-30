<?php
/**
 * @author Show
 * @Date 2011��11��25�� ������ 13:59:48
 * @version 1.0
 * @description:��ԴĿ¼(oa_esm_baseinfo_resource) sql�����ļ� status
0 ����
1.����
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