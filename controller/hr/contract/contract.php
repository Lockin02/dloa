<?php

/**
 * @author Administrator
 * @Date 2012-05-30 19:26:14
 * @version 1.0
 * @description:��ͬ��Ϣ���Ʋ�
 */
class controller_hr_contract_contract extends controller_base_action {

	function __construct() {
		$this->objName = "contract";
		$this->objPath = "hr_contract";
		parent :: __construct();
	}

	/*
	 * ��ת����ͬ��Ϣ�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ������ϢTabҳ�б�
	 */
	function c_tabList(){
		$this->assign("userId",$_GET['userAccount']);
		$this->assign("userNo",$_GET['userNo']);
		$this->view('tablist');
	}

	/**
	 * ������ϢTabҳ�б�
	 */
	function c_tabEditList(){
		$this->assign("userId",$_GET['userAccount']);
		$this->assign("userNo",$_GET['userNo']);
		$this->view('tablist-edit');
	}

	/**
	 * ��ת��Ա���ȼ���Ϣ�б�
	 */
	function c_toDegree(){
		$this->view('degree');
	}

	/**
	 * ��ת����ͬ��Ϣ(�������к�ͬ��Ϣ���ѹ��ں�ͬ��Ϣ)
	 */
	function c_toContractTab(){
		$this->view('contracttablist');
	}

	/**
	 * ��ת���쵽�ں�ͬҳ��
	 */
	function c_toExpireContract(){
		$y = date(Y);
		$m = date(m) + 1;
		if($m == 13) {
			$m = 1;
			$y++;
		}
		if($m > 10) {
			$date = $y.'-'.$m;
		}else{
			$date = $y.'-0'.$m;
		}
		$this->assign('date',$date);
		$this->view('expireing-contract');
	}

	/**
	 * ��ת��������ͬ��Ϣҳ��
	 */
	function c_toAdd() {
		$this->assign("recorderName",$_SESSION['USERNAME']);
		$this->assign("recorderId",$_SESSION['USER_ID']);
		$this->assign("recordDate",date("Y-m-d"));

		$this->view('add',true);
	}

	/**
	 * ��ת��������ͬ��Ϣҳ��
	 */
	function c_toAddEdit() {
		$userNo=$_GET['userNo'];
		//��ȡԱ����Ϣ
		$personnelDao = new model_hr_personnel_personnel();
		$row=$personnelDao->getInfoByUserNo_d($userNo);
		if(!empty($row)){
			foreach ($row as $key => $val) {
				$this->assign($key, $val);
			}
		}else{
			$this->assign("staffName",'');
			$this->assign("userAccount",'');
			$this->assign("userNo",'');
			$this->assign("jobName",'');
			$this->assign("jobId",'');
			$this->assign("entryDate",'');
			$this->assign("becomeDate",'');
		}
		$this->assign("recorderName",$_SESSION['USERNAME']);
		$this->assign("recorderId",$_SESSION['USER_ID']);
		$this->assign("recordDate",date("Y-m-d"));

		$this->view('personnel-add',true);
	}

	/**
	 * ��ת��������ͬ��Ϣҳ��(��ְ֪ͨ���)
	 */
	function c_toAddByExternal() {
		$entryNoticeDao=new model_hr_recruitment_entryNotice();
		$entryNoticeRow=$entryNoticeDao->get_d($_GET['entryId']);
		$this->assign("entryId",$entryNoticeRow['id']);
		$this->assign("jobName",$entryNoticeRow['hrJobName']);
		$this->assign("jobId",$entryNoticeRow['hrJobId']);
		$this->assign("userAccount",$entryNoticeRow['userAccount']);
		$this->assign("userName",$entryNoticeRow['userName']);
		$this->assign("userNo",$entryNoticeRow['userNo']);
		$this->assign("recorderName",$_SESSION['USERNAME']);
		$this->assign("recorderId",$_SESSION['USER_ID']);
		$this->assign("recordDate",date("Y-m-d"));
		$this->view('external-add',true);
	}

