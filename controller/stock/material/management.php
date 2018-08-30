<?php
require_once WEB_TOR . 'model/stock/material/management_import.php';
require_once WEB_TOR . 'model/stock/material/management_view.php';
class controller_stock_material_management extends model_stock_material_management {
    public function __construct() {
        parent::__construct();
        $this->show = new show();
        $this->show->path = 'stock/material/';
        
    }
    
    /**
     * 打开view层
     */
    public function c_index() {
        $this->show->display('material-management');
    }
    
    //库存管理显示
    public function c_inventory_management() {
    	$this->show->display('inventory-management');
    }
    
 	public function c_statistics_form() {
 		$this->show->assign('code',$_GET['code']);
 		$this->show->assign('num',$_GET['num']);
 		$this->show->assign('id',$_GET['id']);
    	$this->show->display('statistics-form');
    }
    public function c_import_inventory() {
    	$import = new model_stock_material_management_import();
        $errorList = $import->importBasicCheck($_POST, $_FILES);
        if (count($errorList) == 0 && $import->importInventory($_FILES) == true) {
            exit($this->alertResult('c_import', ''));
        }
       	$msg = $this->processMsg($errorList);
        exit($this->alertResult('alertResult', $msg, false));
    }
    
    /**
     * 获取树结构列表
     */
    public function c_load_materials_tree() {
        $datas = $this->loadTree('-1');
        $datas = $this->_setBomId($datas, '0');
        
        //判断是否获取多产品
//        if(!isset($_REQUEST['no_more'])) {
//	        $moreConfigList = $this->_setBomId($this->getMoreConfigList(), '1');
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
    
    public function c_get_inventory_list() {
    	$page = intval($_POST['page']);
    	$prepage = intval($_POST['rows']);
    	
    	$datas = $this->getInventoryList($page, $prepage);
    	
    	header('Content-type:application/json');
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
     * 获取配置单列表
     */
    public function c_load_configuration_list() {
        $data = array();
        if ($_GET['id']) {
            $data = $this->loadConfigurationList($_GET['id'], $_POST['page'], $_POST['rows']);
        }
        
        header('Content-type:application/json');
        exit(json_encode(un_iconv($data)));
    }
    
    /*
     * 获取发料清单
     */
    public function c_load_send_tree() {
        $view = new model_stock_material_management_view();
        
        $datas = $view->getStockSendTree();
        
        header('Content-type:application/json');
        exit(json_encode(un_iconv($datas)));
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
    
    public function c_view_more_tags() {
    	if(isset($_POST['val']) && $_POST['val']){
	    	$FIEX = explode('^', $_POST['val']);
	    	foreach ($FIEX as $Ekey => $Eval){
	    		$FIEXA = explode('&', $Eval);
	    		$FIEXT[$FIEXA[0]] =  $FIEXA[1];
	    	}		
	    }
	    
	    if(isset($_POST['code']) && $_POST['code']){
	    	$codes = $this->getCode($_POST['code']);
	    	if(!count($codes)){
	    		echo 0;
	    		exit();
	    	}
	    	$select = "<select onchange='statistics_form(".$_POST['code'].",".$_POST['num'].",this[selectedIndex].value)'>";
	    	foreach($codes as $val){
	    		if(!$id){
	    			$id = $val['id'];
	    		}
	    		if($val['id'] == $_POST['cid']){
	    			$select .= "<option value ='{$val['id']}' selected>{$val['name']}</option> ";
	    			$id = $val['id'];
	    		}else{
		    		$select .= "<option value ='{$val['id']}'>{$val['name']}</option> ";
	    		}
	    		
	    	}
	    	$select .= "</select>";
	    	$table1 .="<tr style='text-align:center;'>";
    		$table1 .="<td class='sf_td ' style='width:30px;' colspan='2'>成品名：</td>";
    		$table1 .="<td class='sf_td ' style='width:30px;' colspan='3' align='left'>$select</td>";
    		$table1 .="</tr>";
	    }

	    $equipment = new model_purchase_contract_equipment();
    	if((isset($_POST['id']) && $_POST['id']) || $id){
    		$id = $id ? $id : $_POST['id'];
    		$datas = $this->getFinishedData($id);

    	$table = "<table style='word-break:break-all' width='100%'>";
    	$table .= $table1;
    	$table .="<tr style='text-align:center;'>";
    	$table .="<td class='sf_td sf_bg' style='width:30px;'>序号</td>";
    	$table .="<td class='sf_td sf_bg' style='width:20px;'><input id='selectC' type='checkbox' onclick='selectChecked();' checked='checked'/></td>";
    	$table .="<td class='sf_td sf_bg' style='width:60px;'>编码</td>";
    	$table .="<td class='sf_td sf_bg'>元件名称</td>";
    	$table .="<td class='sf_td sf_bg'>规格</td>";
    	$table .="<td class='sf_td sf_bg'>封装</td>";
    	$table .="<td class='sf_td sf_bg'>品牌</td>";
    	foreach($datas['SF'] as $key_sf => $val_sf){
	    	if(isset($FIEXT[$val_sf['id']]) && $FIEXT[$val_sf['id']]){
	    		$tval = $FIEXT[$val_sf['id']];
	    	}else{
	    		$tval = $val_sf['num'];//带出默认值
	    	}
    		$table .="<td class='sf_td sf_bg button_r' style='width:45px;'>
    					{$val_sf['name']}
    					<br/>";
    		if(isset($_POST['num']) && $_POST['num']){
    			$table .="[ {$_POST['num']} ]";
    		}else{
    			$table .="<input type='text' size=4 class='but_r' sf_id='{$val_sf['id']}' value='{$tval}' >";
    		}
    		$table .="</td>";
    	}
    	$table .="<td class='sf_td sf_bg' style='width:50px;'>合计用量</td>";
    	$table .="<td class='sf_td sf_bg' style='width:40px;'>库存数<br>( oa )</td>";
    	$table .="<td class='sf_td sf_bg' style='width:40px;'>库存数<br>(生产)</td>";
    	$table .="<td class='sf_td sf_bg' style='width:40px;'>在途数</td>";
    	$table .="<td class='sf_td sf_bg' style='width:40px;'>领料数</td>";
    	$table .="<td class='sf_td sf_bg' style='width:40px;'>缺料数</td>";
    	$table .="<td class='sf_td sf_bg' style='width:40px;'>采购数</td>";
    	$table .="</tr>";
    	$i = 0;
    	$IData = $this->getInventoryData();
    	foreach($datas['DS'] as $key => $val){
    		$TNum = $issue = $UnIssue = $inventory = $ANum = $T = 0;
    		$inventory = ($datas['IN'][$datas['DS'][$i]['code']])?$datas['IN'][$datas['DS'][$i]['code']]:0;
    		if($inventory > '0'){
    			$disabled = '';
    			$checked = 'checked';
    		}else{
    			$disabled = 'disabled';
    			$checked = '';
    		}
    		$transit = $equipment->getEqusOnway( array('productNumb'=>$datas['DS'][$i]['code']));
    		$table .="<tr>";
	    	$table .="<td class='sf_td sf_bg' style ='text-align:center;'>". ($i+1) ."</td>";
	    	$table .="<td class='sf_td'><input name='items' type='checkbox' value='{$datas['DS'][$i]['code']}' $checked $disabled/></td>";
	    	$table .="<td class='sf_td'>{$datas['DS'][$i]['code']}</td>";
	    	$table .="<td class='sf_td'>{$datas['DS'][$i]['name']}</td>";
	    	$table .="<td class='sf_td'>{$datas['DS'][$i]['model']}</td>";
	    	$table .="<td class='sf_td'>{$datas['DS'][$i]['packaging']}</td>";
	    	$table .="<td class='sf_td'>{$datas['DS'][$i]['factory']}</td>";
	    	foreach($datas['SF'] as $key_sf1 => $val_sf1){
	    		$ANum = $datas['DT'][$datas['DS'][$i]['code']][$val_sf1['id']];
	    		if(isset($_POST['num']) && $_POST['num']){
	    			$T = $_POST['num'];
	    		}else{
					$T = ($FIEXT[$val_sf1['id']])?$FIEXT[$val_sf1['id']]:$val_sf1['num'];	    		
	    		}
//		    	foreach($datas['DT'] as $key_dt => $val_dt){
		    		$table .="<td class='sf_td' style='text-align:center;'>{$datas['DT'][$datas['DS'][$i]['code']][$val_sf1['id']]}</td>";
//		    	}
				$TNum += $ANum * $T;
	    	}
	    	$INum = $IData[$datas['DS'][$i]['code']];
	    	$INum = ($INum)?$INum:0;
	    	$TNum = ($TNum)?$TNum:0;
	    	if ((($inventory+$INum) - $TNum) >= 0){
	    		$issue = $TNum;
	    		$UnIssue = 0;
	    	}else{
	    		$issue = ($inventory + $INum);
	    		$UnIssue = ($TNum - ($inventory+$INum))?($TNum - ($inventory+$INum)):0;
	    	}
	    	$table .="<td class='sf_td'>{$TNum}</td>";
	    	$table .="<td class='sf_td'>{$inventory}</td>";
	    	$table .="<td class='sf_td'>{$INum}</td>";
	    	$table .="<td class='sf_td'>{$transit}</td>";
	    	$table .="<td class='sf_td'><span id='issue".$datas['DS'][$i]['code']."'>{$issue}</span></td>";
	    	$table .="<td class='sf_td' ".(($UnIssue>0)?'style=background-color:#EEB4B4':'').">
	    					<span id='lack".$datas['DS'][$i]['code']."'>{$UnIssue}</span></td>";
	    	$table .="<td class='sf_td'><input type='text' size='4'></td>";
	    	$table .="</tr>";
	    	$i++;
    	}
    	$table .="</table>";
    	
    	}
//    	$productIds = explode('_', $_REQUEST['current_product_id']);
//    	$sendListId = $productIds[0];
//    	
//    	$view = new model_stock_material_management_view();
//    	$datas = $view->getProductNameForConfigId($sendListId);
//    	
//    	if(!$datas) {
//    		exit();
//    	}
//    	
//    	$str = '';
//    	foreach($datas as $id => $data) {
//    		$_GET['current_product_id'] = $id;
//    		$str .= '<div class="bom_list_config"> <span class="bom_list_name">' . $data . ':</span>' . $this->c_view_tags(true) . '</div>';
//    	}
//    	
    	//exit(un_iconv($table));
    	echo $table;
    }
    
	public function c_view_history_detail() {
//    	if(isset($_POST['val']) && $_POST['val']){
//	    	$FIEX = explode('^', $_POST['val']);
//	    	foreach ($FIEX as $Ekey => $Eval){
//	    		$FIEXA = explode('&', $Eval);
//	    		$FIEXT[$FIEXA[0]] =  $FIEXA[1];
//	    	}		
//	    }
	    $equipment = new model_purchase_contract_equipment();
    	if(isset($_POST['id']) && $_POST['id']){
    		$datas = $this->getHistoryDetail($_POST['id']);

    	$table = "<table style='word-break:break-all' width='100%'>";
    	$table .="<tr style='text-align:center;'>";
    	$table .="<td class='sf_td sf_bg' style='width:30px;'>序号</td>";
    	$table .="<td class='sf_td sf_bg' style='width:20px;'><input id='selectC' type='checkbox' onclick='selectChecked();' checked='checked'/></td>";
    	$table .="<td class='sf_td sf_bg' style='width:60px;'>编码</td>";
    	$table .="<td class='sf_td sf_bg'>元件名称</td>";
    	$table .="<td class='sf_td sf_bg'>规格</td>";
    	$table .="<td class='sf_td sf_bg'>封装</td>";
    	$table .="<td class='sf_td sf_bg'>品牌</td>";
    	foreach($datas['SF'] as $key_sf => $val_sf){
    		$table .="<td class='sf_td sf_bg button_r' style='width:40px;'>
    					{$val_sf['name']}
    					<br/><br/>
    					[ {$datas['EX'][$val_sf['id']]} ]
    				</td>";
    	}
    	$table .="<td class='sf_td sf_bg' style='width:50px;'>合计用量</td>";
    	$table .="<td class='sf_td sf_bg' style='width:40px;'>库存数<br>( oa )</td>";
    	$table .="<td class='sf_td sf_bg' style='width:40px;'>库存数<br>(生产)</td>";
    	$table .="<td class='sf_td sf_bg' style='width:40px;'>在途数</td>";
    	$table .="<td class='sf_td sf_bg' style='width:40px;'>领料数</td>";
    	$table .="<td class='sf_td sf_bg' style='width:40px;'>发料数</td>";
    	$table .="<td class='sf_td sf_bg' style='width:40px;'>缺料数</td>";
    	$table .="<td class='sf_td sf_bg' style='width:40px;'>补料数</td>";
    	$table .="<td class='sf_td sf_bg' style='width:40px;'>采购数</td>";
    	$table .="</tr>";
    	$i = 0;
    	$IData = $this->getInventoryData();
    	foreach($datas['DS'] as $key => $val){
    		$TNum = $issue = $UnIssue = $inventory = $ANum = $T = 0;
    		$inventory = ($datas['IN'][$datas['DS'][$i]['code']])?$datas['IN'][$datas['DS'][$i]['code']]:0;
    		if($inventory > '0'){
    			$disabled = '';
    			$checked = 'checked';
    		}else{
    			$disabled = 'disabled';
    			$checked = '';
    		}
    		$transit = $equipment->getEqusOnway( array('productNumb'=>$datas['DS'][$i]['code']));
    		$table .="<tr>";
	    	$table .="<td class='sf_td sf_bg' style ='text-align:center;'>". ($i+1) ."</td>";
	    	$table .="<td class='sf_td'><input name='items' type='checkbox' $checked $disabled/></td>";
	    	$table .="<td class='sf_td'>{$datas['DS'][$i]['code']}</td>";
	    	$table .="<td class='sf_td'>{$datas['DS'][$i]['name']}</td>";
	    	$table .="<td class='sf_td'>{$datas['DS'][$i]['model']}</td>";
	    	$table .="<td class='sf_td'>{$datas['DS'][$i]['packaging']}</td>";
	    	$table .="<td class='sf_td'>{$datas['DS'][$i]['factory']}</td>";
	    	
	    	foreach($datas['SF'] as $key_sf1 => $val_sf1){
				if($val['semifinishedId'] == $val_sf1['id']){
					$table .="<td class='sf_td' style='text-align:center;'>{$val['total']}</td>";
					$TNum += $val['total'] * $datas['EX'][$val_sf1['id']];
				}else{
					$table .="<td class='sf_td' style='text-align:center;'></td>";
				}
				$inventory = ($datas['IN'][$datas['DS'][$i]['code']])?$datas['IN'][$datas['DS'][$i]['code']]:0;
	    	}
	    	$INum = $IData[$datas['DS'][$i]['code']];
	    	$INum = ($INum)?$INum:0;
	    	$TNum = ($TNum)?$TNum:0;
	    	if ((($inventory+$INum) - ($TNum - $datas['DS'][$i]['issueNum'])) >= 0){
	    		$issue = ($TNum - $datas['DS'][$i]['issueNum']);
	    		$UnIssue = 0;
	    		$fedBatch = 0;
	    	}else{
	    		$issue = ($inventory+$INum);
	    		$UnIssue = ($TNum - ($inventory+$INum))?($TNum - ($inventory+$INum)):0;
	    		$pN = $TNum - $datas['DS'][$i]['pickingNum'];
	    		$fedBatch = (($inventory+$INum) > $pN)?$pN:($inventory+$INum);
	    	}
	    	$table .="<td class='sf_td'>{$TNum}</td>";
	    	$table .="<td class='sf_td'>{$inventory}</td>";
	    	$table .="<td class='sf_td'>{$INum}</td>";
	    	$table .="<td class='sf_td'>{$transit}</td>";
	    	$table .="<td class='sf_td'>{$datas['DS'][$i]['pickingNum']}</td>";
	    	$table .="<td class='sf_td'>{$datas['DS'][$i]['issueNum']}</td>";
	    	$table .="<td class='sf_td' ".(($UnIssue>0)?'style=background-color:#EEB4B4':'').">{$UnIssue}</td>";
	    	$table .="<td class='sf_td'><input type='text' size='4' value='{$fedBatch}' id='issue".$datas['DS'][$i]['code']."'></td>";
	    	$table .="<td class='sf_td'><input type='text' size='4' id='lack".$datas['DS'][$i]['code']."'></td>";
	    	$table .="</tr>";
	    	$i++;
    	}
    	$table .="</table>";
    	
    	}
    	echo $table;
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
     * 获取属性
     */
    public function c_load_attribute_name() {
        $attributeNameList = $this->attributeName();
        $str = "";
        foreach ($attributeNameList as $key => $value) {
            $str .= "<option value='{$key}' >{$value}</option>";
        }
        exit(un_iconv($str));
    }

    public function c_view_config_list_info() {
    	$view = new model_stock_material_management_view();
    	$ids = explode('_', $_POST['current_product_id']);
    	$ids = $view->getProductNameForConfigId($ids[0]);
    	
    	unset($_POST['current_product_id']);
    	$id = array();
    	foreach($ids as $pid => $d) {
    		$id[] = $pid;
    	}
    	$_POST['product_ids'] = implode(',', $id);
    	
    	$this->c_view_config_info();
    }
    
    //配置清单的显示
    public function c_view_config_info() {
        $import = new model_stock_material_management_import();
        $view = new model_stock_material_management_view();
        $import->initAddConfigTagCount();
        
        $oneId = false;
        
        if(isset($_POST['current_product_id'])) {
        	$oneId = explode('_', $_POST['current_product_id']);
        	$oneId = $oneId[0];
        }
        
        //处理多产品的显示
        $product_ids = $_POST['product_ids'] ? $_POST['product_ids'] : '';
        $onePid = $_POST['onePid'] ? $_POST['onePid'] : 0;
        if ($product_ids) {
            $product_ids = trim($product_ids, ',');
            $ids = explode(',', $product_ids);
        } else {
            $product_ids = $oneId;
            $arr = array();
            $arr[] = $oneId;
            $ids = $arr;
        }
        
        //获取产品名字
        $product_name = array();
        if ($oneId) {
            $product_name[$oneId] = $view->getProductName($oneId);
        } else {
            foreach ($ids as $key => $pname) {
                $product_name[$pname] = $view->getProductName((int)$pname);
            }
        }
        
        if (isset($_POST['current_product_id']) || count($ids) > 0) {
            foreach ($ids as $key => $value) {
                if (isset($_POST['onePid']) && $onePid != 0) {
                    if ($onePid == $value) {
                        $m = $key;
                        $vpid = $value;
                    }
                } else {
                    $m = 0;
                }
            }
            
            $ative_pid = $oneId ? $oneId : (int) $ids[$m];
            
            $view = new model_stock_material_management_view();
            
            $tags = $view->getRelevanceByProductId($ative_pid);
            
            $main = "";
            if (count($tags) > 0) {
                $colspan = 11;
                $tag = "";
                $detailInfo = "";
                $isShow = "block";
                $bgColor = '#B0C4DE';
                $pBgColor = '#B9B9FF';
                $hbt = "";
                //添加多产品的按钮
                
                $hbt .= "<div>";
                foreach ($product_name as $pid => $pname) {
                    if (!isset($vpid)) {
                        $vpid = $pid;
                    }
                    if ($vpid == $pid) {
                        $hbt .= "<span class='hbt_left'><input style='background:{$pBgColor}' class='productOne' onclick=\"choseOneProduct('{$pid}','{$product_ids}')\" type='button' pid='{$pid}' value='{$pname}' /></span>";
                    } else {
                        $hbt .= "<span class='hbt_left'><input class='productOne' onclick=\"choseOneProduct('{$pid}','{$product_ids}')\" type='button' pid='{$pid}' value='{$pname}' /></span>";
                    }
                }
                
                $hbt .= " <span style='float:right;padding-right:20px;' class='hbt_right'>";
                $hbt .= " <input onclick='showConfigMsg(" . json_encode($ids) . ")' type='submit' value='保存'/>";
                $hbt .= " <input onclick='countMeterials(" . json_encode(un_iconv($product_name)) . ")' type='button' value='统计用料' />";
                $hbt .= "<input onclick='closePublicDialog();' type='reset' value='关闭'/>";
                $hbt .= " </span>";
                $hbt .= " </div>";
                //头部选项
                //				$tag .= "<input id='add_product' name='add_product' type='button' onclick='openAddConifgInfoList()' style='cursor: pointer;' value='添加'>";
                foreach ($tags as $id => $tagName) {
                    $tag .= "<input id='config_tag_{$id}' name='config_tag[]' type='button' onclick='changeConfigTab($id);' style='cursor: pointer; background:{$bgColor};' value='{$tagName}'>";
                    
                    //包含层
                    $detailInfo .= "
						<div id='div_config_{$id}' name='div_config[]' style='width:100%; display:{$isShow};'  >
						<table cellpadding='0' cellspacing='0' style='width:100%;' >	
					";
                    
                    //元件表格内容
                    $details = $view->getSubDetail($id, 'detail');
                    
                    if (count($details) > 0) {
                        $detailInfo .= $this->normalDetailTableHeader($colspan);
                        foreach ($details as $detail) {
                            $detailInfo .= $this->markDetailTable($id, $detail);
                        }
                    }
                    
                    $detailInfo .= "</table>";
                    
                    //配置表格内容
                    $configTags = $view->getConfigTitle($id);
                    if (count($configTags) > 0) {
                        $detailInfo .= "
							<table cellpadding='0' cellspacing='0' style='width:100%;' >
						";
                        foreach ($configTags as $configTag) {
                            $detailInfo .= $this->normalConfigTableHeader($configTag, $colspan - 4);
                            $configDetails = $view->getSubDetail($configTag['id'], 'config');
                            foreach ($configDetails as $configDetail) {
                                $detailInfo .= $this->markConfigTable($id, $configDetail);
                            }
                        }
                        $detailInfo .= "</table>";
                    }
                    
                    $detailInfo .= "</div>";
                    $isShow = 'none';
                    $bgColor = 'buttonface';
                }
                $button = "<div id='div_button_config' width='100%' style='text-align:center;'>";
                $button .= "<input type='hidden' name='pid' value='" . $ative_pid . "'/>";
                $button .= "<input type='hidden' name='productName' value='" . $product_name . "'/>";
                $button .= "<input onclick='showConfigMsg(" . json_encode($ids) . ")' type='submit' value='保存'/>";
                $button .= "<input onclick='countMeterials(" . json_encode($product_name) . ")' type='button' value='统计用料'/>";
                $button .= "<input onclick='closePublicDialog();' type='reset' value='关闭'/>";
                $button .= "</div>";
                
                
                $htmlLine = "<hr size='1' align='left' width='100%' color='black' />";
                $header = "<div id='div_header_config' style='width:100%;' >{$tag}<br/></div>";
                $body = "<div id='div_body_config' style='width:100%;' >{$detailInfo}</div>";
                $footer = "<div id='div_footer_config' style='width:100%;' ></div>";
                $mainDiv = "<div id='div_main_config' style='width:100%;' >{$hbt}{$header}{$htmlLine}{$body}{$footer}{$button}</div>";
                $main = "<form  id='save_config_list_form' method='post' target='public_iframe' >{$mainDiv}</form>";
            } else {
                return false;
            }
            exit(un_iconv($main));
        }
    }
    
    /**
     * 保存配置到数据表
     * 
     */
    public function c_save_config_list_form() {
        //这里功能需要做调整，关键是保存的数据怎么来源，session还是表，先实现保存功能
        $product_ids = $_POST['product_id'];
        
        if (count($product_ids) > 0) {
            $view = new model_stock_material_management_view();
            $import = new model_stock_material_management_import();
            $productType = new model_stock_productinfo_producttype();
            
            foreach ($product_ids as $product_id) {
                $product_id = (int) $product_id;
                $tags = $view->getRelevanceByProductId($product_id);
                
                if (count($tags) > 0) {
                    //先检测配置表中是否已经保存，已经保存的话先删除或更新
                    $view->delBom($product_id);
                    
                    $details = array();
                    foreach ($tags as $key => $tag) {
                        //把数据插入配置的relevant表
                        $newSheetId = $import->saveRelevanceBom($tag, $key, $product_id);
                        
                        //获取sheet中的详细detail数据
                        $details = $view->getSubDetail($key, 'detail');
                        
                        //插入detail到配置表中的detail表
                        $flag = $import->saveDetailBom($newSheetId, $details, $product_id);
                        
                        //插入config-detail到配置表的detail表
                        $config = $view->getConfig($key);
                        
                        if (count($config) > 0) {
                            
                            foreach ($config as $k => $v) {
                                $datas = $view->getSubDetail($v['id'], 'config');
                                $config[$k]['config'] = $datas;
                                
                            }
                            $import->saveConfigBom($newSheetId, $config, $product_id); //先保存新的配置config		
                        }
                    }
                    
                }
            }
            
            exit($this->alertResult('showConfigMsg', ''));
            
        } else {
            //请选择产品
            $msg = "保存失败，请检查或与管理员联系";
            exit($this->alertResult('alertResult', $msg, false));
        }
    }
    
    /* 确认发料 */
    public function c_confirmSendStock() {
        $import = new model_stock_material_management_import();
        $view = new model_stock_material_management_view();
        $inventory = new model_stock_inventoryinfo_inventoryinfo();
        
        $pid_list = explode(',', $_POST['pids']);
        $loss_rate = intval($_POST['loss_rate']);
        $config_name = un_iconv(trim($_POST['config_name']), 'utf-8', 'gbk');
        $send_id = 0;
        $datas = $_POST['data']['rows'];
                
        //如果多产品则, 先插入一条配置详细信息到bom_detail_send
        if(count($pid_list) > 1) {
        	$send_detail = $import->saveDetailSend($config_name);
        	$send_id = $send_detail['id'];
        }
        
        $stock_arr = array();  //记录配件是否第一次出现
		$res = array();
		foreach($datas as $data) {
			if(empty($data['stock_code']) || !isset($data['outStockNum'])) {
				continue;
			}			
			
			$is_first = true;
			
			foreach($data as &$field) {
				$field = un_iconv($field, 'utf-8', 'gbk');
			}
			
			foreach($pid_list as $pids_data) {
				$pids = explode(':', $pids_data); //分割post字符串
	        	$product_id = $pids[0]; //获取产品id
	        	
	        	if(!$is_first) {
	        		$data['outStockNum'] = 0;
	        	}
	        	
	        	$data['stock_total'] = $data[$product_id];
	        	$data['sendid'] = $send_id;
	        	$data['status'] = 1;
	        	$data['pid'] = $product_id;
	        	$data['create_date'] = isset($send_detail) ? $send_detail['creat_time'] : false;
	            $data['edit_date'] = isset($send_detail) ? $send_detail['edit_time'] : false;
	            $data['sunhao'] = $loss_rate;
	            
	        	$inventory->updateStockNum($data['stock_code'], $data['outStockNum'], 'outstock');
	        	
	        	$res[] = $data;
	        	
	        	$is_first = false;
			}
		}
				
		$return = $import->saveDetailBom(FALSE, $res); //保存出料数据
		
		$datas = array(
			'data' => $datas,
			'id' => $product_id . '_' . (isset($send_detail) ? $send_detail['creat_time'] : $return[1]) . '_' . $send_id
		);
        
        header("Content-type:application/json;");
        exit(json_encode($datas));
    }
    
    /* 确认再次发料 */
    public function c_confirmSecondSendStock() {
        $import = new model_stock_material_management_import();
        $view = new model_stock_material_management_view();
        $inventory = new model_stock_inventoryinfo_inventoryinfo();
        
        $ids = explode('_', $_POST['id']);
        $pid = intval($ids[0]);
        $date = $ids[1];
        
        //统计查询
        $datas = $view->getDetailInfo($pid, '`create_date` = \'' . $date . '\' AND `outStockNum` - `stock_total` < 0');
        //再发料数组
        $result = array();
        
        foreach ($datas as $data) {
            //库存为0不发
            if ($data['actNum'] <= 0) {
                continue;
            }
            
            //计算本次发多少
            $changeNum = 0;
            if ($data['actNum'] > $data['stock_total'] - $data['outStockNum']) {
                $changeNum = $data['stock_total'] - $data['outStockNum'];
                $data['outStockNum'] = $data['stock_total'];
            } else {
                $changeNum = $data['actNum'];
                $data['outStockNum'] += $data['actNum'];
            }
            
            $view->updateStock($data); //更新发料信息
            $inventory->updateStockNum($data['stock_code'], $changeNum, 'outstock'); //更新库存
            
            $result[] = $data;
        }
    }
    
    public function c_exists_config() {
    	$name = $_POST['name'];
    	
    	$result = array(
    		'result' => false,
    	);
    	
    	$view = new model_stock_material_management_view();
    	$result['data'] = $view->existsSendListConfig($name);
    	
    	if(!empty($result['data'])) {
    		$result['result'] = true;
    	}
    	
    	header('Content-type:application/json');
    	exit(json_encode(un_iconv($result)));
    }
    
    public function c_add_config_list() {
        $data = array();
        if ($_FILES) {
            $import = new model_stock_material_management_import();
            $data = $import->addConfigList($_FILES);
        }
        exit($this->alertResult('processAddConfigInfoResult', json_encode(un_iconv($data))));
    }
    
    public function c_update_detail_mark() {
        $result = $this->updateDetailMark($_POST['id'], $_POST['mark']);
        echo $result ? true : false;
        exit;
    }
    
    public function c_edit_sub_review_table_detail() {
        $flag = 'fail';
        if ($_POST) {
            $import = new model_stock_material_management_import();
            
            if ($import->editReviewDetail($_POST['stock_code'], $_POST['stock_index'], $_POST['main_type'], $_POST['main_tag'])) {
                $flag = 'successful';
            }
        }
        
        exit($flag);
    }
    
    //导出bom功能
    public function c_export_excel() {
        $product_id = $_GET['product_id'];
        $this->export_excel($product_id);
        
    }
    
    //导出物料清单（已统计）
    public function c_export_materials() {
        $pids = $_GET['pids'];
        $ext = $_GET['ext'];
        $this->export_materials($pids, $ext);
    }
    
    //导出BOM管理成品元器件信息
    public function c_statistics_export() {
    	$equipment = new model_purchase_contract_equipment();
    	$id = $_GET['id'];
    	$datas = $this->getFinishedData($id);
    	$sql = "select name from oa_stock_material_finished where id =".$id;//物料名称
    	$rs = $this->query($sql);
    	$rs = $this->fetch_array($rs);
		if(!empty($rs)){
			$filename = preg_replace('#[\s\?"><|*/:\\\\]#', '_', $rs['name']);
		}else{
			$filename = "元器件信息";
		}
    	$thArr = array(//标题
    			"序号",
    			"编码",
    			"元件名称",
    			"规格",
    			"封装",
    			"品牌"
    	);
    	foreach($datas['SF'] as $key_sf => $val_sf){
    		array_push($thArr, $val_sf['name']."<".$val_sf['num'].">");
    	}
    	array_push($thArr, "合计用量","库存数(oa)","库存数(生产)","在途数","领料数","缺料数","采购数");
		$rowdatas = array();
    	$i = 0;
    	$IData = $this->getInventoryData();

    	foreach($datas['DS'] as $key => $val){
    		$tempArr = array();
    		array_push($tempArr, $i+1,$val['code'],$val['name'],$val['model'],$val['packaging'],$val['factory']);
    		
    		$TNum = $issue = $UnIssue = $inventory = $ANum = $T = 0;
    		$inventory = ($datas['IN'][$datas['DS'][$i]['code']])?$datas['IN'][$datas['DS'][$i]['code']]:0;		 		
    		$transit = $equipment->getEqusOnway( array('productNumb'=>$datas['DS'][$i]['code']));
    		
    		foreach($datas['SF'] as $key_sf1 => $val_sf1){
    			$ANum = $datas['DT'][$val['code']][$val_sf1['id']];
				$T = $val_sf1['num'];
				$TNum += $ANum * $T;
				array_push($tempArr, $datas['DT'][$val['code']][$val_sf1['id']]);
    		}
    		$INum = $IData[$val['code']];
    		$INum = ($INum)?$INum:0;
    		$TNum = ($TNum)?$TNum:0;
    		if ((($inventory+$INum) - $TNum) >= 0){
    			$issue = $TNum;
    			$UnIssue = 0;
    		}else{
    			$issue = ($inventory + $INum);
    			$UnIssue = ($TNum - ($inventory+$INum))?($TNum - ($inventory+$INum)):0;
    		}
    		array_push($tempArr, $TNum,$inventory,$INum,$transit,$issue,$UnIssue);
			array_push($rowdatas, $tempArr);
    		$i++;
    	}
    	$this->statistics_export($thArr,$rowdatas,$filename);  
    }
    
    /**
     * 最后实现的统计功能
     * Enter description here ...
     */
    public function c_goCount() {
        $view = new model_stock_material_management_view();
        $pids = $_GET['pids'];
        $res = array(); //存最后的数组
        $arr = explode(',', $pids);
        $n = 0;
        foreach ($arr as $key => $value) {
            $newValue = explode(':', $value);
            $newValue[1] = explode('-', $newValue[1]);
            foreach ($newValue[1] as $k => $rid) {
                $new = explode('_', $rid);
                $res[$n]['rid'] = (int) $new[0];
                $relevance = $view->getRelevanceName($res[$n]['rid']);
                $res[$n]['relevance'] = $relevance;
                $res[$n]['numTotal'] = (int) $new[1];
                $res[$n]['product_id'] = (int) $newValue[0];
                $pname = $view->getProductName($res[$n]['product_id']);
                $res[$n]['product_name'] = $pname;
                $n += 1;
            }
        }
        header('Content-type:application/json');
        exit(json_encode(un_iconv($res)));
    }
    
    //获取子数据
    public function c_goCountSub() {
        $view = new model_stock_material_management_view();
        $rid = (int) $_GET['rid'];
        $mNum = (int) $_GET['num'];
        $datas = $view->getDetailInfoByRid($rid, $mNum);
        header('Content-type:application/json');
        exit(json_encode(un_iconv($datas)));
    }
    
    /* 发料统计 */
    public function c_mergeDataSub() {
        $view = new model_stock_material_management_view();
        $pids = $_GET['pids'];
        $res = array(); //存最后的数组
        $arr = explode(',', $pids);
        foreach ($arr as $key => $value) {
            $newValue = explode(':', $value);
            $newValue[1] = explode('-', $newValue[1]);
            foreach ($newValue[1] as $k => $rid) {
                $new = explode('_', $rid);
                $datas = $view->getDetailInfoByRid((int) $new[0], (int) $new[1]);
                                    
                foreach ($datas as $data) {
                    if (!$data || !$data['stock_code']) {
                        continue;
                    }
                    
                    if (!isset($res[$data['stock_code']])) {
                        $res[$data['stock_code']] = $data; //如果配件第一次出现, 插入到res
                        $res[$data['stock_code']][(int)$newValue[0]] = (int)$data['stock_total'];
                    } else {
                        //配件已经被使用过, 合并数据
                        $res[$data['stock_code']]['stock_total'] += $data['stock_total'];
                        $res[$data['stock_code']]['shortage'] = ($shortage = $res[$data['stock_code']]['stock_total'] - $data['actNum']) > 0 ? $shortage : 0;
                        $res[$data['stock_code']][(int)$newValue[0]] += (int)$data['stock_total'];
                    }
                    
                    $stock =& $res[$data['stock_code']];
                    $stock['outStockNum'] = $stock['stock_total'] - $stock['shortage'];
                }
            }
        }
        
        $res = array_values($res);
        header('Content-type:application/json');
        exit(json_encode(un_iconv($res)));
    }
    
    /* 再发料统计 */
    public function c_mergeDataSubAgain() {
        $view = new model_stock_material_management_view();
        
        $ids = explode('_', $_POST['id']);
        $pid = intval($ids[0]);
        $date = $ids[1];
        
        //统计查询
        $datas = $view->getDetailInfo($pid, '`create_date` = \'' . $date . '\' AND `outStockNum` - `stock_total` < 0');
        $result = array();
        
        foreach ($datas as $data) {
            //库存为0不发
            if ($data['actNum'] <= 0) {
                continue;
            }
            
            //计算本次发多少
            if ($data['actNum'] > $data['stock_total'] - $data['outStockNum']) {
                $data['outStockNum'] = $data['stock_total'] - $data['outStockNum'];
                $data['shortage'] = 0;
            } else {
                $data['shortage'] = $data['stock_total'] - $data['outStockNum'] - $data['actNum'];
                $data['outStockNum'] = $data['actNum'];
            }
            
            $result[] = $data;
        }
        
        header('Content-type:application/json');
        echo json_encode(un_iconv($result));
        exit();
    }
    
    //查找配置表是否存在pid
    public function c_search_config_pid() {
        $pids = $_POST['pid'];
        $num = count($pids);
        
        $tmp_pids = array();
        foreach($pids as $pid => $pname) {
        	$tpids = explode('_', $pid);
        	$tmp_pids[$tpids[0]] = '';
        }
        $pids = $tmp_pids;
        
        if ($pids != '') {
            $view = new model_stock_material_management_view();
            foreach ($pids as $pid => $pname) {
                $pid = (int) $pid;
                $rs = $view->getProductId($pid);
                if (!$rs) {
                    echo '0';
                    exit;
                }
            }
            $str = '';
            foreach ($pids as $pid => $pname) {
                $relevance = $view->getBomRelevanceByProductId($pid);
                $str .= "<div class='pbox'>";
                $str .= "<table width='90%' align='center' cellpadding='2'>";
                $str .= "<tr>";
                $str .= "<th colspan='2' pid='" . $pid . "' class='pro_name'><span style='color:red; font-size:14px;'>{$pname}</span></th>";
                $str .= "</tr>";
                $str .= "<tr>";
                $str .= "<th width='60%'>板卡名</th>";
                $str .= "<th>需求数</th>";
                $str .= "</tr>";
                foreach ($relevance as $rid => $rname) {
                    $str .= "<tr>";
                    $str .= "<td width='60%' class='relevance_name_{$pid}' rid='" . $rid . "'>{$rname}</td>";
                    $str .= "<td><input type='text' class='relevance_num_{$pid}_{$rid}' name='num' style='width:60px; text-align:center;' value='1' /></td>";
                    $str .= "</tr>";
                }
                $str .= "</table>";
                $str .= "</div>";
            }
            echo un_iconv($str);
        } else {
            //居然没有pid
        }
        
    }
    
    public function c_update_Repertory() {
        $view = new model_stock_material_management_view();
        $pids = $_GET['pids'];
        $arr = explode(',', $pids);
        foreach ($arr as $key => $value) {
            $newValue = explode(':', $value);
            $newValue[1] = explode('-', $newValue[1]);
            foreach ($newValue[1] as $k => $rid) {
                $new = explode('_', $rid);
                $datas = $view->updateRepertory((int) $new[0], (int) $new[1]);
            }
        }
        //exit(json_encode(un_iconv($res)));
    }
    
    
    
    
    public function c_update_excel() {
        $product_id = $_GET['product_id'];
        $view = new model_stock_material_management_view();
        //先删除原来pid对应的数据
        $view->deleteProduct($product_id, false);
        //重新插入
        $import = new model_stock_material_management_import();
        
        if ($import->processImport($_FILES) == true) {
            //如果成功，保存SESSION信息
            if ($product_id) {
                $sheets = $import->getSession('SHEETS');
                foreach ($sheets as $key => $tag) {
                    $newSheetId = $import->saveRelevance($tag, $key, $product_id);
                    if ($newSheetId) {
                        $datas = $import->getSession('INFOS');
                        
                        //零固件
                        if (isset($datas[$key]['detail'])) {
                            $flag = $import->saveDetail($newSheetId, $datas[$key]['detail'], $product_id);
                            
                            if ($flag) {
                                //配置信息
                                if (isset($datas[$key]['config']) && count($datas[$key]['config']) > 0) {
                                    $import->saveConfig($newSheetId, $datas[$key]['config'], $product_id);
                                }
                            } else {
                                //rollback sheet
                            }
                        }
                    }
                }
            }
        }
    }
    
    //编辑库存
    public function c_inventory_edit() {
    	$view = new model_stock_material_management_view();
    	
    	$data = $_POST['data'];

    	$result = $view->updateInventory($data);
    	echo json_encode(un_iconv(array(
    		'result' => $result
    	)));
    }

    public function c_export_config() {
        $pid = $_POST['pid'];
        $this->export_config($pid);
    }
    
    //发料记录列表页
    public function c_send_list() {
        $this->show->display('material-send-list');
    }
    
    //发料记录详细
    public function c_load_send_list() {
        $view = new model_stock_material_management_view();
        
        $ids = explode('_', $_GET['id']);
        $sendid = intval($ids[2]);
        $pid = !empty($sendid) ? false : intval($ids[0]);
        $date = $ids[1];
        $datas = $view->getDetailInfo($pid, "`create_date` = '{$date}' AND `sendid` = '{$sendid}'");
        
        $result = array();
        foreach ($datas as $data) {
        	$stock_code = $data['stock_code'];
        	if(!isset($result[$stock_code])) {
        		$result[$stock_code] = $data;
        		$result[$stock_code][$data['pid']] = $data['stock_total'];
        	} else {
        		$result[$stock_code]['stock_total'] += $data['stock_total'];
        		$result[$stock_code][$data['pid']] += $data['stock_total'];
        	}        	
        	
        	$stock = &$result[$stock_code];
            $stock['actNum'] = intval($stock['actNum']);
            $stock['shortage'] = ($shortage = $stock['stock_total'] - $stock['outStockNum']) > 0 ? $shortage : 0;
            $stock['useTotal'] = $data['outStockNum'] - $data['realOutNum'];
        }
        unset($datas);
        
        $result = array_values($result);
        header('Content-type:application/json');
        exit(json_encode(un_iconv($result)));
    }
    
    //获取多产品配置, 各产品的产品名
    public function c_get_product_name() {
    	$view = new model_stock_material_management_view();
    	
    	$datas = array();
    	$ids = explode('_', $_REQUEST['id']);
    	$create_date = $ids[1];
    	$config_id = $ids[2];
    	
    	if(!$config_id) {
    		return;
    	}
    	
    	$datas = $view->getProductNameForConfigId($config_id, $create_date);
    	
    	header('Content-type:application/json;');
    	echo json_encode(array('data' => un_iconv($datas)));
    	exit();
    }
    
    //导出发料记录
    public function c_export_send_list() {
        $ids = explode('_', $_GET['id']);
        $pid = intval($ids[0]);
        $date = $ids[1];
        $configid = intval($ids[2]);
        $columns = $_POST['columns'];
        
        if (!$this->export_send($pid, $date, $configid, $columns)) {
            exit("错误请求!");
        }
        
        exit();
    }
    
    //发料记录修改
    public function c_edit_send_list() { 
        $view = new model_stock_material_management_view();
        
        $datas = $_POST['data'];
        foreach ($datas as &$data) {
            $stock = $view->getStockDetail($data['id']);
            $stock['actNum'] = intval($stock['actNum']);
            
            //退库不能小于0
            if ($data['realOutNum'] < 0) {
                $data['realOutNum'] = 0;
            }
            
            if ($stock['actNum'] < 0) {
                $stock['actNum'] = 0; //库存不会小于0
            }
            
            //如果减少退库数大于库存
            if ($stock['realOutNum'] - $data['realOutNum'] > $stock['actNum']) {
                $data['realOutNum'] = $stock['realOutNum'] - $stock['actNum'];
            }
            
            //如果退库数大于发料数
            if ($data['realOutNum'] > $stock['outStockNum']) {
                $data['realOutNum'] = $stock['outStockNum'];
            }
        }
            
        $view->updateOutNum($datas);
    }
    
    public function c_delete_inventory() {
    	$id = intval($_POST['id']);
    	$view = new model_stock_material_management_view();
    	$result = $view->deleteInventory($id);
    	
    	header('Content-type: application/json;');
    	echo json_encode(array(
    		'error' => !$result
    	));
    }
    public function c_statistics_remove() {
    	if( isset($_POST['id']) && $_POST['id'] ){
    		echo $this->delete_statistics($_POST['id']);
    	}
    }
    function c_load_history_tree() {
        $datas = $this->loadHistoryTree();
        $datas = $this->_setBomId($datas, '0');
        header('Content-type:application/json');
        exit(json_encode(un_iconv($datas)));
    }
    function c_picking_record() {
        $datas = $this->addPickingRecord($_POST['id'],$_POST['vals']);
        echo $datas;
//        $datas = $this->_setBomId($datas, '0');
//        header('Content-type:application/json');
//        exit(json_encode(un_iconv($datas)));
    }
    
    function c_statistics_manage_bom(){
    	$FId = $_POST[data][0][0];
    	$this->show->display('material-management');
    }
}