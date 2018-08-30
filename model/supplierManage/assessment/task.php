<?php
/**
 * @author Administrator
 * @Date 2012年6月28日 星期四 16:55:01
 * @version 1.0
 * @description:供应商评估任务 Model层
 */
class model_supplierManage_assessment_task extends model_base
{

    //状态位
    private $state;

    public $statusDao; //状态类

    function __construct()
    {
        $this->tbl_name = "oa_supp_suppasses_task";
        $this->sql_map = "supplierManage/assessment/taskSql.php";
        parent::__construct();

        $this->state = array(
            0 => array(
                "stateEName" => "begin",
                "stateCName" => "待接收",
                "stateVal" => "0"
            ),
            1 => array(
                "stateEName" => "execute",
                "stateCName" => "已接收",
                "stateVal" => "1"
            ),
            2 => array(
                "stateEName" => "end",
                "stateCName" => "已评估",
                "stateVal" => "2"
            ),
            3 => array(
                "stateEName" => "close",
                "stateCName" => "关闭",
                "stateVal" => "3"
            )
        );

        $this->statusDao = new model_common_status();
        $this->statusDao->status = array(
            0 => array(
                "statusEName" => "begin",
                "statusCName" => "待接收",
                "key" => "0"
            ),
            1 => array(
                "statusEName" => "execute",
                "statusCName" => "已接收",
                "key" => "1"
            ),
            2 => array(
                "statusEName" => "end",
                "statusCName" => "已评估",
                "key" => "2"
            ),
            3 => array(
                "statusEName" => "close",
                "statusCName" => "关闭",
                "key" => "3"
            )
        );

        //调用初始化对象关联类
        parent::setObjAss();
    }

    //公司权限处理 TODO
    protected $_isSetCompany = 0; # 单据是否要区分公司,1为区分,0为不区分

    /*****************************************显示分割线********************************************/

    /**
     * 通过value查找状态
     */
    function stateToVal($stateVal)
    {
        $returnVal = false;
        foreach ($this->state as $key => $val) {
            if ($val['stateVal'] == $stateVal) {
                $returnVal = $val['stateCName'];
            }
        }
        //TODO:添加异常操作
        return $returnVal;
    }

    /**
     * 通过状态查找value
     */
    function stateToSta($stateSta)
    {
        $returnVal = false;
        foreach ($this->state as $key => $val) {
            if ($val['stateEName'] == $stateSta) {
                $returnVal = $val['stateVal'];
            }
        }
        //TODO:添加异常操作
        return $returnVal;
    }

    /*****************************************显示分割线********************************************/

