<?php
/**
 * @author Show
 * @Date 2012��3��6�� ���ڶ� 14:02:58
 * @version 1.0
 * @description:������ѡ���趨�� Model��
 */
class model_common_workflow_selectedsetting  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_system_wf_selectsetting";
		$this->sql_map = "common/workflow/selectedsettingSql.php";
		parent::__construct ();
	}

	/**
	 * �ж��Ƿ���ڸ��˵�ѡ������,û�������������ؿ��ַ���
	 */
	function rtUserSelected_d($gridId = 'auditing'){
		$rs = $this->find(array('userId' => $_SESSION['USER_ID'] , 'gridId' => $gridId),null,'selectedCode');
		if(is_array($rs)){
			return $rs['selectedCode'];
		}else{
			$this->add_d(array('userId' => $_SESSION['USER_ID'] , 'gridId' => $gridId ,'selectedCode' => ''));
			return '';
		}
	}

    /**
     * ���µ�ǰ�û���ѡ�м�¼
     * @param $gridId
     * @param $selectedCode
     */
	function updateUserRecord($gridId,$selectedCode){
        $rs = $this->find(array('userId' => $_SESSION['USER_ID'] , 'gridId' => $gridId),null,'id');
        if(is_array($rs)){
            $this->updateById(array("id"=>$rs['id'],"selectedCode" => $selectedCode));
        }else{
            $this->add_d(array("userId" => $_SESSION['USER_ID'] , 'gridId' => $gridId, "selectedCode" => $selectedCode));
        }
    }

    /**
     * ���µ�ǰ�û����Զ���ÿҳ����������
     * @param $gridId
     * @param int $pageSize
     */
    function updateUserRecordPageSize($gridId,$pageSize = 20){
        $rs = $this->find(array('userId' => $_SESSION['USER_ID'] , 'gridId' => $gridId),null,'id,defaultPageSize');
        if(is_array($rs)){
            if(isset($rs['defaultPageSize']) && $rs['defaultPageSize'] != $pageSize){
                $this->updateById(array("id"=>$rs['id'],"defaultPageSize" => $pageSize));
            }
        }else{
            $this->add_d(array("userId" => $_SESSION['USER_ID'] , 'gridId' => $gridId, "defaultPageSize" => $pageSize));
        }
    }

    /**
     * ��ȡ�û���Ĭ����������
     * @param string $gridId
     * @return bool|mixed
     */
    function getUserDefaultPageSize($gridId = 'auditing'){
        $rs = $this->find(array('userId' => $_SESSION['USER_ID'] , 'gridId' => $gridId),null,'id,defaultPageSize');
        return $rs;
    }

    /**
     * ���µ�ǰ�û����Զ�����
     * @param $gridId
     * @param array $setArr
     */
    function updateUserPersonalSet($gridId,$setArr = array()){
        $rs = $this->find(array('userId' => $_SESSION['USER_ID'] , 'gridId' => $gridId),null,'id');
        if(is_array($rs)){
            if(isset($rs['id']) && $rs['id'] > 0){
                $setArr['id'] = $rs['id'];
                $this->updateById($setArr);
            }
        }else{
            $setArr['userId'] = $_SESSION['USER_ID'];
            $setArr['gridId'] = $gridId;
            $this->add_d($setArr);
        }
    }

    /**
     * ��ȡ�û���Ĭ���Զ�����
     * @param string $gridId
     * @return bool|mixed
     */
    function getUserDefaultPersonalSet($gridId = 'auditing'){
        $rs = $this->find(array('userId' => $_SESSION['USER_ID'] , 'gridId' => $gridId));
        return $rs;
    }
}
?>