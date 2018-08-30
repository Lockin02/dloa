<?php
require_once 'management_tools.php';
/**
 * 表格数据类
 * @author Administrator
 *
 */
class model_stock_material_management extends model_stock_material_management_tools {
    private $table_product_info;
    private $table_product_material;
    private $table_data_dict;
    private $table_product_bom_detail_bom;
    private $table_product_bom_detail_send;
    private $table_product_inventory;
    
    function __construct() {
        $this->table_product_type 		= 'oa_stock_product_type';
        $this->table_product_info 		= 'oa_stock_product_info';
        $this->table_product_material 	= 'oa_stock_product_material';
        $this->table_data_dict 			= 'oa_system_datadict';
        $this->table_product_bom_detail_send 	= 'oa_stock_product_bom_detail_send';
        $this->table_product_inventory 			= 'oa_stock_product_inventory';
        
        $this->inventory_info 		= 'oa_stock_inventory_info';
        $this->material_SF 			= 'oa_stock_material_semiFinished';
        $this->material_finished 	= 'oa_stock_material_finished';
        $this->material_details 	= 'oa_stock_material_details';
        $this->history_finished 	= 'oa_stock_material_history_finished';
        $this->history_details 		= 'oa_stock_material_history_details';
        $this->product_inventory 	= 'oa_stock_product_inventory';
        parent::__construct();
    }
    
    /**
     * 树结构数据集
     * @param Int $parentId
     * @return Array
     */
    public function loadTree($parentId) {
        $conditions = " `deleted` = '0'";
//        if ($parentId == -1) {
//            $conditions .= " AND `proType` = '成品' ";
//        } else {
//            $conditions .= " AND properties = 'WLSXZZ'";
//        }
//        echo $conditions;echo "\n";
        $query = $this->getTableFiledByConditions($this->material_finished, "*,name as text", $conditions);
        $treeData = array();
        while (($rs = $this->fetch_array($query)) != false) {
//            $subList = array();
//            $subList = self::loadTree($rs['id']);
//            $state = 'open';
//            if (count($subList) == 0) {
//                //$subList = self::loadCase($rs['id']);
//                $state = 'open';
//            }
//            $rs['state'] = $state;
//            $rs['children'] = $subList;
            $treeData[] = $rs;
        }
        return $treeData;
    }
    /**
     * 获取成品列表
     * */
    public function getFinishedData($id){
    	//获取成品资料
    	$SQL = "SELECT * FROM $this->material_finished  WHERE `deleted` = '0' AND `id`= $id";
    	$query = $this->query($SQL);
    	$rs = $this->fetch_array($query);
//    	
    	//获取半成品资料
    	if($rs['id']){
    		$exp_SF = explode("#",$rs['sfIdNum']);
    		foreach ($exp_SF as $exp_key => $exp_val){
    			$exp_SFId = explode("^",$exp_val);
    			$SFId[$exp_key]['id'] = $exp_SFId[0];
    			$SFId[$exp_key]['num'] = $exp_SFId[1];
    		}
    		$datas_sf = array();
    		foreach ($SFId as $sf_key => $sf_val){
		    	$SQL_sf = "SELECT * FROM $this->material_SF  WHERE `deleted` = '0' AND `id`= {$sf_val['id']}";
		    	$query_sf = $this->query($SQL_sf);
		    	while (($rs_sf = $this->fetch_array($query_sf)) != false) {
		            $datas_sf[] = $rs_sf;
		            $datas_sf[$sf_key]['num'] = $sf_val['num'];
		        }
    		}
	        //元器件数据
	    	$SQL_d = "SELECT * FROM $this->material_details  WHERE `deleted` = '0' AND `finishedId`= {$id}";
	    	$query_d = $this->query($SQL_d);
	    	$datas_t = array();
	    	$datas_d = array();
	    	$being = array();
    		while (($rs_d = $this->fetch_array($query_d)) != false) {
    			if(!in_array($rs_d['code'], $being)){
    				$being[]=$rs_d['code'];
    				$datas_d[] = $rs_d;
    			}
				$datas_t[$rs_d['code']][$rs_d['semifinishedId']] = $rs_d['total'];  
	        }
	        $SQL_i = "SELECT productCode,actNum FROM $this->inventory_info";
	    	$query_i = $this->query($SQL_i);
    		while (($rs_i = $this->fetch_array($query_i)) != false) {
				$datas_i[$rs_i['productCode']] = $rs_i['actNum'];  
	        }
	        
	        $datas['SF'] = 	$datas_sf;  
	        $datas['DS'] = 	$datas_d;
	        $datas['DT'] = 	$datas_t; 
	        $datas['IN'] = 	$datas_i;        
	        return $datas;
    	}
    }
    
