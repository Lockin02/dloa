<?php
require_once 'management_tools.php';
/**
 * 模板导入类
 * @author Administrator
 *
 */
class model_stock_material_management_import extends model_stock_material_management_tools {
    private $table_product_info;
    private $table_bom_detail;
    private $table_bom_config;
    private $table_bom_config_detail;
    private $table_bom_relevance;
    private $table_bom_relevance_bom;
    private $table_bom_detail_bom;
    private $table_bom_config_bom;
    private $table_bom_detail_send;
    private $table_bom_inventory;

    function __construct() {
        $this->table_product_info 		= 'oa_stock_product_info';
        $this->table_bom_inventory 		= 'oa_stock_product_inventory';
//        $this->table_bom_detail 		= 'oa_stock_product_bom_detail';
//        $this->table_bom_config 		= 'oa_stock_product_bom_config';
//        $this->table_bom_config_bom 	= 'oa_stock_product_bom_config_bom';
//        $this->table_bom_relevance 		= 'oa_stock_product_bom_relevance';
//        $this->table_bom_relevance_bom 	= 'oa_stock_product_bom_relevance_bom';
//        $this->table_bom_detail_send 	= 'oa_stock_product_bom_detail_send';
//        $this->table_bom_config_detail 	= 'oa_stock_product_bom_config_detail';

        $this->material_semiFinished 	= 'oa_stock_material_semiFinished';
        $this->material_parts 			= 'oa_stock_material_parts';
        $this->material_type 			= 'oa_stock_material_type';
        $this->material_config 			= 'oa_stock_material_config';
        $this->product_inventory 		= 'oa_stock_product_inventory';

        parent::__construct();
    }

    //TODO 基本方法
    /**
     * 设置SESSION SHEET
     * @param int $sheetId
     * @param String $sheetName
     */
    private function setSessionSheet($sheetId, $sheetName) {
        $_SESSION[$_SESSION['USER_ID']]['EXCEL_IMPORT']['SHEETS'][$sheetId] = $sheetName;

    }

    /**
     * 设置SESSION INFO
     * @param int $sheetId
     * @param String $data
     */
    private function setSessionInfos($sheetId, $datas) {
        $_SESSION[$_SESSION['USER_ID']]['EXCEL_IMPORT']['INFOS'][$sheetId] = $datas;

    }

    /**
     * 获取SESSION
     * @param Array $type default NULL, It can set "SHEETS" and "INFOS"
     */
    public function getSession($type = "") {
        if ($type == "SHEETS" || $type == "INFOS") {
            $datas = isset($_SESSION[$_SESSION['USER_ID']]['EXCEL_IMPORT'][$type]) ? $_SESSION[$_SESSION['USER_ID']]['EXCEL_IMPORT'][$type] : array();
        } else {
            $datas = isset($_SESSION[$_SESSION['USER_ID']]['EXCEL_IMPORT']) ? $_SESSION[$_SESSION['USER_ID']]['EXCEL_IMPORT'] : array();
        }
        return $datas;
    }

    public function printSession($type = "") {
        if ($type == "SHEETS" || $type == "INFOS") {
            $datas = isset($_SESSION[$_SESSION['USER_ID']]['EXCEL_IMPORT'][$type]) ? $_SESSION[$_SESSION['USER_ID']]['EXCEL_IMPORT'][$type] : array();
        } else {
            $datas = isset($_SESSION[$_SESSION['USER_ID']]['EXCEL_IMPORT']) ? $_SESSION[$_SESSION['USER_ID']]['EXCEL_IMPORT'] : array();
        }
        echo "<pre>";
        echo "</pre>";
        exit;
    }

    /**
     * 保存二次导入Tag数
     * @param Int $num
     */
    public function SetAddConfigTagCount($num) {
        $_SESSION[$_SESSION['USER_ID']]['CONFIG_TAG']['COUNT'] = $num;
    }

    /**
     * 获取二次导入Tag数
     */
    public function getAddConfigTagCount() {
        return $_SESSION[$_SESSION['USER_ID']]['CONFIG_TAG']['COUNT'];
    }

