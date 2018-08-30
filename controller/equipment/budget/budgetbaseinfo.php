<?php
/**
 * @author Administrator
 * @Date 2012-10-25 15:02:55
 * @version 1.0
 * @description:�豸������Ϣ���Ʋ�
 */
class controller_equipment_budget_budgetbaseinfo extends controller_base_action {

	function __construct() {
		$this->objName = "budgetbaseinfo";
		$this->objPath = "equipment_budget";
		parent::__construct ();
	 }

	/*
	 * ��ת���豸������Ϣ�б�
	 */
    function c_page() {
        $flag = isset($_GET['flag'])?$_GET['flag'] : "all";
        $this->assign("flag" ,$flag);

        // ���ڷֱ��ǵ������豸����ҳ���뻹���ҵ����۵��豸��ѯҳ���� ��ID2187������һ����Ŀ��
        $jsFile = (isset($_GET['pagefrom']) && $_GET['pagefrom'] = "mysales")? "budgetbaseinfo-viewgrid.js" : "budgetbaseinfo-grid.js";
        $this->assign("jsFile",$jsFile);
        $this->view('list');
    }

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$rows = $service->page_d ();
	  foreach($rows as $k=>$v){
	  	 $dao = new model_purchase_contract_equipment();
	  	 $newPriceArr = $dao -> getHistoryInfo_d($v['equCode'],date("Y-m-d"));
	  	 $rows[$k]['latestPrice'] = $newPriceArr['applyPrice'];
	  }
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * ��ת������
	 */
	function c_toAdd() {
		$parentName = isset ( $_GET ['parentName'] ) ? $_GET ['parentName'] : "";
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET ['parentId'] : "";

		$this->assign ( "parentId", $parentId );
		$this->assign ( "parentName", $parentName );
		$this->view ( 'add' );
	}

   /**
	 * ��ת���༭�豸������Ϣҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴�豸������Ϣҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }


	/**
	 * �����������
	 */
	function c_add($isAddInfo = true) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}
	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = true) {
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}

	/**
	 * Ԥ���ѡ��
	 */
	function c_budgetChooseiframe(){
		$budgetTypeId = $_GET['budgetTypeId'];
	   $list = $this->service->budgetChooseiframe_d($budgetTypeId);
	   $this->assign("list",$list);
       $this->view("budgetChooseiframe");
	}

	/**
	 * �Ƿ������豸
	 */
		function c_ajaxUseStatus(){
		try{
			$this->service->ajaxUseStatus_d($_POST ['id'],$_POST ['useStatus']);
			echo 1;
		} catch(Exception $e){
			echo 0;
		}
	}

	/**
	 * �豸����ѡ��
	 */
	function c_toChooseBudget(){
		$this->view ( 'choose-list' );
	}

	/**
	 * ajax �������
	 */
    function c_ajaxEmptyData(){
    	try{
			$this->service->ajaxEmptyData_d();
			echo 1;
		} catch(Exception $e){
			echo 0;
		}
    }


