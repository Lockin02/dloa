<?php

/**
 * �ʲ�����model����
 *@linzx
 */
class model_asset_basic_directory extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_assettype";
		$this->sql_map = "asset/basic/directorySql.php";
		parent :: __construct();


	}

/**
 *�����ݿ����ȡ����������������ѡ��
 */
	function showSelectList_d($name) {
		if (is_array ( $name )) {
			foreach ( $name as $k => $v ) {
				$str .= '<option value="' .$v['name'] . '">';
				$str .= $v['name'];
				$str .= '</option>';
			}
				return  $str;
		}

	}
	/**
	 * ����Id��ȡ�������
	 */
	 function getCodeById_d($id){
	 	$dirObj = $this->get_d($id);
	 	//return $dirObj['code'];
	 	return $dirObj;
	 }

	/**
	 * �ʲ����ർ��
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
						if( $excelData[$key][1] == '' ){
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
						if($val['isDepr'] == '��ʹ��״̬�����Ƿ����۾�'){
							$objectArr[$key]['isDepr']=1;
						}elseif($val['isDepr'] == '����ʹ��״̬���һ�����۾�'){
							$objectArr[$key]['isDepr']=2;
						}elseif($val['isDepr'] == '����ʹ��״̬���һ�������۾�'){
							$objectArr[$key]['isDepr']=3;
						}
					}
					if( count($repeatCodeArr)>0 ){
				 		$returnFlag = false;
						$title = '�ʲ����ർ����';
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