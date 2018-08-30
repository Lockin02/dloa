<?php
/**
 * @author Administrator
 * @Date 2013��3��7�� ������ 10:47:46
 * @version 1.0
 * @description:�ʼ����뵥�嵥���Ʋ�
 */
class controller_produce_quality_qualityapplyitem extends controller_base_action {

	function __construct() {
		$this->objName = "qualityapplyitem";
		$this->objPath = "produce_quality";
		parent::__construct ();
	}

	/**
	 * ��ת���ʼ����뵥�嵥�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת�������ʼ����뵥�嵥ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭�ʼ����뵥�嵥ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/**
	 * ��ת���鿴�ʼ����뵥�嵥ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 *
	 * ��ȡ�ʼ������嵥subgrid����
	 */
	function c_pageItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 *
	 * ��ȡ�����嵥editgrid����
	 */
	function c_editItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//		$arr = array ();
		//		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ȷ�����ҳ��
	 */
	function c_toConfirmPass(){
		if(empty($_GET['id'])){
			msg('û�д���id�������¹�ѡ���Ͻ��д˲���');
		}
		$this->assign('id',$_GET['id']);

        //�ʼ�Ĭ�Ͻ�������Ⱦ
		$defaultUserIdArr = array();
		$defaultUserNameArr = array();
        $isDamagePass = 0;
		if(!empty($_GET['relDocType'])){
			$relDocTypeArr = explode(",",$_GET['relDocType']);
			foreach ($relDocTypeArr as $val){
				if($val == 'ZJSQYDSL'){//�ɹ�����֪ͨ��
					$mailInfo = $this->service->getMailUser_d('qualityapplyPassSL');
				}elseif($val == 'ZJSQYDGH'){//�黹����֪ͨ��
                    $isDamagePass = 1;
					$mailInfo = $this->service->getMailUser_d('qualityapplyPassGH');
				}elseif($val == 'ZJSQYDHH'){//��������֪ͨ��
					$mailInfo = $this->service->getMailUser_d('qualityapplyPassHH');
				}
				array_push($defaultUserIdArr, $mailInfo['defaultUserId']);
				array_push($defaultUserNameArr, $mailInfo['defaultUserName']);
			}
		}
		$defaultUserIdStr = implode(",",$defaultUserIdArr);
		$defaultUserNameStr = implode(",",$defaultUserNameArr);
        $this->assign('sendUserId',$defaultUserIdStr);
        $this->assign('sendName',$defaultUserNameStr);
        $this->assign('isDamagePass',$isDamagePass);

		$this->view ( 'confirmpass');
	}

	/**
 *
 * ��ȡ�����嵥editgrid����
 */
    function c_confirmPassJson() {
        $service = $this->service;
        $service->getParam ( $_REQUEST );
        $rows = $service->list_d ('select_confirmpass');
        echo util_jsonUtil::encode ( $rows );
    }

	/**
	 * ȷ�����
	 */
	function c_confirmPass(){
		$ids = $_POST['ids'];
		$to_id = $_POST['TO_ID'];
		$issend = $_POST['issend'];
        $passReason = util_jsonUtil::iconvUTF2GB($_POST['passReason']);
		$rs = $this->service->confirmPass_d($ids,$issend,$to_id,$passReason);
		if($rs){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

    /**
     * �𻵷���
     */
    function c_damagePass(){
        $ids = $_POST['ids'];
        $to_id = $_POST['TO_ID'];
        $issend = $_POST['issend'];
        $passReason = util_jsonUtil::iconvUTF2GB($_POST['passReason']);
        $rs = $this->service->damagePass_d($ids,$issend,$to_id,$passReason);
        if($rs){
            echo 1;
        }else{
            echo 0;
        }
        exit();
    }
	
	/**
	 * �ʼ���Ա����ȷ��
	 */
	function c_ajaxReceive(){
		echo $this->service->ajaxReceive_d($_GET['ids']) ? 1 : 0;
	}
	
	/**
	 * �ʼ���Ա���
	 */
	function c_ajaxBack(){
		echo $this->service->ajaxBack_d($_GET['ids']) ? 1 : 0;
	}

    /**
     * ���ѡ�е�ID�����Ƿ������ͬԴ��û�б�ѡ�е�����
     */
	function c_chkIsAllRelativeSelected(){
        $service = $this->service;
        $ids = isset($_REQUEST['ids'])? $_REQUEST['ids'] : '';
        $items = $service->chkIsAllRelativeSelected($ids);
        echo util_jsonUtil::encode ( $items );
    }
}