	/**
	 * ��ת���༭��ͬ��Ϣҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts(array('conType' => 'HRHTLX'),$obj['conType']);
		$this->showDatadicts(array('conState' => 'HRHTZT'),$obj['conState']);
		$this->showDatadicts(array('conNum' => 'HRHTCS'),$obj['conNum']);

		$this->assign ( "file", $this->service->getFilesByObjId ($_GET['id'], true ) );
		$this->view('edit',true);
	}

	/**
	 * ��ת���鿴��ͬ��Ϣҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
	  	$this->assign ( "file", $this->service->getFilesByObjId ($_GET['id'], false ) );
		$this->view('view');
	}

	/**
	 * ��ȡȨ��
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/******************* S ���뵼��ϵ�� ************************/
	/**
	 * ����excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}

	/**
	 * ����excel
	 */
	function c_excelIn(){
		$title = '��ͬ��Ϣ�������б�';
		$thead = array( '������Ϣ','������' );
		$objNameArr = array (
			0 => 'userNo', //Ա�����
			1 => 'userName', //����
			2 => 'conNo', //��ͬ���
			3 => 'conName', //��ͬ����
			4 => 'conTypeName', //��ͬ����
			5 => 'conStateName', //��ͬ״̬
			6 => 'beginDate', //��ͬ��ʼʱ��
			7 => 'closeDate', //��ͬ����ʱ��
			8 => 'trialBeginDate', //���ÿ�ʼʱ��
			9 => 'trialEndDate', //���ý���ʱ��
			10 => 'conNumName', //��ͬ����
			11 => 'conContent', //��ͬ����
		);
		$resultArr = $this->service->addExecelData_d ($objNameArr);
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * ����excel����
	 */
	function c_toExcelUpdate(){
		$this->display('excel-update');
	}

	/**
	 * ����excel
	 */
	function c_excelUpdate(){
		set_time_limit(0);
		$resultArr = $this->service->updateExecelData_d ();

		$title = '��ͬ��Ϣ������½���б�';
		$thead = array('������Ϣ' ,'������');
		echo util_excelUtil::showResult($resultArr ,$title ,$thead);
	}

	/*
	 * ����excel
	 */
	 function c_toExcelOut(){
	 	$this->showDatadicts(array('conType' => 'HRHTLX'),null,true);
	 	$this->showDatadicts(array('conState' => 'HRHTZT'),null,true);
	 	$this->showDatadicts(array('conNum' => 'HRHTCS'),null,true);
	 	$this->view("excelout");
	 }

	 /*
	  * ����excel
	  */
	  function c_excelOut(){
		$object = $_POST[$this->objName];
		//print_r($object);
		if(!empty($object['userNo']))
	 		$this->service->searchArr['userNo'] = $object['userNo'];
		if(!empty($object['jobId']))
	 		$this->service->searchArr['jobId'] = $object['jobId'];
		if(!empty($object['conType']))
			$this->service->searchArr['conType'] = $object['conType'];
		if(!empty($object['conState']))
	 		$this->service->searchArr['conState'] = $object['conState'];
		if(!empty($object['beginDate']))
			$this->service->searchArr['beginDate'] = $object['beginDate'];
		if(!empty($object['closeDate']))
			$this->service->searchArr['closeDate'] = $object['closeDate'];
		if(!empty($object['conNum']))
			$this->service->searchArr['conNum'] = $object['conNum'];
		//print_r($this->service->searchArr);
		set_time_limit(600);
		$planEquRows = $this->service->list_d();
		$exportData = array();
		foreach ( $planEquRows as $key => $val ){
			$exportData[$key]['userNo']=$planEquRows[$key]['userNo'];
			$exportData[$key]['userName']=$planEquRows[$key]['userName'];
			$exportData[$key]['conNo']=$planEquRows[$key]['conNo'];
			$exportData[$key]['conName']=$planEquRows[$key]['conName'];
			$exportData[$key]['conTypeName']=$planEquRows[$key]['conTypeName'];
			$exportData[$key]['conStateName']=$planEquRows[$key]['conStateName'];
			$exportData[$key]['beginDate']=$planEquRows[$key]['beginDate'];
			$exportData[$key]['closeDate']=$planEquRows[$key]['closeDate'];
			$exportData[$key]['trialBeginDate']=$planEquRows[$key]['trialBeginDate'];
			$exportData[$key]['trialEndDate']=$planEquRows[$key]['trialEndDate'];
			$exportData[$key]['conNumName']=$planEquRows[$key]['conNumName'];
			$exportData[$key]['conContent']=$planEquRows[$key]['conContent'];
		}
		return $this->service->excelOut ( $exportData );
	  }
	  	/**
	 * ��������
	 */
	function c_excelOutSelect(){
		$this->assign('listSql',str_replace("&nbsp;"," ",stripslashes(stripslashes($_POST['contract']['listSql']))));
		$this->view('excelout-select');

	}	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		//�ж�Ա���Ƿ��е�ʦ
        if(is_array($rows)){
        	$managmentDao=new model_file_uploadfile_management();
	        foreach($rows as $k=>$v){
				$filelist=$managmentDao->getFilesByObjId($v['id'],'oa_hr_personnel_contract');
				if(empty($filelist)){
					$rows[$k]['files']=0;
				}else{
					$rows[$k]['files']=1;
				}
	        }
        }
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��������
	 */
	function c_selectExcelOut(){
//			set_time_limit(600);
		$rows=array();//���ݼ�
		$listSql=str_replace("&nbsp;"," ",stripslashes(stripslashes(stripslashes($_POST['listSql']))));
		if(!empty($listSql)){
			$rows = $this->service->_db->getArray($listSql);
		}
		$colNameArr=array();//��������
		include_once ("model/hr/contract/contractFieldArr.php");
		if(is_array($_POST['contract'])){
			foreach($_POST['contract'] as $key=>$val){
					foreach($contractFieldArr as $fKey=>$fVal){
						if($val==$fKey){
							$colNameArr[$key]=$fVal;
						}
					}
			}
		}
		$newColArr=array_combine($_POST['contract'],$colNameArr);//�ϲ�����
		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip($_POST['contract']);
		if(is_array($rows)){
			foreach ($rows as $key => $row) {
				foreach ($colIdArr as $index => $val) {
					$colIdArr[$index] = $row[$index];
				}
				array_push($dataArr, $colIdArr);
			}
		}
		return model_hr_personnel_personnelExcelUtil::excelOutContract($newColArr,$dataArr);
	}
	/******************* E ���뵼��ϵ�� ************************/
}
?>