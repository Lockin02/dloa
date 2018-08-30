<?php

/**
 * @author show
 * @Date 2013��10��24�� 19:30:28
 * @version 1.0
 * @description:�⳥�����Ʋ�
 */
class controller_finance_compensate_compensate extends controller_base_action
{

    function __construct() {
        $this->objName = "compensate";
        $this->objPath = "finance_compensate";
        parent:: __construct();
    }

    /**
     * ��ת���⳥���б�
     */
    function c_page() {
        $this->assign('thisDate', day_date);
        $this->view('list');
    }

	/**
	 * ��ת�������⳥���б�
	 */
	function c_myPage() {
		$this->assign('dutyObjId',$_SESSION['USER_ID']);
		$this->view('mylist');
	}

	/**
	 * ��ת�������⳥��Ϣ�б�
	 */
	function c_deductPage() {
		$this->view('deductlist');
	}
    
    /**
     * ��ȡ�⳥����
     */
    function c_businessGetDetail() {
        //Դ�����ֲ��Դ���
        $relClass = $this->service->getRelType($_REQUEST['relDocType']);
        if ($relClass) {
            $relClassM = new $relClass();//����ʵ��
            $relObj = $this->service->businessGetDetail_d($_REQUEST, $relClassM);
            $proInfoDao = new model_stock_productinfo_productinfo();
            foreach ($relObj as $k => $v){
                $proInfoArr = $proInfoDao->getProByCode($v['productNo']);
                $relObj[$k]['unitPrice'] = ($proInfoArr)? $proInfoArr['priCost'] : 0;
            }
            echo util_jsonUtil::encode($relObj);
        } else {
            exit('δ���õĲ�����');
        }
    }

    /**
     * ��ת�������⳥��ҳ��
     */
    function c_toAdd() {
        // �⳥����Ĭ��ֵ
        $dutyType = 'PCZTLX-01';

        // Դ�����ֲ��Դ���
        $relClass = $this->service->getRelType($_GET['relDocType']);
        if ($relClass) {
            $relClassM = new $relClass();//����ʵ��
            $relObj = $this->service->businessGet_d($_GET['relDocId'], $relClassM);
            $this->assignFunc($relObj);
            $this->assign('objTypeName', $this->getDataNameByCode($relObj['objType']));
            $dutyType = isset($relObj['dutyType']) ? $relObj['dutyType'] : $dutyType;
        }
        $this->assignFunc($_GET);

        // �����⳥����
        $this->showDatadicts(array('dutyType' => 'PCZTLX'), '',array('��ѡ��' => ''));

        $this->assign('formDate', day_date);
        $this->view('add', true);
    }

    /**
     * ��������
     */
    function c_add() {
        $this->checkSubmit();
        if ($this->service->add_d($_POST[$this->objName])) {
            msgRf('����ɹ���');
        } else {
            msgRf('����ʧ�ܣ�');
        }
    }

    /**
     * ��ת���༭�⳥��ҳ��
     */
    function c_toEdit() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //Դ�����ֲ��Դ���
        $relClass = $this->service->getRelType($obj['relDocType']);
        if ($relClass) {
            $relClassM = new $relClass();//����ʵ��
            $relObj = $this->service->businessGet_d($obj['relDocId'], $relClassM);
            $this->assign('qualityObjType', $relObj['qualityObjType']);
        }
        $this->assign('objTypeName', $this->getDataNameByCode($obj['objType']));
        $this->showDatadicts(array('dutyType' => 'PCZTLX'), $obj['dutyType'],array('��ѡ��' => ''));

        $isConfirm = isset($_GET['isConfirm']) ? $_GET['isConfirm'] : 0;//ȷ��״̬
        $this->assign('isConfirm', $isConfirm);

