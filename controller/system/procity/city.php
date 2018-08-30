<?php

/*
 *	�л�����ϢController
 */

class controller_system_procity_city extends controller_base_action {

	function __construct() {
		$this->objName = "city";
		$this->objPath = "system_procity";
		parent :: __construct();
	}

	/**
	 * ��ת��	ʡ��ҳ��
	 */
	function c_toTreeList() {

		$this->display("list");
	}

	/**
	 * ���
	 */
	function c_toAdd() {
		$this->view("add");
	}
	/**
	* ��д�޸�
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
	 * ��дpageJson����
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc = false;
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ����parentId��ȡ��ʡ����Ϣ
	 */
	 function c_listByParent() {
	 	$searchArr = array ("parentId" => $_GET ['parentId']);
	 	$service = $this->service;
	 	$service->searchArr = $searchArr;
	 	$service->sort = 'orderNum';
	 	$rows = $service->list_d ();
        //��Ҷ�ӽڵ��ֵ0ת��false��1ת��true
        function toBoolean ($row) {
        	 $row ['leaf'] = $row ['leaf'] == 0 ? false :ture;
        	 return $row;
        }
        echo util_jsonUtil::encode ( array_map ("toBoolean",$rows));

	 }

    /**
     * ����parentCode��ȡʡ����Ϣ
     */
     function c_listByParentCode () {
     	$searchArr = array ("parentCode" => $_GET ['parentCode']);
     	$service = $this->service;
     	$service->searchArr = $searchArr;
     	$service->shot = 'orderNum';
     	$rows = $service->list_d ();
     	//��Ҷ�ӽڵ��ֵ0ת��false��1ת��true
        function toBoolean ($row) {
        	 $row ['leaf'] = $row ['leaf'] == 0 ? false :ture;
        	 return $row;
        }
        echo util_jsonUtil::encode ( array_map ("toBoolean",$rows));


     }
      /**
       * ����Code���ض�Ӧ��value
       */
      function c_getDataJsonByCode() {
      	 $datas = $this->service->getDateNameByCode ( $_GET ['code']);
      	 echo util_jsonUtil::encode ( $datas);
      }

	/**
	 * ���б����ظ���У��
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
    * ������
    */
     function c_getChildren(){
		$service = $this->service;

		$parentId = isset($_POST['id'])? $_POST['id'] : -1;
        //����ʡid��ȡʡ��Ϣ
        $provinceDao = new model_system_procity_province();
        $proArr = $provinceDao->get_d($parentId);

		$service->searchArr['provinceId'] = $parentId;
		$service->asc = false;
		$rows=$service->listBySqlId('select_city');
      //��������
      $arr = $this->handleArr($proArr,$rows);

		echo util_jsonUtil :: encode ($arr);
	}
	//��������
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
	 * ��ȡ�б� ���� - ����
	 */
	function c_getCityForEditGrid(){
		$this->service->getParam ( $_REQUEST );
		$this->service->asc = false;
		$rows = $this->service->list_d('select_editgrid');
		echo util_jsonUtil::encode($rows);
	}
}

?>
