<?php
/**
 * @author Administrator
 * @Date 2012-10-25 15:02:55
 * @version 1.0
 * @description:设备基本信息控制层
 */
class controller_equipment_budget_budgetbaseinfo extends controller_base_action {

	function __construct() {
		$this->objName = "budgetbaseinfo";
		$this->objPath = "equipment_budget";
		parent::__construct ();
	 }

	/*
	 * 跳转到设备基本信息列表
	 */
    function c_page() {
        $flag = isset($_GET['flag'])?$_GET['flag'] : "all";
        $this->assign("flag" ,$flag);

        // 用于分辨是单独从设备管理页进入还是我的销售的设备查询页进入 （ID2187新添了一个栏目）
        $jsFile = (isset($_GET['pagefrom']) && $_GET['pagefrom'] = "mysales")? "budgetbaseinfo-viewgrid.js" : "budgetbaseinfo-grid.js";
        $this->assign("jsFile",$jsFile);
        $this->view('list');
    }

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		//$service->asc = false;
		$rows = $service->page_d ();
	  foreach($rows as $k=>$v){
	  	 $dao = new model_purchase_contract_equipment();
	  	 $newPriceArr = $dao -> getHistoryInfo_d($v['equCode'],date("Y-m-d"));
	  	 $rows[$k]['latestPrice'] = $newPriceArr['applyPrice'];
	  }
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * 跳转到新增
	 */
	function c_toAdd() {
		$parentName = isset ( $_GET ['parentName'] ) ? $_GET ['parentName'] : "";
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET ['parentId'] : "";

		$this->assign ( "parentId", $parentId );
		$this->assign ( "parentName", $parentName );
		$this->view ( 'add' );
	}

   /**
	 * 跳转到编辑设备基本信息页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * 跳转到查看设备基本信息页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }


	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = true) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
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
	 * 修改对象
	 */
	function c_edit($isEditInfo = true) {
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}

	/**
	 * 预算表选择
	 */
	function c_budgetChooseiframe(){
		$budgetTypeId = $_GET['budgetTypeId'];
	   $list = $this->service->budgetChooseiframe_d($budgetTypeId);
	   $this->assign("list",$list);
       $this->view("budgetChooseiframe");
	}

	/**
	 * 是否启用设备
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
	 * 设备配置选择
	 */
	function c_toChooseBudget(){
		$this->view ( 'choose-list' );
	}

	/**
	 * ajax 清空数据
	 */
    function c_ajaxEmptyData(){
    	try{
			$this->service->ajaxEmptyData_d();
			echo 1;
		} catch(Exception $e){
			echo 0;
		}
    }


/***********************************************导入***********begin***************************************************/

	/**
	 *跳转到excel上传页面
	 */
	function c_toExcel() {
		$this->assign("dateTime", date("Y-m-d"));
		$this->view('importexcel');
	}
	/**
	 * 上传EXCEL
	 */
	function c_upExcel() {
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(0);

		$objNameArr = array (
			0 => 'budgetTypeName', //所属分类
			1 => 'equCode', //物料编号
			2 => 'equName', //物料名称
			3 => 'pattern', //规格型号
			4 => 'brand', //品牌
			5 => 'quotedPrice', //报价
			6 => 'useEndDate', //报价有效期
			7 => 'unitName', //单位
			8 => 'remark', //备注

		);
		$this->c_addExecel($objNameArr);
	}

	/**
	 * 上传EXCEl并导入其数据
	 */
	function c_addExecel($objNameArr, $ExaDT) {
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$upexcel = new model_contract_common_allcontract();
			$excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
			spl_autoload_register('__autoload'); //改变加载类的方式
			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					foreach ($objNameArr as $index => $fieldName) {
						$objectArr[$rNum][$fieldName] = $row[$index];
					}
				}
				$arrinfo = array();//导入数据
                //循环处理插入数组
                foreach($objectArr as $key => $val){
                    //判断 设备分类是否存在
                    $budgetTypeId = $this->service->getbudTypeIdByName($val['budgetTypeName']);
                   if(empty($budgetTypeId)){
                   	  array_push ( $arrinfo, array ("orderCode" => $val['budgetTypeName'],"cusName" => $val['equName'],"result" => "导入失败，未找到对应的所属分类信息" ) );
                   }else{
                   	   $objectArr[$key]['budgetTypeId'] = $budgetTypeId;
                   	   $objectArr[$key]['useStatus'] = "1";
                   	   //处理时间戳
                       $objectArr[$key]['useEndDate'] = $this->service->transitionTime($val['useEndDate']);
//echo "<pre>";
//print_r($objectArr[$key]);
//die();
	                      $newId = $this->service->add_d($objectArr[$key], true);
	                      if($newId){
	                      	 array_push ( $arrinfo, array ("orderCode" => $val['budgetTypeName'],"cusName" => $val['equName'],"result" => "导入成功！" ) );
	                      }else{
	                      	 array_push ( $arrinfo, array ("orderCode" => $val['budgetTypeName'],"cusName" => $val['equName'],"result" => "新增失败！" ) );
	                      }

                    }

                }

	            if ($arrinfo){
				  echo util_excelUtil::showResultOrder ( $arrinfo, "导入结果", array ("所属分类","物料名称", "结果" ) );
				}
			} else {
				echo "文件不存在可识别数据!";
			}
		} else {
			echo "上传文件类型不是EXCEL!";
		}

	}


/***********************************************导入***********end***************************************************/
  /**
   * 列表导出
   */
  function c_exportExcel(){
  	    $useStatusArr = array(
			'0'=>'关闭',
			'1'=>'启用'
		);

		$service = $this->service;
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(600);
		$rows = array ();
		$colIdStr = $_GET['colId'];
		$colNameStr = $_GET['colName'];
		$searchConditionKey = $_GET['searchConditionKey']; //普通搜索的Key
		$searchConditionVal = $_GET['searchConditionVal']; //普通搜索的Val
		$searchArr[$searchConditionKey] = $searchConditionVal;

		$budgetTypeId = $_GET['budgetTypeId']; //普通搜索的Val
	  if(!empty($budgetTypeId)){
	  	 $searchArr['budgetTypeId'] = $budgetTypeId;
	  }

		if( isset($_SESSION['advSql']) ){
			$_REQUEST['advSql'] = $_SESSION['advSql'];
		}

		$service->getParam($_REQUEST);
//		//登录人
//		$appId = $_SESSION['USER_ID'];
		//表头Id数组
		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		//表头Name数组
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		//表头数组
		$colArr = array_combine($colIdArr, $colNameArr);
		//过滤型权限设置
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
		//匹配导出列
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