        $this->view('edit');
    }

    /**
     * �޸Ķ���
     */
    function c_edit() {
        $object = $_POST[$this->objName];
        if ($this->service->edit_d($object)) {
            if (empty($object['isSubAudit'])) {
                msgRf('����ɹ���');
            } else {
                succ_show('controller/finance/compensate/ewf_compensate.php?actTo=ewfSelect&billId=' . $object['id'] . '&flowMoney=' . $object['formMoney'] . '&billDept=' . $object['deptId']);
            }
        }
    }

    /**
     * ��ת���鿴�⳥��ҳ��
     */
    function c_toView() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        // �ϼ����Ͼ�ֵ
        $rs = $this->service->_db->getArray("SELECT SUM(price) as price,SUM(unitPrice) as unitPrice FROM oa_finance_compensate_detail WHERE mainId = {$_GET['id']} GROUP BY mainId");
        if(!empty($rs)){
            $obj['price'] = $rs[0]['price'];
            $obj['unitPrice'] = $rs[0]['unitPrice'];
        }
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //Դ�����ֲ��Դ���
        $relClass = $this->service->getRelType($obj['relDocType']);
        if ($relClass) {
            $relClassM = new $relClass();//����ʵ��
            $relObj = $this->service->businessGet_d($obj['relDocId'], $relClassM);
            $this->assign('qualityObjType', $relObj['qualityObjType']);
        }
        $this->assign('objTypeName', $this->getDataNameByCode($obj['objType']));
        $this->view('view');
    }

    /**
     * ��ת�������鿴�⳥��ҳ��
     */
    function c_toViewAudit() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        // �ϼ����Ͼ�ֵ
        $rs = $this->service->_db->getArray("SELECT SUM(price) as price,SUM(unitPrice) as unitPrice FROM oa_finance_compensate_detail WHERE mainId = {$_GET['id']} GROUP BY mainId");
        if(!empty($rs)){
            $obj['price'] = $rs[0]['price'];
            $obj['unitPrice'] = $rs[0]['unitPrice'];
        }
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //Դ�����ֲ��Դ���
        $relClass = $this->service->getRelType($obj['relDocType']);
        if ($relClass) {
            $relClassM = new $relClass();//����ʵ��
            $relObj = $this->service->businessGet_d($obj['relDocId'], $relClassM);
            $this->assign('qualityObjType', $relObj['qualityObjType']);
        }
        $this->assign('objTypeName', $this->getDataNameByCode($obj['objType']));
        // �����⳥����
        // $this->showDatadicts(array('dutyType' => 'PCZTLX'), $obj['dutyType']);
        $this->showDatadicts(array('dutyType' => 'PCZTLX'), $obj['dutyType'],array('��ѡ��' => ''));

        $this->view('viewaudit');
    }

    /**
     * ����ȷ��
     */
    function c_toConfirm() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //Դ�����ֲ��Դ���
        $relClass = $this->service->getRelType($obj['relDocType']);
        if ($relClass) {
            $relClassM = new $relClass();//����ʵ��
            $relObj = $this->service->businessGet_d($obj['relDocId'], $relClassM);
            $this->assign('qualityObjType', $relObj['qualityObjType']);
        }
        $this->assign('objTypeName', $this->getDataNameByCode($obj['objType']));
        $this->showDatadicts(array('dutyType' => 'PCZTLX'), $obj['dutyType']);

        $isConfirm = isset($_GET['isConfirm']) ? $_GET['isConfirm'] : 0;//ȷ��״̬
        $this->assign('isConfirm', $isConfirm);

        $this->view('confirm');
    }

    /**
     * ����ȷ��
     */
    function c_confirm() {
        if ($this->service->confirm_d($_POST[$this->objName])) {
            msgRf('ȷ����ɣ�');
        }
    }

    /**
     * ȷ���⳥
     */
    function c_ajaxComConfirm() {
        echo $this->service->comConfirm_d($_POST['id']) ? 1 : 0;
    }

    /**
     * ȡ��ȷ���⳥
     */
    function c_ajaxUnComConfirm() {
        echo $this->service->unComConfirm_d($_POST['id']) ? 1 : 0;
    }

    /**
     * �������
     */
    function c_ajaxAudit() {
        echo $this->service->audit_d($_POST['id']) ? 1 : 0;
    }

    /**
     * ȡ���������
     */
    function c_ajaxUnAudit() {
        echo $this->service->unAudit_d($_POST['id']) ? 1 : 0;
    }

    /**
     * �ر�
     */
    function c_close() {
        echo $this->service->close_d($_POST['id']) ? 1 : 0;
    }

    /**
     * �����⳥��Ϣ
     */
    function c_ajaxUpdateDutyInfo() {
        echo $this->service->updateDutyInfo_d($_POST['id'], $_POST['dutyType'],
            $_POST['dutyObjId'], util_jsonUtil::iconvUTF2GB($_POST['dutyObjName'])) ? 1 : 0;
    }

    /**
     * ���к�
     */
    function c_toSerialNos() {
        $this->assignFunc($_GET);
        $this->view('serialnos');
    }

    /**
     * ��ȡ���к�
     */
    function c_getSerialNos() {
        if ($_POST['id']) {
            //����н��յ�id,ֱ�Ӵ������к�
            $compensateDetailDao = new model_finance_compensate_compensatedetail();
            $compensateDetailArr = $compensateDetailDao->findAll(array('mainId' => $_POST['id'], 'returnequId' => $_POST['returnequId']), null, 'serialIds,serialNos');
            $serialArr = array();
            foreach ($compensateDetailArr as $v) {
                if (empty($v['serialIds'])) continue;
                $serialIdArr = explode(',', $v['serialIds']);
                if (count($serialIdArr) == 1) {
                    array_push($serialArr, array('serialName' => $v['serialNos'], 'serialId' => $v['serialIds']));
                } else {
                    $serialNameArr = explode(',', $v['serialNos']);
                    foreach ($serialIdArr as $ik => $iv) {
                        array_push($serialArr, array('serialName' => $serialNameArr[$ik], 'serialId' => $iv));
                    }
                }
            }
            exit(util_jsonUtil::encode($serialArr));
        } else {
            //Դ�����ֲ��Դ���
            $relClass = $this->service->getRelType($_REQUEST['relDocType']);
            if ($relClass) {
                $relClassM = new $relClass();//����ʵ��
                $relObj = $this->service->getSerialNos_d($_REQUEST, $relClassM);
                $serialArr = array();
                if ($relObj['serialName']) {
                    $serialNameArr = explode(',', $relObj['serialName']);
                    $serialIdArr = explode(',', $relObj['serialId']);
                    foreach ($serialIdArr as $k => $v) {
                        array_push($serialArr, array('serialName' => $serialNameArr[$k], 'serialId' => $v));
                    }
                }
                exit(util_jsonUtil::encode($serialArr));
            } else {
                exit('undefined strategy');
            }
        }
    }

    /**
     * ������
     */
    function c_dealAfterAudit() {
        $this->service->workflowCallBack($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }
    
    /**
     * ��ӡ
     */
    function c_toBatchPrintAlone(){
    	//id��
    	$ids = null;
    	if(isset($_GET['id'])&&!empty($_GET['id'])){
    		$ids = $_GET['id'];
    		$idArr = explode(',',$ids);
    	}else{
    		msg("������ѡ��һ�ŵ��ݴ�ӡ");
    	}
    
    	$this->view('batchprinthead');
    
    	foreach($idArr as $val){
    		$id = is_array($val) ? $val['id'] : $val;
    		$obj = $this->service->get_d($id);
    		foreach ($obj as $key => $val) {
    			$this->assign($key, $val);
    		}
    		$this->assign('objTypeName',$this->getDataNameByCode($obj['objType']));
    		$this->display('print-expand');
    	}
    	$this->assign('ids',$ids);
    	$this->assign('allNum',count($idArr));
    	$this->display('batchprintalone');
    }
    
    /**
     * ��ת��¼��ۿ�ҳ��
     */
    function c_toDeduct() {
    	$this->permCheck(); //��ȫУ��
    	$service = $this->service;
    	$id = $_GET['id'];
    	$obj = $service->get_d($id);
    	foreach ($obj as $key => $val) {
    		$this->assign($key, $val);
    	}
    	$deductMoney = $service->getDeductMoney_d($id);//��¼��ۿ���
    	$remaining = $obj['compensateMoney']-$deductMoney;
    	//ʣ���¼��ۿ���
    	$this->assign('remainingV',$remaining);
    	$this->assign('remaining',number_format($remaining,2));
    	//����ۿʽ
    	$this->showDatadicts(array('payType' => 'KKFS'));
    
    	$this->view('deduct');
    }
    
    /**
     * ¼��ۿ��
     */
    function c_deduct() {
		if ($this->service->deduct_d($_POST[$this->objName])) {
			msg('¼��ɹ���');
		} else {
			msg('¼��ʧ�ܣ�');
		}
    }
}