    public function getCode($code){
    	$SQL = "SELECT * FROM $this->material_finished  WHERE `deleted` = '0' AND `code`= '$code'";
    	$query = $this->query($SQL);
    	while (($rs = $this->fetch_array($query)) != false) {
			$datas[] = $rs;
		}
		return $datas;
    }
    
	public function getHistoryDetail($id){
    	//获取成品资料
    	$SQL = "SELECT * FROM $this->history_details  WHERE `deleted` = '0' AND `finishedId`= $id";
    	$query = $this->query($SQL);
		while (($rs = $this->fetch_array($query)) != false) {
			$datas_D[] = $rs;
		}
		
		$SQL_F = "SELECT * FROM $this->history_finished  WHERE `deleted` = '0' AND `id`= $id";
    	$query_F = $this->query($SQL_F);
		$data_F = $this->fetch_array($query_F);
		$exp_SF = explode("^",$data_F['sfIdNum']);
    	foreach ($exp_SF as $exp_key => $exp_val){
    		$exp_SFId = explode("&",$exp_val);
    		$datas_E[$exp_SFId[0]] = $exp_SFId[1];
   			$SFId[] = $exp_SFId[0];
    	}
		$datas_sf = array();
    	foreach ($SFId as $sf_key => $sf_val){
		   	$SQL_sf = "SELECT * FROM $this->material_SF  WHERE `deleted` = '0' AND `id`= {$sf_val}";
		   	$query_sf = $this->query($SQL_sf);
		   	while (($rs_sf = $this->fetch_array($query_sf)) != false) {
			    $datas_sf[] = $rs_sf;
		    }
    	}
    	
		$SQL_i = "SELECT productCode,actNum FROM $this->inventory_info";
	    $query_i = $this->query($SQL_i);
    	while (($rs_i = $this->fetch_array($query_i)) != false) {
			$datas_i[$rs_i['productCode']] = $rs_i['actNum'];  
	    }
	        
    	$datas['DS'] = $datas_D;
    	$datas['SF'] = $datas_sf;
    	$datas['EX'] = $datas_E;
    	$datas['IN'] = $datas_i;
	    return $datas;
    }
    
    public function getMoreConfigList() {
    	$query = $this->getTableFiledByConditions("`{$this->table_product_bom_detail_send}`", "`id`, `stock_list_name` AS `text`", "`deleted` = '0'");
    	
    	$datas = array();
    	while($row = $this->fetch_array($query)) {
    		$datas[] = $row;
    	}
    	
    	return $datas;
    }
    public function delete_statistics($id) {
		if($id){
			$SQL = "UPDATE  $this->material_finished SET `deleted` = '1' WHERE `id` = '{$id}'";
    		return $this->query($SQL);
		}
    	
    }
    
    /**
     * 半成品列表
     */
    public function getStockProductByCode($code) {
        $query = $this->getTableFiledByConditions($this->table_product_info, '', "productCode = '{$code}'");
        $data = array();
        while (($rs = $this->fetch_array($query)) != false) {
            $data[] = $rs;
        }
        return $data;
    }
    
    /**
     * 产品属性
     * @return Array
     */
    function attributeName() {
        $SQL = "SELECT * FROM {$this->table_data_dict} WHERE parentId = '548'";
        $query = $this->query($SQL);
        $datas = array();
        while (($rs = $this->fetch_array($query)) != false) {
            $datas[$rs['dataCode']] = $rs['dataName'];
        }
        return $datas;
    }
    
