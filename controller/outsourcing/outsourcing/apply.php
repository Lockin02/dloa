<?php

/**
 * @author Administrator
 * @Date 2013��9��14�� 15:49:00
 * @version 1.0
 * @description:����������Ʋ�
 */
class controller_outsourcing_outsourcing_apply extends controller_base_action
{

    function __construct()
    {
        $this->objName = "apply";
        $this->objPath = "outsourcing_outsourcing";
        parent::__construct();
    }

    function c_index()
    {
        $this->service->setCompany(0); # �����б�,����Ҫ���й�˾����
        $this->view('list');
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->setCompany(0);

        //$service->asc = false;
        $rows = $service->page_d ();
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

    function c_indexData()
    {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

        $service->searchArr['createId'] = $_SESSION['USER_ID'];
        $rows = $service->pageBySqlId('select_default');
        $arr = array();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil:: encode($arr);
    }

    function c_alllist()
    {
        $this->view('listall');
    }

    //������������б�
    function c_toDealList()
    {
        $this->view('deal-list');
    }

    /**
     * ��Ŀ����б�
     */
    function c_pageForProject()
    {
        $projectId = isset($_GET['projectId']) && $_GET['projectId'] ? $_GET['projectId'] : die("undefined project id");
        $this->assign('projectId', $projectId);
        $this->assign('createId', $_SESSION['USER_ID']);
        $this->view('pageForProject');
    }

    /**
     * ��ת��������������ҳ��
     */
    function c_toAdd()
    {
        $this->assign('datenow', date("Y-m-d H:i:s"));
        $this->assign('usernow', $_SESSION['USERNAME']);

        $this->view('add', true);
    }

    /**
     * ��Ŀ����������
     */
    function c_toAddFromProject()
    {
        $projectId = isset($_GET['projectId']) && $_GET['projectId'] ? $_GET['projectId'] : die("undefined project id");
        $this->assign('projectId', $projectId);

        // ��ȡ��Ŀ������
        $projectDao = new model_engineering_project_esmproject();
        $project = $projectDao->get_d($projectId);
        $project = $projectDao->feeDeal($project);
        $project = $projectDao->contractDeal($project);
        $this->assignFunc($project);

        $this->assign('datenow', date("Y-m-d H:i:s"));
        $this->assign('usernow', $_SESSION['USERNAME']);
        $this->view('addFromProject', true);
    }

    /**
     * ��������¼�����ת���б�ҳ
     */
    function c_add()
    {
        $this->checkSubmit(); //��֤�Ƿ��ظ��ύ
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
        $applyId = $this->service->add_d($_POST['apply']);
        if ($applyId) {
            if ($actType != '') {
                $provinceId = $this->service->get_table_fields('oa_esm_project', "id=" . $_POST['apply']['projectId'], 'provinceId');
                $areaId = $this->service->get_table_fields('oa_esm_office_range', "proId=" . $provinceId, 'id');
                succ_show('controller/outsourcing/outsourcing/ewf_index.php?actTo=ewfSelect&billId=' . $applyId . '&billArea=' . $areaId);
            } else {
                msg('����ɹ���');
            }
        } else {
            msg('����ʧ�ܣ�');
        }
    }

    /**
     * �ж��Ƿ��������
     */
    function c_isExemptReview() {
    	$officeId = $this->service->get_table_fields('oa_esm_project', "id=" . $_POST['apply']['projectId'], 'officeId');
    	$mainManagerId = $this->service->get_table_fields('oa_esm_office_range', "officeId=" . $officeId, 'mainManagerId');
//     	$mainManagerId = $this->service->_db->get_one("SELECT mainManagerId FROM oa_esm_office_range where id='$areaId'");
    	//explode(',',$source);
    	if(empty($mainManagerId) == false && in_array($_SESSION['USER_ID'] , explode(',',$mainManagerId)) ) {
    		echo "1";
    	} else {
    		echo "0";
    	}
    }
    
    /**
     * ���󷽷�
     */
    function c_exemptReview() {
    	$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
    	$applyId = $this->service->add_d($_POST['apply']);
    	$isSuccess = false;
    	if ($applyId) {
    		$object["id"] = $applyId;
    		$isSuccess = $this->service->exemptReview($object);
    	}
    	msg('������ɣ�');
    }
    
    /**
     * ���󷽷����༭��
     */
    function c_exemptReviewByEdit() {
    	$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
    	$this->service->edit_d($_POST['apply']);
    	$object["id"] = $_POST['apply']["id"];
    	$this->service->exemptReview($object);
    	msg('������ɣ�');
    }
    
    /**
     * ���󷽷����б�
     */
    function c_exemptReviewByList() {
    	$object["id"] = $_POST['apply']["id"];
    	$this->service->exemptReview($object);
    	echo "1";
    }
    
    /**
     * ��ת���༭��������ҳ��
     */
    function c_toEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);

