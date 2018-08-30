<?php
/**
 * @author Administrator
 * @Date 2012-08-09 13:56:00
 * @version 1.0
 * @description:离职交接清单控制层
 */
class controller_hr_leave_handover extends controller_base_action {

	function __construct() {
		$this->objName = "handover";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }

	/**
	 * 跳转到离职交接清单列表
	 */
	function c_page() {
		$this->view('list');
	}

    /**
     * 离职交接清单
     */
	function c_handoverlist(){
		$leaveId = $_GET['leaveId'];
		//判断是否存在面谈记录
		$sql = "select id  from oa_leave_handover where leaveId=".$leaveId."";
		$flagArr = $this->service->_db->getArray($sql);
		$leaveDao = new model_hr_leave_leave();
		if(empty($flagArr[0]['id'])){
			//获取编制
			$obj = $leaveDao->get_d ( $_GET['leaveId'] );
			//提取离职原因，重新组合
			$obj['quitReson'] = str_replace('^nvsp',"：",$obj['quitReson']);
			$obj['quitReson'] = str_replace('^nbsp',"； ",$obj['quitReson']);
			$obj['quitReson'] = str_replace('^nqsp',"： ",$obj['quitReson']);
			$obj['quitReson'] = str_replace('^nqsd',"： ",$obj['quitReson']);
			$obj['quitReson'] = str_replace('^nbss',"；",$obj['quitReson']);
			$info = $leaveDao->getPersonnelInfo_d($obj['userAccount']);
			$obj['companyName'] = $info['companyName'];
			$obj['companyId'] = $info['companyId'];
			$obj['userNo'] = $info['userNo'];
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
			$this->assign("leaveId",$_GET['leaveId']);
			//处理渲染离职交接清单详细
			$handitemDao=new model_hr_leave_handitem();

			$handitemDao->searchArr=array("mainId"=>$_GET['leaveId']);
			$handitemDao->asc=false;
			$handitemArr=$handitemDao->listBySqlId();

			$fromworkInfo=array();
			foreach($handitemArr as $key =>$handitem){
				$tempObj=array("items"=>$handitem['handContent'],
					"recipientName"=>$handitem['recipientName'],
					"recipientId"=>$handitem['recipientId']);
				array_push($fromworkInfo,$tempObj);
			}
			$fromworkList = $this->service->fromworkInfo_d($fromworkInfo);
			$this->assign('fromworkList',$fromworkList);

			$this->view ('add' ,true);
		} else {
			$obj = $this->service->get_d ( $flagArr[0]['id'] );
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
			//获取联系方式
			$obj = $leaveDao->get_d ( $_GET['leaveId'] );
			$this->assign ( 'mobile', $obj['mobile'] );
			$this->assign ( 'personEmail', $obj['personEmail'] );
			//处理渲染离职交接清单详细
			$Dao = new model_hr_leave_handoverlist();
			$Dao->asc=false;
			$Dao->sort='sort'; // 选择要排序的字段
			$Dao->searchArr['handoverId'] = $flagArr[0]['id'];
			$fromworkInfo = $Dao->list_d ("select_default");
			$fromworkList = $this->service->fromworkInfo_print($fromworkInfo);
			$this->assign('fromworkList',$fromworkList);
			$this->view ('edit' ,true);
		}
	}

