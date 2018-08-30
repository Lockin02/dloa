<?php
header("Content-type: text/html; charset=gb2312");

/**
 * ��֯����model����
 */
class model_deptuser_dept_dept extends model_base
{

    function __construct() {
        $this->tbl_name = "department";
        $this->sql_map = "deptuser/dept/deptSql.php";
        parent::__construct();
    }

    /**
     * �첽������֯����
     * @param $deptName
     * @return mixed
     */
    function tree_d($deptName) {
        $this->searchArr = array('DelFlag' => 0);
        if (!empty ($deptName)) {
            $this->searchArr ['deptName'] = $deptName;
        }
        return $this->list_d();
    }

    /**
     * ����֯������װ����������
     * @param $depts
     * @return array
     */
    function changeDeptsToTree($depts) {
        $this->sort = 'Dflag';
        $this->asc = false;
        $allDepts_tmp = $this->list_d();
        foreach ($allDepts_tmp as $key => $value) {
            $allDepts [$value ['Depart_x']] = $value;
        }
        $deptsMap = array();
        foreach ($depts as $k => $v) {
            //�����һ���ڵ�
            if ($v ['Dflag'] == 0) {
                $depart_x = $v ['Depart_x'];
                if (empty ($deptsMap [$depart_x])) {
                    $deptsMap [$depart_x] = $v;
                    unset ($allDepts [$depart_x]);
                    $children = $this->searchChildren($v, $allDepts);
                    if (is_array($children) && count($children) > 0) {
                        $deptsMap [$depart_x] ['nodes'] = array_merge($children, $deptsMap [$depart_x] ['nodes']);
                    }
                }
            }
        }
        return $deptsMap;
    }

    /**
     * �ݹ�����ָ�������µ��Ӳ���
     * @param $dept
     * @param $allDepts
     * @param int $i
     * @return array
     */
    function searchChildren($dept, $allDepts, $i = 0) {
        $children = array();
        $i = $i + 1;
        foreach ($allDepts as $key => $value) {
            $parentDX = substr($value ['Depart_x'], 0, 2 * $i);
            if ($dept ['Depart_x'] == $parentDX && $value ['Dflag'] == $i) {
                unset ($allDepts [$value ['Depart_x']]);
                $c = $this->searchChildren($value, $allDepts, $i);
                if (is_array($c) && count($c) > 0) {
                    $value ['nodes'] = array_merge($c, $dept ['nodes']);
                }
            }
            array_push($children, $value);
        }
        return $children;
    }

    /**
     * ���ݲ���id����ȡ������Ϣ
     * @param $ids
     * @return mixed
     */
    function getDeptByIds_d($ids) {
        $this->searchArr = array("deptIds" => $ids);
        $data = $this->list_d();
        if ($data) {
            $newData = array();
            foreach ($data as $v) {
                $newData[] = array(
                    'deptId' => $v['id'],
                    'deptName' => $v['DEPT_NAME']
                );
            }
            return $newData;
        } else {
            return null;
        }
    }

    /**
     * ���ݲ���id��ȡ������Ϣ
     * @param $deptId
     * @return mixed
     */
    function getDeptById($deptId) {
        $this->searchArr = array("cid" => $deptId);
        $list = $this->list_d();
        return $list[0];
    }

    /**
     * ���ݲ���ID����ȡ��������
     * @param $deptId
     * @return bool|mixed
     */
    function getDeptName_d($deptId) {
        return $this->find(array("DEPT_ID" => $deptId), 'DEPT_NAME');
    }

    /**
     * ���ݲ���ID����ȡ���ŵȼ�
     * @param $deptId
     * @return bool|mixed
     */
    function getDeptLevel_d($deptId) {
        return $this->find(array("DEPT_ID" => $deptId), 'levelflag');
    }


    /**
     * ���ݲ������ƣ���ȡ����ID
     * @param $deptName
     * @return bool|mixed
     */
    function getDeptId_d($deptName) {
        return $this->find(array("DEPT_NAME" => $deptName, 'DelFlag' => 0), 'DEPT_ID');
    }

    /**
     * �����û�id��ȡ������Ϣ
     * @param $userId
     * @return mixed
     * @throws Exception
     */
    function getDeptByUserId($userId) {
        $userDao = new model_deptuser_user_user();
        $user = $userDao->getUserById($userId);

        if (empty($user['DEPT_ID'])) {
            throw new Exception($userId . "���û�û����������.");
        }
        return $this->getDeptById($user['DEPT_ID']);
    }

    /**
     * �����û�ID��ȡ������Ϣ��������ְ�ģ�
     * @param $userId
     * @return mixed
     */
    function getDeptByUserIdHas($userId) {
        $userDao = new model_deptuser_user_user();
        $user = $userDao->find(array("USER_ID" => $userId));
        return $this->getDeptById($user['DEPT_ID']);
    }

