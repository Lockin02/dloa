<?php

/**
 * @author Show
 * @Date 2011年5月31日 星期二 19:31:17
 * @version 1.0
 * @description:期初余额表控制层
 */
class controller_finance_stockbalance_stockbalance extends controller_base_action
{

    function __construct() {
        $this->objName = "stockbalance";
        $this->objPath = "finance_stockbalance";
        parent::__construct();
    }

    /*
     * 跳转到期初余额表
     */
    function c_page() {
        $thisDate = $this->service->get_table_fields($this->service->tbl_name, '1=1 order by thisDate desc limit 0,1', 'thisDate');
        $this->assign('thisDate', $thisDate);
        $this->view('list');
    }

    /**
     * 获取分页数据转成Json
     */
    function c_pageJson() {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $rows = $service->page_d();
        //数据加入安全码
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

    /****************************核算部分*******************************/

    /**
     * 外购入库核算功能
     */
    function c_calculate() {
        echo $this->service->calculate_d($_POST['thisYear'], $_POST['thisMonth']) ? 1 : 0;
    }

    /****************************产品入库核算*****************************/
    /**
     * 产品入库选择页面
     */
    function c_toProductInCal() {
        $rs = $this->service->rtThisPeriod_d();
        $this->assignFunc($rs);
        $this->display('toproductincal');
    }

    /**
     * 产品入库核算列表
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
     * 产品入库核算操作
     */
    function c_productInCal() {
        echo $this->service->productInCal_d($_POST) ? 1 : 0;
    }

    /**
     * 产品入库核算导出
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
     * 产品入库核算导入
     */
    function c_toProductInCalExcelIn() {
        $this->display('productincalexcelin');
    }

    /**
     * 产品入库核算导入
     */
    function c_productInCalExcelIn() {
        $resultArr = $this->service->productInCalExcelIn_d();
        $title = '自制入库核算导入结果列表';
        $thead = array('数据信息', '导入结果');
        echo util_excelUtil::showResult($resultArr, $title, $thead, "closeFun();parent.show_page();");
    }

    /**
     * 产品入库核算导入 - 按部门
     */
    function c_toProductInCalExcelInDept() {
        $this->display('productincalexcelindept');
    }

    /**
     * 产品入库核算导入  - 按部门
     */
    function c_productInCalExcelInDept() {
        $resultArr = $this->service->productInCalExcelInDept_d();
        $title = '自制入库核算导入结果列表';
        $thead = array('数据信息', '导入结果');
        echo util_excelUtil::showResult($resultArr, $title, $thead, "closeFun();parent.show_page();");
    }


    /***************************盘盈入库核算******************************/
    /**
     * 盘盈入库选择页面
     */
    function c_toOverageCalList() {
        $this->display('tooveragecal');
    }

    /**
     * 盘盈入库核算
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
     * 盘盈入库核算
     */
    function c_overageCal() {
        echo $this->service->overageCal_d($_POST) ? 1 : 0;
    }

    /************************************************************************************************/
    /**---------------------------------------出库核算部分------------------------------------------**/
    /************************************************************************************************/

    /*****************************材料出库核算****************************/
    /**
     * 跳转至材料出库核算
     */
    function c_toMaterialsCal() {
        $rs = $this->service->rtThisPeriod_d();
        $this->assignFunc($rs);
        $this->display('tomaterialscal');
    }

    /**
     * 材料出库核算
     */
    function c_materialsCal() {
        echo $this->service->materialsCal_d($_POST) ? 1 : 0;
    }
    
    /**
     * 材料出库核算-更新关联合同/赠送物料成本
     */
    function c_materialsCostAct() {
    	echo $this->service->materialsCostAct_d($_POST) ? 1 : 0;
    }

    /**
     * 更新转资产物料单价
     */
    function c_updateProductAssetPrice() {
        echo $this->service->updateProductAssetPrice_d($_POST) ? 1 : 0;
    }

    /****************************产品出库核算******************************/
    /**
     * 跳转至产品出库核算
     */
    function c_toProductsCal() {
        $rs = $this->service->rtThisPeriod_d();
        $this->assignFunc($rs);
        $this->display('toproductscal');
    }

    /**
     * 产品出库核算
     */
    function c_productsCal() {
        echo $this->service->materialsCal_d($_POST, 'WLSXZZ') ? 1 : 0;
    }

    /**
     * 产品出库核算-更新关联合同/赠送物料成本
     */
    function c_productsCostAct() {
    	echo $this->service->materialsCostAct_d($_POST, 'WLSXZZ') ? 1 : 0;
    }
    
    /**
     * 产品出库核算-更新物料成本
     */
    function c_productInfoCost() {
    	echo $this->service->productInfoCost_d($_POST) ? 1 : 0;
    }
    
    /**
     * 红字出库核算 获取物料金额
     */
    function c_getPrice() {
        echo $price = $this->service->getPrice_d($_POST['productCode'], $_POST['thisVal']);
    }

    /**********************余额明细*************************************/
    /**
     * 列表页面
     */
    function c_calDetail() {
        $this->assignFunc($_GET);
        $this->display('listcaldetail');
    }

    /**
     * 详细pagejson
     */
    function c_detailPageJson() {
        $rows = $this->service->listDetail_d($_POST);
        $arr = array();
        $arr ['collection'] = $rows;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 余额明细 － 全部
     */
    function c_allCalDetail() {
        $rows = $this->service->listAllDetail_d($_GET);
        $this->assign('list', $this->service->showAllDetail($rows));
        $this->display('listallcaldetail');
    }

    /**
     * 核算结果列表
     */
    function c_calResultList() {
        $rows = $this->service->listResultProduct_d($_GET);
        $this->assign('list', $this->service->showResultProduct($rows, $_GET));
        $this->display('listcalresult');
    }

    /****************S  导入导出部分 *******************************/
    /**
     * 期初余额导入
     */
    function c_toAddBalance() {
        $year = date("Y");
        $yearStr = "";
        for ($i = $year; $i >= 2005; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('yearStr', $yearStr);
        $rs = $this->service->rtThisPeriod_d(1);
        $this->assignFunc($rs);
        $this->display('toaddbalance');
    }

    /**
     * 期初余额导入 - 数据处理部分
     */
    function c_addBalance() {
        $objKeyArr = array(
            0 => 'stockName',
            1 => 'productNo',
            2 => 'clearingNum',
            3 => 'price',
            4 => 'balanceAmount'
        ); //字段数组
        $resultArr = $this->service->addBalance_d($objKeyArr, $_POST[$this->objName]);
        $title = '余额信息更新结果列表';
        $thead = array('数据信息', '导入结果');
        echo util_excelUtil::showResult($resultArr, $title, $thead);
    }

    /*********** 余额更新部分 *********************/
    /**
     * 更新期初余额
     */
    function c_toAddProductPrice() {
        $rs = $this->service->rtThisPeriod_d(1);
        $this->assignFunc($rs);
        $this->display('toaddproductprice');
    }

    /**
     * 更新期初余额 - 数据处理部分
     */
    function c_addProductPrice() {
        $objKeyArr = array(
            0 => 'stockName',
            1 => 'productNo',
            2 => 'clearingNum',
            3 => 'price',
            4 => 'balanceAmount'
        ); //字段数组
        $resultArr = $this->service->addExecelDatabyPro_d($objKeyArr, $_POST[$this->objName]);
        $title = '余额信息更新结果列表';
        $thead = array('数据信息', '导入结果');
        echo util_excelUtil::showResult($resultArr, $title, $thead);
    }

    /**
     * 跳转到导出页面
     */
    function c_toExportExcel() {
    	$this->assign('thisDate', $_GET['thisDate']);
    	$this->display('toexportexcel');
    }
    
    /**
     * 导出
     */
    function c_exportExcel() {
		set_time_limit(0);	//执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        ini_set('memory_limit', '1024M');	//设置内存
        
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
					$v['isDeal'] = '是';
				}else{
					$v['isDeal'] = '否';
				}
			}
		}
		//表头数组
		$thArr = array('thisYear' => '年','thisMonth' => '月','stockName' => '仓库名称','productNo' => '物料编号','k3Code' => 'K3编码',
				'productName' => '物料名称','units' => '单位','clearingNum' => '结算数量','balanceAmount' => '结存金额','isDeal' => '已出单'
		);
		
		return model_finance_common_financeExcelUtil :: export2ExcelUtil($thArr, $dataArr, '期初余额');
    }
    /*****************END 导入导出部分 *******************************/
}