    /**
     * 初始化二次导入Tag数
     */
    public function initAddConfigTagCount() {
        unset($_SESSION[$_SESSION['USER_ID']]['CONFIG_TAG']['COUNT']);
        $_SESSION[$_SESSION['USER_ID']]['CONFIG_TAG']['COUNT'] = 0;
    }

    /**
     * 检查该物料编号是否存在
     * @param String $code
     * @return String
     */
    public function checkStockCode($code) {
        $SQL = "SELECT COUNT(*) AS isExist FROM {$this->table_product_info} WHERE `productCode` = '{$code}' ";

        $query = $this->query($SQL);
        $data = $this->_db->fetch_array($query);
        $isExist = "N";
        if ($data['isExist']) {
            $isExist = "Y";
        }
        return $isExist;
    }

    /**
     * 生成配置信息数据集
     * @param String $item
     * @param String $clash
     * @param String $description
     * @return Array
     */
    private function setConfig($id, $item, $clash, $description) {
        $configArr = array();
        $configArr['id'] = $id;
        $configArr['name'] = $item;
        $configArr['clash'] = $clash;
        $configArr['description'] = $description;
        $configArr['detail'] = array();
        return $configArr;
    }

    //TODO 导入
    /**
     * 文件基本检查
     * @param unknown_type $postData
     * @param unknown_type $fileData
     */
    public function importBasicCheck($postData, $fileData) {
        $errorList = array();
        return $errorList;
    }

    public function importInventory($file) {
    //初始化
        $isSuccess = false;
        $this->initSession();
        set_time_limit(0);

        //包含类
        require_once WEB_TOR . 'includes/classes/PHPExcel.php';
        require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
        require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel2007.php';

        //判断Excel版本
        $fileName = $_FILES['inventory_file']['tmp_name'];
        $PHPExcel = new PHPExcel();
        $PHPReader = new PHPExcel_Reader_Excel2007($PHPExcel);
        if (!$PHPReader->canRead($fileName)) {
            $PHPReader = new PHPExcel_Reader_Excel5($PHPExcel);
        }

        if($PHPReader->canRead($fileName)) {
	        $Excel = $PHPReader->load($fileName);
	        $datas = mb_iconv($Excel->getSheet(0)->toArray());
	        $count = count($datas);

	        for($i = 1; $i < $count; $i++) {
	        	$data = $datas[$i];
	        	$sql = "INSERT INTO `{$this->table_bom_inventory}`(`stock_code`, `stock_model`, `stock_name`, `stock_packaging`, `stock_factory`, `actNum`) VALUES ('{$data[0]}', '{$data[1]}', '{$data[2]}', '{$data[3]}', '{$data[4]}', '{$data[5]}') ON DUPLICATE KEY UPDATE `stock_model` = '{$data[1]}', `stock_name` = '{$data[2]}', `stock_packaging` = '{$data[3]}', `stock_factory` = '{$data[4]}', `actNum` = '{$data[5]}'";
	        	$this->query($sql);
	        }
	        return true;
        }
    }

    /**
     * 导入模板并存放至SESSION中
     * @param Array $fileData
     * @return Boolean
     */
    public function processImport($fileData) {

        //初始化
        $isSuccess = false;
        $this->initSession();
        set_time_limit(0);

        //包含类
        require_once WEB_TOR . 'includes/classes/PHPExcel.php';
        require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
        require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel2007.php';

        //判断Excel版本
        $fileName = $_FILES['upload_excel_file']['tmp_name'];
        $PHPExcel = new PHPExcel();
        $PHPReader = new PHPExcel_Reader_Excel2007($PHPExcel);
        if (!$PHPReader->canRead($fileName)) {
            $PHPReader = new PHPExcel_Reader_Excel5($PHPExcel);
        }

        //读取Excel文件内数据
        if ($PHPReader->canRead($fileName)) {
            $Excel = $PHPReader->load($fileName);
            $sheetNameArr = mb_iconv($Excel->getSheetNames());

            //获取SHEET总数并遍历
            for ($i = 0; $i < $Excel->getSheetCount(); $i++) {
                $datas = array();
                $inSheetData = mb_iconv($Excel->getSheet($i)->toArray());

                if (count($inSheetData) > 0) {
//                    switch ($sheetNameArr[$i]) {
//                        case '配件':
//                            $datas = $this->processAccessoriesSheet($inSheetData);
//                            break;
//                        case '结构件':
//                            $datas = $this->processStructuralSheet($inSheetData);
//                            break;
//                        default:
//                            $datas = $this->proccessStandardSheet($inSheetData);
//                    }
                    $datas = $this->proccessStandardSheet($inSheetData);
                    //如果导入文件中该SHEET没有数据则不添加入数据集
                    if (!empty($datas)) {
                        $this->setSessionSheet($i, $sheetNameArr[$i]);
                        $this->setSessionInfos($i, $datas);
                        $isSuccess = true;
                    }
                }
            }

        }

        return $isSuccess;
    }

