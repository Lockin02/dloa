<?php

/**
 * @author Administrator
 * @Date 2013年9月17日 11:30:50
 * @version 1.0
 * @description:外包申请表 Model层
 */
class model_outsourcing_outsourcing_apply extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_outsourcing_apply";
        $this->sql_map = "outsourcing/outsourcing/applySql.php";
        parent::__construct();
    }

    //公司权限处理 TODO
    protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分

    function add_d($object)
    {
        try {
            $this->start_d();

            //自动生成系统编号和表单状态
            $codeRuleDao = new model_common_codeRule();
            $object['applyCode'] = $codeRuleDao->commonCode2('外包申请', 'WBSQ', 'WBSQ', $_SESSION['DEPT_ID']);

            //获取归属公司名称
            $object['formBelong'] = $_SESSION['USER_COM'];
            $object['formBelongName'] = $_SESSION['USER_COM_NAME'];
            $object['businessBelong'] = $_SESSION['USER_COM'];
            $object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

            //修改主表信息
            $id = parent:: add_d($object, true);

            if ($object['outType'] == 3) {
                $personModel = new model_outsourcing_outsourcing_person();
                if ($object['person']) {
                    foreach ($object['person'] as $key => $arr) {
                        $arr['applyId'] = $id;
                        $personModel->add_d($arr);
                    }
                }
            }

            //更新附件关联关系
            $this->updateObjWithFile($id);

            //附件处理
            if (isset ($_POST ['fileuploadIds']) && is_array($_POST ['fileuploadIds'])) {
                $uploadFile = new model_file_uploadfile_management ();
                $uploadFile->updateFileAndObj($_POST ['fileuploadIds'], $id);
            }

            $this->commit_d();
            return $id;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    function edit_d($object)
    {
        $object['notEnough'] = $object['notEnough'] ? $object['notEnough'] : "0";
        $object['notElse'] = $object['notElse'] ? $object['notElse'] : "0";
        $object['notSkill'] = $object['notSkill'] ? $object['notSkill'] : "0";
        $object['notCost'] = $object['notCost'] ? $object['notCost'] : "0";
        $object['notMart'] = $object['notMart'] ? $object['notMart'] : "0";

        try {
            $this->start_d();

            //修改主表信息
            $id = parent:: edit_d($object, true);

            $personModel = new model_outsourcing_outsourcing_person();
            $personModel->delete(array('applyId' => $object['id']));
            if ($object['outType'] == 3) {
                if (is_array($object['person'])) {
                    foreach ($object['person'] as $key => $arr) {
                        unset($arr['id']);
                        $arr['applyId'] = $object['id'];
                        $personModel->add_d($arr);
                    }
                }
            }
            //更新附件关联关系
            $this->updateObjWithFile($object['id']);
            $this->commit_d();
            return $id;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }
	
	/**
	 * 免审
	 * @param unknown $object
	 * @return boolean 
	 */
	function exemptReview($object) {
		try {
			$this->start_d();
			$object['state'] = '1';
			$object['exaDT'] = date('Y-m-d H:i:s',time());;
			$object['exaStatus'] = '完成';
			//修改主表信息
			parent :: edit_d($object, true);
			$this->commit_d();
			return true;
		} catch (exception $e) {
			return false;
		}
	}

    /**打回申请*/
    function backApply_d($object)
    {
        try {
            $object['state'] = '0';
            $object['cancelManId'] = $_SESSION['USER_ID'];
            $object['cancelManName'] = $_SESSION['USERNAME'];
            //修改主表信息
            $id = parent:: edit_d($object, true);

            //发送邮件通知外包申请流相关人员
            if ($id) {
                $this->searchArr = array('id' => $object['id']);
                $this->setCompany(0);
                $arr = $this->listBySqlId('select_deal_list');
                $obj = $arr[0];
                $receiverId = $this->getMailReceiver_d($object['id']);
                $emailDao = new model_common_mail();
                $mailContent = '您好！此邮件为外包申请打回通知，详细信息如下：<br>' .
                    '单据编号：<span style="color:blue">' . $obj['applyCode'] .
                    '</span><br>归属区域：<span style="color:blue">' . $obj['officeName'] .
                    '</span><br>项目省份：<span style="color:blue">' . $obj['province'] .
                    '</span><br>项目名称：<span style="color:blue">' . $obj['projecttName'] .
                    '</span><br>项目编号：<span style="color:blue">' . $obj['projectCode'] .
                    '</span><br>申请人：<span style="color:blue">' . $obj['createName'] .
                    '</span><br>申请时间：<span style="color:blue">' . $obj['createTime'] .
                    '</span><br>打回原因:<span style="color:blue">' . $object['cancelReason'] .
                    '</span>';
                $emailDao->mailClear("外包申请打回", $receiverId, $mailContent);
            }
            return $id;
        } catch (exception $e) {
            return false;
        }
    }

    /**
     * 关闭
     * @param $object
     * @return bool
     */
    function closeApply_d($object)
    {
        try {
            $object['state'] = '5';
            $object['cancelManId'] = $_SESSION['USER_ID'];
            $object['cancelManName'] = $_SESSION['USERNAME'];
            //修改主表信息
            $id = parent:: edit_d($object, true);

            //发送邮件通知外包申请流相关人员
            if ($id) {
                $this->searchArr = array('id' => $object['id']);
                $this->setCompany(0);
                $arr = $this->listBySqlId('select_deal_list');
                $obj = $arr[0];
                $receiverId = $this->getMailReceiver_d($object['id']);
                $emailDao = new model_common_mail();
                $mailContent = '您好！此邮件为外包申请关闭通知，详细信息如下：<br>' .
                    '单据编号：<span style="color:blue">' . $obj['applyCode'] .
                    '</span><br>归属区域：<span style="color:blue">' . $obj['officeName'] .
                    '</span><br>项目省份：<span style="color:blue">' . $obj['province'] .
                    '</span><br>项目名称：<span style="color:blue">' . $obj['projecttName'] .
                    '</span><br>项目编号：<span style="color:blue">' . $obj['projectCode'] .
                    '</span><br>申请人：<span style="color:blue">' . $obj['createName'] .
                    '</span><br>申请时间：<span style="color:blue">' . $obj['createTime'] .
                    '</span><br>关闭原因:<span style="color:blue">' . $object['closeReason'] .
                    '</span>';
                $emailDao->mailClear("关闭外包申请", $receiverId, $mailContent);
            }
            return $id;
        } catch (exception $e) {
            return false;
        }
    }

    /*
     * 根据外包申请id
     * 获取外包申请流相关人员
     */
    function getMailReceiver_d($id)
    {
        $obj = $this->get_d($id);
        $receiverId = '';
        $receiverId .= $obj['createId'];//申请人
        include(WEB_TOR . "model/common/mailConfig.php");
        $mailOperations = isset($mailUser['oa_outsourcing_operations']) ? $mailUser['oa_outsourcing_operations'] : "";
        $receiverId .= ',' . $mailOperations['TO_ID'];//服务运营部
        $mailInterface = $mailUser['oa_outsourcing_interface'];
        $receiverId .= ',' . $mailInterface['TO_ID'];//外包接口人
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectObj = $esmprojectDao->get_d($obj['projectId']);
        $receiverId .= ',' . $this->get_table_fields('oa_system_province_info', "id=" . $esmprojectObj['provinceId'], 'esmManagerId');//服务经理
        $receiverId .= ',' . $this->get_table_fields('oa_esm_office_baseinfo', "id=" . $esmprojectObj['officeId'], 'mainManagerId');//服务总监
        return $receiverId;
    }

    /**
     * 申请受理
     * @param $id
     * @return bool
     */
    function deal_d($id)
    {

        try {
            $object = array(
                'id' => $id,
                'state' => 6
            );
            //修改主表信息
            $id = parent::edit_d($object, true);

            //发送邮件通知外包申请流相关人员
            if ($id) {
                $obj = $this->get_d($id);
                $emailDao = new model_common_mail();
                $mailContent = '您好！此邮件为外包申请受理通知，详细信息如下：<br>' .
                    '单据编号：<span style="color:blue">' . $obj['applyCode'] .
                    '</span><br>归属区域：<span style="color:blue">' . $obj['officeName'] .
                    '</span><br>项目省份：<span style="color:blue">' . $obj['province'] .
                    '</span><br>项目名称：<span style="color:blue">' . $obj['projecttName'] .
                    '</span><br>项目编号：<span style="color:blue">' . $obj['projectCode'] .
                    '</span>';
                $emailDao->mailClear("外包申请受理", $obj['createId'], $mailContent);
            }
            return true;
        } catch (exception $e) {
            return false;
        }
    }

    /**
     * 审批完成后处理
     * @param $spid
     * @return bool
     * @throws Exception
     */
    function dealAfterAudit_d($spid)
    {
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getStepInfo($spid);

        $id = $folowInfo['objId'];
        $obj = $this->get_d($id);

        // 如果审批完成，那么发个受理通知邮件
        if ($obj['exaStatus'] == AUDITED) {
            $emailDao = new model_common_mail();
            $emailDao->mailDeal_d("outsourcingApplyPassAudit", null, $obj);
        }
        return true;
    }
}