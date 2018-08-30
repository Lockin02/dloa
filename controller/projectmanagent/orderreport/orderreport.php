<?php
class controller_projectmanagent_orderreport_orderreport extends controller_base_action
{

    function __construct() {
        $this->objName = "orderreport";
        $this->objPath = "projectmanagent_orderreport";
        parent::__construct();
    }

    /**
     * 销售月报
     */
    function c_orderMonthReport() {
        if (!empty($_GET["year"])) {
            $year = $_GET["year"];
        } else {
            $year = date("Y");
        }
        if (!empty($_GET["month"])) {
            $month = $_GET["month"];
        } else {
            $month = date("m");
        }
        $this->assign("year", $year);
        $this->assign("month", $month);
        $this->display('monthreport');
    }

    /**
     * 销售月报表查询
     */
    function c_monthSearch() {
        $this->display('monthsearch');
    }

    /**
     * 销售季报
     */
    function c_orderQuarterReport() {
        if (!empty($_GET["year"])) {
            $year = $_GET["year"];
        } else {
            $year = date("Y");
        }
        if (!empty($_GET['areaCode'])) {
            $areaCode = $_GET['areaCode'];
        } else {
            $areaCode = "all";
        }

        $principalId = $_GET['principalId'];
        $this->assign("year", $year);
        $this->assign("areaCode", $areaCode);
        $this->assign("area", $_GET['area']);
        $this->assign("principalId", $principalId);
        $this->assign("principal", $_GET['principal']);
        $this->display('quarterreport');
    }

    /**
     * 销售季报 查询
     */
    function c_quarterSearch() {
        $this->display('quartersearch');
    }

    /**
     * 合同明细表
     */
    function c_orderDetailReport() {
        $beginDT = $_GET['beginDT'];
        $endDT = $_GET['endDT'];
        $orderType = $_GET['orderType'];
        $area = $_GET['area'];
        $principal = $_GET['principal'];
        $customerName = $_GET['customerName'];
        $customerType = $_GET['customerType'];
        $complete = $_GET['complete'];

        $this->assign("beginDT", $beginDT);
        $this->assign("endDT", $endDT);
        $this->assign("orderType", $orderType);
        $this->assign("area", $area);
        $this->assign("principal", $principal);
        $this->assign("customerName", $customerName);
        $this->assign("customerType", $customerType);
        $this->assign("complete", $complete);

        $this->display('detailreport');
    }

    /**
     * 合同明细表  查询
     */
    function c_detailSearch() {
        $this->display('detailSearch');
    }

    /**
     * 发货需求周表
     */
    function c_shipweekReport() {
        $this->assign("beginDate", $_GET['beginDate']);
        $this->assign("endDate", $_GET['endDate']);
        $this->display('shipweekreport');
    }

    /**
     * 发货需求周表查询
     */
    function c_shipweekSearch() {
        $this->assign("beginDate", $_GET['beginDate']);
        $this->assign("endDate", $_GET['endDate']);
        $this->display('shipweeksearch');
    }

    /**
     *合同执行状态表
     */
    function c_toContractExeList() {
        $thisYear = date('Y');
        $yearStr = "";
        for ($i = $thisYear; $i >= 2005; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('yearStr', $yearStr);

        if (isset ($_GET ['thisYear'])) {
            $initArr = $_GET;
        } else {
            $initArr = array(
                'thisYear' => $thisYear
            );
        }
        $this->assignFunc($initArr);

        $this->view("contractexelist");
    }

    /**
     *
     * 合同执行状态表查询页面
     */
    function c_toContractExeSearch() {
        $year = date("Y");
        $yearStr = "";
        for ($i = $year; $i >= 2005; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('yearStr', $yearStr);
        $this->assignFunc($_GET);
        $this->view("contractexesearch");
    }

    /**
     *  物料需求明细表
     */
    function c_equDemandList() {
        echo $_GET ['productName'];
        if (!empty ($_GET ['productName'])) {
            $initArr = $_GET;
        } else {
            $initArr = array(
                'productName' => ""
            );
        }
        $this->assignFunc($initArr);
        $this->view('equDemandList');
    }

    /**
     * 交付物料预警表
     */
    function c_materialWarn() {
        $productName = trim($_GET['productName']);
        $productNo = trim($_GET['productNo']);
        $theCodee = trim($_GET['theCode']);
        $orderTypee = trim($_GET['orderType']);
        $this->assign('productName', $productName);
        $this->assign('productNo', $productNo);
        $this->assign('theCode', $theCodee);
        $this->assign('orderType', $orderTypee);
        $this->view('materialWarn');
    }

    /**
     *
     * 跳转到交付物料预警表查询界面
     */
    function c_searchMaterialWarn() {
        $this->view('searchMaterialWarn');
    }

    /**
     * 借用统计表
     */
    function c_borrowReport(){
        if($_GET['searchType']){
            $this->assignFunc($_GET);
        }else{
            $this->assignFunc(array(
                'searchType' => '','countType' => '','limit' => '30'
            ));
        }
        $this->display('borrowreport');
    }

    /**
     * 借用明细表
     */
    function c_borrowDetailReport(){
        if($_GET['searchType']){
            $this->assignFunc($_GET);
        }else{
            $this->assignFunc(array(
                'searchType' => '','countType' => '','searchKey' => ''
            ));
        }
        $this->display('borrowdetailreport');
    }
}