<?php

/**
 * ����Զ��ƿ��Ʋ���
 */
class controller_system_gridcustom_gridcustom extends controller_base_action
{

    function __construct() {
        $this->objName = "gridcustom";
        $this->objPath = "system_gridcustom";
        parent::__construct();
    }

    /**
     * ��ʼ���Զ�����
     */
    function c_initAndFormat() {
        echo $this->service->initAndFormat_d($_POST);
    }

    /**
     * ����
     */
    function c_reset() {
        if ($_POST['customCode']) {
            echo $this->service->reset_d($_POST['customCode']);
        } else {
            echo 0;
        }
    }

    /**
     * ������
     */
    function c_updateCol() {
        echo $this->service->updateCol($_POST ['gridcustom']);
    }

    /**
     * ���ݶ��Ʊ����ȡ��ǰ�û���������Ϣ
     */
    function c_getCustomCols() {
        $this->service->searchArr = array("userId" => $_SESSION ['USER_ID'], "customCode" => $_POST ['customCode']);
        $list = $this->service->list_d();
        echo util_jsonUtil::encode($list);
    }

    /**
     * ������
     */
    function c_switchCol() {
        $this->service->switchCol($_POST ['customCode'], $_POST ['strartColName'], $_POST ['endColName'],
            $_POST ['startIndex'], $_POST ['endIndex'], $_SESSION ['USER_ID']);
    }

    /**
     * �Զ�������
     */
    function c_customList() {
        $datas['customCode'] = $_GET['customCode'] ? $_GET['customCode'] : exit('û���Զ������');
        //��ʼ����ͷ
        $this->service->initAndFormat_d($_REQUEST);

        //ȡ��
        $this->service->searchArr = array("userId" => $_SESSION ['USER_ID'], "customCode" => $datas['customCode']);
        $this->service->sort = "colIndex";
        $this->service->asc = false;
        $datas['rows'] = $this->service->list_d();
        $datas['customCode'] = $_REQUEST['customCode'];
        $datas['colInfo'] = array_combine(explode(',', str_replace('_Col', '', $_GET['colName'])), explode(',', $_GET['colText']));
        $this->displayPT('customlist', $datas);
    }

    /**
     * �����Զ���������
     */
    function c_saveCustom() {
        echo $this->service->saveCustom_d($_POST[$this->objName]);
    }

    /**
     * ��ʼ���Զ�����
     */
    function c_initCustomList() {
        $list = $this->service->getCustomList_d($_SESSION['USER_ID'], $_POST['customCode']);
        echo util_jsonUtil::encode($list);
    }

    /**
     * �����Զ�������
     */
    function c_setCustomSearch() {
        $data = $_POST;
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $ki => $vi) {
                    if (util_jsonUtil::is_utf8($vi)) {
                        $data[$k][$ki] = util_jsonUtil::iconvUTF2GB($vi);
                    }
                }   
            }
        }

        if($_POST['customCode'] == "auditingGrid" || $_POST['customCode'] == "auditedGrid"){
            $selectedsettingDao = new model_common_workflow_selectedsetting();
            $gridCondition = util_jsonUtil::encode($data);
			$gridCondition = str_replace("\\","\\\\",$gridCondition);
            switch ($_POST['customCode']){
                case 'auditingGrid':
                    $selectedsettingDao->updateUserRecord("auditing",$gridCondition);
                    break;
                case 'auditedGrid':
                    $selectedsettingDao->updateUserRecord("audited",$gridCondition);
                    break;
            }

            echo util_jsonUtil::encode(array(
                "msg" => "",
                "result" => "ok"
            ));
        }else{
            $_SESSION["GRID_" . $_POST['customCode']] = $data;
            echo util_jsonUtil::encode(array(
                "msg" => "",
                "result" => "ok"
            ));
        }
    }

    /**
     * ��ȡ�Զ�������
     */
    function c_getCustomSearch() {
        $customCode = $_POST['customCode'];
        if ($customCode == 'auditingGrid' || $customCode == 'auditedGrid') {
            $selectedsettingDao = new model_common_workflow_selectedsetting();
            $conditionsJson = '';
            switch ($_POST['customCode']){
                case 'auditingGrid':
                    $conditionsJson = $selectedsettingDao->rtUserSelected_d("auditing");
                    break;
                case 'auditedGrid':
                    $conditionsJson = $selectedsettingDao->rtUserSelected_d("audited");
                    break;
            }

            $gridCondition = ($conditionsJson == '')? array() : util_jsonUtil::decode($conditionsJson);
            $gridCondition = empty($gridCondition)? array() : util_jsonUtil::iconvUTF2GBArr($gridCondition);

            $rs = array_merge(array(
                "msg" => "",
                "result" => "ok"
            ), $gridCondition);
        } else {
            $rs = array(
                "msg" => "notSet",
                "result" => "ok"
            );
        }
        echo util_jsonUtil::encode($rs);
    }
}
