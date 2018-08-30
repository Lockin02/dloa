<?php

/*
 *	市基本信息Controller
 */

class controller_system_procity_city extends controller_base_action {

	function __construct() {
		$this->objName = "city";
		$this->objPath = "system_procity";
		parent :: __construct();
	}

	/**
	 * 跳转到	省市页面
	 */
	function c_toTreeList() {

		$this->display("list");
	}

	/**
	 * 添加
	 */
	function c_toAdd() {
		$this->view("add");
	}
	/**
	* 重写修改
	*/
		function c_init() {
			$provinceDao=new model_system_procity_province();
			$obj=$this->service->get_d($_GET['id']);
			$provinceId=$obj['provinceId'];
			$provinceObj=$provinceDao->get_d($provinceId);
			$provinceName = $provinceObj['provinceName'];
			$this->assign ( "provinceName", $provinceName );
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
		 	 }
		 	  $this->view ('edit' );

		}

     /**
	 * 重写pageJson方法
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_POST ); //设置前台获取的参数信息
//		$_GET['provinceId']=="";
		$service->asc = false;
		$rows = $service->page_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc = false;
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 根据parentId获取数省市信息
	 */
	 function c_listByParent() {
	 	$searchArr = array ("parentId" => $_GET ['parentId']);
	 	$service = $this->service;
	 	$service->searchArr = $searchArr;
	 	$service->sort = 'orderNum';
	 	$rows = $service->list_d ();
        //把叶子节点的值0转成false，1转成true
        function toBoolean ($row) {
        	 $row ['leaf'] = $row ['leaf'] == 0 ? false :ture;
        	 return $row;
        }
        echo util_jsonUtil::encode ( array_map ("toBoolean",$rows));

	 }

    /**
     * 根据parentCode获取省市信息
     */
     function c_listByParentCode () {
     	$searchArr = array ("parentCode" => $_GET ['parentCode']);
     	$service = $this->service;
     	$service->searchArr = $searchArr;
     	$service->shot = 'orderNum';
     	$rows = $service->list_d ();
     	//把叶子节点的值0转成false，1转成true
        function toBoolean ($row) {
        	 $row ['leaf'] = $row ['leaf'] == 0 ? false :ture;
        	 return $row;
        }
        echo util_jsonUtil::encode ( array_map ("toBoolean",$rows));


     }
      /**
       * 根据Code返回对应的value
       */
      function c_getDataJsonByCode() {
      	 $datas = $this->service->getDateNameByCode ( $_GET ['code']);
      	 echo util_jsonUtil::encode ( $datas);
      }

	/**
	 * 城市编码重复性校验
	 */
	function c_checkCityCode() {
		$cityCode = isset ( $_GET ['cityCode'] ) ? $_GET ['cityCode'] : false;
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$searchArr = array ("cityCode" => $cityCode );
		$isRepeat = $this->service->isRepeat ( $searchArr, $id );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

   /**
    * 城市树
    */
     function c_getChildren(){
		$service = $this->service;

		$parentId = isset($_POST['id'])? $_POST['id'] : -1;
        //根据省id获取省信息
        $provinceDao = new model_system_procity_province();
        $proArr = $provinceDao->get_d($parentId);

		$service->searchArr['provinceId'] = $parentId;
		$service->asc = false;
		$rows=$service->listBySqlId('select_city');
      //构造数组
      $arr = $this->handleArr($proArr,$rows);

		echo util_jsonUtil :: encode ($arr);
	}
	//整理数组
	function handleArr($proArr,$rows){
		$cityArr = array();
	   foreach($rows as $key => $val){
          $cityArr[$key]['id'] = $val['id'];
          $cityArr[$key]['name'] = $val['cityName'];
          $cityArr[$key]['parentId'] = $proArr['id'];
	   }
       $arr = array(
           "id" => $proArr['id'],
           "name" => $proArr['provinceName'],
           "parentId" => "",
           "nodes" => $cityArr
       );
       return $arr;
	}

	/**
	 * 获取列表 名称 - 名称
	 */
	function c_getCityForEditGrid(){
		$this->service->getParam ( $_REQUEST );
		$this->service->asc = false;
		$rows = $this->service->list_d('select_editgrid');
		echo util_jsonUtil::encode($rows);
	}
}

?>
