<?php
/**
 * @author hhj
 * @Date 2017-11-28 13:22:20
 * @version 1.0
 * @description: 体检申请表控制层
 */
class controller_extSevFuns_bodyChk_bodychk extends controller_base_action {
    function __construct() {
        $this->objName = "bodychk";
        $this->objPath = "extSevFuns_bodyChk";
        parent :: __construct();
    }

    /**
     * 根据传入的userId,判断用户是否存在填报记录
     */
    function c_chkHasRecord(){
        $userId = isset($_GET['userId'])? $_GET['userId'] : $_SESSION['USER_ID'];
        $recordArr = $this->service->find(array("inputUserId" => $userId));
        $result = ($recordArr && !empty($recordArr))? "1" : "0";
        echo $result;
    }

    /**
     * 跳转表单页面
     */
    function c_toForm(){
        $act = isset($_GET['act'])? $_GET['act'] : 'add';
        $dataArr['id'] = $dataArr['name'] = $dataArr['gender'] = $dataArr['identifyCode'] = $dataArr['phoneNum'] = $dataArr['provinceCode'] = $dataArr['province'] = $dataArr['cityCode'] = $dataArr['city'] = $dataArr['storeCode'] = $dataArr['storeName'] = "";
        $dataArr['inputUserId'] = $_SESSION['USER_ID'];
        $dataArr['inputUserName'] = $_SESSION['USER_NAME'];
        $dataArr['inputDate'] = date("Y-m-d H:i:s");
        $dataArr['updateDate'] = date("Y-m-d H:i:s");

        $userId = $_SESSION['USER_ID'];
        $personnelDao = new model_hr_personnel_personnel();
        $userInfo = $personnelDao->find(array("userAccount" => $userId));
        $dataArr['name'] = isset($userInfo['userName'])? $userInfo['userName'] : "";
        $dataArr['gender'] = isset($userInfo['sex'])? $userInfo['sex'] : "";
        $dataArr['identifyCode'] = isset($userInfo['identityCard'])? $userInfo['identityCard'] : "";
        $dataArr['phoneNum'] = isset($userInfo['mobile'])? $userInfo['mobile'] : "";

        switch ($act){
            case 'edit':
                $formAct = "edit";
                $recordArr = $this->service->find(array("inputUserId" => $userId));
                if($recordArr && !empty($recordArr)){
                    $dataArr = $recordArr;
                    $dataArr['updateDate'] = date("Y-m-d H:i:s");
                }else{// 如果没有查询到此用户关联的单据,自动转新增模式
                    $formAct = "add";
                }
                break;
            default:
                $formAct = "add";
                braek;
        }

        foreach ($dataArr as $k => $v){
            $this->assign($k, $v);
        }

        $this->assign("formAct", $formAct);
        $this->view ( 'toForm' );
    }

    /**
     * 前台根据城市获取门店数据
     */
    function c_getStores(){
        $backArr = array();
        $cityId = isset($_POST['cityId'])? $_POST['cityId'] : '';
        if($cityId != ""){
            $getSql = "select * from oa_system_datadict WHERE parentCode = 'MND_STORES' and expand1 = '{$cityId}'";
            $result = $this->service->_db->getArray($getSql);
            $backArr = ($result)? $result : array();
        }

        echo util_jsonUtil::encode($backArr);
    }

    /**
     * 提交表单
     */
    function c_submitForm(){
        $act = isset($_POST['formAct'])? $_POST['formAct'] : "add";
        $formData = $_POST[$this->objName];
        switch ($act){
            case "edit":
                $this->service->edit_d($formData);
                break;
            case "add":
                unset($formData['id']);
                $this->service->add_d($formData);
                break;
        }

        // 保存成功后的处理
        echo "<script>alert('保存成功!');window.location.replace('index1.php?model=extSevFuns_bodyChk_bodychk&action=toForm&act=edit');</script>";
    }

    /**
     * 获取并下载门店分布说明附件
     */
    function c_getFile(){
        $filemngDao = new model_file_uploadfile_management();
        $local = WEB_TOR . 'oa_attachment/';
        $fileName = "美年大健康全国门店分布12.xlsx";
        $filemngDao->downFileByPath($local,"storesLayout.xlsx",$fileName);
    }
}