<?php
header("Content-type: text/html; charset=gb2312");
/**
 * @author qian
 * @Date 2011��3��3�� 10:49:20
 * @version 1.0
 * @description:�����̻����Ʋ�
 */
class controller_projectmanagent_chance_chance extends controller_base_action {

	function __construct() {
		$this->objName = "chance";
		$this->objPath = "projectmanagent_chance";
		parent :: __construct();
	}

	/*********************************************************��ͨAction����**************************************************/

	/*
	 * ��ת�������̻�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * @description ��ת������ҳ��
	 * @date 2011-03-03 16:52
	 */
	function c_toAdd() {
		$this->showDatadicts(array (
			"customerType" => "KHLX"
		));
		$id = $_GET['id'];
		$this->showDatadicts(array (
			'role' => 'ROLE'
		));
		if ($id) {
			$this->permCheck($id, projectmanagent_clues_clues);
			$condiction = array (
				"id" => $id
			);
			$cluesDao = new model_projectmanagent_clues_clues();
			$rows = $cluesDao->get_d($id);
			$linkmanDao = new model_projectmanagent_clues_linkman();
			$linkmanRows = $linkmanDao->getLinkmanByCluesId_d($_GET['id']);
			$chanceLinkmanDao = new model_projectmanagent_chance_linkman();
			$this->assign('linkmanList', $chanceLinkmanDao->showEditList_d($linkmanRows));
			foreach ($rows as $key => $val) {
				$this->assign($key, $val);
			}
			//			$trackmanId = array ();
			//			foreach ($rows['trackmaninfo'] as $key => $val) {
			//				$trackmanId[$key] = $rows['trackmaninfo'][$key]['trackmanId'];
			//			}
			//
			//			$trackmanIds = implode(',', $trackmanId);
			//			$this->assign('trackmanId', $trackmanIds);
			$this->assign('trackman', $_SESSION['USERNAME']);
			$this->assign('trackmanId', $_SESSION['USER_ID']);
			$this->assign('cluesId', $id);
			$length = count($linkmanRows); //��ȡ��������ĳ���
			for ($i = 0; $i < $length; $i++) {
				$j = $i +1;
				$this->showDatadicts(array (
					'roleCode' . $j => 'ROLE'
				), $linkmanRows[$i]['roleCode']);
			}
			$this->assign('linkmanLeng', $length);
			$this->showDatadicts(array (
				'customerType' => 'KHLX'
			), $rows['customerType']);
			$this->display('add-clue');
		} else {
			$this->assign('prinvipalName', $_SESSION['USERNAME']);
			$this->assign('prinvipalId', $_SESSION['USER_ID']);
			$this->assign('prinvipalDept', $_SESSION['DEPT_NAME']);
		    $this->assign('prinvipalDeptId', $_SESSION['DEPT_ID']);

		    //��ȡ��˾����
	    	$branchDao = new model_deptuser_branch_branch();
	    	$companyInfo = $branchDao->getByCode($_SESSION['Company']);
	    	$this->assign('businessBelong',$_SESSION['Company']);
	    	$this->assign('businessBelongName',$companyInfo['NameCN']);

	    	$this->assign('formBelong',$_SESSION['COM_BRN_PT']);
	    	$this->assign('formBelongName',$_SESSION['COM_BRN_CN']);
	        //�����ݲ��������⴦��
	        if(dsjAreaId){
	        	$regionDao = new model_system_region_region();
	        	$rs = $regionDao->find(array('id' => dsjAreaId,'isStart' => '0'),null,'areaName,areaPrincipal,areaPrincipalId,areaSalesmanId');
	        	//��ǰ��¼��Ϊ�����ݲ�����������Ա�ģ�Ҫ�����⴦��
	        	if($rs && in_array($_SESSION['USER_ID'], explode(',', $rs['areaSalesmanId']))){
	        		$areaCode = dsjAreaId;
	        		$areaName = $rs['areaName'];
	        		$areaPrincipalId = $rs['areaPrincipalId'];
	        		$areaPrincipal = $rs['areaPrincipal'];
	        	}
	        }
	        $this->assign('areaCode', isset($areaCode) ? $areaCode : '');
	        $this->assign('areaName', isset($areaName) ? $areaName : '');
	        $this->assign('areaPrincipalId', isset($areaPrincipalId) ? $areaPrincipalId : '');
	        $this->assign('areaPrincipal', isset($areaPrincipal) ? $areaPrincipal : '');
	        
			$this->view('add',true);
		}
	}

	/**
	 * @description �̻��ı��淽��
	 * @date 2011-03-03 16:55
	 */
	function c_add() {
        $this->checkSubmit();
		$chanceInfo = $_POST[$this->objName];

        $authorizeInfo = $_POST['authorize'];
		$id = $this->service->add_d($authorizeInfo,$chanceInfo, true);

		//�ж��Ƿ�ֱ���ύ����
		if ($id && $_GET['act'] == "app") {
			succ_show('controller/projectmanagent/chance/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_sale_chance&formName=�̻�����');
		} else {
			if ($id) {
				msgGo('��ӳɹ���', '?model=projectmanagent_chance_chance&action=toMyChanceTab');
			}
		}

	}

	/**
	 * ����ת�̻��ı��淽��
	 */
	function c_chanceAdd($isAddInfo = false) {
		$chanceInfo = $_POST[$this->objName];

		$chanceCodeDao = new model_common_codeRule();
		$chanceInfo['chanceCode'] = $chanceCodeDao->changeCode("oa_sale_chance", $chanceInfo['customerId']);
		$id = $this->service->addChance_d($chanceInfo);

		//�ж��Ƿ�ֱ���ύ����
		if ($_GET['act'] == "app") {
			$phpurl = 'controller/projectmanagent/chance/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_sale_chance&formName=�̻�����';
			$phpurl = str_replace(" ", "", $phpurl);
			echo "<script>location.replace('" . $phpurl . "');</script>";
		}

		//		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		else
			if ($id) {
				msgRF('��ӳɹ���');
			}
	}
	/**
	 * @description �鿴/�༭ҳ��
	 * @author
	 */
	function c_init() {
		$service = $this->service;
		$returnObj = $this->objName;
		$id = $_GET['id'];
		$perm = isset ($_GET['perm']) ? $_GET['perm'] : null;

		$trackFlag = isset ($_GET['trackFlag']) ? $_GET['trackFlag'] : null;
		$rows = $this->service->get_d($_GET['id']);
		//�жϵ�ǰ�鿴�������Ƿ����̻��ĸ�����
		$trackArr = explode(",",$rows['trackmanId']);
	 if(in_array($_SESSION['USER_ID'],$trackArr)){
	 	$rows = $this->fieldFilterFrom($rows);
	 }
		//��Ⱦ�ӱ�
		if ($perm == 'view') {
			$rows['file'] = $this->service->getFilesByObjId($rows['id'], false);
		} else {
			$rows['file'] = $this->service->getFilesByObjId($rows['id'], true);
		}
		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}

		$boostinfo = $this->service->boostChanceStageInfo_d($_GET['id']);
		$this->assign("boostinfo", $boostinfo);
		$winRateinfo = $this->service->winRateInfo_d($_GET['id']);
		$this->assign("winRateInfo", $winRateinfo);

