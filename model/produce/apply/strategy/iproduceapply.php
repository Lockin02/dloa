<?php

/**
 * 
 * 生产申请策略接口
 * @author huangzf
 *
 */
interface iproduceapply {
	
	/**
	 * @description 下达生产申请,清单显示模板
	 * @param $rows
	 */
	function showItemAtApply($rows);
	
	/**
	 * 下达生产申请,生产申请基本信息 模板赋值
	 * 
	 * @param  $obj
	 */
	function assignBaseAtApply($obj,show $show);
	/**
	 * 新增生产申请时处理相关业务信息
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false);
	/**
	 * 修改生产申请时源单据业务处理
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false,$lastItemArr=FALSE);
	
	/**
	 * 获取源单信息
	 */
	function getRelDocInfo($id);
}
?>
