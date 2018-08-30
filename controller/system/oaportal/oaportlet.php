<?php


/**
 * 鼎利原有门户信息 portlet控制层
 */
class controller_system_oaportal_oaportlet extends controller_base_action {

	function __construct() {
		$this->objName = "oaportlet";
		$this->objPath = "system_oaportal";
		parent :: __construct();
	}

	function processData($rows) {
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = count($rows);
		$arr['page'] = 1;
		return $arr;
	}

	//跳转到公告页面
	function c_toNotice() {
		//$this->view("notice");
		$rows = $this->service->getNoticeList();
		$html = "<table class='form_main_table'><tr bgcolor='#D2E9FF'align='center'><td width='10' align='center'></td>
				<td width='150'>公告标题</td>
				<td width='150'>发布时间</td></tr>";
		$i=0;
		foreach ($rows as $key => $val) {
			$i++;
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$html = $html.'<tr align="center" ><td>'.$i.'</td><td ><a href="javascript:void(0)" onclick=\'parent.showThickboxWin("?model=info_notice&action=showinfo&id='.$rows[$key]['id'].'&rand_key='.$rows[$key]['rand_key'].'&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800","查看《'.$rows[$key]['title'].'》")\'>' .$rows[$key]['title']. '</a></td>'.
					'<td>'.$rows[$key]['datef'].'</td></tr>';
		};
		$html = $html."</table>";
		$this->assign(viewhtml,$html);
		$this->view("view");
	}

	//公告通知
	function c_getNoticeList() {
		$rows = $this->service->getNoticeList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}

		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//跳转到投票箱页面
	function c_toVote() {
		//$this->view("vote");
		$rows = $this->service->getVoteList();
		$html = "<table class='form_main_table'><tr bgcolor='#D2E9FF'><td width='10' align='center'></td>
				<td width='150' align='center'>投票主题</td>
				<td width='150' align='center'>发布时间</td></tr>";
		$i=0;
		foreach ($rows as $key => $val) {
			$i++;
			$type = $rows[$key]['TYPE'];
			if($type==0)
				$URL="general/vote/vote_radio.php?Pid=".$rows[$key]['Pid'];
			else
				$URL="general/vote/vote_action.php?Pid=".$rows[$key]['Pid'];

			$html = $html.'<tr align="center"><td>'.$i.'</td><td ><a href="javascript:void(0)"onclick=\'parent.window.open("'.$URL.'","投票","height=600,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left=250,resizable=yes")\'>' .$rows[$key]['SUBJECT']. '</a></td>'.
					'<td>'.$rows[$key]['BEGIN_DATE'].'</td></tr>';
		};
		$html = $html."</table>";
		$this->assign(viewhtml,$html);
		$this->view("view");
	}

