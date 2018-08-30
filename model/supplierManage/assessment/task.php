<?php
/**
 * @author Administrator
 * @Date 2012��6��28�� ������ 16:55:01
 * @version 1.0
 * @description:��Ӧ���������� Model��
 */
class model_supplierManage_assessment_task extends model_base
{

    //״̬λ
    private $state;

    public $statusDao; //״̬��

    function __construct()
    {
        $this->tbl_name = "oa_supp_suppasses_task";
        $this->sql_map = "supplierManage/assessment/taskSql.php";
        parent::__construct();

        $this->state = array(
            0 => array(
                "stateEName" => "begin",
                "stateCName" => "������",
                "stateVal" => "0"
            ),
            1 => array(
                "stateEName" => "execute",
                "stateCName" => "�ѽ���",
                "stateVal" => "1"
            ),
            2 => array(
                "stateEName" => "end",
                "stateCName" => "������",
                "stateVal" => "2"
            ),
            3 => array(
                "stateEName" => "close",
                "stateCName" => "�ر�",
                "stateVal" => "3"
            )
        );

        $this->statusDao = new model_common_status();
        $this->statusDao->status = array(
            0 => array(
                "statusEName" => "begin",
                "statusCName" => "������",
                "key" => "0"
            ),
            1 => array(
                "statusEName" => "execute",
                "statusCName" => "�ѽ���",
                "key" => "1"
            ),
            2 => array(
                "statusEName" => "end",
                "statusCName" => "������",
                "key" => "2"
            ),
            3 => array(
                "statusEName" => "close",
                "statusCName" => "�ر�",
                "key" => "3"
            )
        );

        //���ó�ʼ�����������
        parent::setObjAss();
    }

    //��˾Ȩ�޴��� TODO
    protected $_isSetCompany = 0; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

    /*****************************************��ʾ�ָ���********************************************/

    /**
     * ͨ��value����״̬
     */
    function stateToVal($stateVal)
    {
        $returnVal = false;
        foreach ($this->state as $key => $val) {
            if ($val['stateVal'] == $stateVal) {
                $returnVal = $val['stateCName'];
            }
        }
        //TODO:����쳣����
        return $returnVal;
    }

    /**
     * ͨ��״̬����value
     */
    function stateToSta($stateSta)
    {
        $returnVal = false;
        foreach ($this->state as $key => $val) {
            if ($val['stateEName'] == $stateSta) {
                $returnVal = $val['stateVal'];
            }
        }
        //TODO:����쳣����
        return $returnVal;
    }

    /*****************************************��ʾ�ָ���********************************************/

