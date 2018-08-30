<?php
/**
 * @author Show
 * @Date 2013��6��7�� ������ 11:24:39
 * @version 1.0
 * @description:�ִ���Ϣ�� Model��
 */

class model_finance_otherfee_otherfee extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_otherfee";
		$this->sql_map = "finance/otherfee/otherfeeSql.php";
		parent :: __construct();
	}
	
	
	function importExcel($stockArr) {
		try {
			$this->start_d ();
			set_time_limit ( 0 );
			$resultArr = array ();//�������
			$addArr = array();//��ȷ��Ϣ����

				foreach ( $stockArr as $key => $obj ) {
				$course = $obj['subjectName'];
			//	$sql = "select id from customer where Name = '$course'"; //���ҿͻ�ID
			/*	$cus =  $this->_db->getArray($sql); //
				foreach($cus as $k => $v){
					$cusId = $v['id'];
				}  */ 
			    $debit = $obj['debit'];
				$linkSql = "select id from oa_finance_otherfee where subjectName = '$course'";
				$subjectName =  $this->_db->getArray($linkSql); //
				if(!empty($obj['subjectName']) && !empty($obj['debit']) && empty($subjectName) && !empty($obj['accountYear']) && !empty($obj['accountPeriod']) && !empty($obj['feeDeptName'])){
					//                      $addArr[$key]['subjectName'] = $obj['subjectName'];
					$addArr[$key]['accountYear'] = $obj['accountYear'];
					$addArr[$key]['accountPeriod'] = $obj['accountPeriod'];
					$addArr[$key]['summary'] = $obj['summary'];
					$addArr[$key]['subjectName'] = $obj['subjectName'];
					$addArr[$key]['debit'] = $obj['debit'];
					$addArr[$key]['chanceCode'] = $obj['chanceCode']; 
					$addArr[$key]['trialProjectCode'] = $obj['trialProjectCode'];
					$addArr[$key]['feeDeptName'] = $obj['feeDeptName'];
					$addArr[$key]['contractCode'] = $obj['contractCode'];
					$addArr[$key]['province'] = $obj['province'];
					
	
					array_push ( $resultArr, array ("docCode" => $obj['subjectName'], "result" => "����ɹ���" ) );
				}else if(empty($obj['accountYear'])){
					array_push ( $resultArr, array ("docCode" => $obj['subjectName'], "result" => "ʧ�ܣ�������Ϊ��" ) );
				}else if(empty($obj['accountPeriod'])){
					array_push ( $resultArr, array ("docCode" => $obj['subjectName'], "result" => "ʧ�ܣ�����ڼ�Ϊ��" ) );
				}else if(empty($obj['subjectName'])){
					array_push ( $resultArr, array ("docCode" => $obj['subjectName'], "result" => "ʧ�ܣ���Ŀ����Ϊ��" ) );
				}else if(empty($obj['debit'])){
					array_push ( $resultArr, array ("docCode" => $obj['subjectName'], "result" => "ʧ�ܣ��跽���Ϊ��" ) );
				}else if(empty($obj['feeDeptName'])){
					array_push ( $resultArr, array ("docCode" => $obj['subjectName'], "result" => "ʧ�ܣ����ù�������Ϊ��" ) );
				}else if(!empty($subjectName)){
					array_push ( $resultArr, array ("docCode" => $obj['subjectName'], "result" => "ʧ�ܣ���Ŀ�����Ѵ���" ) );
				}
			}
			$this->addBatch_d($addArr);
			$this->commit_d ();
			return $resultArr;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

		/**
	 * ������������
	 * $budgetTypex �����������
	 */
	/* function eportExcel(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();
			
		//��Ŀ��������
		$projectCache = array();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );print_r($excelData);
			spl_autoload_register("__autoload");
			if(is_array($excelData)){
				//������ѭ��
				foreach($excelData as $key => $val){
					$val[8] = str_replace( ' ','',$val[8]);
					$val[1] = str_replace( ' ','',$val[1]);
					$actNum = $key + 2;
					if(empty($val[8]) && empty($val[1])){
						continue;
					}else{
						$contractCode = $val[8];
						$service = $this->service;
						//��Ŀ���
						if(!empty($contractCode)){
							$otherfeeDao = new model_finance_otherfee_otherfee();
							$otherfeeInfo = $otherfeeDao->find(array('contractCode' => $contractCode),null,'contractCode,id,chanceCode');
							
							if(empty($otherfeeInfo)){
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '����ʧ��!�޴�<��Ŀ���>';
								array_push( $resultArr,$tempArr );
								continue;
							}else{
								$id = $otherfeeInfo['id'];
								$chanceCode = $otherfeeInfo['chanceCode'];
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!<��ͬ���>����Ϊ��';
							array_push( $resultArr,$tempArr );
							continue;
						}
						$tempArr['docCode'] = '��' . $actNum .'������';
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			} else {
				msg( "�ļ������ڿ�ʶ������!");
			}
		} else {
			msg( "�ϴ��ļ����Ͳ���EXCEL!");
		}
	}
 */

	/**
	 *  ����add
	 */
	/**function add_d($object){
	 *	//�״���������ʼ��������ʵ������
	 *	$object['actNum'] = $object['initNum'];
	 *	return parent::add_d($object,true);
	}*/

	/**
	 * ��дedit
	 */
	/*function edit_d($object){
	 *	//�״���������ʼ��������ʵ������
	 *	$object['actNum'] = $object['initNum'];
	 *	return parent::edit_d($object,true);
	}*/


	/*************************** �ⲿҵ����÷��� *******************/
	/**
	 * ���ӿ��
	 */
	/**function addStockNum_d($id,$inNum){
	 *	$sql = "update ".$this->tbl_name." set actNum = actNum + ".$inNum." where id = ".$id;
	 *	return $this->query($sql);
	}*/
}
?>