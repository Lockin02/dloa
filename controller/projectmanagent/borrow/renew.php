<?php
/**
 * @author Administrator
 * @Date 2011��11��28�� 10:42:46
 * @version 1.0
 * @description:Ա�����������赥���Ʋ�
 */
class controller_projectmanagent_borrow_renew extends controller_base_action {

	function __construct() {
		$this->objName = "renew";
		$this->objPath = "projectmanagent_borrow";
		parent::__construct ();
	 }

	/*
	 * ��ת��Ա�����������赥
	 */
    function c_renewList(){
    	$this->assign('borrowId',$_GET['id']);
    	$this->display('renewlist');
    }
     /**
      * Ա���������� ����
      */
     function c_renew(){
        $rows = $_POST['renew'];
        $act = isset ( $_GET ['act'] ) ? $_GET ['act'] : null;
        $id = $this->service->add_d ( $rows);
		if ($id) {
			if($act =='app'){
				succ_show('controller/projectmanagent/borrow/ewf_renewborrow.php?actTo=ewfSelect&billId=' . $id .'&borrowId=' . $rows['borrowId'] );
			}else{
				msgRF ("����ɹ���");
			}
		}
     }
     /**
      * ����鿴
      */

	function c_view() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		//�鿴Դ����Ϣ
		$orBorrow = $this->service->orBorrow($obj['borrowId']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$dao = new model_projectmanagent_borrow_renewequ();
        $renewequ = $dao->renewTableview($obj['renewequ']);
        $this->assign('renewequ',$renewequ);
		$this->assign('borrow',$orBorrow);
		$this->display ( 'view' );
	}
	/**
	 * ����ͨ������� ������Դ��
	 */
	function c_updateBorrow(){
        if (! empty ( $_GET ['spid'] )) {
        	$this->service->workflowCallBack($_GET ['spid']);
		}
		if($_GET['urlType']){
			echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";

		}else{
			//��ֹ�ظ�ˢ��,���������תҳ��
			echo "<script>this.location='?model=projectmanagent_borrow_borrow&action=toProBorrowAll'</script>";
		}
	}
 }
?>