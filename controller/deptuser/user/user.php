<?php

/**
 * �˻����Ʋ�
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
     * ��ת����֯����ѡ��ҳ��
     */
    function c_selectuser()
    {
        $this->assign("mode", $_GET['mode']); //��Աѡ��ģʽ
        $this->assign("formCode", isset($_GET['formCode']) ? $_GET['formCode'] : ''); //ѡ�е���Աֵ
        $this->assign("userVal", isset($_GET['userVal']) ? $_GET['userVal'] : ''); //ѡ�е���Աֵ
        $this->assign("deptId", isset($_GET['deptId']) ? $_GET['deptId'] : ''); //ѡ�еĲ���ID
        $this->assign("deptName", isset($_GET['deptName']) ? $_GET['deptName'] : ''); //ѡ�еĲ�������
        $this->assign("isOnlyCurDept", isset($_GET['isOnlyCurDept']) ? $_GET['isOnlyCurDept'] : ''); // �Ƿ�ֻ��ѡ��ǰ��¼�����ڲ���
        $this->assign("deptIds", isset($_GET['deptIds']) ? $_GET['deptIds'] : ''); // ֻ��ѡ�����õĲ���id��
        $this->assign("userNo", isset($_GET['userNo']) ? $_GET['userNo'] : '');
        $this->assign("isNeedJob", isset($_GET['isNeedJob']) ? $_GET['isNeedJob'] : '');
        $this->assign("isShowLeft", isset($_GET['isShowLeft']) ? $_GET['isShowLeft'] : '');
        $this->assign("targetId", isset($_GET['targetId']) ? $_GET['targetId'] : '');
        $this->assign("isDeptAddedUser", isset($_GET['isDeptAddedUser']) ? $_GET['isDeptAddedUser'] : ''); // �Ƿ�������׷����Ա
        $this->assign("isDeptSetUserRange", isset($_GET['isDeptSetUserRange']) ? $_GET['isDeptSetUserRange'] : ''); // �����Ƿ���������Աѡ��Χ
        $this->assign("height", isset($_GET['mini']) && $_GET['mini'] == 1 ? 120 : 365); // ���ڴ�С
        $this->view("select");
    }

    /**
     * ��֯������Ա��
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
                $service->searchArr["Dflag"] = 0; //�㼶�����û��Depart_x����ȡ�������
            } else {
                $service->searchArr["Dflag"] = $_POST['Dflag'] + 1;
            }
        }
        if (!empty ($_POST['id'])) {
            $service->searchArr["DEPT_ID"] = $_POST['id'];
        } else {
            //����ǵ�ǰ����
            if ($_GET['isOnlyCurDept'] == 1) {
                $service->searchArr["cid"] = $_SESSION['DEPT_ID'];
            } else if (!empty ($_GET['deptIds'])) { //�����ѡ�����м�������
                $service->searchArr["deptIds"] = $_GET['deptIds'];
            }
        }
        $rows = $service->deptusertree_d($_POST['userName'], $_POST['deptName'], $_GET['isShowLeft']);
        //������������
        if (!empty ($_POST['id']) && ($_GET['isDeptAddedUser'] == 1 || $_GET['isDeptSetUserRange'] == 1)) {
            //��ȡ�����ļ�
            include(WEB_TOR . "includes/config.php");
            //���������Ա����
            if ($_GET['isDeptAddedUser'] == 1) {
                $sourceArr = isset($deptAddedUser) ? $deptAddedUser : null;
                if (!empty($sourceArr) && is_array($sourceArr)) {
                    $rs = $sourceArr[$_POST['id']];
                    if (!empty($rs)) {
                        $userIds = implode(',', array_flip($rs));
                        $service->searchArr = array(); // ���������������
                        $service->searchArr["USER_IDS"] = $userIds;
                        $addUsers = $service->list_d();
                        if (!empty($addUsers)) {
                            $rows = array_merge($rows, $addUsers);
                        }
                    }
                }
            }
            //����������Աѡ��Χ����
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
            if ($row['type'] == 'user') { // �û�����������˺ż����ż�������  update on 2015-11-30 by weijb
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
     * ��ɫ��Ա��
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
            if ($row['type'] == 'user') { // �û�����������˺ż����ż�������  update on 2015-11-30 by weijb
                $row['name'] .= "<" . $row['USER_ID'] . "-" . $row['DEPT_NAME'] . ">";
            }
            return $row;
        }

        if (!is_array($rows)) {
            $rows = array();
        }
        echo util_jsonUtil::encode(array_map("toBoolean", $rows));
    }

    /**�����û�ID����ȡ�û�����
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
     * �����û�����ѯ����û�
     */
    function c_ajaxGetUser()
    {
        $this->service->searchArr['userName'] = $_POST['userName'];
        $rs = $this->service->list_d();
        echo util_jsonUtil::encode($rs);
    }

    /**
     * �����˺Ų�ѯ����û�
     */
    function c_ajaxGetUserInfo()
    {
        $this->service->searchArr['USER_ID'] = $_POST['userId'];
        $rs = $this->service->list_d();
        echo util_jsonUtil::encode($rs[0]);
    }
}