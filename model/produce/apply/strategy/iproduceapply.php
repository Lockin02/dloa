<?php

/**
 * 
 * ����������Խӿ�
 * @author huangzf
 *
 */
interface iproduceapply {
	
	/**
	 * @description �´���������,�嵥��ʾģ��
	 * @param $rows
	 */
	function showItemAtApply($rows);
	
	/**
	 * �´���������,�������������Ϣ ģ�帳ֵ
	 * 
	 * @param  $obj
	 */
	function assignBaseAtApply($obj,show $show);
	/**
	 * ������������ʱ�������ҵ����Ϣ
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false);
	/**
	 * �޸���������ʱԴ����ҵ����
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false,$lastItemArr=FALSE);
	
	/**
	 * ��ȡԴ����Ϣ
	 */
	function getRelDocInfo($id);
}
?>
