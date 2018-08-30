<?php

class controller_stock_report_stockreport extends controller_base_action
{

    function __construct()
    {
        $this->objName = "stockreport";
        $this->objPath = "stock_report";
        parent::__construct();
    }

    function c_toKucun()
    {
        $this->display("kucuntz");
    }

    function c_toDisplayData()
    {
        $this->display("display");
    }

    /**
     *
     * 物料出入库流水账
     */
    function c_toInOutItemReport()
    {
        $this->display("inoutitem");
    }

    /**
     *
     * 物料借用归还汇总表
     */
    function c_toProBRSubReport()
    {
        $this->assign('productId', $_GET['productId']);
        if ($_GET['beginYear']) {
            $this->assign('beginMonth', $_GET['beginMonth']);
            $this->assign('beginYear', $_GET['beginYear']);
            $this->assign('endMonth', $_GET['endMonth']);
            $this->assign('endYear', $_GET['endYear']);
        } else {
            $this->assign('beginMonth', date("m") + 0);
            $this->assign('beginYear', date("Y"));
            $this->assign('endMonth', date("m") + 0);
            $this->assign('endYear', date("Y"));
        }
        $this->display("brsub");
    }

    /**
     *
     * 物料收发汇总报表
     */
    function c_toProSFSubReport()
    {
        $this->assign('productCode', $_GET['productCode']);
        $this->assign('productName', $_GET['productName']);
        $this->assign('productId', $_GET['productId']);
        if ($_GET['beginYear']) {
            $this->assign('beginMonth', $_GET['beginMonth']);
            $this->assign('beginYear', $_GET['beginYear']);
            $this->assign('endMonth', $_GET['endMonth']);
            $this->assign('endYear', $_GET['endYear']);
        } else {
            $this->assign('beginMonth', date("m") + 0);
            $this->assign('beginYear', date("Y"));
            $this->assign('endMonth', date("m") + 0);
            $this->assign('endYear', date("Y"));
        }
        $this->display("sfsub");
    }

