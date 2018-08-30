
<?php


/**
 * 鼎利原有门户信息 portlet model层
 * 数据库查询语句limit 0,10 暂时写死
 */
class model_system_oaportal_oaportlet extends model_base {

	function __construct() {
		//$this->tbl_name = "oa_portal_portlet";
		//$this->sql_map = "system/portal/portletSql.php";
		parent :: __construct();
	}

	//公告通知
	function getNoticeList() {
		$query = "	select
						a.*
					from
						notice as a
						left join (select * from department group by dept_id) as b on b.dept_id in (a.dept_id_str)
					where
						a.nametype!='admin'
						and a.nametype!='系统管理'
						and a.audit=0
						and (a.effect=1 or a.start_date < " . time() . ")
						and a.effect!=2
						and (
								find_in_set('" . $_SESSION['USER_ID'] . "',a.user_id_str)
		                        or (
		                               (find_in_set('" . $_SESSION['DEPT_ID'] . "',a.dept_id_str) or a.dept_id_str is null or a.dept_id_str='')
		                                and
		                                (find_in_set('" . $_SESSION['AREA'] . "',a.area_id_str) or a.area_id_str is null or a.area_id_str='')
		                               	and
		                                (find_in_set('" . $_SESSION['USER_JOBSID'] . "',a.jobs_id_str) or a.jobs_id_str is null or a.jobs_id_str='')
		                                and
		                                (find_in_set('" . $_SESSION['USER_ID'] . "',a.user_id_str) or a.user_id_str is null or a.user_id_str='')
		                            )
		                          or ( find_in_set('" . $_SESSION['USER_ID'] . "',b.ViceManager) or find_in_set('" . $_SESSION['USER_ID'] . "',b.MajorId) )
		                       )
					order by a.edit_time desc , date desc
					limit 10
				 ";
		return $this->_db->getArray($query);
	}
	//投票箱
	function getVoteList() {
		$NOW_DATE = date("Y-m-d");
		$sqlStr = "select  USER_PRIV,DEPT_ID ,area from user where  USER_ID='" . $_SESSION['USER_ID'] . "'"; //.$USER_ID用session userid替换
		$fsql = $this->_db->getArray($sqlStr);
		foreach ($fsql as $key => $value) {

			$checkDept = $fsql[$key]['DEPT_ID'];
			$checkPriv = $fsql[$key]['USER_PRIV'];
			$checkArea = $fsql[$key]['area'];
		}
		if ($_SESSION['USER_ID'] == 'admin') {
			$query = "SELECT vote.*,USER_NAME FROM vote,user where  user.USER_ID=vote.USER_ID and BEGIN_DATE<='$NOW_DATE' and (END_DATE>='$NOW_DATE' or END_DATE='0000-00-00') and  Flag='0' order by ID desc LIMIT 0, 10 "; //
		} else {
			$query = "SELECT vote.*,USER_NAME FROM vote ,user  where  user.USER_ID=vote.USER_ID and BEGIN_DATE<='$NOW_DATE' and (END_DATE>='$NOW_DATE' or END_DATE='0000-00-00') and Flag='0' and  ( ( ( find_in_set('" . $checkDept . "',vote.DEPT_ID)>0 or vote.DEPT_ID='ALL_DEPT' or find_in_set('" . $checkPriv . "',vote.PRIV_ID)>0 or vote.PRIV_ID='ALL_PRIV' or find_in_set('$USER_ID',vote.TO_ID)>0 ) and (find_in_set('" . $checkArea . "',vote.AREA_ID)>0 or vote.AREA_ID='ALL_AREA' or vote.AREA_ID='' ) ) or ( vote.DEPT_ID='' and vote.PRIV_ID='' and vote.TO_ID='' and (find_in_set('" . $checkArea . "',vote.AREA_ID)>0 or vote.AREA_ID='ALL_AREA' or vote.AREA_ID='' ) ) )  order by ID desc LIMIT 0, 10";
		}

		return $this->_db->getArray($query);
	}
	//工作计划
	function getWorkplanList() {
		$NOW_DATE = date("Y-m-d");
		$sqlStr = "select  USER_PRIV,DEPT_ID ,area from user where  USER_ID='" . $_SESSION['USER_ID'] . "'"; //.$USER_ID用session userid替换
		$fsql = $this->_db->getArray($sqlStr);
		foreach ($fsql as $key => $value) {

			$checkDept = $fsql[$key]['DEPT_ID'];
			$checkPriv = $fsql[$key]['USER_PRIV'];

		}
		if ($_SESSION['USER_ID'] == 'admin') {
			$query = "select * from work_plan  where 1 and STATUS_TAG ='1' and END_DATE>='" . date('Y-m-d') . "' order by PLAN_ID desc LIMIT 0, 10";
		} else {
			$query = "select * from work_plan  where ( find_in_set('" . $checkDept . "',TO_ID)>0 or TO_ID='ALL_DEPT' ) and STATUS_TAG ='1' and END_DATE>='" . date('Y-m-d') . "' order by PLAN_ID desc LIMIT 0, 10";
		}
		return $this->_db->getArray($query);
	}
	//新闻
	function getNewsList() {
		$query = "select * from news  order by NEWS_ID desc LIMIT 0, 10";
		return $this->_db->getArray($query);
	}
	//个人费用
	function getCostpersonalList() {
		$NOW_DATE = date("Y-m-d");
		$sqlStr = "select  USER_PRIV,DEPT_ID  from user where  USER_ID='" . $_SESSION['USER_ID'] . "'";
		$fsql = $this->_db->getArray($sqlStr);
		foreach ($fsql as $key => $value) {
			$checkDept = $fsql[$key]['DEPT_ID'];
			$checkPriv = $fsql[$key]['USER_PRIV'];
		}

		$query = "select * from cost_summary_list where (InputMan ='" . $_SESSION['USER_ID'] . "' or CostMan='" . $_SESSION['USER_ID'] . "') order by UpdateDT desc limit 0, 5 ";

		return $this->_db->getArray($query);
	}
	//工作日志
	function getDiaryList() {
		$NOW_DATE = date("Y-m-d");
		$sqlStr = "select  USER_PRIV,DEPT_ID  from user where  USER_ID='" . $_SESSION['USER_ID'] . "'";
		$fsql = $this->_db->getArray($sqlStr);
		foreach ($fsql as $key => $value) {
			$checkDept = $fsql[$key]['DEPT_ID'];
			$checkPriv = $fsql[$key]['USER_PRIV'];
		}
		$query = "select * from diary  where USER_ID='" . $_SESSION['USER_ID'] . "' order by DIA_DATE desc LIMIT 0, 10 ";

		return $this->_db->getArray($query);
	}
	//工作周报
	function getWeeklyList() {
		$NOW_DATE = date("Y-m-d");
		$sqlStr = "select  USER_PRIV,DEPT_ID  from user where  USER_ID='" . $_SESSION['USER_ID'] . "'";
		$fsql = $this->_db->getArray($sqlStr);
		foreach ($fsql as $key => $value) {
			$checkDept = $fsql[$key]['DEPT_ID'];
			$checkPriv = $fsql[$key]['USER_PRIV'];
		}
		$query = "select * from weekly  where USER_ID='" . $_SESSION['USER_ID'] . "' order by Flag , id desc LIMIT 0, 10";

		return $this->_db->getArray($query);
	}
	//个人考勤
	function getAttendanceList() {
		$NOW_DATE = date("Y-m-d");
		$sqlStr = "select  USER_PRIV,DEPT_ID  from user where  USER_ID='" . $_SESSION['USER_ID'] . "'";
		$fsql = $this->_db->getArray($sqlStr);
		foreach ($fsql as $key => $value) {
			$checkDept = $fsql[$key]['DEPT_ID'];
			$checkPriv = $fsql[$key]['USER_PRIV'];
		}
		$query = "select * from attend_leave  where USER_ID='" . $_SESSION['USER_ID'] . "' and STATUS='2' order by LEAVE_ID desc  limit 0,10";

		return $this->_db->getArray($query);
	}
	//IT维修申请表，暂时无数据库表
	function getItserviceList() {
		$NOW_DATE = date("Y-m-d");
		$sqlStr = "select  USER_PRIV,DEPT_ID  from user where  USER_ID='" . $_SESSION['USER_ID'] . "'";
		$fsql = $this->_db->getArray($sqlStr);
		foreach ($fsql as $key => $value) {
			$checkDept = $fsql[$key]['DEPT_ID'];
			$checkPriv = $fsql[$key]['USER_PRIV'];
		}
		$query = "select * from equi_service where appid='" . $_SESSION['USER_ID'] . "' order by flag, appdate limit 0,$num ";

		return $this->_db->getArray($query);
	}
	//课程学习
	function getStudyList() {
		$sqlStr = "select  USER_PRIV,DEPT_ID  from user where  USER_ID='" . $_SESSION['USER_ID'] . "'";
		$fsql = $this->_db->getArray($sqlStr);
		foreach ($fsql as $key => $value) {
			$checkDept = $fsql[$key]['DEPT_ID'];
			$checkPriv = $fsql[$key]['USER_PRIV'];
		}
		$query = "select * from course_data where 1 order by upd desc limit 0, 10 ";

		return $this->_db->getArray($query);
	}
	//BBS论坛
	function getBBSList() {
		$sqlStr = "select  USER_PRIV,DEPT_ID  from user where  USER_ID='" . $_SESSION['USER_ID'] . "'";
		$fsql = $this->_db->getArray($sqlStr);
		foreach ($fsql as $key => $value) {
			$checkDept = $fsql[$key]['DEPT_ID'];
			$checkPriv = $fsql[$key]['USER_PRIV'];
		}
	    $mysql_server_name='localhost:3306';
		$mysql_username='root';
		$mysql_password='root';
		$mysql_database='newcontract';	//phpbb_topics、phpbb_forums两张表所在的数据库
		$conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password);
		mysql_query("SET NAMES 'gb2312'",$conn);//设置编码方式
		if (!$conn) {
			die('Could not connect: ' . mysql_error());
		}
		mysql_select_db($mysql_database,$conn) or die ('Can\'t use foo : ' . mysql_error());
		$query = "SELECT t.topic_last_post_id, t.topic_last_poster_name,t.topic_last_post_time, t.topic_title, t.topic_replies, t.topic_views, f.forum_name, f.forum_id
				FROM phpbb_topics t,phpbb_forums f WHERE f.forum_id = t.forum_id AND f.forum_id != 7 AND f.forum_id != 16 ORDER BY t.topic_last_post_time DESC LIMIT 0,10";
		$result = mysql_query($query);
		$rows = array();
		while($rows[] = mysql_fetch_array($result,MYSQL_ASSOC)){}
		mysql_free_result($result);
		$this->arrSql[] = $query;
		array_pop($rows);
		return $rows;
		mysql_close($conn);
	}
}
?>