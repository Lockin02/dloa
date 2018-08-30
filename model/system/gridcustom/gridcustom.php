<?php

/**
 * ����Զ���model����
 */
class model_system_gridcustom_gridcustom extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_system_gridcustom";
        $this->sql_map = "system/gridcustom/gridcustomSql.php";
        parent::__construct();
    }

    /**
     * ������
     * @param $gridcustom
     * @return int
     */
    function updateCol($gridcustom) {
        try {
            $this->searchArr = array("userId" => $_SESSION['USER_ID'], "customCode" => $gridcustom['customCode'],
                "colName" => $gridcustom['colName']);
            $list = $this->list_d();
            if (is_array($list) && count($list) > 0) {
                $obj = $list[0];
                $gridcustom['id'] = $obj['id'];
                $this->edit_d($gridcustom);
            } else {
                $gridcustom['userId'] = $_SESSION['USER_ID'];
                $this->add_d($gridcustom);
            }

        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return 1;
    }

    /**
     * ������
     * @param $customCode
     * @param $startColName
     * @param $endColName
     * @param $startIndex
     * @param $endIndex
     * @param $userId
     */
    function switchCol($customCode, $startColName, $endColName, $startIndex, $endIndex, $userId) {
        $specialCustomCode = array("myPayablesapplyGrid");
        $startIndex = (in_array($customCode,$specialCustomCode))? $startIndex-1 : $startIndex;
        $endIndex = (in_array($customCode,$specialCustomCode))? $endIndex-1 : $endIndex;
        $tag1 = $this->getCustomGrid($userId, $customCode, $startColName);
        $tag2 = $this->getCustomGrid($userId, $customCode, $endColName);
        if ($tag1 || $tag2) {
            if ($endIndex > $startIndex) {
                $sql = "update " . $this->tbl_name . " set colIndex=colIndex-1 where customCode='" . $customCode .
                    "' and colIndex>" . $startIndex . " and colIndex<=" . $endIndex . " and userId='" . $userId . "'";
                $this->query($sql);
            } else {
                $sql = "update " . $this->tbl_name . " set colIndex=colIndex+1 where customCode='" . $customCode .
                    "' and colIndex<" . $startIndex . " and colIndex>=" . $endIndex . " and userId='" . $userId . "'";
                $this->query($sql);
            }
            $sql = "update " . $this->tbl_name . " set colIndex=" . $endIndex . " where customCode='" . $customCode .
                "' and colName='" . $startColName . "' and userId='" . $userId . "'";
            $this->query($sql);
        }
        if (!$tag1) {
            $gridcustom1 = array();
            $gridcustom1['userId'] = $userId;
            $gridcustom1['customCode'] = $customCode;
            $gridcustom1['colName'] = $startColName;
            $gridcustom1['colIndex'] = $endIndex;
            $gridcustom1['isShow'] = 1;
            $this->add_d($gridcustom1);
        }

        if (!$tag2) {
            $gridcustom2= array();
            $gridcustom2['userId'] = $userId;
            $gridcustom2['customCode'] = $customCode;
            $gridcustom2['colName'] = $endColName;
            if ($endIndex > $startIndex) {
                $gridcustom2['colIndex'] = $endIndex - 1;
            } else {
                $gridcustom2['colIndex'] = $endIndex + 1;
            }
            $gridcustom2['isShow'] = 1;
            $this->add_d($gridcustom2);
        }

        //��һ����Ÿ���
        $sqlPlus = "userId='" . $_SESSION['USER_ID'] . "'  and customCode='" . $customCode . "'";
        $sql = "select count(id) as num,id from oa_system_gridcustom where $sqlPlus group by colIndex having num>1";
        $arr = $this->findSql($sql);
        if (!empty($arr)) {
            foreach ($arr as $key => $val) {
                $num = $val['num'];
                $id = $val['id'];
                $sql = "update oa_system_gridcustom set colIndex=colIndex+1 where $sqlPlus and id!=$id and colIndex>=$num";
                $this->query($sql);
            }
        }

    }

    /**
     * �����˺�,������л�ȡ��Ϣ
     * @param $userId
     * @param $customCode
     * @param $colName
     * @return bool
     */
    function getCustomGrid($userId, $customCode, $colName) {
        $this->searchArr = array("userId" => $userId, "customCode" => $customCode, "colName" => $colName);
        $list = $this->list_d();
        if (is_array($list) && count($list) > 0) {
            return $list[0];
        }
        return false;
    }

    /**
     * �����Զ�����
     * @param $rows
     * @return bool
     */
    function saveCustom_d($rows) {
        try {
            $this->start_d();

            $colIndex = 0;
            foreach ($rows as $k => $v) {
                $this->update(
                    array('id' => $k),
                    array('isShow' => isset($v['isShow']) ? 1 : 0, 'colWidth' => $v['colWidth'], 'colIndex' => $colIndex)
                );
                $colIndex++;
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��ʼ���Զ�����
     * @param $rows
     * @return bool
     */
    function initCustomList_d($rows) {
        try {
            $this->start_d();

            $i = 0;
            foreach ($rows['rows'] as $v) {
                $i++;
                $this->create(
                    array(
                        'isShow' => $v['isShow'] ? 1 : 0,
                        'colWidth' => $v['colWidth'] ? $v['colWidth'] : 100,
                        'colIndex' => $i,
                        'colName' => str_replace('_Col', '', $v['colName']),
                        'userId' => $_SESSION['USER_ID'],
                        'customCode' => $rows['customCode']
                    )
                );
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��ʼ�� - ����ʽ������
     * @param $object
     * @throws Exception
     */
    function initAndFormat_d($object) {
        // ��ʼ������
        $insertArr = array();
        $colNameArr = explode(',', $object['colName']); //��������������
        //���⴦�� - �滻�� _Col ����ַ������������б�
        foreach ($colNameArr as $k => $v) {
            $colNameArr[$k] = str_replace('_Col', '', $v);
        }
        $colWidthArr = explode(',', $object['colWidth']); // ���ƿ�ȵ�����
        $colShowArr = explode(',', $object['colShow']); // ������ʾ������

        //��ȡ��ǰ�б�ֵ
        $list = $this->getCustomList_d($_SESSION['USER_ID'], $object['customCode']);
        // ����û�����Զ������ڵ���
        foreach ($colNameArr as $k => $v) {
            if (!$list || !isset($list[$v])) {
                $insertArr[] = array(
                    'isShow' => $colShowArr[$k],
                    'colWidth' => $colWidthArr[$k] ? $colWidthArr[$k] : 100,
                    'colIndex' => $k,
                    'colName' => $v,
                    'userId' => $_SESSION['USER_ID'],
                    'customCode' => $object['customCode']
                );
            }
        }
        if (!empty($insertArr)) $this->batchAdd_d($insertArr);

        //ɾ����������
        if ($list) {
            $delIdArr = array(); // ��Ҫɾ����id
            foreach ($list as $k => $v) {
                if (!in_array($k, $colNameArr)) {
                    array_push($delIdArr, $v['id']);
                }
            }
            if ($delIdArr) $this->deletes(implode(',', $delIdArr)); // ɾ����������
        }
    }

    /**
     * ��������
     * @param $object
     */
    function batchAdd_d($object) {
        $sql = "INSERT INTO $this->tbl_name (colName,colWidth,colIndex,isShow,customCode,userId) VALUES "; // �����ű�
        if (count($object) == 1) {
            foreach ($object as $v) {
                $sql .= "('" . $v['colName'] . "','" . $v['colWidth'] . "','" . $v['colIndex'] . "','" . $v['isShow'] .
                    "','" . $v['customCode'] . "','" . $v['userId'] . "')";
            }
        } else {
            $mark = false;
            foreach ($object as $v) {
                if ($mark) {
                    $sql .= ",('" . $v['colName'] . "','" . $v['colWidth'] . "','" . $v['colIndex'] . "','" .
                        $v['isShow'] . "','" . $v['customCode'] . "','" . $v['userId'] . "')";
                } else {
                    $sql .= "('" . $v['colName'] . "','" . $v['colWidth'] . "','" . $v['colIndex'] . "','" .
                        $v['isShow'] . "','" . $v['customCode'] . "','" . $v['userId'] . "')";
                    $mark = true;
                }
            }
        }
        $this->_db->query($sql);
    }

    /**
     * ��ȡ��ȡ��̬��ͷ
     * @param $userId
     * @param $customCode
     * @return array
     */
    function getCustomList_d($userId, $customCode) {
        $this->searchArr = array("userId" => $userId, "customCode" => $customCode);
        $this->asc = false;
        $rows = $this->list_d();
        if ($rows) {
            $newRows = array();
            foreach ($rows as $v) {
                $newRows[$v['colName']] = $v;
            }
            $rows = $newRows;
        }
        return $rows;
    }

    /**
     * ���ñ�ͷ�Զ���
     * @param $customCode
     * @return mixed
     */
    function reset_d($customCode) {
        return $this->delete(array(
            'customCode' => $customCode,
            'userId' => $_SESSION['USER_ID']
        ));
    }
}