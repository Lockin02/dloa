<?php

/**
 * @author Show
 * @Date 2012��10��20�� 15:27:58
 * @version 1.0
 * @description:���������(oa_sale_stampapply)���Ʋ�
 */
class controller_contract_stamp_stampapply extends controller_base_action
{
    private $bindId = "";
    function __construct()
    {
        $this->objName = "stampapply";
        $this->objPath = "contract_stamp";
        $this->bindId = "9199f77f-7b2a-4cc9-adae-dd44799573d9";
        parent :: __construct();
    }

    /**
     * ��ת�����������(oa_sale_stampapply)�б�
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     *  �ҵĸ�������
     */
    function c_myList()
    {
        $this->view('listmy');
    }

    /**
     * �ҵĸ�������json
     */
    function c_myPageJson()
    {
        $service = $this->service;

        $_POST['applyUserId'] = $_SESSION['USER_ID'];
        $service->getParam($_POST);

        //$service->asc = false;
        $rows = $service->page_d();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }
    /******************** ��ɾ�Ĳ� ******************/

    /**
     * ��ת���������������(oa_sale_stampapply)ҳ��
     */
    function c_toAdd()
    {
        $initArr = array(
            'userId' => $_SESSION['USER_ID'],
            'userName' => $_SESSION['USERNAME'],
            'deptId' => $_SESSION['DEPT_ID'],
            'deptName' => $_SESSION['DEPT_NAME'],
            'applyDate' => day_date,
            'attn' => $_SESSION['USERNAME'],
            'attnId' => $_SESSION['USER_ID'],
            'attnDept' => $_SESSION['DEPT_NAME'],
            'attnDeptId' => $_SESSION['DEPT_ID']

        );
        $this->assignFunc($initArr);

        $otherDataDao = new model_common_otherdatas();
        $docUrl = $otherDataDao->getDocUrl($this->bindId);
        $this->assign("docUrl",$docUrl);
        $this->showDatadicts(array('stampExecution' => 'HTGZXZ'), null, true);
        $this->showDatadicts(array('contractType' => 'HTGZYD'), null, true);

        $this->view('add', true);
    }

