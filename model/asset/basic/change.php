<?php

/*
 * Created on 2011-11-16
 *�䶯��ʽcontrol��
 * @author chenzb
 */

class model_asset_basic_change extends model_base {

	public $db;
	function __construct() {
		$this->tbl_name = "oa_asset_change";
		$this->sql_map = "asset/basic/changeSql.php";
		parent :: __construct();

	}

	/**
	 * ��ɾ������isDel��Ϊ1ʱΪ��ɾ��
	 *chenzb
	 */
	function deletes_d($ids) {
		try {
			$rows = $this->get_d($ids);
			$cardDao = new model_asset_assetcard_assetcard();
			$relCardInfo = $cardDao->findAll(array (
				'changeTypeCode' => $rows['code']
			));
			if (is_array($relCardInfo) && count($relCardInfo) > 0) {
				throw new Exception();
			} else {
				$isdel = isset ($_GET['isDel']) ? $_GET['isDel'] : null;
				$changeObj = array (
					"id" => $ids,
					"isDel" => "1"
				);
				$this->updateById($changeObj);
			}
			return true;
		} catch (Exception $e) {
			throw $e;
			return false;
		}
	}


	/**
	 * �䶯��ʽ����
	 */
	 function import_d($objKeyArr){
	 	try{
	 		$this->start_d();
	 		$returnFlag = true;
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
//				echo "<pre>";
//				print_R($excelData);
				if ($excelData) {
					foreach ($excelData as $key=>$val){
						//��ʽ�����룬ɾ������Ŀո��������Ϊ�գ���������ݲ�����Ч��
						$excelData[$key][0] = str_replace( ' ','',$val[0]);
						$excelData[$key][1] = str_replace( ' ','',$val[1]);
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
					$rows = $this->list_d();
					$repeatCodeArr = array();
					foreach( $objectArr as $key=>$val ){
						foreach( $rows as $index=>$value ){
							if($val['code'] == $value['code']){
								$repeatCodeArr[$key]['docCode'] = $value['code'];
								$repeatCodeArr[$key]['result'] = '�����ظ�������ʧ�ܣ�';
							}elseif($val['name']==''){
								$repeatCodeArr[$key]['docCode'] = $value['code'];
								$repeatCodeArr[$key]['result'] = '����Ϊ�գ�����ʧ�ܣ�';
							}elseif($val['name']==$value['name']){
								$repeatCodeArr[$key]['docCode'] = $value['code'];
								$repeatCodeArr[$key]['result'] = '�����ظ�������ʧ�ܣ�';
							}
						}
						if($val['type'] == '����'){
							$objectArr[$key]['type']=0;
						}elseif($val['type'] == '����'){
							$objectArr[$key]['type']=1;
						}
					}
					if( count($repeatCodeArr)>0 ){
				 		$returnFlag = false;
						$title = '�䶯��ʽ������';
						$thead = array( '���','���' );
						echo "<script>alert('����ʧ��')</script>";
						echo util_excelUtil::showResult($repeatCodeArr,$title,$thead);
					}else{
						$this->saveDelBatch($objectArr);
						echo "<script>alert('����ɹ�');self.parent.tb_remove();if(self.parent.show_page)self.parent.show_page(1);</script>";
					}
				} else {
					msg( "�ļ������ڿ�ʶ������!");
				}
			} else {
				msg( "�ϴ��ļ����Ͳ���EXCEL!");
			}
	 		$this->commit_d();
	 		return $returnFlag;
	 	}catch(Exception $e){
	 		$this->rollBack();
	 		return 0;
	 	}
	}



}
?>
