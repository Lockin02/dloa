<?php
/**
 * @author HaoJin
 * @Date 2017��4��20�� ������ 10:40:10
 * @version 1.0
 * @description: ���ö˿��Ʋ�
 *  �������ö˲���:
 *      1. ������������������: insert into oa_system_configurator set configuratorName = '���õ�����',configuratorCode = '���õ�Ψһ���',groupCode = '��������',groupName = '����������';
 *      2. �� view/template/system/configurator/js/configuration-format.js �����������ö˵ı�����������б��ʼ�����Լ�����������ģ�����¼��ر���ʽ����ʼ������
 *      3. �����Ҫ���ſ��������õĻ�,����Ҫ�� view/template/system/configurator/js/configuration-common.js ����ġ�������������ݡ��������ص�����
 */
class controller_system_configurator_configurator extends controller_base_action {

    function __construct() {
        $this->objName = "configurator";
        $this->objPath = "system_configurator";
        parent :: __construct();
    }

    /**
     * ���ö���Ŀ���
     */
    function c_configIndex(){
        $this->assign('t_model' ,'system_configurator_configurator');
        $this->assign('createName' ,$_SESSION['USERNAME']);
        $this->assign('createId' ,$_SESSION ['USER_ID']);
        $this->assign('createTime' ,date("Y-m-d"));
        $this->view('list');
    }

    /**
     * �������ö�ģ��˵���������
     */
    function c_loadConfiguratorMenu(){
        $this->service->sort = "c.id";
        $this->service->asc = false;
        $datas = $this->service->list_d();
        $parentGroup = $backArr = array();
        $num = 1;
        foreach ($datas as $k => $v){
            $attributes = array("code"=>$v['configuratorCode']);
            $arr = array();
            $arr['id'] = $v['id'];
            $arr['text'] = $v['configuratorName'];
            $arr['attributes'] = json_encode($attributes);
            if($num == 1){// ��һ������ĵ�һ������Ĭ��Ϊ��״̬
                $arr['state'] = "open";
                $num += 0.01;
            }
            if(isset($parentGroup[$v['groupCode']])){
                $parentGroup[$v['groupCode']]['children'][] = $arr;
            }else{
                $parentGroup[$v['groupCode']]['children'] = array();
                $parentGroup[$v['groupCode']]['id'] = $v['groupCode'];
                $parentGroup[$v['groupCode']]['text'] = $v['groupName'];
                $parentGroup[$v['groupCode']]['state'] = "open";//($num < 2)? "open" : "closed";
                $parentGroup[$v['groupCode']]['children'][] = $arr;
                $num += 1;
            }
        }

        foreach ($parentGroup as $v){
            $backArr[] = $v;
        }

        header('Content-type:application/json');
        exit(json_encode(un_iconv($backArr)));
    }

    /**
     * ��ȡ��������ϸ�����б���Ϣ
     */
    function c_getConfiguratorItemsList(){
        if(isset($_REQUEST['mainId'])){
            $mainId = $_REQUEST['mainId'];
            $this->service->searchArr['mainId'] = $mainId;
        }else if(isset($_REQUEST['id'])){
            $id = $_REQUEST['id'];
            $this->service->searchArr['itemId'] = $id;
        }
        $datas = $this->service->pageBySqlId('list_items');
        header('Content-type:application/json');

        if($datas){
            exit(json_encode(un_iconv($datas)));
        }else{
            $datas = array();
            exit(json_encode(un_iconv($datas)));
        }
    }

    /**
     * ɾ�������������
     */
    function c_deleteConfiguratorItem(){
        if(isset($_REQUEST['id']) && $_REQUEST['id'] != ''){
            $id = $_REQUEST['id'];
            $service = $this->service;
            $service->tbl_name = 'oa_system_configurator_item';
            $result = $service->delete(array("id"=>$id));

            if($result){
                echo 1;
            }else{
                echo 0;
            }
        }else{
            echo 0;
        }
    }

    /**
     * �༭�����������
     */
    function c_editConfiguratorItem(){
        $service = $this->service;
        $data = $_POST[$this->objName];
        $service->tbl_name = 'oa_system_configurator_item';
        $result = $service->updateById($data);
        if($result){
            msg( '�޸ĳɹ���' );
            succ_show('index1.php?model=system_configurator_configurator&action=configIndex');
        }else{
            msg( '�޸�ʧ�ܣ�' );
        }
    }

