<?php

/**
 * @author Administrator
 * @Date 2012-06-18 16:25:39
 * @version 1.0
 * @description:����������Ʋ�
 */
class controller_projectmanagent_trialproject_extension extends controller_base_action
{

    function __construct()
    {
        $this->objName = "extension";
        $this->objPath = "projectmanagent_trialproject";
        parent::__construct();
    }

    /*
     * ��ת�����������б�
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * ������Ŀtab-��������
     */
    function c_extensionViewList()
    {
        $this->assign("proId", $_GET['id']);
        $this->view("extensionviewlist");
    }

    /**
     * ��ת��������������ҳ��
     */
    function c_toAdd()
    {
        //������Ŀ��Ϣ
        $proDao = new model_projectmanagent_trialproject_trialproject();
        $obj = $proDao->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //����
        $this->assign("file", $proDao->getFilesByObjId($obj ['id'], false));
        $this->view('add');
    }

    /**
     * ��ת���༭��������ҳ��
     */
    function c_toEdit()
    {
        $this->permCheck(); //��ȫУ��
        //������Ŀ��Ϣ
        $proDao = new model_projectmanagent_trialproject_trialproject();
        $obj = $proDao->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //����
        $this->assign("file", $proDao->getFilesByObjId($obj ['id'], false));
        //�������µ����������¼
        $extensionId = $this->service->findExtensionId($_GET ['id']);
        $objArr = $this->service->get_d($extensionId);
        foreach ($objArr as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('budgetMoney', $obj['budgetMoney']);
// 		if($objArr['budgetMoney']!=NULL){
// 			$this->assign('budgetMoneyEx',$objArr['budgetMoney']);
// 		}
// 		else{
// 			$this->assign('budgetMoneyEx',$obj['budgetMoney']);
// 		}
        //�ж�Ԥ�ƽ���Ƿ�һ���������ͬ��ʾ��ʽ
        if ($objArr['budgetMoney'] == $obj['budgetMoney']) {
            $str = <<<EOT
				{$obj['budgetMoney']}
EOT;
        } else {
            $str = <<<EOT
				<font color="red">{$obj['budgetMoney']} => {$objArr['budgetMoney']}</font>
EOT;
        }
        $this->assign('money', $str);
        $this->view('edit');
    }

    /**
     * ��ת���鿴��������ҳ��
     */
    function c_toView()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //������Ŀ��Ϣ
        $proDao = new model_projectmanagent_trialproject_trialproject();
        $proObj = $proDao->get_d($obj ['trialprojectId']);
        foreach ($proObj as $key => $val) {
            $this->assign($key, $val);
        }
        //����
        $this->assign("file", $proDao->getFilesByObjId($proObj ['id'], false));
        $this->assign('affirmMoneyEx', $obj['affirmMoney']);
// 		if($obj['budgetMoney']!=NULL){
// 			$this->assign('budgetMoneyEx',$obj['budgetMoney']);
// 		}
// 		else{
// 			$this->assign('budgetMoneyEx',$proObj['budgetMoney']);
// 		}
        $this->assign("budgetFile", $this->service->getFilesByObjId($obj ['id'], false));
        //�ж�Ԥ�ƽ���Ƿ�һ���������ͬ��ʾ��ʽ
        if ($proObj['budgetMoney'] == $obj['budgetMoney']) {
            $str = <<<EOT
				{$proObj['budgetMoney']}
EOT;
        } else {
            $str = <<<EOT
				<font color="red">{$proObj['budgetMoney']} => {$obj['budgetMoney']}</font>
EOT;
        }
        $this->assign('trialprojectId', $obj ['trialprojectId']);
        $this->assign('id', $_GET ['id']);
        $this->assign('actType', 'audit');
        $this->assign('money', $str);
        $this->view('view');
    }

