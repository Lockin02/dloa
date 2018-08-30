<?php

/**
 * @author Administrator
 * @Date 2012��1��12�� 15:55:18
 * @version 1.0
 * @description:��Ӧ���������Ʋ�
 */
class controller_supplierManage_assessment_supasses extends controller_base_action
{

    function __construct()
    {
        $this->objName = "supasses";
        $this->objPath = "supplierManage_assessment";
        parent::__construct();
    }

    /*
     * ��ת����Ӧ�������б�
     */
    function c_page()
    {
        $this->view('list');
    }

    /*
     * ��ת���ҷ���Ĺ�Ӧ�������б�
     */
    function c_toMyList()
    {
        $this->view('my-list');
    }

    /*
     * ��ת��������Ӧ�������б�
     */
    function c_toSuppList()
    {
        $suppId = isset ($_GET ['suppId']) ? $_GET ['suppId'] : null;
        $this->assign('suppId', $suppId);
        $this->view('supp-list');
    }

    /*
     * ��ת���Ҳ���Ĺ�Ӧ�������б�
     */
    function c_toMyJoinList()
    {
        $this->view('myjoin-list');
    }

    /*
 * ��ת����Ӧ�����������б�
 */
    function c_toAssesList()
    {
        $this->view('asses-list');
    }

    /**
     * �ҷ���Ĺ�Ӧ�������б�Json
     */
    function c_myListJson()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        $service->searchArr ['assesManId'] = $_SESSION ['USER_ID'];
        //$service->asc = false;
        $rows = $service->page_d();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * �Ҳ���Ĺ�Ӧ�������б�Json
     */
    function c_myJoinListJson()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        $service->searchArr ['myjoinId'] = $_SESSION ['USER_ID'];
        //$service->asc = false;
        $rows = $service->page_d();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��Ӧ�����������б�Json
     */
    function c_assesListJson()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        $ids = $service->affirmUserInfo($_SESSION ['USER_ID']);
        $service->searchArr['ids'] = $ids;
        $service->searchArr['ExaStatusNot'] = 'δ�ύ';
        //$service->asc = false;
        $rows = $service->page_d("select_assesList");
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['pageSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��ת��������Ӧ������ҳ��
     */
    function c_toAdd()
    {
        $this->assign('assesManName', $_SESSION ['USERNAME']); //�����
        $this->assign('assesManId', $_SESSION ['USER_ID']);
        //��ȡ�����ֵ�
        $this->showDatadicts(array(
            'assessType' => 'FALX'
        ));
        $this->view('add', true);
    }

    /**
     * ��ת���༭��Ӧ������ҳ��
     */
    function c_toEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //����С���Ա
        $menberDao = new model_supplierManage_assessment_assessmentmenber();
        $menberRows = $menberDao->getMenberByParentId($_GET ['id']);
        $menberStr = "";
        $menberIdStr = "";
        if (is_array($menberRows)) {
            $menberArr = array();
            $menberIdArr = array();
            foreach ($menberRows as $key => $val) {
                $menberArr[] = $val['assesManName'];
                $menberIdArr[] = $val['assesManId'];
            }
            $menberStr = implode(",", $menberArr);
            $menberIdStr = implode(",", $menberIdArr);
        }
        $this->assign("file", $this->service->getFilesByObjId($_GET ['id']));
        if ($obj['isFirst'] == 2) {
            $this->assign('isFirstView', '�ڶ�������');
        } else {
            $this->assign('isFirstView', '����');
        }
        $this->assign('menberStr', $menberStr);
        $this->assign('menberIdStr', $menberIdStr);
        $this->view('edit', true);
    }

