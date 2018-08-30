<?php
/**
 * @author Administrator
 * @Date 2013年5月30日 星期四 15:57:29
 * @version 1.0
 * @description:物料BOM清单控制层
 */
require_once WEB_TOR . 'model/stock/material/management_import.php';
class controller_stock_material_material extends controller_base_action {

	function __construct() {
		$this->objName = "material";
		$this->objPath = "stock_material";
		parent::__construct ();
	}

	/**
	 * 跳转到物料BOM清单列表
	 */
	function c_test_view() {
		$this->view ( 'test-view' );
	}

	/**
	 * 跳转到物料BOM清单列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 * 跳转到新增物料BOM清单页面
	 */
	function c_toAdd() {
		$parentProductID = isset ( $_GET ['parentProductID'] ) ? $_GET ['parentProductID'] : '';
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET ['parentId'] : '-1';
		$this->assign ( 'parentId', $parentId );
		$this->assign ( 'parentProductID', $parentProductID );
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑物料BOM清单页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}

	/**
	 * 跳转到编辑列表
	 */
	function c_toEditList() {
		$this->service->checkParent_d ();
		$this->assign ( 'parentId', PARENT_ID );
		$this->assign ( 'productId', $_GET ['productId'] );
		$this->assign ( 'productCode', $_GET ['productCode'] );
		$this->assign ( 'productName', $_GET ['productName'] );
		$this->view ( 'list-edit' );
	}

	/**
	 * 跳转到查看物料BOM清单页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 * 跳转到投料页面
	 */
	function c_toCoutMaterial() {
		$this->view ( 'count' );
	}

	/**
	 * 树表数据
	 */
	function c_treeJson() {
		$service = $this->service;
		$arrs = array ();
		$parentProductID = isset ( $_GET ['parentProductID'] ) ? $_GET ['parentProductID'] : "";
		if ($parentProductID) {
			$service->searchArr ['parentProductID'] = $parentProductID;
		}
		$service->sort = 'c.lft';
		$service->asc = false;
		$arrs = $service->listBySqlId ( 'treelist' );

		if (! empty ( $arrs )) {
			//除去_parentId
			foreach ( $arrs as $key => $val ) {
				if ($val ['_parentId'] == - 1) {
					unset ( $arrs [$key] ['_parentId'] );
				}
			}
		}
		//数组设值
		$rows ['rows'] = $arrs;

		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 树表数据(计算结果)
	 */
	function c_treeJsonCount() {
		$service = $this->service;
		$arrs = array ();
		$parentProductID = isset ( $_GET ['parentProductID'] ) ? $_GET ['parentProductID'] : "";
		$neednum = isset ( $_GET ['neednum'] ) ? $_GET ['neednum'] : 1;

		if ($parentProductID) {
			$service->searchArr ['parentProductID'] = $parentProductID;
		}
		$service->sort = 'c.lft';
		$service->asc = false;
		$arrs = $service->listBySqlId ( 'treelist' );

		if (is_array ( $arrs )) {
			//处理计算结果
			$newRows = $this->service->treeCondList ( $arrs, - 1, $neednum );
			//数组设值
			$rows ['rows'] = $newRows;

			echo util_jsonUtil::encode ( $newRows );
		} else {
			//数组设值
			$rows ['rows'] = $arrs;

			echo util_jsonUtil::encode ( $arrs );

		}
	}
	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}
	}

	/**
	 * 修改对象
	 */
	//	function c_edit($isEditInfo = false) {
	////		$this->permCheck (); //安全校验
	//		$object = $_POST [$this->objName];
	//		if ($this->service->edit_d ( $object, $isEditInfo )) {
	//			msg ( '编辑成功！' );
	//		}
	//	}


	/**
	 * 导入
	 */
	function c_import() {

	}

	/**
	 * 导出
	 */
	function c_export() {

	}

	/**
	 * 批量更新
	 */
	function c_batch_update() {

	}

    /**
     * 导入清单文件
     */
    public function c_import_excel() {
        $import = new model_stock_material_management_import();
        $errorList = $import->importBasicCheck($_POST, $_FILES);
        if (count($errorList) == 0 && $import->processImport($_FILES) == true) {
            exit($this->alertResult('openReviewDialog', ''));
        }
        $msg = $this->processMsg($errorList);
        exit($this->alertResult('alertResult', $msg, false));
    }

    /**
     * 获取树结构列表
     */
    function c_load_materials_tree() {
        $datas = $this->service->loadTree();
        $datas = $this->_setBomId($datas, '0');

        //判断是否获取多产品
//        if(!isset($_REQUEST['no_more'])) {
//	        $moreConfigList = $this->_setBomId($management->getMoreConfigList(), '1');
//	        if(!empty($moreConfigList)) {
//	        	$datas[] = array(
//		        	'id' => '',
//		        	'text' => '多产品配置',
//		        	'children' => $moreConfigList
//		        );
//	        }
//        }

        header('Content-type:application/json');
        exit(json_encode(un_iconv($datas)));
    }

    function c_load_SF_tree() {
        $datas = $this->service->loadSFTree();
        $datas = $this->_setBomId($datas, '0');

        header('Content-type:application/json');
        exit(json_encode(un_iconv($datas)));
    }
	/**
	 * 获取配置列表
	 * */
    function c_load_config(){
    	$datas = $this->service->loadConfig($_GET['semifinishedId']);

    	exit(json_encode(un_iconv($datas)));
    }
    /**
     * 获取元器件数据
     * */
    function c_load_parts(){
    	//BOM清单列表不过滤物料编码为空的元器件数据，只有当生成配置单的时候才需过滤
    	$listType = isset($_GET['listType']) ? $_GET['listType'] : "";
    	$datas = $this->service->loadParts($_GET['semifinishedId'],$_GET['configId'],$listType);

    	exit(json_encode(un_iconv($datas)));
    }
	private function _setBomId($arr, $type) {
    	foreach($arr as &$val) {
    		if(!empty($val['children'])) {
    			$val['children'] = $this->_setBomId($val['children'], $type);
    		}

    		$val['id'] = $val['id'] . '_' . $type;
    	}

    	return $arr;
    }
    /**
     * 生成配置单获取数据
     * */
    function c_load_configuration(){
    	$datas = $this->service->loadParts($_GET['semifinishedId']);
    	exit(json_encode(un_iconv($datas)));
    }

    /**
     * BOM管理存储数据
     * */
    function c_insert_details(){
    	if(!empty($_POST['datas'])){
    		$ID = $this->service->insertDetails($_POST['datas'],$_POST['FName'],$_POST['CPCode']);
    		exit(json_encode($ID));
    	}

    }
    
    /**
     * BOM管理统计用料时更新数据
     * */
    function c_update_details(){
    	if(!empty($_POST['id']) && !empty($_POST['val'])){
    		if($this->service->updateDetails($_POST['id'],$_POST['val'])){
    			echo 0;
    		}else{
    			echo 1;
    		}
    	}   
    }
    
    /**
     * 统计用料
     * */
    function c_load_statistical(){
    	if(!empty($_GET['finishedId'])){
    		$datas = $this->service->loadStatistical($_GET['finishedId']);
    		exit(json_encode(un_iconv($datas)));
    	}

    }
