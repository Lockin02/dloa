<?php

/**
 * @author Show
 * @Date 2012年11月6日 星期二 11:42:10
 * @version 1.0
 * @description:设备管理-库存信息控制层
 */
class controller_engineering_device_esmdevice extends controller_base_action
{

    function __construct() {
        $this->objName = "esmdevice";
        $this->objPath = "engineering_device";
        parent:: __construct();
    }

    /**
     * 跳转到设备管理-库存信息列表
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * combogrid选择设备
     */
    function c_selectDevice() {
        $this->assign('showButton', $_GET ['showButton']);
        $this->assign('showcheckbox', $_GET ['showcheckbox']);
        $this->assign('checkIds', $_GET ['checkIds']);
        $this->view("selectdevice");
    }

    /**
     * 选择json
     */
    function c_selectJson() {
        // 接入新OA的设备类型获取方法
        // $_POST['device_nameSearch'] = util_jsonUtil::iconvUTF2GB($_POST['device_nameSearch']);
        $result = util_curlUtil::getDataFromAWS('asset', 'ClassifiDetailAslp', array(
            'pid' => isset($_POST['typeid']) ? $_POST['typeid'] : "", 'page' => $_POST['page'],
            'pageSize' => $_POST['pageSize'], 'nameSearch' => isset($_POST['device_nameSearch']) ? $_POST['device_nameSearch'] : ""
        ), array(), false);
        $data = util_jsonUtil::decode($result['data'], true);

        $rows = array();
        if (!empty($data['data']['array'])) {
            foreach ($data['data']['array'] as $k => $v) {
                $j = $k + 1;
                $rows[] = array(
                    'deviceType' => $v['ASSETSCLASS'],
                    'device_name' => $v['DeviceName'],
                    'unit' => $v['MEASUREMENTUNIT'],
                    'discount' => $v['INPRICE'],
                    'id' => $v['DeviceName'] . '-' . $v['ASSETSCLASS'] . '-' . $j,
                    'virtualId' => $j
                );
            }
        }

        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = !empty($rows) ? $data['data']['size'] : 0;
        $arr ['page'] = $_POST['page'];
        echo util_jsonUtil::encode($arr);

//        $service = $this->service;
//        //加入部门过滤
//        include(WEB_TOR . "includes/config.php");
//        $deptIds = isset($defaultEsmDept) ? implode(',', array_keys($defaultEsmDept)) : '';
//        $_POST['dept_id_in'] = $deptIds;
//        $service->getParam($_POST); //设置前台获取的参数信息
//
//        //$service->asc = false;
//        $rows = $service->page_d('select_select');
//        $arr = array();
//        $arr ['collection'] = $rows;
//        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
//        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
//        $arr ['page'] = $service->page;
//        $arr ['advSql'] = $service->advSql;
//        echo util_jsonUtil::encode($arr);
    }

    /**
     * 获取工作流类型 - 用于下拉过滤
     */
    function c_getFormType() {
        $orgArr = $this->service->getFormType_d($_POST);
        echo util_jsonUtil:: encode($orgArr);
    }

    /**
     * 项目设备列表
     */
    function c_deviceList() {
        $this->assign('projectId', $_GET['projectId']);
        $this->assign('projectCode', $_GET['projectCode']);
        $this->view('list-device');
    }

    /**
     * 项目设备列表json
     */
    function c_deviceJson() {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $rows = $service->page_d('select_device');
        $sum = 0;

        if ($rows) {
            foreach ($rows as $key => $value) {
                $sum = bcadd($value['amount'], $sum, 2);
            }
        } else {
            $rows = array();
        }

        //导入存储设备决算
        $rowDevice = array('device_name' => '系统补录');//存储设备决算的数组
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectObj = $esmprojectDao->find(array('projectCode' => $_POST['dprojectcode']), null, 'feeEqu');
        if ($esmprojectObj['feeEqu'] > 0) {
            $rowDevice['amount'] = $esmprojectObj['feeEqu'];
            array_push($rows, $rowDevice);
        }

        if (!empty($rows)) {
            //导入项目合计
            $rowSum = array('device_name' => ' 项 目 合 计 ');//存储项目合计的数组
            $rowSum['amount'] = bcadd($sum, $rowDevice['amount'], 2);
            array_push($rows, $rowSum);
        }

        $arr = array();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 个人设备列表
     */
    function c_myList() {
        $this->view('list-my');
    }

    /**
     * 项目设备列表json
     */
    function c_myJson() {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->searchArr['oUserid'] = $_SESSION['USER_ID'];
        $service->searchArr['notReturn'] = 1;
        $service->searchArr['aClaim'] = 1;
        if($_POST['isLock'] == '0'){
            $service->searchArr['lockCondition'] = "sql: and DATEDIFF(CURDATE(),FROM_UNIXTIME(a.targetdate, '%Y-%m-%d')) < 8";
        }elseif($_POST['isLock'] == '1'){
            $service->searchArr['lockCondition'] = "sql: and DATEDIFF(CURDATE(),FROM_UNIXTIME(a.targetdate, '%Y-%m-%d')) >= 8";
        }
        $service->groupBy = 'g.id,c.device_name,de.DEPT_ID';
        $rows = $service->page_d('select_my');
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        echo util_jsonUtil::encode($arr);
    }

    /******************* 增删改查 ***********************/

    /**
     * 跳转到新增设备管理-库存信息页面
     */
    function c_toAdd() {
        $this->view('add');
    }

    /**
     * 跳转到编辑设备管理-库存信息页面
     */
    function c_toEdit() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('edit');
    }

