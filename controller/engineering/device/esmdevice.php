<?php

/**
 * @author Show
 * @Date 2012��11��6�� ���ڶ� 11:42:10
 * @version 1.0
 * @description:�豸����-�����Ϣ���Ʋ�
 */
class controller_engineering_device_esmdevice extends controller_base_action
{

    function __construct() {
        $this->objName = "esmdevice";
        $this->objPath = "engineering_device";
        parent:: __construct();
    }

    /**
     * ��ת���豸����-�����Ϣ�б�
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * combogridѡ���豸
     */
    function c_selectDevice() {
        $this->assign('showButton', $_GET ['showButton']);
        $this->assign('showcheckbox', $_GET ['showcheckbox']);
        $this->assign('checkIds', $_GET ['checkIds']);
        $this->view("selectdevice");
    }

    /**
     * ѡ��json
     */
    function c_selectJson() {
        // ������OA���豸���ͻ�ȡ����
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
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = !empty($rows) ? $data['data']['size'] : 0;
        $arr ['page'] = $_POST['page'];
        echo util_jsonUtil::encode($arr);

//        $service = $this->service;
//        //���벿�Ź���
//        include(WEB_TOR . "includes/config.php");
//        $deptIds = isset($defaultEsmDept) ? implode(',', array_keys($defaultEsmDept)) : '';
//        $_POST['dept_id_in'] = $deptIds;
//        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
//
//        //$service->asc = false;
//        $rows = $service->page_d('select_select');
//        $arr = array();
//        $arr ['collection'] = $rows;
//        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
//        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
//        $arr ['page'] = $service->page;
//        $arr ['advSql'] = $service->advSql;
//        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��ȡ���������� - ������������
     */
    function c_getFormType() {
        $orgArr = $this->service->getFormType_d($_POST);
        echo util_jsonUtil:: encode($orgArr);
    }

    /**
     * ��Ŀ�豸�б�
     */
    function c_deviceList() {
        $this->assign('projectId', $_GET['projectId']);
        $this->assign('projectCode', $_GET['projectCode']);
        $this->view('list-device');
    }

    /**
     * ��Ŀ�豸�б�json
     */
    function c_deviceJson() {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $rows = $service->page_d('select_device');
        $sum = 0;

        if ($rows) {
            foreach ($rows as $key => $value) {
                $sum = bcadd($value['amount'], $sum, 2);
            }
        } else {
            $rows = array();
        }

        //����洢�豸����
        $rowDevice = array('device_name' => 'ϵͳ��¼');//�洢�豸���������
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectObj = $esmprojectDao->find(array('projectCode' => $_POST['dprojectcode']), null, 'feeEqu');
        if ($esmprojectObj['feeEqu'] > 0) {
            $rowDevice['amount'] = $esmprojectObj['feeEqu'];
            array_push($rows, $rowDevice);
        }

        if (!empty($rows)) {
            //������Ŀ�ϼ�
            $rowSum = array('device_name' => ' �� Ŀ �� �� ');//�洢��Ŀ�ϼƵ�����
            $rowSum['amount'] = bcadd($sum, $rowDevice['amount'], 2);
            array_push($rows, $rowSum);
        }

        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * �����豸�б�
     */
    function c_myList() {
        $this->view('list-my');
    }

    /**
     * ��Ŀ�豸�б�json
     */
    function c_myJson() {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        echo util_jsonUtil::encode($arr);
    }

    /******************* ��ɾ�Ĳ� ***********************/

    /**
     * ��ת�������豸����-�����Ϣҳ��
     */
    function c_toAdd() {
        $this->view('add');
    }

    /**
     * ��ת���༭�豸����-�����Ϣҳ��
     */
    function c_toEdit() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('edit');
    }

    /**
     * ��ת���鿴�豸����-�����Ϣҳ��
     */
    function c_toView() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('view');
    }

    /**
     * ������������(�������豸����) ����Դ
     */
    function c_projectAreaJson() {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $rows = $service->pageBySqlId('select_projectArea');

        if (isset($_REQUEST['isList'])) {
            //��ȡ��ǰ������δ�����������۳�
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
            //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
            $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
            $arr ['page'] = $service->page;
            $arr ['advSql'] = $service->advSql;
            $arr ['listSql'] = $service->listSql;
            echo util_jsonUtil::encode($arr);
        }
    }

