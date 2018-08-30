<?php

/**
 * @author Administrator
 * @Date 2011��5��17�� 14:10:08
 * @version 1.0
 * @description:�������к�̨�˿��Ʋ�
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
     * ��ȡ�������ݷ���json
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
     * ��ת���������к�̨��
     */
    function c_page()
    {
        $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

    /**
     * ��ȡȨ��
     */
    function c_getLimits()
    {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }

    /**
     * ����������к�
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
     * ���к��б���������
     */
    function c_toAddBatch()
    {
        $this->view("add-batch");
    }

    /**
     * ��ʼ������
     */
    function c_init()
    {
        $this->permCheck(); //��ȫУ��
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
     * ����ʱ��ʱ��ӿ�治��������
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
     * ����ʱ��ʱ��ӿ�治��������
     */
    function c_toAddTempForRe()
    {
        $this->assignFunc($_GET);
        $this->view("add-tempforre");
    }

    /**
     *
     * ����������ʱ�������
     */
    function c_addTemp()
    {
        if ($this->service->addTemp_d($_POST[$this->objName])) {
            echo "<script>self.parent.reloadSerialList();if(self.parent.tb_remove)self.parent.tb_remove();</script>";
        }
    }

    /**
     *
     * ����������ʱ�������
     */
    function c_addBatch()
    {
        if ($this->service->addItem_d($_POST[$this->objName])) {
            echo "<script>alert('�����ɹ�!');self.parent.show_page();if(self.parent.tb_remove)self.parent.tb_remove();</script>";
        }
    }

    /**
     * �鿴���к�
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
     * ���к��б�ѡ��
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
            $service->searchArr['seqStatus'] = "1"; //�ѳ����
        } else {
            $service->searchArr['seqStatus'] = "0"; //�ڿ����
        }

        // ID2228 ����relDocItemId��ֵΪundefined��ʱ��������relDocItemId���ֶ�Ϊ0����������޷���ȡ����ʱ��������
        if(isset($_REQUEST['relDocItemId']) && $_REQUEST['relDocItemId'] == 'undefined'){
            unset($service->searchArr['relDocItemId']);
        }
        $showpage = new includes_class_page();
        $service->sort = "c.id";
        $rows = $service->pageBySqlId();
        //���ǵ�ĳЩҵ������ѡ������к�
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
     * ����ѡ����
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
        //����ʾ�����к�
        $this->show->assign("ignoreSerialnoId", isset($_GET['ignoreSerialnoId']) ? $_GET['ignoreSerialnoId'] : '');
        $this->view("choose-frame");
    }

    /**
     * �黹ʱ��ѡ�����к�
     */
    function c_toChooseFrameForRe()
    {
        $this->show->assign("productId", $_GET['productId']);
        $this->show->assign("elNum", $_GET['elNum']);
        $this->show->assign("relDocCode", $_GET['relDocCode']);
        $this->show->assign("relDocType", $_GET['relDocType']);
        $this->show->assign("relDocId", $_GET['relDocId']);
        $this->show->assign("relDocItemId", $_GET['relDocItemId']);
        $this->show->assign("stockName", "�����");
        $this->show->assign("serialnoId", $_GET['serialId']);
        $this->show->assign("serialnoName", $_GET['serialName']);
        //����ʾ�����к�
        $this->show->assign("ignoreSerialnoId", isset($_GET['ignoreSerialnoId']) ? $_GET['ignoreSerialnoId'] : '');
        $this->view("choose-frameforre");
    }

    /**
     * ��ת�����ʱ�������к�ҳ��
     */
    function c_toImportAtInstock()
    {
        $this->view("instock-import");
    }

    /**
     * ���ʱ�������к�
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
            echo '<div align="center"><input type="hidden" value="' . $resultStr . '" id="excelSequence" />' . '<input type="button" class="txt_btn_a" value="ȷ���ر�" onclick="javascript:window.parent.setExcelSequence(\'' . $resultStr . '\');self.parent.tb_remove()" />' . '&nbsp;&nbsp;&nbsp;<input type="button" class="txt_btn_a" value="���µ���" onclick="history.go(-1)" /></div>';
        } else {
            echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');history.go(-1);</script>";
        }
    }

    /**
     *
     * ��ת������ʱ����ѡ�����к�ҳ��
     */
    function c_toImportAtOutstock()
    {
        $this->assign("productId", $_GET['productId']);
        $this->assign("stockId", $_GET['stockId']);
        $this->view("outstock-import");
    }

    /**
     * ����ʱ����ѡ�����к�
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
            $idArr = array(); //���к�id
            $sequenceArr = array(); //���к�����
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
            echo '<div align="center"> <input type="button" class="txt_btn_a" value="ȷ���ر�" onclick="javascript:window.parent.setExcelSequence(\'' . $idStr . '\',\'' . $sequenceStr . '\');self.parent.tb_remove()" />' . '&nbsp;&nbsp;&nbsp;<input type="button" class="txt_btn_a" value="���µ���" onclick="history.go(-1)" /></div>';
        } else {
            echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');history.go(-1);</script>";
        }
    }

    /**
     *
     * ��ת�����кŲ�ѯҳ��
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
     * ������к��Ƿ��ظ�
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

        if ($isRed == "1") { //����������к�
            $searchArr['seqStatus'] = "1"; //�ѳ�������к�
        } else {
            $searchArr['seqStatus'] = "0"; //����е����к�
        }

        if ($this->service->isRepeat($searchArr, $id)) {
            echo "0";
        } else {
            echo "1";
        }
    }

    /**
     *
     * ɾ�����к�
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
     * �������к�
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
        //���ݼ��밲ȫ��
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
     * �ṩһ��ͨ�ò鿴�����к�չʾ
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
     * ǰ����֤���к�
     */
    function c_checkTemp()
    {
        echo util_jsonUtil::encode($this->service->checkTemp_d($_POST['ids']));
    }

    /**
     * ajax ��ȡ��Դ��id
     */
    function c_ajaxRelDocIdByCode()
    {
        echo $this->service->ajaxRelDocIdByCode_d($_POST['relDocType'], $_POST['relDocCode']);
    }

    /**
     * ajax ��ȡ��Դ��id
     */
    function c_ajaxGetLimitsById()
    {
        echo $this->service->ajaxGetLimitsById_d($_POST['relDocId']) == "�ͻ�" ? "c" : "p";
    }

    /**
     * ��ȡȨ��
     */
    function c_ajaxlimit()
    {
        echo $this->service->ajaxlimit_d($_POST['relDocType'], $_POST['relDocId']);
    }

    /**
     * ���Դ���Ƿ������к�
     */
    function c_checkHasSerialNo()
    {
        $_POST['stockName'] = isset($_POST['stockName']) ? $_POST['stockName'] : '�����'; //�ֿ�����
        $_POST['seqStatus'] = isset($_POST['isRed']) && $_POST['isRed'] == 1 ? 1 : 0; //�ѳ����
        $this->service->getParam($_POST);
        $rows = $this->service->list_d();
        echo $rows ? util_jsonUtil::encode($rows[0]) : 0;
    }

    /**
     * ��ʼ�����кŹ���
     */
    function c_initSerialNo()
    {
        $this->service->initSerialNo_d();
    }
}