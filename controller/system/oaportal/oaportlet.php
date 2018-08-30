<?php


/**
 * ����ԭ���Ż���Ϣ portlet���Ʋ�
 */
class controller_system_oaportal_oaportlet extends controller_base_action {

	function __construct() {
		$this->objName = "oaportlet";
		$this->objPath = "system_oaportal";
		parent :: __construct();
	}

	function processData($rows) {
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = count($rows);
		$arr['page'] = 1;
		return $arr;
	}

	//��ת������ҳ��
	function c_toNotice() {
		//$this->view("notice");
		$rows = $this->service->getNoticeList();
		$html = "<table class='form_main_table'><tr bgcolor='#D2E9FF'align='center'><td width='10' align='center'></td>
				<td width='150'>�������</td>
				<td width='150'>����ʱ��</td></tr>";
		$i=0;
		foreach ($rows as $key => $val) {
			$i++;
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$html = $html.'<tr align="center" ><td>'.$i.'</td><td ><a href="javascript:void(0)" onclick=\'parent.showThickboxWin("?model=info_notice&action=showinfo&id='.$rows[$key]['id'].'&rand_key='.$rows[$key]['rand_key'].'&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800","�鿴��'.$rows[$key]['title'].'��")\'>' .$rows[$key]['title']. '</a></td>'.
					'<td>'.$rows[$key]['datef'].'</td></tr>';
		};
		$html = $html."</table>";
		$this->assign(viewhtml,$html);
		$this->view("view");
	}

	//����֪ͨ
	function c_getNoticeList() {
		$rows = $this->service->getNoticeList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}

		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//��ת��ͶƱ��ҳ��
	function c_toVote() {
		//$this->view("vote");
		$rows = $this->service->getVoteList();
		$html = "<table class='form_main_table'><tr bgcolor='#D2E9FF'><td width='10' align='center'></td>
				<td width='150' align='center'>ͶƱ����</td>
				<td width='150' align='center'>����ʱ��</td></tr>";
		$i=0;
		foreach ($rows as $key => $val) {
			$i++;
			$type = $rows[$key]['TYPE'];
			if($type==0)
				$URL="general/vote/vote_radio.php?Pid=".$rows[$key]['Pid'];
			else
				$URL="general/vote/vote_action.php?Pid=".$rows[$key]['Pid'];

			$html = $html.'<tr align="center"><td>'.$i.'</td><td ><a href="javascript:void(0)"onclick=\'parent.window.open("'.$URL.'","ͶƱ","height=600,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left=250,resizable=yes")\'>' .$rows[$key]['SUBJECT']. '</a></td>'.
					'<td>'.$rows[$key]['BEGIN_DATE'].'</td></tr>';
		};
		$html = $html."</table>";
		$this->assign(viewhtml,$html);
		$this->view("view");
	}

