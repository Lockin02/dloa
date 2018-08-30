<?php
/**
 * @author Show
 * @Date 2012年3月6日 星期二 14:02:58
 * @version 1.0
 * @description:工作流选择设定表 Model层
 */
class model_common_workflow_selectedsetting  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_system_wf_selectsetting";
		$this->sql_map = "common/workflow/selectedsettingSql.php";
		parent::__construct ();
	}

	/**
	 * 判断是否存在个人的选择设置,没有则新增并返回空字符串
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
     * 更新当前用户的选中记录
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
     * 更新当前用户的自定义每页的数据条数
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
     * 获取用户的默认数据条数
     * @param string $gridId
     * @return bool|mixed
     */
    function getUserDefaultPageSize($gridId = 'auditing'){
        $rs = $this->find(array('userId' => $_SESSION['USER_ID'] , 'gridId' => $gridId),null,'id,defaultPageSize');
        return $rs;
    }

    /**
     * 更新当前用户的自定义项
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
     * 获取用户的默认自定义项
     * @param string $gridId
     * @return bool|mixed
     */
    function getUserDefaultPersonalSet($gridId = 'auditing'){
        $rs = $this->find(array('userId' => $_SESSION['USER_ID'] , 'gridId' => $gridId));
        return $rs;
    }
}
?>