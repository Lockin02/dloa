<?php
/**
 * @author Show
 * @Date 2011��12��25�� ������ 14:36:05
 * @version 1.0
 * @description:�⳵��λ(oa_carrental_units) Model��
 */
 class model_carrental_units_units  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_carrental_units";
		$this->sql_map = "carrental/units/unitsSql.php";
		parent::__construct ();
	}


	/***************************************************************************************************
	 * ------------------------------����Ϊ���ýӿڷ���,����Ϊ����ģ��������------------------------------
	 *************************************************************************************************/

	/**
	 * ��д����
	 */
	function add_d($object){
		return parent::add_d($object,true);
	}

	/**
	 * ���ݶ�ȡEXCEL�е���Ϣ���뵽ϵͳ��
	 * @param $stockArr
	 * importUnitsInfo()--->importUnitsInfo()
	 */
	function importUnitsInfo($excelData) {
			set_time_limit ( 0 );
			$resultArr = array ();//�������
		    $addArr = array();//��ȷ��Ϣ����
			$datadictArr = array();//�����ֵ�����
			$datadictDao = new model_system_datadict_datadict();
			$countryArr = array();//��������
			$countryDao = new model_system_procity_country();
			$provinceArr = array();//ʡ������
			$provinceDao = new model_system_procity_province();
			$cityArr = array();//��������
			$cityDao = new model_system_procity_city();
			if(is_array($excelData)){
				//������ѭ��
				foreach($excelData as $key => $val){
					$val[0] = str_replace( ' ','',$val[0]);
					$val[1] = str_replace( ' ','',$val[1]);
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1])){
						continue;
					}else{
						//���ⵥλ����
						if(!empty($val[0])){
							$unitName = $val[0];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!û����д�ĳ��ⵥλ';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//����
						if(!empty($val[2])){
							$countryName = $val[2];
							if(!isset($countryArr[$val[2]])){

							   $sql = "select countryCode from oa_system_country_info where countryName = '$countryName'"; //����ID
				               $countryN =  $this->_db->getArray($sql);

				               $countryCode = $countryN[0]['countryCode'];

								if(!empty($countryCode)){
									$countryCode = $countryArr[$val[2]]['countryCode'] = $countryCode;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '����ʧ��!û�ж�Ӧ�Ĺ���';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$countryCode = $countryArr[$val[2]]['countryCode'];
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!û���������';
							array_push( $resultArr,$tempArr );
							continue;
						}


						//ʡ��
						if(!empty($val[3])){
							$provinceName = $val[3];
							if(!isset($provinceArr[$val[3]])){
								$provinceCode = $provinceDao->getCodeByName($val[3]);
								if(!empty($provinceCode)){
									$provinceCode = $provinceArr[$val[3]]['provinceCode'] = $provinceCode;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '����ʧ��!û�ж�Ӧ��ʡ��';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$provinceCode = $provinceArr[$val[3]]['provinceCode'];
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!û������ʡ��';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//����
						if(!empty($val[4])){
							$cityName = $val[4];
							if(!isset($cityArr[$val[4]])){

							   $sql = "select cityCode from oa_system_city_info where cityName = '$cityName'"; //����ID
				               $cityN =  $this->_db->getArray($sql);

				               foreach($cityN as $k => $v){
			                       $cityCode = $v['cityCode'];
				               }

								if(!empty($cityCode)){
									$cityCode = $cityArr[$val[4]]['cityCode'] = $cityCode;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '����ʧ��!û�ж�Ӧ�ĳ���';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$cityCode = $cityArr[$val[4]]['cityCode'];
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!û���������';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//��λ����

						if(!empty($val[5])){
							$val[5] = trim($val[5]);
							if(!isset($datadictArr[$val[5]])){
								$rs = $datadictDao->getCodeByName('DWXZ',$val[5]);
								if(!empty($rs)){
									$unitNature = $datadictArr[$val[5]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '����ʧ��!�����ڵĵ�λ����';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$unitNature = $datadictArr[$val[5]]['code'];
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!û�е�λ����';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//��ϵ��
						if(!empty($val[6])){
							$linkMan = $val[6];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!û����д��ϵ��';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//��ϵ�绰
						if(!empty($val[7])){
							$linkPhone = $val[7];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!û����д��ϵ�绰';
							array_push( $resultArr,$tempArr );
							continue;
						}

						$updateRows = array(
							'unitName' => $unitName,
							'address' => $val[1],
							'countryName' => $countryName,
							'countryCode' => $countryCode,
							'provinceName' => $provinceName,
							'provinceCode' => $provinceCode,
							'cityName' => $cityName,
							'cityCode' => $cityCode,
							'unitNature' => $unitNature,
							'linkMan' => $linkMan,
							'linkPhone' => $linkPhone,
							'remark' => $val[8]
						);
							if ($this->add_d ($updateRows,true)) {
								$tempArr['result'] = '���³ɹ�';
							}else{
								$tempArr['result'] = '�޸�������';
							}

						$tempArr['docCode'] = '��' . $actNum .'������';
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			} else {
				msg( "�ļ������ڿ�ʶ������!");
			}

	}


}
?>