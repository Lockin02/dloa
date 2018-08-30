<?php
/**
 * @author Administrator
 * @Date 2013年3月7日 星期四 10:47:46
 * @version 1.0
 * @description:质检申请单清单控制层
 */
class controller_produce_quality_qualityapplyitem extends controller_base_action {

	function __construct() {
		$this->objName = "qualityapplyitem";
		$this->objPath = "produce_quality";
		parent::__construct ();
	}

	/**
	 * 跳转到质检申请单清单列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增质检申请单清单页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑质检申请单清单页面
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
	 * 跳转到查看质检申请单清单页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 *
	 * 获取质检申请清单subgrid数据
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
	 * 获取生产清单editgrid数据
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
	 * 确认免检页面
	 */
	function c_toConfirmPass(){
		if(empty($_GET['id'])){
			msg('没有传入id，请重新勾选物料进行此操作');
		}
		$this->assign('id',$_GET['id']);

        //邮件默认接收人渲染
		$defaultUserIdArr = array();
		$defaultUserNameArr = array();
        $isDamagePass = 0;
		if(!empty($_GET['relDocType'])){
			$relDocTypeArr = explode(",",$_GET['relDocType']);
			foreach ($relDocTypeArr as $val){
				if($val == 'ZJSQYDSL'){//采购收料通知单
					$mailInfo = $this->service->getMailUser_d('qualityapplyPassSL');
				}elseif($val == 'ZJSQYDGH'){//归还收料通知单
                    $isDamagePass = 1;
					$mailInfo = $this->service->getMailUser_d('qualityapplyPassGH');
				}elseif($val == 'ZJSQYDHH'){//换货收料通知单
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
 * 获取生产清单editgrid数据
 */
    function c_confirmPassJson() {
        $service = $this->service;
        $service->getParam ( $_REQUEST );
        $rows = $service->list_d ('select_confirmpass');
        echo util_jsonUtil::encode ( $rows );
    }

	/**
	 * 确认免检
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
     * 损坏放行
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
	 * 质检人员接收确认
	 */
	function c_ajaxReceive(){
		echo $this->service->ajaxReceive_d($_GET['ids']) ? 1 : 0;
	}
	
	/**
	 * 质检人员打回
	 */
	function c_ajaxBack(){
		echo $this->service->ajaxBack_d($_GET['ids']) ? 1 : 0;
	}

    /**
     * 检查选中的ID里面是否存在相同源单没有被选中的物料
     */
	function c_chkIsAllRelativeSelected(){
        $service = $this->service;
        $ids = isset($_REQUEST['ids'])? $_REQUEST['ids'] : '';
        $items = $service->chkIsAllRelativeSelected($ids);
        echo util_jsonUtil::encode ( $items );
    }
}