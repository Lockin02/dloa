<?php
/**
 * @author Administrator
 * @Date 2013年11月11日 星期一 22:21:40
 * @version 1.0
 * @description:备货申请表控制层
 */
class controller_stockup_apply_apply extends controller_base_action {

	function __construct() {
		$this->objName = "apply";
		$this->objPath = "stockup_apply";
		parent::__construct ();
	 }

	/**
	 * 跳转到备货申请表列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增备货申请表页面
	 */
	function c_toAdd() {
		$this->assign ( 'appDeptId', $_SESSION['DEPT_ID'] );
		$this->assign ( 'appDeptName', $_SESSION['DEPT_NAME'] );
		$this->assign ( 'appUserId', $_SESSION['USER_ID'] );
		$this->assign ( 'appUserName', $_SESSION['USERNAME'] );
		$this->assign ( 'appDate', date('Y-m-d'));
     	$this->view ( 'add',true );
   }

   /**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit();
		$object = $_POST[$this->objName];
		$id = $this->service->add_d($object,$object['auditType'] == 'audit'?true:false);
		if ($id) {
            if($object['auditType'] == 'audit'){
            	$num=0;
            	if($object['list']&&is_array($object['list'])){
            		foreach($object['list'] as $key =>$val){
            		 if($val['productNum']){
            		 	$num+=$num;
            		 }
            		}
            	}
        		if($num > 100){
               		succ_show('controller/stockup/apply/ewf_index.php?actTo=ewfSelect&billId=' . $id .'&flowMoney=6&billDept='.$object['appDeptId'] );
            	}else{
               		succ_show('controller/stockup/apply/ewf_index.php?actTo=ewfSelect&billId=' . $id .'&flowMoney=3&billDept='.$object['appDeptId'] );
            	}
             }else{
                msg("保存成功");
            }
		} else {
			msg("保存失败");
		}
	}

	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
		$this->checkSubmit();
		$object = $_POST[$this->objName];
		$rs = $this->service->edit_d($object);
		if ($rs) {
            if($object['auditType'] == 'audit'){
            	$num=0;
            	if($object['list']&&is_array($object['list'])){
            		foreach($object['list'] as $key =>$val){
            		 if($val['productNum']){
            		 	$num+=$num;
            		 }
            		}
            	}
        		if($num > 100){
               		succ_show('controller/stockup/apply/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'].'&flowMoney=6&billDept='.$object['appDeptId'] );
            	}else{
               		succ_show('controller/stockup/apply/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'] .'&flowMoney=3&billDept='.$object['appDeptId'] );
            	}
            }else{
                msg("保存成功");
            }
		} else {
			msg('保存失败');
		}
	}

   /**
	 * 跳转到编辑备货申请表页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'edit',true);
   }

   /**
	 * 关闭开启
	 */
	 function c_updateStatus(){
	   	if($_POST ['id']&&$_POST ['flag']){
	   		if($this->service->updateObjStatus((int)$_POST ['id'],'isClose',(int)$_POST ['flag'])==true){
	   			echo 1;
	   		}else{
	   			echo 2;
	   		}
	   	}
   }
   /**
	 * 跳转到查看备货申请表页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if($_GET['actType']=='audit'){
			$this->assign ( 'button', '' );
		}else{
			$this->assign ( 'button', '<input  type="button" class="txt_btn_a" value=" 关  闭 " onclick="closeFun()"/>' );
		}
		$this->assign ( 'actType', $_GET['actType'] );
      	$this->view ( 'view' );
   }
   /**
    *个人表格
    */
   function c_personList(){
   		$this->view('personList');
   }

   /**
	 * 表格方法
	 */
	function c_personListJson(){
		$service = $this->service;
		$rows = null;
		$service->getParam($_POST); //设置前台获取的参数信息
		$rows = $service->pageBySqlId('personList');
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	 /**
    *个人表格
    */
   function c_appList(){
		$keyType=($_POST['keyType']?$_POST['keyType']:$_GET['keyType']);
		$keyWords=($_POST['keyWords']?$_POST['keyWords']:$_GET['keyWords']);
		$keyTypeI=array('listNo,productCode,productName,appUserName'=>'所 有 ','listNo'=>'单据编号','productCode'=>'产品编码','productName'=>'产品名称','appUserName'=>'申请人');
		foreach($keyTypeI as $key=>$val){
			$keyTypeStr.="<option value='$key'".($key==$keyType?'selected':'')." >$val</option>";
		}
		$this->assign ( 'keyType', $keyTypeStr);
		$this->assign ( 'keyWords', $keyWords);
		$this->assign ( 'appList', $this->service->showAppList() );
   		$this->view('appList');
   }
    /**
    *
    */
    function c_showAppList(){
    	$msgI=$this->service->inPostAppList($_POST['apply']);
    	if($msgI['Flag']==1){
    		echo "<script>alert('操作成功!');window.location='?model=stockup_apply_apply&action=appList';</script>";
    	}else if($msgI['Flag']==2){
    		$this->assign ( 'appDate', date('Y-m-d'));
    		$this->assign ( 'appList', $msgI['str']);
    		$this->assign ( 'matterJosn', $msgI['matterJosn']);
   			$this->view('showAppList');
    	}else{
    		echo "<script>alert('操作失败!');window.location='?model=stockup_apply_apply&action=appList';</script>";
    	}

    }
    /**
    *
    */
    function c_appTab(){
    		$this->view('appTab');

    }
     /**
      *
    */
    function c_closeList(){
    	$keyType=($_POST['keyType']?$_POST['keyType']:$_GET['keyType']);
		$keyWords=($_POST['keyWords']?$_POST['keyWords']:$_GET['keyWords']);
		$keyTypeI=array('listNo,productCode,productName,appUserName'=>'所 有 ','listNo'=>'单据编号','productCode'=>'产品编码','productName'=>'产品名称','appUserName'=>'申请人');
		foreach($keyTypeI as $key=>$val){
			$keyTypeStr.="<option value='$key'".($key==$keyType?'selected':'')." >$val</option>";
		}
		$this->assign ( 'keyType', $keyTypeStr);
		$this->assign ( 'keyWords', $keyWords);
		$this->assign ( 'appList', $this->service->showAppCloseList() );
		$this->view('closeList');
    }
    function c_inAppCloseList(){
    	if($this->service->inAppCloseList($_POST['apply'])){
    		msgGo ( "操作成功！",'index1.php?model=stockup_apply_apply&action=closeList');
    	}else{
    		msg ( "操作失败！" );
    	}
    }

    /**
	 * 审批完成后发送邮箱的方法
	 */
	function c_appAfterMail(){
		$otherdatas = new model_common_otherdatas ();
		if( $_GET ['spid']){
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			$arr = $this->service->setMail($folowInfo['objId'],$folowInfo['examines']);
		}
		succ_show('?model=common_workflow_workflow&action=auditingList');

	}

 }
?>