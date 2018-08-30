<?php

/**
 * ��֯�������Ʋ�
 * @author chris
 */
class controller_deptuser_dept_dept extends controller_base_action
{

    function __construct() {
        $this->objName = "dept";
        $this->objPath = "deptuser_dept";
        parent::__construct();
    }

    /**
     * ��ת����֯����ѡ��ҳ��
     */
    function c_selectdept() {
        $this->assign("mode", $_GET['mode']); // ��֯����ѡ��ģʽ
        $this->assign("deptVal", $_GET['deptVal']); // ѡ�еĻ���ֵ
        $this->assign("deptFilter", $_GET['deptFilter']); // ���˵Ļ���ֵ
        $this->assign("unDeptFilter", (isset($_GET['unDeptFilter']) && $_GET['unDeptFilter'] != 'unDeptFilter' && $_GET['unDeptFilter'] != 'undefined')? $_GET['unDeptFilter'] : ''); // ���˵Ļ���ֵ
        $this->assign("unSltDeptFilter", isset($_GET['unSltDeptFilter'])? $_GET['unSltDeptFilter'] : ''); // ���˲���ѡ�Ļ���ֵ
        $this->assign("targetId", $_GET['targetId']); // Ŀ��id
        $this->assign("disableDeptLevel", $_GET['disableDeptLevel']); // ����ѡ��Ĳ��ż���
        $this->assign("height", isset($_GET['mini']) && $_GET['mini'] == 1 ? 120 : 365); // ���ڴ�С
        $this->view("select");
    }

    /**
     * �첽��ȡ��֯������
     */
    function c_tree() {
        $service = $this->service;
        if (empty ($_POST['comCode']) || $_POST['comCode'] === 'undefined') {
            $rows = $service->getCompanyList_d();
        } else {
            if (empty ($_POST['Depart_x']) || $_POST['Depart_x'] === 'undefined') {
                //�㼶�����û��Depart_x����ȡ�������
                $searchArr = array("Dflag" => 0, 'DelFlag' => 0, 'comCode' => $_POST['comCode']);
            } else {
                $searchArr = array(
                    "Depart_x" => $_POST['Depart_x'], "Dflag" => $_POST['Dflag'] + 1,
                    'DelFlag' => 0, "comCode" => $_POST['comCode']
                );
            }
            if (isset($_POST['deptFilter']) && !empty($_POST['deptFilter']))
                $searchArr['deptFilter'] = $_POST['deptFilter'];

            if (isset($_POST['unDeptFilter']) && !empty($_POST['unDeptFilter'])){
                $unDeptFilterArr = explode(",",$_POST['unDeptFilter']);
                foreach ($unDeptFilterArr as $k => $v){
                    if($v == '' || !is_numeric($v)){
                        unset($unDeptFilterArr[$k]);
                    }
                }
                $unDeptFilterStr = implode($unDeptFilterArr,",");
                $searchArr['unDeptFilter'] = $unDeptFilterStr;
            }

            $service->searchArr = $searchArr;
            $rows = $service->list_d();
        }

        //���Ƿ�Ҷ��ֵ0ת��false��1ת��true
        function toBoolean($row) {
            $row['isParent'] = $row['hasChildren'] == 0 ? 'false' : 'true';
            $row['hasChildren'] = $row['hasChildren'] ? 'true' : 'false';
            $row['icon'] = 'dept';
            return $row;
        }

        echo util_jsonUtil::encode(array_map("toBoolean", $rows));
    }

    /**
     * ͬ����ȡ��֯������
     */
    function c_alltree() {
        $this->service->searchArr = array(
            'deptName' => $_POST['deptName'], 'DelFlag' => 0
        );
        if (isset($_POST['deptFilter']) && !empty($_POST['deptFilter']))
            $this->service->searchArr['deptFilter'] = $_POST['deptFilter'];

        if (isset($_POST['unDeptFilter']) && !empty($_POST['unDeptFilter']))
            $this->service->searchArr['unDeptFilter'] = $_POST['unDeptFilter'];
        $rows = $this->service->list_d();
        //���Ƿ�Ҷ��ֵ0ת��false��1ת��true
        function toBoolean($row) {
            $row['isParent'] = count($row['nodes']) == 0 ? 'false' : 'true';
            $row['hasChildren'] = $row['hasChildren'] ? 'true' : 'false';
            return $row;
        }

        echo util_jsonUtil::encode(array_map("toBoolean", $rows));
    }

    /**���ݲ���ID����ȡ��������
     *author can
     *2011-8-18
     */
    function c_getDeptName() {
        $deptId = isset($_POST['deptId']) ? $_POST['deptId'] : "";
        $deptName = $this->service->getDeptName_d($deptId);
        echo $deptName['DEPT_NAME'];
    }

    /**���ݲ���ID����ȡ���ŵȼ�
     *author can
     *2011-8-18
     */
    function c_getDeptLevel() {
        $deptId = isset($_POST['deptId']) ? $_POST['deptId'] : "";
        $levelflag = $this->service->getDeptLevel_d($deptId);
        echo $levelflag['levelflag'];
    }


    /**���ݲ���ID����ȡ���ŵ������ϼ�������Ϣ
     *author can
     *2011-9-26
     */
    function c_getDeptInfo() {
        $deptId = isset($_POST['deptId']) ? $_POST['deptId'] : "";
        $levelflag = isset($_POST['levelflag']) ? $_POST['levelflag'] : "";
        $deptRow = $this->service->getSuperiorDeptById_d($deptId, $levelflag);
        echo util_jsonUtil::encode($deptRow);
    }


    /**���ݲ���ID����ȡ���ŵ������ϼ�������Ϣ
     *author can
     *2011-9-26
     */
    function c_getDept() {
        $deptId = isset($_POST['deptId']) ? $_POST['deptId'] : "";
        $deptRow = $this->service->find(array('DEPT_ID' => $deptId));
        echo util_jsonUtil::encode($deptRow);
    }
}