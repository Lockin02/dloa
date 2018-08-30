<?php
/**
 * 质检申请单策略接口
 */
interface iqualityapply{
	/**
	 * 新增质检申请时处理相关业务信息
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false);
	/**
	 * 修改质检申请时源单据业务处理
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false,$lastItemArr=FALSE);
	/**
	 * 审核单据时处理源单据业务处理
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtConfirm($paramArr = false);
	/**
	 * 获取源单信息
	 */
	function getRelDocInfo($id);
}