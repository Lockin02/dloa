<?php
/**
 * @author Show
 * @Date 2012��8��31�� ������ 14:53:12
 * @version 1.0
 * @description:Ա�����üƻ���ϸ���Ʋ�
 */
class controller_hr_trialplan_trialplandetail extends controller_base_action {

	function __construct() {
		$this->objName = "trialplandetail";
		$this->objPath = "hr_trialplan";
		parent :: __construct();
	}

	/************** �б��� *******************/

	/**
	 * ��ת��Ա�����üƻ���ϸ�б�
	 */
	function c_page() {
		$this->view('list');
	}

    /**
     * �鿴�б�
     */
    function c_viewList(){
        $this->assignFunc($_GET);
    	$this->view('listview');
    }

	/**
	 * �ҵ������б�
	 */
	function c_myList(){
		$this->view('listmy');
	}

    /**
     * �ҵ�����
     */
    function c_myJson(){
        $service = $this->service;

        $_POST['memberId'] = $_SESSION['USER_ID'];
        $service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $rows = $service->page_d ();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        echo util_jsonUtil::encode ( $arr );
    }

	/**
	 * �ҵ�ʹ������
	 */
	function c_myManage(){
		$this->view('listmymanage');
	}

    /**
     * �ҵ�����
     */
    function c_myManageJson(){
        $service = $this->service;

        $_POST['managerId'] = $_SESSION['USER_ID'];
        $service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $rows = $service->page_d ();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        echo util_jsonUtil::encode ( $arr );
    }


	/*************** ��ɾ�Ĳ� ******************/

	/**
	 * ��ת������Ա�����üƻ���ϸҳ��
	 */
	function c_toAdd() {
		//�ж��Ƿ���PLANID
		if(empty($_GET['planId'])){
			msg('û�����ö�Ӧ����ѵ�ƻ���üƻ�����ɣ������б��жԸ���Ա����һ����ѵ�ƻ�');
			die();
		}

		//����������Ⱦ
		$this->showDatadicts(array('taskType' => 'HRSYRW'));

		//��Ⱦ�ƻ�
		$this->assign('planId',$_GET['planId']);
		$trialPlanObj = $this->service->getTrialPlanInfo_d($_GET['planId']);
		$this->assignFunc($trialPlanObj);

		//��Ա��Ϣ��Ⱦ
		$this->assign('memberName',$_GET['userName']);
		$this->assign('memberId',$_GET['userAccount']);

		//��ȡǰ������
		$beforeTaskArr = $this->service->getBeforeTask_d($_GET['planId']);
		$this->assign('beforeId',$this->service->showTaskSel_d($beforeTaskArr));

		$this->view('add');
	}

	/**
	 * �����������
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}
	}

	/**
	 * ��ת���༭Ա�����üƻ���ϸҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		//����������Ⱦ
		$this->showDatadicts(array('taskType' => 'HRSYRW'),$obj['taskType']);

        //������Ⱦ
		$ruleInfo = $this->service->getRuleInfo_d($obj['isRule']);
		if($ruleInfo){
			$this->assignFunc($ruleInfo);
		}else{
			$thisRuleInfo = array('upperLimit' => $obj['taskScore'],'lowerLimit' => 0);
			$this->assignFunc($thisRuleInfo);
		}

		//��ȡǰ������
		$beforeTaskArr = $this->service->getBeforeTask_d($obj['planId'],$obj['id']);
		$this->assign('beforeId',$this->service->showTaskSel_d($beforeTaskArr,$obj['beforeId']));

		//��Ⱦ�ƻ�
		$trialPlanObj = $this->service->getTrialPlanInfo_d($obj['planId']);
		$this->assignFunc($trialPlanObj);
		if($trialPlanObj){
			$this->assign('planScoreOther',bcsub($trialPlanObj['planScoreAll'],$obj['taskScore'],2));
		}

		$this->view('edit');
	}

    /**
     * �����������
     */
    function c_edit() {
        $id = $this->service->edit_d ( $_POST );
        $msg = $_POST ["msg"] ? $_POST ["msg"] : '����ɹ���';
        if ($id) {
            msg ( $msg );
        }
    }

	/**
	 * ��ת���鿴Ա�����üƻ���ϸҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		//�������
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;

        //ѡ��ֵ��Ⱦ
        $this->assign('isNeed',$this->service->rtIsNeed_c($obj['isNeed']));
        $this->assign('closeType',$this->service->rtCloseType_c($obj['closeType']));

        //������Ⱦ
        $ruleInfo = $this->service->getRuleInfo_d($obj['isRule']);
        if($ruleInfo){
            $this->assignFunc($ruleInfo);
        }else{
            $thisRuleInfo = array('upperLimit' => $obj['taskScore'],'lowerLimit' => 0);
            $this->assignFunc($thisRuleInfo);
        }

		$this->view('view');
	}

	/**
	 * �ύ���
	 */
	function c_toHandUp(){
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
        $this->assign('thisDate',day_date);
		$this->view('handup');
	}

	//�ύ
	function c_handup(){
        $object = $_POST[$this->objName];
        $rs = $this->service->handup_d($object);
        if($rs){
        	msg('�ύ�ɹ�');
        }
	}

	/**
	 * �������
	 */
	function c_toScore(){
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
        $this->assign('thisDate',day_date);

        //������Ⱦ
		$ruleInfo = $this->service->getRuleInfo_d($obj['isRule']);
		if($ruleInfo){
			$this->assignFunc($ruleInfo);
		}else{
			$thisRuleInfo = array('upperLimit' => $obj['taskScore'],'lowerLimit' => 0);
			$this->assignFunc($thisRuleInfo);
		}

		//�������
		$this->assign('file',$this->service->getFilesByObjId ( $obj ['id'], false,$this->service->tbl_name )) ;
		$this->view('score');
	}

    /**
     * �������
     */
    function c_score(){
        $object = $_POST[$this->objName];
        $rs = $this->service->score_d($object);
        if($rs){
        	msg('��˳ɹ�');
        }
    }

    /**
     * ֱ�����
     */
    function c_complate(){
        $rs = $this->service->complate_d($_POST['id']);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
    }

    /**
     * ��������
     */
    function c_begin(){
        $rs = $this->service->begin_d($_POST['id']);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
    }

    /**
     * �ж�ǰ�������Ƿ��Ѿ����
     */
    function c_isComplate(){
        $rs = $this->service->isComplate_d($_POST['planId'],$_POST['taskName']);
        if($rs){
        	echo 1;
        }else{
        	echo 0;
        }
        exit();
    }

    /**
     * �ر�����
     */
    function c_close(){
        $rs = $this->service->close_d($_POST['id']);
        if($rs){
            echo 1;
        }else{
            echo 0;
        }
        exit();
    }
}
?>