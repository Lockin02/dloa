<?php

/*
 *  huangzf ������뵥���Խӿ�
 */
interface istockin{

	/**
	 * @description ���ƺ�ɫ���ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemAddRed($rows);

	/**
	 * @description �޸��������ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemEdit($rows) ;

	/**
	 * @description �鿴�������ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemView($rows) ;

	/**
	 * @description ��ӡ�������ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemPrint($rows) ;

	/**
	 *
	 * ������ɫ��ⵥ���ɺ�ɫ��ⵥ���嵥��ʾģ��
	 * @param  $rows
	 */
	function showRelItem($rows);

	/**
	 * ������ⵥ���Ƴ��ⵥʱ���嵥��ʾģ��
	 * @param  $rows
	 * @param  $istrategy
	 */
	function showItemAtOutStock($rows);
	/**
	 * ������ⵥʱ�������ҵ����Ϣ
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) ;
	/**
	 * �޸��������ʱԴ����ҵ����
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false) ;

	/**
	 * �������ⵥ
	 * @param $stockinObj
	 */
	function cancelAudit($stockinObj);

	/**
	 * ���ݻ�����ϢID��ȡ�����嵥��Ϣ
	 */
	function getItem($id);
}
?>
