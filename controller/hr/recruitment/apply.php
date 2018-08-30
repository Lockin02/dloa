<?php
/**
 * @author Administrator
 * @Date 2012年7月11日 星期三 13:20:21
 * @version 1.0
 * @description:增员申请表控制层
 */
class controller_hr_recruitment_apply extends controller_base_action {

	function __construct() {
		$this->objName = "apply";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	 }

	/**
	 * 跳转到增员申请表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到增员申请(招聘组)列表
	 */
	function c_pageTeam() {
		$this->view('list-team');
	}

	/**
	 * 跳转到我的增员申请表列表
	 */
	function c_mypage() {
		$this->view('mylist');
	}

	/**
	 * 跳转到我协助的增员申请表列表
	 */
	function c_myassistpage() {
		$this->view('myassistlist');
	}

	/**
	 * 跳转到我负责的增员申请表列表
	 */
	function c_mymainpage() {
		$this->view('mymainlist');
	}

	/**
	 * 选择增员申请弹出的选择页面
	 */
	function c_selectApply() {
		$this->view('selectapply');
	}

	/**
	 * 跳转到新增增员申请表页面
	 */
	function c_toAdd() {
		$area = new includes_class_global();
		$this->assign('area_select' ,$area->area_select());
		$this->assign('formManId', $_SESSION['USER_ID']);
		$this->assign('formManName', $_SESSION['USERNAME']);
		$this->assign('resumeToId', $_SESSION['USER_ID']);
		$this->assign('resumeToName', $_SESSION['USERNAME']);

		//获取部门
		$this->assign('deptName' ,$_SESSION["DEPT_NAME"]);
		$this->assign('deptId' , $_SESSION['DEPT_ID']);
		$sendTime = date("Y-m-d");
		$this->assign('formDate' ,$sendTime);
		$this->showDatadicts(array('addTypeCode' => 'HRZYLX')); //增员类型
		$this->showDatadicts(array('addModeCode' => 'HRBCFS')); //建议补充方式
		$this->showDatadicts(array('employmentTypeCode' => 'HRPYLX')); //用工类型
		$this->showDatadicts(array('maritalStatus' => 'HRHYZK')); //婚姻
		$this->showDatadicts(array('education' => 'HRZYXL')); //学历
		$this->showDatadicts(array('postType' => 'YPZW')); //职位类型
		$this->view ('add' ,true);
	}