    /**
     * ajax ��ȡ�������
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
        //��ȡ���滻�豸
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

    /******************************�����豸********************/
    /**
     * ����������Ŀ - ������Ŀ���豸����
     */
    function c_exportDevice() {
        set_time_limit(0);
        $service = $this->service;
        $service->getParam($_GET);//����ǰ̨��ȡ�Ĳ�����Ϣ
        $rows = $service->list_d('select_device');

        $sum = 0;
        foreach ($rows as $value) {
            $sum = bcadd($value['amount'], $sum, 2);
        }

        //����洢�豸����
        $rowDevice = array('device_name' => 'ϵͳ��¼');//�洢�豸���������
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectObj = $esmprojectDao->find(array('projectCode' => $_GET['dprojectcode']), null, 'feeEqu');
        $rowDevice['amount'] = $esmprojectObj['feeEqu'];
        array_push($rows, $rowDevice);

        //������Ŀ�ϼ�
        $rowSum = array('device_name' => '��Ŀ�ϼ�');//�洢��Ŀ�ϼƵ�����
        $rowSum['amount'] = bcadd($sum, $rowDevice['amount'], 2);
        array_push($rows, $rowSum);

        if ($rows) {
            $thArr = array(
                'deviceType' => '�豸����', 'device_name' => '�豸����', 'coding' => '������', 'dpcoding' => '���ű���',
                'borrowNum' => '����', 'unit' => '��λ', 'borrowUserName' => '������', 'borrowDate' => '���ʱ��',
                'returnDate' => '�黹ʱ��', 'useDays' => 'ʹ������', 'amount' => 'ʵʱ����', 'description' => '������Ϣ'
            );
            model_engineering_util_esmexcelutil::exportSearchDept($thArr, $rows, '��Ŀ�豸');
        } else {
            msg('�޿ɵ�������');
        }
    }

    /**
     * �����豸��Ϣexcel
     */
    function c_exportDeviceExcel() {
        set_time_limit(0);
        //���벿�Ź���
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('device_export', $_SESSION['USER_ID'],
            $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        $deptLimit = $sysLimit['������'];
        if ($deptLimit) {
            if (strpos($deptLimit, ';;') === false) {
                $_POST['fdept_id_in'] = $deptLimit;
            }

            $this->service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
            $rows = $this->service->listBySqlId('select_device');
            return model_engineering_util_esmexcelutil::exportCSV(array(
                'deviceType' => '�豸����', 'device_name' => '�豸����', 'coding' => '������', 'dpcoding' => '������',
                'borrowNum' => '����', 'unit' => '��λ', 'borrowUserName' => '������', 'borrowDate' => '��������',
                'returnDate' => '�黹����', 'useDays' => 'ʹ������', 'amount' => '����', 'fitting' => '����',
                'projectCode' => '�黹����', 'projectName' => 'ʹ������', 'notes' => '����', 'budgetPrice' => '����'
            ), $rows, '�豸��Ϣ');
        } else {
            echo "û�в���Ȩ�ޣ�������ϵ�����Ա��ͨ������ģ���еĹ�����Ȩ��";
        }
    }

    /**
     * ��ȡ�豸��Ϣ
     */
    function c_equJson() {
        $service = $this->service;
        $service->getParam($_POST);
        $service->searchArr['notReturn'] = 1;
        $service->searchArr['aClaim'] = 1;
        $service->searchArr['oUserid'] = $_SESSION['USER_ID'];
        $service->sort = "";
        $rows = $service->page_d('select_mydetail');
        // ռ���豸
        $rows = $service->setUsedInfo_d($rows);
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * �����豸��Ϣ
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
     * ��ת���޸���Ŀ�豸-�豸��¼ҳ��
     */
    function c_toEditLog() {
        $this->permCheck(); //��ȫУ��
        $dateInfo = $this->service->getLogDate_d($_GET['id']);
        $this->assign('id', $_GET['id']);
        $this->assign('date', $dateInfo[0]['date']);
        $this->assign('returndate', $dateInfo[0]['returndate']);
        $this->view('editlog');
    }

    /**
     * �޸��豸��¼ʱ��
     */
    function c_editLog() {
        $dateInfo = array('id' => $_POST['id'], 'date' => $_POST['date'], 'returndate' => $_POST['returndate']);
        if ($this->service->updateLogDate_d($dateInfo)) {
            msg("�޸ĳɹ���");
        } else {
            msg("�޸�ʧ�ܣ�");
        }
    }

    /**
     * ɾ���豸��¼
     */
    function c_ajaxdelete() {
        echo $this->service->delete_d($_POST['id']) ? 1 : 0;
    }

    /**
     * ��ת����������ҳ��(�����黹/����/ת��)
     */
    function c_toBatch() {
        $this->view('bacth');
    }

    /**
     * �����黹/����/ת��
     */
    function c_batch() {
        $object = $_POST[$this->objName];
        $rows = $this->service->batch_d($object);
        if(empty($rows)){
            msg("û���������");
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
                    $msg = "��ͬ�������ŵ��豸���ܺϲ�����һ�ŵ���";
                    break;
                }
                if($type == 'erenew'){//���������
                    if($projectId == ''){
                        $projectId = $v['projectId'];
                        $flag = $v['flag'];
                    }elseif($projectId != $v['projectId']){
                        $isMsg = true;
                        $msg = "����ʱ,��ͬ��Ŀ���豸���ܺϲ�����һ�ŵ���";
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