/**
     * 加载Tag项
     */
    public function c_view_tags($return = false) {
        $str = '';
        if (isset($_GET['operation'])) {
            //导入操作
            if ($_GET['operation'] == 'import') {
                $import = new model_stock_material_management_import();
                $sheets = $import->getSession('SHEETS');
            }

            //查看/配置单操作
            if ($_GET['operation'] == 'view' && isset($_GET['current_product_id'])) {
            	$ids = explode('_', $_GET['current_product_id']);
            	$id = $ids[0];

                $view = new model_stock_material_management_view();
                $sheets = $view->getRelevanceByProductId($id);
            }

            if (count($sheets) > 0) {
                $str = "<div style='width:100%;'>";
                foreach ($sheets as $id => $sheetName) {
                    $str .= "<input type='button' id='import_sheet_{$id}' name='import_sheet_tag[]' style='cursor: pointer; background:{$bgColor};' onclick=\"processReviewTag(this, '{$_GET['operation']}');\" value='{$sheetName}' >&nbsp;";
                    $bgColor = 'buttonface';
                }
                $str .= "</div>";
            }

        }

        if($return) {
        	return $str;
        }

        if(!empty($str)) {
        	$str = '<div class="bom_list_config">' . $str . '</div>';
        }

        exit(un_iconv($str));
    }
 	/**
     * 信息输出方法
     * @param unknown_type $msg
     */
    public function alertResult($methodName, $val, $isJson = true) {
        if (!$isJson && $val != "") {
            $val = "'{$val}'";
        }
        return "<script>parent.{$methodName}({$val});</script>";
    }
 	/**
     * 获取导入、查看功能的子表
     */
    public function c_sub_review_table() {
        $data = array();
        if (isset($_GET['operation']) && isset($_GET['id']) && isset($_GET['tag']) && isset($_GET['type'])) {

            if ($_GET['operation'] == 'view') {
                $view = new model_stock_material_management_view();
                if ($_GET['type'] == 'detail') {
                    $product_id = $_GET['tag'];
                } else {
                    $product_id = $_GET['id'];
                }

                $data = $view->getSubDetail($product_id, $_GET['type']);
            }

            if ($_GET['operation'] == 'import') {
                $import = new model_stock_material_management_import();
                $data = $import->importSheetDetail($_GET['tag'], $_GET['id'], $_GET['type']);
            }
        }

        header('Content-type:application/json');
        exit(json_encode(un_iconv($data)));
    }
	/**
     * 获取主表
     */
    public function c_review_table() {
        $outputArr = array();
        if (isset($_GET['tag']) && isset($_GET['operation'])) {
            $data = array();

            //导入操作
            if ($_GET['operation'] == 'import') {
                $import = new model_stock_material_management_import();
                $data = $import->getSession('INFOS');
                $data = $data[$_GET['tag']];
            }

            //查看操作
            if ($_GET['operation'] == 'view') {
                $view = new model_stock_material_management_view();
                $data['detail'] = $view->getSubDetail($_GET['tag'], 'detail');
                $data['config'] = $view->getConfigTitle($_GET['tag']);
            }

            if (count($data) > 0) {
                if (isset($data['detail'])) {
                    $outputArr[] = $this->setMainReviewTableProperties(0, '物料清单', '', '', 'detail');
                }

                if (isset($data['config'])) {
                    foreach ($data['config'] as $key => $value) {
                        $outputArr[] = $this->setMainReviewTableProperties($value['id'], $value['name'], $value['clash'], $value['description'], 'config');
                    }
                }
            }
        }

        header('Content-type:application/json');
        exit(json_encode(un_iconv($outputArr)));
    }
