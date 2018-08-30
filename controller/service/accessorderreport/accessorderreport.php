<?php

class controller_service_accessorderreport_accessorderreport extends controller_base_action {

    function __construct() {
        $this->objName = "accessorderreport";
        $this->objPath = "service_accessorderreport";
        parent::__construct();
    }
    /**
     * ��������շ���ϸ����
     */
    function c_accessOrderDetailReport() {
        if (!empty($_GET["year"])) {
            $year = $_GET["year"];
        } else {
            $year = date("Y");
        }
        $this->assign("year", $year);
        $this->display('detailreport');
    }
    //�������
    function c_searchYear() {
        if (!empty($_GET["year"])) {
            $year = $_GET["year"];
        } else {
            $year = date("Y");
        }
        $this->assign("year", $year);
        $this->display('searchreport');
    }

}

?>