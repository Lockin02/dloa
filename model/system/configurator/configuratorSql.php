<?php
/**
 * @author HaoJin
 * @Date 2017年4月20日 星期四 10:40:10
 * @version 1.0
 * @description:配置端 sql配置文件
 */
$sql_arr = array (
    "select_default" => "select c.id ,c.configuratorName ,c.configuratorCode,c.groupCode,c.groupName ,c.remarks
		from oa_system_configurator c where 1=1 ",
    "list_items" => "select c.id as mainId,i.id,i.belongDeptNames,i.belongDeptIds,i.groupBelongName,".
        "i.config_item1,i.config_itemSub1,i.config_item2,i.config_itemSub2,i.config_item3,i.config_itemSub3,i.config_item4,i.config_itemSub4,".
        "i.remarks from oa_system_configurator_item i LEFT JOIN oa_system_configurator c on i.mainId = c.id where 1=1 ",
);

$condition_arr = array (
    array (
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array (
        "name" => "configuratorName",
        "sql" => " and c.configuratorName=# "
    ),
    array (
        "name" => "configuratorCode",
        "sql" => " and c.configuratorCode=# "
    ),
    array (
        "name" => "belongDeptNames",
        "sql" => " and i.belongDeptNames=# "
    ),
    array (
        "name" => "belongDeptNamesSearch",
        "sql" => " and i.belongDeptNames like concat('%',#,'%') "
    ),
    array (
        "name" => "belongDeptIds",
        "sql" => " and i.belongDeptIds=# "
    ),
    array (
        "name" => "belongDeptIdsSearch",
        "sql" => " and i.belongDeptIds like concat('%',#,'%') "
    ),
    array(
        "name" => "mainId",
        "sql" => " and c.id=# "
    ),
    array(
        "name" => "itemId",
        "sql" => " and i.id=# "
    ),
    array(
        "name" => "deptIdIn",
        "sql" => " and FIND_IN_SET(#,i.belongDeptIds)>0 "
    ),

    // 绝对匹配
    array(
        "name" => "config_item1",
        "sql" => " and i.config_item1=# "
    ),
    array(
        "name" => "config_itemSub1",
        "sql" => " and i.config_itemSub1=# "
    ),
    array(
        "name" => "config_item2",
        "sql" => " and i.config_item2=# "
    ),
    array(
        "name" => "config_itemSub2",
        "sql" => " and i.config_itemSub2=# "
    ),
    array(
        "name" => "config_item3",
        "sql" => " and i.config_item3=# "
    ),
    array(
        "name" => "config_itemSub3",
        "sql" => " and i.config_itemSub3=# "
    ),
    array(
        "name" => "config_item4",
        "sql" => " and i.config_item4=# "
    ),
    array(
        "name" => "config_itemSub4",
        "sql" => " and i.config_itemSub4=# "
    ),

    // 模糊匹配
    array(
        "name" => "config_item1Like",
        "sql" => " and i.config_item1 Like concat('%',#,'%') "
    ),
    array(
        "name" => "config_itemSub1Like",
        "sql" => " and i.config_itemSub1 Like concat('%',#,'%') "
    ),
    array(
        "name" => "config_item2Like",
        "sql" => " and i.config_item2 Like concat('%',#,'%') "
    ),
    array(
        "name" => "config_itemSub2Like",
        "sql" => " and i.config_itemSub2 Like concat('%',#,'%') "
    ),
    array(
        "name" => "config_item3Like",
        "sql" => " and i.config_item3 Like concat('%',#,'%') "
    ),
    array(
        "name" => "config_itemSub3Like",
        "sql" => " and i.config_itemSub3 Like concat('%',#,'%') "
    ),
    array(
        "name" => "config_item4Like",
        "sql" => " and i.config_item4 Like concat('%',#,'%') "
    ),
    array(
        "name" => "config_itemSub4Like",
        "sql" => " and i.config_itemSub4 Like concat('%',#,'%') "
    ),

    // IN匹配
    array(
        "name" => "config_item1IN",
        "sql" => " and i.config_item1 in(arr) "
    ),
    array(
        "name" => "config_itemSub1IN",
        "sql" => " and i.config_itemSub1 in(arr) "
    ),
    array(
        "name" => "config_item2Like",
        "sql" => " and i.config_item2 in(arr) "
    ),
    array(
        "name" => "config_itemSub2IN",
        "sql" => " and i.config_itemSub2 in(arr) "
    ),
    array(
        "name" => "config_item3IN",
        "sql" => " and i.config_item3 in(arr) "
    ),
    array(
        "name" => "config_itemSub3IN",
        "sql" => " and i.config_itemSub3 in(arr) "
    ),
    array(
        "name" => "config_item4IN",
        "sql" => " and i.config_item4 in(arr) "
    ),
    array(
        "name" => "config_itemSub4IN",
        "sql" => " and i.config_itemSub4 in(arr) "
    ),

    array (//自定义条件
        "name" => "mySearchCondition",
        "sql" => "$"
    )
);