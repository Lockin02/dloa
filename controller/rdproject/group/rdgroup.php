<?php
/**
 * @description: ��Ŀ���action
 * @date 2010-9-11 ����12:51:57
 * @author oyzx
 * @version V1.0
 */
class controller_rdproject_group_rdgroup extends controller_base_action {

	/**
	 * @desription ���캯��
	 * @date 2010-9-11 ����12:52:29
	 */
	function __construct() {
		$this->objName = "rdgroup";
		$this->objPath = "rdproject_group";
		$this->operArr = array ("groupName" => "��Ŀ�������", "simpleName" => "���" ); //ͳһע�����ֶΣ������ͬ�����в�ͬ�ļ���ֶΣ��ڸ��Է���������Ĵ�����
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊ��ͨaction����-----------------------------------------------*
	 **************************************************************************************************/
	/**
	 * ��Ŀ�����������תҳ��
	 */
	function c_page() {
		$this->service->createTreeLRValue();//�Զ�����������ֵ
		$this->show->display ( 'rdproject_project_rdproject-list' );
	}

	/**
	 * ��Ŀ��Ϸ���������תҳ��
	 */
	function c_pageAll() {
		$this->show->display ( 'rdproject_project_rdproject-listAll' );
	}

	/**
	 * @desription ��ת��ӷ���action
	 * @param tags
	 * @date 2010-9-23 ����03:52:42
	 */
	function c_toAdd() {
		$parentId = isset ( $_GET ['gpId'] ) ? $_GET ['gpId'] : "-1";
		$arr = $this->service->rgParentById_d ( $parentId );
		if($parentId != -1){
			foreach($arr as $key=>$val){
				$this->show->assign("id",$arr[$key]['id']);
				$this->show->assign("groupName",$arr[$key]['groupName']);
				$this->show->assign("groupCode",$arr[$key]['groupCode']);
			}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
		}else
		$this->show->display ( $this->objPath . '_' . $this->objName . '-upadd' );

	}

	/**
	 * @desription ��ӱ�����Ŀ���action
	 * @date 2010-9-13 ����04:16:50
	 */
	function c_rgAdd() {
		$objArr = $_POST [$this->objName];
		if ($objArr ['parentName'] == "��ѡ��..." || $objArr ['parentId'] == null || $objArr ['parentId'] == "" || $objArr ['parentId'] == "-1") {
			$objArr ['parentName'] = "root";
			$objArr ['parentId'] = - 1;
			$objArr ['parentCode'] = "root";
		}
		$id = $this->service->rgAdd_d ( $objArr );
		if ($id) {
			$objArr ['id'] = $id;
			$objArr ['operType_'] = "�����ϡ�" . $objArr ['groupName'] . "��";
			$this->behindMethod ( $objArr );
			msg ( '��ӳɹ���' );
		}else{
			msg ( '���ʧ�ܣ�' );
		}

	}

	/**
	 * @desription �޸���Ŀ��תaction
	 * @date 2010-9-16 ����03:02:07
	 */
	function c_rgUpdateTo() {
		$gpId = isset ( $_GET ['gpId'] ) ? $_GET ['gpId'] : exit ();
		$arr = $this->service->rgArrById_d ( $gpId );
		$this->arrToShow ( $arr );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-update' );

	}

	/**
	 * @desription TODO
	 * @date 2010-9-24 ����09:54:41
	 */
	function c_rgUpdate() {
		$rdgroup = $_POST ['rdgroup'];
//		echo "<pre>";
//		print_r($rdgroup);
		//����ǰִ�з�����������������ã�
		$this->beforeMethod ( $rdgroup );
		if ($this->service->edit_d ( $rdgroup )) {
//			//������¼
			$rdgroup ['operType_'] = "�༭��ϡ�" . $rdgroup ['groupName'] . "��";
			$this->behindMethod ( $rdgroup );//$this->behindMethod ( $rdgroup,'change' );
			msg ( '�༭�ɹ���' );
		}
	}

	/**
	 * @exclude �鿴���
	 * @version 2010-9-24 ����09:28:45
	 */
	function c_rgRead() {
		$gpId = isset ( $_GET ['gpId'] ) ? $_GET ['gpId'] : exit ();
		$arr = $this->service->rgArrById_d ( $gpId );
		$this->arrToShow ( $arr );
		$this->show->assign('gpId' , $gpId);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-read' );
	}

	function c_rgDel(){
		if(!$this->service->this_limit['���ɾ��']){
			echo "<script>alert('û��Ȩ�޽��в���!');history.back(-1);</script>";
			exit();
		}
		$gpId = isset ( $_GET ['gpId'] ) ? $_GET ['gpId'] : exit ();
		$rdgroup = $this->service->rgArrById_d($gpId);
		$retVal = $this->service->rgDel_d($gpId);
		if($retVal){
			$rdgroup ['0']['operType_'] = "ɾ����ϡ�" . $rdgroup ['0']['groupName'] . "��";
			$this->behindMethod ( $rdgroup['0'] );
			msgGo ( 'ɾ���ɹ���' );
		}else{
			msgGo ( 'ɾ��ʧ�ܣ�' );
		}
	}

	/**
	 * ��д���෽��
	 */
	function arrToShow($arr) {
		$assignName = $this->objName;
		foreach ( $arr ["0"] as $key => $val ) {
			if (! is_array ( $val )) {
				if( $key=="parentName"&&$val=="root" ){
					$this->show->assign ( $assignName . "[$key]", "��" );
				}else{
					$this->show->assign ( $assignName . "[$key]", $val );
				}
			}
		}
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊajax����json����---------------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription �����ϼ���ȡ��Ŀ����б�һ�����ڹ�������
	 * @date 2010-9-11 ����05:14:34
	 */
	function c_ajaxGroupByParent() {
		$service = $this->service;
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$searchArr = array ("parentId" => $_GET ['parentId'] );
		$service->searchArr = $searchArr;
		$service->sort = 'Id';
		$rows = $service->list_d ();
		//���Ƿ�Ҷ��ֵ0ת��false��1ת��true
		function toBoolean($row) {
			$row ['leaf'] = $row ['leaf'] == 0 ? false : true;
			return $row;
		}
		echo util_jsonUtil::encode ( array_map ( "toBoolean", $rows ) );
	}

	/**
	 * @desription ajax�ж���Ŀ��������Ƿ��ظ�
	 * @date 2010-9-13 ����02:22:04
	 */
	function c_ajaxGroupName() {
		$groupName = isset ( $_GET ['groupName'] ) ? $_GET ['groupName'] : false;
		$searchArr = array ("ajaxGroupName" => $groupName );
		$isRepeat = $this->service->isRepeat ( $searchArr, "" );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/**
	 * @desription ajax�ж���Ŀ��������Ƿ��ظ�UPDATE
	 * @date 2010-9-24 ����09:41:21
	 */
	function c_ajaxGroupNameUpdate() {
		$groupName = isset ( $_GET ['groupName'] ) ? $_GET ['groupName'] : false;
		$groupNameOld = isset ( $_GET ['groupNameOld'] ) ? $_GET ['groupNameOld'] : false;
		if ($groupName == $groupNameOld) {
			echo "1";
		} else {
			$searchArr = array ("ajaxGroupName" => $groupName );
			$isRepeat = $this->service->isRepeat ( $searchArr, "" );
			if ($isRepeat) {
				echo "0";
			} else {
				echo "1";
			}
		}
	}

	/*********************������Ҫ�����α���õ��ķ���******************************/

	/**
	 * @desription ��������Ŀ ��ȡĳһ����Ŀ����µ���Ϸ�ҳ
	 */
	function c_ajaxGroupByParentPending() {
		$searchArr = array ("parentId" => $_GET ['parentId'] );
		$projectDao = new model_rdproject_project_rdproject ();
		$statusDao = $projectDao->statusDao;
		$pjSeachArr = array (
			"createUser" => $_SESSION['USER_ID'],
			"statusArr" => $statusDao->statusEtoK ( "save" ) . "," . $statusDao->statusEtoK ( "approval" ) . "," . $statusDao->statusEtoK ( "fightback" ) . "," . $statusDao->statusEtoK ( "wite" )
		);
		$this->ajaxGroupByParent ( $searchArr,$pjSeachArr );
	}

	/**
	 * @desription ��������Ŀ �����ϼ����id��ȡ����µ���Ŀ��ϸ���Ŀ�б�
	 */
	function c_ajaxGroupAndProjectPending() {
		$searchArrGroup = array ("parentId" => $_GET ['parentId'] );
		$projectDao = new model_rdproject_project_rdproject ();
		$statusDao = $projectDao->statusDao;
		$searchArrProject = array (
			"createUser" => $_SESSION['USER_ID'],
			"groupId" => $_GET ['parentId'],
			"statusArr" => $statusDao->statusEtoK ( "save" ) . "," . $statusDao->statusEtoK ( "approval" ) . "," . $statusDao->statusEtoK ( "fightback" ) . "," . $statusDao->statusEtoK ( "wite" ) );
		$this->ajaxGroupAndProject ( $searchArrGroup, $searchArrProject );
	}

	/**
	 * @desription ��������Ŀ ��ȡĳһ����Ŀ����µ���Ϸ�ҳ
	 */
	function c_ajaxGroupByParentApproved() {
		$searchArr = array ("parentId" => $_GET ['parentId'] );
		$projectDao = new model_rdproject_project_rdproject ();
		$statusDao = $projectDao->statusDao;
		$pjSeachArr = array (
			"createUser" => $_SESSION['USER_ID'],
			"statusArr" => $statusDao->statusEtoK ( "locking" ) . "," . $statusDao->statusEtoK ( "execute" ) . "," . $statusDao->statusEtoK ( "end" ) . "," . $statusDao->statusEtoK ( "close" )
		);
		$this->ajaxGroupByParent ( $searchArr,$pjSeachArr );
	}

	/**
	 * @desription ��������Ŀ �����ϼ����id��ȡ����µ���Ŀ��ϸ���Ŀ�б�
	 */
	function c_ajaxGroupAndProjectApproved() {
		$searchArrGroup = array ("parentId" => $_GET ['parentId'] );
		$projectDao = new model_rdproject_project_rdproject ();
		$statusDao = $projectDao->statusDao;
		$searchArrProject = array (
			"groupId" => $_GET ['parentId'],
			"createUser" => $_SESSION['USER_ID'],
			"statusArr" => $statusDao->statusEtoK ( "locking" ) . "," . $statusDao->statusEtoK ( "execute" ) . "," . $statusDao->statusEtoK ( "end" ) . "," . $statusDao->statusEtoK ( "close" )
		);
		$this->ajaxGroupAndProject ( $searchArrGroup, $searchArrProject );
	}

	/**
	 * @desription ��Ŀ���� ��ȡ��һ����Ϸ�ҳ
	 * @param tags
	 * @date 2010-9-29 ����09:15:34
	 */
	function c_ajaxGroupByParentCenter () {
		$searchArr = array ("parentId" => $_GET ['parentId'] );
		$this->ajaxGroupByParent ( $searchArr );
	}

	/**
	 * @desription ��Ŀ���� �����ϼ����id��ȡ����µ���Ŀ��ϸ���Ŀ�б�
	 * @param tags
	 * @date 2010-9-29 ����09:18:52
	 */
	function c_ajaxGroupAndProjectCenter () {
		$searchArrGroup = array ("parentId" => $_GET ['parentId'] );
		$projectDao = new model_rdproject_project_rdproject ();
		$statusDao = $projectDao->statusDao;
		$searchArrProject = array (
			"groupId" => $_GET ['parentId']
			,"statusArr" => $statusDao->statusEtoK ( "locking" ) . "," . $statusDao->statusEtoK ( "execute" ) . "," .$statusDao->statusEtoK ( "end" ) . "," . $statusDao->statusEtoK ( "close" )
							. "," . $statusDao->statusEtoK ( "approval" ) . "," . $statusDao->statusEtoK ( "fightback" ) . "," . $statusDao->statusEtoK ( "wite" )
		);
		$this->ajaxGroupAndProject ( $searchArrGroup, $searchArrProject );
	}

	/**
	 * @desription �ҵ���Ŀ ��ȡ��һ����Ϸ�ҳ
	 * @param tags
	 * @date 2010-9-29 ����09:15:34
	 */
	function c_ajaxGroupByParentMine () {
		$searchArr = array ("parentId" => $_GET ['parentId'] );
		$projectDao = new model_rdproject_project_rdproject ();
		$statusDao = $projectDao->statusDao;
		$searchArrProject = array (
			"myPUser" => $_SESSION['USER_ID'],
			"statusArr" => $statusDao->statusEtoK ( "locking" ) . "," . $statusDao->statusEtoK ( "execute" ) . "," . $statusDao->statusEtoK ( "end" ) . "," . $statusDao->statusEtoK ( "close" )
		);
		$this->ajaxGroupByParent ( $searchArr ,$searchArrProject);
	}

	/**
	 * @desription �ҵ���Ŀ �����ϼ����id��ȡ����µ���Ŀ��ϸ���Ŀ�б�
	 * @param tags
	 * @date 2010-9-29 ����09:18:52
	 */
	function c_ajaxGroupAndProjectMine () {
		$searchArrGroup = array ("parentId" => $_GET ['parentId'] );
		$projectDao = new model_rdproject_project_rdproject ();
		$statusDao = $projectDao->statusDao;
		$searchArrProject = array (
			"myPUser" => $_SESSION['USER_ID'],
			"groupId" => $_GET ['parentId'],
			"statusArr" => $statusDao->statusEtoK ( "locking" ) . "," . $statusDao->statusEtoK ( "execute" ) . "," .
							$statusDao->statusEtoK ( "end" ) . "," . $statusDao->statusEtoK ( "close" )
		);
		$this->ajaxGroupAndProject ( $searchArrGroup, $searchArrProject );
	}



	/*********************������Ҫ�����α���õ��Ĺ��÷���******************************/

	/**
	 * @desription ����Ĭ����Ŀ��������б��һ��
	 * @date 2010-9-21 ����11:16:56
	 */
	function ajaxGroupByParent( $searchArr,$pjSeachArr=false ) {
		$service = $this->service;
		$projectDao = new model_rdproject_project_rdproject ();
		$statusDao = $projectDao->statusDao;
		if($pjSeachArr ){
			$searchArr['pjTree'] = $projectDao->rpGroupTreeSql_d ($pjSeachArr);
		}
		$service->getParam ( $_POST );
		$service->searchArr = $searchArr;
		$rows = $service->rgPage_d ();

		if (is_array ( $rows )) {
			$arr = array ();
			$arr ['data'] = $rows;
			$arr ['total'] = $service->count;
			$arr ['page'] = $service->page;
			//			echo "<pre>";
			//			print_r($arr);
			echo util_jsonUtil::encode ( $arr );
		} else {
			echo 0;
		}
	}

	/**
	 * @desription �����ϼ����id��ȡ����µ���Ŀ��ϸ���Ŀ�б�
	 * @param tags
	 * @date 2010-9-21 ����11:19:59
	 */
	function ajaxGroupAndProject($searchArrGroup, $searchArrProject) {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr = $searchArrGroup;
		$groupRows = $service->rgList_d ();

		$projectDao = new model_rdproject_project_rdproject ();
		$projectDao->searchArr = $searchArrProject;
		$projectRows = $projectDao->rpList_d ();
		$projectRows = model_common_util::yx_array_merge ( $projectRows, $groupRows );
		//		echo "<pre>";
		//		print_r($projectRows);
		if (is_array ( $projectRows )) {
			echo util_jsonUtil::encode ( $projectRows );
		} else {
			echo "0";
		}
	}

	/***************************************************************************/

	/**
	 * @desription ��ȡĳһ����Ŀ����µ���Ϸ�ҳ
	 */
	function c_ajaxPageGroupByParent() {
		$service = $this->service;
		$searchArr = $service->getParam ( $_POST );
		$searchArr ["parentId"] = $_GET ['parentId'];
		$service->searchArr = $searchArr;
		$rows = $service->page_d ();

		//����һ����g_Ϊǰ׺��id������������Ŀ�����,ע�������ǰ׺Ҫ�������ȡ��ϸ���Ŀ��oParentIdǰ׺һ��
		function createOIdFn($row) {
			$row ['oid'] = "g_" . $row ['id']; //��g-Ϊǰ׺����Ϊ���
			$row ['oParentId'] = "g_" . $row ['parentId'];
			$row ['icon'] = "group.gif"; //�������ͼ����ʽ
			return $row;
		}
		$rows = array_map ( "createOIdFn", $rows );
		$arr = array ();
		$arr ['data'] = $rows;
		$arr ['total'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
		//echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @desription �����ϼ����id��ȡ����µ���Ŀ��ϸ���Ŀ�б�
	 */
	function c_pageGroupAndProject() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$searchArr ["parentId"] = $_GET ['parentId'];
		$service->searchArr = $searchArr;
		$groupRows = $service->list_d ();

		$projectDao = new model_rdproject_project_rdproject ();
		$searchArr = array ("groupId" => $_GET ['parentId'] );
		$projectDao->searchArr = $searchArr;
		$projectRows = $projectDao->list_d ();
		$projectRows = model_common_util::yx_array_merge ( $projectRows, $groupRows );

		if (is_array ( $projectRows )) {
			//����һ����g_��p_Ϊǰ׺��id������������Ŀ�����
			function createOIdFn($row) {
				if (isset ( $row ['projectName'] )) {
					$row ['oid'] = "p_" . $row ['id']; //��p-Ϊǰ׺����Ϊ��Ŀ
					$row ['oParentId'] = "g_" . $row ['groupId'];
					$row ['icon'] = "project.gif"; //������Ŀͼ����ʽ
				} else {
					$row ['oid'] = "g_" . $row ['id']; //��g-Ϊǰ׺����Ϊ���
					$row ['oParentId'] = "g_" . $row ['parentId'];
					$row ['icon'] = "group.gif"; //�������ͼ����ʽ
				}
				return $row;
			}
			echo util_jsonUtil::encode ( array_map ( "createOIdFn", $projectRows ) );
		} else {
			echo "";
		}

	}

	/**
	 * @desription ������Ŀ��Ϸ�ҳ���������������ϼ���Ŀ
	 * ע�⣺�˷�������һ���Ի�ȡ��Ŀ��ϸ���Ŀ����ǰ̨����
	 */
	function c_pageGroupAndProjectAll() {
		$service = $this->service;
		$searchArr = $service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->pageAll_d ( $searchArr );

		//����һ����g_��p_Ϊǰ׺��id������������Ŀ�����
		function createOIdFn($row) {
			if (isset ( $row ['projectName'] )) {
				$row ['oid'] = "p_" . $row ['id']; //��p-Ϊǰ׺����Ϊ��Ŀ
				$row ['oParentId'] = "g_" . $row ['groupId'];
				$row ['icon'] = "project.gif"; //������Ŀͼ����ʽ
			} else {
				$row ['oid'] = "g_" . $row ['id']; //��g-Ϊǰ׺����Ϊ���
				$row ['oParentId'] = "g_" . $row ['parentId'];
				$row ['icon'] = "group.gif"; //�������ͼ����ʽ
			}
			return $row;
		}
		$rows = array_map ( "createOIdFn", $rows );
		$arr = array ();
		$arr ['data'] = $rows;
		$arr ['total'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
		//echo util_jsonUtil::encode ( $arr );
	//echo util_jsonUtil::encode ( $rows );
	}

}

?>