		if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
			$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
			$this->assign('chanceStage', $this->getDataNameByCode($rows['chanceStage']));
		 if($rows['winRate'] == '0'){
		 	$this->assign('winRate', "0%");
		 }else{
		 	$this->assign('winRate', $this->getDataNameByCode($rows['winRate']));
		 }
			$this->assign('chanceType', $this->getDataNameByCode($rows['chanceType']));
			$this->assign('chanceNature', $this->getDataNameByCode($rows['chanceNature']));
			//��Ʒ�б�
			$goodsList = $this->service->productListView($id);
			$this->assign('productList', $goodsList);
			//�豸Ӳ��
			$hardwareList = $this->service->hardwareListView($id);
			$this->assign('hardwareList', $hardwareList);
			$this->assign('module', $this->getDataNameByCode($rows['module']));
			$this->view('view');
		} else {

			//��ȡ��������Ϣ
			$trackman = new model_projectmanagent_chancetracker_chancetracker();
			$trackinfo = $trackman->getDetail_d($id);
			$trackId = array ();
			foreach ($trackinfo as $key => $val) {
				$trackId[$key] = $trackinfo[$key]['trackmanId'];
			}
			$trackId = implode(',', $trackId);
			$this->assign('trackmanId', $trackId);
			$this->showDatadicts(array (
				'customerType' => 'KHLX'
			), $rows['customerType']);
			$this->showDatadicts(array (
				'chanceStage' => 'SJJD'
			), $rows['chanceStage']);
			$this->showDatadicts(array (
				'chanceLevel' => 'SJDJ'
			), $rows['chanceLevel']);
			$this->showDatadicts(array (
				'chanceType' => 'SJLX'
			), $rows['chanceType']);
			if ($rows['chanceType'] == 'SJLX-XSXS') {
				$dataOrderNature = 'XSHTSX';
			} else
				if ($rows['chanceType'] == 'SJLX-FWXM') {
					$dataOrderNature = 'FWHTSX';
				} else
					if ($rows['chanceType'] == 'SJLX-ZL') {
						$dataOrderNature = 'ZLHTSX';
					} else
						if ($rows['chanceType'] == 'SJLX-YF') {
							$dataOrderNature = 'YFHTSX';
						}

			$this->showDatadicts(array (
				'role' => 'ROLE'
			));
			$this->showDatadicts(array (
				'orderNature' => $dataOrderNature
			), $rows['orderNature']);
			$this->display('edit');
		}
	}

	/**
	 * �̻�����
	 */
	function c_updateChance() {
		$obj = $this->service->get_d($_GET['id']);
		//�жϵ�ǰ�鿴�������Ƿ����̻��ĸ�����
		$trackArr = explode(",",$obj['trackmanId']);
		if(in_array($_SESSION['USER_ID'],$trackArr)){
			$obj = $this->fieldFilterFrom_Update($obj);
		}else{
			$obj['chanceMoneyL'] = "";
			$obj['winRateL'] = "";
			$obj['chanceStageL'] = "";
		}
		//����
		$obj['file'] = $this->service->getFilesByObjId($obj['id'], true);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
//echo "<pre>";print_r($obj);exit();
        $countryOpt = $provinceOpt = $cityOpt = '';
        if(isset($obj['Country']) && !empty($obj['Country'])){
            $countryOpt = '<option value="'.$obj['CountryId'].'">'.$obj['Country'].'</option>';
        }
        if(isset($obj['Province']) && !empty($obj['Province'])){
            $provinceOpt = '<option value="'.$obj['ProvinceId'].'">'.$obj['Province'].'</option>';
        }
        if(isset($obj['City']) && !empty($obj['City'])){
            $cityOpt = '<option value="'.$obj['CityId'].'">'.$obj['City'].'</option>';
        }

        $this->assign('countryOpt',$countryOpt);
        $this->assign('provinceOpt',$provinceOpt);
        $this->assign('cityOpt',$cityOpt);

		$this->assign('oldWinRate',$obj['winRate']);
		$this->assign('updateTime',date('Y-m-d'));
		//��Ʒ�б�
		$goodsList = $this->service->productListUpdate($obj['id']);
		$this->assign('productListA', $goodsList);
		//�豸Ӳ��
		$hardwareList = $this->service->hardwareListUpdate($obj['id']);
		$this->assign('hardwareListA', $hardwareList);

		$this->showDatadicts(array ('chanceType' => 'HTLX'), $obj['chanceType']);
		$this->showDatadicts(array ('module' => 'HTBK'), $obj['module']);
		$this->showDatadicts(array ('chanceNature' => $obj['chanceType']), $obj['chanceNature']);
		$this->showDatadicts(array ('winRate' => 'SJYL'), $obj['winRate']);
		$this->showDatadicts(array ('chanceStage' => 'SJJD'), $obj['chanceStage']);
		// $this->showDatadicts(array ('customerType' => 'KHLX'), $obj['customerType']);
		$this->showDatadicts(array ('signSubject' => 'QYZT'), $obj['signSubject']);
		//�����ݲ��������⴦��
		if(dsjAreaId){
			$regionDao = new model_system_region_region();
			$rs = $regionDao->find(array('id' => dsjAreaId,'isStart' => '0'),null,'areaName,areaPrincipal,areaPrincipalId,areaSalesmanId');
			//��ǰ��¼��Ϊ�����ݲ�����������Ա�ģ�Ҫ�����⴦��
			if($rs && in_array($_SESSION['USER_ID'], explode(',', $rs['areaSalesmanId']))){
				$areaCode = dsjAreaId;
				$areaName = $rs['areaName'];
				$areaPrincipalId = $rs['areaPrincipalId'];
				$areaPrincipal = $rs['areaPrincipal'];
			}
		}
		$this->assign('areaCode', isset($areaCode) ? $areaCode : $obj['areaCode']);
		$this->assign('areaName', isset($areaName) ? $areaName : $obj['areaName']);
		$this->assign('areaPrincipalId', isset($areaPrincipalId) ? $areaPrincipalId : $obj['areaPrincipalId']);
		$this->assign('areaPrincipal', isset($areaPrincipal) ? $areaPrincipal : $obj['areaPrincipal']);

        // ����ͻ�����������
        $customerTypeName = '';
        $sql = "select * from oa_system_datadict where parentCode = 'KHLX' and dataCode = '{$obj['customerType']}'";
        if($obj['customerType'] != ''){
            $result = $this->service->_db->getArray($sql);
            $customerTypeName = (isset($result[0]) && isset($result[0]['dataName']))? $result[0]['dataName'] : '';
        }
        $this->assign('customerTypeName', $customerTypeName);
		
		$this->view("updateChance");
	}

	/**
	 * �����̻�
	 */
	function c_updateChanceInfo() {
		$obj = $_POST[$this->objName];
		$oldlayout = $this->service->getUpdateRecord_d($obj['id']);
		$rs = $this->c_getUpdateInfo($obj['pcdHidden'],$obj['predictContractDate'],$obj['cmHidden'],$obj['chanceMoney'],$obj['updateTime']);
		$layout = $oldlayout[0]['updateRecord'].$rs;
		$rs = $this->c_getUpdateInfo($obj['oldWinRate']."%",$obj['winRate']."%",$obj['oldProgress'],$obj['progress'],$obj['updateTime']);
		$layout = $layout.$rs;
		$this->service->updateRecord_d($layout,$obj['id']);
		$flag = $this->service->updateChance_d($obj);
		if ($flag) {
			msg('���³ɹ���');
		}
	}

	/**
	 * �ƽ��̻�
	 */
	function c_boostChance() {
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts(array ('chanceStage' => 'SJJD'), $obj['chanceStage']);
		$this->showDatadicts(array ('winRate' => 'SJYL'), $obj['winRate']);
        $this->assign('customerType', $this->getDataNameByCode($obj['customerType']));
		$this->assign("chanceStageA", $obj['chanceStage']);
		$this->assign("winRateA", $obj['winRate']);
		$this->assign("updateTime",date('Y-m-d'));

		//��ȡ��չ�ֶ�1��ֵ
		$dataDao = new model_system_datadict_datadict ();
		$winRateDone = $dataDao->find(array("dataCode"=>$obj['winRate']),null,"expand1");
        $chanceStageDone = $dataDao->find(array("dataCode"=>$obj['chanceStage']),null,"expand1");

        $this->assign("winRateDone", $winRateDone['expand1']);
        $this->assign("chanceStageDone", $chanceStageDone['expand1']);
		$this->view("boostChance");
	}
	/**
	 * �����ƽ��̻���Ϣ
	 */
	function c_updateBoostChance() {
		$info = $_POST[$this->objName];
		$oldchanceStage = $this->getDataNameByCode($info['oldchanceStage']);
		$chanceStage = $this->getDataNameByCode($info['chanceStage']);
		$layout = $this->c_getUpdateInfo($oldchanceStage,$chanceStage,$info['oldwinRate']."%",$info['winRate']."%",$info['updateTime']);
		$oldlayout = $this->service->getUpdateRecord_d($info['chanceId']);
		$layout=$oldlayout[0]['updateRecord'].$layout;
		$this->service->updateRecord_d($layout,$info['chanceId']);
		$flag = $this->service->boostChance_d($info);
		if ($flag) {
			msg('���³ɹ���');
		}
	}
	/**
	 * ajax �ж��̻��Ƿ��в�Ʒ
	 */
   function c_ajaxFindChanceGoods(){
     	try {
     		$goods = $this->service->getChanceGoods_d( $_POST ['chanceId']);
			echo $goods;
		} catch ( Exception $e ) {
			echo 0;
		}
   }
	/**
	 * ���¾���������Ϣ
	 */
	function c_toCompetitor() {
		$this->assign("chanceId", $_GET['chanceId']);
		$this->view("toCompetitor");
	}
	/**
	 * ���¾�������
	 */
	function c_competitorAdd() {
		$flag = $this->service->competitorAdd_d($_POST[$this->objName]);
		if ($flag) {
			msg('����ɹ���');
		}
	}
	/**
	 * ����������ϸ��Ϣ
	 */
	function c_toProductinfo() {
		$this->assign("chanceId", $_GET['chanceId']);
		$this->view("toProductinfo");
	}
	//������ϸ������Ϣ
	function c_prodcutInfoAdd() {
		$flag = $this->service->prodcutInfoAdd_d($_POST[$this->objName]);
		if ($flag) {
			msg('����ɹ���');
		}
	}
	/**
	 * �̻��׶��ƽ���Ϣ
	 */
	function c_boostChanceStageInfo() {
		$info = $this->service->boostChanceStageInfo_d($_GET['id']);
		$this->assign("boostinfo", $info);
		$this->view("boostChanceStageInfo");
	}
	/**
	 *�̻�Ӯ���ƽ���Ϣ
	 */
	function c_winRateInfo() {
		$info = $this->service->winRateInfo_d($_GET['id']);
		$this->assign("winRateInfo", $info);
		$this->view("winRateInfo");
	}
	/**
	 * ��ʱ�̻�����
	 */
	function c_chanceInfoList() {
		$this->assign('timingDT' , date("Y-m-d"));
		$this->view("chanceInfoList");
	}

	/**
	 * �̻��鿴Tabҳ��
	 */
	function c_toViewTab() {
		//		$this->permCheck();
		$this->assign('id', $_GET['id']);
		$findBorrow = $this->service->findBorrow($_GET['id']);
		$this->display('view-tab');
	}

	/**
	 * �鿴Tab--���ټ�¼
	 */
	function c_toRecord() {
		$this->assign('chanceId', $_GET['id']);
		$this->display('trackrecord');
	}

	/**
	 * �鿴Tab���ر���Ϣ
	 */
	function c_toChanceInfo() {
		$rows = $this->service->get_d($_GET['id']);
		if ($rows['status'] == '3') {
			foreach ($rows as $key => $val) {
				$this->assign($key, $val);
			}
			$this->display('closeInfo');

		} else
			if ($rows['status'] == '6') {
				foreach ($rows as $key => $val) {
					$this->assign($key, $val);
				}
				$this->display('pauseinfo');
			} else {
				$this->display('none');
			}
	}
	/**
	 * @description ��ת�������Ĳ鿴ҳ��
	 *
	 * @date 2011-03-07
	 */
	function c_toView() {
		$service = $this->service;
		$returnObj = $this->objName;
		$id = $_GET['id'];
		//�����̻��Ż���̻�������
		$row = $service->get_d($id);
		$rows = $this->service->initView($row);
		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
		$this->assign('chanceStage', $this->getDataNameByCode($rows['chanceStage']));
		$this->assign('chanceLevel', $this->getDataNameByCode($rows['chanceLevel']));
		$this->assign('module', $this->getDataNameByCode($rows['module']));

		$this->display('view-app');
	}

	/**
	 * �ҵ��̻�TAb
	 */
	function c_toMyChanceTab() {
		$this->display('list-mychanceTab');
	}

	/**
	 * �Ҹ�����̻�
	 */
	function c_toMyChanceList() {
		$this->display('list-mychance');
	}
	/**
	 * �����̻����
	 */
	function c_chanceByuser(){
		$this->assign('userId', $_GET['USER_ID']);
		$this->display('chancebyuserlist');
	}

	/**
	 * �Ҹ��ٵ��̻�
	 */
	function c_toMyTractList() {
		$this->display('list-mytrack');
	}
	/**
	 * ����ɾ������
	 */
	function c_deletesInfo() {
		$deleteId = isset ($_GET['id']) ? $_GET['id'] : exit;
		$delete = $this->service->deletesInfo_d($deleteId);
		if ($delete) {
			msg('ɾ���ɹ�');
		}
	}

	/**
	 * @description �ر��̻��ĵ��÷���
	 */
	function c_toClose() {
		$this->permCheck();
		$id = $_GET['id'];
		$rows = $this->service->get_d($id);
		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}

		$customerType = $this->getDataNameByCode($rows['customerType']);
		$this->assign('customerType', $customerType);
		$this->display('close');
	}

	/**
	 * @description ��ͣ�̻��ĵ��÷���
	 * @date 2011-03-06
	 */
	function c_toPause() {
		$this->permCheck();
		$id = $_GET['id'];
		$rows = $this->service->get_d($id);
		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}

		$customerType = $this->getDataNameByCode($rows['customerType']);
		$this->assign('customerType', $customerType);
		$this->display('pause');
	}

	/**
	 * @description �ָ��̻��ĵ��÷���
	 * @date 2011-03-06
	 */
	function c_toRecover() {
		$this->permCheck();
		$id = $_GET['id'];
		$rows = $this->service->get_d($id);
		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}

		$customerType = $this->getDataNameByCode($rows['customerType']);
		$this->assign('customerType', $customerType);
		$this->display('recover');
	}
	/**
	 * @description �ر��̻��ı��淽��
	 * @date 2011-03-06 10:13
	 */
	function c_closeChance() {
		$service = $this->service;
		$rows = $_POST[$this->objName];
		$flag = $service->closeChance_d($rows);
		if ($flag) {
			msg('�رճɹ�');
		} else {
			msg('�ر�ʧ��');
		}
	}

	/**
	 * @description ��ͣ�̻��ı��淽��
	 * @date 2011-03-06 10:13
	 */
	function c_pauseChance() {
		$service = $this->service;
		$rows = $_POST;
		$flag = $service->pauseChance_d($rows);
		if ($flag) {
			msg('��ͣ�ɹ�');
		} else {
			msg('��ͣʧ��');
		}
	}

	/**
	 * @description �ָ��̻��ı��淽��
	 * @date 2011-03-06 10:13
	 */
	function c_recoverChance() {
		$service = $this->service;
		$rows = $_POST[$this->objName];
		$flag = $service->recoverChance_d($rows);
		if ($flag) {
			msg('�ָ��ɹ�');
		} else {
			msg('�ָ�ʧ��');
		}
	}
	/**
	 * ָ��������
	 */
	function c_toTrackman() {
		$this->permCheck();
		$row = $this->service->get_d($_GET['id']);

		foreach ($row as $key => $val) {
			$this->assign($key, $val);
		}
		$this->display('assigntrack');
	}
	/**
	 *  �ƽ��̻�
	 */
	function c_transferChance() {
		$this->permCheck();
		$row = $this->service->get_d($_GET['id']);

		foreach ($row as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('trackUser', $_SESSION['USERNAME']);
		$this->assign('trackUserId', $_SESSION['USER_ID']);
		$this->display('transfer');
	}

	/**
	 * @description ��ת���̻������Ĳ鿴�б�ҳ
	 * @date 2011-03-06 10:14
	 */
	function c_toChanceApprovalTab() {
		$this->display('tab-app');
	}

	/**
	 * @description �̻��Ĵ������б�
	 * @date 2011-03-06 10:16
	 */
	function c_toUnApprovalList() {
		$this->display('list-unapp');
	}

	/**
	 * @description �̻����������б�
	 */
	function c_toApprovaledList() {
		$this->view('list-apped');
	}

	/**
	 * ������Դ
	 */
	function c_toAppSupport() {
		$this->assign('skey', $this->md5Row($_GET['objId']));
		$this->assign('objId', $_GET['objId']);
		$this->assign('objCode', $_GET['objCode']);
		$this->assign('objName', $_GET['objName']);
		$this->assign('objType', 'CHANCE');
		$this->display('toaddsupport');
	}
	/**
	 * ����֧��-����֧��
	 */
	function c_toSupport() {
		$this->display('support');
	}
	/**
	 * ������Դ---������
	 */
	function c_toBorrow() {
		$this->display('resource-borrow');
	}

	/**
	* ��������ָ�������Ÿ�����
	*/
	function c_deptTrackman() {
		$this->permCheck();
		$rowA = $this->service->get_d($_GET['id']);
		$trackmanDao = new model_projectmanagent_chancetracker_chancetracker();
		$conditions = array (
			'chanceId' => $_GET['id']
		);
		$rowA['trackmaninfo'] = $trackmanDao->findAll($conditions);
		$rows = $this->service->deptTrackInfo($rowA);
		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}

		$deptman = implode(',', $rows['deptman']);
		$deptmanId = implode(',', $rows['deptmanId']);
		$deptmanOther = implode(',', $rows['deptManOther']);
		$deptmanOtherId = implode(',', $rows['deptManOtherId']);

		$this->assign('deptTrackman', $deptman);
		$this->assign('deptTrackmanId', $deptmanId);
		$this->assign('deptTrackmanOther', $deptmanOther);
		$this->assign('deptTrackmanOtherId', $deptmanOtherId);
		$this->assign('trackUser', $_SESSION['USERNAME']);
		$this->assign('trackUserId', $_SESSION['USER_ID']);
		$this->display('depttrackman');
	}
	/**
	 * ��������ָ�������Ÿ����˷���
	 */
	function c_deptTrack() {
		$id = $this->service->deptTrack_d($_POST[$this->objName], true);
		if ($id) {
			msg('�޸ĳɹ���');
		}
	}
	/*
	 * ��ת��������̻�ҳ��
	 */
	function c_finish() {

		$this->view("finish");
	}

	/*********************************************************��������ͨAction����**************************************************/

	/**
	 * �޸ĸ�����
	 */
	function c_editTrack() {
		$authorizeInfo = $_POST['authorize'];
		$id = $this->service->TrackmanEdit_d($authorizeInfo,$_POST[$this->objName], true);
		if ($id) {
			msg('�޸ĳɹ���');
		}
	}
	/**
	 * �ƽ��̻�����
	 */
	function c_transfer() {
		$id = $this->service->transfer_d($_POST[$this->objName], true);
		if ($id) {
			msg('�޸ĳɹ���');
		}
	}
	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST[$this->objName];
		if ($this->service->edit_d($object, $isEditInfo)) {
			msgRF('�༭�ɹ���');
		}
	}
	/*********************************************************Ajax��JSON����**************************************************/
    /**
	 *  �̻���������ͳ��Json
	 */
	function c_chanceEquListJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->asc = false;
        $service->sort = "";
        $service->groupBy = "e.productId,c.winRate";
