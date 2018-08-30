<?php
/**
 * @author hhj
 * @Date 2017-11-28 13:22:20
 * @version 1.0
 * @description: ����������Ʋ�
 */
class controller_extSevFuns_bodyChk_bodychk extends controller_base_action {
    function __construct() {
        $this->objName = "bodychk";
        $this->objPath = "extSevFuns_bodyChk";
        parent :: __construct();
    }

    /**
     * ���ݴ����userId,�ж��û��Ƿ�������¼
     */
    function c_chkHasRecord(){
        $userId = isset($_GET['userId'])? $_GET['userId'] : $_SESSION['USER_ID'];
        $recordArr = $this->service->find(array("inputUserId" => $userId));
        $result = ($recordArr && !empty($recordArr))? "1" : "0";
        echo $result;
    }

    /**
     * ��ת��ҳ��
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
                }else{// ���û�в�ѯ�����û������ĵ���,�Զ�ת����ģʽ
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
     * ǰ̨���ݳ��л�ȡ�ŵ�����
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
     * �ύ��
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

        // ����ɹ���Ĵ���
        echo "<script>alert('����ɹ�!');window.location.replace('index1.php?model=extSevFuns_bodyChk_bodychk&action=toForm&act=edit');</script>";
    }

    /**
     * ��ȡ�������ŵ�ֲ�˵������
     */
    function c_getFile(){
        $filemngDao = new model_file_uploadfile_management();
        $local = WEB_TOR . 'oa_attachment/';
        $fileName = "����󽡿�ȫ���ŵ�ֲ�12.xlsx";
        $filemngDao->downFileByPath($local,"storesLayout.xlsx",$fileName);
    }
}