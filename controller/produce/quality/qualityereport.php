<?php
/**
 * @author Administrator
 * @Date 2013��3��7�� ������ 16:46:27
 * @version 1.0
 * @description:���鱨����Ʋ�
 */
class controller_produce_quality_qualityereport extends controller_base_action {

	function __construct() {
		$this->objName = "qualityereport";
		$this->objPath = "produce_quality";
		parent::__construct ();
	}
	/**
	 * ��ת�����鱨��Tab
	 */
	function c_toPageTab() {
		$this->view ( 'list-tab' );
	}
	/**
	 * ��ת�����鱨���б�
	 */
	function c_page() {
		$this->assign('statu_',$_GET['statu_']);
		$this->view('list');
	}

    /**
     * ��ת�����鱨���б�(��ϸ)
     */
    function c_toItempage() {
        $this->assign('type',isset($_GET['type'])?$_GET['type']:"");
        $this->assign('sourceId',isset($_GET['sourceId'])?$_GET['sourceId']:"");
        $this->assign('objType',isset($_GET['objType'])?$_GET['objType']:"");
        $this->view('item-list');
    }

	/**
	 * ��ת�����鱨��Tab
	 */
	function c_toMyTab() {
		$this->assign("userId",$_SESSION['USER_ID']);
		$this->view ( 'mylist-tab' );
	}

    /**
     * �鿴�ʼ���Ϣ - ����������ϸid
     */
    function c_toListQuality(){
        $this->assign("relDocItemId",$_GET['relDocItemId']);
        $this->view ( 'list-quality' );
    }
    /**
	 * ��ת���ʼ챨���б�
	 */
	function c_listReport(){
		 $this->view ( 'listReport' );
	}

	/**
	 * �ʼ챨����ϸ�б�
	 */
	function c_pageDetail(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ('select_detail');
		//���ݼ��밲ȫ��
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
	 * ��ת���������鱨��ҳ��
	 */
	function c_toAdd() {
		$this->assign("mainId",$_GET['mainId']);
		$this->assign("mainCode",$_GET['mainCode']);
        $this->assign('relDocType',$_GET['relDocType']);

		$this->assign("docDate", date ( "Y-m-d" ));
		$this->assign("examineUserId", $_SESSION['USER_ID']);
		$this->assign("examineUserName", $_SESSION['USERNAME']);
		$this->assign("applyId",isset($_GET['applyId']) ? $_GET['applyId'] : '');
        //�ʼ���������Ⱦ
        $this->assignFunc($this->service->getMail_d('purchquality'));
        //Դ������Ϊ��������ģ���ʾ�ƻ�������ͬ���
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
	 * �����������
	 */
	function c_add() {
		$this->checkSubmit();//�ظ��ύУ��
		$object = $_POST [$this->objName];
        $id = $this->service->add_d ( $object );
		if ($id) {
			//Դ������Ϊ��������ģ����⴦����������Ϣ
			if($object['relDocType'] == 'ZJSQYDSC'){
				$documentDao = new model_produce_document_document();
				$documentDao->updateObjWithFile($_POST['fileuploadIds'],array(
						'typeName' => $_POST['ducument']['typeName'],
						'typeId' => $_POST['ducument']['typeId'],
				));
			}
			if($object['auditStatus'] == 'BC'){
				msgRf ( '����ɹ�' );
			}else{
                $allPass = $this->service->checkDLBFPass($object['applyId']);
                if($object['relDocType'] == 'ZJSQDLBF' && $allPass){
                    succ_show('controller/produce/quality/ewf_bfzj_index.php?actTo=ewfSelect&billId=' . $id . '&relDocType=' . $object['relDocType']);
                }else{
                    msgRf ( '�ύ�ɹ�' );
                }
			}
		}else{
			if($object['auditStatus'] == 'BC'){
				msgRf ( '����ʧ��' );
			}else{
				msgRf ( '�ύʧ��' );
			}
		}
	}

	/**
	 * ��ת���༭���鱨��ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj['file'] = $this->service->getFilesByObjId ( $_GET['id'], true,$this->service->tbl_name);
		//Դ������Ϊ��������ģ����⴦����������Ϣ
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
			//��ʾ�ƻ�������ͬ���
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

        //�ʼ���������Ⱦ
        $this->assignFunc($this->service->getMail_d('purchquality'));
		$this->view ( 'edit',true );
	}

	/**
	 * �޸Ķ������
	 */
	function c_edit() {
		$this->checkSubmit();//�ظ��ύУ��
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ($object , true )) {
			//Դ������Ϊ��������ģ����⴦����������Ϣ
			if($object['relDocType'] == 'ZJSQYDSC'){
				//�ϲ��¾ɸ���id
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
				msgRf ( '����ɹ�' );
			}else{
                $baseObjInfo = $this->service->get_d ( $object['id'] );//��ȡ������Ҫ��Ϣ
                $allPass = $this->service->checkDLBFPass($baseObjInfo['applyId']);
                if($baseObjInfo['relDocType'] == 'ZJSQDLBF' && $allPass){
                    succ_show('controller/produce/quality/ewf_bfzj_index.php?actTo=ewfSelect&billId=' . $object['id'] . '&relDocType=' . $baseObjInfo['relDocType']);
                }else{
                    msgRf ( '�ύ�ɹ�' );
                }
			}
		}else{
			if($object['auditStatus'] == 'BC'){
				msgRf ( '����ʧ��' );
			}else{
				msgRf ( '�ύʧ��' );
			}
		}
	}
	/**
	 * ��ת���鿴���鱨��ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj['file'] = $this->service->getFilesByObjId ( $_GET['id'], false,$this->service->tbl_name);
		//Դ������Ϊ��������ģ����⴦����������Ϣ
		if($obj['relDocType'] == 'ZJSQYDSC'){
			$managementDao = new model_file_uploadfile_management();
			$rs = $managementDao->find(array('serviceId' => $_GET ['id'],'serviceType' => 'oa_produce_quality_ereport'),null,'typeName');
			$this->assign ( 'typeName', empty($rs['typeName']) ? '' : $rs['typeName']);
			//��ʾ�ƻ�������ͬ���
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
	 * ��ת�����ȷ�ϼ��鱨��ҳ��
	 */
	function c_toConfirm() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ('confirm',true);
	}

