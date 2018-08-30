<?php
/**
 * @author Michael
 * @Date 2014��11��28�� 17:30:26
 * @version 1.0
 * @description:���ѡ��¼�� Model��
 */
 class model_contract_gridreport_gridrecord  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_system_gridrecord";
		$this->sql_map = "contract/gridreport/gridrecordSql.php";
		parent::__construct ();
	}

	/**
	 * �����û���ѡ��¼
	 */
	function saveRecord_d($obj ,$objCode) {
		try {
			$this->start_d();

			$record = $this->findAll(array("userId" => $_SESSION["USER_ID"] ,"recordCode" => $objCode));

			if (is_array($record)) {
				if (count($obj) == count($record)) {
					foreach ($obj as $key => $val) {
						foreach ($record as $k => $v) {
							if ($v['colName'] == $key) {
								$this->updateById(array("id" => $v["id"] ,"colValue" => $val));
							}
						}
					}
				} else {
					$this->delete(array("userId" => $_SESSION["USER_ID"] ,"recordCode" => $objCode));
					foreach ($obj as $key => $val) {
						$newObj = array(
							"userId" => $_SESSION["USER_ID"],
							"recordCode" => $objCode,
							"colName" => $key,
							"colValue" => $val
						);
						parent::add_d($newObj);
					}
				}
			} else {
				foreach ($obj as $key => $val) {
					$newObj = array(
						"userId" => $_SESSION["USER_ID"],
						"recordCode" => $objCode,
						"colName" => $key,
						"colValue" => $val
					);
					parent::add_d($newObj);
				}
			}
			//��ָͬ�����ͣ�ʱ������ͬ������
			$this->update(array("userId" => $_SESSION["USER_ID"],"colName" => "startMonth"),array("colValue" => $obj["startMonth"]));
			$this->update(array("userId" => $_SESSION["USER_ID"],"colName" => "endMonth"),array("colValue" => $obj["endMonth"]));
			
			$this->commit_d();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack();
			return false;
		}
	}

    /**
     *  ����¼�˻�ȡ������Ϣ
     */
     function getRecordInfo(){
         $recordCode = "productLine"; // ���ñ��룬��ʱд����������չ
         $sql = "select * from oa_system_gridrecord where userId='".$_SESSION["USER_ID"]."' AND recordCode='$recordCode'" ;
         $arr = $this->_db->getArray($sql);
         return $arr;
     }
     
     /**
      * Ĭ����������
      */
     function addDefault(){
     	//�û����������������������Ĭ��ָ��ȫ����ѡ��ʱ������Ϊ����1�µ���ǰ�£����ַ�ʽΪ�ۼƣ���λΪ��Ԫ
     	$indicatorsDao = new model_contract_gridreport_gridindicators();
     	$indicatorsObj = $indicatorsDao->findAll();
     	$startMonth = date("Y-01");//Ĭ�ϵ����һ����
     	$endMonth = date("Y-m");//Ĭ�ϵ�ǰ�·�
     	$addArr = array();
     	foreach ($indicatorsObj as $key => $val){
     		$rs = $this->findAll(array("userId" => $_SESSION["USER_ID"] ,"recordCode" => $val['objCode']));
     		if(empty($rs)){
     			$defaultArr = array(
     					array(
     							'colName' => 'startMonth',
     							'colValue' => $startMonth,
     							'recordCode' => $val['objCode']
     					),
     					array(
     							'colName' => 'endMonth',
     							'colValue' => $endMonth,
     							'recordCode' => $val['objCode']
     					),
     					array(
     							'colName' => 'presentation',
     							'colValue' => 1,
     							'recordCode' => $val['objCode']
     					),
     					array(
     							'colName' => 'unit',
     							'colValue' => 2,
     							'recordCode' => $val['objCode']
     					)
     			);
     			foreach ($indicatorsObj[$key]["item"] as $k => $v) {
     				array_push($defaultArr, array('colName' => $v['indicatorsCode'],'colValue' => 1,'recordCode' => $val['objCode']));
     			}
     			$addArr = array_merge($addArr,$defaultArr);
     		}
     		continue;
     	}
     	$this->createBatch($addArr,array('userId' => $_SESSION["USER_ID"]));
     }
 }