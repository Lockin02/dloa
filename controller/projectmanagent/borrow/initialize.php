<?php

/**
 * @author Administrator
 * @Date 2012年2月14日 9:28:54
 * @version 1.0
 * @description:借试用初始化数据表控制层
 */
class controller_projectmanagent_borrow_initialize extends controller_base_action
{

    function __construct() {
        $this->objName = "initialize";
        $this->objPath = "projectmanagent_borrow";
        parent::__construct();
    }

    /**
     * 跳转到借试用初始化数据表列表
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * 跳转到新增借试用初始化数据表页面
     */
    function c_toAdd() {
        $this->view('add');
    }

    /**
     * 跳转到编辑借试用初始化数据表页面
     */
    function c_toEdit() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * 跳转到查看借试用初始化数据表页面
     */
    function c_toView() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }


    /**********************************借试用导入**********************************************************/
    /**
     * 导入页面
     */
    function c_toImportExcel() {
        $this->display('importexcel');
    }

    /**
     * 上传EXCEL
     */
    function c_upExcel() {
        // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        set_time_limit(0);
        $objNameArr = array(
            0 => 'beginTime', //借出时间（借用开始日期）
            1 => 'useType', //用途
            2 => 'K3Code', //K3编码
            3 => 'productName', //物料名称
            4 => 'productModel', //型号
            5 => 'number', //数量
            6 => 'deptName', //部门名称
            7 => 'customerName', //客户名称
            8 => 'userName', //借用人
            9 => 'seriesNumber', //产品序列号
            10 => 'closeTime', //预计归还日期
            11 => 'closeTimeTrue', //实际归还日期
            12 => 'productNoKS', //物料代码
            13 => 'Code', //单据编号
            14 => 'KCode', //原单号（K3原单）
            15 => 'outStorage', //调出仓库
            16 => 'inStorage', //调入仓库
            17 => 'dept', //部门（根据借用人获取当前所在部门）
            18 => 'reason', //借用原因
            19 => 'seriesNumber', //产品序列号（暂时不做导入）
            20 => 'customerName', //客户
            21 => 'customerInfo', //
            22 => 'remark')//备注
        ;
        $this->c_addExecel($objNameArr);
    }

    /**
     * 上传EXCEl并导入其数据
     * @param $objNameArr
     */
    function c_addExecel($objNameArr) {
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        $customerDao = new model_customer_customer_customer(); // 初始化客户类
        $proDao = new model_stock_productinfo_productinfo(); // 初始化物料类

        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $upexcel = new model_contract_common_allcontract ();
            $excelData = $upexcel->upExcelData($filename, $temp_name);
            spl_autoload_register('__autoload'); //改变加载类的方式
            if ($excelData) {
                $objectArr = array();
                foreach ($excelData as $rNum => $row) {
                    foreach ($objNameArr as $index => $fieldName) {
                        $objectArr [$rNum] [$fieldName] = $row [$index];
                    }
                }

                $arrinfo = array(); //导入信息

                //处理客户借试用并保存信息
                foreach ($objectArr as $key => $val) {
                    if ($val['applyUserName'] == "" && $val['Type'] == "") {
                        unset($objectArr[$key]);
                    } else if ($val['Type'] != "客户" && $val['Type'] != "员工") {
                        array_push($arrinfo, array("orderCode" => $val['Type'], "cusName" => $val['applyUserName'], "result" => "导入失败，借用性质错误"));
                        unset($objectArr[$key]);
                    } else {
                        //判断借用人是否离职或重复
                        $userId = $this->borrower($val['applyUserName']);
                        if (empty($userId)) {
                            array_push($arrinfo, array("orderCode" => $val['Type'], "cusName" => $val['applyUserName'], "result" => "导入失败，借用人信息不存在"));
                        } else if ($userId == "repeat") {
                            array_push($arrinfo, array("orderCode" => $val['Type'], "cusName" => $val['applyUserName'], "result" => "导入失败，借用人有重名信息"));
                        } else {
                            //判断客户是否存在
                            $customerId = $customerDao->findCid($val ['customerName']);
                            if ($val["Type"] == "客户" && empty($customerId)) {
                                array_push($arrinfo, array("orderCode" => $val['Type'], "cusName" => $val['applyUserName'], "result" => "导入失败，客户为空或不存在"));
                            } else {
                                $productInfoArr = $proDao->findBy("ext2", $val['productNoKS']);
                                if (empty($productInfoArr)) {
                                    array_push($arrinfo, array("orderCode" => $val['Type'], "cusName" => $val['applyUserName'], "result" => "导入失败，物料信息不存在或K3物料代码错误"));
                                } else {
                                    $cusBorrowAdd = $this->BorrowInfo($val, $userId, $customerId, $productInfoArr);
                                    $borrowId = $this->borrowOne($cusBorrowAdd['applyUserId'], $cusBorrowAdd['productName']);
                                    if (!empty($borrowId)) {
                                        $sql = "update oa_borrow_initialize set number = number + " . $cusBorrowAdd['number'] . " where id = " . $borrowId[0]['id'] . "";
                                        $this->service->_db->query($sql);
                                        array_push($arrinfo, array("orderCode" => $val['Type'], "cusName" => $val['applyUserName'], "result" => "导入成功"));
                                    } else {
                                        $id = $this->service->add_d($cusBorrowAdd);
                                        if ($id) {
                                            array_push($arrinfo, array("orderCode" => $val['Type'], "cusName" => $val['applyUserName'], "result" => "导入成功"));
                                        } else {
                                            array_push($arrinfo, array("orderCode" => $val['Type'], "cusName" => $val['applyUserName'], "result" => "导入失败，未知原因"));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if ($arrinfo) {
                    echo util_excelUtil::showResultOrder($arrinfo, "导入结果", array("借用性质", "借用人", "结果"));
                }
            } else {
                echo "文件不存在可识别数据!";
            }
        } else {
            echo "上传文件类型不是EXCEL!";
        }

    }

    /**
     * 转换时间戳
     * @param $timestamp
     * @return bool|string
     */
    function transitionTime($timestamp) {
        $time = "";
        if (!empty($timestamp)) {
            $wirteDate = mktime(0, 0, 0, 1, $timestamp - 1, 1900);
            $time = date("Y-m-d", $wirteDate);
        }
        return $time;
    }

    /**
     * 判断借用人是否重复或离职
     * @param $name
     * @return string
     */
    function borrower($name) {
        $Dao = new model_common_otherdatas();
        $userId = $Dao->getUserID($name);
        if (!empty($userId[1])) {
            return "repeat";
        } else {
            $userInfoArr = $Dao->getUserDatas($userId[0]['USER_ID']);
            if ($userInfoArr['HAS_LEFT'] == 1) {
                return "leave ";
            } else {
                return $userId[0]['USER_ID'];
            }
        }
    }

    /**
     * 处理借试用数据
     * @param $cusBorrow
     * @param $userId
     * @param $customerId
     * @param $productInfoArr
     * @return array
     */
    function BorrowInfo($cusBorrow, $userId, $customerId, $productInfoArr) {
        $Dao = new model_common_otherdatas();
        $userInfoArr = $Dao->getUserDatas($userId);
        $addArr = array();
        $addArr['Type'] = $cusBorrow['Type'];
        $addArr['applyUserName'] = $cusBorrow['applyUserName'];
        $addArr['applyUserId'] = $userId;
        $addArr['customerName'] = $cusBorrow['customerName'];
        $addArr['customerId'] = $customerId[0]['id'];
        $addArr['customerInfo'] = $cusBorrow['customerInfo'];
        $addArr['dept'] = $userInfoArr['DEPT_NAME'];
        $addArr['deptId'] = $userInfoArr['DEPT_ID'];

        $addArr['productId'] = $productInfoArr['id'];
        $addArr['productCode'] = $productInfoArr['productCode'];
        $addArr['productName'] = $productInfoArr['productName'];
        $addArr['productModel'] = $productInfoArr['pattern'];
        $addArr['number'] = $cusBorrow['number'];
        $addArr['unitName'] = $productInfoArr['unitName'];

        $addArr['createName'] = $_SESSION ['USERNAME'];
        $addArr['createId'] = $_SESSION ['USER_ID'];
        $addArr['createTime'] = date('Y-m-d');

        return $addArr;
    }

    /**
     * 判断初始化导入的数据 信息是否已存在
     * @param $applyUserId
     * @param $productName
     * @return mixed
     */
    function borrowOne($applyUserId, $productName) {
        $sql = "select id from oa_borrow_initialize where applyUserId = '$applyUserId' and productName = '$productName'";
        return $this->service->_db->getArray($sql);
    }


    /**
     * 更新初始化数据的归还数量
     */
    function c_renewInitBorrowEquNum() {
        echo $this->service->renewInitBorrowEquNum();
    }

    /**
     * 初始化借试用
     */
    function c_initExeBorrow() {
        $borrowDao = new model_projectmanagent_borrow_borrow();
        echo $borrowDao->initExeBorrow() ? 1 : 0;
    }
    /**********************************借试用导入*******END*************************************************/

    /**
     * 释放并删除旧的借用单
     */
    function c_initClearData() {
        set_time_limit(0);
        $this->service->_db->query("UPDATE oa_borrow_borrow c INNER JOIN oa_stock_product_serialno s
            ON c.id = s.relDocId AND s.relDocType = 'oa_borrow_borrow'
            SET s.relDocCode = '',s.relDocId = NULL,s.relDocType = NULL
            WHERE c.beginTime < '2013-01-01' AND (UNIX_TIMESTAMP(createTime) < UNIX_TIMESTAMP() - 864000 OR createTime IS NULL)");

        $this->service->_db->query("DELETE FROM oa_borrow_borrow
            WHERE beginTime < '2013-01-01' AND (UNIX_TIMESTAMP(createTime) < UNIX_TIMESTAMP() - 864000 OR createTime IS NULL)");

        echo 'ok';
    }

    /**
     * 序列号处理
     */
    function c_initSerialno() {
        set_time_limit(0);
        $data = $this->service->_db->getArray("SELECT reason,id,Code FROM oa_borrow_borrow
          WHERE beginTime < '2013-01-01' AND UNIX_TIMESTAMP(createTime) > UNIX_TIMESTAMP('2014-11-01') AND reason <> ''");
        foreach ($data as $k => $v) {
            $codeArr = explode('/', str_replace(',', '/', $v['reason']));
            $this->service->_db->query("UPDATE oa_stock_product_serialno SET relDocCode = '" . $v['Code']
                . "',relDocId = '" . $v['id'] . "'
              WHERE seqStatus = 0 AND relDocType = 'oa_borrow_borrow' AND sequence IN('" . implode("','", $codeArr)
                . "')");
        }
        echo 'ok';
    }

    /**
     * 数据差异对比
     */
    function c_initBorrowTemp() {
        set_time_limit(0);
        $this->service->tbl_name = 'oa_borrow_temp';
        $this->service->_db->query("UPDATE oa_borrow_temp SET salesman = created WHERE customerName IS NULL;");
        $data = $this->service->_db->getArray("SELECT
            b.customerName,b.customerId,
            b.salesName AS salesman,b.createName AS created,
            YEAR(b.beginTime) AS beginYear,
            e.productId,i.ext2 k3Code,e.productNo AS oaCode,
            e.productName,SUM(e.executedNum - e.backNum) AS oaNum,
            'oa' AS inType
            FROM
                oa_borrow_equ e
                LEFT JOIN
                oa_borrow_borrow b ON e.borrowId=b.id
                LEFT JOIN
                oa_stock_product_info i ON e.productId = i.id
            WHERE b.isTemp = 0 AND e.isTemp = 0 AND e.executedNum>e.backNum AND YEAR(beginTime) >= 2013 AND b.id <= 7623
            AND DATE_FORMAT(beginTime,'%Y%m') <= 201410
            GROUP BY b.customerName,e.productNo,b.salesName,YEAR(b.beginTime)
            ORDER by b.beginTime,b.Code,e.productid");
        foreach ($data as $v) {
            if ($v['customerName']) {
                $info = $this->service->_db->get_one("SELECT id FROM oa_borrow_temp WHERE customerName = '" .
                    $v['customerName'] . "' AND salesman = '" . $v['salesman'] ."' AND productId = '" . $v['productId'] .
                    "' AND beginYear = '" . $v['beginYear'] ."'");
                if ($info) {
                    $this->service->_db->query("UPDATE oa_borrow_temp SET oaNum = oaNum + 1 WHERE id = ".$info['id']);
                } else {
                    $this->service->create($v);
                }
            } else {
                $info = $this->service->_db->get_one("SELECT id FROM oa_borrow_temp WHERE customerName IS NULL" .
                    " AND salesman = '" . $v['salesman'] ."' AND productId = '" . $v['productId'] .
                    "' AND beginYear = '" . $v['beginYear'] ."'");
                if ($info) {
                    $this->service->_db->query("UPDATE oa_borrow_temp SET oaNum = oaNum + 1 WHERE id = ".$info['id']);
                } else {
                    $this->service->create($v);
                }
            }
        }
        $this->service->_db->query("UPDATE oa_borrow_temp SET diff = k3Num - oaNum;");
    }
}