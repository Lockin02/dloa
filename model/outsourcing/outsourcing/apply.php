<?php

/**
 * @author Administrator
 * @Date 2013��9��17�� 11:30:50
 * @version 1.0
 * @description:�������� Model��
 */
class model_outsourcing_outsourcing_apply extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_outsourcing_apply";
        $this->sql_map = "outsourcing/outsourcing/applySql.php";
        parent::__construct();
    }

    //��˾Ȩ�޴��� TODO
    protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

    function add_d($object)
    {
        try {
            $this->start_d();

            //�Զ�����ϵͳ��źͱ�״̬
            $codeRuleDao = new model_common_codeRule();
            $object['applyCode'] = $codeRuleDao->commonCode2('�������', 'WBSQ', 'WBSQ', $_SESSION['DEPT_ID']);

            //��ȡ������˾����
            $object['formBelong'] = $_SESSION['USER_COM'];
            $object['formBelongName'] = $_SESSION['USER_COM_NAME'];
            $object['businessBelong'] = $_SESSION['USER_COM'];
            $object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

            //�޸�������Ϣ
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

            //���¸���������ϵ
            $this->updateObjWithFile($id);

            //��������
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

            //�޸�������Ϣ
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
            //���¸���������ϵ
            $this->updateObjWithFile($object['id']);
            $this->commit_d();
            return $id;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }
	
	/**
	 * ����
	 * @param unknown $object
	 * @return boolean 
	 */
	function exemptReview($object) {
		try {
			$this->start_d();
			$object['state'] = '1';
			$object['exaDT'] = date('Y-m-d H:i:s',time());;
			$object['exaStatus'] = '���';
			//�޸�������Ϣ
			parent :: edit_d($object, true);
			$this->commit_d();
			return true;
		} catch (exception $e) {
			return false;
		}
	}

    /**�������*/
    function backApply_d($object)
    {
        try {
            $object['state'] = '0';
            $object['cancelManId'] = $_SESSION['USER_ID'];
            $object['cancelManName'] = $_SESSION['USERNAME'];
            //�޸�������Ϣ
            $id = parent:: edit_d($object, true);

            //�����ʼ�֪ͨ��������������Ա
            if ($id) {
                $this->searchArr = array('id' => $object['id']);
                $this->setCompany(0);
                $arr = $this->listBySqlId('select_deal_list');
                $obj = $arr[0];
                $receiverId = $this->getMailReceiver_d($object['id']);
                $emailDao = new model_common_mail();
                $mailContent = '���ã����ʼ�Ϊ���������֪ͨ����ϸ��Ϣ���£�<br>' .
                    '���ݱ�ţ�<span style="color:blue">' . $obj['applyCode'] .
                    '</span><br>��������<span style="color:blue">' . $obj['officeName'] .
                    '</span><br>��Ŀʡ�ݣ�<span style="color:blue">' . $obj['province'] .
                    '</span><br>��Ŀ���ƣ�<span style="color:blue">' . $obj['projecttName'] .
                    '</span><br>��Ŀ��ţ�<span style="color:blue">' . $obj['projectCode'] .
                    '</span><br>�����ˣ�<span style="color:blue">' . $obj['createName'] .
                    '</span><br>����ʱ�䣺<span style="color:blue">' . $obj['createTime'] .
                    '</span><br>���ԭ��:<span style="color:blue">' . $object['cancelReason'] .
                    '</span>';
                $emailDao->mailClear("���������", $receiverId, $mailContent);
            }
            return $id;
        } catch (exception $e) {
            return false;
        }
    }

    /**
     * �ر�
     * @param $object
     * @return bool
     */
    function closeApply_d($object)
    {
        try {
            $object['state'] = '5';
            $object['cancelManId'] = $_SESSION['USER_ID'];
            $object['cancelManName'] = $_SESSION['USERNAME'];
            //�޸�������Ϣ
            $id = parent:: edit_d($object, true);

            //�����ʼ�֪ͨ��������������Ա
            if ($id) {
                $this->searchArr = array('id' => $object['id']);
                $this->setCompany(0);
                $arr = $this->listBySqlId('select_deal_list');
                $obj = $arr[0];
                $receiverId = $this->getMailReceiver_d($object['id']);
                $emailDao = new model_common_mail();
                $mailContent = '���ã����ʼ�Ϊ�������ر�֪ͨ����ϸ��Ϣ���£�<br>' .
                    '���ݱ�ţ�<span style="color:blue">' . $obj['applyCode'] .
                    '</span><br>��������<span style="color:blue">' . $obj['officeName'] .
                    '</span><br>��Ŀʡ�ݣ�<span style="color:blue">' . $obj['province'] .
                    '</span><br>��Ŀ���ƣ�<span style="color:blue">' . $obj['projecttName'] .
                    '</span><br>��Ŀ��ţ�<span style="color:blue">' . $obj['projectCode'] .
                    '</span><br>�����ˣ�<span style="color:blue">' . $obj['createName'] .
                    '</span><br>����ʱ�䣺<span style="color:blue">' . $obj['createTime'] .
                    '</span><br>�ر�ԭ��:<span style="color:blue">' . $object['closeReason'] .
                    '</span>';
                $emailDao->mailClear("�ر��������", $receiverId, $mailContent);
            }
            return $id;
        } catch (exception $e) {
            return false;
        }
    }

    /*
     * �����������id
     * ��ȡ��������������Ա
     */
    function getMailReceiver_d($id)
    {
        $obj = $this->get_d($id);
        $receiverId = '';
        $receiverId .= $obj['createId'];//������
        include(WEB_TOR . "model/common/mailConfig.php");
        $mailOperations = isset($mailUser['oa_outsourcing_operations']) ? $mailUser['oa_outsourcing_operations'] : "";
        $receiverId .= ',' . $mailOperations['TO_ID'];//������Ӫ��
        $mailInterface = $mailUser['oa_outsourcing_interface'];
        $receiverId .= ',' . $mailInterface['TO_ID'];//����ӿ���
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectObj = $esmprojectDao->get_d($obj['projectId']);
        $receiverId .= ',' . $this->get_table_fields('oa_system_province_info', "id=" . $esmprojectObj['provinceId'], 'esmManagerId');//������
        $receiverId .= ',' . $this->get_table_fields('oa_esm_office_baseinfo', "id=" . $esmprojectObj['officeId'], 'mainManagerId');//�����ܼ�
        return $receiverId;
    }

    /**
     * ��������
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
            //�޸�������Ϣ
            $id = parent::edit_d($object, true);

            //�����ʼ�֪ͨ��������������Ա
            if ($id) {
                $obj = $this->get_d($id);
                $emailDao = new model_common_mail();
                $mailContent = '���ã����ʼ�Ϊ�����������֪ͨ����ϸ��Ϣ���£�<br>' .
                    '���ݱ�ţ�<span style="color:blue">' . $obj['applyCode'] .
                    '</span><br>��������<span style="color:blue">' . $obj['officeName'] .
                    '</span><br>��Ŀʡ�ݣ�<span style="color:blue">' . $obj['province'] .
                    '</span><br>��Ŀ���ƣ�<span style="color:blue">' . $obj['projecttName'] .
                    '</span><br>��Ŀ��ţ�<span style="color:blue">' . $obj['projectCode'] .
                    '</span>';
                $emailDao->mailClear("�����������", $obj['createId'], $mailContent);
            }
            return true;
        } catch (exception $e) {
            return false;
        }
    }

    /**
     * ������ɺ���
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

        // ���������ɣ���ô��������֪ͨ�ʼ�
        if ($obj['exaStatus'] == AUDITED) {
            $emailDao = new model_common_mail();
            $emailDao->mailDeal_d("outsourcingApplyPassAudit", null, $obj);
        }
        return true;
    }
}