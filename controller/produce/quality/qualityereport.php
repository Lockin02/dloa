<?php
/**
 * @author Administrator
 * @Date 2013年3月7日 星期四 16:46:27
 * @version 1.0
 * @description:检验报告控制层
 */
class controller_produce_quality_qualityereport extends controller_base_action {

	function __construct() {
		$this->objName = "qualityereport";
		$this->objPath = "produce_quality";
		parent::__construct ();
	}
	/**
	 * 跳转到检验报告Tab
	 */
	function c_toPageTab() {
		$this->view ( 'list-tab' );
	}
	/**
	 * 跳转到检验报告列表
	 */
	function c_page() {
		$this->assign('statu_',$_GET['statu_']);
		$this->view('list');
	}

    /**
     * 跳转到检验报告列表(明细)
     */
    function c_toItempage() {
        $this->assign('type',isset($_GET['type'])?$_GET['type']:"");
        $this->assign('sourceId',isset($_GET['sourceId'])?$_GET['sourceId']:"");
        $this->assign('objType',isset($_GET['objType'])?$_GET['objType']:"");
        $this->view('item-list');
    }

	/**
	 * 跳转到检验报告Tab
	 */
	function c_toMyTab() {
		$this->assign("userId",$_SESSION['USER_ID']);
		$this->view ( 'mylist-tab' );
	}

    /**
     * 查看质检信息 - 传入收料明细id
     */
    function c_toListQuality(){
        $this->assign("relDocItemId",$_GET['relDocItemId']);
        $this->view ( 'list-quality' );
    }
    /**
	 * 跳转到质检报告列表
	 */
	function c_listReport(){
		 $this->view ( 'listReport' );
	}

	/**
	 * 质检报告明细列表
	 */
	function c_pageDetail(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ('select_detail');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转到新增检验报告页面
	 */
	function c_toAdd() {
		$this->assign("mainId",$_GET['mainId']);
		$this->assign("mainCode",$_GET['mainCode']);
        $this->assign('relDocType',$_GET['relDocType']);

		$this->assign("docDate", date ( "Y-m-d" ));
		$this->assign("examineUserId", $_SESSION['USER_ID']);
		$this->assign("examineUserName", $_SESSION['USERNAME']);
		$this->assign("applyId",isset($_GET['applyId']) ? $_GET['applyId'] : '');
        //邮件发送人渲染
        $this->assignFunc($this->service->getMail_d('purchquality'));
        //源单类型为生产检验的，显示计划单及合同编号
        if($_GET['relDocType'] == 'ZJSQYDSC'){
        	$qualityapplyDao = new model_produce_quality_qualityapply();
        	$rs = $qualityapplyDao->find(array('id' => $_GET['applyId']),null,'relDocId,relDocCode');
        	if(!empty($rs['relDocId'])){
        		$this->assign("planCode", "<a href='javascript:void(0)' onclick='showModalWin(\"?model=produce_plan_produceplan&action=toViewTab&id=" . $rs['relDocId'] .'",1,'.$rs['relDocId'].")'>" . $rs['relDocCode'] . "</a>");
        		$produceplanDao = new model_produce_plan_produceplan();
        		$rs = $produceplanDao->find(array('id' => $rs['relDocId']),null,'relDocId,relDocCode');
        		if(!empty($rs['relDocId'])){
        			$this->assign("contractCode", "<a href='javascript:void(0)' onclick='showModalWin(\"?model=contract_contract_contract&action=toView&id=" . $rs['relDocId'] .'",1,'.$rs['relDocId'].")'>" . $rs['relDocCode'] . "</a>");
        		}else{
        			$this->assign("contractCode", '');
        		}
        	}else{
        		$this->assign("planCode", '');
        		$this->assign("contractCode", '');
        	}
        }
		$this->view ( 'add',true );
	}

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$this->checkSubmit();//重复提交校验
		$object = $_POST [$this->objName];
        $id = $this->service->add_d ( $object );
		if ($id) {
			//源单类型为生产检验的，额外处理附件分类信息
			if($object['relDocType'] == 'ZJSQYDSC'){
				$documentDao = new model_produce_document_document();
				$documentDao->updateObjWithFile($_POST['fileuploadIds'],array(
						'typeName' => $_POST['ducument']['typeName'],
						'typeId' => $_POST['ducument']['typeId'],
				));
			}
			if($object['auditStatus'] == 'BC'){
				msgRf ( '保存成功' );
			}else{
                $allPass = $this->service->checkDLBFPass($object['applyId']);
                if($object['relDocType'] == 'ZJSQDLBF' && $allPass){
                    succ_show('controller/produce/quality/ewf_bfzj_index.php?actTo=ewfSelect&billId=' . $id . '&relDocType=' . $object['relDocType']);
                }else{
                    msgRf ( '提交成功' );
                }
			}
		}else{
			if($object['auditStatus'] == 'BC'){
				msgRf ( '保存失败' );
			}else{
				msgRf ( '提交失败' );
			}
		}
	}

