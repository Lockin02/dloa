<?php

/**
 * @author zengzx
 * @Date 2011年5月5日 17:03:12
 * @version 1.0
 * @description:发货通知单策略接口
 */
interface planStrategy {
	/**
	 * @description 发货通知单列表显示模板
	 * @param $rows
	 */
	function showList($rows);

	/**
	 * @description 新增发货通知单时，清单显示模板
	 * @param $rows
	 */
	function showItemAdd($rows) ;

	/**
	 * @description 修改发货通知单时，清单显示模板
	 * @param $rows
	 */
	function showItemEdit($rows) ;

	/**
	 * @description 变更发货通知单时，清单显示模板
	 * @param $rows
	 */
	function showItemChange($rows) ;

	/**
	 * @description 查看发货通知单时，清单显示模板
	 * @param $rows
	 */
	function showItemView($rows) ;

	/**
	 * 查看相关业务信息
	 * @param $paramArr
	 */
	function viewRelInfo($paramArr,$skey) ;

	/**
	 * 新增发货通知单时处理相关业务
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) ;
	/**
	 * 修改发货通知单时处理相关业务
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false) ;
	/**
	 * 删除发货通知单时处理相关业务
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtDel($id) ;
	/**
	 * 变更发货通知单时处理相关业务
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtChange($paramArr = false, $relItemArr = false) ;


	/**
	 * 下推获取源单数据方法
	 */
	 function getDocInfo( $id );

	/**
//	 * 根据发货情况改变合同数量
//	 */
//	 function dealupdateOrderShipStatus_d( $id );

	/**
	 * @description 发货计划查看，带出转销售物料
	 * @param $rows
	 */
	function shwoBToOEquView($rows,$rowNum);
	/**
	 * @description 发货计划变更，带出转销售物料
	 * @param $rows
	 */
	function shwoBToOEquChange($rows,$rowNum);
	/**
	 * @description 发货计划编辑，带出转销售物料
	 * @param $rows
	 */
	function shwoBToOEqu($rows,$rowNum);

}
?>
