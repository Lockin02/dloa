<?php
/**
 * @author Show
 * @Date 2012��3��9�� ������ 15:50:47
 * @version 1.0
 * @description:��Ʒ���û������Ʋ�
 */
class controller_goods_goods_goodscache extends controller_base_action {

	function __construct() {
		$this->objName = "goodscache";
		$this->objPath = "goods_goods";
		parent::__construct ();
	}

	/**
	 * ���滺�沢����id
	 */
	function c_saveCache(){
		$object = $_POST['dataArr'];

		//�������id�����ñ��淽�������������������
		if(isset($object['id'])){
			$rs = $this->service->saveRecord_d($object);
		}else{
			$rs = $this->service->addRecord_d($object);
		}
		if($rs){
			echo $rs;
		}else{
			echo 0;
		}
	}

	/**
	 * ��ȡ��Ʒ��������
	 */
	function c_getCacheConfig(){
		$id = $_POST['id'];

		$object = $this->service->find(array('id' => $id),null,'goodsCache');
		if($object['goodsCache']){
			echo util_jsonUtil::iconvGB2UTF($object['goodsCache']);
		}else{
			$obj = $this->service->changeToProduct_d($id);
			$rsStr = $this->service->getPropertiesInfo_f($obj,$id);
			echo util_jsonUtil::iconvGB2UTF($rsStr);
		}
	}

	/**
	 * ��ȡ��Ƶ���ñ������
	 */
	function c_getCacheChange(){
		//��ǰ��¼
		$id = $_POST['id'];
		$obj = $this->service->changeToProduct_d($id);

		//���ǰ��¼
		$beforeId = $_POST['beforeId'];
		$beforeObj = $this->service->changeToProduct_d($beforeId);

		$rsStr = $this->service->getCacheChange_d($obj,$id,$beforeObj);

		echo util_jsonUtil::iconvGB2UTF($rsStr);
	}

	/**
	 * ��ȡ��Ʒ��������
	 */
	function c_getCacheInRow(){
		$id = $_POST['id'];

		$obj = $this->service->changeToProduct_d($id);

		$rsStr = $this->service->showCache_d($obj,$id);

		echo util_jsonUtil::iconvGB2UTF($rsStr);
	}

	/**
	 * ����
	 */
	function c_listUpdate(){
		$this->display('listupdate');
	}

	/**
	 * ���»���
	 */
	function c_updateCache(){
		//���»���
		$rs = $this->service->updateCache_d();
		if($rs){
			echo 1;
		}else{
			echo 0;
		}
	}

//���溣�����͵Ĳ�Ʒ����HTML
	function c_saveHWProHtml($objArr){
		$result = $this->service->saveHWProHtml_d($objArr);
		return $result;
	}
}
?>