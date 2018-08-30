<?php

/**
 * @author Show
 * @Date 2011��5��31�� ���ڶ� 19:31:17
 * @version 1.0
 * @description:�ڳ�������Ʋ�
 */
class controller_finance_stockbalance_stockbalance extends controller_base_action
{

    function __construct() {
        $this->objName = "stockbalance";
        $this->objPath = "finance_stockbalance";
        parent::__construct();
    }

    /*
     * ��ת���ڳ�����
     */
    function c_page() {
        $thisDate = $this->service->get_table_fields($this->service->tbl_name, '1=1 order by thisDate desc limit 0,1', 'thisDate');
        $this->assign('thisDate', $thisDate);
        $this->view('list');
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_pageJson() {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $rows = $service->page_d();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count ($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        $arr['listSql'] = $service->listSql;

        if (!empty($rows)) {
            $pageCount = 0;
            foreach ($rows as $v) {
                $pageCount = bcadd($pageCount, $v['balanceAmount'], 2);
            }
            $rows[] = array(
                'balanceAmount' => $pageCount,
                'id' => 'noId'
            );
        }
        $arr['collection'] = $rows;
        echo util_jsonUtil::encode($arr);
    }

    /****************************���㲿��*******************************/

    /**
     * �⹺�����㹦��
     */
    function c_calculate() {
        echo $this->service->calculate_d($_POST['thisYear'], $_POST['thisMonth']) ? 1 : 0;
    }

    /****************************��Ʒ������*****************************/
    /**
     * ��Ʒ���ѡ��ҳ��
     */
    function c_toProductInCal() {
        $rs = $this->service->rtThisPeriod_d();
        $this->assignFunc($rs);
        $this->display('toproductincal');
    }

    /**
     * ��Ʒ�������б�
     */
    function c_productInCalList() {
        $isGroupByDept = isset($_GET['isGroupByDept']) ? $_GET['isGroupByDept'] : $_GET['isGroupByDept'] = "";
        $service = $this->service;
        $rows = $service->productInCalList_d($_GET);
        $this->assignFunc($_GET);
        if ($isGroupByDept) {
            $rs = $service->showProductInListDept($rows);
            $this->assign('list', $rs[0]);
            $this->assign('countNum', $rs[1]);
            $this->display('listcalproindept');
        } else {
            $rs = $service->showProductInList($rows);
            $this->assign('list', $rs[0]);
            $this->assign('countNum', $rs[1]);
            $this->display('listcalproin');
        }
    }

    /**
     * ��Ʒ���������
     */
    function c_productInCal() {
        echo $this->service->productInCal_d($_POST) ? 1 : 0;
    }

    /**
     * ��Ʒ�����㵼��
     */
    function c_productInCalExcelOut() {
        $isGroupByDept = isset($_GET['isGroupByDept']) ? $_GET['isGroupByDept'] : null;
        $rows = $this->service->productInCalList_d($_GET);
        if ($isGroupByDept) {
            return model_finance_common_financeExcelUtil::productInCalExcelOutDept($rows);
        } else {
            return model_finance_common_financeExcelUtil::productInCalExcelOut($rows);
        }
    }

    /**
     * ��Ʒ�����㵼��
     */
    function c_toProductInCalExcelIn() {
        $this->display('productincalexcelin');
    }

    /**
     * ��Ʒ�����㵼��
     */
    function c_productInCalExcelIn() {
        $resultArr = $this->service->productInCalExcelIn_d();
        $title = '���������㵼�����б�';
        $thead = array('������Ϣ', '������');
        echo util_excelUtil::showResult($resultArr, $title, $thead, "closeFun();parent.show_page();");
    }

    /**
     * ��Ʒ�����㵼�� - ������
     */
    function c_toProductInCalExcelInDept() {
        $this->display('productincalexcelindept');
    }

    /**
     * ��Ʒ�����㵼��  - ������
     */
    function c_productInCalExcelInDept() {
        $resultArr = $this->service->productInCalExcelInDept_d();
        $title = '���������㵼�����б�';
        $thead = array('������Ϣ', '������');
        echo util_excelUtil::showResult($resultArr, $title, $thead, "closeFun();parent.show_page();");
    }


    /***************************��ӯ������******************************/
    /**
     * ��ӯ���ѡ��ҳ��
     */
    function c_toOverageCalList() {
        $this->display('tooveragecal');
    }

    /**
     * ��ӯ������
     */
    function c_overageCalList() {
        $service = $this->service;
        $rows = $service->overageCalList_d();
        $this->pageShowAssign();
        $rs = $service->showOverageCalList($rows);
        $this->assign('list', $rs[0]);
        $this->assign('countNum', $rs[1]);
        $this->display('listcaloverage');
    }

    /**
     * ��ӯ������
     */
    function c_overageCal() {
        echo $this->service->overageCal_d($_POST) ? 1 : 0;
    }

    /************************************************************************************************/
    /**---------------------------------------������㲿��------------------------------------------**/
    /************************************************************************************************/

    /*****************************���ϳ������****************************/
    /**
     * ��ת�����ϳ������
     */
    function c_toMaterialsCal() {
        $rs = $this->service->rtThisPeriod_d();
        $this->assignFunc($rs);
        $this->display('tomaterialscal');
    }

    /**
     * ���ϳ������
     */
    function c_materialsCal() {
        echo $this->service->materialsCal_d($_POST) ? 1 : 0;
    }
    
    /**
     * ���ϳ������-���¹�����ͬ/�������ϳɱ�
     */
    function c_materialsCostAct() {
    	echo $this->service->materialsCostAct_d($_POST) ? 1 : 0;
    }

    /**
     * ����ת�ʲ����ϵ���
     */
    function c_updateProductAssetPrice() {
        echo $this->service->updateProductAssetPrice_d($_POST) ? 1 : 0;
    }

    /****************************��Ʒ�������******************************/
    /**
     * ��ת����Ʒ�������
     */
    function c_toProductsCal() {
        $rs = $this->service->rtThisPeriod_d();
        $this->assignFunc($rs);
        $this->display('toproductscal');
    }

    /**
     * ��Ʒ�������
     */
    function c_productsCal() {
        echo $this->service->materialsCal_d($_POST, 'WLSXZZ') ? 1 : 0;
    }

    /**
     * ��Ʒ�������-���¹�����ͬ/�������ϳɱ�
     */
    function c_productsCostAct() {
    	echo $this->service->materialsCostAct_d($_POST, 'WLSXZZ') ? 1 : 0;
    }
    
    /**
     * ��Ʒ�������-�������ϳɱ�
     */
    function c_productInfoCost() {
    	echo $this->service->productInfoCost_d($_POST) ? 1 : 0;
    }
    
    /**
     * ���ֳ������ ��ȡ���Ͻ��
     */
    function c_getPrice() {
        echo $price = $this->service->getPrice_d($_POST['productCode'], $_POST['thisVal']);
    }

    /**********************�����ϸ*************************************/
    /**
     * �б�ҳ��
     */
    function c_calDetail() {
        $this->assignFunc($_GET);
        $this->display('listcaldetail');
    }

    /**
     * ��ϸpagejson
     */
    function c_detailPageJson() {
        $rows = $this->service->listDetail_d($_POST);
        $arr = array();
        $arr ['collection'] = $rows;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * �����ϸ �� ȫ��
     */
    function c_allCalDetail() {
        $rows = $this->service->listAllDetail_d($_GET);
        $this->assign('list', $this->service->showAllDetail($rows));
        $this->display('listallcaldetail');
    }

    /**
     * �������б�
     */
    function c_calResultList() {
        $rows = $this->service->listResultProduct_d($_GET);
        $this->assign('list', $this->service->showResultProduct($rows, $_GET));
        $this->display('listcalresult');
    }

    /****************S  ���뵼������ *******************************/
    /**
     * �ڳ�����
     */
    function c_toAddBalance() {
        $year = date("Y");
        $yearStr = "";
        for ($i = $year; $i >= 2005; $i--) {
            $yearStr .= "<option value=$i>" . $i . "��</option>";
        }
        $this->assign('yearStr', $yearStr);
        $rs = $this->service->rtThisPeriod_d(1);
        $this->assignFunc($rs);
        $this->display('toaddbalance');
    }

    /**
     * �ڳ����� - ���ݴ�����
     */
    function c_addBalance() {
        $objKeyArr = array(
            0 => 'stockName',
            1 => 'productNo',
            2 => 'clearingNum',
            3 => 'price',
            4 => 'balanceAmount'
        ); //�ֶ�����
        $resultArr = $this->service->addBalance_d($objKeyArr, $_POST[$this->objName]);
        $title = '�����Ϣ���½���б�';
        $thead = array('������Ϣ', '������');
        echo util_excelUtil::showResult($resultArr, $title, $thead);
    }

    /*********** �����²��� *********************/
    /**
     * �����ڳ����
     */
    function c_toAddProductPrice() {
        $rs = $this->service->rtThisPeriod_d(1);
        $this->assignFunc($rs);
        $this->display('toaddproductprice');
    }

    /**
     * �����ڳ���� - ���ݴ�����
     */
    function c_addProductPrice() {
        $objKeyArr = array(
            0 => 'stockName',
            1 => 'productNo',
            2 => 'clearingNum',
            3 => 'price',
            4 => 'balanceAmount'
        ); //�ֶ�����
        $resultArr = $this->service->addExecelDatabyPro_d($objKeyArr, $_POST[$this->objName]);
        $title = '�����Ϣ���½���б�';
        $thead = array('������Ϣ', '������');
        echo util_excelUtil::showResult($resultArr, $title, $thead);
    }

    /**
     * ��ת������ҳ��
     */
    function c_toExportExcel() {
    	$this->assign('thisDate', $_GET['thisDate']);
    	$this->display('toexportexcel');
    }
    
    /**
     * ����
     */
    function c_exportExcel() {
		set_time_limit(0);	//ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
        ini_set('memory_limit', '1024M');	//�����ڴ�
        
		$service = $this->service;
		if($_POST [$this->objName]['thisDate'] != ""){
			$service->searchArr["thisDate"] = $_POST [$this->objName]['thisDate'];
		}
		$service->asc = false;
		$service->sort = 'c.thisDate DESC,c.stockId ASC,c.productNo';
		$dataArr = $service->list_d();
		if(!empty($dataArr)){
			foreach ($dataArr as &$v){
				if($v['isDeal'] == '1'){
					$v['isDeal'] = '��';
				}else{
					$v['isDeal'] = '��';
				}
			}
		}
		//��ͷ����
		$thArr = array('thisYear' => '��','thisMonth' => '��','stockName' => '�ֿ�����','productNo' => '���ϱ��','k3Code' => 'K3����',
				'productName' => '��������','units' => '��λ','clearingNum' => '��������','balanceAmount' => '�����','isDeal' => '�ѳ���'
		);
		
		return model_finance_common_financeExcelUtil :: export2ExcelUtil($thArr, $dataArr, '�ڳ����');
    }
    /*****************END ���뵼������ *******************************/
}