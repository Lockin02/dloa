<?php
/*
 * Created on 2011-3-13
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

interface iinvoiceapply{
	/**
	 * ������Ʊ����ʱ��ȡҵ����Ϣ
	 */
	function getObjInfo_d($obj);

	/**
	 * ������Ʊ����ʱ��Ⱦ��Ʊ��ϸ��Ϣ
	 */
	function initAdd_d($obj);

	/**
	 * �༭��Ʊ����ʱ��Ⱦ��Ʊ��ϸ��Ϣ
	 */
	function initEdit_d($obj);

	/**
	 * �鿴��Ʊ����ʱ��Ⱦ��Ʊ��ϸ��Ϣ
	 */
	function initView_d($obj);

	/**
	 * ��ȡ��Ʊ��ϸ����
	 */
	function getDetail($obj);
}
?>
