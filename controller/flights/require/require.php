<?php

/**
 * @author Administrator
 * @Date 2013年7月11日 20:30:47
 * @version 1.0
 * @description:订票需求控制层
 */
class controller_flights_require_require extends controller_base_action
{
	function __construct() {
		$this->objName = "require";
		$this->objPath = "flights_require";
		parent:: __construct();
	}

	/**
	 * 跳转到订票需求列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到我的订票需求列表
	 */
	function c_myList() {
		$this->view('mylist');
	}

	/**
	 * 重写pageJson
	 */
	function c_myPageJson() {
		$service = $this->service;
		$_REQUEST['requireId'] = $_SESSION["USER_ID"];
		$service->getParam($_REQUEST);
		$rows = $service->page_d();
		$arr = array();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		$arr['advSql'] = $service->advSql;
		$arr['listSql'] = $service->listSql;
		echo util_jsonUtil:: encode($arr);
	}

	/**
	 * 跳转到新增订票需求页面
	 */
	function c_toAdd() {

		$this->assign('requireTime', day_date);
		$this->assign('requireName', $_SESSION['USERNAME']);
		$this->assign('requireId', $_SESSION['USER_ID']);

		$this->assign('companyId', $_SESSION['COM_BRN_PT']);
		$this->assign('companyName', $_SESSION['COM_BRN_CN']);
		$this->assign('deptName', $_SESSION['DEPT_NAME']);
		$this->assign('deptId', $_SESSION['DEPT_ID']);

		//获取通讯录中的电话号码
		$otherDataDao = new model_common_otherdatas();
		$userConnectInfo = $otherDataDao->getPersonnelInfo_d($_SESSION['USER_ID']);
		$this->assign('cardNo', $userConnectInfo['identityCard']);
		$this->assign('requirePhone', $userConnectInfo['mobile']);

		$this->showDatadicts(array('tourAgency' => 'CLKJG'), null, true);//常旅卡机构
		$this->showDatadicts(array('cardType' => 'JPZJLX'));//证件类型
		$this->showDatadicts(array('districtType' => 'QULX'));//区域类型

		//判断当前部门是否需要特殊配置
		$this->assignFunc($this->service->deptNeedInfo_d($_SESSION['DEPT_ID']));

		$this->view('add');
	}

	/**
	 * 新增对象
	 */
	function c_add() {
		$object = $_POST[$this->objName];
		if (!isset($object['detailType']) || empty($object['detailType'])) {
			msgRf("保存失败，单据没有选择费用类型");
		} else {
			//将外部员工姓名赋值给其人员编号
			foreach ($object[items] as $key => $val) {
				if ($val['employeeType'] == 'YGLX-02') {
					$object[items][$key]['airId'] = $object[items][$key]['airName'];
				}
			}

			//判断是否要进入审批流
			$isSetExaStatus = true;
			$isUserNeedAudit = $this->service->isUserNeedAudit_d($_SESSION['USER_ID'], $_SESSION['DEPT_ID']);
			if (!$isUserNeedAudit) {
				$object['ExaStatus'] = AUDITED;
				$object['ExaDT'] = day_date;
				$isSetExaStatus = false;
			}

			$id = $this->service->add_d($object, $isSetExaStatus);
			if ($id) {
				if ($object['auditType'] == 'audit') {
					if (!$isUserNeedAudit) {
						msgRf("提交成功");
					} else {
						$requireTime = strtotime($object['requireTime']);
						$startDate = strtotime($object['startDate']);
						$days = round(($startDate - $requireTime) / 3600 / 24);
						if ($days > 3) {
							succ_show('controller/flights/require/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=6&billDept=' . $object['costBelongDeptId']);
						} else {
							succ_show('controller/flights/require/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=3&billDept=' . $object['costBelongDeptId']);
						}
					}
				} else {
					msgRf("保存成功");
				}
			} else {
				msgRf("保存失败");
			}
		}
	}

