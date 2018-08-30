<?php
/**
 * @author Michael
 * @Date 2014��11��28�� 17:30:26
 * @version 1.0
 * @description:���ѡ��¼����Ʋ�
 */
class controller_report_report_gridrecord extends controller_base_action {

	function __construct() {
		$this->objName = "gridrecord";
		$this->objPath = "report_report";
		parent::__construct ();
	}

	/**
	 * ��ת����Ʒ����Ŀ����ҳ��
	 */
	function c_toViewProduct() {
		$obj = $this->service->findAll(array("userId" => $_SESSION["USER_ID"] ,"recordCode" => 'productLineProjec'));
		if (is_array($obj)) {
			foreach ($obj as $key => $val) {
				$this->assign($val['colName'] ,$val['colValue']);
			}
		} else {
			$this->assign("contractAmount" ,1); //Ĭ�ϼ���
			$this->assign("revenue" ,1); //Ĭ�ϼ���
			$this->assign("grossProfit" ,0);
			$this->assign("grossProfitMargin" ,0);
			$this->assign("estimate" ,0);
			$this->assign("budget" ,0);
			$this->assign("finalAccounts" ,0);
			$this->assign("startMonth" ,date("Y-01")); //Ĭ�ϵ����һ����
			$this->assign("endMonth" ,date("Y-m")); //Ĭ�ϵ�ǰ�·�
			$this->assign("presentation" ,1);
		}
		$this->view('view-product');
	}

	/**
	 * ���湴ѡ��¼
	 */
	function c_saveRecord() {
		$this->service->saveRecord_d($_POST);
	}

	/**
	 * ����
	 */
	function c_excelOut() {
		$obj = $_POST;
		//����������
		$colModel = stripslashes(stripslashes($_POST['colModel'])); //ȥ��ת���
		$colModel = iconv("GBK" ,"UTF-8" ,$colModel); //תΪutf8���룬�����޷���JsonתΪ����
		$colModel = json_decode($colModel ,true); //Json�ַ���תΪ����
		$colModel = util_jsonUtil::iconvUTF2GBArr($colModel); //ת��GBK
		//���ϱ�ͷ����
		$parentColName = explode(',' ,$_POST['parentColName']);
		//���ַ�ʽ(1:�ۼƣ�2:����)
		$presentation = $_POST['presentation'];

		set_time_limit(0);
		$rows = $this->service->list_d();

		$colArr = array(array('name' => '��Ʒ��')); //��ͷ(����)��ʼ��
		foreach ($parentColName as $key => $val) {
			array_push($colArr ,array('name' => $val));
		}
		$rowDataKey = array(); //���ݵ��±�
		if (is_array($colModel)) {
			foreach ($colModel as $key => $val) {
				if (isset($val['parentCol'])) {
					//�����+1��Ϊ�������̶��ı�ͷ(��Ʒ��)
					if (!isset($colArr[$val['parentCol'] + 1]['children'])) {
						$colArr[$val['parentCol'] + 1]['children'] = array();
					}
					array_push($colArr[$val['parentCol'] + 1]['children'] ,$val['display']);
				}
				array_push($rowDataKey ,$val['name']);
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

		return model_report_report_importUtil::exportContract($colArr ,$rowData ,'��ͬ��Ŀ��');
	}
}
?>