    /**
     *
     * 物料收发汇总报表查询页面
     */
    function c_toProSFSubSearch()
    {
        $month = date("m") + 0;
        $year = date("Y");
        $yearStr = "";
        for ($i = $year; $i >= 2010; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('monthStr', $month);
        $this->assign('yearStr', $yearStr);
        $this->view("sfsub-search");
    }

    /**
     *
     * 物料库存台账报表
     */
    function c_toProSFItemReport()
    {
        $periodDao = new model_finance_period_period();
        //期初年份,期初月份
        if (!isset($_GET['year']) && !isset($_GET['month'])) {
            $rs = $periodDao->find(array('isUsing' => 1, 'businessBelong' => $_SESSION['Company']), null, 'thisYear,thisMonth');
            if (!empty($rs)) {
                $year = $rs['thisYear'];
                $month = $rs['thisMonth'];
            } else {
                $year = date("Y");
                $month = date("m");
            }
        } else {
            $year = $_GET['year'];
            $month = $_GET['month'];
        }
        //期末年份,期末月份
        $nextYear = isset($_GET['nextYear']) ? $_GET['nextYear'] : $year;
        $nextMonth = isset($_GET['nextMonth']) ? $_GET['nextMonth'] : $month;

        $this->assign('year', $year);
        $this->assign('month', $month);
        $this->assign('nextYear', $nextYear);
        $this->assign('nextMonth', $nextMonth);
        $this->assign('productNo', isset($_GET['productNo']) ? $_GET['productNo'] : ""); //物料编码
        $this->assign('productName', isset($_GET['productName']) ? $_GET['productName'] : ""); //物料名称
        $this->assign('k3Code', isset($_GET['k3Code']) ? $_GET['k3Code'] : ""); //k3编码
        $this->assign('isStock', isset($_GET['isStock']) ? $_GET['isStock'] : ""); //显示分库
        $this->view("sfitem");
    }

    /**
     *
     * 物料库存台账报表查询页面
     */
    function c_toProSFItemSearch()
    {
        $month = date("m") + 0;
        $year = date("Y");
        $yearStr = "";
        for ($i = $year - 3; $i <= $year + 3; $i++) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('monthStr', $month);
        $this->assign('yearStr', $yearStr);
        $this->assign("beginYear", isset ($_GET['beginYear']) ? $_GET['beginYear'] : $year);
        $this->assign("beginMonth", isset ($_GET['beginMonth']) ? $_GET['beginMonth'] : $month);
        $this->assign("endYear", isset ($_GET['endYear']) ? $_GET['endYear'] : $year);
        $this->assign("endMonth", isset ($_GET['endMonth']) ? $_GET['endMonth'] : $month);
        $this->assign("productId", isset ($_GET['productId']) ? $_GET['productId'] : "");
        $this->assign("productCode", isset ($_GET['productCode']) ? $_GET['productCode'] : "");
        $this->assign("productName", isset ($_GET['productName']) ? $_GET['productName'] : "");
        $this->view("sfitem-search");
    }

    /**
     *
     * 物料收发明细报表
     */
    function c_toProSFDetailReport()
    {
        $periodDao = new model_finance_period_period();
        //期初年份,期初月份
        if (!isset($_GET['year']) && !isset($_GET['month'])) {
            $rs = $periodDao->find(array('isUsing' => 1, 'businessBelong' => $_SESSION['Company']), null, 'thisYear,thisMonth');
            if (!empty($rs)) {
                $endYear = $year = $rs['thisYear'];
                $endMonth = $month = $rs['thisMonth'] > 9 ? $rs['thisMonth'] : '0' . $rs['thisMonth'];
            } else {
                $endYear = $year = date("Y");
                $endMonth = $month = date("m");
            }
        } else {
            $year = $_GET['year'];
            $month = $_GET['month'];
            $endYear = $_GET['endYear'];
            $endMonth = $_GET['endMonth'];
        }
        //期末年份,期末月份
        if ($endMonth == 12) {
            $nextYear = $endYear + 1;
            $nextMonth = '01';
        } else {
            $nextYear = $endYear;
            $nextMonth = $endMonth + 1;
        }
        $this->assign('year', $year);
        $this->assign('month', $month);
        $this->assign('nextYear', $nextYear);
        $this->assign('nextMonth', $nextMonth);
        $this->assign('endYear', $endYear);
        $this->assign('endMonth', $endMonth);
        $this->assign('productNo', isset($_GET['productNo']) ? $_GET['productNo'] : ""); //物料编码
        $this->assign('k3Code', isset($_GET['k3Code']) ? $_GET['k3Code'] : ""); //k3编码
        $this->assign('stockId', isset($_GET['stockId']) ? $_GET['stockId'] : ""); //仓库id
        $this->view("sfdetail");
    }

    /**
     *
     * 物料收发明细报表查询页面
     */
    function c_toProSFDetailSearch()
    {
        $month = date("m") + 0;
        $year = date("Y");
        $yearStr = "";
        for ($i = $year; $i >= 2010; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('monthStr', $month);
        $this->assign('yearStr', $yearStr);
        $this->view("sfdetail-search");
    }

    /**
     * 采购发票报表
     */
    function c_toPurInvReport()
    {
        $month = date('m') * 1;
        $object = array('beginYear' => date('Y'), 'beginMonth' => $month, 'endYear' => date('Y'), 'endMonth' => $month, 'supplierId' => '', 'supplierName' => '', 'productId' => '', 'productCode' => '', 'catchStatus' => '', 'isRed' => '', 'remark' => '');

        $object = isset ($_GET['beginYear']) ? $_GET : $object;
        $stockinDao = new model_stock_instock_stockin();

        $moneyLimit = $stockinDao->getMoneyLimit('入库金额');
        $this->assign('moneyLimit', $moneyLimit);

        $this->assignFunc($object);
        $this->display("purinvreport");
    }

    /**
     * 采购发票报表搜索页面
     */
    function c_toPurInvReportSearch()
    {
        $month = date('m') * 1;
        $object = array('beginYear' => date('Y'), 'beginMonth' => $month, 'endYear' => date('Y'), 'endMonth' => $month, 'supplierId' => '', 'supplierName' => '', 'productId' => '', 'productCode' => '', 'catchStatus' => '', 'isRed' => '', 'remark' => '');
        $object = isset ($_GET['beginYear']) ? $_GET : $object;
        $this->assignFunc($object);

        $month = date("m") + 0;
        $year = date("Y");
        $yearStr = "";
        for ($i = $year; $i >= 2010; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('monthStr', $month);
        $this->assign('yearStr', $yearStr);
        $this->view("purinvreport-search");
    }

    /**
     *
     * 生产领料汇总表
     */
    function c_toProPkSubReport()
    {
        $beginDate = isset ($_GET['beginDate']) ? $_GET['beginDate'] : "2011-09-01";
        $endDate = isset ($_GET['endDate']) ? $_GET['endDate'] : "2011-09-30";

        $this->assign('beginDate', $beginDate);
        $this->assign('endDate', $endDate);
        $stockoutDao = new model_stock_outstock_stockout();

        $priceLimit = $stockoutDao->getMoneyLimit('单价');
        $this->assign('priceLimit', $priceLimit);
        $subPriceLimit = $stockoutDao->getMoneyLimit('金额');
        $this->assign('subPriceLimit', $subPriceLimit);

        $this->display("pickingsub");
    }

    /**
     *
     * 生产领料汇总表搜索页面
     */
    function c_toProPkSubSearch()
    {
        $beginDate = isset ($_GET['beginDate']) ? $_GET['beginDate'] : "2011-09-01";
        $endDate = isset ($_GET['endDate']) ? $_GET['endDate'] : "2011-09-30";

        $this->assign('beginDate', $beginDate);
        $this->assign('endDate', $endDate);

        //		$month = date ( "m" ) + 0;
        //		$year = date ( "Y" );
        //		$yearStr = "";
        //		for($i = $year; $i >= 2010; $i --) {
        //			$yearStr .= "<option value=$i>" . $i . "年</option>";
        //		}
        //		$this->assign ( 'beginDate', $month );
        //		$this->assign ( 'beginDate', $yearStr );
        $this->view("pickingsub-search");
    }

    /**
     *
     * 物料信息明细账
     */
    function c_toProItemDetail()
    {
        $service = $this->service;
        $productId = isset ($_GET['productId']) ? $_GET['productId'] : "";
        $beginYear = isset ($_GET['beginYear']) ? $_GET['beginYear'] : "";
        $beginMonth = isset ($_GET['beginMonth']) ? $_GET['beginMonth'] : "";
        $endYear = isset ($_GET['endYear']) ? $_GET['endYear'] : "";
        $endMonth = isset ($_GET['endMonth']) ? $_GET['endMonth'] : "";
        $months = (($endYear + 0) - ($beginYear + 0)) * 12 + (($endMonth + 0) - ($beginMonth + 0)) + 1;
        if ($months <= 0) {
            echo "<script>alert('起始期次大于结束期次，请正确输入！');history.go(-1);</script>";
            return 0;
        }
        //物料基本信息
        //		$productDao = new model_stock_productinfo_productinfo ();
        //		$productDao->searchArr = array ("id" => $productId );
        //		$productArr = $productDao->listBySqlId ();
        //		foreach ( $productArr[0] as $key => $value ) {
        //			$this->assign ( $key, $value );
        //		}
        //		$this->show->assign ( "properties", $this->getDataNameByCode ( $productArr[0]['properties'] ) );


        $itemArr = array();
        $productIdArr = explode(",", $productId);
        $resultListStr = "";
        foreach ($productIdArr as $proIdKey => $proId) {
            if ($months == 1) {
                //$this->assign ( "kjPeriod", $beginYear . "年" . $beginMonth . "月" );
                //物料明细台账
                $itemArr = $service->findProItemDetail($proId, $beginYear, $beginMonth);
                //$this->assign ( "itemList", $service->showProItemDetail ( $itemArr ) );
                $resultListStr .= $service->showProItemDetail($itemArr);
            } else {
                $thisMonth = $beginMonth + 0;
                $thisYear = $beginYear + 0;
                $itemStr = '';
                for ($i = 0; $i < $months; $i++) {
                    $itemArr = $service->findProItemDetail($proId, $thisYear, $thisMonth);
                    $itemStr .= $service->showProItemDetail($itemArr);
                    if (++$thisMonth > 12) {
                        $thisMonth = 1;
                        ++$thisYear;
                    }
                }
                $resultListStr .= $itemStr;

                //$this->assign ( "itemList", $itemStr );
            }
        }

        $this->assign("productCode", $_GET['productCode']);
        $this->assign("productName", $_GET['productName']);
        $this->assign("itemList", $resultListStr);
        $this->assign("kjPeriod", $beginYear . "年" . $beginMonth . "月&nbsp;~&nbsp;" . $endYear . "年" . $endMonth . "月");

        $this->view("proitem-detail");

    }

    /**
     *
     * 物料信息明细账搜索页面
     */
    function c_toProItemSearch()
    {
        $month = date("m") + 0;
        $year = date("Y");
        $yearStr = "";
        for ($i = $year; $i >= 2010; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('monthStr', $month);
        $this->assign('yearStr', $yearStr);
        $this->view("proitem-search");
    }

    /**
     * 出库单明细单 add by chengl
     */
    function c_toStockoutDetail()
    {
        $this->display("stockout-detail");
    }

    /**
     *
     * 采购成本明细表
     */
    function c_toStockinPurchase()
    {
        $month = date('m') * 1;
        $object = array('beginYear' => date('Y'), 'beginMonth' => $month, 'endYear' => date('Y'), 'endMonth' => $month, 'supplierId' => '', 'supplierName' => '', 'productId' => '', 'productCode' => '', 'catchStatus' => '', 'isRed' => '', 'remark' => '');

        $object = isset ($_GET['beginYear']) ? $_GET : $object;
        $stockinDao = new model_stock_instock_stockin();

        $moneyLimit = $stockinDao->getMoneyLimit('入库金额');
        $this->assign('moneyLimit', $moneyLimit);

        $this->assignFunc($object);

        $this->display("stockin-purshase-detail");
    }

    /**
     *
     * 采购成本明细表搜索页面
     */
    function c_toStockinPurSearch()
    {
        $year = date("Y");
        $yearStr = "";
        for ($i = $year; $i >= 2005; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('yearStr', $yearStr);
        $this->assignFunc($_GET);

        $this->showDatadicts(array('catchStatus' => 'CGFPZT'), $_GET['catchStatus']);

        $this->display("stockin-purchase-search");
    }

    /**
     *
     * 销售成本明细表
     */
    function c_toStockoutSales()
    {
        $year = date('Y');
        $month = date('n');
        $object = array(
            'beginYear' => $year, 'beginMonth' => $month, 'endYear' => $year, 'endMonth' => $month,
            'customerName' => '', 'customerId' => '', 'objCode' => '', 'objId' => '', 'isRed' => '',
            'productCode' => '', 'k3Code' => ''
        );
        $object = array_merge($object, $_GET);
        $this->assignFunc($object);
        $stockoutDao = new model_stock_outstock_stockout();
        $priceLimit = $stockoutDao->getMoneyLimit('单价');
        $this->assign('priceLimit', $priceLimit);
        $subPriceLimit = $stockoutDao->getMoneyLimit('金额');
        $this->assign('subPriceLimit', $subPriceLimit);
        $this->display("stockout-sales-detail");
    }

    /**
     *
     * 销售成本明细表搜索页面
     */
    function c_toStockoutSalesSearch()
    {
        $year = date("Y");
        $yearStr = "";
        for ($i = $year; $i >= 2005; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('yearStr', $yearStr);
        $year = date('Y');
        $month = date('n');
        $object = array(
            'beginYear' => $year, 'beginMonth' => $month, 'endYear' => $year, 'endMonth' => $month,
            'customerName' => '', 'customerId' => '', 'objCode' => '', 'objId' => '', 'isRed' => '',
            'productCode' => '', 'k3Code' => ''
        );
        $object = array_merge($object, $_GET);
        $this->assignFunc($object);

        $this->display("stockout-sales-search");
    }

    /**
     *
     * 调拨单明细表
     */
    function c_toAllocationItem()
    {
        $month = date('m') * 1;
        $object = array('beginYear' => date('Y'), 'beginMonth' => $month, 'endYear' => date('Y'), 'endMonth' => $month, 'productId' => '', 'productCode' => '', 'productName' => '');
        $object = isset ($_GET['beginYear']) ? $_GET : $object;
        $this->assignFunc($object);
        $this->display("allocation-detail");
    }

    /**
     *
     * 调拨单明细表搜索页面
     */
    function c_toAlloctSearch()
    {
        $year = date("Y");
        $yearStr = "";
        for ($i = $year; $i >= 2005; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('yearStr', $yearStr);
        $this->assignFunc($_GET);

        $this->display("allocation-search");
    }

    /**
     *
     * 产品入库明细表
     */
    function c_toStockinProduct()
    {
        $month = date('m') * 1;
        $object = array('beginYear' => date('Y'), 'beginMonth' => $month, 'endYear' => date('Y'), 'endMonth' => $month, 'productId' => '', 'productCode' => '', 'docStatus' => '', 'isRed' => '');

        $object = isset ($_GET['beginYear']) ? $_GET : $object;
        $this->assignFunc($object);
        $stockinDao = new model_stock_instock_stockin();

        $moneyLimit = $stockinDao->getMoneyLimit('入库金额');
        $this->assign('moneyLimit', $moneyLimit);

        $this->display("stockin-product-detail");
    }

    /**
     *
     * 产品入库明细表搜索页面
     */
    function c_toStockinProductSearch()
    {
        $year = date("Y");
        $yearStr = "";
        for ($i = $year; $i >= 2005; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('yearStr', $yearStr);
        $this->assignFunc($_GET);

        $this->display("stockin-product-search");
    }

    /**
     *
     * 其他入库明细表
     */
    function c_toStockinOther()
    {
        $month = date('m') * 1;
        $object = array('beginYear' => date('Y'), 'beginMonth' => $month, 'endYear' => date('Y'), 'endMonth' => $month, 'productId' => '', 'productCode' => '', 'supplierId' => '', 'supplierName' => '', 'docStatus' => '', 'isRed' => '');

        $object = isset ($_GET['beginYear']) ? $_GET : $object;
        $this->assignFunc($object);
        $stockinDao = new model_stock_instock_stockin();

        $moneyLimit = $stockinDao->getMoneyLimit('入库金额');
        $this->assign('moneyLimit', $moneyLimit);

        $this->display("stockin-other-detail");
    }

    /**
     *
     * 其他入库明细表搜索页面
     */
    function c_toStockinOtherSearch()
    {
        $year = date("Y");
        $yearStr = "";
        for ($i = $year; $i >= 2005; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('yearStr', $yearStr);
        $this->assignFunc($_GET);

        $this->display("stockin-other-search");
    }

    /**
     *
     * 领料出库明细表
     */
    function c_toStockoutPicking()
    {
        $month = date('m') * 1;
        $object = array('beginYear' => date('Y'), 'beginMonth' => $month, 'endYear' => date('Y'), 'endMonth' => $month, 'productId' => '', 'productCode' => '', 'deptCode' => '', 'deptName' => '', 'pickCode' => '', 'pickName' => '');

        $object = isset ($_GET['beginYear']) ? $_GET : $object;

        $this->assignFunc($object);
        $stockoutDao = new model_stock_outstock_stockout();
        $priceLimit = $stockoutDao->getMoneyLimit('单价');
        $this->assign('priceLimit', $priceLimit);
        $subPriceLimit = $stockoutDao->getMoneyLimit('金额');
        $this->assign('subPriceLimit', $subPriceLimit);

        $this->display("stockout-picking-detail");
    }

    /**
     *
     * 领料出库明细表搜索页面
     */
    function c_toStockoutPickingSearch()
    {
        $year = date("Y");
        $yearStr = "";
        for ($i = $year; $i >= 2005; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('yearStr', $yearStr);
        $this->assignFunc($_GET);

        $this->display("stockout-picking-search");
    }

    /**
     *
     * 其他出库明细表
     */
    function c_toStockoutOther()
    {
        $month = date('m') * 1;
        $object = array('beginYear' => date('Y'), 'beginMonth' => $month, 'endYear' => date('Y'), 'endMonth' => $month, 'productId' => '', 'productCode' => '', 'customerId' => '', 'customerName' => '', 'deptCode' => '', 'deptName' => '', 'pickCode' => '', 'pickName' => '');

        $object = isset ($_GET['beginYear']) ? $_GET : $object;

        $this->assignFunc($object);
        $stockoutDao = new model_stock_outstock_stockout();
        $priceLimit = $stockoutDao->getMoneyLimit('单价');
        $this->assign('priceLimit', $priceLimit);
        $subPriceLimit = $stockoutDao->getMoneyLimit('金额');
        $this->assign('subPriceLimit', $subPriceLimit);

        $this->display("stockout-other-detail");
    }

    /**
     *
     * 其他出库明细表搜索页面
     */
    function c_toStockoutOtherSearch()
    {
        $year = date("Y");
        $yearStr = "";
        for ($i = $year; $i >= 2005; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('yearStr', $yearStr);
        $this->assignFunc($_GET);

        $this->display("stockout-other-search");
    }

    /**
     *
     * 设备交付明细表
     */
    function c_toProOutItem()
    {
        if (isset ($_GET['beginDate'])) {
            $initArr = $_GET;
        } else {
            $thisYear = date('Y');
            $thisMonth = date('m') * 1;
            //初始化数组
            $initArr = array("beginDate" => date('Y-m-d'), "endDate" => date('Y-m-d'), "productId" => "", "productCode" => "", "productName" => "", "customerName" => "", "customerId" => "", "contractId" => "", "contractCode" => "", "contractName" => "");
        }
        //		print_r($initArr);
        //		echo date('Y-m-d');
        $this->assignFunc($initArr);
        $this->view("prooutitem-detail");
    }

    /**
     *
     * 设备交付明细表搜索页面
     */
    function c_toProOutItemSearch()
    {
        //		$year = date ( "Y" );
        //		$yearStr = "";
        //		for($i = $year; $i >= 2005; $i --) {
        //			$yearStr .= "<option value=$i>" . $i . "年</option>";
        //		}
        //		$this->assign ( 'yearStr', $yearStr );
        //		print_r($_GET);
        $this->assignFunc($_GET);
        $this->view("prooutitem-search");
    }

    /**
     *
     * 设备交付明细表
     */
    function c_toProOutSub()
    {
        $thisYear = date('Y');
        //初始化数组
        $initArr = array("thisYear" => $thisYear);
        $this->assignFunc($initArr);
        $this->view("prooutsub-detail");
    }

    /**
     *
     * 设备交付明细表搜索页面
     */
    function c_toProOutSubSearch()
    {
        $this->assignFunc($_GET);
        $this->view("prooutsub-search");
    }


    /**************************** 新库存报表 ************************/
    /**
     * 新库存报表
     */
    function c_toProductStockBalance()
    {
        if ($_GET['thisYear']) {
            $initArr = $_GET;
        } else {
            $thisYear = date('Y');
            //初始化数组
            $initArr = array("thisYear" => $thisYear);
        }
        $this->assignFunc($initArr);

        //设置仓库
        $this->assignFunc($this->getDefaultStock());

        $this->view("productstockbalance");
    }

    /**
     * 获取默认库存仓库
     */
    function getDefaultStock()
    {
        $rtArr = array();
        include(WEB_TOR . "includes/config.php");
        $defaultReportBalanceStock = isset($defaultReportBalanceStock) ? $defaultReportBalanceStock : array();
        $rtArr['balanceStock'] = implode(',', array_keys($defaultReportBalanceStock));

        $defaultReportOldStock = isset($defaultReportOldStock) ? $defaultReportOldStock : array();
        $rtArr['oldStock'] = implode(',', array_keys($defaultReportOldStock));
        return $rtArr;
    }

    /**
     * 新库存报表 - 库存明细表
     */
    function c_toProductStockDetail()
    {
        if ($_GET['thisDate']) {
            $initArr = $_GET;
        } else {
            //初始化数组
            $initArr = array(
                "thisDate" => day_date,
                "stock" => "",
                "productNo" => ""
            );
        }
        $this->assignFunc($initArr);
        $stockinDao = new model_stock_instock_stockin();

        $moneyLimit = $stockinDao->getMoneyLimit('入库金额');
        $this->assign('moneyLimit', $moneyLimit);

        $this->view("productstockdetail");
    }

    /**
     * 新库存报表
     */
    function c_toProductStockChange()
    {
        $year = date("Y");
        $yearStr = "";
        for ($i = $year; $i >= 2005; $i--) {
            $yearStr .= "<option value='$i'>" . $i . "年</option>";
        }
        $this->assign('yearStr', $yearStr);

        //月
        $month = date('m');
        $monthStr = "";
        for ($i = 1; $i <= 12; $i++) {
            if ($i == $month) {
                $monthStr .= "<option value='$i' selected='selected'>" . $i . "月</option>";
            } else {
                $monthStr .= "<option value='$i'>" . $i . "月</option>";
            }
        }
        $this->assign('monthStr', $monthStr);

        $this->view("productstockchange");
    }
}

?>