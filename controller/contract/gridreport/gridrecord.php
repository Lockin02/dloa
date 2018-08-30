<?php
/**
 * @author Michael
 * @Date 2014年11月28日 17:30:26
 * @version 1.0
 * @description:表格勾选记录表控制层
 */
class controller_contract_gridreport_gridrecord extends controller_base_action {

	function __construct() {
		$this->objName = "gridrecord";
		$this->objPath = "contract_gridreport";
		include (WEB_TOR.'model/contract/gridreport/gridrecordRegister.php');
		$this->setting = isset($setting) ? $setting : array();
		parent::__construct ();
	}

	/**
	 * 跳转页面
	 */
	function c_toViewProduct() {
		$objCode = $_GET['objCode'];
		$this->assign("objCode" ,$objCode); //业务编码

		$setObj = $this->setting[$objCode];
		$this->assign("title" ,$setObj["title"]); //标题

		$this->c_getRecordTable($objCode); //获取指标表

		$this->assign("fixedThead" ,util_jsonUtil::encode($setObj["fixedThead"])); //固定表头
		$this->assign("tableUrl" ,$setObj["url"]); //获取表格数据的地址
		$this->view('view-product');
	}

    /**
     * 跳转页面
     */
    function c_toViewContractPro() {
        $this->view('view-contractPro');
    }

	/**
	 * 跳转表格指标页面
	 */
	function c_toViewProductContent() {
		$objCode = isset($_GET['objCode']) ? $_GET['objCode'] : 'productLine';// 页面编码 暂时写死，预留后续扩展多指标时使用
		$gridindicatorsDao = new model_contract_gridreport_gridindicators();
		$rs = $gridindicatorsDao->findAll(null,null,'objCode,objName');
		$str = "";
		if(!empty($rs)){
			foreach ($rs as $v){
				if($v['objCode'] == $objCode){
					$str.= "<option value='".$v['objCode']."' selected>".$v['objName']."</option>";
				}else{
					$str.= "<option value='".$v['objCode']."'>".$v['objName']."</option>";
				}
			}
		}
		$this->assign("objCode" ,$str); //业务编码

		$setObj = $this->setting[$objCode];
		$this->assign("title" ,$setObj["title"]); //标题

		$this->c_getRecordTable($objCode); //获取指标表

		$this->view('view-product-content');
	}

	/**
	 * 获取指标表
	 */
	function c_getRecordTable($objCode) {
		$obj = $this->service->findAll(array("userId" => $_SESSION["USER_ID"] ,"recordCode" => $objCode));
		$indicatorsHtml = '';
		$indicatorsDao = new model_contract_gridreport_gridindicators();
		$indicatorsObj = $indicatorsDao->find(array("objCode" => $objCode));
		if (is_array($indicatorsObj["item"])) {
			foreach ($indicatorsObj["item"] as $key => $val) {
				$indicatorsHtml .= <<<EOT
					$val[indicatorsName]:
					<input type="checkbox" class="checkItems" id="$val[indicatorsCode]" val="$val[indicatorsName]" value="$val[indicatorsCode]"/>&nbsp;&nbsp;&nbsp;&nbsp;
EOT;
			}
		}
		if (is_array($obj) && count($obj) == count($indicatorsObj["item"]) + 4) { //指标设置未变化的情况
			foreach ($obj as $key => $val) {
				if ($val["colName"] == 'startMonth' || $val["colName"] == 'endMonth' || $val["colName"] == 'presentation' || $val["colName"]=="unit") {
                    $this->assign($val["colName"] ,$val["colValue"]);
				} else {
					$indicatorsHtml .= "<input type='hidden' id='$val[colName]Check' value='$val[colValue]'/>";
				}
			}
		} else { //指标设置新增或者删除都重新使用默认情况
			foreach ($indicatorsObj["item"] as $key => $val) {
				$indicatorsHtml .= "<input type='hidden' id='$val[indicatorsCode]Check' value='$val[isEnable]'/>";
			}
			$this->assign("startMonth" ,date("Y-01")); //默认当年第一个月
			$this->assign("endMonth" ,date("Y-m")); //默认当前月份
			$this->assign("presentation" ,1);
			//默认新增配置
			$this->service->addDefault();
        }

		$this->assign("indicatorsHtml" ,$indicatorsHtml); //指标
	}

	/**
	 * 保存勾选记录
	 */
	function c_saveRecord() {
		$obj = $_POST;
		$objCode = $obj['objCode'];
		unset($obj['objCode']);
		$rs = $this->service->saveRecord_d($obj ,$objCode);
		if ($rs) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 导出
	 */
	function c_excelOut() {
		$obj = $_POST;
		//内容行数组
		$colModel = stripslashes(stripslashes($obj['colModel'])); //去除转义符
		$colModel = iconv("GBK" ,"UTF-8" ,$colModel); //转为utf8编码，否则无法从Json转为数组
		$colModel = json_decode($colModel ,true); //Json字符串转为数组
		$colModel = util_jsonUtil::iconvUTF2GBArr($colModel); //转回GBK
		//复合表头数组
		$parentColName = explode(',' ,$obj['parentColName']);
		//呈现方式(1:累计：2:分月)
		$presentation = $obj['presentation'];

		$objCode = $obj['objCode'];
		$setObj = $this->setting[$objCode];
		$daoName = 'model_'.$setObj['keyObj'];
		$funcName = $setObj['excelFunc'];
		$dao = new $daoName();
		set_time_limit(0);
		ini_set('memory_limit' ,'128M');
		$rows = $dao->$funcName($obj);

		$fixedTheadNum = count($setObj["fixedThead"]);
		$colArr = array();
		foreach ($setObj["fixedThead"] as $key => $val) { //固定表头初始化
			array_push($colArr ,array("name" => $val["display"]));
		}

		foreach ($parentColName as $key => $val) {
			array_push($colArr ,array("name" => $val));
		}
		$rowDataKey = array(); //数据的下标
		if (is_array($colModel)) {
			foreach ($colModel as $key => $val) {
				if (isset($val['parentCol'])) {
					//这里的+1是为了跳过固定的表头
					if (!isset($colArr[$val['parentCol'] + $fixedTheadNum]['children'])) {
						$colArr[$val['parentCol'] + $fixedTheadNum]['children'] = array();
					}
					array_push($colArr[$val['parentCol'] + $fixedTheadNum]['children'] ,$val['display']);
				}
				array_push($rowDataKey ,$val["name"]);
			}
		}

		$rowData = array(); //数据
		if(is_array($rows)){
			foreach ( $rows as $key => $val ) {
				foreach ($rowDataKey as $k => $v) {
					$rowData[$key][$v] = $val[$v];
				}
			}
		}

		return model_contract_gridreport_importUtil::exportContract($colArr ,$rowData ,$setObj["title"]);
	}
}
?>