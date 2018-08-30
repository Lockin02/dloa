<?php

/**
 * @author Administrator
 * @Date 2011年5月17日 14:10:08
 * @version 1.0
 * @description:物料序列号台账控制层
 */
class controller_stock_serialno_serialno extends controller_base_action
{

    function __construct()
    {
        $this->objName = "serialno";
        $this->objPath = "stock_serialno";
        parent::__construct();
    }

    /**
     * 获取所有数据返回json
     */
    function c_listJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $rows = $service->list_d();
        if (empty($rows)) {
            unset($service->searchArr['relDocItemId']);
            $rows = $service->list_d();
        }
        echo util_jsonUtil::encode($rows);
    }

    /**
     * 跳转到物料序列号台账
     */
    function c_page()
    {
        $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

    /**
     * 获取权限
     */
    function c_getLimits()
    {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }

    /**
     * 入库新增序列号
     *
     */
    function c_toAdd()
    {
        $productId = isset($_GET['productId']) ? $_GET['productId'] : null;
        $productCode = isset($_GET['productCode']) ? $_GET['productCode'] : null;
        $productName = isset($_GET['productName']) ? $_GET['productName'] : null;
        $inStockId = isset($_GET['inStockId']) ? $_GET['inStockId'] : null;
        $inStockCode = isset($_GET['inStockCode']) ? $_GET['inStockCode'] : null;
        $inStockName = isset($_GET['inStockName']) ? $_GET['inStockName'] : null;
        $pattern = isset($_GET['pattern']) ? $_GET['pattern'] : null;
        $productNum = isset($_GET['productNum']) ? $_GET['productNum'] : null;
        $elNum = isset($_GET['elNum']) ? $_GET['elNum'] : null;
        $serialSequence = isset($_GET['serialSequence']) ? $_GET['serialSequence'] : null;
        $serialRemark = isset($_GET['serialRemark']) ? $_GET['serialRemark'] : null;
        $serialId = isset($_GET['serialId']) ? $_GET['serialId'] : null;
        if (isset($_GET['productCodeSeNum'])) {
            $serialKey = $_SESSION['USER_ID'] . "_" . $_GET['productCodeSeNum'];
            $serialSequence = util_jsonUtil::iconvUTF2GB($_SESSION[$serialKey]);
        }

        $this->show->assign("productId", $productId);
        $this->show->assign("productName", $productName);
        $this->show->assign("productCode", $productCode);
        $this->show->assign("stockId", $inStockId);
        $this->show->assign("stockName", $inStockName);
        $this->show->assign("stockCode", $inStockCode);
        $this->show->assign("pattern", $pattern);
        $this->show->assign("productNum", $productNum);
        $this->show->assign("elNum", $elNum);
        $this->show->assign("serialSequence", $serialSequence);
        $this->show->assign("serialRemark", $serialRemark);
        $this->show->assign("serialId", $serialId);
        $this->view("add");
    }

    /**
     * 序列号列表批量新增
     */
    function c_toAddBatch()
    {
        $this->view("add-batch");
    }

    /**
     * 初始化对象
     */
    function c_init()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if (isset($_GET['perm']) && $_GET['perm'] == 'view') {
            $this->view('view');
        } else {
            $this->view('edit-temp');
        }
    }

    /**
     * 出库时临时添加库存不存在物料
     */
    function c_toAddTemp()
    {
        $productId = isset($_GET['productId']) ? $_GET['productId'] : null;
        $stockId = isset($_GET['stockId']) ? $_GET['stockId'] : null;
        $this->show->assign("productId", $productId);
        $this->show->assign("stockId", $stockId);
        $this->view("add-temp");
    }

    /**
     *
     * 出库时临时添加库存不存在物料
     */
    function c_toAddTempForRe()
    {
        $this->assignFunc($_GET);
        $this->view("add-tempforre");
    }

    /**
     *
     * 新增保存临时添加物料
     */
    function c_addTemp()
    {
        if ($this->service->addTemp_d($_POST[$this->objName])) {
            echo "<script>self.parent.reloadSerialList();if(self.parent.tb_remove)self.parent.tb_remove();</script>";
        }
    }

    /**
     *
     * 新增保存临时添加物料
     */
    function c_addBatch()
    {
        if ($this->service->addItem_d($_POST[$this->objName])) {
            echo "<script>alert('新增成功!');self.parent.show_page();if(self.parent.tb_remove)self.parent.tb_remove();</script>";
        }
    }

    /**
     * 查看序列号
     */
    function c_toView()
    {
        $serialSequence = isset($_GET['serialSequence']) ? $_GET['serialSequence'] : null;
        $serialRemark = isset($_GET['serialRemark']) ? $_GET['serialRemark'] : null;

        $this->show->assign("serialSequence", $serialSequence);
        $this->show->assign("serialRemark", $serialRemark);
        $this->view("view");
    }

    /**
     * 序列号列表选择
     *
     */
    function c_toChoose()
    {
        $service = $this->service;
        $service->getParam($_GET);

        $rows = $service->list_d();
        if (empty($rows)) {
            unset($service->searchArr['relDocItemId']);
        }

        $isRed = isset($_GET['isRed']) ? $_GET['isRed'] : "";
        if ($isRed == "1") {
            $service->searchArr['seqStatus'] = "1"; //已出库的
        } else {
            $service->searchArr['seqStatus'] = "0"; //在库存中
        }

        // ID2228 发现relDocItemId的值为undefined的时候，数据里relDocItemId该字段为0以外的数据无法获取，暂时这样处理
        if(isset($_REQUEST['relDocItemId']) && $_REQUEST['relDocItemId'] == 'undefined'){
            unset($service->searchArr['relDocItemId']);
        }
        $showpage = new includes_class_page();
        $service->sort = "c.id";
        $rows = $service->pageBySqlId();
        //顾虑掉某些业务中已选择的序列号
        if (isset($_GET['ignoreSerialnoId']) && !empty($_GET['ignoreSerialnoId'])) {
            $ignoreSerialnoIdArr = explode(',', $_GET['ignoreSerialnoId']);
            foreach ($rows as $k => $v) {
                if (in_array($v['id'], $ignoreSerialnoIdArr)) {
                    unset($rows[$k]);
                    $this->service->count--;
                }
            }
        }
        $showpage->show_page(array('total' => $this->service->count, 'perpage' => 10));

        $this->show->assign("isRed", $isRed);
        $this->show->assign("productId", $_GET['productId']);
        $this->show->assign("stockId", $_GET['stockId']);
        $this->show->assign("relDocId", $_GET['relDocId']);
        $this->show->assign("relDocType", $_GET['relDocType']);
        $this->show->assign("relDocCode", $_GET['relDocCode']);
        $this->show->assign("relDocItemId", $_GET['relDocItemId']);
        $this->show->assign("likesequence", $_GET['likesequence']);
        $this->show->assign("pageDiv", $showpage->pageDiv());
        $this->show->assign('list', $service->showChooseList($rows));
        $this->show->assign("ignoreSerialnoId", isset($_GET['ignoreSerialnoId']) ? $_GET['ignoreSerialnoId'] : '');
        $this->view("choose-list");
    }

    /**
     * 物料选择框架
     */
    function c_toChooseFrame()
    {
        $this->show->assign("stockId", $_GET['stockId']);
        $this->show->assign("productId", $_GET['productId']);
        $this->show->assign("elNum", $_GET['elNum']);
        $this->show->assign("serialnoId", $_GET['serialnoId']);
        $this->show->assign("relDocId", $_GET['relDocId']);
        $this->show->assign("relDocType", $_GET['relDocType']);
        $this->show->assign("relDocItemId", $_GET['relDocItemId']);
        if (!isset($_GET['serialnoName'])) {
            if (isset($_GET['productCodeSeNum'])) {
                $serialKey = $_SESSION['USER_ID'] . "_" . $_GET['productCodeSeNum'];
                $serialSequence = util_jsonUtil::iconvUTF2GB($_SESSION[$serialKey]);
                $this->show->assign("serialnoName", $serialSequence);
            }
        } else {
            $this->show->assign("serialnoName", $_GET['serialnoName']);
        }

        $isRed = isset($_GET['isRed']) ? $_GET['isRed'] : "";
        $this->show->assign("isRed", $isRed);
        //不显示的序列号
        $this->show->assign("ignoreSerialnoId", isset($_GET['ignoreSerialnoId']) ? $_GET['ignoreSerialnoId'] : '');
        $this->view("choose-frame");
    }

    /**
     * 归还时，选择序列号
     */
    function c_toChooseFrameForRe()
    {
        $this->show->assign("productId", $_GET['productId']);
        $this->show->assign("elNum", $_GET['elNum']);
        $this->show->assign("relDocCode", $_GET['relDocCode']);
        $this->show->assign("relDocType", $_GET['relDocType']);
        $this->show->assign("relDocId", $_GET['relDocId']);
        $this->show->assign("relDocItemId", $_GET['relDocItemId']);
        $this->show->assign("stockName", "借出仓");
        $this->show->assign("serialnoId", $_GET['serialId']);
        $this->show->assign("serialnoName", $_GET['serialName']);
        //不显示的序列号
        $this->show->assign("ignoreSerialnoId", isset($_GET['ignoreSerialnoId']) ? $_GET['ignoreSerialnoId'] : '');
        $this->view("choose-frameforre");
    }

    /**
     * 跳转到入库时导入序列号页面
     */
    function c_toImportAtInstock()
    {
        $this->view("instock-import");
    }

    /**
     * 入库时导入序列号
     */
    function c_importAtInstock()
    {
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $dao = new model_stock_productinfo_importProductUtil();
            $excelData = $dao->readExcelData($filename, $temp_name);
            spl_autoload_register('__autoload');
            $resultArr = array();
            foreach ($excelData as $value) {
                if (!empty($value[0])) {
                    array_push($resultArr, $value[0]);
                }
            }
            $resultStr = implode($resultArr, ",");
            echo '<div align="center"><input type="hidden" value="' . $resultStr . '" id="excelSequence" />' . '<input type="button" class="txt_btn_a" value="确定关闭" onclick="javascript:window.parent.setExcelSequence(\'' . $resultStr . '\');self.parent.tb_remove()" />' . '&nbsp;&nbsp;&nbsp;<input type="button" class="txt_btn_a" value="重新导入" onclick="history.go(-1)" /></div>';
        } else {
            echo "<script>alert('上传文件类型有错,请重新上传!');history.go(-1);</script>";
        }
    }

    /**
     *
     * 跳转到出库时导入选择序列号页面
     */
    function c_toImportAtOutstock()
    {
        $this->assign("productId", $_GET['productId']);
        $this->assign("stockId", $_GET['stockId']);
        $this->view("outstock-import");
    }

    /**
     * 出库时导入选择序列号
     */
    function c_importAtOutstock()
    {
        $service = $this->service;
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $serialnoObj = $_POST[$this->objName];

        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $dao = new model_stock_productinfo_importProductUtil();
            $excelData = $dao->readExcelData($filename, $temp_name);
            spl_autoload_register('__autoload');
            $idArr = array(); //序列号id
            $sequenceArr = array(); //序列号名称
            foreach ($excelData as $value) {
                if (!empty($value[0])) {
                    $service->searchArr = array("sequence" => $value[0], "productId" => $serialnoObj['productId'], "stockId" => $serialnoObj['stockId']);
                    $findResult = $service->listBySqlId();

                    if (is_array($findResult)) {
                        array_push($idArr, $findResult[0]['id']);
                        array_push($sequenceArr, $value[0]);
                    }
                }
            }
            $idStr = implode($idArr, ",");
            $sequenceStr = implode($sequenceArr, ",");
            //			echo $idStr;
            echo '<div align="center"> <input type="button" class="txt_btn_a" value="确定关闭" onclick="javascript:window.parent.setExcelSequence(\'' . $idStr . '\',\'' . $sequenceStr . '\');self.parent.tb_remove()" />' . '&nbsp;&nbsp;&nbsp;<input type="button" class="txt_btn_a" value="重新导入" onclick="history.go(-1)" /></div>';
        } else {
            echo "<script>alert('上传文件类型有错,请重新上传!');history.go(-1);</script>";
        }
    }

    /**
     *
     * 跳转到序列号查询页面
     */
    function c_toHistorySearch()
    {
        $this->view("history-search");
    }

    function c_serialnoHistory()
    {
        $service = $this->service;
        $serialno = isset($_GET['serialno']) ? $_GET['serialno'] : "";
        if (empty($serialno)) {
            $this->assign("list", "");
        } else {
            $rows = $service->serialnoHistory_d($serialno);
            $this->assign("list", $service->showHistory($rows));
        }

        $this->view("history-list");
    }

    /**
     * 检查序列号是否重复
     * @see controller_base_action::c_checkRepeat()
     */
    function c_checkRepeat()
    {
        $serialNo = isset($_POST['serialNo']) ? $_POST['serialNo'] : false;
        $productCode = isset($_POST['productCode']) ? $_POST['productCode'] : false;
        $productId = isset($_POST['productId']) ? $_POST['productId'] : false;
        $isRed = isset($_POST['isRed']) ? $_POST['isRed'] : false;
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $searchArr = array("sequence" => $serialNo);
        if ($productCode) {
            $searchArr['productCode'] = $productCode;
        }
        if ($productId) {
            $searchArr['productId'] = $productId;
        }

        if ($isRed == "1") { //红字添加序列号
            $searchArr['seqStatus'] = "1"; //已出库的序列号
        } else {
            $searchArr['seqStatus'] = "0"; //库存中的序列号
        }

        if ($this->service->isRepeat($searchArr, $id)) {
            echo "0";
        } else {
            echo "1";
        }
    }

    /**
     *
     * 删除序列号
     */
    function c_ajaxdeletes()
    {
        $this->permDelCheck($_POST['id']);
        try {
            $this->service->deleteSerialno_d($_POST['id']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * 缓存序列号
     *
     */
    function c_cacheSerialno()
    {
        $serialSequence = isset($_POST['serialSequence']) ? $_POST['serialSequence'] : "";
        $productCodeSeNum = isset($_POST['productCodeSeNum']) ? $_POST['productCodeSeNum'] : "";
        $serialKey = $_SESSION['USER_ID'] . "_" . $productCodeSeNum;
        $_SESSION[$serialKey] = $serialSequence;
        echo 1;
    }

    function c_viewRecord()
    {
        $this->view("view-record");
    }

    function c_listBySerialno()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->asc = true;
        $service->sort = 'c.createTime';
        $rows = $service->listBySqlId('select_serialno');
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        echo util_jsonUtil::encode($rows);
    }

    function c_toViewDetail()
    {
        $this->assign("contractId", $_GET['contractId']);
        $this->assign("type", $_GET['type']);
        $this->assign("id", $_GET['id']);
        $this->view("view-detail");
    }

    function c_toViewDetailJson()
    {
        $id = $_POST['id'];
        $type = $_POST['type'];
        $row = $this->service->getSerialRecord($id, $type);
        if ($type == 'allocation' || $type == 'backAllocation') {
            $row = $this->service->showAllocation($row);
        } else if ($type == 'outstock') {
            $row = $this->service->showOutStock($row);
        }
        echo util_jsonUtil::encode($row);
    }

    /**
     * 提供一个通用查看的序列号展示
     */
    function c_toViewFormat()
    {
        $nosArr = explode(',', $_GET['nos']);
        $this->assign('num', count($nosArr));
        $rowNum = 0;
        $trStr = "";
        foreach ($nosArr as $v) {
            if ($rowNum == 0) {
                $trStr .= "<tr>";
            }
            $trStr .= "<td width='20%'>" . $v . "</td>";
            $rowNum++;
            if ($rowNum == 5) {
                $rowNum = 0;
                $trStr .= "</tr>";
            }
        }
        $rowNum != 5 ? $trStr .= "</tr>" : "";
        $this->assign('detail', $trStr);
        $this->view("view-format");
    }

    /**
     * 前端验证序列号
     */
    function c_checkTemp()
    {
        echo util_jsonUtil::encode($this->service->checkTemp_d($_POST['ids']));
    }

    /**
     * ajax 获取各源单id
     */
    function c_ajaxRelDocIdByCode()
    {
        echo $this->service->ajaxRelDocIdByCode_d($_POST['relDocType'], $_POST['relDocCode']);
    }

    /**
     * ajax 获取各源单id
     */
    function c_ajaxGetLimitsById()
    {
        echo $this->service->ajaxGetLimitsById_d($_POST['relDocId']) == "客户" ? "c" : "p";
    }

    /**
     * 获取权限
     */
    function c_ajaxlimit()
    {
        echo $this->service->ajaxlimit_d($_POST['relDocType'], $_POST['relDocId']);
    }

    /**
     * 检测源单是否含有序列号
     */
    function c_checkHasSerialNo()
    {
        $_POST['stockName'] = isset($_POST['stockName']) ? $_POST['stockName'] : '借出仓'; //仓库设置
        $_POST['seqStatus'] = isset($_POST['isRed']) && $_POST['isRed'] == 1 ? 1 : 0; //已出库的
        $this->service->getParam($_POST);
        $rows = $this->service->list_d();
        echo $rows ? util_jsonUtil::encode($rows[0]) : 0;
    }

    /**
     * 初始化序列号关联
     */
    function c_initSerialNo()
    {
        $this->service->initSerialNo_d();
    }
}