//        $rateDao->searchArr['relDocIdArr'] = $orderIdStr;

        $rows = $service->pageBySqlId("select_chanceEqu");
       $i="0";
       foreach($rows as $k=>$v){
       	  $i++;
          $rows[$k]['id'] = $i;
       }
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
	 //*  �̻���������ͳ��Json---�����ӱ�����
	function c_chanceEquInfoJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->asc = false;
        $service->sort = "";

        $service->searchArr['productId'] = $_POST['productId'];
        $service->searchArr['winRate'] = $_POST['winRate'];
        $rows = $service->pageBySqlId("select_chanceEqu_info");

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
	 *  �̻����۲�Ʒͳ��Json
	 */
	function c_chanceProductJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->asc = false;
        $service->sort = "";
        $service->groupBy = "p.goodsName,c.winRate";
//        $rateDao->searchArr['relDocIdArr'] = $orderIdStr;

        $rows = $service->pageBySqlId("select_chanceProduct");
       $i="0";
       foreach($rows as $k=>$v){
       	  $i++;
          $rows[$k]['id'] = $i;
       }
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
	 //*  �̻����۲�Ʒͳ��Json---�����ӱ�����
	function c_chanceProInfoJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->asc = false;
        $service->sort = "";

        $service->searchArr['conProductIdC'] = $_POST['conProductId'];
        $service->searchArr['winRate'] = $_POST['winRate'];
        $rows = $service->pageBySqlId("select_chanceProduct_info");

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
	 *  �̻������Ʒͳ��Json
	 */
	function c_serviceProductJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->asc = false;
        $service->sort = "";
