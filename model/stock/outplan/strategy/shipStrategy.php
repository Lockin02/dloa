<?php

/**
 * @author zengzx
 * @Date 2011��5��5�� 17:03:12
 * @version 1.0
 * @description:����֪ͨ�����Խӿ�
 */
interface shipStrategy {
	/**
	 * @description �������б���ʾģ��
	 * @param $rows
	 */
	function showList($rows);

	/**
	 * @description ����������ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemAdd($rows) ;

	/**
	 * @description �޸ķ�����ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemEdit($rows) ;

	/**
	 * @description �鿴������ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemView($rows) ;

	/**
	 * �鿴���ҵ����Ϣ
	 * @param $paramArr
	 */
	function viewRelInfo($paramArr =false) ;

	/**
	 * ���ݷ����ƻ�����������ʱ�������ҵ��
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAddByPlan($paramArr = false, $relItemArr = false) ;
	/**
	 * ����������ʱ�������ҵ��
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) ;
	/**
	 * �޸ķ�����ʱ�������ҵ��
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false) ;

//	/**
//	 * ����ʱ�������ҵ�����
//	 */
//	 function dealOutStock_d( $rows );
}
?>
