<?php
/**
 * @author Administrator
 * @Date 2014年3月12日 星期三 21:25:18
 * @version 1.0
 * @description:法定节假日申请表 Model层
 */
 class model_hr_worktime_apply  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_worktime_apply";
		$this->sql_map = "hr/worktime/applySql.php";
		parent::__construct ();
	}

	/**
	 * 根据员工账号获取人事信息
	 */
	function getPersonnelInfo_d($userAccount) {
		$contractArr = array('userAccount'=>$userAccount);
		//人事信息
		$personnel = new model_hr_personnel_personnel();
		$row = $personnel->find($contractArr);
		return $row;
	}

	/*
	 * 重写新增方法
	 */
	function add_d($object){
		try {
			$this->start_d();
			$object['applyCode'] = "HRJB".date("Ymd").time();
			$object['applyDate'] = date("Y-m-d");
			$newId = parent :: add_d($object ,true);

			$applyequDao = new model_hr_worktime_applyequ();
			if(is_array($object['equ'])) { //假期详细
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
	 * 重写编辑方法
	 */
	function edit_d($object){
		try {
			$this->start_d();
			$newId = parent :: edit_d($object ,true);

			$applyequDao = new model_hr_worktime_applyequ();
			$applyequDao->delete(array('parentId' => $object['id']));
			if(is_array($object['equ'])) { //假期详细
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
	 * 改变状态
	 */
	function getState($id,$ExaStatus='未提交'){
		$object['id']=$id;
		$object['ExaStatus']=$ExaStatus;
		$flag=$this->updateById($object);
		return $flag;
	}

	function getRows($sql){
		return $this->_db->getArray ( $sql );
	}

	/**
	 * 修改时间
	 */
	function changeTime_d($obj){
		try {
			$this->start_d();
			$oldObj = $this->get_d( $obj['id'] );

			parent :: edit_d($obj ,true);

			$applyequDao = new model_hr_worktime_applyequ();
			$oldObjequ = $applyequDao->findAll(array('parentId'=>$obj['id']));
			$applyequDao->delete(array('parentId' => $obj['id']));
			if(is_array($obj['equ'])) { //假期详细
				foreach($obj['equ'] as $key => $val) {
					if ($val['isApply'] == 1) {
						$val['parentId'] = $obj['id'];
						$applyequDao->add_d($val);
					}
				}
			}

			//发邮件通知员工
			$emailDao = new model_common_mail();

			if (is_array($oldObjequ)) {
				$oldData = '';
				foreach ($oldObjequ as $key => $val) {
					if ($val['holidayInfo'] == '1') {
						$val['holidayInfo'] = '上午';
					} else if ($val['holidayInfo'] == '2') {
						$val['holidayInfo'] = '下午';
					} else if ($val['holidayInfo'] == '3') {
						$val['holidayInfo'] = '全天';
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
						$val['holidayInfo'] = '上午';
						} else if ($val['holidayInfo'] == '2') {
							$val['holidayInfo'] = '下午';
						} else if ($val['holidayInfo'] == '3') {
							$val['holidayInfo'] = '全天';
						} else {
							$val['holidayInfo'] = '';
						}
						$newData .= $val['holiday'].'&nbsp;&nbsp;'.$val['holidayInfo'].'<br>';
					}
				}
			}

		 	$mailContent = '您好！此邮件法定节假日加班申请的时间修改通知，详细信息如下：<br>'.
			'申请单据：<span style="color:blue">'.$oldObj['applyCode'].'</span><br>'.
			'<table border="1px" cellspacing="0px" style="text-align:center"><tr style="background-color:#fff999"><td>&nbsp;</td><td width="150px">加班时间</td><td width="80px">天数</td></tr>'.
			'<tr><td>旧数据</td><td>'.$oldData.'</td><td>'.$oldObj['dayNo'].'</td></tr>'.
			'<tr><td>新数据</td><td>'.$newData.'</td><td>'.$obj['dayNo'].'</td></tr>'.
			'</table>';

			$mailContent .= '<br>修改人：<span style="color:blue">'.$_SESSION['USERNAME'].'</span><br>修改时间的原因：'.$obj['changeTimeReason'];

			$emailDao->mailGeneral("法定节假日加班申请的时间修改" ,$oldObj['userAccount'] ,$mailContent);

			$this->commit_d();
			return true;
		} catch (exception $e) {
			return false;
		}
	}

	/**
	 * 根据ID获取当前需要审批的人
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
		$ExaUser = substr($ExaUser ,0 ,-1); //去除尾部逗号
		return $ExaUser;
	}
 }
?>