    /**
     * 获取配置单数据集
     * @param Int $parentId
     * @param Int $pages
     * @param Int $rows
     * @return Array
     */
    function loadConfigurationList($parentId, $pages, $rows) {
        $total = $this->loadTotal($this->table_product_info, "`proTypeId` = '{$parentId}'");
        $limit = $this->setLimit($rows, $pages);
        $query = $this->getTableFiledByConditions($this->table_product_info, '', "`proTypeId` = '{$parentId}'", '', '', $limit);
        $propertiesNameList = $this->attributeName();
        $datas = array();
        while (($rs = $this->fetch_array($query)) != false) {
            $rs['propertiesName'] = $propertiesNameList[$rs['properties']];
            $datas[] = $rs;
        }
        
        $result = array();
        $result['total'] = $total;
        $result['rows'] = $datas;
        
        return $result;
    }
    
    function getInventoryList($pages, $prepage) {
    	$total = $this->loadTotal($this->table_product_inventory);
    	$limit = $this->setLimit($prepage, $pages);
    	$query = $this->getTableFiledByConditions($this->table_product_inventory, '', '', '', '', $limit);
    	$datas = array();
    	while($row = $this->fetch_array($query)) {
    		$datas[] = $row;
    	}
    	
    	$result = array(
    		'total' => $total,
    		'rows' => $datas
    	);
    	
    	return $result;
    }
    
	function getInventoryData() {
    	$query = $this->getTableFiledByConditions($this->table_product_inventory);
    	$datas = array();
    	while($row = $this->fetch_array($query)) {
    		$datas[$row['stock_code']] = $row['actNum'];
    	}
    	return $datas;
    }
    
