<?php
/**
 * @author Show
 * @Date 2014��1��7�� ���ڶ� 10:27:48
 * @version 1.0
 * @description:������Ӧ��-������Դ�� Model��
 */
 class model_outsourcing_outsourcessupp_vehicle  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcessupp_vehicle";
		$this->sql_map = "outsourcing/outsourcessupp/vehicleSql.php";
		parent::__construct ();
	}

	//��˾Ȩ�޴��� TODO
	// protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

	/**
	 * excel����
	 */
	function addExecelData_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();
		$inArr = array();//��������
		$linkmanArr = array();//��������
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		$codeDao=new model_common_codeRule();
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		$vehiclesuppDao = new model_outsourcing_outsourcessupp_vehiclesupp();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){

				//������ѭ��
				foreach($excelData as $key => $val){
					$actNum = $key + 1;
					if( empty($val[0])){
						continue;
					}else{
						//��������
						$inArr = array();

						//��Ӧ������
						if(!empty($val[0])&&trim($val[0])!=''){
							$vehiclesuppObj = $vehiclesuppDao->find(array("suppName" => $val[0]));
							if(!$vehiclesuppObj){
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�ó�����Ӧ����Ϣ������</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}else{
								$inArr['suppName'] = trim($val[0]);
								$inArr['suppId'] = $vehiclesuppObj['id'];
								$inArr['suppCode'] = $vehiclesuppObj['suppCode'];
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��Ӧ������Ϊ��</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//�ص�
						if(!empty($val[1])&&trim($val[1])!=''){
							$inArr['place'] = trim($val[1]);
						}

						//���ƺ�
						if(!empty($val[2])&&trim($val[2])!=''){
							$inArr['carNumber'] = trim($val[2]);
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!���ƺ�Ϊ��</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//����
						if(!empty($val[3])&&trim($val[3])!=''){
							$inArr['carModel'] = trim($val[3]);
						}

						//Ʒ��
						if(!empty($val[4])&&trim($val[4])!=''){
							$inArr['brand'] = trim($val[3]);
						}

						//����
						if(!empty($val[5])&&trim($val[5])!=''){
							$inArr['displacement'] = trim($val[5]);
						}

						//�����������
						if(!empty($val[6])&&trim($val[6])!=''){
							$inArr['powerSupply'] = trim($val[6]);
						}

						//�ۺ��ͺ�
						if(!empty($val[7])&&trim($val[7])!=''){
							$inArr['oilWear'] = trim($val[7]);
						}

						//����ʱ��
						if(!empty($val[8])&& $val[8] != '0000-00-00'&&trim($val[8])!=''){
							$val[8] = trim($val[8]);
							if(!is_numeric($val[8])){
								$inArr['buyDate'] = $val[8];
							}else{
								$recorderDate = date('Y-m-d',(mktime(0,0,0,1, $val[8] - 1 , 1900)));
								if($recorderDate=='1970-01-01'){
									$entryDate = date('Y-m-d',strtotime ($val[8]));
									$inArr['buyDate'] = $entryDate;
								}else{
									$inArr['buyDate'] = $recorderDate;
								}
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��������Ϊ��</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//˾��
						if(!empty($val[9])&&trim($val[9])!=''){
							$inArr['driver'] = trim($val[9]);
						}

						//��ϵ�绰
						if(!empty($val[10])&&trim($val[10])!=''){
							$inArr['phoneNum'] = trim($val[10]);
						}

						//���֤��
						if(!empty($val[11])&&trim($val[11])!=''){
							$inArr['idNumber'] = trim($val[11]);
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!���֤��Ϊ��</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//��ʻ֤
						if(!empty($val[12])&&trim($val[12])!=''){
							$inArr['drivingLicence'] = trim($val[12]);
						}

						//��ʻ֤
						if(!empty($val[13])&&trim($val[13])!=''){
							$inArr['vehicleLicense'] = trim($val[13]);
						}

						//����
						if(!empty($val[14])&&trim($val[14])!=''){
							$inArr['insurance'] = trim($val[14]);
						}

						//����
						if(!empty($val[15])&&trim($val[15])!=''){
							$inArr['annualExam'] = trim($val[15]);
						}

						//�⳵����
						if(!empty($val[16])&&trim($val[16])!=''){
							$inArr['rentPrice'] = trim($val[16]);
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!�⳵����Ϊ��</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						$newId = parent::add_d($inArr,true);
						if($newId){
							$tempArr['result'] = '����ɹ�';
						}else{
							$tempArr['result'] = '<font color=red>����ʧ��</font>';
						}
						$tempArr['docCode'] = '��' . $actNum .'������';
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			}
		}
	}
}
?>