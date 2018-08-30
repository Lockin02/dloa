<?php
/**
 * @author Administrator
 * @Date 2014��3��12�� ������ 21:25:18
 * @version 1.0
 * @description:�����ڼ�������� Model��
 */
 class model_hr_worktime_apply  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_worktime_apply";
		$this->sql_map = "hr/worktime/applySql.php";
		parent::__construct ();
	}

	/**
	 * ����Ա���˺Ż�ȡ������Ϣ
	 */
	function getPersonnelInfo_d($userAccount) {
		$contractArr = array('userAccount'=>$userAccount);
		//������Ϣ
		$personnel = new model_hr_personnel_personnel();
		$row = $personnel->find($contractArr);
		return $row;
	}

	/*
	 * ��д��������
	 */
	function add_d($object){
		try {
			$this->start_d();
			$object['applyCode'] = "HRJB".date("Ymd").time();
			$object['applyDate'] = date("Y-m-d");
			$newId = parent :: add_d($object ,true);

			$applyequDao = new model_hr_worktime_applyequ();
			if(is_array($object['equ'])) { //������ϸ
				foreach($object['equ'] as $key => $val) {
					if ($val['isApply'] == 1) {
						$val['parentId'] = $newId;
						$applyequDao->add_d($val);
					}
				}
			}

			$this->commit_d();
			return $newId;
		} catch (exception $e) {
			return false;
		}
	}

	/**
	 * ��д�༭����
	 */
	function edit_d($object){
		try {
			$this->start_d();
			$newId = parent :: edit_d($object ,true);

			$applyequDao = new model_hr_worktime_applyequ();
			$applyequDao->delete(array('parentId' => $object['id']));
			if(is_array($object['equ'])) { //������ϸ
				foreach($object['equ'] as $key => $val) {
					if ($val['isApply'] == 1) {
						$val['parentId'] = $object['id'];
						$applyequDao->add_d($val);
					}
				}
			}

			$this->commit_d();
			return $newId;
		} catch (exception $e) {
			return false;
		}
	}

	/**
	 * �ı�״̬
	 */
	function getState($id,$ExaStatus='δ�ύ'){
		$object['id']=$id;
		$object['ExaStatus']=$ExaStatus;
		$flag=$this->updateById($object);
		return $flag;
	}

	function getRows($sql){
		return $this->_db->getArray ( $sql );
	}

	/**
	 * �޸�ʱ��
	 */
	function changeTime_d($obj){
		try {
			$this->start_d();
			$oldObj = $this->get_d( $obj['id'] );

			parent :: edit_d($obj ,true);

			$applyequDao = new model_hr_worktime_applyequ();
			$oldObjequ = $applyequDao->findAll(array('parentId'=>$obj['id']));
			$applyequDao->delete(array('parentId' => $obj['id']));
			if(is_array($obj['equ'])) { //������ϸ
				foreach($obj['equ'] as $key => $val) {
					if ($val['isApply'] == 1) {
						$val['parentId'] = $obj['id'];
						$applyequDao->add_d($val);
					}
				}
			}

			//���ʼ�֪ͨԱ��
			$emailDao = new model_common_mail();

			if (is_array($oldObjequ)) {
				$oldData = '';
				foreach ($oldObjequ as $key => $val) {
					if ($val['holidayInfo'] == '1') {
						$val['holidayInfo'] = '����';
					} else if ($val['holidayInfo'] == '2') {
						$val['holidayInfo'] = '����';
					} else if ($val['holidayInfo'] == '3') {
						$val['holidayInfo'] = 'ȫ��';
					} else {
						$val['holidayInfo'] = '';
					}
					$oldData .= $val['holiday'].'&nbsp;&nbsp;'.$val['holidayInfo'].'<br>';
				}
			}

			if(is_array($obj['equ'])) {
				$newData = '';
				foreach($obj['equ'] as $key => $val) {
					if ($val['isApply'] == 1) {
						if ($val['holidayInfo'] == '1') {
						$val['holidayInfo'] = '����';
						} else if ($val['holidayInfo'] == '2') {
							$val['holidayInfo'] = '����';
						} else if ($val['holidayInfo'] == '3') {
							$val['holidayInfo'] = 'ȫ��';
						} else {
							$val['holidayInfo'] = '';
						}
						$newData .= $val['holiday'].'&nbsp;&nbsp;'.$val['holidayInfo'].'<br>';
					}
				}
			}

		 	$mailContent = '���ã����ʼ������ڼ��ռӰ������ʱ���޸�֪ͨ����ϸ��Ϣ���£�<br>'.
			'���뵥�ݣ�<span style="color:blue">'.$oldObj['applyCode'].'</span><br>'.
			'<table border="1px" cellspacing="0px" style="text-align:center"><tr style="background-color:#fff999"><td>&nbsp;</td><td width="150px">�Ӱ�ʱ��</td><td width="80px">����</td></tr>'.
			'<tr><td>������</td><td>'.$oldData.'</td><td>'.$oldObj['dayNo'].'</td></tr>'.
			'<tr><td>������</td><td>'.$newData.'</td><td>'.$obj['dayNo'].'</td></tr>'.
			'</table>';

			$mailContent .= '<br>�޸��ˣ�<span style="color:blue">'.$_SESSION['USERNAME'].'</span><br>�޸�ʱ���ԭ��'.$obj['changeTimeReason'];

			$emailDao->mailGeneral("�����ڼ��ռӰ������ʱ���޸�" ,$oldObj['userAccount'] ,$mailContent);

			$this->commit_d();
			return true;
		} catch (exception $e) {
			return false;
		}
	}

	/**
	 * ����ID��ȡ��ǰ��Ҫ��������
	 */
	function getExaUser_d( $id ) {
		$task = $this->get_table_fields('wf_task'
			,"code='oa_hr_worktime_apply' and pid='$id' order by task desc"
			,'task');
		$sql = "select replace(f.user ,',' ,'') as userId ,u.USER_NAME as userName from flow_step_partent f "
			." left join user u on u.USER_ID=replace(f.user ,',' ,'') "
			." where f.WF_task_ID = '$task' and f.Flag='0' ";
		$rs = $this->findSql($sql);
		$ExaUser = '';
		if (is_array($rs)) {
			foreach($rs as $k => $v) {
				$ExaUser .= $v['userName'].',';
			}
		}
		$ExaUser = substr($ExaUser ,0 ,-1); //ȥ��β������
		return $ExaUser;
	}
 }
?>