<?php
header("Content-type: text/html; charset=gb2312");
/**
 * @author qian
 * @Date 2011年3月3日 10:49:20
 * @version 1.0
 * @description:销售商机控制层
 */
class controller_projectmanagent_chance_chance extends controller_base_action {

	function __construct() {
		$this->objName = "chance";
		$this->objPath = "projectmanagent_chance";
		parent :: __construct();
	}

	/*********************************************************普通Action方法**************************************************/

	/*
	 * 跳转到销售商机
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * @description 跳转到新增页面
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
			$length = count($linkmanRows); //获取物料数组的长度
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

		    //获取公司名称
	    	$branchDao = new model_deptuser_branch_branch();
	    	$companyInfo = $branchDao->getByCode($_SESSION['Company']);
	    	$this->assign('businessBelong',$_SESSION['Company']);
	    	$this->assign('businessBelongName',$companyInfo['NameCN']);

	    	$this->assign('formBelong',$_SESSION['COM_BRN_PT']);
	    	$this->assign('formBelongName',$_SESSION['COM_BRN_CN']);
	        //大数据部区域特殊处理
	        if(dsjAreaId){
	        	$regionDao = new model_system_region_region();
	        	$rs = $regionDao->find(array('id' => dsjAreaId,'isStart' => '0'),null,'areaName,areaPrincipal,areaPrincipalId,areaSalesmanId');
	        	//当前登录人为大数据部区域销售人员的，要做特殊处理
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
	 * @description 商机的保存方法
	 * @date 2011-03-03 16:55
	 */
	function c_add() {
        $this->checkSubmit();
		$chanceInfo = $_POST[$this->objName];

        $authorizeInfo = $_POST['authorize'];
		$id = $this->service->add_d($authorizeInfo,$chanceInfo, true);

		//判断是否直接提交审批
		if ($id && $_GET['act'] == "app") {
			succ_show('controller/projectmanagent/chance/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_sale_chance&formName=商机立项');
		} else {
			if ($id) {
				msgGo('添加成功！', '?model=projectmanagent_chance_chance&action=toMyChanceTab');
			}
		}

	}

