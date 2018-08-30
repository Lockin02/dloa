<?php
/**
 * @description: ��Ӧ����ʽ��
 * @date 2010-11-10 ����02:07:59
 * @author oyzx
 * @version V1.0
 */
class model_supplierManage_formal_flibrary extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_lib";
		$this->sql_map = "supplierManage/formal/flibrarySql.php";
		parent::__construct ();

		$this->datadictDao = new model_system_datadict_datadict ();
		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (array ("statusEName" => "common", "statusCName" => "����", "key" => "1" ), array ("statusEName" => "stop", "statusCName" => "����", "key" => "2" ) );

	}

    //��˾Ȩ�޴��� TODO
    protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

	/**
	 * @desription �Ҹ���Ĺ�Ӧ�̻�ȡ���ݷ���
	 * @param tags
	 * @date 2010-11-16 ����02:22:41
	 */
	function myResSupp($manageUserId) {
		$this->searchArr['manageUserId'] = $manageUserId;
		$arr = $this->pageBySqlId ( 'myResSupp' );
		if(is_array($arr)){
			foreach ( $arr as $key => $val ) {
				$arr [$key] ['status'] = $this->statusDao->statusKtoC ( $arr [$key] ['status'] );
			}
		}
		return $arr;
	}

    /**
     * ���ݹ�Ӧ��ID��ȡ��Ϣ
     * @param $id
     * @return mixed
     */
    function  getSuppInfoById($id){
        $this->searchArr['id'] = $id;
        $arr = $this->listBySqlId ( 'myResSupp' );
        return $arr[0];
   }
	/**
	 * ����������ȡ����
	 */
	/**
	 * ��ȡ�����ҳ�б�����
	 */
	function page_d() {
		$arr = parent::page_d ();
		if(is_array($arr)){
			foreach ( $arr as $key => $val ) {
				$arr [$key] ['status'] = $this->statusDao->statusKtoC ( $arr [$key] ['status'] );
			}

		}
		return $arr;
	}

	/**
	 * @desription ͨ����Ӧ��ID���¹�Ӧ�̼���
	 * @param tags
	 * @date 2010-11-17 ����06:55:57
	 */
	function changeLevel_d( $id,$level) {
		$rows=$this->get_d($id);
		$rows['suppLevel']=$level;
		$arr=parent::edit_d($rows);
	}

	/**
	 * ����Ӫ��ֱ����ӹ�
	 */
	function addByExternal_d ($object) {
		try{
			$this->start_d();
			$bankDao = new model_supplierManage_formal_bankinfo();
			$stproductDao=new model_supplierManage_formal_sfproduct();
			$linkmanDao=new model_supplierManage_formal_sfcontact();
			$datadictDao = new model_system_datadict_datadict ();
			$object['status'] = "1";		//Ĭ�ϵĹ�Ӧ��״̬������
			$codeDao=new model_common_codeRule();
			$object['busiCode']=$codeDao->supplierCode($this->tbl_name);
			$object ['suppCategoryName'] =  $datadictDao->getDataNameByCode ( $object['suppCategory'] );
			$object['suppGrade']="E";

			$object['formBelong'] = $_SESSION['USER_COM'];
			$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$object['businessBelong'] = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];
			$id=parent::add_d($object,true);
			//����������
			$this->updateObjWithFile($id);

			//���������Ϣ
			foreach($object['Bank'] as $key => $val){
				if($val['accountNum']!=""){
					$val['suppId']=$id;
					$val['busiCode']=$object['busiCode'];
					$val['suppName']=$object['suppName'];
					$bankDao->addBankInfo_d($val);
				}
			}
			//�����ϵ����Ϣ
			if(is_array($object['supplinkman'])){
				foreach($object['supplinkman'] as $linkKey=>$linkVal){
					if($linkVal['name']!=""){
						$linkVal['objCode']=generatorSerial();
						$linkVal['systemCode']=generatorSerial();
						$linkVal['parentCode']=$object['objCode'];
						$linkVal['parentId']=$id;
						$linkmanDao->add_d($linkVal,true);
					}
				}
			}
			//��ӹ�Ӧ��Ʒ��Ϣ
			$object['stproduct']['parentCode']=$object['objCode'];
			$object['stproduct']['parentId']=$id;
			$stproductDao->add_d($object['stproduct'],true);

		/*	//�����������
			$task = array (
				"suppId" => $id,
				"formCode" => "T" . date ( "YmdHis" ),//����������
				"assessType" => 'xgyspg',
				"suppName" => $object['suppName'],
				"assesManId" => $_SESSION ['USER_ID'],
				"assesManName" => $_SESSION ['USERNAME'],
				"formDate" => date("Y-m-d"),
				"purchManId" => $_SESSION ['USER_ID'],
				"purchManName" => $_SESSION ['USERNAME']
			);
			$stampDao = new model_supplierManage_assessment_task();
			$stampDao->add_d($task,true);*/
			$this->commit_d();
			return $id;
		}catch (Exception $e){
			return null;
		}

	}

	/**
	 * @desription ע�������Ϣ���޸ķ�����
	 * @param tags
	 * @date 2010-11-18 ����05:19:48
	 */
	function editinfo_d($objinfo) {
		try {
			$this->start_d();
            //��¼��������Ϣ
            $oldRow=$this->get_d($objinfo['id']);
            if(($oldRow['suppGrade']=='A'||$oldRow['suppGrade']=='B'||$oldRow['suppGrade']=='C')&&trim($objinfo['suppName'])!=trim($oldRow['suppName'])){//ֻ�кϸ�Ӧ���޸Ĳż�¼
                $usednameDao=new model_supplierManage_formal_usedname();
                $arr['suppId']=$objinfo['id'];
                $arr['busiCode']=$oldRow['busiCode'];
                $arr['suppName']=$objinfo['suppName'];
                $arr['usedName']=$oldRow['suppName'];
                $arr['newName']=$objinfo['suppName'];
                $usednameDao->add_d($arr,true);
            }
			$num = parent :: edit_d($objinfo, true);
			$bankinfo=new model_supplierManage_formal_bankinfo();
			foreach($objinfo[Bank] as $key=>$val){
				$val['busiCode']=$objinfo['busiCode'];
				$val['suppName']=$objinfo['suppName'];

				$bankinfo->addBankUpdate_d($val);
				if(empty($val['id'])){
					$val['suppId']=$objinfo['id'];
				    $bankinfo->addBankInfo_d($val);
				}
			}
			//���¸���������ϵ
			$this->updateObjWithFile($objinfo['id']);
			$this->commit_d();
			return $num;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}


	/****************** ���뵽������ **********************/
	/**
	 * ����
	 */
	function excelImport_d(){
		set_time_limit(0);
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
//			echo "<pre>";
//			print_r($excelData);
			if(is_array($excelData)){
				//������ѭ��
				foreach($excelData as $key => $val){
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4])&& empty($val[5])&& empty($val[6]) && empty($val[7]) && empty($val[8]) && empty($val[9])&& empty($val[10]) && empty($val[11])){
						continue;
					}else{
						if(!empty($val[0])){
							$busiCode = $val[0];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!û����д��Ӧ�̱��';
							array_push( $resultArr,$tempArr );
							continue;
						}

						if(!empty($val[2])){
							$suppGrade = $val[2];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!û����д��Ӧ�̵ȼ�';
							array_push( $resultArr,$tempArr );
							continue;
						}
						$conditionArr = array(
							'busiCode' => $busiCode
						);

						$updateRows = array(
							'suppGrade' => $suppGrade
						);
						$this->update( $conditionArr,$updateRows );
						if ($this->_db->affected_rows () == 0) {
							$tempArr['result'] = '���³ɹ�';
						}else{
							$tempArr['result'] = '���³ɹ�';
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
}
?>