    /**
     * ��ʼ������
     */
    function c_init()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if (isset ($_GET ['perm']) && $_GET ['perm'] == 'view') {
            //������Ŀ��Ϣ
            $proDao = new model_projectmanagent_trialproject_trialproject();
            $proObj = $proDao->get_d($obj ['trialprojectId']);

            foreach ($proObj as $key => $val) {
                $this->assign($key, $val);
            }
            $url = "<a href='index1.php?model=projectmanagent_trialproject_trialproject&action=viewTab&id=" . $obj['trialprojectId'] . "&skey=undefined&placeValuesBefore&TB_iframe=true&modal=false&h'  target='_Blank'>" . $proObj['projectCode'] . "</a>";
            //����
            $this->assign("file", $proDao->getFilesByObjId($proObj ['id'], false));
            $this->assign('affirmMoneyEx', $obj['affirmMoney']);
// 			if($obj['budgetMoney']!=NULL){
// 			$this->assign('budgetMoneyEx',$obj['budgetMoney']);
// 			}
// 			else{
// 				$this->assign('budgetMoneyEx',$proObj['budgetMoney']);
// 			}
            $this->assign("budgetFile", $this->service->getFilesByObjId($obj ['id'], false));
            //�ж�Ԥ�ƽ���Ƿ�һ���������ͬ��ʾ��ʽ
            if ($proObj['budgetMoney'] == $obj['budgetMoney']) {
                $str = <<<EOT
				{$proObj['budgetMoney']}
EOT;
            } else {
                $str = <<<EOT
				<font color="red">{$proObj['budgetMoney']} => {$obj['budgetMoney']}</font>
EOT;
            }
            $this->assign("trialprojectId", $obj['trialprojectId']);
            $this->assign("projectCode", $url);
            $this->assign('money', $str);
            $this->view('view');
        } else {
            $this->display('edit');
        }
    }

    /**
     * �����������
     */
    function c_add($isAddInfo = true)
    {
        $rows = $_POST [$this->objName];
        $service = $this->service;
        $extTimes = $service->getExtTime_d($rows['trialprojectId']);
        if (!empty($extTimes)) {
            $rows['extensionTime'] = $extTimes + 1;
        } else {
            $rows['extensionTime'] = 1;
        }
        $id = $service->add_d($rows, $isAddInfo);
        $msg = $_POST ["msg"] ? $_POST ["msg"] : '���ύ�������룬��ȴ�ȷ��';

        //��ȡ������չ�ֶ�ֵ
        $dao = new model_projectmanagent_trialproject_trialproject();
        $trialprojectId = $rows['trialprojectId'];
        $arr = $dao->get_d($trialprojectId);
        $regionDao = new model_system_region_region();
        $expand = $regionDao->getExpandbyId($arr['areaCode']);
        if ($id) {
            if ($expand == '1') {
                succ_show('controller/projectmanagent/trialproject/ewf_indexExtension.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $rows['affirmMoney']);
                msg("��Ŀ���ύ����");
            } else {
                $updateSql = "update oa_trialproject_trialproject set serCon = '3' where id=" . $trialprojectId;
                $service->query($updateSql);
                if (!empty($arr['productLine'])) {
                    //��ȡ�ʼ�����������
                    $toMailArr = $service->toMailArr;
                    $productLine = $arr['productLine'];
                    $toMailId = '';
                    if (strstr($productLine, ',')) { //���ڶ��ִ�в���
                        $productLineArr = explode(',', $productLine);
                        foreach ($productLineArr as $v) {
                            $toMailId = empty($toMailId) ? $toMailArr[$v] : $toMailId . ',' . $toMailArr[$v];
                        }
                    } else {
                        $toMailId = $toMailArr[$productLine];
                    }
                    if ($toMailId) {
                        $service->mailDeal_d('trialprojectExtension', $toMailId, array('projectCode' => $arr['projectCode']));
                    }
                }
            }
            msg($msg);
        } else {
            msg($msg);
        }
    }

    /**
     * �༭
     */
    function c_edit($isAddInfo = true)
    {
        $rows = $_POST [$this->objName];
        $id = $this->service->edit_d($rows, $isAddInfo);
        $msg = $_POST ["msg"] ? $_POST ["msg"] : '�����ɹ���';
        if ($id) {
            succ_show('controller/projectmanagent/trialproject/ewf_indexExtension.php?actTo=ewfSelect&proSid=' . $rows['trialprojectId'] . '&billId=' . $rows['id'] . '&flowMoney=' . $rows['affirmMoney']);
            msg($msg);
        } else {
            msg($msg);
        }
    }

    /**
     * ������Ŀ������������ͨ��������
     */
    function c_confirmExa()
    {
        if (!empty ($_GET ['spid'])) {
        	//�������ص�����
            $this->service->workflowCallBack($_GET['spid']);
        }
        echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_extPageJson()
    {
        $service = $this->service;
        $esmDao = new model_engineering_project_esmproject();

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $rows = $service->page_d();
        foreach ($rows as $k => $v) {
            if ($v['extensionDate'] != "0000-00-00") {
                $rows[$k]['extensionDate'] = "<span class='red'>" . $v['endDateOld'] . "=>" . $v['extensionDate'] . "</span>";
            }
            if (!empty($v['newProjectDays'])) {
                $rows[$k]['newProjectDays'] = "<span class='red'>" . $v['oldProjectDays'] . "=>" . $v['newProjectDays'] . "</span>";
            }

            //��ȡ������Ŀ����
            $esmDao->getParam(array('trialStr' => $v['trialprojectId'], 'contractType' => 'GCXMYD-04', null));
            $trialInfo = $esmDao->list_d('select_defaultAndFee');
            if (!empty($trialInfo)) {
                $rows[$k]['budgetAll'] = $trialInfo[0]['budgetAll'];
                $rows[$k]['feeAllCount'] = $trialInfo[0]['feeAllCount'];
            }
        }
        //���ݼ��밲ȫ����
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    //�鿴tab
    function c_viewTab()
    {
        $obj = $this->service->get_d($_GET ['id']);
        $this->assign("id", $_GET ['id']);
        $this->assign("trialprojectId", $obj['trialprojectId']);
        $this->view("viewTab");
    }
}