//        $rateDao->searchArr['relDocIdArr'] = $orderIdStr;
        //���ҷ�����Ĳ�Ʒ
        $rows = $service->pageBySqlId("select_serviceProduct");

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
	 *  �̻����۲�Ʒͳ��Json
	 */
	function c_chanceGoodsJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->asc = false;
        $service->sort = "";
        $service->groupBy = "p.goodsName";
//        $rateDao->searchArr['relDocIdArr'] = $orderIdStr;

        $rows = $service->pageBySqlId("select_chanceGoods");
       $i="0";
       foreach($rows as $k=>$v){
       	  $i++;
          $rows[$k]['id'] = $i;
       }
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
	 //*  �̻����۲�Ʒͳ��Json---�����ӱ�����
	function c_chanceGoodsInfoJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->asc = false;
        $service->sort = "";
        $service->groupBy = "c.winRate,c.chanceStage,p.goodsName";

        $service->searchArr['conProductIdC'] = $_POST['conProductId'];
//      echo $_POST['status'];
//       if(!empty($_POST['status'])){
//       	 $service->searchArr['status'] = $_POST['status'];
//       }
//      print_R($service->searchArr);
        $rows = $service->pageBySqlId("select_chanceGoods_info");

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
	 * �̻���Ϣ�б�
	 */
	function c_chanceGridJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