	/**
	 * 线索转商机的保存方法
	 */
	function c_chanceAdd($isAddInfo = false) {
		$chanceInfo = $_POST[$this->objName];

		$chanceCodeDao = new model_common_codeRule();
		$chanceInfo['chanceCode'] = $chanceCodeDao->changeCode("oa_sale_chance", $chanceInfo['customerId']);
		$id = $this->service->addChance_d($chanceInfo);

		//判断是否直接提交审批
		if ($_GET['act'] == "app") {
			$phpurl = 'controller/projectmanagent/chance/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_sale_chance&formName=商机立项';
			$phpurl = str_replace(" ", "", $phpurl);
			echo "<script>location.replace('" . $phpurl . "');</script>";
		}

		//		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		else
			if ($id) {
				msgRF('添加成功！');
			}
	}
	/**
	 * @description 查看/编辑页面
	 * @author
	 */
	function c_init() {
		$service = $this->service;
		$returnObj = $this->objName;
		$id = $_GET['id'];
		$perm = isset ($_GET['perm']) ? $_GET['perm'] : null;

		$trackFlag = isset ($_GET['trackFlag']) ? $_GET['trackFlag'] : null;
		$rows = $this->service->get_d($_GET['id']);
		//判断当前查看表单的人是否是商机的跟踪人
		$trackArr = explode(",",$rows['trackmanId']);
	 if(in_array($_SESSION['USER_ID'],$trackArr)){
	 	$rows = $this->fieldFilterFrom($rows);
	 }
		//渲染从表
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
			//产品列表
			$goodsList = $this->service->productListView($id);
			$this->assign('productList', $goodsList);
			//设备硬件
			$hardwareList = $this->service->hardwareListView($id);
			$this->assign('hardwareList', $hardwareList);
			$this->assign('module', $this->getDataNameByCode($rows['module']));
			$this->view('view');
		} else {

			//提取跟踪人信息
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
	 * 商机更新
	 */
	function c_updateChance() {
		$obj = $this->service->get_d($_GET['id']);
		//判断当前查看表单的人是否是商机的跟踪人
		$trackArr = explode(",",$obj['trackmanId']);
		if(in_array($_SESSION['USER_ID'],$trackArr)){
			$obj = $this->fieldFilterFrom_Update($obj);
		}else{
			$obj['chanceMoneyL'] = "";
			$obj['winRateL'] = "";
			$obj['chanceStageL'] = "";
		}
		//附件
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
		//产品列表
		$goodsList = $this->service->productListUpdate($obj['id']);
		$this->assign('productListA', $goodsList);
		//设备硬件
		$hardwareList = $this->service->hardwareListUpdate($obj['id']);
		$this->assign('hardwareListA', $hardwareList);

		$this->showDatadicts(array ('chanceType' => 'HTLX'), $obj['chanceType']);
		$this->showDatadicts(array ('module' => 'HTBK'), $obj['module']);
		$this->showDatadicts(array ('chanceNature' => $obj['chanceType']), $obj['chanceNature']);
		$this->showDatadicts(array ('winRate' => 'SJYL'), $obj['winRate']);
		$this->showDatadicts(array ('chanceStage' => 'SJJD'), $obj['chanceStage']);
		// $this->showDatadicts(array ('customerType' => 'KHLX'), $obj['customerType']);
		$this->showDatadicts(array ('signSubject' => 'QYZT'), $obj['signSubject']);
		//大数据部区域特殊处理
		if(dsjAreaId){
			$regionDao = new model_system_region_region();
			$rs = $regionDao->find(array('id' => dsjAreaId,'isStart' => '0'),null,'areaName,areaPrincipal,areaPrincipalId,areaSalesmanId');
			//当前登录人为大数据部区域销售人员的，要做特殊处理
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

        // 补充客户类型中文名
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
	 * 更新商机
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
			msg('更新成功！');
		}
	}

	/**
	 * 推进商机
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

		//获取扩展字段1的值
		$dataDao = new model_system_datadict_datadict ();
		$winRateDone = $dataDao->find(array("dataCode"=>$obj['winRate']),null,"expand1");
        $chanceStageDone = $dataDao->find(array("dataCode"=>$obj['chanceStage']),null,"expand1");

        $this->assign("winRateDone", $winRateDone['expand1']);
        $this->assign("chanceStageDone", $chanceStageDone['expand1']);
		$this->view("boostChance");
	}
	/**
	 * 更新推进商机信息
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
			msg('更新成功！');
		}
	}
	/**
	 * ajax 判断商机是否有产品
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
	 * 更新竞争对手信息
	 */
	function c_toCompetitor() {
		$this->assign("chanceId", $_GET['chanceId']);
		$this->view("toCompetitor");
	}
	/**
	 * 更新竞争对手
	 */
	function c_competitorAdd() {
		$flag = $this->service->competitorAdd_d($_POST[$this->objName]);
		if ($flag) {
			msg('保存成功！');
		}
	}
	/**
	 * 更新物料详细信息
	 */
	function c_toProductinfo() {
		$this->assign("chanceId", $_GET['chanceId']);
		$this->view("toProductinfo");
	}
	//更新详细物料信息
	function c_prodcutInfoAdd() {
		$flag = $this->service->prodcutInfoAdd_d($_POST[$this->objName]);
		if ($flag) {
			msg('保存成功！');
		}
	}
	/**
	 * 商机阶段推进信息
	 */
	function c_boostChanceStageInfo() {
		$info = $this->service->boostChanceStageInfo_d($_GET['id']);
		$this->assign("boostinfo", $info);
		$this->view("boostChanceStageInfo");
	}
	/**
	 *商机赢率推进信息
	 */
	function c_winRateInfo() {
		$info = $this->service->winRateInfo_d($_GET['id']);
		$this->assign("winRateInfo", $info);
		$this->view("winRateInfo");
	}
	/**
	 * 定时商机报表
	 */
	function c_chanceInfoList() {
		$this->assign('timingDT' , date("Y-m-d"));
		$this->view("chanceInfoList");
	}

	/**
	 * 商机查看Tab页面
	 */
	function c_toViewTab() {
		//		$this->permCheck();
		$this->assign('id', $_GET['id']);
		$findBorrow = $this->service->findBorrow($_GET['id']);
		$this->display('view-tab');
	}

	/**
	 * 查看Tab--跟踪记录
	 */
	function c_toRecord() {
		$this->assign('chanceId', $_GET['id']);
		$this->display('trackrecord');
	}

	/**
	 * 查看Tab－关闭信息
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
	 * @description 跳转到审批的查看页面
	 *
	 * @date 2011-03-07
	 */
	function c_toView() {
		$service = $this->service;
		$returnObj = $this->objName;
		$id = $_GET['id'];
		//根据商机号获得商机的数据
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
	 * 我的商机TAb
	 */
	function c_toMyChanceTab() {
		$this->display('list-mychanceTab');
	}

	/**
	 * 我负责的商机
	 */
	function c_toMyChanceList() {
		$this->display('list-mychance');
	}
	/**
	 * 个人商机类表
	 */
	function c_chanceByuser(){
		$this->assign('userId', $_GET['USER_ID']);
		$this->display('chancebyuserlist');
	}

	/**
	 * 我跟踪的商机
	 */
	function c_toMyTractList() {
		$this->display('list-mytrack');
	}
	/**
	 * 批量删除对象
	 */
	function c_deletesInfo() {
		$deleteId = isset ($_GET['id']) ? $_GET['id'] : exit;
		$delete = $this->service->deletesInfo_d($deleteId);
		if ($delete) {
			msg('删除成功');
		}
	}

	/**
	 * @description 关闭商机的调用方法
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
	 * @description 暂停商机的调用方法
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
	 * @description 恢复商机的调用方法
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
	 * @description 关闭商机的保存方法
	 * @date 2011-03-06 10:13
	 */
	function c_closeChance() {
		$service = $this->service;
		$rows = $_POST[$this->objName];
		$flag = $service->closeChance_d($rows);
		if ($flag) {
			msg('关闭成功');
		} else {
			msg('关闭失败');
		}
	}

	/**
	 * @description 暂停商机的保存方法
	 * @date 2011-03-06 10:13
	 */
	function c_pauseChance() {
		$service = $this->service;
		$rows = $_POST;
		$flag = $service->pauseChance_d($rows);
		if ($flag) {
			msg('暂停成功');
		} else {
			msg('暂停失败');
		}
	}

	/**
	 * @description 恢复商机的保存方法
	 * @date 2011-03-06 10:13
	 */
	function c_recoverChance() {
		$service = $this->service;
		$rows = $_POST[$this->objName];
		$flag = $service->recoverChance_d($rows);
		if ($flag) {
			msg('恢复成功');
		} else {
			msg('恢复失败');
		}
	}
	/**
	 * 指定跟踪人
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
	 *  移交商机
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
	 * @description 跳转到商机审批的查看列表页
	 * @date 2011-03-06 10:14
	 */
	function c_toChanceApprovalTab() {
		$this->display('tab-app');
	}

	/**
	 * @description 商机的待审批列表
	 * @date 2011-03-06 10:16
	 */
	function c_toUnApprovalList() {
		$this->display('list-unapp');
	}

	/**
	 * @description 商机的已审批列表
	 */
	function c_toApprovaledList() {
		$this->view('list-apped');
	}

	/**
	 * 申请资源
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
	 * 销售支持-申请支持
	 */
	function c_toSupport() {
		$this->display('support');
	}
	/**
	 * 申请资源---借试用
	 */
	function c_toBorrow() {
		$this->display('resource-borrow');
	}

	/**
	* 部门主管指定本部门跟踪人
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
	 * 部门主管指定本部门跟踪人方法
	 */
	function c_deptTrack() {
		$id = $this->service->deptTrack_d($_POST[$this->objName], true);
		if ($id) {
			msg('修改成功！');
		}
	}
	/*
	 * 跳转到已完成商机页面
	 */
	function c_finish() {

		$this->view("finish");
	}

	/*********************************************************以上是普通Action方法**************************************************/

	/**
	 * 修改跟踪人
	 */
	function c_editTrack() {
		$authorizeInfo = $_POST['authorize'];
		$id = $this->service->TrackmanEdit_d($authorizeInfo,$_POST[$this->objName], true);
		if ($id) {
			msg('修改成功！');
		}
	}
	/**
	 * 移交商机方法
	 */
	function c_transfer() {
		$id = $this->service->transfer_d($_POST[$this->objName], true);
		if ($id) {
			msg('修改成功！');
		}
	}
	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST[$this->objName];
		if ($this->service->edit_d($object, $isEditInfo)) {
			msgRF('编辑成功！');
		}
	}
	/*********************************************************Ajax及JSON方法**************************************************/
    /**
	 *  商机销售物料统计Json
	 */
	function c_chanceEquListJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
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
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}
	 //*  商机销售物料统计Json---下拉从表数据
	function c_chanceEquInfoJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
        $service->asc = false;
        $service->sort = "";