    //��дadd
    function c_add()
    {
        $this->checkSubmit(); //�����Ƿ��ظ��ύ
        $service = $this->service;
        $object = $_POST[$this->objName];

        // ��ȡ��˾��Ϣ
        $datadictDao = new model_system_datadict_datadict();
        $businessInfo = $datadictDao->getDataDictList_d('QYZT'); //ǩԼ���壨��˾����
        $object['stampCompanyId'] = $object['businessBelongId'];
        $object['stampCompany'] = $businessInfo[$object['businessBelongId']];

        //�Ǻ�ͬ����£���֤�Ƿ���Ҫ������������Ӧ����Ϣ
        if ($object['contractType'] != 'HTGZYD-04') {
            $object = $service->checkNeedAudit($object);
        } else {
            $object['isNeedAudit'] = 1;
        }

        $id = $service->add_d($object);
        if ($id) {
            //�ύʱ�����ж�
            if (isset($_GET['act'])) {
                if ($object['isNeedAudit'] == 0) {
                    $service->sendEmail($object, $id);
                    $service->dealAfterSubmit_d($id); //��ӵ����º�ͬ��
                    msg('�ύ�ɹ�');
                } else {

                    // ����Ǻ�ͬ�������룬�������������̣�������ͨ������
                    if ($object['contractType'] == 'HTGZYD-04') {

                        // �����ͬ�Ѿ���ˣ�����������
                        if ($service->contractIsAudited_d($object['contractId'])) {
                            succ_show('controller/contract/stamp/ewf_indexcontract.php?actTo=ewfSelect&billId=' . $id .
                                '&billDept=' . $object['deptId'] . '&categoryId=' . $object['categoryId'] . '&flowMoney=10');
                        } else {
                            succ_show('controller/contract/stamp/ewf_indexcontract.php?actTo=ewfSelect&billId=' . $id .
                                '&billDept=' . $object['deptId'] . '&categoryId=' . $object['categoryId'] . '&flowMoney=1');
                        }

                    } else { //�Ǻ�ͬ����£�������Ҫ������ʹ������ʱ����Ҫ�ߡ���������������
                        succ_show('controller/contract/stamp/ewf_index.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $object['deptId'] . '&categoryId=' . $object['categoryId']);
                    }
                }
            } else {
                msg('����ɹ�');
            }
        } else {
            if (isset($_GET['act'])) {
                msg('�ύʧ��');
            } else {
                msg('����ʧ��');
            }
        }
    }

    /**
     * ��ת���༭���������(oa_sale_stampapply)ҳ��
     */
    function c_toEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $obj['file'] = $this->service->getFilesByObjId($_GET['id'], true, $this->service->tbl_name);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->showDatadicts(array('stampExecution' => 'HTGZXZ'), $obj['stampExecution'], true);
        $this->showDatadicts(array('categoryId' => 'GZLB'), $obj['categoryId'], false);
        $this->assign('contractTypeCN', $this->getDataNameByCode($obj['contractType']));

        // ��ȡ�Ƿ�˫��ӡˢ�ֶ�����
        $df_select = $y_select = $n_select = '';
        if ($obj['printDoubleSide'] == 'y') {
            $y_select = 'selected';
        } else if ($obj['printDoubleSide'] == 'n') {
            $n_select = 'selected';
        } else {
            $df_select = 'selected';
        }
        $option_str = "<option value='' " . $df_select . ">...��ѡ��...</option>" .
            "<option value='y' " . $y_select . ">��</option>" .
            "<option value='n' " . $n_select . ">��</option>";
        $this->assign('printDoubleSide', $option_str);

        //�ļ�����Ϊ������ͬʱ
        if ($obj['contractType'] == "HTGZYD-04") {
            //����ҵ�񾭰���Ϊ��ǰ��¼��
            $this->assign('attnId', $_SESSION['USER_ID']);
            $this->assign('attn', $_SESSION['USERNAME']);
            $this->assign('attnDeptId', $_SESSION['DEPT_ID']);
            $this->assign('attnDept', $_SESSION['DEPT_NAME']);
            //��ȡ��ͬ������˾
            $contractDao = new model_contract_contract_contract();
            $rs = $contractDao->find(array('id' => $obj['contractId']), null, 'businessBelong');
            if (!empty($rs)) {
                $this->assign('businessBelong', $rs['businessBelong']);
            }
        }

        $otherDataDao = new model_common_otherdatas();
        $docUrl = $otherDataDao->getDocUrl($this->bindId);
        $this->assign("docUrl",$docUrl);
        $this->view('edit');
    }

    /**
     * �޸Ķ���
     */
    function c_edit($isEditInfo = true)
    {
        $service = $this->service;
        $object = $_POST[$this->objName];

        // ��ȡ��˾��Ϣ
        $datadictDao = new model_system_datadict_datadict();
        $businessInfo = $datadictDao->getDataDictList_d('QYZT'); //ǩԼ���壨��˾����
        $object['stampCompanyId'] = $object['businessBelongId'];
        $object['stampCompany'] = $businessInfo[$object['businessBelongId']];

        //�Ǻ�ͬ����£���֤�Ƿ���Ҫ������������Ӧ����Ϣ
        if ($object['contractType'] != 'HTGZYD-04') {
            $object = $service->checkNeedAudit($object);
        } else {
            $object['isNeedAudit'] = 1;
        }

        $rs = $service->edit_d($object);
        if ($rs) {
            $id = $object['id'];
            //�ύʱ�����ж�
            if (isset($_GET['act'])) {
                if ($object['isNeedAudit'] == 0) {
                    $service->sendEmail($object, $id);
                    $service->dealAfterSubmit_d($id); //��ӵ����º�ͬ��
                    msg('�ύ�ɹ�');
                } else {

                    // ����Ǻ�ͬ�������룬�������������̣�������ͨ������
                    if ($object['contractType'] == 'HTGZYD-04') {

                        // �����ͬ�Ѿ���ˣ�����������
                        if ($service->contractIsAudited_d($object['contractId'])) {
                            succ_show('controller/contract/stamp/ewf_indexcontract.php?actTo=ewfSelect&billId=' . $id .
                                '&billDept=' . $object['deptId'] . '&categoryId=' . $object['categoryId'] . '&flowMoney=10');
                        } else {
                            succ_show('controller/contract/stamp/ewf_indexcontract.php?actTo=ewfSelect&billId=' . $id .
                                '&billDept=' . $object['deptId'] . '&categoryId=' . $object['categoryId'] . '&flowMoney=1');
                        }

                    } else { //�Ǻ�ͬ����£�������Ҫ������ʹ������ʱ����Ҫ�ߡ���������������
                        succ_show('controller/contract/stamp/ewf_index.php?actTo=ewfSelect&billId=' . $id .
                            '&billDept=' . $object['deptId'] . '&categoryId=' . $object['categoryId']);
                    }
                }
            } else {
                msg('����ɹ�');
            }
        } else {
            if (isset($_GET['act'])) {
                msg('�ύʧ��');
            } else {
                msg('����ʧ��');
            }
        }
    }

