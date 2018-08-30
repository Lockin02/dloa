<?php
/*
 * Created on 2011-10-29
 * 源单查询功能，即金蝶所谓的上查下查功能
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_common_search_searchSource extends controller_base_action {

	function __construct() {
		$this->objName = "searchSource";
		$this->objPath = "common_search";
		parent::__construct ();
	}

	/**
	 * 数组处理
	 */
	function arrDeal($obj){
		//查询已解析对象
		$obj['orgObj'] = isset($obj['orgObj']) ? $obj['orgObj'] : '';


		//上查id串
		$obj['ids'] = isset($obj['ids']) ? $obj['ids'] : '';



		//下查id串
		$obj['objId'] = isset($obj['objId']) ? $obj['objId'] : '';
		//被下查对象对应字段码
		$obj['sourceType'] = isset($obj['sourceType']) ? $obj['sourceType'] : '';


		return $obj;
	}

	/**
	 * 上查验证方法,验证当前单据是否有源单类型和ID
	 */
	function c_checkUp(){
		$obj = $_POST;
		$newClass = $this->service->sourceArr[$_POST['objType']]['thisClass'];
		$newAction = $this->service->sourceArr[$_POST['objType']]['thisUpAction'];
		$classDao = new $newClass();
		$rows = $classDao->$newAction($_POST['objId']);
		if(!empty($rows)){
			echo json_encode($rows);
		}else{
			echo '';
		}
		exit();

	}

	/**
	 *  上查显示方法
	 */
	function c_upList(){
		$obj = $this->arrDeal($_GET);
		$this->assignFunc($obj);
		//上查对象关键字
		$thisUpObj = $this->service->sourceArr[$obj['objType']]['up'][$obj['orgObj']];
		if(empty($thisUpObj)){
			msgRf('未配置的对象，请先进行配置');
			exit();
		}
		$this->assign('sourceType',$thisUpObj);

		$this->show->display($this->objPath . '_' .$this->service->sourceArr[$thisUpObj]['pageObj'].'-sourcelist');
	}

	/**
	 * 下查验证方法
	 * 检测对象下推业务中是否存在由该对象下推生成的业务
	 */
	function c_checkDown(){
		$obj = $_POST;
		$rtArr = array();
		//获取下查源数组
		$sourceArr = !empty($this->service->sourceArr[$_POST['objType']]['down']) ? $this->service->sourceArr[$_POST['objType']]['down']:'';
		if(!$sourceArr){
			echo '';
			exit();
		}
		//返回所有存在数据的源单类型
		foreach($sourceArr as $key => $val){
			$newClass = $this->service->sourceArr[$key]['thisClass'];
			$newAction = $this->service->sourceArr[$key]['thisDownAction'];
			$classDao = new $newClass();
			if($classDao->$newAction($_POST['objId'],$val)){
				array_push($rtArr,$key);
			}
		}
		if(!empty($rtArr)){
			echo implode($rtArr);
		}else{
			echo '';
		}
		exit();
	}


	/**
	 * 下查显示方法
	 */
	function c_downList(){
		$obj = $this->arrDeal($_GET);
		$this->assignFunc($obj);
		$objsArr = explode(',',$obj['orgObj']);
		foreach($objsArr as $key => $val){
			if($key == 0){//显示第一个存在的源单列表
				$this->assign('sourceType',$this->service->sourceArr[$obj['objType']]['down'][$val]);
				$this->show->display($this->objPath . '_' .$this->service->sourceArr[$val]['pageObj'].'-sourcelist');
			}
		}
	}
}
?>
