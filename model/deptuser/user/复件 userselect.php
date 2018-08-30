<?php


/**
 * 组织机构model层类
 */
class model_deptuser_user_userselect extends model_base {

	function __construct() {
		$this->tbl_name = "oa_user_select";
		$this->sql_map = "deptuser/user/userselectSql.php";
		parent :: __construct();
	}

	/**
	 * 保存常用选择人
	 */
	function saveSelectedUser($formCode, $selectedUserIds, $selectedUserNames) {
		$userIdArr = explode(",", $selectedUserIds);
		$userNameArr = explode(",", $selectedUserNames);
		for ($i = 0; $i < count($userIdArr); $i++) {
			$userId = $userIdArr[$i];
			$userName = $userNameArr[$i];
			//先找下能否找到，找到就更新时间，找不到就新增一条
			$this->searchArr = array (
				"formCode" => $formCode,
				"selectUserId" => $userId,
				"userId" => $_SESSION['USER_ID']
			);
			$l = $this->list_d();
			if (is_array($l)&&isset($l[0])) {
				//更新时间
				$selectUser = $l[0];
				$selectUser['selectTime'] =date("Y-m-d H:i:s");
				$this->edit_d($selectUser);
			} else {
				$selectUser = array (
					"formCode" => $formCode,
					"userId" => $_SESSION['USER_ID'],
					"userName" => $_SESSION['USERNAME'],
					"selectTime" => date("Y-m-d H:i:s"),
					"selectUserId" => $userId, "selectUserName" => $userName);
				$this->add_d($selectUser);
			}
		}
	}

}
?>