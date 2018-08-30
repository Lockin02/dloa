<?php
/**
 * @author Administrator
 * @Date 2014��3��12�� ������ 21:25:18
 * @version 1.0
 * @description:�����ڼ����������Ʋ�
 */
class controller_hr_worktime_apply extends controller_base_action {

	function __construct() {
		$this->objName = "apply";
		$this->objPath = "hr_worktime";
		parent::__construct ();
	}

	/**
	 * ��ת�������ڼ���������б�
	 */
	function c_page() {
		$this->assign('userAccount',$_SESSION['USER_ID']);
		$this->view('list');
	}

	/**
	 * ��ת�������ڼ���ͳ���б�
	 */
	function c_toPageList() {
		$this->view('pageList');
	}

	/**
	 * ��ת�����������ڼ��������ҳ��
	 */
	function c_toAdd() {
		//��ȡ���ʼ���
		$userId = $_SESSION['USER_ID'];
		$arr = $this->service->getPersonnelInfo_d($userId);
		foreach($arr as $key => $val){
			$this->assign($key ,$val);
		}

		//��ȡ��ǰ��ݵĽڼ������ñ�ID
		$setId = $this->service->get_table_fields('oa_hr_worktime_set' ,"year='".date("Y")."'" ,'id');
		$this->assign('setId' ,$setId);
		$this->view ('add', true);
	}

	/**
	 *������������
	 */
	function c_add() {
		$this->checkSubmit();
		$row = $_POST [$this->objName];
		$actType = $_GET['actType'];
		$row['ExaStatus']='δ�ύ';
		$id = $this->service->add_d ($row);
		if($actType == 'approval') {
			$row['id'] = $id;
			switch($row['wageLevelCode']) {
				case "GZJBFGL":$auditType = '5' ;break;//�ǹ����
				case "GZJBJL" :$auditType = '15';break;//��������
				case "GZJBZJ" :$auditType = '25';break;//�ܼ�
				case "GZJBZG" :$auditType = '35';break;//����
				case "GZJBFZ" :$auditType = '35';break;//����
			}
			$rangeDao = new model_engineering_officeinfo_range();
			$areaId = $rangeDao->getRangeByProvinceAndDept_d($row['workProvinceId'],$row['belongDeptId']);
			if($areaId > 0) {
				$billArea = $areaId;
			}else{
				$billArea = '';
			}
			succ_show('controller/hr/worktime/ewf_index1.php?actTo=ewfSelect&billId='.$row['id'].'&billDept='.$row['belongDeptId'].'&flowMoney='.$auditType.'&billArea='.$billArea.'&proSid='.$row['projectManagerId']);
		}
		if($id && $actType == 'approval') {
			msg("�ύ�ɹ���");
		} else if($id) {
			msg("��ӳɹ���");
		} else {
			msg("����ʧ�ܣ�");
		}
	}

	/**
	 * ��ת���༭�����ڼ��������ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		//��ȡ��ݵĽڼ������ñ�ID
		$year = substr($obj['createTime'] ,0 ,4);
		$setId = $this->service->get_table_fields('oa_hr_worktime_set' ,"year='".$year."'" ,'id');
		$this->assign('setId' ,$setId);

		$this->view('edit', true);
	}

	/**
	 * �༭��������
	 */
	function c_edit() {
		$this->checkSubmit();
		$row = $_POST [$this->objName];
		$actType = $_GET['actType'];
		if($actType == 'approval'){
			$id = $this->service->edit_d ($row);
			switch($row['wageLevelCode']) {
				case "GZJBFGL":$auditType = '5' ;break;//�ǹ����
				case "GZJBJL" :$auditType = '15';break;//��������
				case "GZJBZJ" :$auditType = '25';break;//�ܼ�
				case "GZJBZG" :$auditType = '35';break;//����
				case "GZJBFZ" :$auditType = '35';break;//����
			}
			$rangeDao = new model_engineering_officeinfo_range();
			$areaId = $rangeDao->getRangeByProvinceAndDept_d($row['workProvinceId'] ,$row['belongDeptId']);
			if($areaId > 0){
				$billArea = $areaId;
			}else{
				$billArea = '';
			}
			succ_show('controller/hr/worktime/ewf_index1.php?actTo=ewfSelect&billId=' . $row['id'].'&billDept='.$row['belongDeptId'].'&flowMoney='.$auditType.'&billArea='.$billArea.'&proSid='.$row['projectManagerId']);
		} else{
			$id = $this->service->edit_d ($row);
		}
		if($id && $actType == 'approval') {
			msg("�ύ�ɹ���");
		} else if($id) {
			msg("�༭�ɹ���");
		} else {
			msg("����ʧ�ܣ�");
		}
	}

	/**
	 * ��ת���鿴�����ڼ��������ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$this->assign ( 'actType', $_GET ['actType'] );

		//��ȡ��ݵĽڼ������ñ�ID
		$year = substr($obj['createTime'] ,0 ,4);
		$setId = $this->service->get_table_fields('oa_hr_worktime_set' ,"year='".$year."'" ,'id');
		$this->assign('setId' ,$setId);

		$this->view ( 'view' );
	}

	/**
	 * ��ת���鿴�����ڼ��������ҳ����������
	 */
	function c_toViewApproval() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( 'actType', $_GET ['actType'] );

