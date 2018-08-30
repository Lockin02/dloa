<?php
/**
 * @author Administrator
 * @Date 2013��11��11�� ����һ 22:21:40
 * @version 1.0
 * @description:�����������Ʋ�
 */
class controller_stockup_apply_apply extends controller_base_action {

	function __construct() {
		$this->objName = "apply";
		$this->objPath = "stockup_apply";
		parent::__construct ();
	 }

	/**
	 * ��ת������������б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת���������������ҳ��
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
	 * �����������
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
                msg("����ɹ�");
            }
		} else {
			msg("����ʧ��");
		}
	}

	/**
	 * �޸Ķ���
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
                msg("����ɹ�");
            }
		} else {
			msg('����ʧ��');
		}
	}

   /**
	 * ��ת���༭���������ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'edit',true);
   }

   /**
	 * �رտ���
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
	 * ��ת���鿴���������ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if($_GET['actType']=='audit'){
			$this->assign ( 'button', '' );
		}else{
			$this->assign ( 'button', '<input  type="button" class="txt_btn_a" value=" ��  �� " onclick="closeFun()"/>' );
		}
		$this->assign ( 'actType', $_GET['actType'] );
      	$this->view ( 'view' );
   }
   /**
    *���˱��
    */
   function c_personList(){
   		$this->view('personList');
   }

   /**
	 * ��񷽷�
	 */
	function c_personListJson(){
		$service = $this->service;
		$rows = null;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->pageBySqlId('personList');
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	 /**
    *���˱��
    */
   function c_appList(){
		$keyType=($_POST['keyType']?$_POST['keyType']:$_GET['keyType']);
		$keyWords=($_POST['keyWords']?$_POST['keyWords']:$_GET['keyWords']);
		$keyTypeI=array('listNo,productCode,productName,appUserName'=>'�� �� ','listNo'=>'���ݱ��','productCode'=>'��Ʒ����','productName'=>'��Ʒ����','appUserName'=>'������');
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
    		echo "<script>alert('�����ɹ�!');window.location='?model=stockup_apply_apply&action=appList';</script>";
    	}else if($msgI['Flag']==2){
    		$this->assign ( 'appDate', date('Y-m-d'));
    		$this->assign ( 'appList', $msgI['str']);
    		$this->assign ( 'matterJosn', $msgI['matterJosn']);
   			$this->view('showAppList');
    	}else{
    		echo "<script>alert('����ʧ��!');window.location='?model=stockup_apply_apply&action=appList';</script>";
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
		$keyTypeI=array('listNo,productCode,productName,appUserName'=>'�� �� ','listNo'=>'���ݱ��','productCode'=>'��Ʒ����','productName'=>'��Ʒ����','appUserName'=>'������');
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
    		msgGo ( "�����ɹ���",'index1.php?model=stockup_apply_apply&action=closeList');
    	}else{
    		msg ( "����ʧ�ܣ�" );
    	}
    }

    /**
	 * ������ɺ�������ķ���
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