//        $service->sort = "c.newUpdateDate";
       //������Ȩ������
		$limit = $this->initLimit();
		if ($limit == true) {
           $rows = $service->page_d ();
           //ͳ�ƽ��
		   $rows = $service->getRowsallMoney_d($rows, "select_list");
		}
		//��ȡ�б�ע��Ϣ�ĺ�ͬid
	    $remarkIsArr = $this->service->getRemarkIs();
	    foreach($rows as $key => $val){
			if(in_array($val['id'],$remarkIsArr)){
	                $rows[$key]['flag'] = "1";
			}
		}
		//$service->asc = false;

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
	 * �̻���Ϣ�б�(��ʷ��Ϣ)
	 */
	function c_chanceListJson() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
       //������Ȩ������
		$limit = $this->initLimit();
		$service->asc = true;

		if ($limit == true) {
          $rows = $service->pageBySqlId("select_chancelist");
		}
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * @description �Ҹ�����̻�
	 */
	function c_pageJsonMyChance() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$service->asc = true;
		$service->searchArr['prinvipalId'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId("select_default");
		//��ȡ�б�ע��Ϣ�ĺ�ͬid
	    $remarkIsArr = $this->service->getRemarkIs();

	    $idArr = array();//ids
	    foreach($rows as $key => $val){
			$time = $rows[$key]['changeTime'];
			if(in_array($val['id'],$remarkIsArr)){
	                $rows[$key]['flag'] = "1";
			}
			array_push($idArr,$val['id']);

		}
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * �̻��б���ʾ���¼�¼��ʽ
	 */
	function c_getUpdateInfo($data1=null,$data2=null,$data3=0,$data4=null,$updateTime=null){
		$layout = "";
		if($data1 != $data2){
			$layout .= "(".$updateTime.")".$data1 ." => ". $data2." || ";
		}
		if($data3 != $data4){
			$layout .= "(".$updateTime.")".$data3 ." => ". $data4." || ";
		}
		return $layout;
	}
	/**
	 * �Ҹ��ٵ��̻�
	 */
	function c_pageJsonMyTrack() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$service->asc = true;
		$service->searchArr['trackmanIdForS'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId("select_mychance");
		//��ȡ�б�ע��Ϣ�ĺ�ͬid
	    $remarkIsArr = $this->service->getRemarkIs();
	    foreach($rows as $key => $val){
			if(in_array($val['id'],$remarkIsArr)){
	                $rows[$key]['flag'] = "1";
			}
		}
		//�����ֶι���
		$rows = $this->fieldFilter($rows);
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}


	 /**
	  * �̻��б� �����ֶι���
	  */
    function fieldFilter($rows) {
		foreach ($rows as $key => $val) {
			//�жϵ�ǰ�鿴�������Ƿ����̻��ĸ�����
			$trackArr = explode(",",$val['trackmanId']);
		 if(in_array($_SESSION['USER_ID'],$trackArr)){
		 	//�ж�Ȩ�޵�
			$limitFilter = $this->service->limitFilter_d($val['id'],$_SESSION['USER_ID']);
			$limitArr = explode(",",$limitFilter['limitInfo']);
			if(!in_array("chanceMoney",$limitArr)){
				$rows[$key]['chanceMoney']="******";
			}
			if(!in_array("winRate",$limitArr)){
				$rows[$key]['winRate']="******";
			}
			if(!in_array("chanceStage",$limitArr)){
				$rows[$key]['chanceStage']="******";
			}
		 }
		}
		return $rows;
	}
	 /**
	  * �̻��б� �����ֶι���(��)
	  */
    function fieldFilterFrom($rows) {
		//�ж�Ȩ�޵�
		$limitFilter = $this->service->limitFilter_d($rows['id'],$_SESSION['USER_ID']);
		$limitArr = explode(",",$limitFilter['limitInfo']);
		if(!in_array("chanceMoney",$limitArr)){
			$rows['chanceMoney']="******";
		}
		if(!in_array("winRate",$limitArr)){
			$rows['winRate']="******";
		}
		if(!in_array("chanceStage",$limitArr)){
			$rows['chanceStage']="******";
		}

		return $rows;
	}
	function fieldFilterFrom_Update($rows) {
		//�ж�Ȩ�޵�
		$limitFilter = $this->service->limitFilter_d($rows['id'],$_SESSION['USER_ID']);
		$limitArr = explode(",",$limitFilter['limitInfo']);
		if(!in_array("chanceMoney",$limitArr)){
			$rows['chanceMoneyL']="******";
		}else{
			$rows['chanceMoneyL']="";
		}
		if(!in_array("winRate",$limitArr)){
			$rows['winRateL']="******";
		}else{
			$rows['winRateL']="";
		}
		if(!in_array("chanceStage",$limitArr)){
			$rows['chanceStageL']="******";
		}else{
			$rows['chanceStageL']="";
		}

		return $rows;
	}
	/**
	 * @description �̻���������JSON����
	 */
	function c_unApprovalJson() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['applyId'] = $_SESSION['USER_ID'];
		$service->searchArr['Flag'] = 0;
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId('sql_unapproval');
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * @description ���������̻���JSON����
	 */
	function c_approvaledJson() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['applyId'] = $_SESSION['USER_ID'];
		$service->searchArr['Flag'] = 1;
		$service->searchArr['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$service->groupBy = 'c.id';
		$rows = $service->pageBySqlId('sql_approvaled');
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * @ ajax�ж���
	 *
	 */
	function c_ajaxChanceCode() {
		$service = $this->service;
		$projectName = isset ($_GET['chanceCode']) ? $_GET['chanceCode'] : false;

		$searchArr = array (
			"ajaxChanceCode" => $projectName
		);

		$isRepeat = $service->isRepeat($searchArr, "");

		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/**
	* �ӱ�ѡ�����ϴ�������
	*/
	function c_ajaxConfig() {
		$configInfo = $this->service->c_configuration($_GET['id'], $_GET['Num'], $_GET['trId']);
		echo $configInfo[0];
	}


	/**
	 * Ȩ������
	 * Ȩ�޷��ؽ������:
	 * �������Ȩ�ޣ�����true
	 * �����Ȩ��,����false
	 */
	function initLimit() {
		$service = $this->service;
		//Ȩ����������
		$limitConfigArr = array (
			'areaLimit' => 'c.areaCode',
			'deptLimit' => 'c.prinvipalDeptId',
			'customerTypeLimit' => 'c.customerType'
		);
		//Ȩ������
		$limitArr = array ();
		//Ȩ��ϵͳ
		if (isset ($this->service->this_limit['�̻���������']) && !empty ($this->service->this_limit['�̻���������']))
			$limitArr['areaLimit'] = $this->service->this_limit['�̻���������'];
		if (isset ($this->service->this_limit['�̻�����Ȩ��']) && !empty ($this->service->this_limit['�̻�����Ȩ��']))
			$limitArr['deptLimit'] = $this->service->this_limit['�̻�����Ȩ��'];
		if (isset ($this->service->this_limit['�̻��ͻ�����']) && !empty ($this->service->this_limit['�̻��ͻ�����']))
			$limitArr['customerTypeLimit'] = $this->service->this_limit['�̻��ͻ�����'];
		if ( strstr($limitArr['areaLimit'], ';;') || strstr($limitArr['deptLimit'], ';;') || strstr($limitArr['customerTypeLimit'], ';;')) {
			return true;
		} else {
			//�������˻�ȡ�������
			$regionDao = new model_system_region_region();
			$areaPri = $regionDao->getUserAreaId($_SESSION['USER_ID'], 0);
			if (!empty ($areaPri)) {
				//����Ȩ�޺ϲ�
				$limitArr['areaLimit'] = implode(array_filter(array (
					$limitArr['areaLimit'],
					$areaPri
				)), ',');
			}
                        //���۸����˶�ȡ��Ӧʡ�ݺͿͻ�����
                        $saleArea= new model_system_saleperson_saleperson();
                        $saleAreaInfo=$saleArea->getSaleArea($_SESSION['USER_ID']);
                        if(!empty($saleAreaInfo)){
                            $limitArr['saleAreaInfo']=$saleAreaInfo;
                        }
			//			print_r($limitArr);
			if (empty ($limitArr)) {
				return false;
			} else {
				//���û��Ȩ��
				$i = 0;
				$sqlStr = "sql:and ( ";
                                //�������۸�����
                                if(!empty($limitArr['saleAreaInfo'])){
                                    $saleAreaStr="";
                                    foreach($saleAreaInfo as $sval){
                                        $saleTemp="";
                                        //�ͻ�����
                                        $saleTemp.=" c.customerType  in ('".  str_replace(',', "','", $sval['customerType'])."') ";
                                        //ʡ��
                                        if($sval['provinceId']!='0'){//ȫ�����˵�
                                            $saleTemp.="and c.ProvinceId ='".$sval['provinceId']."'  ";
                                        }
                                        $saleAreaStr.="or ( ".$saleTemp." ) ";
                                    }
                                    if(!empty($saleAreaStr)){
                                        $sqlStr.=trim($saleAreaStr,'or');
                                        $i++;//���
                                    }
                                    unset($limitArr['saleAreaInfo']);//����
                                }
				foreach ($limitArr as $key => $val) {
					$arr = explode(',', $val);
					if (is_array($arr)) {
						$val = "";
						foreach ($arr as $v) {
							$val .= "'" . $v . "',";
						}
						$val = substr($val, 0, -1);
					}
					if ($i == 0) {
						$sqlStr .= $limitConfigArr[$key] . " in (" . $val . ")";
					} else {
						$sqlStr .= " or " . $limitConfigArr[$key] . " in (" . $val . ")";
					}
					$i++;
				}
				$sqlStr .= ")";
			   if(empty($i)){
			   	   $sqlStr = "";
			   }

				$service->searchArr['mySearchCondition'] = $sqlStr;
				return true;
			}
		}
	}

	/*********************************************************������Ajax��JSON����**************************************************/

	/**********************************************************************/
	/**
	 * ����������ת����
	 * by maizp
	 */

	function c_deptChance() {
		$this->display('deptlist');
	}

	/******************start��Ϣ�б���**************************/

	function c_exportExcel() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(600);

		$statusArr = array ('0' => '������', '3' => '�ر�', '4' => '�����ɺ�ͬ', '5' => '������', '6' => '��ͣ' );
        $chanceTypeArr = array('SJLX01' => '������','SJLX01'=> '������');
        $isTurnArr = array('0' => '-','1'=>'��');
		$colIdStr = $_GET ['colId'];
		$colNameStr = $_GET ['colName'];
		$status = $_GET ['status'];
		$chanceType = $_GET ['chanceType'];

		$chanceLevel = $_GET ['chanceLevel'];
		$winRate = $_GET ['winRate'];
		$chanceStage = $_GET ['chanceStage'];
		//��������
		$searchArr['status'] = $status;
		$searchArr ['chanceType'] = $chanceType;
		$searchArr ['chanceLevel'] = $chanceLevel;
		$searchArr ['winRate'] = $winRate;
		$searchArr ['chanceStage'] = $chanceStage;
		$searchConditionKey = $_GET['searchConditionKey']; //��ͨ������Key
		$searchConditionVal = $_GET['searchConditionVal']; //��ͨ������Val
		$searchArr[$searchConditionKey] = $searchConditionVal;
		foreach ($searchArr as $key => $val) {
			if ($searchArr[$key] === null || $searchArr[$key] === '' || $searchArr[$key] == 'undefined') {
				unset ($searchArr[$key]);
			}
		}

		$limit = $this->initLimit();
          $this->service->sort = "c.newUpdateDate";
          if(!empty($this->service->searchArr)){
          	  $this->service->searchArr = array_merge($this->service->searchArr,$searchArr);
          }else{
              $this->service->searchArr = $searchArr;
          }
        if ($limit == true) {
		  $rows = $this->service->listBySqlId('select_default');
        }

		$goodsNum = 0;
		foreach ($rows as $index => $row) {
				$goodsinfo = $this->service->getChanceGoodsById($rows[$index]['id']);
				//ȡ��Ʒ���������ֵ(��û�в�Ʒ��ʱ��,count($goodsinfo)Ҳ��Ϊ1,��ʱ�޽�,����������ж�)
				if(count($goodsinfo)!=$goodsNum+1&&count($goodsinfo)>$goodsNum){
					$goodsNum = count($goodsinfo);
				}
				//�����Ʒ����
				foreach($goodsinfo as $key => $val){
					$rows[$index]['goodsName'.$key]=$val['goodsName'];
					$rows[$index]['money'.$key]=$val['money'];
				}
			foreach ($row as $key => $val) {

				if ($key == 'status') {
					$rows[$index][$key] = $statusArr[$val];
				}
				if ($key == 'isTuen') {
					$rows[$index][$key] = $isTurnArr[$val];
				}
				if($rows[$index]['predictContractDate'] == '0000-00-00'){
					$rows[$index]['predictContractDate'] = "";
				}
				if($rows[$index]['predictExeDate'] == '0000-00-00'){
					$rows[$index]['predictExeDate'] = "";
				}
				if($rows[$index]['newUpdateDate'] == '0000-00-00'){
					$rows[$index]['newUpdateDate'] = "";
				}
			}
		}
		//�������м����Ʒ�������ֵ��,
	   for($i=0;$i<$goodsNum;$i++){
					$colIdStr.=",goodsName$i,money$i";
					$colNameStr.=",��Ʒ����,���";
		}
		//��ͷId����
		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		//��ͷName����
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		//��ͷ����
		$colArr = array_combine($colIdArr, $colNameArr);

		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}
		foreach ($dataArr as $key => $val) {
			$dataArr[$key]['customerType'] = $this->getDataNameByCode($val['customerType']);
			$dataArr[$key]['chanceType']=$this->getDataNameByCode($val['chanceType']);
			$dataArr[$key]['chanceStage']=$this->getDataNameByCode($val['chanceStage']);

//			$dataArr[$key]['winRate']=$this->getDataNameByCode($val['winRate']);
		};
        if(empty($dataArr)){
        	echo "������";
        }else{
        	return model_contract_common_contExcelUtil :: exportChanceExcelUtil($colArr, $dataArr);
        }

	}


	/**
	 * ��ʷ�̻�����
	 */
	function c_historyChanceExcel(){
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(600);

		$statusArr = array ('0' => '������', '3' => '�ر�', '4' => '�����ɺ�ͬ', '5' => '������', '6' => '��ͣ' );
        $chanceTypeArr = array('SJLX01' => '������','SJLX01'=> '������');
		$colIdStr = $_GET ['colId'];
		$colNameStr = $_GET ['colName'];
		$status = $_GET ['status'];
		$chanceType = $_GET ['chanceType'];

		$chanceLevel = $_GET ['chanceLevel'];
		$winRate = $_GET ['winRate'];
		$chanceStage = $_GET ['chanceStage'];
		$timingDate = $_GET['timingDate'];
		//��������
		$searchArr['status'] = $status;
		$searchArr ['chanceType'] = $chanceType;
		$searchArr ['chanceLevel'] = $chanceLevel;
		$searchArr ['winRate'] = $winRate;
		$searchArr ['chanceStage'] = $chanceStage;
		$searchArr ['timingDate'] = $timingDate;
		$searchConditionKey = $_GET['searchConditionKey']; //��ͨ������Key
		$searchConditionVal = $_GET['searchConditionVal']; //��ͨ������Val
		$searchArr[$searchConditionKey] = $searchConditionVal;
		foreach ($searchArr as $key => $val) {
			if ($searchArr[$key] === null || $searchArr[$key] === '' || $searchArr[$key] == 'undefined') {
				unset ($searchArr[$key]);
			}
		}
		$limit = $this->initLimit();
          $this->service->sort = "c.newUpdateDate";
          if(!empty($this->service->searchArr)){
          	  $this->service->searchArr = array_merge($this->service->searchArr,$searchArr);
          }else{
              $this->service->searchArr = $searchArr;
          }
		$rows = $this->service->listBySqlId('select_chancelist');

		$goodsNum = 0;
		foreach ($rows as $index => $row) {
				$goodsinfo = $this->service->getHistoryChanceGoodsById($rows[$index]['oldId'],$timingDate);
				//ȡ��Ʒ���������ֵ(��û�в�Ʒ��ʱ��,count($goodsinfo)Ҳ��Ϊ1,��ʱ�޽�,����������ж�)
				if(count($goodsinfo)!=$goodsNum+1&&count($goodsinfo)>$goodsNum){
					$goodsNum = count($goodsinfo);
				}
				//�����Ʒ����
				foreach($goodsinfo as $key => $val){
					$rows[$index]['goodsName'.$key]=$val['goodsName'];
					$rows[$index]['money'.$key]=$val['money'];
				}
			foreach ($row as $key => $val) {

				if ($key == 'status') {
					$rows[$index][$key] = $statusArr[$val];
				}
				if($rows[$index]['predictContractDate'] == '0000-00-00'){
					$rows[$index]['predictContractDate'] = "";
				}
				if($rows[$index]['predictExeDate'] == '0000-00-00'){
					$rows[$index]['predictExeDate'] = "";
				}
				if($rows[$index]['newUpdateDate'] == '0000-00-00'){
					$rows[$index]['newUpdateDate'] = "";
				}
			}
		}
		//�������м����Ʒ�������ֵ��,
	   for($i=0;$i<$goodsNum;$i++){
					$colIdStr.=",goodsName$i,money$i";
					$colNameStr.=",��Ʒ����,���";
		}
		//��ͷId����
		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		//��ͷName����
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		//��ͷ����
		$colArr = array_combine($colIdArr, $colNameArr);

		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}
		foreach ($dataArr as $key => $val) {
			$dataArr[$key]['customerType'] = $this->getDataNameByCode($val['customerType']);
			$dataArr[$key]['chanceType']=$this->getDataNameByCode($val['chanceType']);
			$dataArr[$key]['chanceStage']=$this->getDataNameByCode($val['chanceStage']);

//			$dataArr[$key]['winRate']=$this->getDataNameByCode($val['winRate']);
		};

		if(empty($dataArr)){
        	echo "������";
        }else{
        	return model_contract_common_contExcelUtil :: exportChanceExcelUtil($colArr, $dataArr);
        }

	}
	/******************start��Ϣ�б���************end**************/

	/**
	 * ѡ��Ʒ
	 */
	function c_chanceProduct() {
		$this->assign('productLen',$_GET['productLen']);
		$this->assign('chanceId', $_GET['chanceId']);
		$this->view('choose-list');
	}

	//����ѡ��Ĳ�Ʒ
	function c_toSetProductInfo() {
		$goodsIds = $_POST['goodsIds'];
		$chanceId = $_POST['chanceId'];
		$rows = $_POST['rows'];
		$productLen = $_POST['productLen'];
		//�����Ʒ
		$list = $this->service->setProduct_d($goodsIds, $chanceId,$rows,$productLen);
		//    $list = util_jsonUtil :: encode($list);
		echo $list;
	}


	/**
	 * ѡ�豸 Ӳ��
	 */
	function c_chanceHardware() {
		$this->assign('chanceId', $_GET['chanceId']);
		$this->view('choose-hardware');
	}


	//����ѡ����豸Ӳ��
	function c_toSetHardwareInfo() {
		$goodsIds = $_POST['goodsIds'];
		$chanceId = $_POST['chanceId'];
		$rows = $_POST['rows'];
		//�����Ʒ
		$list = $this->service->setHardware_d($goodsIds, $chanceId,$rows);
		//    $list = util_jsonUtil :: encode($list);
		echo $list;
	}

   /**
    * ��Ȩ�б�����ҳ��
    */
   function c_toSetauthorizeInfo(){
      $trackmanIds = $_POST['trackmanIds'];
      $chanceId = isset($_POST['chanceId'])?$_POST['chanceId']:null;
		//�����Ʒ
		$list = $this->service->toSetauthorizeInfo_d($trackmanIds,$chanceId);
		//    $list = util_jsonUtil :: encode($list);
		echo $list;
   }

   /**
    * ��Ȩ�б�(ָ�������Ŷ�ҳ)
    */
   function c_toSetauthorizeInfoEdit(){
      $chanceId = $_POST['chanceId'];
		//�����Ʒ
		$list = $this->service->toSetauthorizeInfoEdit_d($chanceId);
		//    $list = util_jsonUtil :: encode($list);
		echo $list;
   }