/***********************************************����***********begin***************************************************/

	/**
	 *��ת��excel�ϴ�ҳ��
	 */
	function c_toExcel() {
		$this->assign("dateTime", date("Y-m-d"));
		$this->view('importexcel');
	}
	/**
	 * �ϴ�EXCEL
	 */
	function c_upExcel() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(0);

		$objNameArr = array (
			0 => 'budgetTypeName', //��������
			1 => 'equCode', //���ϱ��
			2 => 'equName', //��������
			3 => 'pattern', //����ͺ�
			4 => 'brand', //Ʒ��
			5 => 'quotedPrice', //����
			6 => 'useEndDate', //������Ч��
			7 => 'unitName', //��λ
			8 => 'remark', //��ע

		);
		$this->c_addExecel($objNameArr);
	}

	/**
	 * �ϴ�EXCEl������������
	 */
	function c_addExecel($objNameArr, $ExaDT) {
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$upexcel = new model_contract_common_allcontract();
			$excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
			spl_autoload_register('__autoload'); //�ı������ķ�ʽ
			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					foreach ($objNameArr as $index => $fieldName) {
						$objectArr[$rNum][$fieldName] = $row[$index];
					}
				}
				$arrinfo = array();//��������
                //ѭ�������������
                foreach($objectArr as $key => $val){
                    //�ж� �豸�����Ƿ����
                    $budgetTypeId = $this->service->getbudTypeIdByName($val['budgetTypeName']);
                   if(empty($budgetTypeId)){
                   	  array_push ( $arrinfo, array ("orderCode" => $val['budgetTypeName'],"cusName" => $val['equName'],"result" => "����ʧ�ܣ�δ�ҵ���Ӧ������������Ϣ" ) );
                   }else{
                   	   $objectArr[$key]['budgetTypeId'] = $budgetTypeId;
                   	   $objectArr[$key]['useStatus'] = "1";
                   	   //����ʱ���
                       $objectArr[$key]['useEndDate'] = $this->service->transitionTime($val['useEndDate']);
//echo "<pre>";
//print_r($objectArr[$key]);
//die();
	                      $newId = $this->service->add_d($objectArr[$key], true);
	                      if($newId){
	                      	 array_push ( $arrinfo, array ("orderCode" => $val['budgetTypeName'],"cusName" => $val['equName'],"result" => "����ɹ���" ) );
	                      }else{
	                      	 array_push ( $arrinfo, array ("orderCode" => $val['budgetTypeName'],"cusName" => $val['equName'],"result" => "����ʧ�ܣ�" ) );
	                      }

                    }

                }

	            if ($arrinfo){
				  echo util_excelUtil::showResultOrder ( $arrinfo, "������", array ("��������","��������", "���" ) );
				}
			} else {
				echo "�ļ������ڿ�ʶ������!";
			}
		} else {
			echo "�ϴ��ļ����Ͳ���EXCEL!";
		}

	}


/***********************************************����***********end***************************************************/
  /**
   * �б���
   */
  function c_exportExcel(){
  	    $useStatusArr = array(
			'0'=>'�ر�',
			'1'=>'����'
		);

		$service = $this->service;
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(600);
		$rows = array ();
		$colIdStr = $_GET['colId'];
		$colNameStr = $_GET['colName'];
		$searchConditionKey = $_GET['searchConditionKey']; //��ͨ������Key
		$searchConditionVal = $_GET['searchConditionVal']; //��ͨ������Val
		$searchArr[$searchConditionKey] = $searchConditionVal;

		$budgetTypeId = $_GET['budgetTypeId']; //��ͨ������Val
	  if(!empty($budgetTypeId)){
	  	 $searchArr['budgetTypeId'] = $budgetTypeId;
	  }

		if( isset($_SESSION['advSql']) ){
			$_REQUEST['advSql'] = $_SESSION['advSql'];
		}

		$service->getParam($_REQUEST);
//		//��¼��
//		$appId = $_SESSION['USER_ID'];
		//��ͷId����
		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		//��ͷName����
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		//��ͷ����
		$colArr = array_combine($colIdArr, $colNameArr);
		//������Ȩ������
		if(!empty($this->service->searchArr)){
          	  $this->service->searchArr = array_merge($this->service->searchArr,$searchArr);
          }else{
              $this->service->searchArr = $searchArr;
          }
        if($this->service->searchArr['budgetTypeId'] == 'undefined'){
        	unset($this->service->searchArr['budgetTypeId']);
        }
			//			$rows = $service->page_d();

		$rows = $service->listBySqlId ( 'select_default' );
		$arr = array ();
		$arr['collection'] = $rows;
		foreach ($rows as $index => $row) {
			foreach ($row as $key => $val) {
				if ($key == 'useStatus') {
					 $rows[$index][$key] = $useStatusArr[$val];
				}
			}
		}
//		echo "<pre>";
//		print_R($rows);
		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}
//		echo "<pre>";
//		print_R($dataArr);
//		die();
		return model_equipment_common_ExcelUtil :: export2ExcelUtil($colArr, $dataArr);
	}


 }
?>