    /**
     * ���ݲ���ID����ȡ���ŵ������ϼ�������Ϣ
     * @param $deptId
     * @param null $levelflag
     * @return array
     */
    function getSuperiorDeptById_d($deptId, $levelflag = null) {
        if ($levelflag == "" || $levelflag == null) {
            $dept = $this->getDeptLevel_d($deptId);
            $levelflag = $dept['levelflag'];
        }
        $row = array();
        //ֱ������
        $row['deptCode'] = "";
        $row['deptName'] = "";
        $row['deptId'] = "";
        //��������
        $row['deptCodeS'] = "";
        $row['deptNameS'] = "";
        $row['deptIdS'] = "";
        //��������
        $row['deptNameT'] = "";
        $row['deptCodeT'] = "";
        $row['deptIdT'] = "";
        //�ļ�����
        $row['deptNameF'] = "";
        $row['deptCodeF'] = "";
        $row['deptIdF'] = "";
        if ($levelflag == 1) {//ֱ������
            $deptRow = $this->getDeptById($deptId);
            $row['deptCode'] = $deptRow['Depart_x'];
            $row['deptName'] = $deptRow['name'];
            $row['deptId'] = $deptId;
        } else if ($levelflag == 2) {//��������
            $deptRow = $this->getDeptById($deptId);
            $row['deptCodeS'] = $deptRow['Depart_x'];
            $row['deptNameS'] = $deptRow['name'];
            $row['deptIdS'] = $deptId;
            if ($deptRow['PARENT_ID'] > 0) {//��ȡֱ������
                $parentdeptRow = $this->getDeptById($deptRow['PARENT_ID']);
                $row['deptCode'] = $parentdeptRow['Depart_x'];
                $row['deptName'] = $parentdeptRow['name'];
                $row['deptId'] = $deptRow['PARENT_ID'];
            } else {
                $row['deptCode'] = $deptRow['Depart_x'];
                $row['deptName'] = $deptRow['name'];
                $row['deptId'] = $deptId;
            }

        } else if ($levelflag == 3) {//��������
            $deptRow = $this->getDeptById($deptId);
            $row['deptCodeT'] = $deptRow['Depart_x'];
            $row['deptNameT'] = $deptRow['name'];
            $row['deptIdT'] = $deptId;
            if ($deptRow['PARENT_ID'] > 0) {//��������
                $parentdeptRow = $this->getDeptById($deptRow['PARENT_ID']);
                $row['deptCodeS'] = $parentdeptRow['Depart_x'];
                $row['deptNameS'] = $parentdeptRow['name'];
                $row['deptIdS'] = $deptRow['PARENT_ID'];
                if ($parentdeptRow['PARENT_ID'] > 0) {//��ȡֱ������
                    $highDeptRow = $this->getDeptById($parentdeptRow['PARENT_ID']);
                    $row['deptCode'] = $highDeptRow['Depart_x'];
                    $row['deptName'] = $highDeptRow['name'];
                    $row['deptId'] = $parentdeptRow['PARENT_ID'];
                } else {
                    $row['deptCode'] = $parentdeptRow['Depart_x'];
                    $row['deptName'] = $parentdeptRow['name'];
                    $row['deptId'] = $deptRow['PARENT_ID'];
                }
            }
        } else if ($levelflag == 4) {//�ļ�������
            $deptFRow = $this->getDeptById($deptId);
            $row['deptCodeF'] = $deptFRow['Depart_x'];
            $row['deptNameF'] = $deptFRow['name'];
            $row['deptIdF'] = $deptId;
            if ($deptFRow['PARENT_ID'] > 0) {//��������
                $deptRow = $this->getDeptById($deptFRow['PARENT_ID']);
                $row['deptCodeT'] = $deptRow['Depart_x'];
                $row['deptNameT'] = $deptRow['name'];
                $row['deptIdT'] = $deptFRow['PARENT_ID'];
                if ($deptRow['PARENT_ID'] > 0) {//��������
                    $parentdeptRow = $this->getDeptById($deptRow['PARENT_ID']);
                    $row['deptCodeS'] = $parentdeptRow['Depart_x'];
                    $row['deptNameS'] = $parentdeptRow['name'];
                    $row['deptIdS'] = $deptRow['PARENT_ID'];
                    if ($parentdeptRow['PARENT_ID'] > 0) {//��ȡֱ������
                        $highDeptRow = $this->getDeptById($parentdeptRow['PARENT_ID']);
                        $row['deptCode'] = $highDeptRow['Depart_x'];
                        $row['deptName'] = $highDeptRow['name'];
                        $row['deptId'] = $parentdeptRow['PARENT_ID'];
                    } else {
                        $row['deptCode'] = $parentdeptRow['Depart_x'];
                        $row['deptName'] = $parentdeptRow['name'];
                        $row['deptId'] = $deptRow['PARENT_ID'];
                    }
                }

            }

        }
        return $row;
    }

    /**
     * ��ȡ������Ϣ
     * @return array
     */
    function getDeptList_d() {
        $sql = "SELECT
	    		d1.DEPT_NAME,d1.DEPT_ID
	    	FROM
	    		department d1 WHERE d1.DelFlag = 0";
        $data = $this->_db->getArray($sql);
        $newData = array();
        foreach ($data as $v) {
            $newData[$v['DEPT_NAME']] = $v['DEPT_ID'];
        }
        return $newData;
    }

    /**
     * �����û�id��ȡ���ܲ���id
     * @param $userId
     * @return string
     */
    function getDeptIdsByUserId($userId) {
        $this->searchArr = array('DeptLead' => $userId);
        $rows = $this->list_d();
        if (is_array($rows)) {
            $deptIdArr = array();
            foreach ($rows as $k => $v) {
                array_push($deptIdArr, $v['id']);
            }
            return implode(",", $deptIdArr);
        } else {
            return "";
        }

    }
}