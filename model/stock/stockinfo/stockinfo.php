<?php

/**
 * Created on 2010-7-17
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_stock_stockinfo_stockinfo extends model_treeNode
{

    public $db;

    function __construct()
    {
        $this->tbl_name = "oa_stock_baseinfo";
        $this->sql_map = "stock/stockinfo/stockinfoSql.php";
        parent::__construct();
    }

    /*****************************业务处理********************************************/
    /**
     * 重写新增函数
     */
    function add_d($object)
    {
        if (empty($object['parentId'])) {
            $object['parentId'] = -1;
            $object['parentName'] = '仓库根节点';
        }
        return parent::add_d($object);
    }

    /**
     * 重写修改函数
     */
    function edit_d($object)
    {
        return parent::edit_d($object, true);
    }

    /**
     * 获取默认仓库信息，先写死仓库编码，以后有设置默认参考功能后更改
     */
    function getDefaultStockInfo()
    {
        $this->searchArr = array("stockCodeEq" => 'XSCK');
        $arr = $this->list_d();
        return $arr[0];
    }

    /**
     * 根据仓库id判断是否可以删除此仓库
     * $id
     */
    function checkDeleteStock($id)
    {
        //是否已经初始化过库存
        $inventoryDao = new model_stock_inventoryinfo_inventoryinfo ();
        $inventoryDao->searchArr = array("stockId" => $id);
        $result = $inventoryDao->listBySqlId("select_count");
        if ($result[0]['countNum'] > 0)
            return false; //不可以删除
        else
            return true;

    }

    /**
     * 根据编码获取仓库信息
     */
    function getStockByCode($stockCode)
    {
        $this->searchArr['stockCode'] = $stockCode;
        $stockArr = $this->list_d();
        if (is_array($stockArr))
            return $stockArr[0];
        else
            return null;
    }

    /**
     * 根据读取EXCEL中的信息导入到系统中
     * @param $stockArr
     */
    function importStockInfo($stockArr)
    {
        try {
            $this->start_d();
            $resultArr = array();
            $dataDictDao = new model_system_datadict_datadict ();
            $dataDictOpt = $dataDictDao->getDatadictsByParentCodes("CKLX,CKYT");

            foreach ($stockArr as $key => $obj) {
                $stockType = "CKYTXS"; //类型编码
                $stockUseCode = "CKLX-SC"; //仓库用途编码
                foreach ($dataDictOpt['CKLX'] as $dataObj) {
                    if ($dataObj['dataName'] == $obj[3]) {
                        $stockType = $dataObj['dataCode'];
                        break;
                    }
                }
                foreach ($dataDictOpt['CKYT'] as $dataObj2) {
                    if ($dataObj2['dataName'] == $obj[2]) {
                        $stockUseCode = $dataObj2['dataCode'];
                        break;
                    }
                }
                $tempObj = array("stockCode" => $obj[0], "stockName" => $obj[1], "stockType" => $stockType,
                    "stockUseCode" => $stockUseCode, "chargeUserName" => $obj[4], "chargeUserCode" => $obj[5],
                    "adress" => $obj[6], "remark" => $obj[7]);
                $this->searchArr = array("stockCodeEq" => $obj[0]);
                $searchData = $this->list_d();
                if (!is_array($searchData)) {
                    if ($this->add_d($tempObj, true))
                        array_push($resultArr, array("docCode" => $obj[0], "result" => "成功!"));
                } else {
                    array_push($resultArr, array("docCode" => $obj[0], "result" => "仓库代码已经存在!"));
                }

            }

            $this->commit_d();
            return $resultArr;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * 库存校验
     */
    function checkStockBalance_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array(); // 错误的数组
        //判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            if (is_array($excelData)) {
                //这里获取库存信息
                $inventoryInfo = $this->getInventInfo_d();
                //匹配库存信息
                foreach ($excelData as $k => $v) {
                    if (!$v[0] && !$v[1]) continue; // 空数据不处理

                    //库存中没有的部分
                    if (!isset($inventoryInfo[$v[1]][$v[0]])) {
                        $rowNum = $k + 2;
                        array_push($resultArr, array(
                                'docCode' => '行号【' . $rowNum . '】仓库【' . $v[0] . '】编码【' . $v[1] . '】',
                                'result' => '没有匹配到该物料')
                        );
                        continue;
                    }

                    //导入数量与库存数量不符
                    $diffNum = $inventoryInfo[$v[1]][$v[0]] - $v[2];
                    if ($diffNum != 0) {
                        $rowNum = $k + 2;
                        array_push($resultArr, array(
                                'docCode' => '行号【' . $rowNum . '】仓库【' . $v[0] . '】编码【' . $v[1] . '】',
                                'result' => '差异为【' . $diffNum . '】（库存【' . $inventoryInfo[$v[1]][$v[0]] . '】 - 导入数量【' . $v[2] . '】)')
                        );
                        continue;
                    }
                }
                return $resultArr;
            }
        } else {
            exit('导入的文件类型有误');
        }
    }

    /**
     * 获取库存的数据
     */
    function getInventInfo_d()
    {
        $sql = "SELECT c.stockName,i.ext2,c.actNum
            FROM
                oa_stock_inventory_info c
                LEFT JOIN
                oa_stock_product_info i ON c.productId = i.id
            WHERE i.ext2 <> ''";
        $inventoryInfo = $this->_db->getArray($sql);
        if ($inventoryInfo) {
            $rtArray = array(); // 返回数组
            foreach ($inventoryInfo as $v) {
                $rtArray[$v['ext2']][$v['stockName']] = $v['actNum'];
            }
            return $rtArray;
        } else {
            return null;
        }
    }

    /**
     * 返回仓库信息，仓库名 => 仓库id
     * return $map
     */
    function getStockMap_d()
    {
        $map = array();

        // 查询所有仓库
        $data = $this->findAll(null, null, 'id,stockName');

        foreach ($data as $v) {
            $map[$v['stockName']] = $v['id'];
        }

        return $map;
    }
}