	/**
	 * ��˶������
	 */
	function c_confirm() {
		$this->checkSubmit();//�ظ��ύУ��
		$object= $_POST [$this->objName];
		if ($this->service->confirm_d($object)) {
			//Դ������Ϊ�ɹ�����֪ͨ����������֪ͨ�Ĳ���Ҫ��������PMS2386:���ϱ���Ҳ��Ҫ������
            if($object['relDocType']!='ZJSQYDSL' && $object['relDocType']!='ZJSQYDSC' && $object['relDocType']!='ZJSQDLBF'){
                msgRf('��˳ɹ�');
            }else if($object['relDocType'] == 'ZJSQDLBF'){
                succ_show('controller/produce/quality/ewf_bfzj_index.php?actTo=ewfSelect&billId=' . $object['id'] . '&relDocType=' . $object['relDocType']);
            }else{
                $result = $this->service->dealWithoutAudit($object['id']);
                if($result){
                    msgRf('��˳ɹ�!');
                }else{
                    msgRf('���ʧ��');
                }
//                succ_show('controller/produce/quality/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'] . '&relDocType=' . $object['relDocType']);
            }
		}else{
			msgRf('���ʧ��');
		}
	}

	/**
	 * ����ʱ�鿴ҳ��
	 */
	function c_toAudit(){
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assignFunc($obj);

		$this->assign('auditStatus',$this->service->rtStatus($obj['auditStatus']));
		$this->view ( 'audit' );
	}

	/**
	 * ����ȷ��
	 */
	function c_dealAfterAudit(){
       	$this->service->workflowCallBack($_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * ��������
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
	 * ���ر���
	 */
	function c_rejectReport(){
        echo $this->service->rejectReport_d($_POST['id'],util_jsonUtil::iconvUTF2GB($_POST['reason'])) ? 1 : 0;
	}

    /**
     * ���ԭ���Ƿ�ȫ���ʼ�ͨ��(ֻ���ڴ��ϱ����ʼ�)
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