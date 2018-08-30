<?php
/**
 * @author Administrator
 * @Date 2011年11月28日 10:42:46
 * @version 1.0
 * @description:员工借试用续借单控制层
 */
class controller_projectmanagent_borrow_renew extends controller_base_action {

	function __construct() {
		$this->objName = "renew";
		$this->objPath = "projectmanagent_borrow";
		parent::__construct ();
	 }

	/*
	 * 跳转到员工借试用续借单
	 */
    function c_renewList(){
    	$this->assign('borrowId',$_GET['id']);
    	$this->display('renewlist');
    }
     /**
      * 员工续借申请 处理
      */
     function c_renew(){
        $rows = $_POST['renew'];
        $act = isset ( $_GET ['act'] ) ? $_GET ['act'] : null;
        $id = $this->service->add_d ( $rows);
		if ($id) {
			if($act =='app'){
				succ_show('controller/projectmanagent/borrow/ewf_renewborrow.php?actTo=ewfSelect&billId=' . $id .'&borrowId=' . $rows['borrowId'] );
			}else{
				msgRF ("申请成功！");
			}
		}
     }
     /**
      * 续借查看
      */

	function c_view() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		//查看源单信息
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
	 * 审批通过后更新 借试用源单
	 */
	function c_updateBorrow(){
        if (! empty ( $_GET ['spid'] )) {
        	$this->service->workflowCallBack($_GET ['spid']);
		}
		if($_GET['urlType']){
			echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";

		}else{
			//防止重复刷新,审批后的跳转页面
			echo "<script>this.location='?model=projectmanagent_borrow_borrow&action=toProBorrowAll'</script>";
		}
	}
 }
?>