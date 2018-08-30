<?php


/**
 * �ʲ��������Ʋ���
 *  @author chenzb
 */
class controller_asset_daily_allocation extends controller_base_action {

	public $provArr;

	function __construct() {
		$this->objName = "allocation";
		$this->objPath = "asset_daily";
		parent :: __construct();
	}
	/**
	 * ��ת��������Ϣ�б�
	 */
	function c_page() {
		$this->view('list');
	}


	/**
	 * ��ת��������Ϣ�б�
	 */
	function c_myList() {
		$this->assign('userId',$_SESSION['USER_ID']);
		$this->view('mylist');
	}
	/**
	 * ��ת����Ŀ�����б�
	 */
	function c_listByPro() {
		$this->view('prolist');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_allPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		foreach($rows as $key=>$row){
			$rows[$key]['deptId']=$this->service->getBillDept($row);
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$this->service->searchArr['userId'] = $_SESSION['USER_ID'];
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		foreach($rows as $key=>$row){
			$rows[$key]['deptId']=$this->service->getBillDept($row);
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageByProJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$this->service->searchArr['projectId'] = $_POST['projectId'];
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
		 * ��ת������ҳ��
		 */
	function c_toAddByCard() {
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->getBranchName_d($_SESSION['Company']);
		$this->assign('applyCompanyCode', $_SESSION['Company']);
		$this->assign('applyCompanyName', $branchArr['NameCN']);
		$this->assign('outDeptId', $_SESSION['DEPT_ID']);
		$this->assign('proposerId', $_SESSION['USER_ID']);
		$this->assign('proposer', $_SESSION['USERNAME']);
		$deptDao = new model_deptuser_dept_dept();
		$deptInfo = $deptDao->getDeptName_d($_SESSION['DEPT_ID']);
		$this->assign('outDeptName', $deptInfo['DEPT_NAME']);
		$this->assign('moveDate', date("Y-m-d"));
		$this->assign('assetId', $_GET['assetId']);
		$this->view('addbycard');
	}

	/**
		 * ��ת������ҳ��
		 */
	function c_toAdd() {
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->getBranchName_d($_SESSION['Company']);
		$this->assign('applyCompanyCode', $_SESSION['Company']);
		$this->assign('applyCompanyName', $branchArr['NameCN']);
		$this->assign('outDeptId', $_SESSION['DEPT_ID']);
		$this->assign('proposerId', $_SESSION['USER_ID']);
		$this->assign('proposer', $_SESSION['USERNAME']);
		$deptDao = new model_deptuser_dept_dept();
		$deptInfo = $deptDao->getDeptName_d($_SESSION['DEPT_ID']);
		$this->assign('outDeptName', $deptInfo['DEPT_NAME']);
		$this->assign('moveDate', date("Y-m-d"));
		$this->view('add');
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		//$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		if (isset ($_GET['btn'])) {
			$this->assign('showBtn', 1);
		} else {
			$this->assign('showBtn', 0);
		}

		foreach ($obj as $key => $val) {
			$this->show->assign($key, $val);
		}
		if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
			//echo"1111";
			$this->view('view');
		} else {
			$this->view('edit');
		}
	}
	//ǩ�շ������޸�ǩ��״̬
	function c_sign() {
		try {
			$id = isset ($_GET['id']) ? $_GET['id'] : false;
			$allocationobject = array (
				"id" => $id,
				"isSign" => "��ǩ��"
			);
			$this->service->updateById($allocationobject);
			echo 1;
		} catch (Exception $e) {
			throw $e;
			echo 0;
		}
	}
	/**
	  * ajaxɾ���������������嵥�ӱ���Ϣ
	  */
	function c_deletes() {
		$message = "";
		try {
			$allocationObj = $this->service->get_d($_GET['id']);
			$allocationitemDao = new model_asset_daily_allocationitem();
			$condition = array (
				'allocateID' => $allocationObj['id']
			);
			$allocationitemDao->delete($condition);
			$this->service->deletes_d($_GET['id']);

			$message = '<div style="color:red" align="center">ɾ���ɹ�!</div>';

		} catch (Exception $e) {
			$message = '<div style="color:red" align="center">ɾ��ʧ�ܣ��ö�������Ѿ�������!</div>';
		}
		if (isset ($_GET['url'])) {
			$event = "document.location='" . iconv('utf-8', 'gb2312', $_GET['url']) . "'";
			showmsg($message, $event, 'button');
		} else
			if (isset ($_SERVER[HTTP_REFERER])) {
				$event = "document.location='" . $_SERVER[HTTP_REFERER] . "'";
				showmsg($message, $event, 'button');
			} else {
				$this->c_page();
			}

		msg('ɾ���ɹ���');

	}

	/**
	* �����������
	* @linzx
	*/
	function c_add() {
		$object = $_POST[$this->objName];
		$userDao = new model_deptuser_user_user();
		$proposerObj = $userDao->getUserById($object['proposerId']);//������
		$recipientObj = $userDao->getUserById($object['recipientId']);//������
		//��������
		$outDeptId = $proposerObj['DEPT_ID'];
		//���벿��
		$inDeptId = $recipientObj['DEPT_ID'];
		$deptId = $outDeptId.','.$inDeptId;
		$id = $this->service->add_d($object, true);
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show('controller/asset/daily/ewf_index_allocation.php?actTo=ewfSelect&billId='
				. $id . '&billDept='.$deptId);
			} else {
				echo "<script>alert('�����ɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		} else {
			if ("audit" == $actType) {
				echo "<script>alert('��������ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		}
	}

	/**
		 * �޸Ķ������
		 * @chenzb
		 */
	function c_edit() {
		$object = $_POST[$this->objName];
		$userDao = new model_deptuser_user_user();
		$proposerObj = $userDao->getUserById($object['proposerId']);
		$recipientObj = $userDao->getUserById($object['recipientId']);
		//��������
		$outDeptId = $proposerObj['DEPT_ID'];
		//���벿��
		$inDeptId = $recipientObj['DEPT_ID'];
		$deptId = $outDeptId.','.$inDeptId;
		$id = $this->service->edit_d($object, true);
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show('controller/asset/daily/ewf_index_allocation.php?actTo=ewfSelect&billId='
				. $object['id'] . '&billDept='.$deptId);
			} else {
				echo "<script>alert('�޸ĳɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		} else {
			if ("audit" == $actType) {
				echo "<script>alert('�����޸�ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				echo "<script>alert('�޸�ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
			}

		}
	}

}
?>