	//ͶƱ��
	function c_getVoteList() {
		$rows = $this->service->getVoteList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//��ת�������ƻ�ҳ��
	function c_toWorkplan() {
		//$this->view("workplan");
		$rows = $this->service->getWorkplanList();
		$html = "<table class='form_main_table'><tr bgcolor='#D2E9FF'>" .
				"<td width='10' align='center'></td>" .
				"<td width='150' align='center'>�����ƻ�</td>
				<td width='150' align='center'>��ʼʱ��</td>
				<td width='150' align='center'>��ֹʱ��</td></tr>";
		$i=0;
		foreach ($rows as $key => $val) {
				$i++;
				$URL="general/work_plan/show/plan_detail.php?PLAN_ID=".$rows[$key]['PLAN_ID'];
				$html = $html . '<tr align="center"><td>'.$i.'</td><td ><a href="javascript:void(0)" onclick=\'parent.window.open("'.$URL.'","�����ƻ�","height=500,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left=200,resizable=yes")\'>' .$rows[$key]['NAME']. '</a></td>'.
				'<td>'.$rows[$key]['BEGIN_DATE'].'</td>'.
				"<td>" .$rows[$key]['END_DATE']. "</td></tr>";
		};
		$html = $html . "</table>";
		$this->assign(viewhtml,$html);
		$this->view("view");
	}
	//�����ƻ�
	function c_getWorkplanList() {
		$rows = $this->service->getWorkplanList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//��ת������ҳ��
	function c_toNews() {
		//$this->view("news");
		$rows = $this->service->getNewsList();
		$html = "<table class='form_main_table'><tr bgcolor='#D2E9FF'><td width='10' align='center'></td>
				<td width='150' align='center'>���ű���</td>
				<td width='150' align='center'>����ʱ��</td></tr>";
		$i=0;
		foreach ($rows as $key => $val) {
			$i++;
			$URL="general/news/show/show_news.php?NEWS_ID=".$rows[$key]['NEWS_ID'];
			$html = $html.'<tr align="center"><td>'.$i.'</td><td ><a href="javascript:void(0)"onclick=\'parent.window.open("'.$URL.'","���ű���","height=500,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left=250,resizable=yes")\'>' .$rows[$key]['SUBJECT']. '</a></td>'.
					'<td>'.$rows[$key]['NEWS_TIME'].'</td></tr>';
		};
		$html = $html."</table>";
		$this->assign(viewhtml,$html);
		$this->view("view");
	}

	//����
	function c_getNewsList() {
		$rows = $this->service->getNewsList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//��ת��OA����ָ��
	function c_toHelp() {
		$this->view("help");
	}
	//��ת�����˷���ҳ��
	function c_toCostpersonal() {
		//$this->view("costpersonal");
		$rows = $this->service->getCostpersonalList();
		$html = "<table class='form_main_table'><tr bgcolor='#D2E9FF'><td width='10' align='center'></td>
				<td width='150' align='center'>��������</td>
				<td width='150' align='center'>�������</td>
				<td width='150' align='center'>����״̬</td></tr>";
		$i=0;
		foreach ($rows as $key => $val) {
				$i++;
				$type = $rows[$key]['isProject'];
				$type = $type==0 ? "���ű���":"��Ŀ����";
				$html = $html . '<tr align="center"><td>'.$i.'</td><td ><a href="javascript:void(0)" onclick="parent.parent.openTab(\'general/costmanage/reim\',\'���˷���\')">' .$type. '</a></td>'.
				'<td><a href="javascript:void(0)" onclick="parent.parent.openTab(\'general/costmanage/reim\',\'���˷���\')">'.$rows[$key]['BillNo'].'</a></td>'.
				'<td><a href="javascript:void(0)" onclick="parent.parent.openTab(\'general/costmanage/reim\',\'���˷���\')">' .$rows[$key]['Status']. '</a></td></tr>';
		};
		$html = $html . "</table>";
		$this->assign(viewhtml,$html);
		$this->view("view");
	}

	//���˷���
	function c_getCostpersonalList() {
		$rows = $this->service->getCostpersonalList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//��ת�����˿���ҳ��
	function c_toAttendance() {
		//$this->view("attendance");
		$rows = $this->service->getAttendanceList();
		$html = "<table class='form_main_table'><tr bgcolor='#D2E9FF'><td width='10' align='center'></td>
				<td width='150' align='center'>���ԭ��</td>
				<td width='150' align='center'>��ְʱ��</td>
				<td width='150' align='center'>��ְʱ��</td>
				<td width='150' align='center'>��������</td></tr>";
		$i=0;
		foreach ($rows as $key => $val) {
				$i++;
				$html = $html . '<tr align="center"><td>'.$i.'</td><td ><a href="javascript:void(0)"onclick="parent.parent.openTab(\'general/attendance/personal/\',\'���˿���\')">' .$rows[$key]['LEAVE_TYPE']. '</td>'.
				'<td>'.$rows[$key]['LEAVE_DATE1'].'</td>'.
				'<td>' .$rows[$key]['LEAVE_DATE2']. '</td>'.
				'<td>' .$rows[$key]['DAYS']. '</td></tr>';
		};
		$html = $html . "</table>";
		$this->assign(viewhtml,$html);
		$this->view("view");
	}

	//���˿���
	function c_getAttendanceList() {
		$rows = $this->service->getAttendanceList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//��ת��������־ҳ��
	function c_toDiary() {
		//$this->view("diary");
		$rows = $this->service->getDiaryList();
		$html = "<table class='form_main_table'><tr bgcolor='#D2E9FF'><td width='10' align='center'></td>
				<td width='150' align='center'>��־����</td>
				<td width='150' align='center'>����ʱ��</td>
				<td width='150' align='center'>��־����</td></tr>";
		$i=0;
		foreach ($rows as $key => $val) {
				$i++;
				$type = $rows[$key]['DIA_TYPE'];
				$type = $type=="1"?"������־":"������־";
				$html = $html . "<tr align='center'><td>$i</td><td >" .$type. '</td>'.
				'<td>'.$rows[$key]['DIA_DATE'].'</td>'.
				'<td><a href="javascript:void(0)" onclick="parent.parent.openTab(\'general/diary/read.php?DIA_ID='.$rows[$key]['DIA_ID'].'\',\'������־\')">' .$rows[$key]['SUBJECT'].'</a></td></tr>';
		};
		$html = $html . "</table>";
		$this->assign(viewhtml,$html);
		$this->view("view");
	}

	//������־
	function c_getDiaryList() {
		$rows = $this->service->getDiaryList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//��ת�������ܱ�ҳ��
	function c_toWeekly() {
		//$this->view("weekly");
		$rows = $this->service->getWeeklyList();
		$html = "<table class='form_main_table'><tr bgcolor='#D2E9FF'><td width='10' align='center'></td>
				<td width='150' align='center'>�ܱ�����</td>
				<td width='150' align='center'>�ܴ�</td>
				<td width='250' align='center'>�ܱ�����</td></tr>";
		$i=0;
		foreach ($rows as $key => $val) {
				$i++;
				$type = $rows[$key]['type'];
				$URL="general/weekly/read_weekly.php?read_id=".$rows[$key]['id'];
				if($type==0)
					$type="���ܱ�";
				else if($type==1)
					$type="˫�ܱ�";
				else $type="�±�";
				$html = $html . "<tr align='center'><td>$i</td><td >".$type. '</td>'.
				'<td>'.$rows[$key]['w_num'].'</td>'.
				'<td><a href="javascript:void(0)" onclick=\'parent.window.open("'.$URL.'","read_notify","height=550,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left=250,resizable=yes")\'>' .$rows[$key]['title'].'</a></td></tr>';
		};
		$html = $html . "</table>";
		$this->assign(viewhtml,$html);
		$this->view("view");
	}

	//�����ܱ�
	function c_getWeeklyList() {
		$rows = $this->service->getWeeklyList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//��ת��ά�������ҳ��
	function c_toItservice() {
		$this->view("itservice");
	}

	//ά�������
	function c_getItserviceList() {
		$rows = $this->service->getItserviceList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//��ת��ά�޹���ҳ��
	function c_toItserviceman() {
		$this->view("itserviceman");
	}

	//ά�޹���
	function c_getItservicemanList() {
		$rows = $this->service->getItservicemanList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}
	//��ת����̳
	function c_toBBS() {
		//$this->view("bbs");
		$rows = $this->service->getBBSList();
		$html="<table class='form_main_table'><tr bgcolor='#D2E9FF'><td width='10' align='center'></td>
				<td width='250' align='center'>����</td>
				<td width='150' align='center'>���ظ�ʱ��</td>
				<td width='150' align='center'>���ظ���</td></tr>";
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

	//��̳
	function c_getBBSList() {
		$rows = $this->service->getBBSList();
		foreach ($rows as $key => $val) {
			$rows[$key]['datef'] = date('Y-m-d H:i:s', $val['date']);
			$rows[$key]['isnew'] = ($val['date'] - (time() - 3 * 24 * 3600)) >= 0;
			$rows[$key]['topic_last_post_time'] = date('Y-m-d H:i:s', $val['topic_last_post_time']);
		}
		echo util_jsonUtil :: encode($this->processData($rows));
	}

	//��ת���γ�ѧϰҳ��
	function c_toStudy() {
		// $this->view("study");
		$rows = $this->service->getStudyList();
		$html = "<table class='form_main_table'><tr bgcolor='#D2E9FF'><td width='10' align='center'></td>
				<td width='150' align='center'>�γ�����</td>
				<td width='150' align='center'>��������</td></tr>";
		foreach ($rows as $key => $val) {
			$type = $rows[$key]['type'] == 1 ? "��Ƶ��" : "�ĵ���";
			$html = $html . '<tr align="center"><td><a href="javascript:void(0)" onclick="parent.parent.openTab(\'general/education/training/study/index.php?data_id='.$rows[$key]['pid'].'&play_id='.$rows[$key]['id'].'\',\'�γ�ѧϰ\')">'.$rows[$key]['id'].'</a></td>'.
			'<td><a href="javascript:void(0)" onclick="parent.parent.openTab(\'general/education/training/study/index.php?data_id='.$rows[$key]['pid'].'&play_id='.$rows[$key]['id'].'\',\'�γ�ѧϰ\')">'.$rows[$key]['name']."</a></td>" .
			'<td><a href="javascript:void(0)" onclick="parent.parent.openTab(\'general/education/training/study/index.php?data_id='.$rows[$key]['pid'].'&play_id='.$rows[$key]['id'].'\',\'�γ�ѧϰ\')">'.$type."</a></td></tr>";
		};
		$html = $html . "</table>";
		$this->assign(viewhtml,$html);
		$this->view("view");
	}

	//�γ�ѧϰ
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