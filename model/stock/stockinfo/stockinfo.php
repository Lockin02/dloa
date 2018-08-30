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

    /*****************************ҵ����********************************************/
    /**
     * ��д��������
     */
    function add_d($object)
    {
        if (empty($object['parentId'])) {
            $object['parentId'] = -1;
            $object['parentName'] = '�ֿ���ڵ�';
        }
        return parent::add_d($object);
    }

    /**
     * ��д�޸ĺ���
     */
    function edit_d($object)
    {
        return parent::edit_d($object, true);
    }

    /**
     * ��ȡĬ�ϲֿ���Ϣ����д���ֿ���룬�Ժ�������Ĭ�ϲο����ܺ����
     */
    function getDefaultStockInfo()
    {
        $this->searchArr = array("stockCodeEq" => 'XSCK');
        $arr = $this->list_d();
        return $arr[0];
    }

    /**
     * ���ݲֿ�id�ж��Ƿ����ɾ���˲ֿ�
     * $id
     */
    function checkDeleteStock($id)
    {
        //�Ƿ��Ѿ���ʼ�������
        $inventoryDao = new model_stock_inventoryinfo_inventoryinfo ();
        $inventoryDao->searchArr = array("stockId" => $id);
        $result = $inventoryDao->listBySqlId("select_count");
        if ($result[0]['countNum'] > 0)
            return false; //������ɾ��
        else
            return true;

    }

    /**
     * ���ݱ����ȡ�ֿ���Ϣ
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
     * ���ݶ�ȡEXCEL�е���Ϣ���뵽ϵͳ��
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
                $stockType = "CKYTXS"; //���ͱ���
                $stockUseCode = "CKLX-SC"; //�ֿ���;����
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
                        array_push($resultArr, array("docCode" => $obj[0], "result" => "�ɹ�!"));
                } else {
                    array_push($resultArr, array("docCode" => $obj[0], "result" => "�ֿ�����Ѿ�����!"));
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
     * ���У��
     */
    function checkStockBalance_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array(); // ���������
        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            if (is_array($excelData)) {
                //�����ȡ�����Ϣ
                $inventoryInfo = $this->getInventInfo_d();
                //ƥ������Ϣ
                foreach ($excelData as $k => $v) {
                    if (!$v[0] && !$v[1]) continue; // �����ݲ�����

                    //�����û�еĲ���
                    if (!isset($inventoryInfo[$v[1]][$v[0]])) {
                        $rowNum = $k + 2;
                        array_push($resultArr, array(
                                'docCode' => '�кš�' . $rowNum . '���ֿ⡾' . $v[0] . '�����롾' . $v[1] . '��',
                                'result' => 'û��ƥ�䵽������')
                        );
                        continue;
                    }

                    //��������������������
                    $diffNum = $inventoryInfo[$v[1]][$v[0]] - $v[2];
                    if ($diffNum != 0) {
                        $rowNum = $k + 2;
                        array_push($resultArr, array(
                                'docCode' => '�кš�' . $rowNum . '���ֿ⡾' . $v[0] . '�����롾' . $v[1] . '��',
                                'result' => '����Ϊ��' . $diffNum . '������桾' . $inventoryInfo[$v[1]][$v[0]] . '�� - ����������' . $v[2] . '��)')
                        );
                        continue;
                    }
                }
                return $resultArr;
            }
        } else {
            exit('������ļ���������');
        }
    }

    /**
     * ��ȡ��������
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
            $rtArray = array(); // ��������
            foreach ($inventoryInfo as $v) {
                $rtArray[$v['ext2']][$v['stockName']] = $v['actNum'];
            }
            return $rtArray;
        } else {
            return null;
        }
    }

    /**
     * ���زֿ���Ϣ���ֿ��� => �ֿ�id
     * return $map
     */
    function getStockMap_d()
    {
        $map = array();

        // ��ѯ���вֿ�
        $data = $this->findAll(null, null, 'id,stockName');

        foreach ($data as $v) {
            $map[$v['stockName']] = $v['id'];
        }

        return $map;
    }
}