	/**
	 * 跳转到编辑增员申请表页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['sex'] == '男') {
			$this->assign ( 'manCheck', 'checked' );
		} else if ($obj ['sex'] == '女') {
			$this->assign ( 'womanCheck', 'checked' );
		} else{
			$this->assign ( 'otherCheck', 'checked' );
		}
		if ($obj ['isEmergency'] == 1) {
			$this->assign ( 'isEmergency', 'checked' );
		} else if ($obj ['isEmergency'] == 0) {
			$this->assign ( 'noEmergency', 'checked' );
		}
		$this->assign('editFromApply', $_GET ['editFromApply'] ); //判断是从我的招聘发起的修改还是招聘管理发起的修改
		$this->assign('html',$this->service->showHtm($_GET ['editFromApply'],$obj)); //根据判断，决定是否显示"关键要点"等三项
		$area = new includes_class_global();
		$useAreaId = $obj['useAreaId'];
		$this->assign('area_select',$area->area_select($useAreaId));
		$this->showDatadicts(array('addTypeCode' => 'HRZYLX') ,$obj['addTypeCode']);//增员类型
		$this->showDatadicts(array('addModeCode' => 'HRBCFS') ,$obj['addModeCode']);//建议补充方式
		$this->showDatadicts(array('employmentTypeCode' => 'HRPYLX') ,$obj['employmentTypeCode']);//用工类型
		$this->showDatadicts(array('maritalStatus' => 'HRHYZK') ,$obj['maritalStatus']);//婚姻
		$this->showDatadicts(array('education' => 'HRZYXL') ,$obj['education']);//学历
		$this->showDatadicts(array('postType' => 'YPZW') ,$obj['postType']);//职位类型
		$this->assign("file" ,$this->service->getFilesByObjId ( $_GET ['id']) );
		$this->view('edit' ,true);
	}

	/**
	 * 跳转到编辑增员申请表页面
	 */
	function c_toAuditEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['sex'] == '男') {
			$this->assign ( 'manCheck', 'checked' );
		} else if ($obj ['sex'] == '女') {
			$this->assign ( 'womanCheck', 'checked' );
		} else {
			$this->assign ( 'otherCheck', 'checked' );
		}
		if ($obj ['isEmergency'] == 1) {
			$this->assign ( 'isEmergency', 'checked' );
		} else if ($obj ['isEmergency'] == 0) {
			$this->assign ( 'noEmergency', 'checked' );
		}
		$area = new includes_class_global();
		$useAreaId = $obj['useAreaId'];
		$this->assign('area_select' ,$area->area_select($useAreaId));
		$this->showDatadicts(array('addTypeCode' => 'HRZYLX') ,$obj['addTypeCode']);//增员类型
		$this->showDatadicts(array('addModeCode' => 'HRBCFS') ,$obj['addModeCode']);//建议补充方式
		$this->showDatadicts(array('employmentTypeCode' => 'HRPYLX') ,$obj['employmentTypeCode']);//用工类型
		$this->showDatadicts(array('maritalStatus' => 'HRHYZK') ,$obj['maritalStatus']);//婚姻
		$this->showDatadicts(array('education' => 'HRZYXL') ,$obj['education']);//学历
		$this->showDatadicts(array('postType' => 'YPZW') ,$obj['postType']);//职位类型
		$this->assign("file" ,$this->service->getFilesByObjId( $_GET['id']) );
		if($_GET ['isAudit']=='no'){
			$this->assign ( 'postType', $obj['postType'] );
			$this->view ('noAudit-edit' ,true);
		} else {
			$this->view ('audit-edit' ,true);
		}
	}

	function c_toTabView() {
		$obj=$this->service->get_d($_GET['id']);
		$stateC=$this->service->statusDao->statusKtoC($obj['state']);
		$this->assign ( "id", $_GET['id'] );
		$this->assign('stateC', $stateC);
		$this->assign('ExaStatus', $obj['ExaStatus']);
		$this->view ( 'tabview' );
	}

	/**
	 * 跳转到查看增员申请表页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj['isEmergency'] == "1") {
			$this->assign ( 'isEmergency', '是' );
		} else if ($obj['isEmergency'] == "0") {
			$this->assign ( 'isEmergency', '否' );
		}
		if ($obj['projectType'] == "YFXM") {
			$this->assign ( 'projectType', '研发项目' );
		} else if ($obj['projectType'] == "GCXM") {
			$this->assign ( 'projectType', '工程项目' );
		} else  {
			$this->assign ( 'projectType', '' );
		}
		if($_GET['judge'] == 1) {
				$html = <<<EOT
				<tr style="height:80px;">
					<td class="form_text_left_three">关键要点</td>
					<td class="form_text_right_three" colspan="5">
						<textarea class="textarea_read_overflow" readonly>{$obj['keyPoint']}</textarea>
					</td>
				</tr>
				<tr style="height:80px;">
					<td class="form_text_left_three">注意事项</td>
					<td class="form_text_right_three" colspan="5">
						<textarea class="textarea_read_overflow" readonly>{$obj['attentionMatter']}</textarea>
					</td>
				</tr>
				<tr style="height:80px;">
					<td class="form_text_left_three">部门领导喜好</td>
					<td class="form_text_right_three" colspan="5">
						<textarea class="textarea_read_overflow" readonly>{$obj['leaderLove']}</textarea>
					</td>
				</tr>
EOT;
			$this->assign ( 'points', $html );
		} else if ($_GET['actType'] =='audit') {
			$html = $this->service->getDeptPer_d($_GET['id']);
			$this->assign ( 'points', $html );
		} else {
			$this->assign ( 'points', '' );
		}

		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'],false ));
		$this->assign ( 'actType', $actType );
		$this->view ( 'view' );
	}

	/**
	 * 跳转到查看增员申请表页面
	 */
	function c_toEditView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj['isEmergency'] == "1") {
			$this->assign ( 'isEmergency', '是' );
		} else if ($obj ['isEmergency'] == "0") {
			$this->assign ( 'isEmergency', '否' );
		}
		if ($obj ['projectType'] =="YFXM") {
			$this->assign ( 'projectType', '研发项目' );
		} else if ($obj ['projectType'] == "GCXM") {
			$this->assign ( 'projectType', '工程项目' );
		} else  {
			$this->assign ( 'projectType', '' );
		}
		if ($obj ['positionLevel'] =="1") {
			$positionLevel='初级' ;
		} else if ($obj ['positionLevel'] == "2") {
			$positionLevel='中级';
		}else if ($obj ['positionLevel'] == "3") {
			$positionLevel='高级';
		}else {
			$positionLevel= '';
		}
		if ($obj ['positionLevelEdit'] =="1") {
			$positionLevelEdit='初级' ;
		} else if ($obj ['positionLevelEdit'] == "2") {
			$positionLevelEdit='中级';
		} else if ($obj ['positionLevelEdit'] == "3") {
			$positionLevelEdit='高级';
		} else {
			$positionLevelEdit= '';
		}
		if($obj['needNum']!=$obj['needNumEdit']){
			$needNumStr="<font color=red>".$obj['needNumEdit']." --> ".$obj['needNum']."<font>";
			$this->assign('needNum',$needNumStr);
		}
		if($obj ['positionLevel'] != $obj ['positionLevelEdit']){
			$positionLevelStr = "<font color=red>".$positionLevelEdit." --> ".$positionLevel."<font>";
			$this->assign('positionLevel',$positionLevelStr);
		} else {
			$this->assign('positionLevel',$positionLevel);
		}
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'],false ));
		$this->assign ( 'actType', $actType );
		$this->view ( 'edit-view' );
	}

	function c_toGive() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['isEmergency'] == "1") {
			$this->assign ( 'isEmergency', '是' );
		} else if ($obj ['isEmergency'] == "0") {
			$this->assign ( 'isEmergency', '否' );
		}
		if ($obj ['projectType'] =="YFXM") {
			$this->assign ( 'projectType', '研发项目' );
		} else if ($obj ['projectType'] == "GCXM") {
			$this->assign ( 'projectType', '工程项目' );
		}
		if ($obj ['positionLevel'] =="1") {
			$this->assign ( 'positionLevel', '初级' );
		} else if ($obj ['positionLevel'] == "2") {
			$this->assign ( 'positionLevel', '中级' );
		}else if ($obj ['positionLevel'] == "3") {
			$this->assign ( 'positionLevel', '高级' );
		}

		$datestr = date('Y-m-d');
		$this->assign("assignedManName" ,$_SESSION['USERNAME']);
		$this->assign("assignedManId" ,$_SESSION['USER_ID']);
		$this->assign("assignedDate" ,$datestr);
		$this->view ('give' ,true);
	}

	/**
	 * @author Admin
	 *修改招聘负责人
	 */
	function c_toEditHead() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['isEmergency'] == "1") {
			$this->assign ( 'isEmergency', '是' );
		} else if ($obj ['isEmergency'] == "0") {
			$this->assign ( 'isEmergency', '否' );
		}
		if ($obj ['projectType'] =="YFXM") {
			$this->assign ( 'projectType', '研发项目' );
		} else if ($obj ['projectType'] == "GCXM") {
			$this->assign ( 'projectType', '工程项目' );
		}
		if ($obj ['positionLevel'] =="1") {
			$this->assign ( 'positionLevel', '初级' );
		} else if ($obj ['positionLevel'] == "2") {
			$this->assign ( 'positionLevel', '中级' );
		}else if ($obj ['positionLevel'] == "3") {
			$this->assign ( 'positionLevel', '高级' );
		}
		$this->assign('editHeadTime' ,date('Y-m-d H:i:s'));
		$this->view ('edit-head' ,true);
	}

	/**
	 * 我的列表Json
	 */
	function c_myListJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->searchArr ['formManId'] = $_SESSION ['USER_ID'];
		//$service->asc = false;
		$service->groupBy='c.id';
		$rows = $service->page_d ('select_list');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				//转换成中文
				$rows[$key]['stateC']=$service->statusDao->statusKtoC($rows[$key]['state'] );
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 新增保存
	 */
	function c_add(){
		$this->checkSubmit(); //检查是否重复提交
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$obj = $_POST[$this->objName];
		$obj['actType'] = $actType;
		$id = $this->service->add_d($obj);
		if($id) {
			if('onSubmit' == $actType) {
				if($this->service->changeState($id)) {
					$obj = $this->service->get_d($id);
					$this->service->thisMail_d($obj);
					msg('提交成功');
				} else {
					msg('提交失败');
				}
			} else {
				msg('保存成功');
			}
		} else {
			msg('保存失败');
		}
	}

	/**
	 * 编辑保存
	 */
	function c_edit() {
		$this->checkSubmit(); //检查是否重复提交
		$id = $this->service->edit_d($_POST[$this->objName]);
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if($id) {
			if('onSubmit' == $actType) { //提交
				$id = $_POST[$this->objName]['id'];
				if($this->service->changeState($id)) {
					$obj = $this->service->get_d($id);
					$this->service->thisMail_d($obj);
					msg('提交成功');
				} else {
					msg('提交失败');
				}
			} else {
				msg('保存成功');
			}
		} else {
			msg('保存失败');
		}
	}

	/**
	 * 审批后提交修改增员申请 (变更)
	 */
	function c_auditEdit(){
		$this->checkSubmit(); //检查是否重复提交
		$oldRow = $this->service->get_d($_POST[$this->objName]['id']);
		$newRow = $_POST[$this->objName];

		$dictDao = new model_system_datadict_datadict();
		$newRow['postTypeName'] = $dictDao->getDataNameByCode($newRow['postType']);

		$newRow = $this->service->fillEdit($oldRow ,$newRow);
		if($newRow != '') {
			$id = $this->service->edit_d($newRow);
			if($id) {
				$this->service->auditEditMail_d($oldRow ,$newRow);
				succ_show('controller/hr/recruitment/ewf_edit_index.php?actTo=ewfSelect&billId='.$newRow['id'].'&billDept='.$newRow['deptId']);
			} else {
				msg('修改失败');
			}
		} else {
			$id = $this->service->edit_d($_POST[$this->objName]);
			if($id) {
				msg('修改成功');
			} else {
				msg('修改失败');
			}
		}
	}

	/**
	 * 变更增员申请（非审批）
	 */
	function c_noAuditEdit(){
		$this->checkSubmit(); //检查是否重复提交
		$oldRow = $this->service->get_d($_POST[$this->objName]['id']);
		$newRow = $_POST['apply'];
		$diff = array();
		//获取改变了的值
		foreach($newRow as $key => $val){
			if($val != $oldRow[$key]){
				$diff[$key] = $val;
			}
		}
		if($diff){
			//查找审批人
			$persons = $this->service->getAuditPersons_d($newRow);
			//发送邮件通知
			$mail = $this->service->sendEmail_d($diff ,$oldRow ,$persons);
		}
		$id = $this->service->edit_d($newRow);
		if($id) {
			msg('修改成功！');
		} else {
			msg('修改失败！');
		}
	}

	/**
	 * 增员申请修改审批处理
	 */
	function c_dealEditApply(){
		$this->checkSubmit(); //检查是否重复提交
		if(! empty ( $_GET ['spid'] ))
			$this->service->dealEditApply( $_GET ['spid']);
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * 增员申请审批后处理
	 */
	function c_dealAfterAudit() {
		$this->checkSubmit(); //检查是否重复提交
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			if($folowInfo['examines'] == "ok") {  //审批通过
				$this->service->applyAuditMail_d( $folowInfo['objId'] ,'通过');
			} else if ($folowInfo['examines'] == "no") {
				$this->service->applyAuditMail_d( $folowInfo['objId'] ,'不通过');
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * 分配负责人
	 */
	function c_assignHead(){
		$this->checkSubmit(); //检查是否重复提交
		$id = $this->service->assignHead_d($_POST[$this->objName]);
		if($id){
			msg('下达成功');
		} else {
			msg('下达失败');
		}
	}

	/**
	 * 修改负责人
	 */
	function c_editHead() {
		$this->checkSubmit(); //检查是否重复提交
		$id=$this->service->editHead_d($_POST[$this->objName]);
		if($id){
			msg('修改成功');
		}else{
			msg('修改失败');
		}
	}

	/**
	 * 获取主面试分页数据转成Json
	 */
	function c_myMainPageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$this->service->searchArr['recruitManId'] = $_SESSION['USER_ID'];
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$rows = $service->page_d ();
		$listRows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows);
		$newRows = $service->dealRows_d($rows,$listRows );

		$arr = array ();
		$arr ['collection'] = $newRows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($newRows ? count ( $newRows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 获取协助面试分页数据转成Json
	 */
	function c_myHelpPageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$this->service->searchArr['mylinkId'] = $_SESSION['USER_ID'];
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$rows = $service->page_d ();
		$listRows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows);
		$newRows = $service->dealRows_d($rows ,$listRows);
		$arr = array();
		$arr ['collection'] = $newRows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($newRows ? count ( $newRows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 获取数据转成Json(包含小计和总计)
	 */
	function c_pageJsonList() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->groupBy='c.id';
		$rows = $service->page_d ('select_list');
		$listRows = $service->list_d ('select_list');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows);
		$newRows=$service->dealRows_d($rows,$listRows );
		$arr = array ();
		$arr ['collection'] = $newRows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($newRows ? count ( $newRows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

    /**
     * 获取数据转成Json(部门增员申请)
     */
    function c_teamPageJsonList() {
        $service = $this->service;
        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //设置前台获取的参数信息
        $service->groupBy='c.id';
        //系统权限
        $sysLimit = $this->service->this_limit['部门权限'];
        $deptDao=new model_deptuser_dept_dept();
        $deptIds= $deptDao->getDeptIdsByUserId($_SESSION['USER_ID']);

        //办事处 － 全部 处理
        if(strstr($sysLimit,';;')){

        }else if(!empty($sysLimit)){//如果没有选择全部，则进行权限查询并赋值
            $_POST['deptIdArr'] = $sysLimit;
        }else{
            if(!empty($deptIds )){
                $_POST['deptIdArr'] =$deptIds;
            }else{
                $_POST['deptIdArr'] ='noId';
            }
        }
        $service->getParam($_POST); //设置前台获取的参数信息
        $rows = $service->page_d ('select_list');
        $listRows = $service->list_d ('select_list');
        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows);
        $newRows=$service->dealRows_d($rows,$listRows );
        $arr = array ();
        $arr ['collection'] = $newRows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($newRows ? count ( $newRows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['deptLeadFlag'] =empty($deptIds)?0:1;
        echo util_jsonUtil::encode ( $arr );
    }

	/**
	 * 改变申请的状态(ajax)
	 */
	function c_changeState(){
		$flag = $this->service->changeState_d($_POST);
		if($flag) {
			//add chenrf 20130522
			$obj = $this->service->get_d($_POST[id]);
			$msg = $_POST['msg'];
			$this->service->thisMail_d($obj,$_POST['state'],$msg);
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 改变申请的状态
	 */
	function c_tochangeState(){
		$state = isset ( $_GET ['state'] ) ? $_GET ['state'] : null;
		$stateName = $this->service->statusDao->statusKtoC( $state );
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('state',$state);
		$this->assign('stateName' ,$stateName);
		$this->view('changestate' ,true);
	}

	/**
	 * 改变申请的状态
	 */
	function c_changeApplyState(){
		$this->checkSubmit(); //检查是否重复提交
		$obj = $_POST[$this->objName];
		$state = $obj['state'];
		$re = $this->service->editState_d($obj);
		if($re) {
			$obj = $this->service->get_d($obj['id']);
			$this->service->emailNotice_d($obj ,$state);
			msg('保存成功');
		} else {
			msg('保存失败');
		}
	}

	/**
	 * 增员申请，打回单据操作
	 */
	function c_getBack(){
		$this->checkSubmit(); //检查是否重复提交
		$objId = $_POST['id'];
		$obj = $this->service->get_d($objId);
		$obj['state'] = 0;
		$re = $this->service->editState_d($obj);
		if($re){
			$mail = new model_common_mail();
			$title = '增员申请单据打回通知';
			$content = '您好,【'.$_SESSION['USERNAME'].'】对单据编号【'.$obj['formCode'].'】进行了打回操作！';
			$mail->mailGeneral($title,$obj['formManId'],$content,null);
			echo $re;
		}
	}

	 /*********************导入导出*************************************/
	/**
	 * 导入excel
	 */
	function c_toExcelIn() {
		$this->display('excelin');
	}

	/**
	 * 导入excel
	 */
	function c_excelIn(){
		set_time_limit(0);
		$resultArr = $this->service->addExecelData_d ();

		$title = '增员申请信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	function c_toExcelOut(){
		$this->showDatadicts ( array ('postType' => 'YPZW' ));
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ));//增员类型
		$this->view("excelout");
	}

	function c_excelOut(){
		$object = $_POST[$this->objName];
		$this->service->searchArr['state_d'] = '0';
		if(!empty($object['formCode']))	//单据编号
			$this->service->searchArr['formCode'] = $object['formCode'];
		if(!empty($object['formManName']))	//填表人
			$this->service->searchArr['formManName'] = $object['formManName'];
		if(!empty($object['resumeToName']))	//接口人
			$this->service->searchArr['resumeToNameSearch'] = $object['resumeToName'];
	 	if(!empty($object['deptNameO'])) //直属部门
			$this->service->searchArr['deptNameO'] = $object['deptNameO'];
		if(!empty($object['deptNameS'])) //二级部门
			$this->service->searchArr['deptNameS'] = $object['deptNameS'];
		if(!empty($object['deptNameT'])) //三级部门
			$this->service->searchArr['deptNameT'] = $object['deptNameT'];
		if(!empty($object['deptNameF'])) //四级级部门
			$this->service->searchArr['deptNameF'] = $object['deptNameF'];
		if(!empty($object['workPlace'])) //工作地点
			$this->service->searchArr['workPlaceSearch'] = $object['workPlace'];
		if(!empty($object['postType']))//职位类型
			$this->service->searchArr['postType'] = $object['postType'];
		if(!empty($object['positionName'])) //需求职位
			$this->service->searchArr['positionName'] = $object['positionName'];
		if(!empty($object['positionLevel'])) //级别
			$this->service->searchArr['positionLevelSearch'] = $object['positionLevel'];
		if(!empty($object['projectGroup'])) //所在项目组
			$this->service->searchArr['projectGroupSearch'] = $object['projectGroup'];
		if(($object['isEmergency'])!="") //是否紧急
			$this->service->searchArr['isEmergency'] = $object['isEmergency'];
		if(!empty($object['formDateBef'])) //填表时间
			$this->service->searchArr['formDateBefSearch'] = $object['formDateBef'];
		if(!empty($object['formDateEnd'])) //填表时间
			$this->service->searchArr['formDateEndSearch'] = $object['formDateEnd'];
		if(!empty($object['ExaDTBef'])) //申请通过时间
			$this->service->searchArr['ExaDTBefSearch'] = $object['ExaDTBef'];
		if(!empty($object['ExaDTEnd'])) //申请通过时间
			$this->service->searchArr['ExaDTEndSearch'] = $object['ExaDTEnd'];
		if(!empty($object['assignedDateBef'])) //下达日期
			$this->service->searchArr['assignedDateBef'] = $object['assignedDateBef'];
		if(!empty($object['assignedDateEnd'])) //下达日期
			$this->service->searchArr['assignedDateEnd'] = $object['assignedDateEnd'];
		if(!empty($object['createTimeBef'])) //录用日期
			$this->service->searchArr['createTimeBefSearch'] = $object['createTimeBef'];
		if(!empty($object['createTimeEnd'])) //录用日期
			$this->service->searchArr['createTimeEndSearch'] = $object['createTimeEnd'];
		if(!empty($object['entryDateBef'])) //到岗时间
			$this->service->searchArr['entryDateBefSearch'] = $object['entryDateBef'];
		if(!empty($object['entryDateEnd'])) //到岗时间
			$this->service->searchArr['entryDateEndSearch'] = $object['entryDateEnd'];
		if(!empty($object['addTypeCode'])) //增员类型
			$this->service->searchArr['addTypeCode'] = $object['addTypeCode'];
		if(!empty($object['recruitManName'])) //招聘负责人
			$this->service->searchArr['recruitManName'] = $object['recruitManName'];
		if(!empty($object['assistManName'])) //招聘协助人
			$this->service->searchArr['assistManNameSearch'] = $object['assistManName'];
		if(!empty($object['applyReason'])) //需求原因
			$this->service->searchArr['applyReasonSearch'] = $object['applyReason'];
		if(!empty($object['workDuty'])) //工作职责
			$this->service->searchArr['workDutySearch'] = $object['workDuty'];
		if(!empty($object['jobRequire'])) //任职要求
			$this->service->searchArr['jobRequireSearch'] = $object['jobRequire'];
		if(!empty($object['keyPoint'])) //关键要点
			$this->service->searchArr['keyPoint'] = $object['keyPoint'];
		if(!empty($object['attentionMatter'])) //注意事项
			$this->service->searchArr['attentionMatter'] = $object['attentionMatter'];
		if(!empty($object['leaderLove'])) //部门领导喜好
			$this->service->searchArr['leaderLove'] = $object['leaderLove'];
		if(!empty($object['applyRemark'])) //进度备注
			$this->service->searchArr['applyRemarkSearch'] = $object['applyRemark'];
		if(!empty($object['state'])){ //单据状态
			$tmp_state = $object['state'];
			$state = implode(',',$tmp_state);
			$this->service->searchArr['stateArr'] = $state;
		}
		if(!empty($object['ExaStatus'])) { //审批状态
			$tmp_ExaStatus = $object['ExaStatus'];
			$ExaStatus = implode(',',$tmp_ExaStatus);
			$this->service->searchArr['ExaStatusArr'] = $ExaStatus;
		}
		set_time_limit(0);
		$this->service->groupBy = 'c.id';
		$rows = $this->service->list_d('select_list');

		$exportData = array();
		if(is_array($rows)){
			foreach ( $rows as $key => $val ){
				$val['stateC'] = $this->service->statusDao->statusKtoC( $val ['state'] );
				if ($val['isEmergency'] == "1") {
					$isEmergency = '是';
				} else if ($val['isEmergency'] == "0") {
					$isEmergency = '否';
				}

				if ($val ['projectType'] =="YFXM") {
					$projectType = '研发项目';
				} else if ($val ['projectType'] == "GCXM") {
					$projectType = '工程项目';
				}

				if($val['entryNum'] == '') {
					$val['entryNum'] = 0;
				}

				if($val['beEntryNum'] == '') {
					$val['beEntryNum'] = 0;
				}

				if ($val['userName'] == '') {
					$userName = $val['employName'];
				} else if ($val['employName'] == '') {
					$userName = $val['userName'];
				} else {
					$userName = $val['userName'].','.$val['employName'];
				}

				//审批通过时间（非审批完成状态不显示）
				if ($val['ExaStatus'] != '完成') {
					$val['ExaDT'] = '';
				}

				$exportData[$key]['formCode'] = $val['formCode'];
				$exportData[$key]['stateC'] = $val['stateC'];
				$exportData[$key]['ExaStatus'] = $val['ExaStatus'];
				$exportData[$key]['formManName'] = $val['formManName'];
				$exportData[$key]['resumeToName'] = $val['resumeToName'];
				$exportData[$key]['deptNameO'] = $val['deptNameO'];
				$exportData[$key]['deptNameS'] = $val['deptNameS'];
				$exportData[$key]['deptNameT'] = $val['deptNameT'];
				$exportData[$key]['deptNameF'] = $val['deptNameF'];
				$exportData[$key]['workPlace'] = $val['workPlace'];
				$exportData[$key]['postTypeName'] = $val['postTypeName'];
				$exportData[$key]['positionName'] = $val['positionName'];
				$exportData[$key]['developPositionName'] = $val['developPositionName'];
				$exportData[$key]['network'] = $val['network'];
				$exportData[$key]['device'] = $val['device'];
				$exportData[$key]['positionLevel'] = $val['positionLevel'];
				$exportData[$key]['projectGroup'] = $val['projectGroup'];
				$exportData[$key]['isEmergency'] = $isEmergency;
				$exportData[$key]['tutor'] = $val['tutor'];
				$exportData[$key]['computerConfiguration'] = $val['computerConfiguration'];
				$exportData[$key]['formDate'] = $val['formDate'];
				$exportData[$key]['ExaDT'] = $val['ExaDT'];
				$exportData[$key]['assignedDate'] = $val['assignedDate'];
				$exportData[$key]['createTime'] = substr($val['createTime'] ,0 ,10);
				$exportData[$key]['entryDate'] = substr($val['entryDate'] ,0 ,10);
				$exportData[$key]['firstOfferTime'] = substr($val['createTime'] ,0 ,10);
				$exportData[$key]['lastOfferTime'] = substr($val['lastOfferTime'] ,0 ,10);
				$exportData[$key]['addType'] = $val['addType'];
				$exportData[$key]['needNum'] = $val['needNum'];
				$exportData[$key]['entryNum'] = $val['entryNum'];
				$exportData[$key]['beEntryNum'] = $val['beEntryNum'];
				$exportData[$key]['stopCancelNum'] = $val['stopCancelNum'];
				$exportData[$key]['ingtryNum'] = $val['needNum'] - $val['entryNum'] - $val['beEntryNum'] - $val['stopCancelNum'];
				$exportData[$key]['recruitManName'] = $val['recruitManName'];
				$exportData[$key]['assistManName'] = $val['assistManName'];
				$exportData[$key]['userName'] = $userName;
				$exportData[$key]['applyReason'] = $val['applyReason'];
				$exportData[$key]['workDuty'] = $val['workDuty'];
				$exportData[$key]['jobRequire'] = $val['jobRequire'];
				$exportData[$key]['keyPoint'] = $val['keyPoint'];
				$exportData[$key]['attentionMatter'] = $val['attentionMatter'];
				$exportData[$key]['leaderLove'] = $val['leaderLove'];
				$exportData[$key]['applyRemark'] = $val['applyRemark'];
			}
		}
		return $this->service->excelOut ( $exportData );
	}

	/*********************导入导出*************************************/

	/***********add chenrf 20130508*************/
	/**
	 * 根据ID提交增员申请,并发送邮件
	 */
	function c_ajaxSubmit(){
		if($this->service->changeState($_POST['id'])){
			$obj = $this->service->get_d($_POST['id']);
			$this->service->thisMail_d($obj);
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 审批流
	 */
	function c_ewf(){
		$id = $_GET['id'];
		$obj = $this->service->get_d($id);

		$url = $this->service->ewfSelect($obj);
		if($url == '') {
			msg('提交数据错误，无法指定对应审批流!');
			exit;
		}
		succ_show($url);
	}

	/**
	 * 获取撤回申请审批流
	 */
	function c_delewf(){
		$id=$_GET['id'];
		$obj=$this->service->get_d($id);

		$url=$this->service->ewfDelWork($obj);
		if($url == '') {
			msg('提交数据错误，无法指定对应审批流!');
			exit();
		} else {
			$mail = new model_common_mail();
			$title = '增员申请撤回审批通知';
			$content = '您好,【'.$_SESSION['USERNAME'].'】对单据编号【'.$obj['formCode'].'】进行了撤销审批操作！';
			$mail->mailGeneral($title ,$obj['formManId'] ,$content,null);
			echo $url;
		}
	}

	/**
	 * 修改审批查看页面
	 */
	function c_toAuditEditView(){
		 $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['isEmergency'] == "1") {
			$this->assign ( 'isEmergency', '是' );
		} else if ($obj ['isEmergency'] == "0") {
			$this->assign ( 'isEmergency', '否' );
		}
		if ($obj ['projectType'] =="YFXM") {
			$this->assign ( 'projectType', '研发项目' );
		} else if ($obj ['projectType'] == "GCXM") {
			$this->assign ( 'projectType', '工程项目' );
		} else {
			$this->assign ( 'projectType', '' );
		}
		if ($obj ['projectTypeEdit'] =="YFXM") {
			$this->assign ( 'projectTypeEdit', '研发项目' );
		} else if ($obj ['projectTypeEdit'] == "GCXM") {
			$this->assign ( 'projectTypeEdit', '工程项目' );
		}

		$type = $this->getDatadicts ( array ('postType' => 'YPZW' ));
		$addTypeCode=$this->getDatadicts(array ('addTypeCode' => 'HRZYLX' ));
		$employmentTypeCode=$this->getDatadicts( array ('employmentTypeCode' => 'HRPYLX' ));//用工类型
		foreach ($type['YPZW'] as $val){
			if($val['dataCode']==$obj['postTypeEdit']){
				$this->assign ( 'postTypeEdit', $val['dataName']);
				break;
			}
		}
		foreach ($addTypeCode['HRZYLX'] as $val){
			if($val['dataCode']==$obj['addTypeCodeEdit']){
				$this->assign ( 'addTypeCodeEdit', $val['dataName']);
				break;
			}
		}

		foreach ($employmentTypeCode['HRPYLX'] as $val){
			if($val['dataCode']==$obj['employmentTypeCodeEdit']){
				$this->assign ( 'employmentTypeCodeEdit', $val['dataName']);
				break;
			}
		}

		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'],false ));
		$this->assign ( 'actType', $actType );
		$this->view ( 'auditEditView' );
	}

	/**
	 * 列表高级查询
	 */
	function c_toSearch(){
		$this->permCheck(); //安全校验
		$this->showDatadicts ( array ('postType' => 'YPZW' ));//职位类型
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ));//增员类型
		$this->view('search');
	}

	/*
	 * 跳转到修改关键点页面
	 */
	function c_toEditKeyPoints(){
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$act = isset ( $_GET ['act'] ) ? $_GET ['act'] : null;
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['isEmergency'] == "1") {
			$this->assign ( 'isEmergency', '是' );
		} else if ($obj ['isEmergency'] == "0") {
			$this->assign ( 'isEmergency', '否' );
		}
		if ($obj ['projectType'] == "YFXM") {
			$this->assign ( 'projectType', '研发项目' );
		} else if ($obj ['projectType'] == "GCXM") {
			$this->assign ( 'projectType', '工程项目' );
		} else {
			$this->assign ( 'projectType', '' );
		}
		$this->assign("file", $this->service->getFilesByObjId($_GET['id'],false));
		$this->assign('actType', $actType );
		$this->assign('act', $act );
		$this->view('editPoint' ,true);
	}

	/**
	 * 编辑关键要点
	 */
	function c_editKeyPoints(){
		$this->checkSubmit(); //检查是否重复提交
		$obj = $_POST['apply'];
		$act = isset ( $_POST ['act'] ) ? $_POST ['act'] : null;
		$id = $this->service->editKeyPoints($obj);
		if($id){
			msg('修改成功');
		} else {
			msg('修改失败');
		}
	}

	/*
	 * 跳转到修改录用名单页面
	 */
	function c_toEditEmploy(){
		$this->permCheck (); //安全校验
		$this->service->searchArr['id'] = $_GET['id'];
		$rows = $this->service->listBySqlId('select_list');

		$obj = $rows[0];
		foreach ($obj as $key => $val) {
			$this->assign($key ,$val);
		}

		$ingtryNum = $obj['needNum'] - $obj['beEntryNum'] - $obj['entryNum'];
		$this->assign('ingtryNum' ,$ingtryNum); //在招聘人数

		if ($obj['employId'] != '') {
			$employNum = count(explode(',' ,$obj['employId']));
		} else {
			$employNum = 0;
		}
		$this->assign('employNum' ,$employNum); //手动编辑的录用名单人数

		$this->view('editEmploy' ,true);
	}

	/**
	 * 修改录用名单
	 */
	function c_editEmploy(){
		$this->checkSubmit(); //检查是否重复提交
		$obj = $_POST[$this->objName];
		if ($_GET['isFinish'] == 'true') {
			$obj['state'] = 4;
		}
		if($this->service->updateById($obj)){
			msg('修改成功');
		} else {
			msg('修改失败');
		}
	}

	/**
	 * 跳转到查看取消招聘原因页面
	 */
	function c_toViewCancel() {
		$obj = $this->service->get_d($_GET['id']);
		$obj['stateName'] = $this->service->statusDao->statusKtoC( $obj['state'] );
		$this->assignFunc($obj);
		$this->view('view-cancel');
	}

	/**
	 * 跳转到查看启用跟暂停招聘原因页面
	 */
	function c_toViewStartstop() {
		$obj = $this->service->get_d($_GET['id']);
		$obj['stateName'] = $this->service->statusDao->statusKtoC( $obj['state'] );
		$this->assignFunc($obj);
		$this->view('view-startstop');
	}
 }

?>