 /**
     * 导入模板并存放至SESSION中
     * @param Array $fileData
     * @return Boolean
     */
    public function PDInventoryImport($fileData) {

        //初始化
        $isSuccess = false;
        $this->initSession();
        set_time_limit(0);

        //包含类
        require_once WEB_TOR . 'includes/classes/PHPExcel.php';
        require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
        require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel2007.php';

        //判断Excel版本
        $fileName = $_FILES['upload_excel_file']['tmp_name'];
        $PHPExcel = new PHPExcel();
        $PHPReader = new PHPExcel_Reader_Excel2007($PHPExcel);
        if (!$PHPReader->canRead($fileName)) {
            $PHPReader = new PHPExcel_Reader_Excel5($PHPExcel);
        }

        //读取Excel文件内数据
        if ($PHPReader->canRead($fileName)) {
            $Excel = $PHPReader->load($fileName);
            $sheetNameArr = mb_iconv($Excel->getSheetNames());

            //获取SHEET总数并遍历
            for ($i = 0; $i < $Excel->getSheetCount(); $i++) {
                $datas = array();
                $inSheetData = mb_iconv($Excel->getSheet($i)->toArray());

                if (count($inSheetData) > 0) {
                	$sql = "INSERT INTO `{$this->product_inventory}`(`stock_code`, `stock_model`, `stock_name`, `stock_packaging`, `stock_factory`, `actNum`) VALUES ('{$data[1]}', '{$data[2]}', '{$data[3]}', '{$data[4]}', '{$data[5]}', '{$data[6]}') ON DUPLICATE KEY UPDATE `stock_model` = '{$data[2]}', `stock_name` = '{$data[3]}', `stock_packaging` = '{$data[4]}', `stock_factory` = '{$data[5]}', `actNum` = '{$data[6]}'";

        	$this->query($sql);
                for ($k = 0; $k < count($inSheetData); $k++) {
		            $data = array();
		            //导入文件中若遇到配置选项，则改变数据类型，并添加入对应的配置数据集中
		                if (preg_match("/^([1-9][0-9]*)$/", trim($inSheetData[$k][0])) && (int) $inSheetData[$k][0] != 0) {
                    		$data['serial_code'] = $inSheetData[$k][0];
		                    $data['stock_model'] = $inSheetData[$k][1];
		                    $data['stock_name'] = $inSheetData[$k][2];
		                 	$data['stock_packaging'] = $inSheetData[$k][3];
		                    $data['stock_factory'] = $inSheetData[$k][4];
		                    $data['actNum'] = $inSheetData[$k][5];
		                }
        		}



//                    $datas = $this->proccessStandardSheet($inSheetData);
//                    //如果导入文件中该SHEET没有数据则不添加入数据集
//                    if (!empty($datas)) {
//                        $this->setSessionSheet($i, $sheetNameArr[$i]);
//                        $this->setSessionInfos($i, $datas);
//                        $isSuccess = true;
//                    }
                }
            }

        }

        return $isSuccess;
    }

    /**
     * 处理导入结构件页
     * @param unknown_type $inSheetData
     */
    private function processStructuralSheet($inSheetData) {
        $datas = array();
        for ($k = 0; $k < count($inSheetData); $k++) {
            if (preg_match("/^([1-9][0-9]*)$/", trim($inSheetData[$k][0])) && (int) $inSheetData[$k][0] != 0) {
//                $data['serial_number'] = $inSheetData[$k][0];
                $data['type'] = $inSheetData[$k][1];
                $data['code'] = $inSheetData[$k][2];
                $data['name'] = $inSheetData[$k][3];
                $data['model'] = $inSheetData[$k][4];
                $data['packaging'] = $inSheetData[$k][5];
                $data['total'] = $inSheetData[$k][6];
                $data['serial_number'] = $inSheetData[$k][7];
                $data['factory'] = $inSheetData[$k][8];
                $data['description'] = $inSheetData[$k][9];
                $data['is_right'] = 'N';
                $datas['detail'][] = $data;
            }
        }

        return $datas;
    }

