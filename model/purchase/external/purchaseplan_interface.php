<?php
 /**
 * @description: 采购计划接口
 * (进入采购计划的业务对象有合同采购，补库采购，固定资产采购等，对于不同的采购类型需要实现此接口)
 * @date 2010-12-21 下午03:07:58
 * @author can
 * @version V1.0
 */
interface purchaseplan_interface{
	/**
	 * 下达采购计划，清单显示模板
	 *
	 * @param $itemRows   物料信息数组
	 * @param $mainRows   不同采购类型的主表信息
	 */
	function showAddList ($itemRows,$mainRows);

	/**
	 * 根据采购类型和物料所关联的主表ID，获取物料信息.
	 *
	 * @param $parentId
	 */
	function getItemsByParentId ($parentId) ;

	/**
	 *根据采购类型的ID，获取其信息
	 *
	 * @param $id
	 */
	function getInfoList ($id);

	/**
	 * 根据不同的类型采购计划，进行业务处理
	 *
	 * @param $paramArr  进行业务处理时所需要的参数数组
	 */
	function dealInfoAtPlan ($paramArr);
	
	
}
?>