    /**
     * 跳转到查看设备管理-库存信息页面
     */
    function c_toView() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('view');
    }

    /**
     * 工程区域下拉(含区域设备数量) 数据源
     */
    function c_projectAreaJson() {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $rows = $service->pageBySqlId('select_projectArea');

        if (isset($_REQUEST['isList'])) {
            //获取当前任务单中未处理数量并扣除
            $taskdetailDao = new model_engineering_resources_taskdetail();
            $waitAreaNumArr = $taskdetailDao->getAreaWaitNumArr_d($_REQUEST['list_id']);

            if (isset($_REQUEST['areaNumArr']) || !empty($waitAreaNumArr)) {
                $areaNumArr = $_REQUEST['areaNumArr'];
                foreach ($rows as &$v) {
                    if (isset($areaNumArr[$v['id']])) {
                        $v['surplus'] -= $areaNumArr[$v['id']];
                    }
                    if (isset($waitAreaNumArr[$v['id']])) {
                        $v['surplus'] -= $waitAreaNumArr[$v['id']];
                    }
                }
            }
            echo util_jsonUtil::encode($rows);
        } else {
            $arr = array();
            $arr ['collection'] = $rows;
            //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
            $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
            $arr ['page'] = $service->page;
            $arr ['advSql'] = $service->advSql;
            $arr ['listSql'] = $service->listSql;
            echo util_jsonUtil::encode($arr);
        }
    }

    /**
     * ajax 获取库存数量
     */
    function c_ajaxFindSurplus() {
        $id = isset($_POST['deviceId']) ? $_POST['deviceId'] : null;
        $this->service->searchArr = array(
            "id" => $id
        );
        $rows = $this->service->page_d();
        $result['total'] = $rows[0]['total'];
        $result['borrow'] = $rows[0]['borrow'];
        $result['surplus'] = $rows[0]['surplus'];
        //获取可替换设备
        $replacedDao = new model_engineering_resources_replaced();
        $replacedIdArr = $replacedDao->find(array("deviceId" => $id), null, 'id');
        if (!empty($replacedIdArr)) {
            $replacedId = $replacedIdArr['id'];
            $sql = "SELECT GROUP_CONCAT(CAST(deviceId AS char)) AS deviceIds,GROUP_CONCAT(deviceName) AS deviceNames
                FROM oa_esm_resource_replacedInfo WHERE replacedId='" . $replacedId . "' GROUP BY replacedId";
            $replacedIdsArr = $this->service->_db->getArray($sql);
            $replacedIds = $replacedIdsArr[0]['deviceIds'];
            $replacedNames = $replacedIdsArr[0]['deviceNames'];
            $result['replacedIds'] = $replacedIds;
            $result['replacedNames'] = $replacedNames;
        } else {
            $result['replacedIds'] = "none";
        }
        echo util_jsonUtil::encode($result);
    }

    /******************************导出设备********************/
    /**
     * 条件导出项目 - 用于项目内设备导出
     */
    function c_exportDevice() {
        set_time_limit(0);
        $service = $this->service;
        $service->getParam($_GET);//设置前台获取的参数信息
        $rows = $service->list_d('select_device');

        $sum = 0;
        foreach ($rows as $value) {
            $sum = bcadd($value['amount'], $sum, 2);
        }

        //导入存储设备决算
        $rowDevice = array('device_name' => '系统补录');//存储设备决算的数组
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectObj = $esmprojectDao->find(array('projectCode' => $_GET['dprojectcode']), null, 'feeEqu');
        $rowDevice['amount'] = $esmprojectObj['feeEqu'];
        array_push($rows, $rowDevice);

        //导入项目合计
        $rowSum = array('device_name' => '项目合计');//存储项目合计的数组
        $rowSum['amount'] = bcadd($sum, $rowDevice['amount'], 2);
        array_push($rows, $rowSum);

        if ($rows) {
            $thArr = array(
                'deviceType' => '设备类型', 'device_name' => '设备名称', 'coding' => '机身码', 'dpcoding' => '部门编码',
                'borrowNum' => '数量', 'unit' => '单位', 'borrowUserName' => '借用人', 'borrowDate' => '借出时间',
                'returnDate' => '归还时间', 'useDays' => '使用天数', 'amount' => '实时决算', 'description' => '描述信息'
            );
            model_engineering_util_esmexcelutil::exportSearchDept($thArr, $rows, '项目设备');
        } else {
            msg('无可导出数据');
        }
    }

    /**
     * 导出设备信息excel
     */
    function c_exportDeviceExcel() {
        set_time_limit(0);
        //加入部门过滤
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('device_export', $_SESSION['USER_ID'],
            $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        $deptLimit = $sysLimit['管理部门'];
        if ($deptLimit) {
            if (strpos($deptLimit, ';;') === false) {
                $_POST['fdept_id_in'] = $deptLimit;
            }

            $this->service->getParam($_POST); //设置前台获取的参数信息
            $rows = $this->service->listBySqlId('select_device');
            return model_engineering_util_esmexcelutil::exportCSV(array(
                'deviceType' => '设备类型', 'device_name' => '设备名称', 'coding' => '机身码', 'dpcoding' => '部门码',
                'borrowNum' => '数量', 'unit' => '单位', 'borrowUserName' => '领用人', 'borrowDate' => '领用日期',
                'returnDate' => '归还日期', 'useDays' => '使用天数', 'amount' => '费用', 'fitting' => '配置',
                'projectCode' => '归还日期', 'projectName' => '使用天数', 'notes' => '费用', 'budgetPrice' => '配置'
            ), $rows, '设备信息');
        } else {
            echo "没有部门权限，请先联系相关人员开通库存管理模块中的管理部门权限";
        }
    }

    /**
     * 获取设备信息
     */
    function c_equJson() {
        $service = $this->service;
        $service->getParam($_POST);
        $service->searchArr['notReturn'] = 1;
        $service->searchArr['aClaim'] = 1;
        $service->searchArr['oUserid'] = $_SESSION['USER_ID'];
        $service->sort = "";
        $rows = $service->page_d('select_mydetail');
        // 占用设备
        $rows = $service->setUsedInfo_d($rows);
        $arr['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 搜索设备信息
     */
    function c_selectdeviceInfo() {
        $service = $this->service;
        $service->getParam($_POST);
        $service->searchArr['aClaim'] = 1;
        $service->searchArr['notReturn'] = 1;
        $service->searchArr['oUserid'] = $_SESSION['USER_ID'];
//         $service->groupBy = 'b.id';
        $service->sort = "";
        $row = $this->service->list_d("select_returninfo");
        echo util_jsonUtil::encode($row);
    }

    /**
     * 跳转到修改项目设备-设备记录页面
     */
    function c_toEditLog() {
        $this->permCheck(); //安全校验
        $dateInfo = $this->service->getLogDate_d($_GET['id']);
        $this->assign('id', $_GET['id']);
        $this->assign('date', $dateInfo[0]['date']);
        $this->assign('returndate', $dateInfo[0]['returndate']);
        $this->view('editlog');
    }

    /**
     * 修改设备记录时间
     */
    function c_editLog() {
        $dateInfo = array('id' => $_POST['id'], 'date' => $_POST['date'], 'returndate' => $_POST['returndate']);
        if ($this->service->updateLogDate_d($dateInfo)) {
            msg("修改成功！");
        } else {
            msg("修改失败！");
        }
    }

    /**
     * 删除设备记录
     */
    function c_ajaxdelete() {
        echo $this->service->delete_d($_POST['id']) ? 1 : 0;
    }

    /**
     * 跳转到批量操作页面(批量归还/续借/转借)
     */
    function c_toBatch() {
        $this->view('bacth');
    }

    /**
     * 批量归还/续借/转借
     */
    function c_batch() {
        $object = $_POST[$this->objName];
        $rows = $this->service->batch_d($object);
        if(empty($rows)){
            msg("没有相关数据");
        }else{
            $type = $object['type'];
            $deptId = '';
            $projectId = '';
            $flag = '';
            $idArr = array();
            $projectIdArr = array();
            $projectCodeArr = array();
            $projectNameArr = array();
            $managerIdArr = array();
            $managerNameArr = array();
            $msg = '';
            $isMsg = false;
            foreach ($rows as $v){
                if($deptId == ''){
                    $deptId = $v['deptId'];
                }elseif($deptId != $v['deptId']){
                    $isMsg = true;
                    $msg = "不同所属部门的设备不能合并生成一张单据";
                    break;
                }
                if($type == 'erenew'){//仅针对续借
                    if($projectId == ''){
                        $projectId = $v['projectId'];
                        $flag = $v['flag'];
                    }elseif($projectId != $v['projectId']){
                        $isMsg = true;
                        $msg = "续借时,不同项目的设备不能合并生成一张单据";
                        break;
                    }
                }
                array_push($idArr, $v['id']);
                array_push($projectIdArr, $v['projectId']);
                array_push($projectCodeArr, $v['projectCode']);
                array_push($projectNameArr, $v['projectName']);
                array_push($managerIdArr, $v['managerId']);
                array_push($managerNameArr, $v['managerName']);
            }
            if($isMsg){
                msg($msg);
            }else{
                succ_show("?model=engineering_resources_".$type."&action=toAdd&rowsId=".implode(',', array_unique($idArr)).
                    "&projectId=".implode(',', array_unique($projectIdArr))."&projectCode=".implode(',', array_unique($projectCodeArr)).
                    "&projectName=".implode(',', array_unique($projectNameArr))."&managerId=".implode(',', array_unique($managerIdArr)).
                    "&managerName=".implode(',', array_unique($managerNameArr))."&deviceDeptId=".$deptId."&flag=".$flag);
            }
        }
    }
}