	/**
	 * 跳转到编辑检验报告页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj['file'] = $this->service->getFilesByObjId ( $_GET['id'], true,$this->service->tbl_name);
		//源单类型为生产检验的，额外处理附件分类信息
		if($obj['relDocType'] == 'ZJSQYDSC'){
			$managementDao = new model_file_uploadfile_management();
			$rs = $managementDao->findAll(array('serviceId' => $_GET ['id'],'serviceType' => 'oa_produce_quality_ereport'),null,'id,typeId,typeName');
			$fileuploadIdsOld = $typeId = $typeName = '';
			if(!empty($rs)){
				foreach ($rs as $k => $v){
					$fileuploadIdsOld .= $v['id'].',';
					if(empty($typeId) && !empty($v['typeId'])){
						$typeId = $v['typeId'];
					}
					if(empty($typeName) && !empty($v['typeName'])){
						$typeName = $v['typeName'];
					}
				}
			}
			$this->assign ( 'fileuploadIdsOld', substr($fileuploadIdsOld,0,strlen($fileuploadIdsOld)-1));
			$this->assign ( 'typeId', $typeId);
			$this->assign ( 'typeName', $typeName);
			//显示计划单及合同编号
			$qualityapplyDao = new model_produce_quality_qualityapply();
			$rs = $qualityapplyDao->find(array('id' => $obj['applyId']),null,'relDocId,relDocCode');
			if(!empty($rs['relDocId'])){
				$this->assign("planCode", "<a href='javascript:void(0)' onclick='showModalWin(\"?model=produce_plan_produceplan&action=toViewTab&id=" . $rs['relDocId'] .'",1,'.$rs['relDocId'].")'>" . $rs['relDocCode'] . "</a>");
				$produceplanDao = new model_produce_plan_produceplan();
				$rs = $produceplanDao->find(array('id' => $rs['relDocId']),null,'relDocId,relDocCode');
				if(!empty($rs['relDocId'])){
					$this->assign("contractCode", "<a href='javascript:void(0)' onclick='showModalWin(\"?model=contract_contract_contract&action=toView&id=" . $rs['relDocId'] .'",1,'.$rs['relDocId'].")'>" . $rs['relDocCode'] . "</a>");
				}else{
					$this->assign("contractCode", '');
				}
			}else{
				$this->assign("planCode", '');
				$this->assign("contractCode", '');
			}
		}
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

        //邮件发送人渲染
        $this->assignFunc($this->service->getMail_d('purchquality'));
		$this->view ( 'edit',true );
	}

	/**
	 * 修改对象操作
	 */
	function c_edit() {
		$this->checkSubmit();//重复提交校验
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ($object , true )) {
			//源单类型为生产检验的，额外处理附件分类信息
			if($object['relDocType'] == 'ZJSQYDSC'){
				//合并新旧附件id
				$fileuploadIdArr = array();
				if(!empty($_POST['ducument']['fileuploadIdsOld'])){
					$fileuploadIdArr = explode(',', $_POST['ducument']['fileuploadIdsOld']);
				}
				if(isset($_POST['fileuploadIds']) && is_array($_POST['fileuploadIds'])){
					$fileuploadIdArr = array_merge($fileuploadIdArr,$_POST['fileuploadIds']);
				}
				$documentDao = new model_produce_document_document();
				$documentDao->updateObjWithFile($fileuploadIdArr,array(
						'typeName' => $_POST['ducument']['typeName'],
						'typeId' => $_POST['ducument']['typeId'],
				));
			}

			if($object['auditStatus'] == 'BC'){
				msgRf ( '保存成功' );
			}else{
                $baseObjInfo = $this->service->get_d ( $object['id'] );//获取报告主要信息
                $allPass = $this->service->checkDLBFPass($baseObjInfo['applyId']);
                if($baseObjInfo['relDocType'] == 'ZJSQDLBF' && $allPass){
                    succ_show('controller/produce/quality/ewf_bfzj_index.php?actTo=ewfSelect&billId=' . $object['id'] . '&relDocType=' . $baseObjInfo['relDocType']);
                }else{
                    msgRf ( '提交成功' );
                }
			}
		}else{
			if($object['auditStatus'] == 'BC'){
				msgRf ( '保存失败' );
			}else{
				msgRf ( '提交失败' );
			}
		}
	}
	/**
	 * 跳转到查看检验报告页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj['file'] = $this->service->getFilesByObjId ( $_GET['id'], false,$this->service->tbl_name);
		//源单类型为生产检验的，额外处理附件分类信息
		if($obj['relDocType'] == 'ZJSQYDSC'){
			$managementDao = new model_file_uploadfile_management();
			$rs = $managementDao->find(array('serviceId' => $_GET ['id'],'serviceType' => 'oa_produce_quality_ereport'),null,'typeName');
			$this->assign ( 'typeName', empty($rs['typeName']) ? '' : $rs['typeName']);
			//显示计划单及合同编号
			$qualityapplyDao = new model_produce_quality_qualityapply();
			$rs = $qualityapplyDao->find(array('id' => $obj['applyId']),null,'relDocId,relDocCode');
			if(!empty($rs['relDocId'])){
				$this->assign("planCode", "<a href='javascript:void(0)' onclick='showModalWin(\"?model=produce_plan_produceplan&action=toViewTab&id=" . $rs['relDocId'] .'",1,'.$rs['relDocId'].")'>" . $rs['relDocCode'] . "</a>");
				$produceplanDao = new model_produce_plan_produceplan();
				$rs = $produceplanDao->find(array('id' => $rs['relDocId']),null,'relDocId,relDocCode');
				if(!empty($rs['relDocId'])){
					$this->assign("contractCode", "<a href='javascript:void(0)' onclick='showModalWin(\"?model=contract_contract_contract&action=toView&id=" . $rs['relDocId'] .'",1,'.$rs['relDocId'].")'>" . $rs['relDocCode'] . "</a>");
				}else{
					$this->assign("contractCode", '');
				}
			}else{
				$this->assign("planCode", '');
				$this->assign("contractCode", '');
			}
		}
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('auditStatus',$this->service->rtStatus($obj['auditStatus']));
		$this->view ( 'view' );
	}

	/**
	 * 跳转到审核确认检验报告页面
	 */
	function c_toConfirm() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ('confirm',true);
	}

	/**
	 * 审核对象操作
	 */
	function c_confirm() {
		$this->checkSubmit();//重复提交校验
		$object= $_POST [$this->objName];
		if ($this->service->confirm_d($object)) {
			//源单类型为采购收料通知或生产收料通知的才需要走审批。PMS2386:呆料报废也需要走审批
            if($object['relDocType']!='ZJSQYDSL' && $object['relDocType']!='ZJSQYDSC' && $object['relDocType']!='ZJSQDLBF'){
                msgRf('审核成功');
            }else if($object['relDocType'] == 'ZJSQDLBF'){
                succ_show('controller/produce/quality/ewf_bfzj_index.php?actTo=ewfSelect&billId=' . $object['id'] . '&relDocType=' . $object['relDocType']);
            }else{
                $result = $this->service->dealWithoutAudit($object['id']);
                if($result){
                    msgRf('审核成功!');
                }else{
                    msgRf('审核失败');
                }
//                succ_show('controller/produce/quality/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'] . '&relDocType=' . $object['relDocType']);
            }
		}else{
			msgRf('审核失败');
		}
	}

	/**
	 * 审批时查看页面
	 */
	function c_toAudit(){
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assignFunc($obj);

		$this->assign('auditStatus',$this->service->rtStatus($obj['auditStatus']));
		$this->view ( 'audit' );
	}

	/**
	 * 审批确认
	 */
	function c_dealAfterAudit(){
       	$this->service->workflowCallBack($_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * 撤销报告
	 */
	function c_backReport(){
		$rs = $this->service->backReport_d($_POST['id']);
		if($rs){
			if($rs != -1){
				echo 1;
			}else{
				echo $rs;
			}
		}else{
			echo 0;
		}
	}

	/**
	 * 驳回报告
	 */
	function c_rejectReport(){
        echo $this->service->rejectReport_d($_POST['id'],util_jsonUtil::iconvUTF2GB($_POST['reason'])) ? 1 : 0;
	}

    /**
     * 检查原单是否全部质检通过(只用于呆料报废质检)
     */
	function c_checkAllPass(){
        if(isset($_REQUEST['applyId'])){
            $applyId = $_REQUEST['applyId'];
            $result = $this->service->checkDLBFPass($applyId);
            echo $result? 1 : 0;
        }else{
            echo 0;
        }
    }
}