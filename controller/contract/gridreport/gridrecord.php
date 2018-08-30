<?php
/**
 * @author Michael
 * @Date 2014��11��28�� 17:30:26
 * @version 1.0
 * @description:���ѡ��¼����Ʋ�
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
	 * ��תҳ��
	 */
	function c_toViewProduct() {
		$objCode = $_GET['objCode'];
		$this->assign("objCode" ,$objCode); //ҵ�����

		$setObj = $this->setting[$objCode];
		$this->assign("title" ,$setObj["title"]); //����

		$this->c_getRecordTable($objCode); //��ȡָ���

		$this->assign("fixedThead" ,util_jsonUtil::encode($setObj["fixedThead"])); //�̶���ͷ
		$this->assign("tableUrl" ,$setObj["url"]); //��ȡ������ݵĵ�ַ
		$this->view('view-product');
	}

    /**
     * ��תҳ��
     */
    function c_toViewContractPro() {
        $this->view('view-contractPro');
    }

	/**
	 * ��ת���ָ��ҳ��
	 */
	function c_toViewProductContent() {
		$objCode = isset($_GET['objCode']) ? $_GET['objCode'] : 'productLine';// ҳ����� ��ʱд����Ԥ��������չ��ָ��ʱʹ��
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
		$this->assign("objCode" ,$str); //ҵ�����

		$setObj = $this->setting[$objCode];
		$this->assign("title" ,$setObj["title"]); //����

		$this->c_getRecordTable($objCode); //��ȡָ���

		$this->view('view-product-content');
	}

	/**
	 * ��ȡָ���
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
		if (is_array($obj) && count($obj) == count($indicatorsObj["item"]) + 4) { //ָ������δ�仯�����
			foreach ($obj as $key => $val) {
				if ($val["colName"] == 'startMonth' || $val["colName"] == 'endMonth' || $val["colName"] == 'presentation' || $val["colName"]=="unit") {
                    $this->assign($val["colName"] ,$val["colValue"]);
				} else {
					$indicatorsHtml .= "<input type='hidden' id='$val[colName]Check' value='$val[colValue]'/>";
				}
			}
		} else { //ָ��������������ɾ��������ʹ��Ĭ�����
			foreach ($indicatorsObj["item"] as $key => $val) {
				$indicatorsHtml .= "<input type='hidden' id='$val[indicatorsCode]Check' value='$val[isEnable]'/>";
			}
			$this->assign("startMonth" ,date("Y-01")); //Ĭ�ϵ����һ����
			$this->assign("endMonth" ,date("Y-m")); //Ĭ�ϵ�ǰ�·�
			$this->assign("presentation" ,1);
			//Ĭ����������
			$this->service->addDefault();
        }

		$this->assign("indicatorsHtml" ,$indicatorsHtml); //ָ��
	}

	/**
	 * ���湴ѡ��¼
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
	 * ����
	 */
	function c_excelOut() {
		$obj = $_POST;
		//����������
		$colModel = stripslashes(stripslashes($obj['colModel'])); //ȥ��ת���
		$colModel = iconv("GBK" ,"UTF-8" ,$colModel); //תΪutf8���룬�����޷���JsonתΪ����
		$colModel = json_decode($colModel ,true); //Json�ַ���תΪ����
		$colModel = util_jsonUtil::iconvUTF2GBArr($colModel); //ת��GBK
		//���ϱ�ͷ����
		$parentColName = explode(',' ,$obj['parentColName']);
		//���ַ�ʽ(1:�ۼƣ�2:����)
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
		foreach ($setObj["fixedThead"] as $key => $val) { //�̶���ͷ��ʼ��
			array_push($colArr ,array("name" => $val["display"]));
		}

		foreach ($parentColName as $key => $val) {
			array_push($colArr ,array("name" => $val));
		}
		$rowDataKey = array(); //���ݵ��±�
		if (is_array($colModel)) {
			foreach ($colModel as $key => $val) {
				if (isset($val['parentCol'])) {
					//�����+1��Ϊ�������̶��ı�ͷ
					if (!isset($colArr[$val['parentCol'] + $fixedTheadNum]['children'])) {
						$colArr[$val['parentCol'] + $fixedTheadNum]['children'] = array();
					}
					array_push($colArr[$val['parentCol'] + $fixedTheadNum]['children'] ,$val['display']);
				}
				array_push($rowDataKey ,$val["name"]);
			}
		}

		$rowData = array(); //����
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