        $service->searchArr['productId'] = $_POST['productId'];
        $service->searchArr['winRate'] = $_POST['winRate'];
        $rows = $service->pageBySqlId("select_chanceEqu_info");

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 *  商机销售产品统计Json
	 */
	function c_chanceProductJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
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
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}
	 //*  商机销售产品统计Json---下拉从表数据
	function c_chanceProInfoJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
        $service->asc = false;
        $service->sort = "";

        $service->searchArr['conProductIdC'] = $_POST['conProductId'];
        $service->searchArr['winRate'] = $_POST['winRate'];
        $rows = $service->pageBySqlId("select_chanceProduct_info");

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 *  商机服务产品统计Json
	 */
	function c_serviceProductJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
        $service->asc = false;
        $service->sort = "";
//        $rateDao->searchArr['relDocIdArr'] = $orderIdStr;
        //查找服务类的产品
        $rows = $service->pageBySqlId("select_serviceProduct");

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 *  商机销售产品统计Json
	 */
	function c_chanceGoodsJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
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
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}
	 //*  商机销售产品统计Json---下拉从表数据
	function c_chanceGoodsInfoJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
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

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * 商机信息列表
	 */
	function c_chanceGridJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
//        $service->sort = "c.newUpdateDate";
       //过滤型权限设置
		$limit = $this->initLimit();
		if ($limit == true) {
           $rows = $service->page_d ();
           //统计金额
		   $rows = $service->getRowsallMoney_d($rows, "select_list");
		}
		//获取有备注信息的合同id
	    $remarkIsArr = $this->service->getRemarkIs();
	    foreach($rows as $key => $val){
			if(in_array($val['id'],$remarkIsArr)){
	                $rows[$key]['flag'] = "1";
			}
		}
		//$service->asc = false;

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 商机信息列表(历史信息)
	 */
	function c_chanceListJson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
       //过滤型权限设置
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
	 * @description 我负责的商机
	 */
	function c_pageJsonMyChance() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息

		$service->asc = true;
		$service->searchArr['prinvipalId'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId("select_default");
		//获取有备注信息的合同id
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
	 * 商机列表显示更新记录格式
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
	 * 我跟踪的商机
	 */
	function c_pageJsonMyTrack() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息

		$service->asc = true;
		$service->searchArr['trackmanIdForS'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId("select_mychance");
		//获取有备注信息的合同id
	    $remarkIsArr = $this->service->getRemarkIs();
	    foreach($rows as $key => $val){
			if(in_array($val['id'],$remarkIsArr)){
	                $rows[$key]['flag'] = "1";
			}
		}
		//敏感字段过滤
		$rows = $this->fieldFilter($rows);
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}


	 /**
	  * 商机列表 敏感字段过滤
	  */
    function fieldFilter($rows) {
		foreach ($rows as $key => $val) {
			//判断当前查看表单的人是否是商机的跟踪人
			$trackArr = explode(",",$val['trackmanId']);
		 if(in_array($_SESSION['USER_ID'],$trackArr)){
		 	//判断权限点
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
	  * 商机列表 敏感字段过滤(表单)
	  */
    function fieldFilterFrom($rows) {
		//判断权限点
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
		//判断权限点
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
	 * @description 商机待审批的JSON方法
	 */
	function c_unApprovalJson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
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
	 * @description 已审批的商机的JSON方法
	 */
	function c_approvaledJson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
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
	 * @ ajax判断项
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
	* 从表选择物料处理配置
	*/
	function c_ajaxConfig() {
		$configInfo = $this->service->c_configuration($_GET['id'], $_GET['Num'], $_GET['trId']);
		echo $configInfo[0];
	}


	/**
	 * 权限设置
	 * 权限返回结果如下:
	 * 如果包含权限，返回true
	 * 如果无权限,返回false
	 */
	function initLimit() {
		$service = $this->service;
		//权限配置数组
		$limitConfigArr = array (
			'areaLimit' => 'c.areaCode',
			'deptLimit' => 'c.prinvipalDeptId',
			'customerTypeLimit' => 'c.customerType'
		);
		//权限数组
		$limitArr = array ();
		//权限系统
		if (isset ($this->service->this_limit['商机销售区域']) && !empty ($this->service->this_limit['商机销售区域']))
			$limitArr['areaLimit'] = $this->service->this_limit['商机销售区域'];
		if (isset ($this->service->this_limit['商机部门权限']) && !empty ($this->service->this_limit['商机部门权限']))
			$limitArr['deptLimit'] = $this->service->this_limit['商机部门权限'];
		if (isset ($this->service->this_limit['商机客户类型']) && !empty ($this->service->this_limit['商机客户类型']))
			$limitArr['customerTypeLimit'] = $this->service->this_limit['商机客户类型'];
		if ( strstr($limitArr['areaLimit'], ';;') || strstr($limitArr['deptLimit'], ';;') || strstr($limitArr['customerTypeLimit'], ';;')) {
			return true;
		} else {
			//区域负责人获取相关区域
			$regionDao = new model_system_region_region();
			$areaPri = $regionDao->getUserAreaId($_SESSION['USER_ID'], 0);
			if (!empty ($areaPri)) {
				//区域权限合并
				$limitArr['areaLimit'] = implode(array_filter(array (
					$limitArr['areaLimit'],
					$areaPri
				)), ',');
			}
                        //销售负责人读取对应省份和客户类型
                        $saleArea= new model_system_saleperson_saleperson();
                        $saleAreaInfo=$saleArea->getSaleArea($_SESSION['USER_ID']);
                        if(!empty($saleAreaInfo)){
                            $limitArr['saleAreaInfo']=$saleAreaInfo;
                        }
			//			print_r($limitArr);
			if (empty ($limitArr)) {
				return false;
			} else {
				//配置混合权限
				$i = 0;
				$sqlStr = "sql:and ( ";
                                //增加销售负责人
                                if(!empty($limitArr['saleAreaInfo'])){
                                    $saleAreaStr="";
                                    foreach($saleAreaInfo as $sval){
                                        $saleTemp="";
                                        //客户类型
                                        $saleTemp.=" c.customerType  in ('".  str_replace(',', "','", $sval['customerType'])."') ";
                                        //省份
                                        if($sval['provinceId']!='0'){//全国过滤掉
                                            $saleTemp.="and c.ProvinceId ='".$sval['provinceId']."'  ";
                                        }
                                        $saleAreaStr.="or ( ".$saleTemp." ) ";
                                    }
                                    if(!empty($saleAreaStr)){
                                        $sqlStr.=trim($saleAreaStr,'or');
                                        $i++;//标记
                                    }
                                    unset($limitArr['saleAreaInfo']);//消除
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

	/*********************************************************以上是Ajax及JSON方法**************************************************/

	/**********************************************************************/
	/**
	 * 部门线索跳转函数
	 * by maizp
	 */

	function c_deptChance() {
		$this->display('deptlist');
	}

	/******************start信息列表导出**************************/

	function c_exportExcel() {
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(600);

		$statusArr = array ('0' => '跟踪中', '3' => '关闭', '4' => '已生成合同', '5' => '跟踪中', '6' => '暂停' );
        $chanceTypeArr = array('SJLX01' => '销售类','SJLX01'=> '服务类');
        $isTurnArr = array('0' => '-','1'=>'√');
		$colIdStr = $_GET ['colId'];
		$colNameStr = $_GET ['colName'];
		$status = $_GET ['status'];
		$chanceType = $_GET ['chanceType'];

		$chanceLevel = $_GET ['chanceLevel'];
		$winRate = $_GET ['winRate'];
		$chanceStage = $_GET ['chanceStage'];
		//过滤条件
		$searchArr['status'] = $status;
		$searchArr ['chanceType'] = $chanceType;
		$searchArr ['chanceLevel'] = $chanceLevel;
		$searchArr ['winRate'] = $winRate;
		$searchArr ['chanceStage'] = $chanceStage;
		$searchConditionKey = $_GET['searchConditionKey']; //普通搜索的Key
		$searchConditionVal = $_GET['searchConditionVal']; //普通搜索的Val
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
				//取产品数量的最大值(当没有产品的时候,count($goodsinfo)也会为1,暂时无解,因此有如下判断)
				if(count($goodsinfo)!=$goodsNum+1&&count($goodsinfo)>$goodsNum){
					$goodsNum = count($goodsinfo);
				}
				//插入产品数据
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
		//往列名中加入产品数量最大值的,
	   for($i=0;$i<$goodsNum;$i++){
					$colIdStr.=",goodsName$i,money$i";
					$colNameStr.=",产品名称,金额";
		}
		//表头Id数组
		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		//表头Name数组
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		//表头数组
		$colArr = array_combine($colIdArr, $colNameArr);

		//匹配导出列
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
        	echo "无数据";
        }else{
        	return model_contract_common_contExcelUtil :: exportChanceExcelUtil($colArr, $dataArr);
        }

	}


	/**
	 * 历史商机保存
	 */
	function c_historyChanceExcel(){
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(600);

		$statusArr = array ('0' => '跟踪中', '3' => '关闭', '4' => '已生成合同', '5' => '跟踪中', '6' => '暂停' );
        $chanceTypeArr = array('SJLX01' => '销售类','SJLX01'=> '服务类');
		$colIdStr = $_GET ['colId'];
		$colNameStr = $_GET ['colName'];
		$status = $_GET ['status'];
		$chanceType = $_GET ['chanceType'];

		$chanceLevel = $_GET ['chanceLevel'];
		$winRate = $_GET ['winRate'];
		$chanceStage = $_GET ['chanceStage'];
		$timingDate = $_GET['timingDate'];
		//过滤条件
		$searchArr['status'] = $status;
		$searchArr ['chanceType'] = $chanceType;
		$searchArr ['chanceLevel'] = $chanceLevel;
		$searchArr ['winRate'] = $winRate;
		$searchArr ['chanceStage'] = $chanceStage;
		$searchArr ['timingDate'] = $timingDate;
		$searchConditionKey = $_GET['searchConditionKey']; //普通搜索的Key
		$searchConditionVal = $_GET['searchConditionVal']; //普通搜索的Val
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
				//取产品数量的最大值(当没有产品的时候,count($goodsinfo)也会为1,暂时无解,因此有如下判断)
				if(count($goodsinfo)!=$goodsNum+1&&count($goodsinfo)>$goodsNum){
					$goodsNum = count($goodsinfo);
				}
				//插入产品数据
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
		//往列名中加入产品数量最大值的,
	   for($i=0;$i<$goodsNum;$i++){
					$colIdStr.=",goodsName$i,money$i";
					$colNameStr.=",产品名称,金额";
		}
		//表头Id数组
		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		//表头Name数组
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		//表头数组
		$colArr = array_combine($colIdArr, $colNameArr);

		//匹配导出列
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
        	echo "无数据";
        }else{
        	return model_contract_common_contExcelUtil :: exportChanceExcelUtil($colArr, $dataArr);
        }

	}
	/******************start信息列表导出************end**************/

	/**
	 * 选产品
	 */
	function c_chanceProduct() {
		$this->assign('productLen',$_GET['productLen']);
		$this->assign('chanceId', $_GET['chanceId']);
		$this->view('choose-list');
	}

	//处理选择的产品
	function c_toSetProductInfo() {
		$goodsIds = $_POST['goodsIds'];
		$chanceId = $_POST['chanceId'];
		$rows = $_POST['rows'];
		$productLen = $_POST['productLen'];
		//处理产品
		$list = $this->service->setProduct_d($goodsIds, $chanceId,$rows,$productLen);
		//    $list = util_jsonUtil :: encode($list);
		echo $list;
	}


	/**
	 * 选设备 硬件
	 */
	function c_chanceHardware() {
		$this->assign('chanceId', $_GET['chanceId']);
		$this->view('choose-hardware');
	}


	//处理选择的设备硬件
	function c_toSetHardwareInfo() {
		$goodsIds = $_POST['goodsIds'];
		$chanceId = $_POST['chanceId'];
		$rows = $_POST['rows'];
		//处理产品
		$list = $this->service->setHardware_d($goodsIds, $chanceId,$rows);
		//    $list = util_jsonUtil :: encode($list);
		echo $list;
	}

   /**
    * 授权列表（新增页）
    */
   function c_toSetauthorizeInfo(){
      $trackmanIds = $_POST['trackmanIds'];
      $chanceId = isset($_POST['chanceId'])?$_POST['chanceId']:null;
		//处理产品
		$list = $this->service->toSetauthorizeInfo_d($trackmanIds,$chanceId);
		//    $list = util_jsonUtil :: encode($list);
		echo $list;
   }

   /**
    * 授权列表(指定跟踪团队页)
    */
   function c_toSetauthorizeInfoEdit(){
      $chanceId = $_POST['chanceId'];
		//处理产品
		$list = $this->service->toSetauthorizeInfoEdit_d($chanceId);
		//    $list = util_jsonUtil :: encode($list);
		echo $list;
   }

//    /**
//     * 商机权限，授权
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
     * 列表权限
     */
      function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
//		echo 1;
	}
		/**
	 *跳转到excel上传页面
	 */
	function c_toExcel() {
		$this->assign("dateTime", date("Y-m-d"));
		$this->display('importexcel');
	}
	/**
	 * 更新日期数据
	 */
    function c_updateDateExcel(){
		$this->display('updateDateExcel');
    }
	/**
	 * 商机产品导入
	 */
	function c_toGoodsExcel() {
		$this->assign("dateTime", date("Y-m-d"));
		$this->display('importexcel-goods');
	}
	/**
	 * 销售商机 服务产品统计（服务部）
	 */
	function c_serviceProductList(){
        $this->view("serviceproductList");
	}
	/**
	 * 销售商机 设备统计（交付部）
	 */
	function c_chanceEquList(){
		$this->view("chanceEquList");
	}
	/**
	 * 销售商机 销售产品统计（交付部）
	 */
	function c_chanceProductList(){
		$this->view("chanceProductList");
	}
	/**
	 * ajax 根据商机编号获取商机数据
	 */
	function c_ajaxChanceByCode(){
		//获取数据
		$chanceCode = $_POST['chanceCode'];
		$chanceName = util_jsonUtil :: iconvUTF2GB($_POST['chanceName']);

		//如果是编号匹配
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
	 * 商机信息列表---以产品为主体
	 */
	function c_chanceGoodsList(){
        $this->view("chanceGoodsList");
	}

	/**
	 * 根据id串显示商机信息列表
	 */
	function c_chanceListByids(){
		$this->assign("ids",$_GET['ids']);
		$this->view("chanceListByids");
	}
	/**
	 * 商机查看-关闭信息
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
			echo '<span>暂无相关信息</span>';
		}
//		$this->view("closeInfoList");

	}

	/**********商机沟通版信息*********/

     /**
      * 合同信息列表 备注
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
	  	 msg ( '添加成功！' );
	  }
	}

	//获取数据
	function c_getRemarkInfo(){
        $contractId = $_POST['chanceId'];
        $info = $this->service->getRemarkInfo_d($contractId);
        echo $info;
//        echo util_jsonUtil :: iconvGB2UTF($info);

	}


	/**
	 * 合同查看页面
	 */
	function c_toContractViewTab() {
		//		$this->permCheck (); //安全校验
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
	 * 获取所有数据
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
	 * 跳转到导出页面
	 */
	function c_toOutputtExcel(){
		//周日期设置
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
	 * 导出EXCEL
	 */
	function c_outputtExcel(){
		$beginDate = $_POST['beginDate'];
		$endDate = $_POST['endDate'];
		$ids = $_POST['chanceId'];
		$service = $this->service;
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(0);
		//定义表头
		$thArr = array('trackName' => '跟踪人姓名','trackDate' => '跟踪日期','trackType' => '跟踪类型','linkmanName' => '联系人姓名',
				'trackPurpose' => '跟踪目的','customerFocus' => '客户关注点', 'result' => '接触结果', 'problem' => '待解决问题',
				'followPlan' =>'后续计划','chanceCode' =>'商机编号','chanceName' =>'商机名称',
		);
		$rows = $service->getChanceInfo_d($beginDate,$endDate,$ids);
		foreach($rows as $k=>$v){
			$rows[$k]['trackType'] = $this->getDataNameByCode($v['trackType']);
		}
		return model_contract_common_contExcelUtil::exportChanceRecord($thArr, $rows,'商机跟踪记录');
	}
	/**
	 * 导出个人商机
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
	 * 导出所有商机
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
	 * ajax获取商机是否有关联合同
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
     * 获取分页数据转成Json（重写）
     */
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //设置前台获取的参数信息

        //$service->asc = false;
        $rows = $service->page_d ();
        $_SESSION['finishChanceSearchArr'] = $service->searchArr;

        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode ( $arr );
    }

    /**
     * 导出列表数据
     */
	function c_exportExcelData(){
        set_time_limit(0);

        $colIdStr = isset($_REQUEST['ColId'])? $_REQUEST['ColId'] : '';
        $colNameStr = isset($_REQUEST['ColName'])? $_REQUEST['ColName'] : '';

        $service = $this->service;
        $searchArr = $_SESSION['finishChanceSearchArr'];

        //表头Id数组
        $colIdArr = explode(',', $colIdStr);
        $colIdArr = array_filter($colIdArr);
        //表头Name数组
        $colNameArr = explode(',', $colNameStr);
        $colNameArr = array_filter($colNameArr);
        //表头数组
        $colArr = array_combine($colIdArr, $colNameArr);
        // $colArr = util_jsonUtil::iconvUTF2GBArr($colArr);

        $service->searchArr = $searchArr;
        $rows = $service->list_d ();

        // 客户类型数组
        $customerTypeArr = $this->getDatadicts(array ('customerType' => 'KHLX'));
        $customerType = array();
        if(isset($customerTypeArr['KHLX']) && count($customerTypeArr['KHLX']) > 0){
            foreach ($customerTypeArr['KHLX'] as $k => $v){
                $customerType[$v['dataCode']] = $v['dataName'];
            }
        }

        // 商机类型数组
        $chanceTypeArr = $this->getDatadicts(array ('customerType' => 'HTLX'));
        $chanceType = array();
        if(isset($chanceTypeArr['HTLX']) && count($chanceTypeArr['HTLX']) > 0){
            foreach ($chanceTypeArr['HTLX'] as $k => $v){
                $chanceType[$v['dataCode']] = $v['dataName'];
            }
        }

        // 商机状态数组
        $chanceStatus = array(
            "0" => "跟踪中",
            "3" => "关闭",
            "4" => "已生成合同",
            "5" => "跟踪中",
            "6" => "暂停"
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
        model_finance_common_financeExcelUtil::export2ExcelUtilNew($colArr,$rows,"销售商机",$formatArr);
    }
}
?>