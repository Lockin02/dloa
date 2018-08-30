<?php

/*
 *  huangzf 入库申请单策略接口
 */
interface istockin{

	/**
	 * @description 下推红色入库时，清单显示模板
	 * @param $rows
	 */
	function showItemAddRed($rows);

	/**
	 * @description 修改入库申请时，清单显示模板
	 * @param $rows
	 */
	function showItemEdit($rows) ;

	/**
	 * @description 查看入库申请时，清单显示模板
	 * @param $rows
	 */
	function showItemView($rows) ;

	/**
	 * @description 打印入库申请时，清单显示模板
	 * @param $rows
	 */
	function showItemPrint($rows) ;

	/**
	 *
	 * 根据蓝色入库单生成红色入库单，清单显示模板
	 * @param  $rows
	 */
	function showRelItem($rows);

	/**
	 * 根据入库单下推出库单时，清单显示模板
	 * @param  $rows
	 * @param  $istrategy
	 */
	function showItemAtOutStock($rows);
	/**
	 * 新增入库单时处理相关业务信息
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) ;
	/**
	 * 修改入库申请时源单据业务处理
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false) ;

	/**
	 * 反审核入库单
	 * @param $stockinObj
	 */
	function cancelAudit($stockinObj);

	/**
	 * 根据基本信息ID获取物料清单信息
	 */
	function getItem($id);
}
?>