		//��ȡ��ݵĽڼ������ñ�ID
		$year = substr($obj['createTime'] ,0 ,4);
		$setId = $this->service->get_table_fields('oa_hr_worktime_set' ,"year='".$year."'" ,'id');
		$this->assign('setId' ,$setId);

		//������չ������
		if(empty($object['exaCode'])){
			$this->assign('exaId' ,$obj['id']);
			$this->assign('exaCode' ,$this->service->tbl_name);
		}
		$this->view ( 'viewApproval' );
	}

	/**
	 * ��������
	 */
	function c_backSubmit(){
		$id = $_POST['id'];
		$ExaStatus = $_POST['ExaStatus'];
		if($this->service->getState($id ,$ExaStatus)){
			echo 1;
		}else
			echo 0;
	}

	/**
	 * �ڼ��ռӰ�����ͳ�Ʋ�ѯ����ҳ��
	 */
	function c_toSearch(){
		$this->view('excel');
	}

	/**
	 * �ڼ��ռӰ�����ͳ�Ʋ�ѯ����
	 */
	function c_excel(){
		set_time_limit(0);
		$service = $this->service;
		$sql = $_REQUEST['sql'];
		$sql = stripslashes($sql);
		$sql = stripslashes($sql);
		$rows = $service->findSql($sql);
		$excelDatas = array();
		$i = 1;
		if($rows) {
			foreach($rows as $key=>$val) {
				//��ȡ��ǰ������
				if ($val['ExaStatus'] != '��������') {
					$val['ExaUser'] = '';
				} else {
					$val['ExaUser'] = $service->getExaUser_d($val['id']);
				}

				//�Ӱ�ʱ��
				$holiday = '';
				if ($val['holiday']) {
					$holidayWork = explode(',' ,$val['holiday']);
					foreach ($holidayWork as $k => $v) {
						$holidayInfo = substr($v ,-1);
						if ($holidayInfo == '1') {
							$holidayInfoStr = '����';
						} else if ($holidayInfo == '2') {
							$holidayInfoStr = '����';
						} else if ($holidayInfo == '3') {
							$holidayInfoStr = 'ȫ��';
						} else {
							$holidayInfoStr = '';
						}

						$holiday .= substr($v ,0 ,10).' '.$holidayInfoStr.' ';
					}
				} else {
					if ($val['beginIdentify'] == '1') {
						$holidayInfo1 = '����';
					} else if ($val['beginIdentify'] == '2') {
						$holidayInfo1 = '����';
					} else {
						$holidayInfo1 = '';
					}
					$holiday .= $val['workBegin'] . ' ' .$holidayInfo1;

					if ($val['endIdentify'] == '1') {
						$holidayInfo2 = '����';
					} else if ($val['endIdentify'] == '2') {
						$holidayInfo2 = '����';
					} else {
						$holidayInfo2 = '';
					}
					$holiday .= ' ��'.$val['workEnd'] . ' ' .$holidayInfo2;
				}

				$excelDatas[$key]['No']          = $i++;
				$excelDatas[$key]['applyCode']   = $val['applyCode'];
				$excelDatas[$key]['userNo']      = $val['userNo'];
				$excelDatas[$key]['userName']    = $val['userName'];
				$excelDatas[$key]['deptName']    = $val['deptName'];
				$excelDatas[$key]['deptNameS']   = $val['deptNameS'];
				$excelDatas[$key]['deptNameT']   = $val['deptNameT'];
				$excelDatas[$key]['deptNameF']   = $val['deptNameF'];
				$excelDatas[$key]['jobName']     = $val['jobName'];
				$excelDatas[$key]['applyDate']   = $val['applyDate'];
				$excelDatas[$key]['holiday']     = $holiday;
				$excelDatas[$key]['dayNo']       = $val['dayNo'];
				$excelDatas[$key]['ExaStatus']   = $val['ExaStatus'];
				$excelDatas[$key]['ExaUser']     = $val['ExaUser'];
				$excelDatas[$key]['workContent'] = $val['workContent'];
			}
			$colArr  = array();
			$modelName = '�ڼ��ռӰ�������Ϣ';
			return model_hr_permanent_examineExportUtil::exportWorkTimeExcelUtil($colArr, $excelDatas, $modelName);
		} else {
			msg("�鲻�����ݣ�");
		}
	}

	/**
	 * ��ת���޸�ʱ��ҳ��
	 */
	function c_toChangeTime() {
		$obj = $this->service->get_d( $_GET ['id'] );
		$this->assignFunc( $obj );

		//��ȡ��ݵĽڼ������ñ�ID
		$year = substr($obj['createTime'] ,0 ,4);
		$setId = $this->service->get_table_fields('oa_hr_worktime_set' ,"year='".$year."'" ,'id');
		$this->assign('setId' ,$setId);

		$this->view( 'changetime' );
	}

	/**
	 * �޸�ʱ��
	 */
	function c_changeTime() {
		$rs = $this->service->changeTime_d( $_POST[$this->objName] );
		if($rs) {
			msg("�޸ĳɹ���");
		} else{
			msg("�޸�ʧ�ܣ�");
		}
	}

	/**
	 * �ж��Ƿ�Ϊ�ڼ���
	 */
	function c_isHolidays() {
		include (WEB_TOR."cache/hols_info.php");
		if (in_array($_POST['workBegin'] ,$hols_info['fj']) && in_array($_POST['workEnd'] ,$hols_info['fj'])) {
			echo 'yes';
		} else {
			echo 'no';
		}
	}
 }
?>