/**
     * 设置Review主表格属性
     * @param int $id
     * @param String $name
     * @param String $clash
     * @param String $description
     * @param String $type
     * @return Array
     */
    public function setMainReviewTableProperties($id, $name, $clash, $description, $type) {
        return array(
            'id' => $id,
            'name' => $name,
            'clash' => $clash,
            'description' => $description,
            'type' => $type
        );
    }
    public function c_edit_sub_review_table_detail() {
        $flag = 'fail';
        if ($_POST) {
            $import = new model_stock_material_management_import();

            if ($import->editReviewDetail($_POST['code'], $_POST['stock_index'], $_POST['main_type'], $_POST['main_tag'])) {
                $flag = 'successful';
            }
        }

        exit($flag);
    }
    /**
     * 保存新产品
     */
    public function c_create_product_and_save_session() {
//        $newId = 0;

            set_time_limit(0);
            $import = new model_stock_material_management_import();
//            $productType = new model_stock_productinfo_producttype();

            //保存产品并检查是否是成品
//            $postData = mb_iconv($_POST['producttype']);
//            if ($_POST['producttype']['parentId'] == '309') {
//                $postData['parentName'] = "成品";
//            } else {
//                $postData['parentName'] = "半成品";
//            }
//
//            $postData['orderNum'] = 0;
//            $postData['accountingCode'] = 'KJKM1';
//
//            $newId = $productType->add_d($postData);
//
            //如果成功，保存SESSION信息
//            if ($newId) {
//                $sheets = $import->getSession('SHEETS');
//                foreach ($sheets as $key => $tag) {
//                    $newSheetId = $import->saveRelevance($tag, $key, $newId);
//                    if ($newSheetId) {
//                        $datas = $import->getSession('INFOS');
						$datas = $import->getSession();
						$INFO_datas = $datas['INFOS'];
						$FNId = $_POST['CPSN'];
						$CPSI = $_POST['CPSI'];
						$CPSC = $_POST['CPSC'];
						$SFC = substr($_POST['SFC'], 1);
						if(strpos($SFC,',')){
							$SFC = explode(',',$SFC);
						}else{
							$SFC[0] = $SFC;
						}

						$SFI = substr($_POST['SFI'], 1);
						if(strpos($SFI,',')){
							$SFI = explode(',',$SFI);
						}else{
							$SFI[0] = $SFI;
						}

						if($CPSI){
							$FNId = $import->savaFinished($CPSI,$CPSC);
						}
						foreach ($datas['SHEETS'] as $key => $name){
                        	$SFId = $import->savaSemiFinished($name,$FNId,$SFC[$key],$SFI[$key]);
                       		if ($SFId) {
                            	$flag = $import->saveDetail($INFO_datas[$key]['detail'], "detail",$SFId);

	                            if ($flag) {
	                                //配置信息
	                                if (isset($INFO_datas[$key]['config']) && count($INFO_datas[$key]['config']) > 0) {
	                                    $import->saveConfig($INFO_datas[$key]['config'], $SFId);
	                                }
	                            }
                       		}
                        }
//                    }
//                }
//            }

        echo $FNId;
    }
    public function c_finishedName(){
    	$data = $this->service->getFinishedData();
    	exit(json_encode(un_iconv($data)));
    }

	//删除半成品
    public function c_delete_SF() {
    	if( isset($_POST['id']) && $_POST['id'] ){
    		echo $this->service->delete_SF($_POST['id']);
    	}
    }
	//删除成品
    public function c_delete_finished() {
    	if( isset($_POST['id']) && $_POST['id'] ){
    		echo $this->service->delete_finished($_POST['id']);
    	}
    }

    public function c_detection_import_session() {
		$import = new model_stock_material_management_import();
		$datas = $import->getSession();
//		$INFO_datas = $datas['INFOS'];

		$table_sf = '';
//		$Tdatas = $import->getMaterialCode(); //TJCP 成品   TJBCP 半成品
		$Cdatas = $this->service->getFinishedData();
//		print_r($datas);
//		$BCPS = "<select class='BCPCla'>";
//		foreach ($Tdatas['TJBCP'] as $key_B => $val_B){
//			$BCPS .= " <option value ='{$val_B['productCode']}'>{$val_B['productCode']}</option>";
//		}
//		$BCPS .= "</select>";

		$CPSN = "<select id='CPSN'>";
		foreach ($Cdatas as $Ckey => $Cval){
			$CPSN .= " <option value ='{$Cval['id']}'>{$Cval['name']}</option>";
		}
		$CPSN .= "</select>";

//		$CPS = "<select id='CPS'>";
//		foreach ($Tdatas['TJCP'] as $key_J => $val_J){
//			$CPS .= " <option value ='{$val_J['productCode']}'>{$val_J['productCode']}</option>";
//		}
//		$CPS .= "</select>";
		$a=1;
		foreach($datas['SHEETS'] as $key => $val){
			if(isset($datas['INFOS'][$key]['detail'])){
				foreach ($datas['INFOS'][$key]['detail'] as $key_d => $val_d){
					if(!empty($val_d['code'])){
						$YND = $import->checkStockCode($val_d['code']);
						if ($YND == 'N'){ break; }
					}
				}
			}
			if(isset($datas['INFOS'][$key]['config'])){
				foreach ($datas['INFOS'][$key]['config'] as $key_c => $val_c){
					foreach ($val_c['detail'] as $key_l => $val_l){
						if(!empty($val_l['code'])){
							$YNC = $import->checkStockCode($val_l['code']);
							if ($YNC == 'N'){ break; }
						}
					}
				}
			}

			if($YND == 'N' || $YNC == 'N' ){
				echo 'N';
				exit;
			}

			if(!$table_sf){
				$cosl = count($datas['SHEETS'])*3;
				$table_sf = "<tr>
								<td rowspan={$cosl} class='sf_td sf_bg' width='30%'>半成品编号</td>
								<td class='sf_td' width='70%'>{$val}</td>
							</tr>
							<tr>
								<td class='sf_td'>
									<input name='pCode".$a++."' type='text'>
								</td>
							</tr>
							<tr>
								<td class='sf_td'>
									领料标识&nbsp;&nbsp;
									<select name='pInfo".$a++."' type='text'>
										<option value='是'>是</option>
										<option value='否'>否</option>
									</select>
								</td>
							</tr>";
				//<tr><td class='sf_td'>{$BCPS}</td></tr>
			}else{
				$table_sf.="<tr><td class='sf_td'>{$val}</td></tr>
							<tr>
								<td class='sf_td'>
									<input name='pCode".$a++."' type='text'>
								</td>
							</tr>
							<tr>
								<td class='sf_td'>
									领料标识&nbsp;&nbsp;
									<select name='pInfo".$a++."' type='text'>
										<option value='是'>是</option>
										<option value='否'>否</option>
									</select>
								</td>
							</tr>";
			}

		}

		//<td class='sf_td'>{$CPS}</td>
		$table = "<table width='100%' id='Tab'> " .$table_sf."
					<tr >
					<td class='sf_td sf_bg'>成品名称</td>
					<td class='sf_td'>
						<div id='addCP'>{$CPSN} <a href='#' onclick=addCP('a');>新增</a></div>
						<div id='cancelCP' style='display:none;'><input type='text' id='CPSI' readonly><a href='#' onclick=addCP('c');>取消</a></div>
					</td>
					</tr>
					<tr  id='addTd' style='display:none;'>
					<td class='sf_td sf_bg'>成品编号</td>
					<td class='sf_td'>
						<input id='FPCode' type='text' style='width:150px;'>
					</td>
					</tr>
					<tr  id='cTd'>
					<td class='sf_td' style='height:22px;'> </td>
					<td class='sf_td'></td>
					</tr>
					</table>
					";

		exit(un_iconv($table));
//		echo  'Y';
//		print_r($INFO_datas);
//			$FNId = $_POST['id'];
//			if(!is_int((int)$FNId)){
//				$FNId = $import->savaFinished($FNId);
//			}
//			foreach ($datas['SHEETS'] as $key => $name){
//            	$SFId = $import->savaSemiFinished($name,$FNId);
//                if ($SFId) {
//                	$flag = $import->saveDetail($INFO_datas[$key]['detail'], "detail",$SFId);
//                }
//            }
    }

	public function c_edit_Material_detail() {
		$import = new model_stock_material_management_import();

        if ($_POST) {
        	if($_POST['editType'] == 'session' && isset($_SESSION[$_SESSION['USER_ID']]['EXCEL_IMPORT']['INFOS'])){
        		if($_POST['code'] != ""){
        			if($import->checkStockCode($_POST['code']) === "N"){
        				exit('2');
        			}
        		}
        		if($_POST['mainTableType'] == 'detail'){
        			$_SESSION[$_SESSION['USER_ID']]['EXCEL_IMPORT']['INFOS'][$_POST['mainTag']]['detail'][$_POST['subTableIndex']]['code'] = $_POST['code'];
        		}else{
        			$configIndex = substr($_POST['subTableName'],-1);
        			$_SESSION[$_SESSION['USER_ID']]['EXCEL_IMPORT']['INFOS'][$_POST['mainTag']]['config'][$configIndex]['detail'][$_POST['subTableIndex']]['code'] = $_POST['code'];
        		}
        		exit(true);
        	}else{
        		$field['id'] 			= $_POST['id'];
        		$field['code'] 			= $_POST['code'];
        		$field['name'] 			= $_POST['name'];
        		$field['model'] 		= $_POST['model'];
        		$field['packaging'] 	= $_POST['packaging'];
        		$field['total'] 		= $_POST['total'];
        		$field['serial_number'] = $_POST['serial_number'];
        		$field['factory'] 		= $_POST['factory'];
        		$field['description'] 	= $_POST['description'];
        		$field['pickingInfo'] 	= $_POST['pickingInfo'];

        		exit($import->editMaterialDetail($field));
        	}
        }
    }
	public function c_load_parts_SF() {
        if ($_POST) {
        	$data = $this->service->loadPartsSF($_POST['id']);
			exit(json_encode(un_iconv($data)));
        }
    }

 public function c_edit_SF() {

		$table_sf = '';
		$import = new model_stock_material_management_import();
		$Tdatas = $import->getMaterialCode(); //TJCP 成品   TJBCP 半成品
		$data = $this->service->loadPartsSF($_POST['id']);
//		$BCPS = "<select id='SFCodeVal'>";
//		foreach ($Tdatas['TJBCP'] as $key_B => $val_B){
//			if($data[0]['code'] == $val_B['productCode']){
//				$BCPS .= " <option value ='{$val_B['productCode']}' selected>{$val_B['productCode']}</option>";
//			}else{
//				$BCPS .= " <option value ='{$val_B['productCode']}'>{$val_B['productCode']}</option>";
//			}
//		}
//		$BCPS .= "</select>";
//		<td class='sf_td'>{$BCPS}</td>
		$yes = $data[0]['pickingInfo'] == '是' ? 'selected' : '';
		$no  = $yes == '' ? 'selected' : '';
		$table = "<table width='100%' id='Tab'>
					<tr >
						<td class='sf_td sf_bg'>半成品名称</td>
						<td class='sf_td'>
							<input type='text' value='{$data[0]['name']}'  size='42' id='SFNameVal'>
						</td>
					</tr>
					<tr>
						<td class='sf_td sf_bg'>半成品编号</td>
						<td class='sf_td'>
							<input id='SFCode' type='text' value='{$data[0]['code']}'>
						</td>
					</tr>
					<tr>
						<td class='sf_td sf_bg'>领料标识</td>
						<td class='sf_td'>
							<select id='SFInfo'>
								<option value='是' $yes>是</option>
								<option value='否' $no >否</option>
							</select>
						</td>
					</tr>
				</table>";

		exit(un_iconv($table));
    }


	public function c_update_SF() {
        if ($_POST) {
			exit($this->service->update_sf($_POST));
        }
    }
}
?>