    /**
     * ��ת����Ӧ����������ҳ��
     */
    function c_toAsses()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //����С���Ա
        $menberDao = new model_supplierManage_assessment_assessmentmenber();
        $menberRows = $menberDao->getMenberByParentId($_GET ['id']);
        $menberStr = "";
        $menberIdStr = "";
        if (is_array($menberRows)) {
            $menberArr = array();
            $menberIdArr = array();
            foreach ($menberRows as $key => $val) {
                $menberArr[] = $val['assesManName'];
                $menberIdArr[] = $val['assesManId'];
            }
            $menberStr = implode(",", $menberArr);
            $menberIdStr = implode(",", $menberIdArr);
        }
        $this->assign("file", $this->service->getFilesByObjId($_GET ['id']), false);
        if ($obj['isFirst'] == 2) {
            $this->assign('isFirstView', '�ڶ�������');
        } else {
            $this->assign('isFirstView', '����');
        }
        if ($obj['assessType'] == "gysjd") {
            switch ($obj['assesQuarter']) {
                case 1:
                    $assesQuarter = "��һ����";
                    break;
                case 2:
                    $assesQuarter = "�ڶ�����";
                    break;
                case 3:
                    $assesQuarter = "��������";
                    break;
                case 4:
                    $assesQuarter = "���ļ���";
                    break;
                default:
                    $assesQuarter = "";
            }

        } else {
            $assesQuarter = "";

        }
        $this->assign("assesQuarter", $assesQuarter);
        $this->assign("assesQuarterCode", $obj['assesQuarter']);
        $this->assign('menberStr', $menberStr);
        $this->assign('menberIdStr', $menberIdStr);
        $this->view('asses', true);
    }

    /**
     * ��ת�����ι�Ӧ������ҳ��
     */
    function c_toSecondAss()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //����С���Ա
        $menberDao = new model_supplierManage_assessment_assessmentmenber();
        $menberRows = $menberDao->getMenberByParentId($_GET ['id']);
        $menberStr = "";
        $menberIdStr = "";
        if (is_array($menberRows)) {
            $menberArr = array();
            $menberIdArr = array();
            foreach ($menberRows as $key => $val) {
                $menberArr[] = $val['assesManName'];
                $menberIdArr[] = $val['assesManId'];
            }
            $menberStr = implode(",", $menberArr);
            $menberIdStr = implode(",", $menberIdArr);
        }
        $this->assign('menberStr', $menberStr);
        $this->assign('menberIdStr', $menberIdStr);
        $this->view('second-ass');
    }

    /**
     * �Ӻϸ����ת����Ӧ������ҳ��
     */
    function c_toQuarterAss()
    {
//   		$this->permCheck (); //��ȫУ��
        $suppId = isset ($_GET ['suppId']) ? $_GET ['suppId'] : "";
        $assesType = isset ($_GET ['assesType']) ? $_GET ['assesType'] : "";
        $taskId = isset ($_GET ['taskId']) ? $_GET ['taskId'] : "";//��������ID
        $taskCode = isset ($_GET ['taskCode']) ? $_GET ['taskCode'] : "";
        $flibraryDao = new model_supplierManage_formal_flibrary();
        //��ȡ��Ӧ����Ϣ
        $suppRow = $flibraryDao->get_d($suppId);
        //��ȡ��ϵ������
        $linkManDao = new model_supplierManage_formal_sfcontact();
        $linkManRow = $linkManDao->conInSupp($suppId);
        $taskDao = new model_supplierManage_assessment_task();
        $taskRow = $taskDao->get_d($taskId);
        $this->assign('suppId', $suppId);
        $this->assign('suppName', $suppRow['suppName']);
        $this->assign('suppLinkName', $linkManRow['0']['name']);
        $this->assign('suppTel', $suppRow['plane']);
        $this->assign('suppAddress', $suppRow['address'] . " " . $suppRow['zipCode']);
        $this->assign('mainProduct', $suppRow['products']);
        $this->assign('assesManName', $_SESSION ['USERNAME']); //�����
        $this->assign('assesManId', $_SESSION ['USER_ID']);
        $this->assign('suppName', $suppRow['suppName']);
        $this->assign('assessType', $assesType);
        $this->assign('taskId', $taskId);
        $this->assign('taskCode', $taskCode);
        if ($assesType == "gysjd") {
            $this->assign('assesQuarter', $taskRow['assesQuarter']);
        } else {
            $this->assign('assesQuarter', "");
        }
        $this->assign('assesYear', $taskRow['assesYear']);
        $this->assign('assessTypeName', $this->getDataNameByCode($assesType));
        $this->view('quarter-ass', false);
    }

    /**
     * ��ת���鿴��Ӧ������ҳ��
     */
    function c_toView()
    {
        $this->permCheck(); //��ȫУ��
        $viewType = isset ($_GET ['viewType']) ? $_GET ['viewType'] : "";
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if ($obj['isFirst'] == 2) {
            $this->assign('isFirstName', '�ڶ�������');
        } else {
            $this->assign('isFirstName', '����');
        }
        //����С���Ա
        $menberDao = new model_supplierManage_assessment_assessmentmenber();
        $menberRows = $menberDao->getMenberByParentId($_GET ['id']);
        $menberStr = "";
        if (is_array($menberRows)) {
            $menberArr = array();
            foreach ($menberRows as $key => $val) {
                $menberArr[] = $val['assesManName'];
            }
            $menberStr = implode(",", $menberArr);
        }
        $this->assign('menberStr', $menberStr);
        if ($obj['assessType'] == "gysjd") {
            switch ($obj['assesQuarter']) {
                case 1:
                    $assesQuarter = "��һ����";
                    break;
                case 2:
                    $assesQuarter = "�ڶ�����";
                    break;
                case 3:
                    $assesQuarter = "��������";
                    break;
                case 4:
                    $assesQuarter = "���ļ���";
                    break;
                default:
                    $assesQuarter = "";
            }

        } else {
            $assesQuarter = "";

        }
        $this->assign("assesQuarter", $assesQuarter);

        $this->assign("file", $this->service->getFilesByObjId($_GET ['id'], false));//����
        $assessType = $this->getDataNameByCode($obj ['assessType']);
        $skey = $this->md5Row($obj['suppId'], 'supplierManage_formal_flibrary');
        $this->assign('skey', $skey);//��Ӧ��KEY
        $this->assign('assesKey', $this->md5Row($obj['parentId'], 'supplierManage_assessment_supasses'));//����KEY
        $this->assign('assessTypeName', $assessType);
        $this->assign('viewType', $viewType);
        $this->view('view');
    }

    /**��ת����Ӧ�̼��ȿ��˱���
     * @author evan
     *
     */
    function c_toQuarterReport()
    {
        $beginYear = isset ($_GET ['beginYear']) ? $_GET ['beginYear'] : "";
        $beginQuarter = isset ($_GET ['beginQuarter']) ? $_GET ['beginQuarter'] : "";
        $endYear = isset ($_GET ['endYear']) ? $_GET ['endYear'] : "";
        $endQuarter = isset ($_GET ['endQuarter']) ? $_GET ['endQuarter'] : "";
        $supplierId = isset ($_GET ['supplierId']) ? $_GET ['supplierId'] : "";
        $this->assign('beginYear', $beginYear);
        $this->assign('beginQuarter', $beginQuarter);
        $this->assign('endYear', $endYear);
        $this->assign('endQuarter', $endQuarter);
        $this->assign('suppId', $supplierId);
        $this->view('quarter-report');
    }

    /**��ת����Ӧ�̿��˻��ܱ���
     * @author evan
     *
     */
    function c_toReport()
    {
        $beginYear = isset ($_GET ['beginYear']) ? $_GET ['beginYear'] : date("Y");
        $endYear = isset ($_GET ['endYear']) ? $_GET ['endYear'] : date("Y");
        $suppName = isset ($_GET ['suppName']) ? $_GET ['suppName'] : "";
        $this->assign('beginYear', $beginYear);
        $this->assign('endYear', $endYear);
        $this->assign('suppName', $suppName);
        $this->view('report');
    }

    /**��ת����Ӧ�̼��ȿ��˱�������ҳ��
     * @author evan
     *
     */
    function c_toReportSearch()
    {
        $this->assign('beginYear', date("Y"));
        $this->assign('endYear', date("Y"));
        $this->view('search');
    }

    /**��ת����Ӧ�̼��ȿ��˱�������ҳ��
     * @author evan
     *
     */
    function c_toQuarterReportSearch()
    {
        $this->assign('beginYear', date("Y"));
        $this->assign('endYear', date("Y"));
        $this->view('quarter-search');
    }

    /**��ת����Ӧ����ȿ��˱���
     * @author evan
     *
     */
    function c_toYearReport()
    {
        $beginYear = isset ($_GET ['beginYear']) ? $_GET ['beginYear'] : "";
        $endYear = isset ($_GET ['endYear']) ? $_GET ['endYear'] : "";
        $supplierId = isset ($_GET ['supplierId']) ? $_GET ['supplierId'] : "";
        $this->assign('beginYear', $beginYear);
        $this->assign('endYear', $endYear);
        $this->assign('suppId', $supplierId);
        $this->view('year-report');
    }

    /**��ת����Ӧ�̼��ȿ��˱�������ҳ��
     * @author evan
     *
     */
    function c_toYearReportSearch()
    {
        $this->assign('beginYear', date("Y"));
        $this->assign('endYear', date("Y"));
        $this->view('year-search');
    }

    /**
     * ���湩Ӧ������
     *
     */
    function c_add()
    {
        $this->checkSubmit(); //��֤�Ƿ��ظ��ύ
        $actType = isset ($_GET ['actType']) ? $_GET ['actType'] : null;
        $type = isset ($_GET ['type']) ? $_GET ['type'] : null;
        if ("audit" == $actType) {
            $_POST[$this->objName]['ExaStatus'] = '������';
        } else {
            $_POST[$this->objName]['ExaStatus'] = 'δ�ύ';
        }
        $id = $this->service->add_d($_POST[$this->objName],$actType);
        if ($id) {
            if ($type == "passSupp") {
                if ("audit" == $actType) {//�ύ������
//					if($_POST[$this->objName]['taskId']>0){
//						switch($_POST[$this->objName]['assessType']){
//							case "xgyspg":succ_show ( 'controller/supplierManage/assessment/ewf_new_index4.php?actTo=ewfSelect&billId='.$id);break;
//							case "gysjd":succ_show ( 'controller/supplierManage/assessment/ewf_quarter_index4.php?actTo=ewfSelect&billId='.$id);break;
//							case "gysnd":succ_show ( 'controller/supplierManage/assessment/ewf_year_index4.php?actTo=ewfSelect&billId='.$id);break;
//						}
//
//					}else{
//						switch($_POST[$this->objName]['assessType']){
//							case "xgyspg":succ_show ( 'controller/supplierManage/assessment/ewf_new_index3.php?actTo=ewfSelect&billId='.$id);break;
//							case "gysjd":succ_show ( 'controller/supplierManage/assessment/ewf_quarter_index3.php?actTo=ewfSelect&billId='.$id);break;
//							case "gysnd":succ_show ( 'controller/supplierManage/assessment/ewf_year_index3.php?actTo=ewfSelect&billId='.$id);break;
//						}
//					}

                    if ($_POST[$this->objName]['taskId'] > 0) {
                        msgGo('�ύ�ɹ�', "?model=supplierManage_assessment_task&action=toMyTab");

                    } else if ($_POST[$this->objName]['assessType'] == "xgyspg") {
                        msgGo('�ύ�ɹ�', "?model=supplierManage_formal_flibrary&action=toOtherSupplist");
                    } else {
                        msgGo('�ύ�ɹ�', "?model=supplierManage_formal_flibrary&action=toPassSupplist");
                    }
                } else {
                    if ($_POST[$this->objName]['taskId'] > 0) {
                        msgGo('����ɹ�', "?model=supplierManage_assessment_task&action=toMyTab");

                    } else if ($_POST[$this->objName]['assessType'] == "xgyspg") {
                        msgGo('����ɹ�', "?model=supplierManage_formal_flibrary&action=toOtherSupplist");
                    } else {
                        msgGo('����ɹ�', "?model=supplierManage_formal_flibrary&action=toPassSupplist");
                    }
                }

            } else {
//				if ("audit" == $actType) {//�ύ������
//					switch($_POST[$this->objName]['assessType']){
//						case "xgyspg":succ_show ( 'controller/supplierManage/assessment/ewf_new_index.php?actTo=ewfSelect&billId='.$id);break;
//						case "gysjd":succ_show ( 'controller/supplierManage/assessment/ewf_quarter_index.php?actTo=ewfSelect&billId='.$id);break;
//						case "gysnd":succ_show ( 'controller/supplierManage/assessment/ewf_year_index.php?actTo=ewfSelect&billId='.$id);break;
//					}
//				} else {
                msgGo('����ɹ�', "?model=supplierManage_assessment_supasses&action=toAdd");
//				}

            }
        } else {
            if ($type = "passSupp") {
                if ($_POST[$this->objName]['taskId'] > 0) {
                    msgGo('����ɹ�', "?model=supplierManage_assessment_task&action=toMyTab");

                } else if ($_POST[$this->objName]['assessType'] == "xgyspg") {
                    msgGo('����ʧ��', "?model=supplierManage_formal_flibrary&action=toOtherSupplist");
                } else {
                    msgGo('����ʧ��', "?model=supplierManage_formal_flibrary&action=toPassSupplist");
                }
            } else {
                msgGo('����ʧ��', "?model=supplierManage_assessment_supasses&action=toAdd");
            }

        }
    }

    /**
     * �༭��Ӧ������
     *
     */
    function c_edit()
    {
        $this->checkSubmit(); //��֤�Ƿ��ظ��ύ
        $actType = isset ($_GET ['actType']) ? $_GET ['actType'] : null;
        if ("audit" == $actType) {
            $_POST[$this->objName]['ExaStatus'] = '������';
        } else {
            $_POST[$this->objName]['ExaStatus'] = 'δ�ύ';
        }
        $id = $this->service->edit_d($_POST[$this->objName],$actType);
        if ($id) {
            if ("audit" == $actType) {//�ύ������
//                switch($_POST[$this->objName]['assessType']){
//                    case "xgyspg":succ_show ( 'controller/supplierManage/assessment/ewf_new_index2.php?actTo=ewfSelect&billId='.$_POST[$this->objName]['id']);break;
//                    case "gysjd":succ_show ( 'controller/supplierManage/assessment/ewf_quarter_index2.php?actTo=ewfSelect&billId='.$_POST[$this->objName]['id']);break;
//                    case "gysnd":succ_show ( 'controller/supplierManage/assessment/ewf_year_index2.php?actTo=ewfSelect&billId='.$_POST[$this->objName]['id']);break;
//                }

                msgGo('�ύ�ɹ�', "?model=supplierManage_assessment_supasses&action=toMyList");
            } else {
                msgGo('����ɹ�', "?model=supplierManage_assessment_supasses&action=toMyList");
            }
        } else {

        }
    }

    /**
     * ����������Ӧ��
     *
     */
    function c_addSecondAss()
    {
        $id = $this->service->addSecondAss_d($_POST[$this->objName]);
        $actType = isset ($_GET ['actType']) ? $_GET ['actType'] : null;
        if ($id) {
            if ("audit" == $actType) {//�ύ������
                switch ($_POST[$this->objName]['assessType']) {
                    case "xgyspg":
                        succ_show('controller/supplierManage/assessment/ewf_new_index2.php?actTo=ewfSelect&billId=' . $id);
                        break;
                    case "gysjd":
                        succ_show('controller/supplierManage/assessment/ewf_quarter_index2.php?actTo=ewfSelect&billId=' . $id);
                        break;
                    case "gysnd":
                        succ_show('controller/supplierManage/assessment/ewf_year_index2.php?actTo=ewfSelect&billId=' . $id);
                        break;
                }
            } else {
                msgGo('����ɹ�', "?model=supplierManage_assessment_supasses&action=toMyList");
            }
        } else {

        }
    }

    /**
     * ��Ӧ����������������
     *
     */
    function c_dealSuppass()
    {
        if (!empty ($_GET ['spid'])) {
            //�������ص�����
            $this->service->workflowCallBack($_GET['spid']);
        }
        if ($_GET['urlType']) {
            echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";

        } else {

            //��ֹ�ظ�ˢ��,���������תҳ��
            echo "<script>this.location='?model=purchase_contract_purchasecontract&action=pcApprovalNo'</script>";
        }
    }

    /**��֤�ù�Ӧ���Ƿ��ѽ��п���
     * @author suxc
     *
     */
    function c_isAsses()
    {
        $suppId = $_POST['suppId'];
        $supassType = $_POST['supassType'];
        if ($suppId) {
            switch ($supassType) {
                case "gysjd":
                    $flag = $this->service->isAssesQuarter_d($suppId);
                    break;//���ȿ���
                case "gysnd":
                    $flag = $this->service->isAssesYear_d($suppId);
                    break;//��ȿ���
                case "xgyspg":
                    $flag = $this->service->isAssesNew_d($suppId);
                    break;//�¹�Ӧ������
            }
            echo $flag;
        }
    }

    /**ɾ������
     */
    function c_deletesInfo()
    {
        $deleteId = isset($_POST['id']) ? $_POST['id'] : exit;
        $delete = $this->service->deletesInfo_d($deleteId);
        //���ɾ���ɹ����1���������0
        if ($delete) {
            echo 1;
        } else {
            echo 0;
        }

    }

    /**�ύ����
     */
    function c_sumbitAsses()
    {
        $id = isset($_POST['id']) ? $_POST['id'] : exit;
        $flag = $this->service->sumbitAsses_d($id);
        if ($flag) {
            echo 1;
        } else {
            echo 0;
        }

    }

    /**
     * ����������Ϣexcelҳ��
     */
    function c_toExcelIn()
    {
        $this->display('excelin');
    }

    /**
     * ����������Ϣexcel
     */
    function c_excelIn()
    {
        $resultArr = $this->service->addFistData_d();

        $title = '������Ϣ�������б�';
        $thead = array('������Ϣ', '������');
        echo util_excelUtil::showResult($resultArr, $title, $thead);
    }

    /**
     * �༭��Ӧ����������
     *
     */
    function c_asses()
    {
//        $this->checkSubmit(); //��֤�Ƿ��ظ��ύ
        $isDoneFlag = $this->service->asses_d($_POST[$this->objName]);
        if ($isDoneFlag) {
            //��ȡ���Ÿ�����
            $leaderIds = $this->service->getDeptLeader($_POST[$this->objName]['id']);
            switch ($_POST[$this->objName]['assessType']) {
                case "xgyspg":
                    succ_show('controller/supplierManage/assessment/ewf_new_index2.php?actTo=ewfSelect&billId=' . $_POST[$this->objName]['id'] . '&billUser=' . $leaderIds);
                    break;
                case "gysjd":
                    succ_show('controller/supplierManage/assessment/ewf_quarter_index2.php?actTo=ewfSelect&billId=' . $_POST[$this->objName]['id'] . '&billUser=' . $leaderIds);
                    break;
                case "gysnd":
                    succ_show('controller/supplierManage/assessment/ewf_year_index2.php?actTo=ewfSelect&billId=' . $_POST[$this->objName]['id'] . '&billUser=' . $leaderIds);
                    break;
            }
        } else {
            msgGo('�ύ�ɹ�', "?model=supplierManage_assessment_supasses&action=toAssesList");
        }
    }
}