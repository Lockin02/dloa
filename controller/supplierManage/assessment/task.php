<?php
/**
 * @author Administrator
 * @Date 2012��6��28�� ������ 16:55:01
 * @version 1.0
 * @description:��Ӧ������������Ʋ�
 */
class controller_supplierManage_assessment_task extends controller_base_action
{

    function __construct()
    {
        $this->objName = "task";
        $this->objPath = "supplierManage_assessment";
        parent::__construct();
    }

    /**
     * ��ת����Ӧ�����������б�
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * ��ת����Ӧ�����������б�(�����)
     */
    function c_toCloseList()
    {
        $this->view('close-list');
    }

    /**
     * ��ת����Ӧ�����������б�(Tab)
     */
    function c_toListTab()
    {
        $this->view('tab');
    }

    /**
     * ��ת���ҵĹ�Ӧ�����������б�
     */
    function c_toMyTab()
    {
        $this->view('mytab');
    }

    /**
     * ��ת���ҵĹ�Ӧ�����������б�(δ���)
     */
    function c_toMyList()
    {
        $this->view('my-list');
    }

    /**
     * ��ת���ҵĹ�Ӧ�����������б�(�����)
     */
    function c_toMyCloseList()
    {
        $this->view('myclose-list');
    }

    /**
     * ��ת��������Ӧ����������ҳ��
     */
    function c_toAdd()
    {
        $this->assign('formDate', date("Y-m-d"));
        $taskNumb = "T" . date("YmdHis"); //����������
        $this->assign('formCode', $taskNumb);
        $this->assign('purchManName', $_SESSION ['USERNAME']); //�����´���
        $this->assign('purchManId', $_SESSION ['USER_ID']);
        //��ȡ�����ֵ�
        $this->showDatadicts(array('assessType' => 'FALX'));
        $thisYear = date('Y');
        $yearStr = "";
        for ($i = $thisYear; $i >= 2005; $i--) {
            $yearStr .= "<option value=$i>" . $i . "��</option>";
        }
        $this->assign('assesYear', $yearStr);
        $this->assign('quarterStr', "<option value=1>��1����</option><option value=2>��2����</option><option value=3>��3����</option><option value=4>��4����</option>");
        if (isset ($_GET ['thisYear'])) {
            $initArr = $_GET;
        } else {
            $thisQuarter = intval((date('m') + 2) / 3);

            $initArr = array(
                'thisYear' => $thisYear,
                'thisQuarter' => $thisQuarter
            );
        }
        $this->assignFunc($initArr);
        $this->view('add', true);
    }

    /**
     * ��ת��������Ӧ����������ҳ��
     */
    function c_toAddBySupp()
    {
        $suppId = isset ($_GET ['suppId']) ? $_GET ['suppId'] : "";
        $assesType = isset ($_GET ['assesType']) ? $_GET ['assesType'] : "";
        $flibraryDao = new model_supplierManage_formal_flibrary();
        //��ȡ��Ӧ����Ϣ
        $suppRow = $flibraryDao->get_d($suppId);
        $this->assign('suppId', $suppId);
        $this->assign('suppName', $suppRow['suppName']);
        $this->assign('assessType', $assesType);
        $this->assign('assessTypeName', $this->getDataNameByCode($assesType));
        $this->assign('formDate', date("Y-m-d"));
        $taskNumb = "T" . date("YmdHis"); //����������
        $this->assign('formCode', $taskNumb);
        $this->assign('purchManName', $_SESSION ['USERNAME']); //�����´���
        $this->assign('purchManId', $_SESSION ['USER_ID']);
        //��ȡ�����ֵ�
//	$this->showDatadicts(array ('assessType' => 'FALX'));
        $thisYear = date('Y');
        $yearStr = "";
        for ($i = $thisYear; $i >= 2005; $i--) {
            $yearStr .= "<option value=$i>" . $i . "��</option>";
        }
        $this->assign('assesYear', $yearStr);
        $this->assign('quarterStr', "<option value=1>��1����</option><option value=2>��2����</option><option value=3>��3����</option><option value=4>��4����</option>");
        if (isset ($_GET ['thisYear'])) {
            $initArr = $_GET;
        } else {
            $thisQuarter = intval((date('m') + 2) / 3);

            $initArr = array(
                'thisYear' => $thisYear,
                'thisQuarter' => $thisQuarter
            );
        }

        $this->assignFunc($initArr);
        $this->view('supp-add', true);
    }

