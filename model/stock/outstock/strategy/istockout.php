<?php

/**
 * @author huangzf
 * @Date 2011��3��12�� 17:03:12
 * @version 1.0
 * @description:���ⵥ���Խӿ�
 */
interface istockout {

	/**
	 * @description �������ⵥʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showProAddRed($rows);
	/**
	 * @description �޸ĳ��ⵥʱ�������嵥��ʾģ��
	 * @param $rows
	 */
	function showProAtEdit($rows);
	/**
	 * �鿴���ⵥ�����嵥��ʾģ��
	 * @param
	 */
	function showProAtView($rows);

	/**
	 * ��ӡ���ⵥ�����嵥��ʾģ��
	 * @param
	 */
	function showProAtPrint($rows);

	/**
	 *
	 * ������ɫ���ⵥ���ɺ�ɫ��ⵥ���嵥��ʾģ��
	 * @param  $rows
	 * @param  $istrategy
	 */
	function showRelItem($rows);

	/**
	 *
	 * ���ⵥ������ⵥʱ���嵥��ʾģ��
	 * @param
	 */
	function showItemAtInStock($rows);
	/**
	 * �������ⵥʱ�������ҵ��
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false);
	/**
	 * �޸ĳ��ⵥʱ�������ҵ��
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false);

	/**
	 * ����˳��ⵥ
	 * @param $stockoutObj
	 */
	function cancelAudit($stockoutObj);
	/**
	 * ��ȡ����ʱ���������嵥��Ϣ
	 */
	function getItem($id);
}
?>
