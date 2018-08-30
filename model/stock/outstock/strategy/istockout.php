<?php

/**
 * @author huangzf
 * @Date 2011年3月12日 17:03:12
 * @version 1.0
 * @description:出库单策略接口
 */
interface istockout {

	/**
	 * @description 新增出库单时，清单显示模板
	 * @param $rows
	 */
	function showProAddRed($rows);
	/**
	 * @description 修改出库单时，物料清单显示模板
	 * @param $rows
	 */
	function showProAtEdit($rows);
	/**
	 * 查看出库单物料清单显示模板
	 * @param
	 */
	function showProAtView($rows);

	/**
	 * 打印出库单物料清单显示模板
	 * @param
	 */
	function showProAtPrint($rows);

	/**
	 *
	 * 根据蓝色出库单生成红色入库单，清单显示模板
	 * @param  $rows
	 * @param  $istrategy
	 */
	function showRelItem($rows);

	/**
	 *
	 * 出库单下推入库单时，清单显示模板
	 * @param
	 */
	function showItemAtInStock($rows);
	/**
	 * 新增出库单时处理相关业务
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false);
	/**
	 * 修改出库单时处理相关业务
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false);

	/**
	 * 反审核出库单
	 * @param $stockoutObj
	 */
	function cancelAudit($stockoutObj);
	/**
	 * 获取下推时关联单据清单信息
	 */
	function getItem($id);
}
?>