    /**
     * ��ת���鿴���������(oa_sale_stampapply)ҳ��
     */
    function c_toView()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $obj['file'] = $this->service->getFilesByObjId($_GET['id'], false, $this->service->tbl_name);
        $obj['printDoubleSideStr'] = ($obj['printDoubleSide'] == 'y') ? "��" : "��"; //�Ƿ�˫��ӡˢ
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if (isset($_GET['hideBtn'])) {
            $this->assign('hideBtn', 1);
        } else {
            $this->assign('hideBtn', 0);
        }
        $this->assign('contractType', $this->getDataNameByCode($obj['contractType']));
        $this->assign('stampExecution', $this->getDataNameByCode($obj['stampExecution']));
        $this->assign('categoryId', $this->getDataNameByCode($obj['categoryId']));
        $this->assign('status', $this->service->rtStampType_d($obj['status']));
        $this->view('view');
    }

    /**
     * ��ת���������������
     */
    function c_toAudit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $obj['file'] = $this->service->getFilesByObjId($_GET['id'], false, $this->service->tbl_name);
        $obj['printDoubleSideStr'] = ($obj['printDoubleSide'] == 'y') ? "��" : "��"; //�Ƿ�˫��ӡˢ
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('contractType', $this->getDataNameByCode($obj['contractType']));
        $this->assign('stampExecution', $this->getDataNameByCode($obj['stampExecution']));
        $this->assign('categoryId', $this->getDataNameByCode($obj['categoryId']));
        $this->assign('status', $this->service->rtStampType_d($obj['status']));
        $this->view('audit');
    }

    /**
     * ������ɺ���ת
     */
    function c_dealAfterAudit()
    {
        $this->service->workflowCallBack($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    //�޸�����״̬
    function c_changeStatus()
    {
        return $this->service->update(array('id' => $_POST['id']), array('ExaStatus' => '���'));
    }

    //�����ʼ�
    function c_toSend()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $obj['file'] = $this->service->getFilesByObjId($_GET['id'], true, $this->service->tbl_name);
        $obj['printDoubleSideStr'] = ($obj['printDoubleSide'] == 'y') ? "��" : "��"; //�Ƿ�˫��ӡˢ
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->showDatadicts(array('stampExecution' => 'HTGZXZ'), $obj['stampExecution'], true);
        $this->assign('contractTypeCN', $this->getDataNameByCode($obj['contractType']));
        $this->view('send');
    }

    //ajax�ύ����
    function c_ajaxStamp()
    {
        $service = $this->service;
        $id = $_POST['id'];
        //���¸�����������״̬
        $obj = array(
            'id' => $id,
            'ExaStatus' => '���',
            'ExaDT' => day_date
        );
        if ($service->updateById($obj)) {
            //�ڸ����б������Ϣ
            $service->dealAfterSubmit_d($id);
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * �жϺ�ͬ�Ƿ�������� - ���ۺ�ͬר��
     */
    function c_contractIsAudited()
    {
        echo $this->service->contractIsAudited_d($_POST['contractId']);
    }
}