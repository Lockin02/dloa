<?php
/**
 * @author Administrator
 * @Date 2013��5��30�� ������ 15:57:29
 * @version 1.0
 * @description:����BOM�嵥���Ʋ�
 */
require_once WEB_TOR . 'model/stock/material/management_import.php';
class controller_stock_material_material extends controller_base_action {

	function __construct() {
		$this->objName = "material";
		$this->objPath = "stock_material";
		parent::__construct ();
	}

	/**
	 * ��ת������BOM�嵥�б�
	 */
	function c_test_view() {
		$this->view ( 'test-view' );
	}

	/**
	 * ��ת������BOM�嵥�б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 * ��ת����������BOM�嵥ҳ��
	 */
	function c_toAdd() {
		$parentProductID = isset ( $_GET ['parentProductID'] ) ? $_GET ['parentProductID'] : '';
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET ['parentId'] : '-1';
		$this->assign ( 'parentId', $parentId );
		$this->assign ( 'parentProductID', $parentProductID );
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭����BOM�嵥ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}

	/**
	 * ��ת���༭�б�
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
	 * ��ת���鿴����BOM�嵥ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 * ��ת��Ͷ��ҳ��
	 */
	function c_toCoutMaterial() {
		$this->view ( 'count' );
	}

	/**
	 * ��������
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
			//��ȥ_parentId
			foreach ( $arrs as $key => $val ) {
				if ($val ['_parentId'] == - 1) {
					unset ( $arrs [$key] ['_parentId'] );
				}
			}
		}
		//������ֵ
		$rows ['rows'] = $arrs;

		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��������(������)
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
			//���������
			$newRows = $this->service->treeCondList ( $arrs, - 1, $neednum );
			//������ֵ
			$rows ['rows'] = $newRows;

			echo util_jsonUtil::encode ( $newRows );
		} else {
			//������ֵ
			$rows ['rows'] = $arrs;

			echo util_jsonUtil::encode ( $arrs );

		}
	}
	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}
	}

	/**
	 * �޸Ķ���
	 */
	//	function c_edit($isEditInfo = false) {
	////		$this->permCheck (); //��ȫУ��
	//		$object = $_POST [$this->objName];
	//		if ($this->service->edit_d ( $object, $isEditInfo )) {
	//			msg ( '�༭�ɹ���' );
	//		}
	//	}


	/**
	 * ����
	 */
	function c_import() {

	}

	/**
	 * ����
	 */
	function c_export() {

	}

	/**
	 * ��������
	 */
	function c_batch_update() {

	}