	/**
	 * 跳转到新增离职交接清单页面
	 */
	function c_toAdd() {
		$leaveDao = new model_hr_leave_leave();
		$obj = $leaveDao->get_d ( $_GET['leaveId'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts(array ( 'quitTypeCode' => 'HRLZLX' ), $obj['quitTypeCode']);
		$this->assign("leaveId",$_GET['leaveId']);
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑离职交接清单页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/**
	 * 跳转到查看离职交接清单页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//处理渲染离职交接清单详细
		$Dao = new model_hr_leave_handoverlist();
		$Dao->asc=false;
		$Dao->sort='sort';
		$Dao->searchArr['handoverId'] = $_GET['id'];
		$fromworkInfo = $Dao->list_d ("select_default");
		$fromworkList = $this->service->fromworkInfo_view($fromworkInfo);
		$this->assign('fromworkList',$fromworkList);
		$this->view ( 'view' );
	}

	/**
	 * 跳转到查看离职交接清单页面(通过离职申请单)
	 */
	function c_toViewByApply() {
		$leaveId = $_GET['leaveId'];
		//判断是否存在面谈记录
		$sql = "select id  from oa_leave_handover where leaveId=".$leaveId."";
		$flagArr = $this->service->_db->getArray($sql);
		$obj = $this->service->get_d (  $flagArr[0]['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//处理渲染离职交接清单详细
		$Dao = new model_hr_leave_handoverlist();
		$Dao->asc=false;
		$Dao->sort='sort';
		$Dao->searchArr['handoverId'] = $flagArr[0]['id'] ;
		$fromworkInfo = $Dao->list_d ("select_default");
		$fromworkList = $this->service->fromworkInfo_view($fromworkInfo);
		$this->assign('fromworkList',$fromworkList);
		$this->view ( 'view' );
	}

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit();
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '离职交接清单发起成功，请注意跟踪！';
		if ($id) {
			msg ( $msg );
		}
	}

	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}

	/**
	 * 离职交接清单确认列表
	 */
	function c_handoverAffirm(){
		$this->assign("userId" , $_SESSION['USER_ID']);
		$this->view('handoveraffirm');
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_handoverAffirmJson() {
		$affirmUserId = $_GET['affirmUserId'];

		$service = $this->service;

		$ids = $service->affirmUserInfo($affirmUserId);

		$service->getParam ( $_REQUEST );
		$service->searchArr['ids'] = $ids;
		$rows = $service->page_d ("select_leave");
		if(is_array($rows)){
			foreach($rows as $key=>$val){
				$isDone[$key]=$rows[$key]['isDone'];
			}
			array_multisort($isDone,SORT_DESC,$rows);
		}
//		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 离职交接清单确认页面
	 */
	function c_affirmList(){
		$obj = $this->service->get_d ($_GET['handoverId'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('userId' ,$_SESSION['USER_ID']);
		$leaveDao = new model_hr_leave_leave();
		$leaveRow = $leaveDao->get_d ( $obj['leaveId'] );
		$this->assign('mobile' ,$leaveRow['mobile']);
		$this->assign('personEmail' ,$leaveRow['personEmail']);
		$this->assign('salaryEndDate' ,$leaveRow['salaryEndDate']);
		$info = $leaveDao->getPersonnelInfo_d($obj['userAccount']);
		$this->assign('regionName' ,$this->service->get_table_fields('oa_hr_personnel',"userNo='$obj[userNo]'",'regionName'));
		if($sysLimit = $this->service->this_limit['查看离职类型'] == 1) {
			$quitTypeNameHtm =<<<EOT
			<tr>
				<td class="form_text_left_three_new">离职类型</td>
				<td class="form_text_right">
					{$obj['quitTypeName']}
				</td>
			</tr>
EOT;
			$this->assign('quitTypeNameHtm',$quitTypeNameHtm);
		} else {
			$this->assign('quitTypeNameHtm',"");
		}
		$this->view ('affirmList');
	}

	/**
	 * 离职交接清单查看页面
	 */
	function c_toViewAffirmList(){
		$obj = $this->service->get_d ($_GET['handoverId'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('userId',$_SESSION['USER_ID']);
		$leaveDao = new model_hr_leave_leave();
		$leaveRow = $leaveDao->get_d ( $obj['leaveId'] );
		$this->assign('mobile',$leaveRow['mobile']);
		$this->assign('personEmail',$leaveRow['personEmail']);
		$info = $leaveDao->getPersonnelInfo_d($obj['userAccount']);
		$this->assign('regionName',$this->service->get_table_fields('oa_hr_personnel','userNo='.$obj['userNo'],'regionName'));
		$this->view ('viewAffirmList');
	}

	/**
	 * 离职清单确认操作
	 */
	function c_affirmCon(){
		if ($this->service->affirmCon_d ( $_POST [$this->objName] )) {
			msg ( '确认成功！' );
		}
	}

	/**
	 * ajax验证离职交接单是否全部确认完成
	 */
	function c_getLeaveInfo(){
		$handoverId = $_POST['handoverId'];
		$falg = $this->service->getLeaveInfo_d($handoverId);
		echo $falg;
	}

	/**
	 * ajax验证离职交接单是否全部确认完成(必须确认项)
	 */
	function c_isDone(){
		$handoverId = $_POST['handoverId'];
		$falg = $this->service->isDone_d($handoverId);
		echo $falg;
	}

	/**
	 * 交接清单确认完成后，发起确认单据操作
	 */
	function c_startAffirm(){
		$rows = $_POST [$this->objName];
		if (!empty($rows['handoverId'])) {
			succ_show('controller/hr/leave/ewf_handover.php?actTo=ewfSelect&billId=' . $rows['handoverId'].'&billDept='.$rows['deptId']);
		}
	}

	/**
	 * 员工个人确认交接清单
	 */
	function c_startAffirmPro(){
		$rows = $_POST [$this->objName];

		if ($this->service->startAffirmPro_d ( $_POST [$this->objName] )) {
			msg ( '确认成功！' );
		}
	}

	/**
	 * 清单确认查看页
	 */
	function c_toHandoverList(){
		$otherdatasDao=new model_common_otherdatas();
		$flag=$otherdatasDao->isFirstStep($_GET['id'],$this->service->tbl_name);
		$obj = $this->service->get_d ( $_GET['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//处理渲染离职交接清单详细
		$Dao = new model_hr_leave_handoverlist();
		$Dao->asc=false;
		$Dao->sort='sort';
		$Dao->searchArr['handoverId'] = $_GET['id'];
		$fromworkInfo = $Dao->list_d ("select_default");
		$fromworkList = $this->service->fromworkInfo_view($fromworkInfo);
		$this->assign('fromworkList',$fromworkList);
		if($flag){
			$this->view ('approval-edit');
		}else{
			$this->view ('view');
		}
	}

	/**
	 * 清单确认查看页
	 */
	function c_editHandover(){
		$leaveId = $_GET['leaveId'];
		//判断是否存在面谈记录
		$sql = "select id  from oa_leave_handover where leaveId=".$leaveId."";
		$flagArr = $this->service->_db->getArray($sql);
		$obj = $this->service->get_d ( $flagArr[0]['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//处理渲染离职交接清单详细
		$Dao = new model_hr_leave_handoverlist();
		$Dao->asc=false;
		$Dao->sort='sort';
		$Dao->searchArr['handoverId'] = $flagArr[0]['id'];
		$fromworkInfo = $Dao->list_d ("select_default");
		echo "<pre>";
		print_r($fromworkInfo);
		$fromworkList = $this->service->editHandoverList_d($fromworkInfo);
		$this->assign('fromworkList',$fromworkList);

		$this->view ('audit-edit');
	}

	/**
	 * 员工个人确认离职交接清单
	 */
	function c_handoverProlist(){
		$obj = $this->service->getnfo_d ( $_GET['leaveId'] );
		//提取离职原因，重新组合
		$obj['quitReson'] = str_replace('^nvsp',"：",$obj['quitReson']);
		$obj['quitReson'] = str_replace('^nbsp',"； ",$obj['quitReson']);
		$obj['quitReson'] = str_replace('^nqsp',"： ",$obj['quitReson']);
		$obj['quitReson'] = str_replace('^nqsd',"： ",$obj['quitReson']);
		$obj['quitReson'] = str_replace('^nbss',"； ",$obj['quitReson']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//处理渲染离职交接清单详细
		$Dao = new model_hr_leave_handoverlist();
		$Dao->asc=false;
		$Dao->sort='sort';
		$Dao->searchArr['handoverId'] = $obj['id'];
		$fromworkInfo = $Dao->list_d ("select_default");
		$fromworkList = $this->service->fromworkInfo_view($fromworkInfo);
		$this->assign('fromworkList',$fromworkList);
		$this->assign('staffAffDT',date("Y-m-d"));
		$this->view ('handoverProlist');
	}

	/**
	 * 离职交接清单审批后发送邮件
	 */
	function c_dealApproval(){
		$rows=isset($_GET['rows'])?$_GET['rows']:null;
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			if($folowInfo['Result']=="ok"&&is_array($rows)){
				$this->service->edit_d ( $rows);
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * 跳转到离职交接清单重启页面
	 */
	function c_toRestart() {
		$leaveDao = new model_hr_leave_leave();
		$leaveRow = $leaveDao->get_d ( $_GET['leaveId'] );
		//提取离职原因，重新组合
		$leaveRow['quitReson'] = str_replace('^nvsp',"：<br>",$leaveRow['quitReson']);
		$leaveRow['quitReson'] = str_replace('^nbsp',"； ",$leaveRow['quitReson']);
		$leaveRow['quitReson'] = str_replace('^nqsp',"： ",$leaveRow['quitReson']);
		$leaveRow['quitReson'] = str_replace('^nqsd',"： ",$leaveRow['quitReson']);
		$leaveRow['quitReson'] = str_replace('^nbss',"；<br> ",$leaveRow['quitReson']);
		//var_dump($leaveRow);exit();
		foreach ( $leaveRow as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$obj = $this->service->find ( array("leaveId" => $_GET['leaveId']) );
		$this->assign ( 'handoverId', $obj['id'] );

		$this->view ('restart');
	}

	/**
	 * 跳转到修改交接人页面
	 */
	function c_toAlterHand() {
		$leaveDao = new model_hr_leave_leave();
		$leaveRow = $leaveDao->get_d ( $_GET['leaveId'] );
		//提取离职原因，重新组合
		$leaveRow['quitReson'] = str_replace('^nvsp',"：<br>",$leaveRow['quitReson']);
		$leaveRow['quitReson'] = str_replace('^nbsp',"； ",$leaveRow['quitReson']);
		$leaveRow['quitReson'] = str_replace('^nqsp',"： ",$leaveRow['quitReson']);
		$leaveRow['quitReson'] = str_replace('^nqsd',"： ",$leaveRow['quitReson']);
		$leaveRow['quitReson'] = str_replace('^nbss',"；<br> ",$leaveRow['quitReson']);
		foreach ( $leaveRow as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$obj = $this->service->find ( array("leaveId" => $_GET['leaveId']) );
		$this->assign ( 'handoverId', $obj['id'] );

		$this->view ('alterHand');
	}

	/**
	 * 批量打印离职交接清单
	 */
	function c_printOrderAll(){
		//id串
		$ids = null;
		if(isset($_POST['leave'])) {
			$leaveDatas = $_POST['leave'];
			$ids = explode(',',$leaveDatas['ids']);
			if($leaveDatas['ids'] && $leaveDatas['idchecked']) {
				$idArr = array_merge($ids,$leaveDatas['idchecked']);
			} else if($leaveDatas['idchecked']) {
				$idArr = $leaveDatas['idchecked'];
			} else if($leaveDatas['ids']) {
				$idArr = $ids;
			}
		}
		$ids = implode(',',$idArr);
		$this->assign('allNumH',count($idArr));
		$this->view('printOrderHead');
		foreach($idArr as $key => $val) {
			//判断是否存在面谈记录
			$sql = "select id  from oa_leave_handover where leaveId=".$val."";
			$flagArr = $this->service->_db->getArray($sql);
			$leaveDao = new model_hr_leave_leave();
			$obj = $this->service->get_d ( $flagArr[0]['id'] );
			foreach ( $obj as $k => $v ) {
				$this->assign ( $k, $v );
			}
			//获取联系方式
			$obj = $leaveDao->get_d ( $val );
			$this->assign ( 'mobile', $obj['mobile'] );
			$this->assign ( 'personEmail', $obj['personEmail'] );
			//处理渲染离职交接清单详细
			$Dao = new model_hr_leave_handoverlist();
			$Dao->asc=false;
			$Dao->sort='sort'; // 选择要排序的字段
			$Dao->searchArr['handoverId'] = $flagArr[0]['id'];
			$fromworkInfo = $Dao->list_d ("select_default");
			$fromworkList = $this->service->fromworkInfo_print($fromworkInfo);
			$this->assign('fromworkList',$fromworkList);
			$this->assign('id',$val);
			$this->view('printOrderBody');
		}
		$this->assign('allNum',count($idArr));
		$this->assign('ids',$ids);
		$this->view('printOrderTail');
	}
 }
?>