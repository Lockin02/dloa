<?php
header("Content-type: text/html; charset=gb2312");

/**
 * 组织机构model层类
 */
class model_deptuser_dept_dept extends model_base
{

    function __construct()
    {
        $this->tbl_name = "department";
        $this->sql_map = "deptuser/dept/deptSql.php";
        parent::__construct();
    }

    /**
     * 异步加载组织机构
     * @param $deptName
     * @return mixed
     */
    function tree_d($deptName)
    {
        $this->searchArr = array('DelFlag' => 0);
        if (!empty ($deptName)) {
            $this->searchArr ['deptName'] = $deptName;
        }
        return $this->list_d();
    }

    /**
     * 把组织机构组装成树形数组
     * @param $depts
     * @return array
     */
    function changeDeptsToTree($depts)
    {
        $this->sort = 'Dflag';
        $this->asc = false;
        $allDepts_tmp = $this->list_d();
        foreach ($allDepts_tmp as $key => $value) {
            $allDepts [$value ['Depart_x']] = $value;
        }
        $deptsMap = array();
        foreach ($depts as $k => $v) {
            //如果是一级节点
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
     * 递归搜索指定部门下的子部门
     * @param $dept
     * @param $allDepts
     * @param int $i
     * @return array
     */
    function searchChildren($dept, $allDepts, $i = 0)
    {
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
     * 根据部门id串获取部门信息
     * @param $ids
     * @return mixed
     */
    function getDeptByIds_d($ids)
    {
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
     * 根据部门id获取部门信息
     * @param $deptId
     * @return mixed
     */
    function getDeptById($deptId)
    {
        $this->searchArr = array("cid" => $deptId);
        $list = $this->list_d();
        return $list[0];
    }

    /**
     * 根据部门ID，获取部门名称
     * @param $deptId
     * @return bool|mixed
     */
    function getDeptName_d($deptId)
    {
        return $this->find(array("DEPT_ID" => $deptId), 'DEPT_NAME');
    }
    
    /**
     * 根据用户ID，获取用户名称
     */
    function getName_d($deptId)
    {
        $deptInfo = $this->find(array("DEPT_ID" => $deptId), 'DEPT_NAME');
        return $deptInfo['DEPT_NAME'];
    }

    /**
     * 根据部门ID，获取部门等级
     * @param $deptId
     * @return bool|mixed
     */
    function getDeptLevel_d($deptId)
    {
        return $this->find(array("DEPT_ID" => $deptId), 'levelflag');
    }


    /**
     * 根据部门名称，获取部门ID
     * @param $deptName
     * @return bool|mixed
     */
    function getDeptId_d($deptName)
    {
        return $this->find(array("DEPT_NAME" => $deptName, 'DelFlag' => 0), 'DEPT_ID');
    }

    /**
     * 根据用户id获取部门信息
     * @param $userId
     * @return mixed
     * @throws Exception
     */
    function getDeptByUserId($userId)
    {
        $userDao = new model_deptuser_user_user();
        $user = $userDao->getUserById($userId);

        if (empty($user['DEPT_ID'])) {
            throw new Exception($userId . "该用户没有所属部门.");
        }
        return $this->getDeptById($user['DEPT_ID']);
    }

    /**
     * 根据用户ID获取部门信息（包括离职的）
     * @param $userId
     * @return mixed
     */
    function getDeptByUserIdHas($userId)
    {
        $userDao = new model_deptuser_user_user();
        $user = $userDao->find(array("USER_ID" => $userId));
        return $this->getDeptById($user['DEPT_ID']);
    }

    /**
     * 根据部门ID，获取部门的所有上级部门信息
     * @param $deptId
     * @param null $levelflag
     * @return array
     */
    function getSuperiorDeptById_d($deptId, $levelflag = null)
    {
        if ($levelflag == "" || $levelflag == null) {
            $dept = $this->getDeptLevel_d($deptId);
            $levelflag = $dept['levelflag'];
        }
        $row = array();
        //直属部门
        $row['deptCode'] = "";
        $row['deptName'] = "";
        $row['deptId'] = "";
        //二级部门
        $row['deptCodeS'] = "";
        $row['deptNameS'] = "";
        $row['deptIdS'] = "";
        //三级部门
        $row['deptNameT'] = "";
        $row['deptCodeT'] = "";
        $row['deptIdT'] = "";
        //四级部门
        $row['deptNameF'] = "";
        $row['deptCodeF'] = "";
        $row['deptIdF'] = "";
        if ($levelflag == 1) { //直属部门
            $deptRow = $this->getDeptById($deptId);
            $row['deptCode'] = $deptRow['Depart_x'];
            $row['deptName'] = $deptRow['name'];
            $row['deptId'] = $deptId;
        } else if ($levelflag == 2) { //二级部门
            $deptRow = $this->getDeptById($deptId);
            $row['deptCodeS'] = $deptRow['Depart_x'];
            $row['deptNameS'] = $deptRow['name'];
            $row['deptIdS'] = $deptId;
            if ($deptRow['pId'] > 0) { //获取直属部门
                $parentdeptRow = $this->getDeptById($deptRow['pId']);
                $row['deptCode'] = $parentdeptRow['Depart_x'];
                $row['deptName'] = $parentdeptRow['name'];
                $row['deptId'] = $deptRow['pId'];
            } else {
                $row['deptCode'] = $deptRow['Depart_x'];
                $row['deptName'] = $deptRow['name'];
                $row['deptId'] = $deptId;
            }

        } else if ($levelflag == 3) { //三级部门
            $deptRow = $this->getDeptById($deptId);
            $row['deptCodeT'] = $deptRow['Depart_x'];
            $row['deptNameT'] = $deptRow['name'];
            $row['deptIdT'] = $deptId;
            if ($deptRow['pId'] > 0) { //二级部门
                $parentdeptRow = $this->getDeptById($deptRow['pId']);
                $row['deptCodeS'] = $parentdeptRow['Depart_x'];
                $row['deptNameS'] = $parentdeptRow['name'];
                $row['deptIdS'] = $deptRow['pId'];
                if ($parentdeptRow['pId'] > 0) { //获取直属部门
                    $highDeptRow = $this->getDeptById($parentdeptRow['pId']);
                    $row['deptCode'] = $highDeptRow['Depart_x'];
                    $row['deptName'] = $highDeptRow['name'];
                    $row['deptId'] = $parentdeptRow['pId'];
                } else {
                    $row['deptCode'] = $parentdeptRow['Depart_x'];
                    $row['deptName'] = $parentdeptRow['name'];
                    $row['deptId'] = $deptRow['pId'];
                }
            }
        } else if ($levelflag == 4) { //四级级部门
            $deptFRow = $this->getDeptById($deptId);
            $row['deptCodeF'] = $deptFRow['Depart_x'];
            $row['deptNameF'] = $deptFRow['name'];
            $row['deptIdF'] = $deptId;
            if ($deptFRow['pId'] > 0) { //三级部门
                $deptRow = $this->getDeptById($deptFRow['pId']);
                $row['deptCodeT'] = $deptRow['Depart_x'];
                $row['deptNameT'] = $deptRow['name'];
                $row['deptIdT'] = $deptFRow['pId'];
                if ($deptRow['pId'] > 0) { //二级部门
                    $parentdeptRow = $this->getDeptById($deptRow['pId']);
                    $row['deptCodeS'] = $parentdeptRow['Depart_x'];
                    $row['deptNameS'] = $parentdeptRow['name'];
                    $row['deptIdS'] = $deptRow['pId'];
                    if ($parentdeptRow['pId'] > 0) { //获取直属部门
                        $highDeptRow = $this->getDeptById($parentdeptRow['pId']);
                        $row['deptCode'] = $highDeptRow['Depart_x'];
                        $row['deptName'] = $highDeptRow['name'];
                        $row['deptId'] = $parentdeptRow['pId'];
                    } else {
                        $row['deptCode'] = $parentdeptRow['Depart_x'];
                        $row['deptName'] = $parentdeptRow['name'];
                        $row['deptId'] = $deptRow['pId'];
                    }
                }

            }

        }
        return $row;
    }

    /**
     * 获取部门信息
     * @return array
     */
    function getDeptList_d()
    {
        $sql = "SELECT
	    		d1.DEPT_NAME,d1.DEPT_ID,d1.module
	    	FROM
	    		department d1 WHERE d1.DelFlag = 0";
        $data = $this->_db->getArray($sql);
        $newData = array();
        foreach ($data as $v) {
            $newData[$v['DEPT_NAME']] = $v;
            $newData[$v['DEPT_ID']] = $v;
        }
        return $newData;
    }

    /**
     * 根据用户id获取所管部门id
     * @param $userId
     * @return string
     */
    function getDeptIdsByUserId($userId)
    {
        $this->searchArr = array('DeptLead' => $userId);
        $rows = $this->list_d();
        if (is_array($rows)) {
            $deptIdArr = array();
            foreach ($rows as $v) {
                array_push($deptIdArr, $v['id']);
            }
            return implode(",", $deptIdArr);
        } else {
            return "";
        }
    }

    /**
     * 获取首层公司
     * @return array|bool
     */
    function getCompanyList_d() {
        //加入权限
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('deptuser_dept_dept', $_SESSION['USER_ID'],
            $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        $comLimit = isset($sysLimit['公司权限']) ? $sysLimit['公司权限'] : '';

        // 如果没有权限处理，则用自身公司过滤
        if ($comLimit) {
            // 整合公司权限
            $comLimitArr = explode(',', $comLimit);
            $comLimitArr[] = $_SESSION['USER_COM'];
            $rows = array();

            // 没有公司编码，且不过滤部门的显示为公司
            $sql = "select 'dept' as icon,'dept' as type ,c.nameCN as DEPT_NAME ,c.nameCN as name ,
                                c.comCode ,'1' as hasChildren,c.cNameCode from company c order by c.sortOrder asc ";
            $tempResult = $this->findSql($sql);

            foreach ($tempResult as $v) {
                $cNameCodeArr = explode(',', $v['cNameCode']);
                foreach ($cNameCodeArr as $vi) {
                    if (in_array($vi, $comLimitArr)) {
                        $rows[] = $v;
                        break;
                    }
                }
            }
        }  else {
            // 没有公司编码，且不过滤部门的显示为公司
            $sql = "select 'dept' as icon,'dept' as type ,c.nameCN as DEPT_NAME ,c.nameCN as name ,
                        c.comCode ,'1' as hasChildren from company c
                        WHERE find_in_set('" . $_SESSION['USER_COM'] . "', c.cNameCode) > 0 order by c.sortOrder asc ";
            $rows = $this->findSql($sql);
        }
        return $rows;
    }
}