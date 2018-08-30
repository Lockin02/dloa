<?php
/*采购计划的父类，用于为各种采购计划类型提供公用的方法
 * Created on 2011-3-22
 *Created by can
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 abstract class planBasic{
	/**
	 * 下达采购计划，清单显示模板
	 *
	 * @param $itemRows   物料信息数组
	 * @param $mainRows   不同采购类型的主表信息
	 */
	abstract function showAddList ($itemRows,$mainRows);
	/**
	 * 根据采购类型和物料所关联的主表ID，获取物料信息.
	 *
	 * @param $parentId
	 */
	abstract function getItemsByParentId ($parentId);

	/**
	 *根据采购类型的ID，获取其信息
	 *
	 * @param $id
	 */
	abstract function getInfoList ($id);

	/**
	 * 根据不同的类型采购计划，进行业务处理
	 *
	 * @param $paramArr  进行业务处理时所需要的参数数组
	 */
	function dealInfoAtPlan ($paramArr){}

	/**
	 * 下达采购计划后的跳转。默认为统一下达采购计划页面，各业可根据需要跳转到不同的页面
	 *
	 * @param $id 判断的标志
	 * @return return_type
	 */
	function toShowPage ($id,$type=null) {
		if($id){
			msgGo('下达成功',"?model=purchase_external_external&action=toAdd");
		}else{
			msgGo('物料信息不完整，没有物料或数量为0，下达失败',"?model=purchase_external_external&action=toAdd");
		}
	}

 }
?>