	/**
	 * 跳转到编辑订票需求页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		//判断当前部门是否需要省份
		$this->assignFunc($this->service->deptNeedInfo_d($obj['costBelongDeptId']));

		$this->showDatadicts(array('tourAgency' => 'CLKJG'), $obj['tourAgency'], true);//常旅卡机构
		$this->showDatadicts(array('cardType' => 'JPZJLX'), $obj['cardType']);//证件类型
		$this->showDatadicts(array('districtType' => 'QULX'), $obj['districtType']);//区域类型
		$this->view('edit');
	}

	//重写编辑
	function c_edit() {
		$object = $_POST[$this->objName];

		//判断是否要进入审批流
		$isUserNeedAudit = $this->service->isUserNeedAudit_d($_SESSION['USER_ID'], $_SESSION['DEPT_ID']);
		if (!$isUserNeedAudit) {
			$object['ExaStatus'] = AUDITED;
			$object['ExaDT'] = day_date;
		}

		if ($this->service->edit_d($object)) {
			if ($object['auditType'] == 'audit') {
				if (!$isUserNeedAudit) {
					msgRf("提交成功");
				} else {
					$requireTime = strtotime($object['requireTime']);
					$startDate = strtotime($object['startDate']);
					$days = round(($startDate - $requireTime) / 3600 / 24);
					if ($days > 3) {
						succ_show('controller/flights/require/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'] . '&flowMoney=6&billDept=' . $object['costBelongDeptId']);
					} else {
						succ_show('controller/flights/require/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'] . '&flowMoney=3&billDept=' . $object['costBelongDeptId']);
					}
				}
			} else {
				msg("保存成功");
			}
		} else {
			msg('保存失败');
		}
	}

	/**
	 * 跳转到查看订票需求页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		$this->assign('ticketType', $this->service->rtStatus_d($obj['ticketType']));
		$this->assign('ticketTypeHidden', $obj['ticketType']);

		//审批显示处理
		$actType = isset($_GET['actType']) ? $_GET['actType'] : '';
		$this->assign('actType', $actType);

		$cardNoLimit = isset($_GET['cardNoLimit']) ? $_GET['cardNoLimit'] : '';
		$this->assign('cardNoLimit', $cardNoLimit);
		//权限过滤
		if ($obj['cardType'] == 'JPZJLX-01' && ($cardNoLimit == '1' || $_SESSION['USER_ID'] == $obj['airId'])) {
			$this->assign('cardNo', $obj['cardNoHidden']);
		}
		$this->view('view');
	}

	/**
	 * 跳转到Tab
	 */
	function c_viewTab() {
		$this->assign('id', $_GET['id']);
		$cardNoLimit = isset($_GET['cardNoLimit']) ? $_GET['cardNoLimit'] : '';
		$this->assign('cardNoLimit', $cardNoLimit);
		$this->view('viewTab');
	}

	/************************ 其余列表信息 ***************************/

	//订票信息查询订票需求信息
	function c_requireQuerylistJson() {
		$id = $_POST['id'];
		$rows = array();

		//主表取数
		$obj = $this->service->get_d($id);
		$obj['auditDate'] = day_date;
		//时间、地点处理
		$obj['flightTime'] = $obj['startDate'] . ' 10:00:00';
		$obj['departPlace'] = $obj['startPlace'];
		$obj['arrivalPlace'] = $obj['endPlace'];

		array_push($rows, $obj);

		//查询出子表信息
		$requiresuitArr = $this->service->getRequiresuite_d($id);
		if ($requiresuitArr) {
			foreach ($requiresuitArr as $key => $val) {
				$requiresuitArr[$key]['auditDate'] = day_date;
				$requiresuitArr[$key]['flightTime'] = $obj['flightTime'];
				$requiresuitArr[$key]['departPlace'] = $obj['departPlace'];
				$requiresuitArr[$key]['arrivalPlace'] = $obj['arrivalPlace'];
				array_push($rows, $requiresuitArr[$key]);
			}
		}
		//去掉第一条数据
		$rows = array_splice($rows, 1);
		echo util_jsonUtil:: encode($rows);
	}

	//ajax获取需求信息
	function c_ajaxGet() {
		$obj = $this->service->find(array(
			'id' => $_POST['id']
		));
		echo util_jsonUtil::encode($obj);
	}

	/**
	 * 审批完成后发送邮箱的方法
	 */
	function c_dealAfterAudit() {
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo($_GET ['spid']);
		$arr = $this->service->get_d($folowInfo['objId']);
		$requireNo = $arr['requireNo'];
		$requireName = $arr['requireName'];
		$airName = $arr['airName'];
		$ticketType = $this->service->rtStatus_d($arr['ticketType']);
		$startPlace = $arr['startPlace'];
		$startDate = $arr['startDate'];
		$endPlace = $arr['endPlace'];
		$userId = $folowInfo['Enter_user'];
		$this->service->mailDeal_d('flightsNotice', $userId, array(
				'requireNo' => $requireNo, 'requireName' => $requireName,
				'ticketType' => $ticketType, 'startPlace' => $startPlace,
				'endPlace' => $endPlace, 'airName' => $airName, 'startDate' => $startDate
			)
		);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * 判断部门是否需要省份信息
	 */
	function c_deptNeedInfo() {
		echo util_jsonUtil::encode($this->service->deptNeedInfo_d($_POST['deptId']));
	}

	/**
	 * 判断是否需要走审批
	 */
	function c_isUserNeedAudit() {
		echo $this->service->isUserNeedAudit_d($_SESSION['USER_ID'], $_SESSION['DEPT_ID']) ? 1 : 0;
	}

	/**
	 * 异步提交单据
	 */
	function c_ajaxSubmit() {
		echo $this->service->ajaxSubmit_d($_POST['id']) ? 1 : 0;
	}
}