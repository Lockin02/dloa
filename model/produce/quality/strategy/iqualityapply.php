<?php
/**
 * �ʼ����뵥���Խӿ�
 */
interface iqualityapply{
	/**
	 * �����ʼ�����ʱ�������ҵ����Ϣ
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false);
	/**
	 * �޸��ʼ�����ʱԴ����ҵ����
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false,$lastItemArr=FALSE);
	/**
	 * ��˵���ʱ����Դ����ҵ����
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtConfirm($paramArr = false);
	/**
	 * ��ȡԴ����Ϣ
	 */
	function getRelDocInfo($id);
}