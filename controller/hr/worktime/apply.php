<?php
/**
 * @author Administrator
 * @Date 2014年3月12日 星期三 21:25:18
 * @version 1.0
 * @description:法定节假日申请表控制层
 */
class controller_hr_worktime_apply extends controller_base_action {

	function __construct() {
		$this->objName = "apply";
		$this->objPath = "hr_worktime";
		parent::__construct ();
	}

	/**
	 * 跳转到法定节假日申请表列表
	 */
	function c_page() {
		$this->assign('userAccount',$_SESSION['USER_ID']);
		$this->view('list');
	}

	/**
	 * 跳转到法定节假日统计列表
	 */
	function c_toPageList() {
		$this->view('pageList');
	}

	/**
	 * 跳转到新增法定节假日申请表页面
	 */
	function c_toAdd() {
		//获取工资级别
		$userId = $_SESSION['USER_ID'];
		$arr = $this->service->getPersonnelInfo_d($userId);
		foreach($arr as $key => $val){
			$this->assign($key ,$val);
		}

		//获取当前年份的节假日设置表ID
		$setId = $this->service->get_table_fields('oa_hr_worktime_set' ,"year='".date("Y")."'" ,'id');
		$this->assign('setId' ,$setId);
		$this->view ('add', true);
	}

	/**
	 *新增操作对象
	 */
	function c_add() {
		$this->checkSubmit();
		$row = $_POST [$this->objName];
		$actType = $_GET['actType'];
		$row['ExaStatus']='未提交';
		$id = $this->service->add_d ($row);
		if($actType == 'approval') {
			$row['id'] = $id;
			switch($row['wageLevelCode']) {
				case "GZJBFGL":$auditType = '5' ;break;//非管理层
				case "GZJBJL" :$auditType = '15';break;//经理、副总
				case "GZJBZJ" :$auditType = '25';break;//总监
				case "GZJBZG" :$auditType = '35';break;//主管
				case "GZJBFZ" :$auditType = '35';break;//副总
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
			msg("提交成功！");
		} else if($id) {
			msg("添加成功！");
		} else {
			msg("操作失败！");
		}
	}

	/**
	 * 跳转到编辑法定节假日申请表页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		//获取年份的节假日设置表ID
		$year = substr($obj['createTime'] ,0 ,4);
		$setId = $this->service->get_table_fields('oa_hr_worktime_set' ,"year='".$year."'" ,'id');
		$this->assign('setId' ,$setId);

		$this->view('edit', true);
	}

	/**
	 * 编辑操作对象
	 */
	function c_edit() {
		$this->checkSubmit();
		$row = $_POST [$this->objName];
		$actType = $_GET['actType'];
		if($actType == 'approval'){
			$id = $this->service->edit_d ($row);
			switch($row['wageLevelCode']) {
				case "GZJBFGL":$auditType = '5' ;break;//非管理层
				case "GZJBJL" :$auditType = '15';break;//经理、副总
				case "GZJBZJ" :$auditType = '25';break;//总监
				case "GZJBZG" :$auditType = '35';break;//主管
				case "GZJBFZ" :$auditType = '35';break;//副总
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
			msg("提交成功！");
		} else if($id) {
			msg("编辑成功！");
		} else {
			msg("操作失败！");
		}
	}

	/**
	 * 跳转到查看法定节假日申请表页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$this->assign ( 'actType', $_GET ['actType'] );

		//获取年份的节假日设置表ID
		$year = substr($obj['createTime'] ,0 ,4);
		$setId = $this->service->get_table_fields('oa_hr_worktime_set' ,"year='".$year."'" ,'id');
		$this->assign('setId' ,$setId);

		$this->view ( 'view' );
	}

	/**
	 * 跳转到查看法定节假日申请表页面和审批情况
	 */
	function c_toViewApproval() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( 'actType', $_GET ['actType'] );

		//获取年份的节假日设置表ID
		$year = substr($obj['createTime'] ,0 ,4);
		$setId = $this->service->get_table_fields('oa_hr_worktime_set' ,"year='".$year."'" ,'id');
		$this->assign('setId' ,$setId);

		//处理扩展审批流
		if(empty($object['exaCode'])){
			$this->assign('exaId' ,$obj['id']);
			$this->assign('exaCode' ,$this->service->tbl_name);
		}
		$this->view ( 'viewApproval' );
	}

	/**
	 * 撤回申请
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
	 * 节假日加班申请统计查询导出页面
	 */
	function c_toSearch(){
		$this->view('excel');
	}

	/**
	 * 节假日加班申请统计查询导出
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
				//获取当前审批人
				if ($val['ExaStatus'] != '部门审批') {
					$val['ExaUser'] = '';
				} else {
					$val['ExaUser'] = $service->getExaUser_d($val['id']);
				}

				//加班时间
				$holiday = '';
				if ($val['holiday']) {
					$holidayWork = explode(',' ,$val['holiday']);
					foreach ($holidayWork as $k => $v) {
						$holidayInfo = substr($v ,-1);
						if ($holidayInfo == '1') {
							$holidayInfoStr = '上午';
						} else if ($holidayInfo == '2') {
							$holidayInfoStr = '下午';
						} else if ($holidayInfo == '3') {
							$holidayInfoStr = '全天';
						} else {
							$holidayInfoStr = '';
						}

						$holiday .= substr($v ,0 ,10).' '.$holidayInfoStr.' ';
					}
				} else {
					if ($val['beginIdentify'] == '1') {
						$holidayInfo1 = '上午';
					} else if ($val['beginIdentify'] == '2') {
						$holidayInfo1 = '下午';
					} else {
						$holidayInfo1 = '';
					}
					$holiday .= $val['workBegin'] . ' ' .$holidayInfo1;

					if ($val['endIdentify'] == '1') {
						$holidayInfo2 = '上午';
					} else if ($val['endIdentify'] == '2') {
						$holidayInfo2 = '下午';
					} else {
						$holidayInfo2 = '';
					}
					$holiday .= ' 至'.$val['workEnd'] . ' ' .$holidayInfo2;
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
			$modelName = '节假日加班申请信息';
			return model_hr_permanent_examineExportUtil::exportWorkTimeExcelUtil($colArr, $excelDatas, $modelName);
		} else {
			msg("查不到数据！");
		}
	}

	/**
	 * 跳转到修改时间页面
	 */
	function c_toChangeTime() {
		$obj = $this->service->get_d( $_GET ['id'] );
		$this->assignFunc( $obj );

		//获取年份的节假日设置表ID
		$year = substr($obj['createTime'] ,0 ,4);
		$setId = $this->service->get_table_fields('oa_hr_worktime_set' ,"year='".$year."'" ,'id');
		$this->assign('setId' ,$setId);

		$this->view( 'changetime' );
	}

	/**
	 * 修改时间
	 */
	function c_changeTime() {
		$rs = $this->service->changeTime_d( $_POST[$this->objName] );
		if($rs) {
			msg("修改成功！");
		} else{
			msg("修改失败！");
		}
	}

	/**
	 * 判断是否为节假日
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