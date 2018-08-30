<?php

/**
 * @author zengzx
 * @Date 2011��5��5�� 17:03:12
 * @version 1.0
 * @description:����֪ͨ�����Խӿ�
 */
interface planStrategy {
	/**
	 * @description ����֪ͨ���б���ʾģ��
	 * @param $rows
	 */
	function showList($rows);

	/**
	 * @description ��������֪ͨ��ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemAdd($rows) ;

	/**
	 * @description �޸ķ���֪ͨ��ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemEdit($rows) ;

	/**
	 * @description �������֪ͨ��ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemChange($rows) ;

	/**
	 * @description �鿴����֪ͨ��ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemView($rows) ;

	/**
	 * �鿴���ҵ����Ϣ
	 * @param $paramArr
	 */
	function viewRelInfo($paramArr,$skey) ;

	/**
	 * ��������֪ͨ��ʱ�������ҵ��
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) ;
	/**
	 * �޸ķ���֪ͨ��ʱ�������ҵ��
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false) ;
	/**
	 * ɾ������֪ͨ��ʱ�������ҵ��
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtDel($id) ;
	/**
	 * �������֪ͨ��ʱ�������ҵ��
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtChange($paramArr = false, $relItemArr = false) ;


	/**
	 * ���ƻ�ȡԴ�����ݷ���
	 */
	 function getDocInfo( $id );

	/**
//	 * ���ݷ�������ı��ͬ����
//	 */
//	 function dealupdateOrderShipStatus_d( $id );

	/**
	 * @description �����ƻ��鿴������ת��������
	 * @param $rows
	 */
	function shwoBToOEquView($rows,$rowNum);
	/**
	 * @description �����ƻ����������ת��������
	 * @param $rows
	 */
	function shwoBToOEquChange($rows,$rowNum);
	/**
	 * @description �����ƻ��༭������ת��������
	 * @param $rows
	 */
	function shwoBToOEqu($rows,$rowNum);

}
?>
