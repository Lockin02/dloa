<?php

/**
 * @author zengzx
 * @Date 2011年6月2日13:27:17
 * @version 1.0
 * @description:生产任务策略接口
 */
interface taskstrategy {
	/**
	 * @description 生产任务列表显示模板
	 * @param $rows
	 */
	function showList($rows);

	/**
	 * @description 新增生产任务时，清单显示模板
	 * @param $rows
	 */
	function showItemAdd($rows) ;

	/**
	 * @description 修改生产任务时，清单显示模板
	 * @param $rows
	 */
	function showItemEdit($rows) ;

	/**
	 * @description 查看生产任务时，清单显示模板
	 * @param $rows
	 */
	function showItemView($rows) ;

	/**
	 * 查看相关业务信息
	 * @param $paramArr
	 */
	function viewRelInfo($paramArr,$skey) ;

	/**
	 * 新增生产任务时处理相关业务
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) ;
	/**
	 * 修改生产任务时处理相关业务
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false) ;

	/**
	 * 下推获取源单数据方法
	 */
	 function getDocInfo( $id );


}
?>