    /**
     * 处理导入配件页
     * @param unknown_type $inSheetData
     */
    private function processAccessoriesSheet($inSheetData) {
        $datas = array();
        for ($k = 0; $k < count($inSheetData); $k++) {
            if (preg_match("/^([1-9][0-9]*)$/", trim($inSheetData[$k][0])) && (int) $inSheetData[$k][0] != 0) {
//                $data['serial_number'] = $inSheetData[$k][0];
                $data['type'] = $inSheetData[$k][1];
                $data['code'] = $inSheetData[$k][2];
                $data['name'] = $inSheetData[$k][3];
                $data['model'] = $inSheetData[$k][4];
                $data['packaging'] = $inSheetData[$k][5];
                $data['total'] = $inSheetData[$k][6];
                $data['serial_number'] = $inSheetData[$k][7];
                $data['factory'] = $inSheetData[$k][8];
                $data['description'] = $inSheetData[$k][9];
                $data['is_right'] = 'N';
                $datas['detail'][] = $data;
            }
        }
        return $datas;
    }

    /**
     * 处理导入常规页
     * @param unknown_type $inSheetData
     */
    private function proccessStandardSheet($inSheetData) {
        $datas = array();
        $dataType = 'detail';
        $configNum = 0;
        for ($k = 0; $k < count($inSheetData); $k++) {
            $data = array();
            //导入文件中若遇到配置选项，则改变数据类型，并添加入对应的配置数据集中
            if (trim($inSheetData[$k][0]) == '配置选项') {
                $configNum++;
                $dataType = 'config';
                //配置头
                $datas['config'][$configNum] = $this->setConfig($configNum, trim($inSheetData[$k][1]), trim($inSheetData[$k][2]), trim($inSheetData[$k][3]));

            } else {
                if (preg_match("/^([1-9][0-9]*)$/", trim($inSheetData[$k][0])) && (int) $inSheetData[$k][0] != 0) {
                    $data['code']          = $inSheetData[$k][2];
                    $data['name']          = $inSheetData[$k][3];
                    $data['model']         = $inSheetData[$k][4];
                    $data['packaging']     = $inSheetData[$k][5];
                    $data['total']         = $inSheetData[$k][6];
                    $data['serial_number'] = $inSheetData[$k][7];
                    $data['factory']       = $inSheetData[$k][8];
                    $data['description']   = $inSheetData[$k][9];
                    $data['pickingInfo']   = $inSheetData[$k][10];
                    $data['is_right'] = 'N';
                    if ($dataType == 'config') {
                        $datas[$dataType][$configNum]['detail'][] = $data;
                    } else {
                        $datas[$dataType][] = $data;
                    }
                }
            }
        }

        return $datas;
    }

    /**
     * 根据ID获取SESSION列表
     * @param int $tag
     * @param int $num
     * @param String $type
     */
    public function importSheetDetail($tag, $num, $type) {
        $infos = $this->getSession("INFOS");

        //获取Tag数据
        if ($type == 'config') {
            $datas = isset($infos[$tag]['config'][$num]['detail']) ? $infos[$tag]['config'][$num]['detail'] : array();
        } else {
            $datas = isset($infos[$tag]['detail']) ? $infos[$tag]['detail'] : array();
        }

        //遍历数据是否正确
        $total = count($datas);
        if ($total > 0) {
            for ($i = 0; $i < $total; $i++) {
                if ($datas[$i]['is_right'] == 'N' && trim($datas[$i]['code']) != "") {
                    $datas[$i]['is_right'] = $this->checkStockCode($datas[$i]['code']);
                }
            }
        }

        //保存回SESSION
        if ($type == 'config') {
            $infos[$tag]['config'][$num]['detail'] = $datas;
        } else {
            $infos[$tag]['detail'] = $datas;
        }
        $this->setSessionInfos($tag, $infos[$tag]);

        return $datas;
    }