    /**
     * ��дadd
     */
    function add_d($object)
    {
        //���������ֵ��ֶ�
        $datadictDao = new model_system_datadict_datadict ();
        $object ['assessTypeName'] = $datadictDao->getDataNameByCode($object['assessType']);
        $object['state'] = $this->statusDao->statusEtoK('begin');
        $id = parent::add_d($object, true);

        //�����ʼ�֪ͨ�ɹ�������
        $emailArr = array();
        $emailArr['issend'] = 'y';
        $emailArr['TO_ID'] = $object['assesManId'];
        $emailArr['TO_NAME'] = $object['assesManName'];
        if ($emailArr) {
            $addmsg .= '���������ţ�<font color=blue><b>' . $object["formCode"] . '</b></font><br>';
            $addmsg .= '������Ӧ�̣�<font color=blue><b>' . $object["suppName"] . '</b></font><br>';
            $addmsg .= '�������ͣ�<font color=blue><b>' . $object["assessTypeName"] . '</b></font><br>';
            $addmsg .= '�´�ʱ�䣺<font color=blue><b>' . $object["formDate"] . '</b></font><br>';
            $addmsg .= 'ϣ�����ʱ�䣺<font color=blue><b>' . $object["hopeDate"] . '</b></font><br>';
            $addmsg .= '��ע��<font color=blue><b>' . $object["approvalRemark"] . '</b></font>';
            $emailDao = new model_common_mail();
            $emailInfo = $emailDao->emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '�����µĹ�Ӧ����������', '', $emailArr['TO_ID'], $addmsg, 1);

        }
        return $id;
    }

    /**
     *�����ʼ�֪ͨ
     *
     */
    function sendEmail_d($object)
    {
        //�����ʼ�֪ͨ�ɹ�������
        $emailArr = array();
        $emailArr['issend'] = 'y';
        $emailArr['TO_ID'] = $object['assesManId'];
        $emailArr['TO_NAME'] = $object['assesManName'];
        if ($emailArr) {
            $addmsg .= '���������ţ�<font color=blue><b>' . $object["formCode"] . '</b></font><br>';
            $addmsg .= '������Ӧ�̣�<font color=blue><b>' . $object["suppName"] . '</b></font><br>';
            $addmsg .= '�������ͣ�<font color=blue><b>' . $object["assessTypeName"] . '</b></font><br>';
            $addmsg .= '�´�ʱ�䣺<font color=blue><b>' . $object["formDate"] . '</b></font><br>';
            $addmsg .= 'ϣ�����ʱ�䣺<font color=blue><b>' . $object["hopeDate"] . '</b></font><br>';
            $addmsg .= '��ע��<font color=blue><b>' . $object["approvalRemark"] . '</b></font>';
        }
        $emailDao = new model_common_mail();
        $emailInfo = $emailDao->emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '�����µĹ�Ӧ����������', '', $emailArr['TO_ID'], $addmsg, 1);

    }

    /**
     * ��дadd
     */
    function edit_d($object)
    {

        //���������ֵ��ֶ�
        $datadictDao = new model_system_datadict_datadict ();
        $object ['assessTypeName'] = $datadictDao->getDataNameByCode($object['assessType']);

        $row = $this->get_d($object['id']);
        if ($row['assesManId'] != $object['assesManId']) { //��������˲�ͬ�������ʼ�
            $object['state'] = 0;
        }

        //�����ʼ�֪ͨ�ɹ�������
        $emailArr = array();
        $emailArr['issend'] = 'y';
        $emailArr['TO_ID'] = $object['assesManId'];
        $emailArr['TO_NAME'] = $object['assesManName'];
        if ($emailArr) {
            $addmsg .= '���������ţ�<font color=blue><b>' . $row["formCode"] . '</b></font><br>';
            $addmsg .= '������Ӧ�̣�<font color=blue><b>' . $row["suppName"] . '</b></font><br>';
            $addmsg .= '�������ͣ�<font color=blue><b>' . $row["assessTypeName"] . '</b></font><br>';
            $addmsg .= '�´�ʱ�䣺<font color=blue><b>' . $row["formDate"] . '</b></font><br>';
            $addmsg .= 'ϣ�����ʱ�䣺<font color=blue><b>' . $object["hopeDate"] . '</b></font><br>';
            $addmsg .= '��ע��<font color=blue><b>' . $object["approvalRemark"] . '</b></font>';
            $emailDao = new model_common_mail();
            $emailInfo = $emailDao->emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '�����µĹ�Ӧ����������', '', $emailArr['TO_ID'], $addmsg, 1);

        }

        return parent::edit_d($object, true);
    }

    /**�жϹ�Ӧ�̸ü����Ƿ����´�����
     * @author suxc
     *
     */
    function isAssesQuarter_d($suppId)
    {
        $month = date("n");
        switch ($month) {
            case "1":
                $beginDate = date("Y") . "-01";
                $endDate = date("Y") . "-03";
                break;
            case "2":
                $beginDate = date("Y") . "-01";
                $endDate = date("Y") . "-03";
                break;
            case "3":
                $beginDate = date("Y") . "-01";
                $endDate = date("Y") . "-03";
                break;
            case "4":
                $beginDate = date("Y") . "-04";
                $endDate = date("Y") . "-06";
                break;
            case "5":
                $beginDate = date("Y") . "-04";
                $endDate = date("Y") . "-06";
                break;
            case "6":
                $beginDate = date("Y") . "-04";
                $endDate = date("Y") . "-06";
                break;
            case "7":
                $beginDate = date("Y") . "-07";
                $endDate = date("Y") . "-09";
                break;
            case "8":
                $beginDate = date("Y") . "-07";
                $endDate = date("Y") . "-09";
                break;
            case "9":
                $beginDate = date("Y") . "-07";
                $endDate = date("Y") . "-09";
                break;
            case "10":
                $beginDate = date("Y") . "-10";
                $endDate = date("Y") . "-12";
                break;
            case "11":
                $beginDate = date("Y") . "-10";
                $endDate = date("Y") . "-12";
                break;
            case "12":
                $beginDate = date("Y") . "-10";
                $endDate = date("Y") . "-12";
                break;
        }
        //��ȡ��Ӧ�̼��ȿ���������Ϣ
        $searchArr = array(
            "beginDate" => $beginDate,
            "endDate" => $endDate,
            "assessType" => "gysjd",
            "suppId" => $suppId
        );
        $this->__SET('searchArr', $searchArr);
        $rows = $this->listBySqlId("select_taskinfo");
        if (is_array($rows)) {
            return 0;
        } else {
            return 1;
        }

    }

    /**�жϹ�Ӧ�̸ü����Ƿ��ѽ�����ȿ���
     * @author suxc
     *
     */
    function isAssesYear_d($suppId,$assesYear=null)
    {
        if($assesYear!=null){
            $assesYear=$assesYear;
        }else{
            $assesYear = date("Y"); //�������
        }

        //��ȡ��Ӧ����ȿ�����Ϣ
        $searchArr = array(
            "assesYear" => $assesYear,
            "assessType" => "gysnd",
            "suppId" => $suppId
        );
        $this->__SET('searchArr', $searchArr);
        $rows = $this->listBySqlId("select_taskinfo");
        if (is_array($rows)) {
            return 0;
        } else {
            return 1;
        }

    }

    /**�жϹ�Ӧ�̸ü����Ƿ��ѽ�����ȿ���
     * @author suxc
     *
     */
    function isAssesNew_d($suppId)
    {
        //��ȡ��Ӧ���¹�Ӧ�̿�����Ϣ
        $searchArr = array(
            "assessType" => "xgyspg",
            "suppId" => $suppId
        );
        $this->__SET('searchArr', $searchArr);
        $rows = $this->listBySqlId("select_taskinfo");
        if (is_array($rows)) {
            return 0;
        } else {
            return 1;
        }

    }

    /**

     */

    /**�ı䵥��״̬
     */
    function accepTask_d($id, $state)
    {
        $object['id'] = $id;
        $object['state'] = $state;
        $flag = parent::edit_d($object, true);
        if ($flag) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * ��֤��Ӧ�̵ļ��ȿ��������Ƿ��Ѵ���
     * @param $suppId ��Ӧ��ID
     * @param $assesYear �������
     * @param $assesQuarter ���˼���
     */
    function checkData_d($suppId, $assesYear, $assesQuarter)
    {
        $searchArr = array(
            "assessType" => "gysjd",
            "suppId" => $suppId,
            "assesYear" => $assesYear,
            "assesQuarter" => $assesQuarter
        );
        $this->__SET('searchArr', $searchArr);
        $rows = $this->listBySqlId("select_taskinfo");
        if (is_array($rows)) {
            return 0;
        } else {
            return 1;
        }
    }
}

?>