    /**
     * �����嵥�ļ�
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
     * ��ȡ���ṹ�б�
     */
    function c_load_materials_tree() {
        $datas = $this->service->loadTree();
        $datas = $this->_setBomId($datas, '0');

        //�ж��Ƿ��ȡ���Ʒ
//        if(!isset($_REQUEST['no_more'])) {
//	        $moreConfigList = $this->_setBomId($management->getMoreConfigList(), '1');
//	        if(!empty($moreConfigList)) {
//	        	$datas[] = array(
//		        	'id' => '',
//		        	'text' => '���Ʒ����',
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
	 * ��ȡ�����б�
	 * */
    function c_load_config(){
    	$datas = $this->service->loadConfig($_GET['semifinishedId']);

    	exit(json_encode(un_iconv($datas)));
    }
    /**
     * ��ȡԪ��������
     * */
    function c_load_parts(){
    	//BOM�嵥�б��������ϱ���Ϊ�յ�Ԫ�������ݣ�ֻ�е��������õ���ʱ��������
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
     * �������õ���ȡ����
     * */
    function c_load_configuration(){
    	$datas = $this->service->loadParts($_GET['semifinishedId']);
    	exit(json_encode(un_iconv($datas)));
    }

    /**
     * BOM����洢����
     * */
    function c_insert_details(){
    	if(!empty($_POST['datas'])){
    		$ID = $this->service->insertDetails($_POST['datas'],$_POST['FName'],$_POST['CPCode']);
    		exit(json_encode($ID));
    	}

    }
    
    /**
     * BOM����ͳ������ʱ��������
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
     * ͳ������
     * */
    function c_load_statistical(){
    	if(!empty($_GET['finishedId'])){
    		$datas = $this->service->loadStatistical($_GET['finishedId']);
    		exit(json_encode(un_iconv($datas)));
    	}

    }
/**
     * ����Tag��
     */
    public function c_view_tags($return = false) {
        $str = '';
        if (isset($_GET['operation'])) {
            //�������
            if ($_GET['operation'] == 'import') {
                $import = new model_stock_material_management_import();
                $sheets = $import->getSession('SHEETS');
            }

            //�鿴/���õ�����
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
     * ��Ϣ�������
     * @param unknown_type $msg
     */
    public function alertResult($methodName, $val, $isJson = true) {
        if (!$isJson && $val != "") {
            $val = "'{$val}'";
        }
        return "<script>parent.{$methodName}({$val});</script>";
    }
 	/**
     * ��ȡ���롢�鿴���ܵ��ӱ�
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
     * ��ȡ����
     */
    public function c_review_table() {
        $outputArr = array();
        if (isset($_GET['tag']) && isset($_GET['operation'])) {
            $data = array();

            //�������
            if ($_GET['operation'] == 'import') {
                $import = new model_stock_material_management_import();
                $data = $import->getSession('INFOS');
                $data = $data[$_GET['tag']];
            }

            //�鿴����
            if ($_GET['operation'] == 'view') {
                $view = new model_stock_material_management_view();
                $data['detail'] = $view->getSubDetail($_GET['tag'], 'detail');
                $data['config'] = $view->getConfigTitle($_GET['tag']);
            }

            if (count($data) > 0) {
                if (isset($data['detail'])) {
                    $outputArr[] = $this->setMainReviewTableProperties(0, '�����嵥', '', '', 'detail');
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
     * ����Review���������
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
     * �����²�Ʒ
     */
    public function c_create_product_and_save_session() {
//        $newId = 0;

            set_time_limit(0);
            $import = new model_stock_material_management_import();
//            $productType = new model_stock_productinfo_producttype();

            //�����Ʒ������Ƿ��ǳ�Ʒ
//            $postData = mb_iconv($_POST['producttype']);
//            if ($_POST['producttype']['parentId'] == '309') {
//                $postData['parentName'] = "��Ʒ";
//            } else {
//                $postData['parentName'] = "���Ʒ";
//            }
//
//            $postData['orderNum'] = 0;
//            $postData['accountingCode'] = 'KJKM1';
//
//            $newId = $productType->add_d($postData);
//
            //����ɹ�������SESSION��Ϣ
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
	                                //������Ϣ
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

	//ɾ�����Ʒ
    public function c_delete_SF() {
    	if( isset($_POST['id']) && $_POST['id'] ){
    		echo $this->service->delete_SF($_POST['id']);
    	}
    }
	//ɾ����Ʒ
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
//		$Tdatas = $import->getMaterialCode(); //TJCP ��Ʒ   TJBCP ���Ʒ
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
								<td rowspan={$cosl} class='sf_td sf_bg' width='30%'>���Ʒ���</td>
								<td class='sf_td' width='70%'>{$val}</td>
							</tr>
							<tr>
								<td class='sf_td'>
									<input name='pCode".$a++."' type='text'>
								</td>
							</tr>
							<tr>
								<td class='sf_td'>
									���ϱ�ʶ&nbsp;&nbsp;
									<select name='pInfo".$a++."' type='text'>
										<option value='��'>��</option>
										<option value='��'>��</option>
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
									���ϱ�ʶ&nbsp;&nbsp;
									<select name='pInfo".$a++."' type='text'>
										<option value='��'>��</option>
										<option value='��'>��</option>
									</select>
								</td>
							</tr>";
			}

		}

		//<td class='sf_td'>{$CPS}</td>
		$table = "<table width='100%' id='Tab'> " .$table_sf."
					<tr >
					<td class='sf_td sf_bg'>��Ʒ����</td>
					<td class='sf_td'>
						<div id='addCP'>{$CPSN} <a href='#' onclick=addCP('a');>����</a></div>
						<div id='cancelCP' style='display:none;'><input type='text' id='CPSI' readonly><a href='#' onclick=addCP('c');>ȡ��</a></div>
					</td>
					</tr>
					<tr  id='addTd' style='display:none;'>
					<td class='sf_td sf_bg'>��Ʒ���</td>
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
		$Tdatas = $import->getMaterialCode(); //TJCP ��Ʒ   TJBCP ���Ʒ
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
		$yes = $data[0]['pickingInfo'] == '��' ? 'selected' : '';
		$no  = $yes == '' ? 'selected' : '';
		$table = "<table width='100%' id='Tab'>
					<tr >
						<td class='sf_td sf_bg'>���Ʒ����</td>
						<td class='sf_td'>
							<input type='text' value='{$data[0]['name']}'  size='42' id='SFNameVal'>
						</td>
					</tr>
					<tr>
						<td class='sf_td sf_bg'>���Ʒ���</td>
						<td class='sf_td'>
							<input id='SFCode' type='text' value='{$data[0]['code']}'>
						</td>
					</tr>
					<tr>
						<td class='sf_td sf_bg'>���ϱ�ʶ</td>
						<td class='sf_td'>
							<select id='SFInfo'>
								<option value='��' $yes>��</option>
								<option value='��' $no >��</option>
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