    public function loadHistoryTree($parentId , $type = '') {
        
        $conditions = " `deleted` = '0'";
		
		if($type == 'SF'){
			$DBName	= $this->history_finished;
			$conditions .= " AND Fid=".$parentId;
			$fields = ' id,create_date as text ';
		}else{
			$SQL_F = "SELECT * FROM {$this->history_finished} WHERE `deleted` = '0'";
	        $query_F = $this->query($SQL_F);
	     	while (($rsF = $this->fetch_array($query_F)) != false) {
		       $ids = ",'".$rsF['Fid']."'";
		    }
			$DBName	= $this->material_finished;
			$conditions .= 'AND `id` in ('.substr($ids,1).')';
			$fields = ' id,name as text ';
		}
        $query = $this->getTableFiledByConditions( $DBName , $fields, $conditions);
        $treeData = array();
	    while (($rs = $this->fetch_array($query)) != false) {
	        $datas = array();
	        $datas = self::loadHistoryTree($rs['id'],'SF');
	        $state = 'open';
	        if (count($datas) == 0) {
	            $state = 'open';
	        }
	        $rs['state'] = $state;
	        $rs['children'] = $datas;
	        $treeData[] = $rs;
	    }

        return $treeData;
    }
    public function addPickingRecord($id,$sfIdNum) {
		$now = date("Y-m-d H:i:s");
    	$SQL = "insert into {$this->history_finished} 
    				(`Fid`,`sfIdNum`,`create_date`,`create_by`,`deleted`)
    			values
    				('{$id}','".substr($sfIdNum, 0,-1)."','{$now}','{$_SESSION ['USER_ID']}','0')";
     	$this->query($SQL);
		$insertID = mysql_insert_id(); 
		
		if($insertID){
	        $SQL = "select * from {$this->material_details} where `finishedId` = '{$id}'";
	        $query = $this->query($SQL);
	        $SQL_ins = "insert into {$this->history_details} ";
	        $SQL_ins .= "(`finishedId`,`semifinishedId`,`code`,`name`,`model`,`packaging`,`factory`,`total`,`pickingNum`)";
	        $SQL_ins .= " values ";
		    while (($rs = $this->fetch_array($query)) != false) {
				$SQL_ins .= "(
							'".$insertID."',
							'".$rs['semifinishedId']."',
							'".$rs['code']."',
							'".$rs['name']."',
							'".$rs['model' ]."',
							'".$rs['packaging']."',
							'".$rs['factory']."',
							'".$rs['total']."',
							'".$rs['total']."'
							),";
		    }
		    
		    $this->query(substr($SQL_ins, 0,-1));
		    $ID = mysql_insert_id();
		}
        return $ID;
    }
    //导出BOM
    function export_excel($productId) {
        if ($productId) {
            set_time_limit(0);
            ini_set('memory_limit', '128M');
            
            $view = new model_stock_material_management_view();
            $title = $view->getProductName($productId); //文件标题
            $sheetList = $view->getRelevanceByProductId($productId); //excel文件sheet名称，即半成品、结构件和配件
            $sheetNames = array_values($sheetList); //sheet名称
            $tabTitle = array(
                "序号",
                "物料种类",
                "物料编码",
                "名称",
                "型号",
                "封装",
                "数量",
                "元件序号",
                "厂商",
                "备注"
            ); //表格标题
            
            $i = 0;
            $materialDetails = array();
            foreach ($sheetList as $sheetId => $sheetName) {
                /* 每个sheet表格的标题 */
                $Titles[$i][] = $sheetName;
                
                /* 半成品基础物料清单 */
                $materialDetails[$i]['base'][] = $tabTitle;
                $baseDetail = $view->getSubDetail($sheetId, 'detail');
                if (!empty($baseDetail)) {
                    foreach ($baseDetail as $baseData) {
                        $materialDetails[$i]['base'][] = array(
                            $baseData['serial_number'],
                            $baseData['stock_type'],
                            $baseData['stock_code'],
                            $baseData['stock_name'],
                            $baseData['stock_model'],
                            $baseData['stock_packaging'],
                            $baseData['stock_total'],
                            $baseData['stock_serial_number'],
                            $baseData['stock_factory'],
                            $baseData['stock_mark']
                        );
                    }
                }
                
                /* 判断相应半成品是否有配置相关内容，若有则导出配置列表内容 */
                $configList = $view->getConfig($sheetId);
                if (!empty($configList)) {
                    foreach ($configList as $config) {
                        $materialDetails[$i]['config'][$config['id']][] = array(
                            '配置选项',
                            $config['name'],
                            $config['clash'],
                            $config['description']
                        );
                        $configDetail = $view->getSubDetail($config['id'], 'config');
                        if (!empty($configDetail)) {
                            foreach ($configDetail as $configData) {
                                $materialDetails[$i]['config'][$config['id']][] = array(
                                    $configData['serial_number'],
                                    $configData['stock_type'],
                                    $configData['stock_code'],
                                    $configData['stock_name'],
                                    $configData['stock_model'],
                                    $configData['stock_packaging'],
                                    $configData['stock_total'],
                                    $configData['stock_serial_number'],
                                    $configData['stock_factory'],
                                    $configData['stock_mark']
                                );
                            }
                        }
                    }
                }
                $i++;
            }
            
            /* 文件数据 */
            $xls = new includes_class_excel($title . '.xls'); //文件名称
            $xls->SetTitle($sheetNames, $Titles); //sheet名称
            
            /* 表格基本样式 */
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array(
                            'argb' => '00000000'
                        )
                    )
                )
            );
            $columnData = array(
                'A',
                'B',
                'C',
                'D',
                'E',
                'F',
                'G',
                'H',
                'I',
                'J',
                'K',
                'L',
                'M',
                'N',
                'O',
                'P',
                'Q',
                'R',
                'S',
                'T',
                'U',
                'V',
                'W',
                'X',
                'Y',
                'Z',
                'AA'
            );
            
            /* 组织导出数据表格 */
            if (!empty($materialDetails)) {
                for ($j = 0; $j < $i; $j++) {
                    $baseTotal = 0;
                    /* 基本信息 */
                    if (!empty($materialDetails[$j]['base'])) {
                        $baseTotal = (count($materialDetails[$j]['base']) - 1); //除去标题
                        foreach ($materialDetails[$j]['base'] as $row => $data) {
                            foreach ($data as $key => $val) {
                                $xls->objActSheet[$j]->setCellValueExplicit(($columnData[$key] . ($row + 2)), un_iconv($val), PHPExcel_Cell_DataType::TYPE_STRING);
                            }
                        }
                        $xls->objActSheet[$j]->mergeCells('A1:J1');
                        $xls->objActSheet[$j]->getStyle('A2:J2')->getFont()->setBold(true);
                        $xls->objActSheet[$j]->getStyle('A1:J' . ($baseTotal + 2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $xls->objActSheet[$j]->getStyle('A1:J' . ($baseTotal + 2))->applyFromArray($styleArray);
                    }
                    /* 配置信息 */
                    if (!empty($materialDetails[$j]['config'])) {
                        $configTotal = (count($materialDetails[$j]['config']) - 1); //除去标题
                        $n = 0;
                        foreach ($materialDetails[$j]['config'] as $configId => $dataList) {
                            foreach ($dataList as $data) {
                                $configRow = $baseTotal + 4 + $n;
                                if (!isset($data['4'])) {
                                    $xls->objActSheet[$j]->mergeCells('D' . $configRow . ':F' . $configRow);
                                    $xls->objActSheet[$j]->getStyle('A' . $configRow . ':J' . $configRow)->getFont()->setBold(true);
                                }
                                foreach ($data as $key => $val) {
                                    $xls->objActSheet[$j]->setCellValueExplicit(($columnData[$key] . $configRow), un_iconv($val), PHPExcel_Cell_DataType::TYPE_STRING);
                                }
                                $n++;
                            }
                        }
                        $xls->objActSheet[$j]->getStyle('A' . ($baseTotal + 4) . ':J' . $configRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $xls->objActSheet[$j]->getStyle('A' . ($baseTotal + 4) . ':J' . $configRow)->applyFromArray($styleArray);
                    }
                    
                    foreach ($columnData as $key => $val) {
                        $xls->objActSheet[$j]->getColumnDimension($val)->setWidth(15);
                    }
                }
            }
            $xls->OutPut();
        }
    }
    
    /* 导出物料清单（已统计） */
    function export_materials($pids, $ext = 'all') {
        set_time_limit(0);
        ini_set('memory_limit', '128M');
        $view = new model_stock_material_management_view();
        $materialsDatas = array();
        $arr = explode(',', $pids);
        foreach ($arr as $key => $list) {
            $dataList = explode(':', $list); //分为产品及半成品
            $productId = (int) $dataList[0];
            $productName = $view->getProductName($productId);
            $tabTitle = array(
                "序号",
                "物料编码",
                "物料名称",
                "物料型号",
                "封装",
                "元件序号",
                "厂商",
                "需求数",
                "库存数",
                "安全库存",
                "在途数",
                "缺货数",
                "用量",
                "损耗率",
                "发料数",
                "应退数",
                "实际退数"
            ); //表格标题
            
            $dataList[1] = explode('-', $dataList[1]); //分为半成品信息
            foreach ($dataList[1] as $i => $list) {
                $data = explode('_', $list); //分为半成品及相应套数
                
                $sheetId = (int) $data[0];
                $numTotal = (int) $data[1];
                $sheetName = $view->getRelevanceName($sheetId);
                $sheetList[] = $Titles[$i][] = $sheetName;
                
                $materialsDatas[$i][] = $tabTitle;
                $baseDatas = $view->getDetailInfoByRid($sheetId, $numTotal, $ext);
                if (!empty($baseDatas)) {
                    foreach ($baseDatas as $num => $baseData) {
                        $materialsDatas[$i][] = array(
                            $num + 1,
                            $baseData['stock_code'],
                            $baseData['stock_name'],
                            $baseData['stock_model'],
                            $baseData['stock_packaging'],
                            $baseData['stock_serial_number'],
                            $baseData['stock_factory'],
                            $baseData['stock_total'],
                            $baseData['actNum'],
                            $baseData['safeNum'],
                            $baseData['planInstockNum'],
                            $baseData['shortage'],
                            $baseData['num'],
                            $baseData['sunhao'] . '%',
                            $baseData['outStockNum'],
                            $baseData['mustOutNum'],
                            $baseData['realOutNum']
                        );
                    }
                }
                
            }
            
            /* 文件数据 */
            $extName = '';
            if ($ext == 'all') {
                $extName = '物料清单';
            } elseif ($ext == 'shortage') {
                $extName = '缺货单';
            }
            $xls = new includes_class_excel($productName . $extName . '.xls'); //文件名称
            $xls->SetTitle($sheetList, $Titles); //sheet名称
            
            /* 表格基本样式 */
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array(
                            'argb' => '00000000'
                        )
                    )
                )
            );
            $columnData = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA');
            
            /* 组织导出数据表格 */
            if (!empty($materialsDatas)) {
                foreach ($materialsDatas as $i => $datas) {
                    /* 基本信息 */
                    if (!empty($datas)) {
                        $baseTotal = (count($datas) - 1); //除去标题
                        foreach ($datas as $row => $data) {
                            foreach ($data as $key => $val) {
                                $xls->objActSheet[$i]->setCellValueExplicit(($columnData[$key] . ($row + 2)), un_iconv($val), PHPExcel_Cell_DataType::TYPE_STRING);
                            }
                        }
                        $xls->objActSheet[$i]->mergeCells('A1:Q1');
                        $xls->objActSheet[$i]->getStyle('A2:Q2')->getFont()->setBold(true);
                        $xls->objActSheet[$i]->getStyle('A1:Q' . ($baseTotal + 2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $xls->objActSheet[$i]->getStyle('A1:Q' . ($baseTotal + 2))->applyFromArray($styleArray);
                    }
                    
                    if ($ext == 'all') {
                        $xls->objActSheet[$i]->setCellValue('C' . ($baseTotal + 4), un_iconv('发料人：'));
                        $xls->objActSheet[$i]->setCellValue('C' . ($baseTotal + 5), un_iconv('发料日期：'));
                        $xls->objActSheet[$i]->setCellValue('H' . ($baseTotal + 4), un_iconv('接收人：'));
                        $xls->objActSheet[$i]->setCellValue('H' . ($baseTotal + 5), un_iconv('接收日期：'));
                    }
                    
                    foreach ($columnData as $key => $val) {
                        $xls->objActSheet[$i]->getColumnDimension($val)->setWidth(12);
                    }
                }
            }
            $xls->OutPut();
        }
    }
    
    //导出配置单
    function export_config($pid) {
        require_once WEB_TOR . 'includes/classes/PHPExcel.php';
        require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
        $PHPExcel = new PHPExcel();
        $PHPReader = new PHPExcel_Reader_Excel5($PHPExcel);
        $objWriter = new PHPExcel_Writer_Excel5($PHPExcel);
        
        $PHPExcel->setActiveSheetIndex(0);
        $objActSheet = $PHPExcel->getActiveSheet();
        /**
         * header
         */
        $objActSheet->setTitle(un_iconv('募投项目'));
        
        $objActSheet->setCellValue('A1', un_iconv('项目编码'));
        $objActSheet->getColumnDimension('A')->setWidth(15);
        
        /**
         * header style
         */
        $objActSheet->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objActSheet->getStyle('A1')->getFill()->getStartColor()->setRGB('c0c0c0');
        $objActSheet->getStyle('A1')->getFont()->setBold(true);
        $objActSheet->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objActSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActSheet->getStyle('A1')->getAlignment()->setWrapText(true);
        $A1Style = $objActSheet->getStyle('A1');
        $objBorderA1 = $A1Style->getBorders();
        $objBorderA1->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objBorderA1->getTop()->getColor()->setARGB('00000000'); // color      
        $objBorderA1->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objBorderA1->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objBorderA1->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objActSheet->duplicateStyle($A1Style, 'A1:P1');
        $objActSheet->getRowDimension('1')->setRowHeight(25);
        
        /**
         * set data
         * @var unknown_type
         */
        $objActSheet->setCellValue('A' . 2, un_iconv('测试'));
        
        /**
         * output
         */
        $fileName = '配置单';
        //			header("Content-type: text/html;charset=GBK");
        //			header("Content-Type: application/force-download");  
        //			header("Content-Type: application/octet-stream");  
        //			header("Content-Type: application/download");
        //			header("Content-type: application/vnd.ms-excel");
        //			header('Content-Disposition:inline;filename="'.$fileName.date('Y-m-d').'.xls'.'"');  
        //			header("Content-Transfer-Encoding: binary");  
        //			//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
        //			header("Last-Modified: " . date("D, d M Y H:i:s") . " GMT");  
        //			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");  
        //			header("Pragma: no-cache");  
        //			$objWriter->save('php://output');
        
        
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type: application/vnd.ms-excel;");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header("Content-Disposition:attachment;filename=" . $fileName);
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
    }
    
    /**
     * 导出发料记录
     * @param int $pid 产品id
     * @param string $date 发料日期
     * @return boolen
     */
    function export_send($pid, $date, $configid, $columns) {
    	/*****************获取数据*******************/
    	$view = new model_stock_material_management_view();
    	
    	if($configid > 0) {
			$filename = $view->getSendListConfigName($configid);
		} else {
			$filename = $view->getProductName($pid);
		}
		
		$filename = preg_replace('#[\s\?"><|*/:\\\\]#', '_', $filename) . '_' . date('Y-m-d', strtotime($date)) . '.xls';
				
        $sendid = $configid;
        $pid = !empty($sendid) ? false : $pid;
        $datas = $view->getDetailInfo($pid, "`create_date` = '{$date}' AND `sendid` = '{$sendid}'");
                
        $result = $pids = array();
        foreach ($datas as $data) {
        	$stock_code = $data['stock_code'];
        	if(!isset($result[$stock_code])) {
        		$result[$stock_code] = $data;
        		$result[$stock_code][$data['pid']] = $data['stock_total'];
        	} else {
        		$result[$stock_code]['stock_total'] += $data['stock_total'];
        		$result[$stock_code][$data['pid']] += $data['stock_total'];
        	}        	
        	
        	$pids[$data['pid']] = 1;
        	
        	$stock = &$result[$stock_code];
            $stock['actNum'] = intval($stock['actNum']);
            $stock['shortage'] = ($shortage = $stock['stock_total'] - $stock['outStockNum']) > 0 ? $shortage : 0;
            $stock['useTotal'] = $data['outStockNum'] - $data['realOutNum'];
            $stock['stock_loss_total'] = ceil($stock['stock_total'] * (100 + $stock['sunhao']) / 100);
        }
        $datas = array_values($result);
        unset($result);
        
        $columnData = array(
			'stock_code' => 'OA编码',
			'stock_name' => '名称',
			'stock_model' => '型号',
			'stock_packaging' => '封装',
			'stock_factory' => '厂商',
			'stock_total' => '实际需求',
			'stock_loss_total' => '总需求(含损耗)',
			'outStockNum' => '发料数',
			'realOutNum' => '退库数',
			'mustOutNum' => '应退数',
			'useTotal' => '总用量',
			'shortage' => '缺货数'
		);
        
        $pids = array_keys($pids);
    	$index = array_search('stock_loss_total', $columns);
		if($index !== false) {
			array_splice($columns, $index + 1, 0, $pids);
		}
		foreach($pids as $pid) {
			$columnData[$pid] = $view->getProductName($pid);
			if($index === false) {
				$columns[] = $pid;
			}
		}
		/*****************获取数据*******************/
		
		if(empty($datas)) {
			return false;
		}
		
		require_once WEB_TOR . 'includes/classes/PHPExcel.php';
        
        //实例化phpexcel对象
        $PHPExcel = new PHPExcel();
        
        //选择第一个工作区
        $PHPExcel->setActiveSheetIndex(0);
        
        //获取当前工作区
        $objActSheet = $PHPExcel->getActiveSheet();
        $objActSheet->setTitle(un_iconv('发料列表'));
                
       	$positions = range('A', 'Z');
       	$needPositions = array();
        foreach($columns as $key => $column) {
        	$position = (($p = $key / 26) > 1 ? $positions[floor($p)] : '') . $positions[$key % 26];
        	$needPositions[$column] = $position;
        	
        	//设置列宽度
        	$objActSheet->getColumnDimension($position)->setWidth(15);
        	
        	//获取表头Style
        	$headerStyle = $objActSheet->getStyle($position . '1');
        	
        	//设置表头字体, 颜色
        	$headerStyle->getFont()->setBold(true);
        	$headerStyle->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
        	        	        	
        	//设置居中对齐
        	$headerStyle->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        	
        	//设置表头填充
        	$headerFill = $headerStyle->getFill();
        	$headerFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        	$headerFill->getStartColor()->setARGB('FFFF0000');
        	
        	//添加表头
        	$objActSheet->setCellValue($position . '1', un_iconv($columnData[$column]));
        }
        
        //开始写数据
        $currentRowIndex = 2;
    	foreach($datas as $data) {
			$data['useTotal'] = $data['outStockNum'] - $data['realOutNum'];
			$data['shortage'] = ($shortage = $data['stock_total'] - $data['outStockNum']) > 0 ? $shortage : 0;

			foreach($needPositions as $field => $position) {
				$objActSheet->setCellValue($position . $currentRowIndex, un_iconv($data[$field]));
				
				if($field == 'shortage' && $data['shortage'] > 0) {
					//未发料填充色
					$objStyle = $objActSheet->getStyle($position . $currentRowIndex);
					$objFill = $objStyle->getFill();
		        	$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		        	$objFill->getStartColor()->setARGB('FFFFCCCC');
				}
			}
								
			$currentRowIndex++;
		}
		unset($currentRowIndex);
		        
        //输出文件
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download;");
        header("Content-Type: application/vnd.ms-excel;");
        header("Content-Type: application/octet-stream;");
        header("Content-Type: application/download;");
        header("Content-Disposition: attachment; filename={$filename}");
        header("Content-Transfer-Encoding: binary");
        
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    //导出BOM管理成品元器件信息
    function statistics_export($thArr,$rowdatas,$filename) {
    	require_once WEB_TOR . 'includes/classes/PHPExcel.php';
    	
    	//实例化phpexcel对象
    	$PHPExcel = new PHPExcel();
    	
    	//选择第一个工作区
    	$PHPExcel->setActiveSheetIndex(0);
    	
    	//获取当前工作区
    	$objActSheet = $PHPExcel->getActiveSheet();
    	$objActSheet->setTitle(un_iconv($filename));
    	
    	$positions = range('A', 'Z');
    	$needPositions = array();
    	foreach($thArr as $key => $column) {
    		$position = (($p = $key / 26) > 1 ? $positions[floor($p)] : '') . $positions[$key % 26];
    		$needPositions[$column] = $position;
    		 
    		//设置列宽度
    		$objActSheet->getColumnDimension($position)->setWidth(15);
    		 
    		//获取表头Style
    		$headerStyle = $objActSheet->getStyle($position . '1');
    		 
    		//设置表头字体, 颜色
    		$headerStyle->getFont()->setBold(true);
    		$headerStyle->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
    		 
    		//设置居中对齐
    		$headerStyle->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		 
    		//设置表头填充
    		$headerFill = $headerStyle->getFill();
    		$headerFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    		$headerFill->getStartColor()->setARGB('FFFF0000');
    		 
    		//添加表头
    		$objActSheet->setCellValue($position . '1', un_iconv($column));
    	}
    	//开始写数据
    	$currentRowIndex = 2;
    	foreach($rowdatas as $data) {
    		$i = 0;   	
    		foreach($needPositions as $field => $position) {
    			$objActSheet->setCellValue($position . $currentRowIndex, un_iconv($data[$i]));
    			$i++;
    		} 	
    		$currentRowIndex++;
    	}
    	unset($currentRowIndex);
    	
    	//输出文件
    	header("Pragma: public");
    	header("Expires: 0");
    	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    	header("Content-Type: application/force-download;");
    	header("Content-Type: application/vnd.ms-excel;");
    	header("Content-Type: application/octet-stream;");
    	header("Content-Type: application/download;");
    	header("Content-Disposition: attachment; filename={$filename}.xls");
    	header("Content-Transfer-Encoding: binary");
    	
    	$objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
    	$objWriter->save('php://output');
    }
}