    /**
     * 修改SESSION中STOCK CODE值
     * @param String $stockCode
     * @param Int $stockIndex
     * @param String $type
     * @param Int $tag
     */
    public function editReviewDetail($stockCode, $stockIndex, $type, $tag) {
        $flag = false;
        if ($this->checkStockCode($stockCode) === "Y") {
            if ($type == 'detail') {
                $_SESSION[$_SESSION['USER_ID']]['EXCEL_IMPORT']['INFOS'][$tag][$type][$stockIndex]['code'] = $stockCode;
            } else {
                $_SESSION[$_SESSION['USER_ID']]['EXCEL_IMPORT'][$tag]['config'][$type]['detail'][$stockIndex]['code'] = $stockCode;
            }
            $flag = true;
        }
        return $flag;
    }

	public function editMaterialDetail($field) {
		if($field['code'] != ""){
			if($this->checkStockCode($field['code']) === "N"){
				return '2';
			}
		}

        $SQL = "UPDATE {$this->material_parts} SET ";
        	foreach($field as $key => $val){
        		if($key != 'id'){
        			$SQL1.= ",`{$key}`='".un_iconv($val,'utf-8','gbk')."'";
        		}
        	}
        $SQL.= substr($SQL1,1)."WHERE `id`='{$field['id']}'";

        return $this->_db->query($SQL);
    }

    //TODO 创建与保存
    /**
     * 保存产品关系
     * @param int $fId
     * @param int $sId
     */
    public function saveRelevance($sheet_name, $position, $product_id) {
        $isSave = false;
        $errorList = array();
        $SQL = "INSERT INTO {$this->table_bom_relevance}(`sheet_name`, `position`, `product_id`) VALUES('{$sheet_name}', '{$position}', {$product_id})";

        $lastInsertID = 0;
        if ($this->_db->query($SQL) != false) {
            $lastInsertID = $this->_db->insert_id();
        }
        return $lastInsertID;
    }

    /**
     * 保存配置中的产品关系
     * @param int $fId
     * @param int $sId
     */
    public function saveRelevanceBom($sheet_name, $position, $product_id) {
        $isSave = false;
        $errorList = array();
        $SQL = "INSERT INTO {$this->table_bom_relevance_bom}(`sheet_name`, `position`, `product_id`) VALUES('{$sheet_name}', '{$position}', {$product_id})";

        $lastInsertID = 0;
        if ($this->_db->query($SQL) != false) {
            $lastInsertID = $this->_db->insert_id();
        }
        return $lastInsertID;
    }
     /**
     * 保存成品资料
     * */
    public function savaFinished($name,$code){
		if(!empty($name)){
        	$SQL = "INSERT INTO {$this->material_type}(
	            				`name`,
	            				`code`,
	            				`deleted`
							) VALUES(
		            			'".un_iconv($name,'utf-8','gbk')."',
		            			'{$code}',
								'0'
							)";
	    }
        if ($this->_db->query($SQL) != false) {
	      	return  $this->_db->insert_id();
	    }else{
	    	$msg = $this->processMsg($SQL);
          	exit($this->alertResult('alertResult', $msg, false));
	    }
    }
    /**
     * 保存半成品资料
     * */
    public function savaSemiFinished($name,$id,$code,$info=''){
		if(!empty($name)){
        	$SQL = "INSERT INTO {$this->material_semiFinished}(
	            				`name`,
	            				`code`,
	            				`parentId`,
                                `deleted`,
	            				`pickingInfo`
							) VALUES(
		            			'{$name}',
		            			'{$code}',
		            			'{$id}',
                                '0',
								'".un_iconv($info,'utf-8','gbk')."'
							)";
	    }
        if ($this->_db->query($SQL) != false) {
	      	return  $this->_db->insert_id();
	    }else{
	    	$msg = $this->processMsg($SQL);
          	exit($this->alertResult('alertResult', $msg, false));
	    }
    }

    /**
     * 保存在SESSION中Detail的数据
     * @param int $productId
     * @return Boolean
     */
    public function saveDetail($datas, $type = 'detail' ,$semifinishedId = '0', $configId = '0') {

        $isSave = false;
        $errorList = array();
        $date = date('Y-m-d H:i:s');
//        if($type == 'detail'){
        	$SQL_val = '';
        	$SQL = "INSERT INTO {$this->material_parts}(
	            				`semifinishedId`,
	            				`configId`,
								`type`,
								`code`,
								`name`,
								`model`,
								`packaging`,
								`total`,
								`serial_number`,
								`factory`,
								`description`,
								`create_date`,
								`create_by`,
                                `deleted`,
								`pickingInfo`
							) VALUES";
	        //保存清单列表
	        foreach ($datas as $data) {
	            $SQL_val .= ",(
	            				'{$semifinishedId}',
	            				'{$configId}',
	            				'{$data['type']}',
	            				'{$data['code']}',
	            				'{$data['name']}',
	            				'{$data['model']}',
	            				'{$data['packaging']}',
	            				'{$data['total']}',
	            				'{$data['serial_number']}',
	            				'{$data['factory']}',
	            				'{$data['description']}',
	            				'{$date}',
	            				'{$_SESSION ['USER_ID']}',
	            				'0',
                                '{$data['pickingInfo']}'
	            			)";
	        }
         	if ($this->_db->query($SQL.substr($SQL_val, 1)) != false) {
	        	$lastInsertID = $this->_db->insert_id();
	        } else {
	            $errorList[] = $SQL;
	        }
