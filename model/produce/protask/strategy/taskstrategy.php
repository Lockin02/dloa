<?php

/**
 * @author zengzx
 * @Date 2011��6��2��13:27:17
 * @version 1.0
 * @description:����������Խӿ�
 */
interface taskstrategy {
	/**
	 * @description ���������б���ʾģ��
	 * @param $rows
	 */
	function showList($rows);

	/**
	 * @description ������������ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemAdd($rows) ;

	/**
	 * @description �޸���������ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemEdit($rows) ;

	/**
	 * @description �鿴��������ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemView($rows) ;

	/**
	 * �鿴���ҵ����Ϣ
	 * @param $paramArr
	 */
	function viewRelInfo($paramArr,$skey) ;

	/**
	 * ������������ʱ�������ҵ��
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) ;
	/**
	 * �޸���������ʱ�������ҵ��
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false) ;

	/**
	 * ���ƻ�ȡԴ�����ݷ���
	 */
	 function getDocInfo( $id );


}
?>