    /**
     * 重写add
     */
    function add_d($object)
    {
        //处理数据字典字段
        $datadictDao = new model_system_datadict_datadict ();
        $object ['assessTypeName'] = $datadictDao->getDataNameByCode($object['assessType']);
        $object['state'] = $this->statusDao->statusEtoK('begin');
        $id = parent::add_d($object, true);

        //发送邮件通知采购负责人
        $emailArr = array();
        $emailArr['issend'] = 'y';
        $emailArr['TO_ID'] = $object['assesManId'];
        $emailArr['TO_NAME'] = $object['assesManName'];
        if ($emailArr) {
            $addmsg .= '评估任务编号：<font color=blue><b>' . $object["formCode"] . '</b></font><br>';
            $addmsg .= '评估供应商：<font color=blue><b>' . $object["suppName"] . '</b></font><br>';
            $addmsg .= '评估类型：<font color=blue><b>' . $object["assessTypeName"] . '</b></font><br>';
            $addmsg .= '下达时间：<font color=blue><b>' . $object["formDate"] . '</b></font><br>';
            $addmsg .= '希望完成时间：<font color=blue><b>' . $object["hopeDate"] . '</b></font><br>';
            $addmsg .= '备注：<font color=blue><b>' . $object["approvalRemark"] . '</b></font>';
            $emailDao = new model_common_mail();
            $emailInfo = $emailDao->emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '你有新的供应商评估任务', '', $emailArr['TO_ID'], $addmsg, 1);

        }
        return $id;
    }

    /**
     *发送邮件通知
     *
     */
    function sendEmail_d($object)
    {
        //发送邮件通知采购负责人
        $emailArr = array();
        $emailArr['issend'] = 'y';
        $emailArr['TO_ID'] = $object['assesManId'];
        $emailArr['TO_NAME'] = $object['assesManName'];
        if ($emailArr) {
            $addmsg .= '评估任务编号：<font color=blue><b>' . $object["formCode"] . '</b></font><br>';
            $addmsg .= '评估供应商：<font color=blue><b>' . $object["suppName"] . '</b></font><br>';
            $addmsg .= '评估类型：<font color=blue><b>' . $object["assessTypeName"] . '</b></font><br>';
            $addmsg .= '下达时间：<font color=blue><b>' . $object["formDate"] . '</b></font><br>';
            $addmsg .= '希望完成时间：<font color=blue><b>' . $object["hopeDate"] . '</b></font><br>';
            $addmsg .= '备注：<font color=blue><b>' . $object["approvalRemark"] . '</b></font>';
        }
        $emailDao = new model_common_mail();
        $emailInfo = $emailDao->emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '您有新的供应商评估任务', '', $emailArr['TO_ID'], $addmsg, 1);

    }

    /**
     * 重写add
     */
    function edit_d($object)
    {

        //处理数据字典字段
        $datadictDao = new model_system_datadict_datadict ();
        $object ['assessTypeName'] = $datadictDao->getDataNameByCode($object['assessType']);

        $row = $this->get_d($object['id']);
        if ($row['assesManId'] != $object['assesManId']) { //如果负责人不同，则发送邮件
            $object['state'] = 0;
        }

        //发送邮件通知采购负责人
        $emailArr = array();
        $emailArr['issend'] = 'y';
        $emailArr['TO_ID'] = $object['assesManId'];
        $emailArr['TO_NAME'] = $object['assesManName'];
        if ($emailArr) {
            $addmsg .= '评估任务编号：<font color=blue><b>' . $row["formCode"] . '</b></font><br>';
            $addmsg .= '评估供应商：<font color=blue><b>' . $row["suppName"] . '</b></font><br>';
            $addmsg .= '评估类型：<font color=blue><b>' . $row["assessTypeName"] . '</b></font><br>';
            $addmsg .= '下达时间：<font color=blue><b>' . $row["formDate"] . '</b></font><br>';
            $addmsg .= '希望完成时间：<font color=blue><b>' . $object["hopeDate"] . '</b></font><br>';
            $addmsg .= '备注：<font color=blue><b>' . $object["approvalRemark"] . '</b></font>';
            $emailDao = new model_common_mail();
            $emailInfo = $emailDao->emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '您有新的供应商评估任务', '', $emailArr['TO_ID'], $addmsg, 1);

        }

        return parent::edit_d($object, true);
    }

    /**判断供应商该季度是否已下达任务
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
        //获取供应商季度考核任务信息
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

    /**判断供应商该季度是否已进行年度考核
     * @author suxc
     *
     */
    function isAssesYear_d($suppId,$assesYear=null)
    {
        if($assesYear!=null){
            $assesYear=$assesYear;
        }else{
            $assesYear = date("Y"); //评估年份
        }

        //获取供应商年度考核信息
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

    /**判断供应商该季度是否已进行年度考核
     * @author suxc
     *
     */
    function isAssesNew_d($suppId)
    {
        //获取供应商新供应商考核信息
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

    /**改变单据状态
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
     * 验证供应商的季度考核任务是否已存在
     * @param $suppId 供应商ID
     * @param $assesYear 考核年份
     * @param $assesQuarter 考核季度
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