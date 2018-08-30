<?php
/**
 * @author HaoJin
 * @Date 2017年4月20日 星期四 10:40:10
 * @version 1.0
 * @description: 配置端控制层
 *  新增配置端步骤:
 *      1. 在配置类别表新增配置: insert into oa_system_configurator set configuratorName = '配置的名称',configuratorCode = '配置的唯一编号',groupCode = '所属组编号',groupName = '所属组名称';
 *      2. 在 view/template/system/configurator/js/configuration-format.js 里添加相关配置端的表格【配置内容列表初始化】以及表单【按所属模块重新加载表单样式】初始化配置
 *      3. 如果需要开放可新增配置的话,还需要在 view/template/system/configurator/js/configuration-common.js 里面的【添加配置项内容】处添加相关的配置
 */
class controller_system_configurator_configurator extends controller_base_action {

    function __construct() {
        $this->objName = "configurator";
        $this->objPath = "system_configurator";
        parent :: __construct();
    }

    /**
     * 配置端栏目入口
     */
    function c_configIndex(){
        $this->assign('t_model' ,'system_configurator_configurator');
        $this->assign('createName' ,$_SESSION['USERNAME']);
        $this->assign('createId' ,$_SESSION ['USER_ID']);
        $this->assign('createTime' ,date("Y-m-d"));
        $this->view('list');
    }

    /**
     * 加载配置端模块菜单树形数据
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
            if($num == 1){// 第一个分类的第一个子项默认为打开状态
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
     * 获取配置项详细内容列表信息
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
     * 删除配置项的内容
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
     * 编辑配置项的内容
     */
    function c_editConfiguratorItem(){
        $service = $this->service;
        $data = $_POST[$this->objName];
        $service->tbl_name = 'oa_system_configurator_item';
        $result = $service->updateById($data);
        if($result){
            msg( '修改成功！' );
            succ_show('index1.php?model=system_configurator_configurator&action=configIndex');
        }else{
            msg( '修改失败！' );
        }
    }

    /**
     * Ajax 编辑配置项的内容
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
     * 新增配置项
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
            msg( '新增成功！' );
            succ_show('index1.php?model=system_configurator_configurator&action=configIndex');
        }else{
            msg( '新增失败！' );
        }
    }

    /**
     * Ajax 新增配置项
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
     * 检查传入的部门ID是否在报销分摊配置项内
     */
    function c_checkDeptInConfig(){
        $service = $this->service;
        $deptId = isset($_POST['deptId'])? $_POST['deptId'] : '';
        $data = $service->checkDeptInConfig($deptId);
        $result = ($data)? 1 : 0;
        echo $result;
    }

    /**
     * 检查服务线报销限制
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
     * 检查租车系统名目是否在配置项中重复出现
     */
    function c_chkDuptrentalCarType(){
        $param['notId'] = $_REQUEST['id'];// 不包含自己的配置项
        $param['typesArr'] = $_REQUEST['selectedTypes'];// 当前配置项所选的租车系统名目编号
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
     * 获取租车系统的名目
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
     * 获取客户类型数据
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
     * 获取报销系统的归属部门
     */
    function c_getExpenseCostBelongDept(){
        include(WEB_TOR . "includes/config.php");

        $costBelongDeptArr = array();
        $datatype = isset($_REQUEST['datatype'])? $_REQUEST['datatype'] : '';
        $backData = array(
            'msg' => 'fail',
            'data' => array(),
        );

        //售前部门
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

        //售后部门
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