<?php
/*�ɹ��ƻ��ĸ��࣬����Ϊ���ֲɹ��ƻ������ṩ���õķ���
 * Created on 2011-3-22
 *Created by can
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 abstract class planBasic{
	/**
	 * �´�ɹ��ƻ����嵥��ʾģ��
	 *
	 * @param $itemRows   ������Ϣ����
	 * @param $mainRows   ��ͬ�ɹ����͵�������Ϣ
	 */
	abstract function showAddList ($itemRows,$mainRows);
	/**
	 * ���ݲɹ����ͺ�����������������ID����ȡ������Ϣ.
	 *
	 * @param $parentId
	 */
	abstract function getItemsByParentId ($parentId);

	/**
	 *���ݲɹ����͵�ID����ȡ����Ϣ
	 *
	 * @param $id
	 */
	abstract function getInfoList ($id);

	/**
	 * ���ݲ�ͬ�����Ͳɹ��ƻ�������ҵ����
	 *
	 * @param $paramArr  ����ҵ����ʱ����Ҫ�Ĳ�������
	 */
	function dealInfoAtPlan ($paramArr){}

	/**
	 * �´�ɹ��ƻ������ת��Ĭ��Ϊͳһ�´�ɹ��ƻ�ҳ�棬��ҵ�ɸ�����Ҫ��ת����ͬ��ҳ��
	 *
	 * @param $id �жϵı�־
	 * @return return_type
	 */
	function toShowPage ($id,$type=null) {
		if($id){
			msgGo('�´�ɹ�',"?model=purchase_external_external&action=toAdd");
		}else{
			msgGo('������Ϣ��������û�����ϻ�����Ϊ0���´�ʧ��',"?model=purchase_external_external&action=toAdd");
		}
	}

 }
?>
