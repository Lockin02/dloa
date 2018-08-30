<?php

/**
 * 账户控制层
 * @author chris
 */
class controller_deptuser_user_user extends controller_base_action
{

    function __construct()
    {
        $this->objName = "user";
        $this->objPath = "deptuser_user";
        parent::__construct();
    }

    /**
     * 跳转到组织机构选择页面
     */
    function c_selectuser()
    {
        $this->assign("mode", $_GET['mode']); //人员选择模式
        $this->assign("formCode", isset($_GET['formCode']) ? $_GET['formCode'] : ''); //选中的人员值
        $this->assign("userVal", isset($_GET['userVal']) ? $_GET['userVal'] : ''); //选中的人员值
        $this->assign("deptId", isset($_GET['deptId']) ? $_GET['deptId'] : ''); //选中的部门ID
        $this->assign("deptName", isset($_GET['deptName']) ? $_GET['deptName'] : ''); //选中的部门名称
        $this->assign("isOnlyCurDept", isset($_GET['isOnlyCurDept']) ? $_GET['isOnlyCurDept'] : ''); // 是否只能选择当前登录人所在部门
        $this->assign("deptIds", isset($_GET['deptIds']) ? $_GET['deptIds'] : ''); // 只能选择设置的部门id串
        $this->assign("userNo", isset($_GET['userNo']) ? $_GET['userNo'] : '');
        $this->assign("isNeedJob", isset($_GET['isNeedJob']) ? $_GET['isNeedJob'] : '');
        $this->assign("isShowLeft", isset($_GET['isShowLeft']) ? $_GET['isShowLeft'] : '');
        $this->assign("targetId", isset($_GET['targetId']) ? $_GET['targetId'] : '');
        $this->assign("isDeptAddedUser", isset($_GET['isDeptAddedUser']) ? $_GET['isDeptAddedUser'] : ''); // 是否往部门追加人员
        $this->assign("isDeptSetUserRange", isset($_GET['isDeptSetUserRange']) ? $_GET['isDeptSetUserRange'] : ''); // 部门是否设置了人员选择范围
        $this->assign("height", isset($_GET['mini']) && $_GET['mini'] == 1 ? 120 : 365); // 窗口大小
        $this->view("select");
    }

    /**
     * 组织机构人员树
     */
    function c_deptusertree()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->searchArr['DelFlag'] = 0;
        if (!empty($_REQUEST['deptIds'])) {
            if (!empty($_POST['id'])) {
                $service->searchArr["Dflag"] = $_POST['Dflag'] + 1;
            }
        } else {
            if ((empty ($_POST['Depart_x']) || $_POST['Depart_x'] === 'undefined')) {
                $service->searchArr["Dflag"] = 0; //层级，如果没有Depart_x，则取最顶层数据
            } else {
                $service->searchArr["Dflag"] = $_POST['Dflag'] + 1;
            }
        }
        if (!empty ($_POST['id'])) {
            $service->searchArr["DEPT_ID"] = $_POST['id'];
        } else {
            //如果是当前部门
            if ($_GET['isOnlyCurDept'] == 1) {
                $service->searchArr["cid"] = $_SESSION['DEPT_ID'];
            } else if (!empty ($_GET['deptIds'])) { //如果是选择其中几个机构
                $service->searchArr["deptIds"] = $_GET['deptIds'];
            }
        }
        $rows = $service->deptusertree_d($_POST['userName'], $_POST['deptName'], $_GET['isShowLeft']);
        //特殊条件处理
        if (!empty ($_POST['id']) && ($_GET['isDeptAddedUser'] == 1 || $_GET['isDeptSetUserRange'] == 1)) {
            //读取配置文件
            include(WEB_TOR . "includes/config.php");
            //部门添加人员处理
            if ($_GET['isDeptAddedUser'] == 1) {
                $sourceArr = isset($deptAddedUser) ? $deptAddedUser : null;
                if (!empty($sourceArr) && is_array($sourceArr)) {
                    $rs = $sourceArr[$_POST['id']];
                    if (!empty($rs)) {
                        $userIds = implode(',', array_flip($rs));
                        $service->searchArr = array(); // 清空其它搜索条件
                        $service->searchArr["USER_IDS"] = $userIds;
                        $addUsers = $service->list_d();
                        if (!empty($addUsers)) {
                            $rows = array_merge($rows, $addUsers);
                        }
                    }
                }
            }
            //部门设置人员选择范围处理
            if ($_GET['isDeptSetUserRange'] == 1) {
                $sourceArr = isset($deptSetUserRange) ? $deptSetUserRange : null;
                if (!empty($sourceArr) && is_array($sourceArr)) {
                    $rs = $sourceArr[$_POST['id']];
                    if (!empty($rs)) {
                        foreach ($rows as $k => $v) {
                            if (isset($v['USER_ID']) && !array_key_exists($v['USER_ID'], $rs)) {
                                unset($rows[$k]);
                            }
                        }
                        $rows = array_values($rows);
                    }
                }
            }
        }
        function toBoolean($row)
        {
            if ($_GET['noIcon'] == 1) {
                $row['icon'] = "";
            }
            if ($row['hasChildren'] > 0 || $row['hasUser'] > 0) {
                $row['isParent'] = "true";
            } else {
                $row['isParent'] = false;
            }
            if ($row['type'] == 'user') { // 用户名后面加上账号及部门加以区分  update on 2015-11-30 by weijb
                $row['name'] .= "<" . $row['USER_ID'] . "-" . $row['DEPT_NAME'] . ">";
            }
            return $row;
        }

        if (!is_array($rows)) {
            $rows = array();
        }

        echo util_jsonUtil::encode(array_map("toBoolean", $rows));
    }

    /**
     * 角色人员树
     */
    function c_jobsusertree()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        if (!empty ($_POST['id'])) {
            $service->searchArr["jobs_id"] = $_POST['id'];
        }
        $rows = $service->jobsusertree_d($_POST['userName'], $_POST['jobsName'], $_GET['isShowLeft']);
        function toBoolean($row)
        {
            $row['isParent'] = $row['hasUser'] == 1 ? true : false;
            if ($row['type'] == 'user') { // 用户名后面加上账号及部门加以区分  update on 2015-11-30 by weijb
                $row['name'] .= "<" . $row['USER_ID'] . "-" . $row['DEPT_NAME'] . ">";
            }
            return $row;
        }

        if (!is_array($rows)) {
            $rows = array();
        }
        echo util_jsonUtil::encode(array_map("toBoolean", $rows));
    }

    /**根据用户ID，获取用户名称
     *author can
     *2011-8-18
     */
    function c_getUserName()
    {
        $userId = isset($_POST['userId']) ? $_POST['userId'] : "";
        $userName = $this->service->getUserName_d($userId);
        echo $userName['USER_NAME'];
    }

    /**
     * 根据用户名查询相关用户
     */
    function c_ajaxGetUser()
    {
        $this->service->searchArr['userName'] = $_POST['userName'];
        $rs = $this->service->list_d();
        echo util_jsonUtil::encode($rs);
    }

    /**
     * 根据账号查询相关用户
     */
    function c_ajaxGetUserInfo()
    {
        $this->service->searchArr['USER_ID'] = $_POST['userId'];
        $rs = $this->service->list_d();
        echo util_jsonUtil::encode($rs[0]);
    }
}