	//投票箱
	function c_getVoteList() {
		$rows = $this->service->getVoteList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//跳转到工作计划页面
	function c_toWorkplan() {
		//$this->view("workplan");
		$rows = $this->service->getWorkplanList();
		$html = "<table class='form_main_table'><tr bgcolor='#D2E9FF'>" .
				"<td width='10' align='center'></td>" .
				"<td width='150' align='center'>工作计划</td>
				<td width='150' align='center'>开始时间</td>
				<td width='150' align='center'>截止时间</td></tr>";
		$i=0;
		foreach ($rows as $key => $val) {
				$i++;
				$URL="general/work_plan/show/plan_detail.php?PLAN_ID=".$rows[$key]['PLAN_ID'];
				$html = $html . '<tr align="center"><td>'.$i.'</td><td ><a href="javascript:void(0)" onclick=\'parent.window.open("'.$URL.'","工作计划","height=500,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left=200,resizable=yes")\'>' .$rows[$key]['NAME']. '</a></td>'.
				'<td>'.$rows[$key]['BEGIN_DATE'].'</td>'.
				"<td>" .$rows[$key]['END_DATE']. "</td></tr>";
		};
		$html = $html . "</table>";
		$this->assign(viewhtml,$html);
		$this->view("view");
	}
	//工作计划
	function c_getWorkplanList() {
		$rows = $this->service->getWorkplanList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//跳转到新闻页面
	function c_toNews() {
		//$this->view("news");
		$rows = $this->service->getNewsList();
		$html = "<table class='form_main_table'><tr bgcolor='#D2E9FF'><td width='10' align='center'></td>
				<td width='150' align='center'>新闻标题</td>
				<td width='150' align='center'>发布时间</td></tr>";
		$i=0;
		foreach ($rows as $key => $val) {
			$i++;
			$URL="general/news/show/show_news.php?NEWS_ID=".$rows[$key]['NEWS_ID'];
			$html = $html.'<tr align="center"><td>'.$i.'</td><td ><a href="javascript:void(0)"onclick=\'parent.window.open("'.$URL.'","新闻标题","height=500,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left=250,resizable=yes")\'>' .$rows[$key]['SUBJECT']. '</a></td>'.
					'<td>'.$rows[$key]['NEWS_TIME'].'</td></tr>';
		};
		$html = $html."</table>";
		$this->assign(viewhtml,$html);
		$this->view("view");
	}

	//新闻
	function c_getNewsList() {
		$rows = $this->service->getNewsList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//跳转到OA操作指引
	function c_toHelp() {
		$this->view("help");
	}
	//跳转到个人费用页面
	function c_toCostpersonal() {
		//$this->view("costpersonal");
		$rows = $this->service->getCostpersonalList();
		$html = "<table class='form_main_table'><tr bgcolor='#D2E9FF'><td width='10' align='center'></td>
				<td width='150' align='center'>报销类型</td>
				<td width='150' align='center'>报销编号</td>
				<td width='150' align='center'>报销状态</td></tr>";
		$i=0;
		foreach ($rows as $key => $val) {
				$i++;
				$type = $rows[$key]['isProject'];
				$type = $type==0 ? "部门报销":"项目报销";
				$html = $html . '<tr align="center"><td>'.$i.'</td><td ><a href="javascript:void(0)" onclick="parent.parent.openTab(\'general/costmanage/reim\',\'个人费用\')">' .$type. '</a></td>'.
				'<td><a href="javascript:void(0)" onclick="parent.parent.openTab(\'general/costmanage/reim\',\'个人费用\')">'.$rows[$key]['BillNo'].'</a></td>'.
				'<td><a href="javascript:void(0)" onclick="parent.parent.openTab(\'general/costmanage/reim\',\'个人费用\')">' .$rows[$key]['Status']. '</a></td></tr>';
		};
		$html = $html . "</table>";
		$this->assign(viewhtml,$html);
		$this->view("view");
	}

	//个人费用
	function c_getCostpersonalList() {
		$rows = $this->service->getCostpersonalList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//跳转到个人考勤页面
	function c_toAttendance() {
		//$this->view("attendance");
		$rows = $this->service->getAttendanceList();
		$html = "<table class='form_main_table'><tr bgcolor='#D2E9FF'><td width='10' align='center'></td>
				<td width='150' align='center'>请假原因</td>
				<td width='150' align='center'>离职时间</td>
				<td width='150' align='center'>复职时间</td>
				<td width='150' align='center'>假期天数</td></tr>";
		$i=0;
		foreach ($rows as $key => $val) {
				$i++;
				$html = $html . '<tr align="center"><td>'.$i.'</td><td ><a href="javascript:void(0)"onclick="parent.parent.openTab(\'general/attendance/personal/\',\'个人考勤\')">' .$rows[$key]['LEAVE_TYPE']. '</td>'.
				'<td>'.$rows[$key]['LEAVE_DATE1'].'</td>'.
				'<td>' .$rows[$key]['LEAVE_DATE2']. '</td>'.
				'<td>' .$rows[$key]['DAYS']. '</td></tr>';
		};
		$html = $html . "</table>";
		$this->assign(viewhtml,$html);
		$this->view("view");
	}

	//个人考勤
	function c_getAttendanceList() {
		$rows = $this->service->getAttendanceList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//跳转到工作日志页面
	function c_toDiary() {
		//$this->view("diary");
		$rows = $this->service->getDiaryList();
		$html = "<table class='form_main_table'><tr bgcolor='#D2E9FF'><td width='10' align='center'></td>
				<td width='150' align='center'>日志类型</td>
				<td width='150' align='center'>发布时间</td>
				<td width='150' align='center'>日志标题</td></tr>";
		$i=0;
		foreach ($rows as $key => $val) {
				$i++;
				$type = $rows[$key]['DIA_TYPE'];
				$type = $type=="1"?"工作日志":"个人日志";
				$html = $html . "<tr align='center'><td>$i</td><td >" .$type. '</td>'.
				'<td>'.$rows[$key]['DIA_DATE'].'</td>'.
				'<td><a href="javascript:void(0)" onclick="parent.parent.openTab(\'general/diary/read.php?DIA_ID='.$rows[$key]['DIA_ID'].'\',\'工作日志\')">' .$rows[$key]['SUBJECT'].'</a></td></tr>';
		};
		$html = $html . "</table>";
		$this->assign(viewhtml,$html);
		$this->view("view");
	}

	//工作日志
	function c_getDiaryList() {
		$rows = $this->service->getDiaryList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//跳转到工作周报页面
	function c_toWeekly() {
		//$this->view("weekly");
		$rows = $this->service->getWeeklyList();
		$html = "<table class='form_main_table'><tr bgcolor='#D2E9FF'><td width='10' align='center'></td>
				<td width='150' align='center'>周报类型</td>
				<td width='150' align='center'>周次</td>
				<td width='250' align='center'>周报标题</td></tr>";
		$i=0;
		foreach ($rows as $key => $val) {
				$i++;
				$type = $rows[$key]['type'];
				$URL="general/weekly/read_weekly.php?read_id=".$rows[$key]['id'];
				if($type==0)
					$type="单周报";
				else if($type==1)
					$type="双周报";
				else $type="月报";
				$html = $html . "<tr align='center'><td>$i</td><td >".$type. '</td>'.
				'<td>'.$rows[$key]['w_num'].'</td>'.
				'<td><a href="javascript:void(0)" onclick=\'parent.window.open("'.$URL.'","read_notify","height=550,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left=250,resizable=yes")\'>' .$rows[$key]['title'].'</a></td></tr>';
		};
		$html = $html . "</table>";
		$this->assign(viewhtml,$html);
		$this->view("view");
	}

	//工作周报
	function c_getWeeklyList() {
		$rows = $this->service->getWeeklyList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//跳转到维修申请表页面
	function c_toItservice() {
		$this->view("itservice");
	}

	//维修申请表
	function c_getItserviceList() {
		$rows = $this->service->getItserviceList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//跳转到维修管理页面
	function c_toItserviceman() {
		$this->view("itserviceman");
	}

	//维修管理
	function c_getItservicemanList() {
		$rows = $this->service->getItservicemanList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//跳转到论坛
	function c_toBBS() {
		//$this->view("bbs");
		$rows = $this->service->getBBSList();
		$html="<table class='form_main_table'><tr bgcolor='#D2E9FF'><td width='10' align='center'></td>
				<td width='250' align='center'>主题</td>
				<td width='150' align='center'>最后回复时间</td>
				<td width='150' align='center'>最后回复人</td></tr>";
		$i = 0;
		foreach ($rows as $key => $val) {
			$i++;
			$rows[$key]['topic_last_post_time'] = date('Y-m-d H:i:s', $val['topic_last_post_time']);
			$html=$html."<tr align='center'><td>$i</td><td >" . $rows[$key]['topic_title'] . "</td>" .
			"<td>" . $rows[$key]['topic_last_post_time'] . "</td>" .
			"<td>" . $rows[$key]['topic_last_poster_name'] . "</td></tr>";
		};
		$html=$html."</table>";
		$this->assign(viewhtml,$html);
		$this->view("view");
	}

	//论坛
	function c_getBBSList() {
		$rows = $this->service->getBBSList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
			$rows[$key]['topic_last_post_time'] = date('Y-m-d H:i:s', $val['topic_last_post_time']);
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}

	//跳转到课程学习页面
	function c_toStudy() {
		// $this->view("study");
		$rows = $this->service->getStudyList();
		$html = "<table class='form_main_table'><tr bgcolor='#D2E9FF'><td width='10' align='center'></td>
				<td width='150' align='center'>课程名称</td>
				<td width='150' align='center'>资料种类</td></tr>";
		foreach ($rows as $key => $val) {
			$type = $rows[$key]['type'] == 1 ? "视频类" : "文档类";
			$html = $html . '<tr align="center"><td><a href="javascript:void(0)" onclick="parent.parent.openTab(\'general/education/training/study/index.php?data_id='.$rows[$key]['pid'].'&play_id='.$rows[$key]['id'].'\',\'课程学习\')">'.$rows[$key]['id'].'</a></td>'.
			'<td><a href="javascript:void(0)" onclick="parent.parent.openTab(\'general/education/training/study/index.php?data_id='.$rows[$key]['pid'].'&play_id='.$rows[$key]['id'].'\',\'课程学习\')">'.$rows[$key]['name']."</a></td>" .
			'<td><a href="javascript:void(0)" onclick="parent.parent.openTab(\'general/education/training/study/index.php?data_id='.$rows[$key]['pid'].'&play_id='.$rows[$key]['id'].'\',\'课程学习\')">'.$type."</a></td></tr>";
		};
		$html = $html . "</table>";
		$this->assign(viewhtml,$html);
		$this->view("view");
	}

	//课程学习
	function c_getStudyList() {
		$rows = $this->service->getStudyList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}
}
?>