//    /**
//     * �̻�Ȩ�ޣ���Ȩ
//     */
//    function c_toAuthorize(){
////    	$this->permCheck();
//		$row = $this->service->get_d($_GET['id']);
//
//		foreach ($row as $key => $val) {
//			$this->assign($key, $val);
//		}
////		$this->assign('authorizeInfo' , $this->service->authorizeInfo_d($_GET['id']));
//		$this->assign('authorizeInfo' , $this->service->authorizeInfo_d(17));
//        $this->view('authorize');
//    }

    /**
     * �б�Ȩ��
     */
      function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
//		echo 1;
	}
		/**
	 *��ת��excel�ϴ�ҳ��
	 */
	function c_toExcel() {
		$this->assign("dateTime", date("Y-m-d"));
		$this->display('importexcel');
	}
	/**
	 * ������������
	 */
    function c_updateDateExcel(){
		$this->display('updateDateExcel');
    }
	/**
	 * �̻���Ʒ����
	 */
	function c_toGoodsExcel() {
		$this->assign("dateTime", date("Y-m-d"));
		$this->display('importexcel-goods');
	}
	/**
	 * �����̻� �����Ʒͳ�ƣ����񲿣�
	 */
	function c_serviceProductList(){
        $this->view("serviceproductList");
	}
	/**
	 * �����̻� �豸ͳ�ƣ���������
	 */
	function c_chanceEquList(){
		$this->view("chanceEquList");
	}
	/**
	 * �����̻� ���۲�Ʒͳ�ƣ���������
	 */
	function c_chanceProductList(){
		$this->view("chanceProductList");
	}
	/**
	 * ajax �����̻���Ż�ȡ�̻�����
	 */
	function c_ajaxChanceByCode(){
		//��ȡ����
		$chanceCode = $_POST['chanceCode'];
		$chanceName = util_jsonUtil :: iconvUTF2GB($_POST['chanceName']);

		//����Ǳ��ƥ��
		if($chanceCode){
			$confition = array("chanceCode"=>$chanceCode);
		}else{
			$confition = array("chanceName"=>$chanceName);
		}
		$arr =  $this->service->findAll($confition,null,null);
		if($arr){
			$rtObj = $arr[0];
			$rtObj['thisLength'] = count($arr);

			echo util_jsonUtil :: encode($rtObj);
		}else{
			return false;
		}
	}


	/**
	 * �̻���Ϣ�б�---�Բ�ƷΪ����
	 */
	function c_chanceGoodsList(){
        $this->view("chanceGoodsList");
	}

	/**
	 * ����id����ʾ�̻���Ϣ�б�
	 */
	function c_chanceListByids(){
		$this->assign("ids",$_GET['ids']);
		$this->view("chanceListByids");
	}
	/**
	 * �̻��鿴-�ر���Ϣ
	 */
	function c_closeInfoList(){
        $this->assign("chanceId",$_GET['chanceId']);
        $rows = $this->service->get_d($_GET['chanceId']);
		if ($rows['status'] == '3') {
			foreach ($rows as $key => $val) {
				$this->assign($key, $val);
			}
			$this->view("closeInfo");
		} else {
			echo '<span>���������Ϣ</span>';
		}
//		$this->view("closeInfoList");

	}

	/**********�̻���ͨ����Ϣ*********/

     /**
      * ��ͬ��Ϣ�б� ��ע
      */
    function c_listRemark(){
    	$this->assign("chanceId" , $_GET['id']);
        $this->view("listremark");
    }
	 function c_listremarkAdd() {
		$rows = $_POST['objInfo'];
        $rows['createName'] = $_SESSION ['USERNAME'];
        $rows['createId'] = $_SESSION ['USER_ID'];
        $rows['createTime'] = date ( "Y-m-d H:i:s" );
	    $id = $this->service->listremarkAdd_d($rows);
	  if($id){
	  	 msg ( '��ӳɹ���' );
	  }
	}

	//��ȡ����
	function c_getRemarkInfo(){
        $contractId = $_POST['chanceId'];
        $info = $this->service->getRemarkInfo_d($contractId);
        echo $info;
//        echo util_jsonUtil :: iconvGB2UTF($info);

	}


	/**
	 * ��ͬ�鿴ҳ��
	 */
	function c_toContractViewTab() {
		//		$this->permCheck (); //��ȫУ��
		$chanceId = $_GET['id'];
		$contractId = $this->service->getContractIdBychanceId($chanceId);
        $conDao = new model_contract_contract_contract();
		$rows = $conDao->get_d($contractId);

		$this->assign('contractCode', $rows['contractCode']);
		$this->assign('originalId', $rows['originalId']);
		$this->assign('contractType', $rows['contractType']);
		$this->assign('contractId', $contractId);

		$this->display('contractview-tab');
	}

	/**
	 * ��ȡ��������
	 */
	function c_getChanceStaleDated(){
		$prinvipalId = $_SESSION['USER_ID'];
		$chanceArr = $this->service->getChanceAll_d($prinvipalId);
		$num = 0;
		foreach($chanceArr as $k=>$v){
			$num++;
		}
		echo util_jsonUtil :: encode($num);
	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toOutputtExcel(){
		//����������
		if($_GET['beginDate']){
			$beginDate = $_GET['beginDate'];
			$endDate = $_GET['endDate'];
		}else{
            $weekDao = new model_engineering_baseinfo_week();
            $weekBEArr = $weekDao->getWeekRange();
			$beginDate = $weekBEArr['beginDate'];
			$endDate = $weekBEArr['endDate'];
		}
		$this->assign('beginDate',$beginDate);
		$this->assign('endDate',$endDate);
		$this->view('outputtExcel');
	}


	/**
	 * ����EXCEL
	 */
	function c_outputtExcel(){
		$beginDate = $_POST['beginDate'];
		$endDate = $_POST['endDate'];
		$ids = $_POST['chanceId'];
		$service = $this->service;
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(0);
		//�����ͷ
		$thArr = array('trackName' => '����������','trackDate' => '��������','trackType' => '��������','linkmanName' => '��ϵ������',
				'trackPurpose' => '����Ŀ��','customerFocus' => '�ͻ���ע��', 'result' => '�Ӵ����', 'problem' => '���������',
				'followPlan' =>'�����ƻ�','chanceCode' =>'�̻����','chanceName' =>'�̻�����',
		);
		$rows = $service->getChanceInfo_d($beginDate,$endDate,$ids);
		foreach($rows as $k=>$v){
			$rows[$k]['trackType'] = $this->getDataNameByCode($v['trackType']);
		}
		return model_contract_common_contExcelUtil::exportChanceRecord($thArr, $rows,'�̻����ټ�¼');
	}
	/**
	 * ���������̻�
	 */
	function c_getChanceIds(){
		$prinvipalId = $_SESSION['USER_ID'];
		$chanceArr = $this->service->getChanceAll_d($prinvipalId);
		$ids = array();
		foreach($chanceArr as $key=>$val){
			array_push($ids,$val['id']);
		}
		echo implode(",",$ids);
	}

	/**
	 * ���������̻�
	 */
	function c_getAllChanceIds(){
		$status = $_POST['status'];
		$chanceArr = $this->service->getAllChanceIds_d($status);
		$ids = array();
		foreach($chanceArr as $key=>$val){
			array_push($ids,$val['id']);
		}
		echo implode(",",$ids);
	}

	/**
	 * ajax��ȡ�̻��Ƿ��й�����ͬ
	 */
	function c_ajaxGetConTurn(){
		$chanceId = $_POST['chanceId'];
		$sql = "select count(id) as num,GROUP_CONCAT(contractCode) as code from oa_contract_contract where chanceId='$chanceId'";
		$tmp = $this->service->_db->getArray($sql);
		if($tmp[0]['num'] > 0){
			 echo $tmp[0]['code'];
		}else{
			echo 0;
		}
	}

    /**
     * ��ȡ��ҳ����ת��Json����д��
     */
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $rows = $service->page_d ();
        $_SESSION['finishChanceSearchArr'] = $service->searchArr;

        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode ( $arr );
    }

    /**
     * �����б�����
     */
	function c_exportExcelData(){
        set_time_limit(0);

        $colIdStr = isset($_REQUEST['ColId'])? $_REQUEST['ColId'] : '';
        $colNameStr = isset($_REQUEST['ColName'])? $_REQUEST['ColName'] : '';

        $service = $this->service;
        $searchArr = $_SESSION['finishChanceSearchArr'];

        //��ͷId����
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //��ͷName����
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //��ͷ����
        $colArr = array_combine($colIdArr, $colNameArr);
        // $colArr = util_jsonUtil::iconvUTF2GBArr($colArr);

        $service->searchArr = $searchArr;
        $rows = $service->list_d ();

        // �ͻ���������
        $customerTypeArr = $this->getDatadicts(array ('customerType' => 'KHLX'));
        $customerType = array();
        if(isset($customerTypeArr['KHLX']) && count($customerTypeArr['KHLX']) > 0){
            foreach ($customerTypeArr['KHLX'] as $k => $v){
                $customerType[$v['dataCode']] = $v['dataName'];
            }
        }

        // �̻���������
        $chanceTypeArr = $this->getDatadicts(array ('customerType' => 'HTLX'));
        $chanceType = array();
        if(isset($chanceTypeArr['HTLX']) && count($chanceTypeArr['HTLX']) > 0){
            foreach ($chanceTypeArr['HTLX'] as $k => $v){
                $chanceType[$v['dataCode']] = $v['dataName'];
            }
        }

        // �̻�״̬����
        $chanceStatus = array(
            "0" => "������",
            "3" => "�ر�",
            "4" => "�����ɺ�ͬ",
            "5" => "������",
            "6" => "��ͣ"
        );

        foreach ($rows as $k => $v){
            $rows[$k]['customerType'] = $customerType[$v['customerType']];
            $rows[$k]['chanceType'] = $chanceType[$v['chanceType']];
            $rows[$k]['status'] = $chanceStatus[$v['status']];
        }

        // echo $service->listSql."<br>";echo "<pre>";print_r($colArr);echo "<br>";print_r($rows);exit();

        $formatArr = array(
            "perArr" => array("winRate"),
            "feeArr" => array("chanceMoney"),
            "txtArr" => array("chanceCode","chanceName","customerName")
        );
        model_finance_common_financeExcelUtil::export2ExcelUtilNew($colArr,$rows,"�����̻�",$formatArr);
    }
}
?>