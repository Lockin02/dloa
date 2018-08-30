<?php

/**
 * ��֯����model����
 */
class model_deptuser_user_userselect extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_user_select";
        $this->sql_map = "deptuser/user/userselectSql.php";
        parent:: __construct();
    }

    /**
     * ���泣��ѡ����
     * @param $formCode
     * @param $selectedUserIds
     * @param $selectedUserNames
     */
    function saveSelectedUser($formCode, $selectedUserIds, $selectedUserNames) {
        $userIdArr = explode(",", $selectedUserIds);
        $userNameArr = explode(",", $selectedUserNames);
        foreach ($userIdArr as $k => $v) {
            // ����ǿ�ֵ����ֱ������
            if(empty($v)) continue;

            $userId = $userIdArr[$k];
            $userName = $userNameArr[$k];

            // �������ܷ��ҵ����ҵ��͸���ʱ�䣬�Ҳ���������һ��
            $selectUser = $this->find(array(
                "formCode" => $formCode,
                "selectUserId" => $userId,
                "userId" => $_SESSION['USER_ID']
            ));
            if ($selectUser) {
                //����ʱ��
                $selectUser['selectTime'] = date("Y-m-d H:i:s");
                $this->edit_d($selectUser);
            } else {
                $selectUser = array(
                    "formCode" => $formCode,
                    "userId" => $_SESSION['USER_ID'],
                    "userName" => $_SESSION['USERNAME'],
                    "selectTime" => date("Y-m-d H:i:s"),
                    "selectUserId" => $userId,
                    "selectUserName" => $userName
                );
                $this->add_d($selectUser);
            }
        }
    }
}