    /**
     * ��ת���༭��Ӧ����������ҳ��
     */
    function c_toEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * ��ת���鿴��Ӧ����������ҳ��
     */
    function c_toView()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
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
        $this->assign("assesQuarter",$assesQuarter);

        $this->view('view');
    }

    /**���������б����ʾ����
     */
    function c_pageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->asc = true;
//		$rows = $service->page_d ();
        $rows = $service->pageBySqlId();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                //ת��������
                $rows[$key]['stateName'] = $service->statusDao->statusKtoC($rows[$key]['state']);
            }

        }
        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**�ҵ����������б����ʾ����
     */
    function c_myPageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->asc = true;
        $service->searchArr['assesManId'] = $_SESSION['USER_ID'];
//		$rows = $service->page_d ();
        $rows = $service->pageBySqlId();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                //ת��������
                $rows[$key]['stateName'] = $service->statusDao->statusKtoC($rows[$key]['state']);
            }

        }
        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��������
     *
     */
    function c_add()
    {
        $this->checkSubmit(); //��֤�Ƿ��ظ��ύ
        $id = $this->service->add_d($_POST[$this->objName]);
        if ($id) {
            msg('�´�ɹ�');

        } else {
            msg('�´�ʧ��');

        }
    }

    /**
     * �������
     *
     */
    function c_edit()
    {
//	 	$row=$this->service->get_d($_POST[$this->objName]['id']);
//	 	if($row['assesManId']!=$_POST[$this->objName]['assesManId']){//��������˲�ͬ����״̬��Ϊ������
//	 		$_POST[$this->objName]['state']=0;
//	 	}
        $id = $this->service->edit_d($_POST[$this->objName]);
        if ($id) {
            msg('����ɹ�');

        } else {
            msg('���ʧ��');

        }
    }

    /**��������
     */
    function c_accepTask()
    {
        $id = isset($_POST['id']) ? $_POST['id'] : exit;
        $state = isset($_POST['state']) ? $_POST['state'] : exit;
        $flag = $this->service->accepTask_d($id, $state);
        //���ɾ���ɹ����1���������0
        if ($flag) {
            echo 1;
        } else {
            echo 0;
        }

    }

    /**��֤�ù�Ӧ���Ƿ����´�����
     * @author suxc
     *
     */
    function c_isTask()
    {
        $suppId = $_POST['suppId'];
        $supassType = $_POST['supassType'];
        if ($suppId) {
            switch ($supassType) {
                case "gysjd":
                    $flag = $this->service->isAssesQuarter_d($suppId);
                    break; //���ȿ���
                case "gysnd":
                    $flag = $this->service->isAssesYear_d($suppId);
                    break; //��ȿ���
                case "xgyspg":
                    $flag = $this->service->isAssesNew_d($suppId);
                    break; //�¹�Ӧ������
            }
            echo $flag;
        }
    }

    /**
     * ��֤��Ӧ�̵ļ��ȿ��������Ƿ��Ѵ���
     * @author suxc
     */
    function c_checkData()
    {
        $suppId = $_POST['suppId']; //��Ӧ��ID
        $assesYear = $_POST['assesYear']; //�������
        $assesQuarter = $_POST['assesQuarter']; //���˼���
        $assessType = $_POST['assessType']; //��������
        if ($assessType == "gysjd") {
            $falg = $this->service->checkData_d($suppId, $assesYear, $assesQuarter);
        } else if ($assessType == "gysnd") {
            $falg = $this->service->isAssesYear_d($suppId, $assesYear);
        }
        echo $falg;
    }
}

?>