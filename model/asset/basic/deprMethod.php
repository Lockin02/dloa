<?php

/**
 *
 * �۾ɷ�ʽmodel
 * @author fengxw
 *
 */
class model_asset_basic_deprMethod extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_deprMethod";
		$this->sql_map = "asset/basic/deprMethodSql.php";
		parent::__construct ();
	}

	/**
	 * �۾ɷ�ʽ����
	 */
	 function import_d($objKeyArr){
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();
		$objectArr = array();
		$excelData = array ();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			//�ж��Ƿ��������Ч����
			echo "<pre>";
			print_R($excelData);
			if ($excelData) {
				foreach ($excelData as $key=>$val){
					//����̱��ƻ����ƺ��������Ƹ�ʽ����ɾ������Ŀո����������Ϊ�գ���������ݲ�����Ч��
					$excelData[$key][0] = str_replace( ' ','',$val[0]);
					if( $excelData[$key][0] == '' ){
						unset( $excelData[$key] );
					}
				}
				foreach ( $excelData as $rNum => $row ) {
					foreach ( $objKeyArr as $index => $fieldName ) {
						//��ֵ������Ӧ���ֶ�
						$objectArr [$rNum] [$fieldName] = $row [$index];
					}
				}
				echo "<pre>";
				print_R($objectArr);
//				foreach( $objectArr as $key=>$val ){
//					$condition = array(
//						'mailNo' => $objectArr[$key]['mailNo']
//					);
//					$rows = array(
//						'number' => $objectArr[$key]['number'],
//						'weight' => $objectArr[$key]['weight'],
//						'serviceType' => $objectArr[$key]['serviceType'],
//						'fare' => $objectArr[$key]['fare'],
//						'anotherfare' => $objectArr[$key]['anotherfare'],
//						'mailMoney' => $objectArr[$key]['mailMoney']
//					);
//					$this->update( $condition,$rows );
//					$tempArr['docCode']=$val['mailNo'];
//					if ($this->_db->affected_rows () == 0) {
//						$tempArr['result']='����ʧ�ܣ������Ų����ڻ�������Ч��';
//					}else{
//						$tempArr['result']='����ɹ���';
//					}
//					array_push( $resultArr,$tempArr );
//				}
//				return $resultArr;
			} else {
				msg( "�ļ������ڿ�ʶ������!");
			}
		} else {
			msg( "�ϴ��ļ����Ͳ���EXCEL!");
		}

	}

}
?>
