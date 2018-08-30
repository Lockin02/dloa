<?php
header("Content-type: text/html; charset=gb2312");

/**
 * 组织机构model层类
 */
class model_deptuser_user_user extends model_base
{

    function __construct()
    {
        $this->tbl_name = "user";
        $this->sql_map = "deptuser/user/userSql.php";
        parent::__construct();
    }

    /**
     *组织机构人员
     */
    function deptusertree_d($userName, $deptName, $isShowLeft)
    {
        $this->sort = "c.USER_NAME"; //根据名称排序
        $this->asc = false;
        //如果是根据用户名称搜索
        if (!empty ($userName)) {
            $this->searchArr = array('userAndLogName' => $userName);
            if ($_GET['isOnlyCurDept'] == 1) {
                $this->searchArr["DEPT_ID"] = $_SESSION['DEPT_ID'];
            } else if (!empty ($_GET['deptIds'])) { //如果是选择其中几个机构
                $this->searchArr["deptIds"] = $_GET['deptIds'];
            }

            if ($isShowLeft) {
                $users = $this->list_d("selectAll");
            } else {
                $users = $this->list_d();
            }
            $result = $users;
        } else {
            $deptDao = new model_deptuser_dept_dept ();
            $deptDao->searchArr = $this->searchArr;
            $deptDao->searchArr['DelFlag'] = 0;
            $this->searchArr['DelFlag'] = 0;
            //如果是部门搜索
            if (!empty ($deptName)) {
                $deptDao->searchArr = array('deptName' => $deptName, 'DelFlag' => 0);
                if (!empty ($_GET['deptIds'])) { //如果是选择其中几个机构
                    $deptDao->searchArr["deptIds"] = $_GET['deptIds'];
                }
                $result = $deptDao->list_d('selectForUser');
            } else {
                if (!empty ($this->searchArr['DEPT_ID']) && $this->searchArr['DEPT_ID'] != 'undefined') {
                    if ($isShowLeft) {
                        $users = $this->list_d("selectAll");
                    } else {
                        $users = $this->list_d();
                    }
                    $depts = $deptDao->list_d('selectForUser');
                    if (is_array($depts) && is_array($users)) {
                        $result = array_merge($depts, $users);
                    } else if (is_array($depts)) {
                        $result = $depts;
                    } else {
                        $result = $users;
                    }
                } else {
                    if (!isset($_POST['comCode']) && empty($_POST['comCode']) && !isset($this->searchArr['deptIds']) && empty($this->searchArr['deptIds'])) {
                        $result = $deptDao->getCompanyList_d();
                    } else {
                        if (isset($this->searchArr['deptIds']) && !empty($this->searchArr['deptIds'])) {
                            $sql = "select group_concat(distinct CAST(comCode as CHAR)) as comCode from department where DEPT_ID in(" . $this->searchArr['deptIds'] . ")";
                            $rs = $this->_db->getArray($sql);
                            $comCodes = $rs[0]['comCode'];
                            if (!empty($comCodes)) {
                                $deptDao->searchArr['comCodes2'] = $comCodes;
                            }
                        }
                        if (is_array($deptDao->searchArr)) {
                            foreach ($deptDao->searchArr as $key => $val) {
                                if ($val === 'undefined') {
                                    unset($deptDao->searchArr[$key]);
                                }
                            }
                        }
                        $result = $deptDao->list_d('selectForUser');
                    }
                }

            }
        }
        return $result;
    }

    /**
     *角色人员
     */
    function jobsusertree_d($userName, $jobsName, $isShowLeft)
    {
        if (!empty ($userName)) {
            $this->searchArr = array('userAndLogName' => $userName);
            if ($isShowLeft) {
                $users = $this->list_d("selectAll");
            } else {
                $users = $this->list_d();
            }
            $result = $users;
        } else {
            $jobsDao = new model_deptuser_jobs_jobs ();
            //如果是角色搜索
            if (!empty ($jobsName)) {
                $jobsDao->searchArr = array('name' => $jobsName);
            }
            if (!empty ($this->searchArr['jobs_id'])) {
                $result = $this->list_d();
            } else {
                $result = $jobsDao->list_d('selectForUser');
            }
        }
        return $result;
    }

    /**
     * 根据用户名称获取第一个用户信息
     */
    function getUserByName($userName)
    {
        $this->searchArr = array('userNameEq' => $userName);
        $users = $this->list_d();
        if (is_array($users) && count($users) > 0) {
            return $users[0];
        }
        return null;
    }

    /**
     * 根据用户ID，获取用户名称
     *author can
     *2011-8-18
     */
    function getUserName_d($userId)
    {
        $userId = array("USER_ID" => $userId);
        $userName = $this->find($userId, 'USER_NAME');
        return $userName;
    }
    
    /**
     * 根据用户ID，获取用户名称
     *author can
        *2011-8-18
        */
    function getName_d($userId)
    {
        $userId = array("USER_ID" => $userId);
        $userName = $this->find($userId, 'USER_NAME');
        return $userName['USER_NAME'];
    }

    /**
     * 根据用户ID，获取用户信息
     */
    function getUserById($userId)
    {
        $this->searchArr = array("USER_ID" => $userId);
        $list = $this->list_d("selectAll");
        return $list[0];
    }

    /**
     * 根据用户id串，获取外包公司人数
     */
    function getOutPeople_d($memberIdstr)
    {
        $this->searchArr = array('logNames' => $memberIdstr, 'userType' => 2);
        $this->sort = '';
        $countArr = $this->list_d('selectCount');
        if (empty($countArr[0]['count'])) {
            return 0;
        }
        return $countArr[0]['count'];
    }
}