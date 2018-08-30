<?php

/**
 * @author zengzx
 * @Date 2011年5月5日 17:03:12
 * @version 1.0
 * @description:发货通知单策略接口
 */
interface shipStrategy {
	/**
	 * @description 发货单列表显示模板
	 * @param $rows
	 */
	function showList($rows);

	/**
	 * @description 新增发货单时，清单显示模板
	 * @param $rows
	 */
	function showItemAdd($rows) ;

	/**
	 * @description 修改发货单时，清单显示模板
	 * @param $rows
	 */
	function showItemEdit($rows) ;

	/**
	 * @description 查看发货单时，清单显示模板
	 * @param $rows
	 */
	function showItemView($rows) ;

	/**
	 * 查看相关业务信息
	 * @param $paramArr
	 */
	function viewRelInfo($paramArr =false) ;

	/**
	 * 根据发货计划新增发货单时处理相关业务
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAddByPlan($paramArr = false, $relItemArr = false) ;
	/**
	 * 新增发货单时处理相关业务
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) ;
	/**
	 * 修改发货单时处理相关业务
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false) ;

//	/**
//	 * 出库时处理相关业务操作
//	 */
//	 function dealOutStock_d( $rows );
}
?>
