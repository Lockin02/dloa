<?php
/*
 * Created on 2011-3-13
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

interface iinvoiceapply{
	/**
	 * 新增开票申请时获取业务信息
	 */
	function getObjInfo_d($obj);

	/**
	 * 新增开票申请时渲染开票详细信息
	 */
	function initAdd_d($obj);

	/**
	 * 编辑开票申请时渲染开票详细信息
	 */
	function initEdit_d($obj);

	/**
	 * 查看开票申请时渲染开票详细信息
	 */
	function initView_d($obj);

	/**
	 * 获取开票详细内容
	 */
	function getDetail($obj);
}
?>