    /**
     * Ajax �༭�����������
     */
    function c_ajaxEditConfiguratorItem(){
        if(isset($_REQUEST['configType'])){
            $data['id'] = isset($_REQUEST[$this->objName]['id'])? $_REQUEST[$this->objName]['id'] : '';
            $data['remarks'] = isset($_REQUEST[$this->objName]['remarks'])? $_REQUEST[$this->objName]['remarks'] : '';
            switch ($_REQUEST['configType']){
                case 'YSKPZ':
                    $checkedTypes = $_REQUEST['checkedTypes'];
                    $data['config_item1'] = $data['config_itemSub1'] = '';
                    foreach ($checkedTypes as $k => $v){
                        $itemArr = explode("|",$v);
                        $data['config_item1'] .= ($k == 0)? $itemArr[1] : ','.$itemArr[1];
                        $data['config_itemSub1'] .= ($k == 0)? $itemArr[0] : ','.$itemArr[0];
                    }
                    break;
                case 'SALEDEPT':
                    $checkedDepts = $_REQUEST['checkedDepts'];
                    foreach ($checkedDepts as $k => $v){
                        $itemArr = explode("|",$v);
                        $data['belongDeptNames'] .= ($k == 0)? $itemArr[1] : ','.$itemArr[1];
                        $data['belongDeptIds'] .= ($k == 0)? $itemArr[0] : ','.$itemArr[0];
                    }
                    break;
            }
        }else{
            $data = $_REQUEST[$this->objName];
        }

        $service = $this->service;
        $service->tbl_name = 'oa_system_configurator_item';
        $data = util_jsonUtil::iconvUTF2GBArr($data);
        $result = $service->updateById($data);

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     * ����������
     */
    function c_addConfiguratorItem(){
        $service = $this->service;
        $data = $_POST[$this->objName];
        if(isset($data['id'])){
            unset($data['id']);
        }

        $service->tbl_name = 'oa_system_configurator_item';
        $result = $service->add_d($data);

        if($result){
            msg( '�����ɹ���' );
            succ_show('index1.php?model=system_configurator_configurator&action=configIndex');
        }else{
            msg( '����ʧ�ܣ�' );
        }
    }

    /**
     * Ajax ����������
     */
    function c_ajaxAddConfiguratorItem(){
        ini_set("display_errors",1);
        $data = $_REQUEST[$this->objName];
        if(isset($data['id'])){
            unset($data['id']);
        }

        $service = $this->service;
        $service->tbl_name = 'oa_system_configurator_item';
        $data = util_jsonUtil::iconvUTF2GBArr($data);
        $result = $service->add_d($data);

        if($result){
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     * ��鴫��Ĳ���ID�Ƿ��ڱ�����̯��������
     */
    function c_checkDeptInConfig(){
        $service = $this->service;
        $deptId = isset($_POST['deptId'])? $_POST['deptId'] : '';
        $data = $service->checkDeptInConfig($deptId);
        $result = ($data)? 1 : 0;
        echo $result;
    }

    /**
     * �������߱�������
     */
    function c_checkBxLimitConfig(){
        $service = $this->service;
        $expenseTypeId = isset($_POST['expenseTypeId'])? $_POST['expenseTypeId'] : '';
        $costDepartID = isset($_POST['costDepartID'])? $_POST['costDepartID'] : '';
        $costTypeNames = isset($_POST['costTypeNames'])? $_POST['costTypeNames'] : '';
        $costTypeIds = isset($_POST['costTypeIds'])? $_POST['costTypeIds'] : '';

        $data = $service->checkBxLimitConfig($expenseTypeId,$costDepartID,$costTypeIds,$costTypeNames);
        echo util_jsonUtil::encode($data);
    }

    /**
     * ����⳵ϵͳ��Ŀ�Ƿ������������ظ�����
     */
    function c_chkDuptrentalCarType(){
        $param['notId'] = $_REQUEST['id'];// �������Լ���������
        $param['typesArr'] = $_REQUEST['selectedTypes'];// ��ǰ��������ѡ���⳵ϵͳ��Ŀ���
        if(is_array($param['typesArr'])){
            $pass = true;
            $extSql = ($param['notId'] != '')? " AND i.id <> ".$param['notId']  : "";
            foreach ($param['typesArr'] as $val){
                $sql = "select * from oa_system_configurator_item i left join oa_system_configurator c on i.mainId = c.id where c.configuratorCode = 'ZCFYMM' and FIND_IN_SET('".$val."',i.config_itemSub1) > 0 ".$extSql;
                $chkResult = $this->service->_db->getArray($sql);
                if($chkResult){
                    $pass = false;
                }
            }
            echo ($pass)? 1 : 0;
        }else{
            echo 1;
        }
    }

    /**
     * ��ȡ�⳵ϵͳ����Ŀ
     */
    function c_getCarRentCostTypes(){
        $datatype = isset($_REQUEST['datatype'])? $_REQUEST['datatype'] : '';
        $backData = array(
            'msg' => 'fail',
            'data' => array(),
        );

        $rentalcarDao = new model_outsourcing_vehicle_rentalcar();
        $rentCarFeeNameArr = $rentalcarDao->_rentCarFeeName;

        switch ($datatype){
            case 'options':
                $backData['msg'] = 'ok';
                $optStr = '';
                foreach($rentCarFeeNameArr as $k => $v){
                    $optStr .= "<option value=\"{$k}\">{$v}</option>";
                }
                $backData['data'] = array(
                    "options" => $optStr,
                    "dataArr" => $rentCarFeeNameArr
                );
                break;
            default:
                $backData['msg'] = 'ok';
                $backData['data'] = $rentCarFeeNameArr;
        }

        echo util_jsonUtil::encode($backData);
    }

    /**
     * ��ȡ�ͻ���������
     */
    function c_getCustomerTypes(){
        $datatype = isset($_REQUEST['datatype'])? $_REQUEST['datatype'] : '';
        $backData = array(
            'msg' => 'fail',
            'data' => array(),
        );

        $datadictDao = new model_system_datadict_datadict();
        $customerTypesArr = $datadictDao->findAll(array("parentCode" => "KHLX","isUse" => 0),"dataName desc","dataName,dataCode");

        switch ($datatype){
            case 'checkbox':
                $configId = isset($_REQUEST['configId'])? $_REQUEST['configId'] : '';
                $selectedItemsArr = array();
                if($configId != ''){
                    $this->service->tbl_name = 'oa_system_configurator_item';
                    $configArr = $this->service->get_d($configId);
                    if($configArr && isset($configArr['config_itemSub1'])){
                        $selectedItemsArr = explode(",",$configArr['config_itemSub1']);
                    }
                }

                $backData['msg'] = 'ok';
                $htmlStr = '';
                foreach($customerTypesArr as $k => $v){
                    $checkedStr = (in_array($v['dataCode'],$selectedItemsArr))? "checked" : "";
                    $htmlStr .= '<span class="checkboxItem"><input type="checkbox" name="checkedTypes[]" id="customerType_'.$v['dataCode'].'" title="'.$v['dataName'].'" value="'.$v['dataCode'].'|'.$v['dataName'].'" '.$checkedStr.'> '.$v['dataName'].'</span>';
                }
                $backData['data'] = array(
                    "htmlStr" => $htmlStr,
                    "dataArr" => $customerTypesArr
                );
                break;
            default:
                $backData['msg'] = 'ok';
                $backData['data'] = $customerTypesArr;
        }

        echo util_jsonUtil::encode($backData);
    }

    /**
     * ��ȡ����ϵͳ�Ĺ�������
     */
    function c_getExpenseCostBelongDept(){
        include(WEB_TOR . "includes/config.php");

        $costBelongDeptArr = array();
        $datatype = isset($_REQUEST['datatype'])? $_REQUEST['datatype'] : '';
        $backData = array(
            'msg' => 'fail',
            'data' => array(),
        );

        //��ǰ����
        $expenseSaleDeptArr = isset($expenseSaleDept) ? $expenseSaleDept : null;
        if($expenseSaleDeptArr){
            if(isset($expenseSaleDeptArr['normalDept'])){
                foreach ($expenseSaleDeptArr['normalDept'] as $k => $v){
                    $costBelongDeptArr[$k] = $v;
                }
            }

            if(isset($expenseSaleDeptArr['limitDept'])){
                foreach ($expenseSaleDeptArr['limitDept'] as $k => $v){
                    $costBelongDeptArr[$k] = $v;
                }
            }
        }

        //�ۺ���
        $expenseContractDeptArr = isset($expenseContractDept) ? $expenseContractDept : null;
        if($expenseContractDeptArr){
            if(isset($expenseContractDeptArr['normalDept'])){
                foreach ($expenseContractDeptArr['normalDept'] as $k => $v){
                    $costBelongDeptArr[$k] = $v;
                }
            }

            if(isset($expenseContractDeptArr['limitDept'])){
                foreach ($expenseContractDeptArr['limitDept'] as $k => $v){
                    $costBelongDeptArr[$k] = $v;
                }
            }
        }

        switch ($datatype){
            case 'checkbox':
                $deptIds = isset($_REQUEST['deptIds'])? $_REQUEST['deptIds'] : '';
                $selectedItemsArr = explode(",",$deptIds);

                $backData['msg'] = 'ok';
                $htmlStr = '';
                foreach($costBelongDeptArr as $k => $v){
                    $checkedStr = (in_array($k,$selectedItemsArr))? "checked" : "";
                    $htmlStr .= '<span class="checkboxItem"><input type="checkbox" name="checkedDepts[]" id="costBelongDept_'.$k.'" title="'.$v.'" value="'.$k.'|'.$v.'" '.$checkedStr.'> '.$v.'</span>';
                }
                $backData['data'] = array(
                    "htmlStr" => $htmlStr,
                    "dataArr" => $costBelongDeptArr
                );
                break;
            default:
                $backData['msg'] = 'ok';
                $backData['data'] = $costBelongDeptArr;
        }

        echo util_jsonUtil::encode($backData);
    }
}