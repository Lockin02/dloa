<?php
 /**
 * @description: �ɹ��ƻ��ӿ�
 * (����ɹ��ƻ���ҵ������к�ͬ�ɹ�������ɹ����̶��ʲ��ɹ��ȣ����ڲ�ͬ�Ĳɹ�������Ҫʵ�ִ˽ӿ�)
 * @date 2010-12-21 ����03:07:58
 * @author can
 * @version V1.0
 */
interface purchaseplan_interface{
	/**
	 * �´�ɹ��ƻ����嵥��ʾģ��
	 *
	 * @param $itemRows   ������Ϣ����
	 * @param $mainRows   ��ͬ�ɹ����͵�������Ϣ
	 */
	function showAddList ($itemRows,$mainRows);

	/**
	 * ���ݲɹ����ͺ�����������������ID����ȡ������Ϣ.
	 *
	 * @param $parentId
	 */
	function getItemsByParentId ($parentId) ;

	/**
	 *���ݲɹ����͵�ID����ȡ����Ϣ
	 *
	 * @param $id
	 */
	function getInfoList ($id);

	/**
	 * ���ݲ�ͬ�����Ͳɹ��ƻ�������ҵ����
	 *
	 * @param $paramArr  ����ҵ����ʱ����Ҫ�Ĳ�������
	 */
	function dealInfoAtPlan ($paramArr);
	
	
}
?>
