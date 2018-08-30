<?php

/**
 * @author Administrator
 * @Date 2012��2��14�� 9:28:54
 * @version 1.0
 * @description:�����ó�ʼ�����ݱ���Ʋ�
 */
class controller_projectmanagent_borrow_initialize extends controller_base_action
{

    function __construct() {
        $this->objName = "initialize";
        $this->objPath = "projectmanagent_borrow";
        parent::__construct();
    }

    /**
     * ��ת�������ó�ʼ�����ݱ��б�
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * ��ת�����������ó�ʼ�����ݱ�ҳ��
     */
    function c_toAdd() {
        $this->view('add');
    }

    /**
     * ��ת���༭�����ó�ʼ�����ݱ�ҳ��
     */
    function c_toEdit() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * ��ת���鿴�����ó�ʼ�����ݱ�ҳ��
     */
    function c_toView() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }


    /**********************************�����õ���**********************************************************/
    /**
     * ����ҳ��
     */
    function c_toImportExcel() {
        $this->display('importexcel');
    }

    /**
     * �ϴ�EXCEL
     */
    function c_upExcel() {
        // ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
        set_time_limit(0);
        $objNameArr = array(
            0 => 'beginTime', //���ʱ�䣨���ÿ�ʼ���ڣ�
            1 => 'useType', //��;
            2 => 'K3Code', //K3����
            3 => 'productName', //��������
            4 => 'productModel', //�ͺ�
            5 => 'number', //����
            6 => 'deptName', //��������
            7 => 'customerName', //�ͻ�����
            8 => 'userName', //������
            9 => 'seriesNumber', //��Ʒ���к�
            10 => 'closeTime', //Ԥ�ƹ黹����
            11 => 'closeTimeTrue', //ʵ�ʹ黹����
            12 => 'productNoKS', //���ϴ���
            13 => 'Code', //���ݱ��
            14 => 'KCode', //ԭ���ţ�K3ԭ����
            15 => 'outStorage', //�����ֿ�
            16 => 'inStorage', //����ֿ�
            17 => 'dept', //���ţ����ݽ����˻�ȡ��ǰ���ڲ��ţ�
            18 => 'reason', //����ԭ��
            19 => 'seriesNumber', //��Ʒ���кţ���ʱ�������룩
            20 => 'customerName', //�ͻ�
            21 => 'customerInfo', //
            22 => 'remark')//��ע
        ;
        $this->c_addExecel($objNameArr);
    }

    /**
     * �ϴ�EXCEl������������
     * @param $objNameArr
     */
    function c_addExecel($objNameArr) {
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        $customerDao = new model_customer_customer_customer(); // ��ʼ���ͻ���
        $proDao = new model_stock_productinfo_productinfo(); // ��ʼ��������

        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $upexcel = new model_contract_common_allcontract ();
            $excelData = $upexcel->upExcelData($filename, $temp_name);
            spl_autoload_register('__autoload'); //�ı������ķ�ʽ
            if ($excelData) {
                $objectArr = array();
                foreach ($excelData as $rNum => $row) {
                    foreach ($objNameArr as $index => $fieldName) {
                        $objectArr [$rNum] [$fieldName] = $row [$index];
                    }
                }

                $arrinfo = array(); //������Ϣ

                //����ͻ������ò�������Ϣ
                foreach ($objectArr as $key => $val) {
                    if ($val['applyUserName'] == "" && $val['Type'] == "") {
                        unset($objectArr[$key]);
                    } else if ($val['Type'] != "�ͻ�" && $val['Type'] != "Ա��") {
                        array_push($arrinfo, array("orderCode" => $val['Type'], "cusName" => $val['applyUserName'], "result" => "����ʧ�ܣ��������ʴ���"));
                        unset($objectArr[$key]);
                    } else {
                        //�жϽ������Ƿ���ְ���ظ�
                        $userId = $this->borrower($val['applyUserName']);
                        if (empty($userId)) {
                            array_push($arrinfo, array("orderCode" => $val['Type'], "cusName" => $val['applyUserName'], "result" => "����ʧ�ܣ���������Ϣ������"));
                        } else if ($userId == "repeat") {
                            array_push($arrinfo, array("orderCode" => $val['Type'], "cusName" => $val['applyUserName'], "result" => "����ʧ�ܣ���������������Ϣ"));
                        } else {
                            //�жϿͻ��Ƿ����
                            $customerId = $customerDao->findCid($val ['customerName']);
                            if ($val["Type"] == "�ͻ�" && empty($customerId)) {
                                array_push($arrinfo, array("orderCode" => $val['Type'], "cusName" => $val['applyUserName'], "result" => "����ʧ�ܣ��ͻ�Ϊ�ջ򲻴���"));
                            } else {
                                $productInfoArr = $proDao->findBy("ext2", $val['productNoKS']);
                                if (empty($productInfoArr)) {
                                    array_push($arrinfo, array("orderCode" => $val['Type'], "cusName" => $val['applyUserName'], "result" => "����ʧ�ܣ�������Ϣ�����ڻ�K3���ϴ������"));
                                } else {
                                    $cusBorrowAdd = $this->BorrowInfo($val, $userId, $customerId, $productInfoArr);
                                    $borrowId = $this->borrowOne($cusBorrowAdd['applyUserId'], $cusBorrowAdd['productName']);
                                    if (!empty($borrowId)) {
                                        $sql = "update oa_borrow_initialize set number = number + " . $cusBorrowAdd['number'] . " where id = " . $borrowId[0]['id'] . "";
                                        $this->service->_db->query($sql);
                                        array_push($arrinfo, array("orderCode" => $val['Type'], "cusName" => $val['applyUserName'], "result" => "����ɹ�"));
                                    } else {
                                        $id = $this->service->add_d($cusBorrowAdd);
                                        if ($id) {
                                            array_push($arrinfo, array("orderCode" => $val['Type'], "cusName" => $val['applyUserName'], "result" => "����ɹ�"));
                                        } else {
                                            array_push($arrinfo, array("orderCode" => $val['Type'], "cusName" => $val['applyUserName'], "result" => "����ʧ�ܣ�δ֪ԭ��"));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if ($arrinfo) {
                    echo util_excelUtil::showResultOrder($arrinfo, "������", array("��������", "������", "���"));
                }
            } else {
                echo "�ļ������ڿ�ʶ������!";
            }
        } else {
            echo "�ϴ��ļ����Ͳ���EXCEL!";
        }

    }

    /**
     * ת��ʱ���
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
     * �жϽ������Ƿ��ظ�����ְ
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
     * �������������
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
     * �жϳ�ʼ����������� ��Ϣ�Ƿ��Ѵ���
     * @param $applyUserId
     * @param $productName
     * @return mixed
     */
    function borrowOne($applyUserId, $productName) {
        $sql = "select id from oa_borrow_initialize where applyUserId = '$applyUserId' and productName = '$productName'";
        return $this->service->_db->getArray($sql);
    }


    /**
     * ���³�ʼ�����ݵĹ黹����
     */
    function c_renewInitBorrowEquNum() {
        echo $this->service->renewInitBorrowEquNum();
    }

    /**
     * ��ʼ��������
     */
    function c_initExeBorrow() {
        $borrowDao = new model_projectmanagent_borrow_borrow();
        echo $borrowDao->initExeBorrow() ? 1 : 0;
    }
    /**********************************�����õ���*******END*************************************************/

    /**
     * �ͷŲ�ɾ���ɵĽ��õ�
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
     * ���кŴ���
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
     * ���ݲ���Ա�
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