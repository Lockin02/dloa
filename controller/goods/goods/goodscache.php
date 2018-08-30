<?php
/**
 * @author Show
 * @Date 2012年3月9日 星期五 15:50:47
 * @version 1.0
 * @description:产品配置缓存表控制层
 */
class controller_goods_goods_goodscache extends controller_base_action {

	function __construct() {
		$this->objName = "goodscache";
		$this->objPath = "goods_goods";
		parent::__construct ();
	}

	/**
	 * 保存缓存并返回id
	 */
	function c_saveCache(){
		$object = $_POST['dataArr'];

		//如果传入id，调用保存方法，否则调用新增方法
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
	 * 获取产品配置数据
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
	 * 获取差频配置变更数据
	 */
	function c_getCacheChange(){
		//当前记录
		$id = $_POST['id'];
		$obj = $this->service->changeToProduct_d($id);

		//变更前记录
		$beforeId = $_POST['beforeId'];
		$beforeObj = $this->service->changeToProduct_d($beforeId);

		$rsStr = $this->service->getCacheChange_d($obj,$id,$beforeObj);

		echo util_jsonUtil::iconvGB2UTF($rsStr);
	}

	/**
	 * 获取产品配置数据
	 */
	function c_getCacheInRow(){
		$id = $_POST['id'];

		$obj = $this->service->changeToProduct_d($id);

		$rsStr = $this->service->showCache_d($obj,$id);

		echo util_jsonUtil::iconvGB2UTF($rsStr);
	}

	/**
	 * 更新
	 */
	function c_listUpdate(){
		$this->display('listupdate');
	}

	/**
	 * 更新缓存
	 */
	function c_updateCache(){
		//更新缓存
		$rs = $this->service->updateCache_d();
		if($rs){
			echo 1;
		}else{
			echo 0;
		}
	}

//保存海外推送的产品配置HTML
	function c_saveHWProHtml($objArr){
		$result = $this->service->saveHWProHtml_d($objArr);
		return $result;
	}
}
?>