        $obj['notEnough'] == 1 ? ($obj['notEnough'] = "checked='checked'") : ($obj['notEnough'] = "");
        $obj['notSkill'] == 1 ? ($obj['notSkill'] = "checked='checked'") : ($obj['notSkill'] = "");
        $obj['notCost'] == 1 ? ($obj['notCost'] = "checked='checked'") : ($obj['notCost'] = "");
        $obj['notMart'] == 1 ? ($obj['notMart'] = "checked='checked'") : ($obj['notMart'] = "");
        $obj['notElse'] == 1 ? ($obj['notElse'] = "checked='checked'") : ($obj['notElse'] = "");

        $obj['outType1'] = "";
        $obj['outType2'] = "";
        $obj['outType3'] = "";
        if ($obj['outType'] == 3) {
            $obj['outType3'] = "checked='checked'";
        } else if ($obj['outType'] == 2) {
            $obj['outType2'] = "checked='checked'";
        } else {
            $obj['outType1'] = "checked='checked'";
        }
        $this->assignFunc($obj);
        $this->view('edit', true);
    }

    /**
     * �༭
     * @param bool|false $isEditInfo
     * @throws Exception
     */
    function c_edit($isEditInfo = false)
    {
        $this->checkSubmit(); //��֤�Ƿ��ظ��ύ
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
        $applyId = $this->service->edit_d($_POST['apply']);
        if ($applyId) {
            if ($actType != '') {
                $provinceId = $this->service->get_table_fields('oa_esm_project', "id=" . $_POST['apply']['projectId'], 'provinceId');
                $areaId = $this->service->get_table_fields('oa_esm_office_range', "proId=" . $provinceId, 'id');
                succ_show('controller/outsourcing/outsourcing/ewf_index.php?actTo=ewfSelect&billId=' . $_POST['apply']['id'] . '&billArea=' . $areaId);
            } else {
                msg('����ɹ���');
            }
        } else {
            msg('����ʧ�ܣ�');
        }
    }

    /**
     * ��ת���鿴��������ҳ��
     */
    function c_toView()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_REQUEST ['id']);
        $obj['notEnough'] == 1 ? ($obj['notEnough'] = "checked='checked'") : ($obj['notEnough'] = "");
        $obj['notSkill'] == 1 ? ($obj['notSkill'] = "checked='checked'") : ($obj['notSkill'] = "");
        $obj['notCost'] == 1 ? ($obj['notCost'] = "checked='checked'") : ($obj['notCost'] = "");
        $obj['notMart'] == 1 ? ($obj['notMart'] = "checked='checked'") : ($obj['notMart'] = "");
        $obj['notElse'] == 1 ? ($obj['notElse'] = "checked='checked'") : ($obj['notElse'] = "");

        $obj['outType1'] = "";
        $obj['outType2'] = "";
        $obj['outType3'] = "";
        if ($obj['outType'] == 3) {
            $obj['outType3'] = "checked='checked'";
        } else if ($obj['outType'] == 2) {
            $obj['outType2'] = "checked='checked'";
        } else {
            $obj['outType1'] = "checked='checked'";
        }
        $this->assignFunc($obj);
        //������ʾ����
        $actType = isset($_GET['actType']) ? $_GET['actType'] : '';
        $this->assign('actType', $actType);
        $this->assign("file", $this->service->getFilesByObjId($_GET ['id'], false));
        $this->view('view');
    }

    /**
     * ��ת�������������ҳ��
     */
    function c_toBackApply()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_REQUEST ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('back', true);
    }

    /**
     * �������
     * @throws Exception
     */
    function c_backApply()
    {
        $this->checkSubmit(); //��֤�Ƿ��ظ��ύ
        $applyId = $this->service->backApply_d($_POST['apply']);
        if ($applyId) {
            msg('��سɹ���');
        } else {
            msg('���ʧ�ܣ�');
        }
    }

    /**
     * ��ת�������������ҳ��
     */
    function c_toBackView()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_REQUEST ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('back-view');
    }

    /**
     * ��ת���ر���������ҳ��
     */
    function c_toCloseApply()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_REQUEST['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('close', true);
    }

    /**
     * �������
     * @throws Exception
     */
    function c_closeApply()
    {
        $this->checkSubmit(); //��֤�Ƿ��ظ��ύ
        $applyId = $this->service->closeApply_d($_POST['apply']);
        if ($applyId) {
            msg('�رճɹ���');
        } else {
            msg('�ر�ʧ�ܣ�');
        }
    }

    /**
     * ��ת�������������ҳ��
     */
    function c_toCloseView()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_REQUEST['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('close-view');
    }

    /**
     * ����
     */
    function c_deal()
    {
        echo $this->service->deal_d($_POST['id']);
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_pageJsonDeal()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->setCompany(0);
        $service->groupBy = 'c.id';
        $rows = $service->page_d("select_deal_list");
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                if ($val['personSum'] < 5) {
                    if ($val['outType'] == 1) {//����
                        if ($val['nature'] == 'GCYHL') {// �Ż�
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +8 day"));
                        } else if ($val['nature'] == 'GCPGL') {//����
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +6 day"));
                        }
                    } else if ($val['outType'] == 3) {//��Ա����
                        if ($val['nature'] == 'GCYHL') {// �Ż�
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +4 day"));
                        } else if ($val['nature'] == 'GCPGL') {//����
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +2 day"));
                        }
                    }
                } else if ($val['personSum'] < 10) {
                    if ($val['outType'] == 1) {//����
                        if ($val['nature'] == 'GCYHL') {// �Ż�
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +10 day"));
                        } else if ($val['nature'] == 'GCPGL') {//����
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +8 day"));
                        }
                    } else if ($val['outType'] == 3) {//����
                        if ($val['nature'] == 'GCYHL') {// �Ż�
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +6 day"));
                        } else if ($val['nature'] == 'GCPGL') {//����
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +4 day"));
                        }
                    }
                } else {
                    if ($val['outType'] == 1) {//����
                        if ($val['nature'] == 'GCYHL') {// �Ż�
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +12 day"));
                        } else if ($val['nature'] == 'GCPGL') {//����
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +10 day"));
                        }
                    } else if ($val['outType'] == 3) {//����
                        if ($val['nature'] == 'GCYHL') {// �Ż�
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +8 day"));
                        } else if ($val['nature'] == 'GCPGL') {//����
                            $rows[$key]['predictDate'] = date("Y-m-d H:i:s", strtotime($val['ExaDT'] . " +6 day"));
                        }
                    }
                }
            }
        }
        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * tabҳ��ַ
     */
    function c_viewTab()
    {
        $this->assignFunc($_GET);
        $this->view("viewTab");
    }

    /**
     * ������ɵ��÷���
     */
    function c_dealAfterAudit()
    {
        $this->service->dealAfterAudit_d($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }
}