//        }
        if (count($errorList) == 0) {
            $isSave = true;
        } else {
            $msg = $this->processMsg($errorList);
            exit($this->alertResult('alertResult', $msg, false));
        }
        return $isSave;
    }

    /**
     * update by jiejie.zhuo
     * date 2014-06-11 17:00
     * 把多条insert语句合成一条, 解决保存很卡的bug
     */
    public function saveDetailBom($parentId, $datas, $pid = FALSE, $type = 'detail') {
        $isSave = false;
        $errorList = array();
        $date = date('Y-m-d H:i:s');

        //保存清单列表
        $SQL = "INSERT INTO {$this->table_bom_detail_bom}(
        					`type`,
							`serial_number`,
							`stock_type`,
							`stock_code`,
							`stock_name`,
							`stock_model`,
							`stock_packaging`,
							`stock_total`,
							`stock_serial_number`,
							`stock_factory`,
							`stock_mark`,
							`create_date`,
							`edit_date`,
							`create_by`,
							`edit_by`,
							`product_id`,
							`sunhao`,
							`realOutNum`,
							`outStockNum`,
							`mustOutNum`,
							`pid`,
							`sendid`,
							`status`,
							`version`
						) VALUES";


        foreach ($datas as $data) {
            if ($data['mark'] == 0) {
            	$status = isset($data['status']) && $data['status'] != 0 ? 1 : 0;
            	$pid_temp = isset($pid) && $pid ? $pid : $data['pid'];
            	$parentId_temp = $parentId === FALSE ? $data['product_id'] : $parentId;

            	$createDate = $data['create_date'] ? $data['create_date'] : $date;
            	$editDate = $data['edit_date'] ? $data['edit_date'] : $date;

            	if(!isset($data['sunhao'])) {
            		$data['sunhao'] = 5;
            	}

                $SQL .= "(
                			'{$type}',
							'{$data['serial_number']}',
							'{$data['stock_type']}',
							'{$data['stock_code']}',
							'{$data['stock_name']}',
							'{$data['stock_model']}',
							'{$data['stock_packaging']}',
							'{$data['stock_total']}',
							'{$data['stock_serial_number']}',
							'{$data['stock_factory']}',
							'{$data['stock_mark']}',
							'{$createDate}',
							'{$editDate}',
							'{$_SESSION ['USER_ID']}',
							'{$_SESSION ['USER_ID']}',
							'{$parentId_temp}',
							'{$data['sunhao']}',
							'{$data['realOutNum']}',
							'{$data['outStockNum']}',
							'{$data['mustOutNum']}',
							'{$pid_temp}',
							'{$data['sendid']}',
							'{$status}',
							'1'
						),";
            }
        }
        $SQL = substr($SQL, 0, -1);

    	if ($this->_db->query($SQL) != false) {
			$lastInsertID = $this->_db->insert_id();
		} else {
			$errorList[] = $SQL;
		}

        if (count($errorList) == 0) {
            $isSave = true;
        } else {
            $msg = $this->processMsg($errorList);
            exit($this->alertResult('alertResult', $msg, false));
        }

        return array($isSave, $date);
    }

    /**
     * 保存在SESSION中Config的配置名称、描述、冲突
     * @param int $productId
     * @return Boolean
     */
    public function saveConfig($datas, $SFId) {
        $isSave = false;
        $errorList = array();

        foreach ($datas as $key => $val) {
            $SQL = "INSERT INTO {$this->material_config}(
            			`name`,
            			`clash`,
            			`description`,
            			`semifinishedId`,
            			`deleted`
            			) VALUES(
            			'{$val['name']}',
            			'{$val['clash']}',
            			'{$val['description']}',
            			'{$SFId}',
            			'0'
            			)";
            if ($this->_db->query($SQL) != false) {
                $lastInsertID = $this->_db->insert_id();
                if ($lastInsertID) {
                    if (!$this->saveDetail($val['detail'], 'config', $SFId,$lastInsertID)) {
                        $errorList[] = $SQL;
                    }
                }
            } else {
                $errorList[] = $SQL;
            }
        }

        if (count($errorList) == 0) {
            $isSave = true;
        } else {
            $msg = $this->processMsg($errorList);
            exit($this->alertResult('alertResult', $msg, false));
        }
        return $isSave;
    }
    /**
     *
     * 保存配置中的config
     * @param unknown_type $id
     * @param unknown_type $datas
     */
    public function saveConfigBom($id, $datas, $pid) {
        $isSave = false;
        $errorList = array();

        foreach ($datas as $data) {
            $SQL = "INSERT INTO {$this->table_bom_config_bom}(`name`, `description`, `clash`, `product_id`,`pid`) VALUES('{$data['name']}', '{$data['description']}', '{$data['clash']}', '{$id}','{$pid}')";
            if ($this->_db->query($SQL) != false) {
                $lastInsertID = $this->_db->insert_id();
                if ($lastInsertID) {

                    if (!$this->saveDetailBom($lastInsertID, $data['config'], $pid, 'config')) {
                        $errorList[] = $SQL;
                    }
                }
            } else {
                $errorList[] = $SQL;
            }
        }

        if (count($errorList) == 0) {
            $isSave = true;
        } else {
            $msg = $this->processMsg($errorList);
            exit($this->alertResult('alertResult', $msg, false));
        }
        return $isSave;
    }

    public function saveAccessories($id, $list) {

        $configurationDao = new model_stock_productinfo_configuration();
        //新增配件清单
        if (is_array($list)) {
            $accessItemArr = $this->setItemMainId("hardWareId", $id, $list);
            $accessItemObj = $configurationDao->saveDelBatch($accessItemArr);
        }
    }

    public function saveConfigItem($id, $list) {
        $configurationDao = new model_stock_productinfo_configuration();
        //新增配置信息
        if (is_array($list)) {
            $configItemArr = $this->setItemMainId("hardWareId", $id, $list);
            $configItemObj = $configurationDao->saveDelBatch($configItemArr);
        }
    }

    public function addConfigList($fileData) {

        //初始化
        $result = array();
        $this->initSession();
        set_time_limit(0);

        //包含类
        require_once WEB_TOR . 'includes/classes/PHPExcel.php';
        require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
        require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel2007.php';

        //判断Excel版本
        $fileName = $_FILES['upload_excel_file']['tmp_name'];
        $PHPExcel = new PHPExcel();
        $PHPReader = new PHPExcel_Reader_Excel2007($PHPExcel);
        if (!$PHPReader->canRead($fileName)) {
            $PHPReader = new PHPExcel_Reader_Excel5($PHPExcel);
        }

        //读取Excel文件内数据
        if ($PHPReader->canRead($fileName)) {
            $Excel = $PHPReader->load($fileName);
            $sheetNameArr = mb_iconv($Excel->getSheetNames());

            //获取SESSION中的Tag数
            $countInSesstion = $this->getAddConfigTagCount();

            //获取SHEET总数并遍历
            for ($i = 0; $i < $Excel->getSheetCount(); $i++) {
                $countInSesstion++;
                $datas = array();
                $inSheetData = mb_iconv($Excel->getSheet($i)->toArray());

                if (count($inSheetData) > 0) {

                    switch ($sheetNameArr[$i]) {
                        case '配件':
                            $datas = $this->processAccessoriesSheet($inSheetData);
                            break;
                        case '结构件':
                            $datas = $this->processStructuralSheet($inSheetData);
                            break;
                        default:
                            $datas = $this->proccessStandardSheet($inSheetData);
                    }

                    //如果导入文件中该SHEET没有数据则不添加入数据集
                    if (!empty($datas)) {


                        //						$tagSpan = "
                        //							<span id='span_config_tag_id' onclick='changeAddConfigTab(\"new_{$countInSesstion}\");' style='cursor: pointer; border-width: 1px; border-style: solid;' >
                        //								<input type='checkbox' name='checkbox_confit_tag[]' value='new_{$countInSesstion}' checked='checked' />
                        //									{$sheetNameArr[$i]}
                        //								</span>
                        //							&nbsp;
                        //						";
                        $tagSpan = "<input id='config_tag_{$i}' name='config_tag_{$i}' type='button' onclick='changeAddConfigTab(\"new_{$countInSesstion}\");' style='cursor: pointer;' value='{$sheetNameArr[$i]}'>";
                        $colspan = 11;
                        $isShow = 'none';
                        $detailInfo = "
							<div id='div_config_new_{$countInSesstion}' name='div_config_new[]' style='width:100%; display:{$isShow};'  >
							<table border='1' cellpadding='1' cellspacing='1' style='width:100%;' >
						";
                        $detailInfo .= $this->normalDetailTableHeader($colspan);
                        if (isset($datas['detail'])) {
                            foreach ($datas['detail'] as $key => $data) {
                                $detailInfo .= $this->markDetailTableExt("new_{$countInSesstion}", $data);
                            }
                            $detailInfo .= "</table>";
                        }

                        if (isset($data['config'])) {
                            $detailInfo .= "
								<table border='1' cellpadding='1' cellspacing='1' style='width:100%;' >
							";
                            foreach ($datas['config'] as $key => $data) {
                                $detailInfo .= $this->normalConfigTableHeader($data, $colspan - 2);
                                if (isset($data['detail'])) {
                                    foreach ($data['detail'] as $configDetail) {
                                        $detailInfo .= $this->markConfigTableExt("new_{$countInSesstion}", $configDetail);
                                    }
                                }
                            }
                            $detailInfo .= "</table>";
                        }
                        $detailInfo .= "</div>";

                        $result['title'][] = array(
                            'id' => $countInSesstion,
                            'tagSpan' => $tagSpan
                        );
                        $result['detail'][] = array(
                            'tag' => $countInSesstion,
                            'tagDiv' => $detailInfo
                        );
                    }
                }
            }

            $this->SetAddConfigTagCount($countInSesstion);
        }
        return $result;
    }

    //保存一条detail_send数据
    public function saveDetailSend($send_name) {
    	if(!$send_name) {
    		return false;
    	}

    	$date = date('Y-m-d H:i:s');

    	$sql = "INSERT INTO `{$this->table_bom_detail_send}` SET
    				`stock_list_name` = '{$send_name}',
    				`creat_time` = '{$date}',
    				`edit_time` = '{$date}',
    				`time` = '1'";

    	$this->query($sql);
    	$send_id = $this->_db->insert_id();

    	$sql = "SELECT * FROM `{$this->table_bom_detail_send}` WHERE `id` = '{$send_id}' LIMIT 1";
    	$query = $this->query($sql);
    	return $this->fetch_array($query);
    }
	//保存一条detail_send数据
    public function getMaterialCode() {

    	$datas = array();
    	$sql = "SELECT productCode,statType FROM `{$this->table_product_info}` WHERE `statType` = 'TJCP' OR `statType` = 'TJBCP'";
    	$query = $this->query($sql);
    	while (($rs = $this->fetch_array($query)) != false) {
	    	$datas[$rs['statType']][] = $rs;
		}
    	return $datas;

    }
}