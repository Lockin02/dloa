<?php

/**
 * @description: 项目任务Model
 * @date 2010-9-14 下午02:49:24
 * @author oyzx
 * @version V1.0
 */
class model_rdproject_task_rdtask extends model_base {

	/**
	 * @desription 构造函数
	 * @date 2010-9-14 下午02:50:04
	 */
	function __construct() {
		$this->tbl_name = "oa_rd_task";
		$this->sql_map = "rdproject/task/rdtaskSql.php";
		$this->pk = "id";
		//		$this->email = new includes_class_sendmail ();
		parent :: __construct();
	}

	/* ---------------------------------页面模板显示调用------------------------------------------*/

	/*
	 * 项目任务汇总信息列表模板
	 */
	function showAllTasks($rows, $showpage) {
		$str = null;
		return $str . '<tr><td colspan="7" style="text-align:center;">' . $showpage->show(6) . '</td></tr>';
	}

	/*
	 * 我接收的项目任务信息列表模板
	 */
	function showPReceivedTaskList($rows) {
		if ($rows) {
			$i = 0;
			$str = "";
			$datadictDao = new model_system_datadict_datadict();
			foreach ($rows as $key => $val) {
				$i++;
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$img = ($val['warpRate'] == 0) ? "<img src='images/Knob_Green1.gif'>" : "<img src='images/Knob_red1.gif'>";
				$typeOne = $datadictDao->getDataNameByCode($val['taskType']);
				$firstOne = $datadictDao->getDataNameByCode($val['priority']);
				$tkStatus = $datadictDao->getDataNameByCode($val['status']);
				$finishGrade = $datadictDao->getDataNameByCode($val['finishGrade']);
				$str .=<<<EOT
						<tr id="tr_$val[id]" pid="$val[id]" pstatus="$val[status]" class="$classCss">
							<td align="center">$i</td>
							<td align="center">$img</td>
							<td align="center" class="main_td_align_left">
								$val[name]
							</td>
							<td align="center">$val[projectName]</td>
							<td align="center">$firstOne</td>
							<td align="center">$tkStatus</td>
							<td align="center">$finishGrade</td>
							<td align="center">$val[effortRate]</td>
							<td align="center">$val[warpRate]</td>
							<td align="center">$val[chargeName]</td>
							<td align="center">$val[publishName]</td>
							<td align="center">$val[createTime]</td>
							<td align="center">$val[planEndDate]</td>
							<td align="center">$typeOne</td>
						</tr>
EOT;
			}
			return $str;
		} else
			return $str = '<tr><td colspan="20">暂无相关记录</td></tr>';

	}

	/*
	 * 我分配的项目任务信息列表模板
	 */
	function showPAllotTaskList($rows) {
		if ($rows) {
			$i = 0;
			$str = "";
			$datadictDao = new model_system_datadict_datadict();
			foreach ($rows as $key => $val) {
				$i++;
				$endStr = "";
				$img = ($val['warpRate'] == 0) ? "<img src='images/Knob_Green1.gif'>" : "<img src='images/Knob_red1.gif'>";
				$typeOne = $datadictDao->getDataNameByCode($val['taskType']);
				$firstOne = $datadictDao->getDataNameByCode($val['priority']);
				$tkStatus = $datadictDao->getDataNameByCode($val['status']);
				$finishGrade = $datadictDao->getDataNameByCode($val['finishGrade']);
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .=<<<EOT
						<tr id="tr_$val[id]" pid="$val[id]" pstatus="$val[status]" pplanId="$val[planId]" class="$classCss">
							<td align="center">$i</td>
							<td align="center">$img</td>
							<td align="center" class="main_td_align_left">
								$val[name]
							</td>
							<td align="center">$val[projectName]</td>
							<td align="center">$firstOne</td>
							<td align="center">$tkStatus</td>
							<td align="center">$finishGrade</td>
							<td align="center">$val[effortRate]</td>
							<td align="center">$val[warpRate]</td>
							<td align="center">$val[chargeName]</td>
							<td align="center">$val[updateTime]</td>
							<td align="center">$val[planEndDate]</td>
							<td align="center">$typeOne</td>

						</tr>
EOT;
			}
			return $str;
		} else
			return $str = '<tr><td colspan="20">暂无相关记录</td></tr>';

	}
	/*
	 * 我审核的项目任务信息列表模板
	 */
	function showPAuditTaskList($rows) {
		if ($rows) {
			$i = 0;
			$str = "";
			$datadictDao = new model_system_datadict_datadict();
			foreach ($rows as $key => $val) {
				$i++;
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$img = ($val['warpRate'] == 0) ? "<img src='images/Knob_Green1.gif'>" : "<img src='images/Knob_red1.gif'>";
				$typeOne = $datadictDao->getDataNameByCode($val['taskType']);
				$firstOne = $datadictDao->getDataNameByCode($val['priority']);
				$tkStatus = $datadictDao->getDataNameByCode($val['status']);
				$finishGrade = $datadictDao->getDataNameByCode($val['finishGrade']);
				$str .=<<<EOT
						<tr id="tr_$val[id]" pid="$val[id]" pstatus="$val[status]" class="$classCss">
							<td height="25" align="center">$i</td>
							<td align="center">$img</td>
							<td align="center" class="main_td_align_left">
								$val[name]
							</td>
							<td align="center">$val[projectName]</td>
							<td align="center">$firstOne</td>
							<td align="center">$tkStatus</td>
							<td align="center">$finishGrade</td>
							<td align="center">$val[effortRate]</td>
							<td align="center">$val[warpRate]</td>
							<td align="center">$val[chargeName]</td>
							<td align="center">$val[publishName]</td>
							<td align="center">$val[updateTime]</td>
							<td align="center">$val[planEndDate]</td>
							<td align="center">$typeOne</td>
						</tr>
EOT;
			}
			return $str;
		} else
			return $str = '<tr><td colspan="20">暂无相关记录</td></tr>';

	}
	/*
	 *一键通任务列表模板
	 */
	function showOnekeyTaskList($rows) {
		if ($rows) {
			$i = 0;
			$str = "";
			$datadictDao = new model_system_datadict_datadict();

			foreach ($rows as $key => $val) {
				$i++;
				$typeOne = $datadictDao->getDataNameByCode($val['taskType']);
				$status = $datadictDao->getDataNameByCode($val['status']);
				$priority = $datadictDao->getDataNameByCode($val['priority']);
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .=<<<EOT
						<tr id="tr_$val[id]" class="$classCss">
							<td height="25" align="center">$i</td>
							<td align="center"><img src="images/ico4.gif"></img></td>
							<td align="center" class="main_td_align_left">
								$val[name]
							</td>
							<td align="center">$val[projectName]</td>
							<td align="center">$priority</td>
							<td align="center">$status</td>
							<td align="center">$val[effortRate]</td>
							<td align="center">$val[warpRate]</td>
							<td align="center">$val[chargeName]</td>
							<td align="center">$val[publishName]</td>
							<td align="center">$val[updateTime]</td>
							<td align="center">$val[planEndDate]</td>
							<td align="center">$typeOne</td>
							<td align="center" id="m_$val[id] ">
								<b><a href="?model=rdproject_task_rdtask&action=toEditTask&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800" title="修改<$val[name]>" class="thickbox">修改</a></b>|
								<b><a href="?model=rdproject_task_rdtask&action=toOneKeyDetail&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800" title="查看<$val[name]>" class="thickbox">详细</a></b>|
								<b><a href="?model=rdproject_task_rdtask&action=init&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=500" title="发送<$val[name]>" class="thickbox">沟通</a></b>
							</td>
						</tr>
EOT;
			}
			return $str;
		} else
			return $str = '<tr><td colspan="20">暂无相关记录</td></tr>';

	}
	/**
	 * @desription 进度计划批量编辑列表
	 * @param tags
	 * @date 2010-9-26 上午09:36:24
	 */
	function showBatchEditorTaskList($rows) {
		if ($rows) {
			$i = $m = 0;
			$datadictDao = new model_system_datadict_datadict();
			$str = "";
			$str .=<<<EOT
			<tr>
			<td colspan="20">
			<table class="main_table">
	<thead>
		<tr class="main_tr_header">
			<th width="5%">序号</th>
			<th width="15%">任务名称</th>
			<th width="15%">计划开始日期</th>
			<th width="15%">计划完成日期</th>
			<th width="15%">任务优先级</th>
			<th width="10%">审核人</th>
			<th width="10%">参与人</th>
			<th>任务类型</th>
		</tr>
	</thead>
EOT;

			foreach ($rows as $key => $val) {
				$m++;
				$typeOne = $datadictDao->getDataNameByCode($val['taskType']);
				$priority = $datadictDao->getDataNameByCode($val['priority']);
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .=<<<EOT
						<tr id="tr_$val[id]" class="$classCss">
							<td height="25" align="center">$m</td>
							<td align="center" class="main_td_align_left">
								<input type="hidden" id="id" name="rdtask[id]" value="$val[id]">
								<input type="hidden" id="id" name="rdtask[$i][id]" value="$val[id]">
								<input tye="text" name="rdtask[$i][name]" id="id" value="$val[name]">
							</td>
							<td align="center"><input type="text" value="$val[planBeginDate]" id="planBeginDate" name="rdtask[$i][planBeginDate]" onFocus="WdatePicker()"></td>
							<td align="center"><input type="text" value="$val[planEndDate]" id="planEndDate" name="rdtask[$i][planEndDate]" onFocus="WdatePicker()"></td>
							<td align="center"><input type="text" value="$priority" id="priority" name="rdtask[$i][priority]"></td>
							<td align="center">
								<input type="hidden" id="chargeId" name="rdtask[$i][chargeId]">
								<input type="text" value="$val[chargeName]" name="rdtask[$i][chargeName]" id="chargeName">
							</td>
							<td align="center">
								<input type="hidden" id="publishId" name="rdtask[$i][publishId]">
								<input type="text"id="publishName"name="rdtask[$i][publishName]" value="$val[publishName]"></td>
							<td align="center">
								<input type="text" value="$typeOne" name="rdtask[$i][taskType]">
							</td>
						</tr>
EOT;
				$i++;
			}
			$str .=<<<EOT
			   </table>
                    </td>
                </tr>
EOT;
			return $str;
		} else
			return $str = '<tr><td colspan="20">暂无相关记录</td></tr>';
	}

	/**
	 * 完成任务的数组转成列表
	 */
	function showEndTask($rows) {
		if ($rows) {
			$str = null;
			$i = 0;
			foreach ($rows as $val) {
				$i++;
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				//				$biasP = round ( $this->biasProportion ( $val ['putWorkload'], $val ['appraiseWorkload'] ) * 100, 2 );
				$str .=<<<EOT
					<tr id="tr_$val[id]" class="$classCss">
						<td>
							$i
						</td>
						<td>
							$val[name]
						</td>
						<td>
							$val[projectName]
						</td>
						<td>
							$val[planBeginDate]
						</td>
						<td>
							$val[actBeginDate]
						</td>
						<td>
							$val[planEndDate]
						</td>
						<td>
							$val[actEndDate]
						</td>
						<td>
							$val[appraiseWorkload]
						</td>
						<td>
							$val[putWorkload]
						</td>
						<td>
							$val[warpRate] %
						</td>
					</tr>
EOT;
			}
			return $str;
		} else
			return $str = '<tr><td colspan="20">暂无相关记录</td></tr>';
	}
	/*
	 * 显示工作进展结果列表
	 */
	function showWorkSchedule($rows, $condition_arr = null) {
		if ($rows) {
			$i = 0;
			$str = "";
			$datadictDao = new model_system_datadict_datadict();
			$worklog = new model_rdproject_worklog_rdworklog();
			foreach ($rows as $key => $val) {
				$i++;
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$tkStatus = $datadictDao->getDataNameByCode($val['status']);
				if ($val['warpRate'] > 0) {
					$warpRate = "<img src='images/icon/icon070.gif' title='偏差率为：" . $val['warpRate'] . "'>";
				} else {
					$warpRate = "<img src='images/icon/icon072.gif' title='偏差率为：" . $val['warpRate'] . "'>";
				}
				$str .=<<<EOT
						<tr class="$classCss">
							<td>$val[userName]</td>
							<td>$warpRate</td>
							<td>$val[name]</td>
							<td>$val[projectName]</td>
							<td>$tkStatus</td>
							<td>$val[effortRate] %</td>
EOT;
				$workrows = $worklog->getWorkDay($condition_arr['beginDate'], $condition_arr['overDate'], $val['actUserId'], $val['id']);
				//				echo "<pre>";
				$workrows = $this->changeRows($workrows);
				//				print_r($workrows);
				$beginStamp = $this->getTheDate($condition_arr['beginDate']);
				$overStamp = $this->getTheDate($condition_arr['overDate'], '0');
				for ($m = $beginStamp; $m <= $overStamp; $m += 86400) {
					$temp = date('Y-m-d', $m);
					if (isset ($workrows[$temp])) {
						$str .= '<td><a href="#" onclick="readWorklog(\''. $val['id'].'\',\''.$temp.'\',\''.$val['actUserId'] .'\')">' . $workrows[$temp] . '</a></td>';
					} else {
						$str .= '<td></td>';
					}
				}
				$str .=<<<EOT
						</tr>
EOT;
			}
			return $str;
		} else
			return '<tr><td colspan="20">暂无相关信息</td></tr>';

	}

	/*
	 *项目任务视图列表模板
	 */
	function showProjectTaskList($rows) {
		if ($rows) {
			$i = 0;
			$str = "";
			$datadictDao = new model_system_datadict_datadict();

			foreach ($rows as $key => $val) {
				$i++;
				$typeOne = $datadictDao->getDataNameByCode($val['taskType']);
				$status = $datadictDao->getDataNameByCode($val['status']);
				$priority = $datadictDao->getDataNameByCode($val['priority']);
				$img = ($val['warpRate'] == 0) ? "<img src='images/icon/icon072.gif'>" : "<img src='images/icon/icon070.gif'>";
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .=<<<EOT
						<tr id="tr_$val[id]" pid="$val[id]" pstatus="$val[status]" class="$classCss thickbox" ondblclick="toView($val[id]);">
							<td height="25" align="center">$i</td>
							<td align="center">$img</td>
							<td align="center" class="main_td_align_left">
								$val[name]
							</td>
							<td align="center">$val[planName]</td>
							<td align="center">$priority</td>
							<td align="center">$status</td>
							<td align="center">$val[effortRate]</td>
							<td align="center">$val[warpRate]</td>
							<td align="center">$val[chargeName]</td>
							<td align="center">$val[publishName]</td>
							<td align="center">$val[updateTime]</td>
							<td align="center">$val[planEndDate]</td>
							<td align="center">$typeOne</td>
						</tr>
EOT;
			}
			return $str;
		} else
			return '<tr><td colspan="20">暂无相关信息</td></tr>';

	}
	/*
	 * 项目任务进展视图列表模板
	 */
	function showProTkProcessList($rows, $beginDate, $endDate) {
		/*s:列表头*/
		//		echo "<pre>";
		//		print_r($rows);
		$week = array (
			'日',
			'一',
			'二',
			'三',
			'四',
			'五',
			'六'
		);
		$str =<<<EOT
			<tr class="main_tr_header">
				<th colspan="4">
				</th>
				{thfirst}
			</tr>
			<tr class="main_tr_header">
				<th width="100px">
					序号
				</th>
				<th width="400px">
					任务名称
				</th>
				<th width="100px">
					状态
				</th>
				<th width="100px">
					完成率
				</th>
				{thsecond}
			</tr>
EOT;
		$beginStamp = $this->getTheDate($beginDate);
		$overStamp = $this->getTheDate($endDate, '0');

		$j = 0; //记录星期几
		for ($i = $beginStamp; $i <= $overStamp; $i += 86400) {
			$j++;
			$str = str_replace('{thsecond}', '<th width="50px">' . $week[date('w', $i)] . '</th>{thsecond}', $str);
			if ($j == '7') {
				$str = str_replace('{thfirst}', '<th colspan="7"><b>' . date('Y-m-d', $i - (6 * 86400)) . "</b>__<b>" . date('Y-m-d', $i) . '</b></th>{thfirst}', $str);
				$j = 0;
			}
		}
		$str = str_replace('{thfirst}', '', $str);
		$str = str_replace('{thsecond}', '', $str);
		/*e:列表头*/

		/*s:列表body*/
		if ($rows) {
			$i = 1;
			$datadictDao = new model_system_datadict_datadict();
			foreach ($rows as $key => $val) {
				$nBStamp = $beginStamp;
				$nEStamp = $overStamp;
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$tkStatus = $datadictDao->getDataNameByCode($val['status']);
				$str .=<<<EOT
							<tr id="tr_$val[id]" pid="$val[id]" class="$classCss">
							<td height="25" align="center">$i</td>
							<td align="center">$val[name]</td>
							<td align="center" >
								$tkStatus
							</td>
							<td align="center" >
								$val[effortRate]
							</td>
EOT;

				$dayWorkRow = $val['dayWork'];
				for ($nBStamp; $nBStamp <= $nEStamp; $nBStamp += 24 * 60 * 60) {
					$str .=<<<EOT
							<td align="center" >
								$dayWorkRow[$nBStamp]
							</td>
EOT;

				}

				$str .=<<<EOT
						</tr>
EOT;
				$i++;
			}
		} else
			$str .= '<tr><td colspan="50">暂无相关信息</td></tr>';

		/*e:列表body*/

		return $str;
	}

	/*
	 * 项目任务偏差视图列表模板
	 */
	function showProTkDeviationList($rows) {
		if ($rows) {
			$i = 0;
			$str = "";
			$datadictDao = new model_system_datadict_datadict();

			foreach ($rows as $key => $val) {
				$i++;
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$img = ($val['warpRate'] == 0) ? "<img src='images/icon/icon072.gif'>" : "<img src='images/icon/icon070.gif'>";
				$status = $datadictDao->getDataNameByCode($val['status']);
				$p1 = ""; //计划开始日期、实际开始日期偏差
				$p2 = ""; //计划完成日期、实际完成日期偏差
				$p3 = ""; //计划工期、实际工期偏差
				$p4 = ""; //估计工作量、实际工作量偏差
				if ($val['actBeginDate'] != "")
					$p1 = (strtotime($val['actBeginDate']) - strtotime($val['planBeginDate'])) / 86400;
				if ($val['actEndDate'] != "")
					$p2 = (strtotime($val['actEndDate']) - strtotime($val['planEndDate'])) / 86400;
				if ($val['realDuration'] != "")
					$p3 = $val['realDuration'] - $val['planDuration'];
				if ($val['actWorkload'] != "")
					$p4 = $val['actWorkload'] - $val['appraiseWorkload'];
				$str .=<<<EOT
						<tr id="tr_$val[id]" pid="$val[id]" class="$classCss">
							<td height="25" align="center">$i</td>
							<td align="center">$img</td>
							<td align="center" class="main_td_align_left">
								$val[name]
							</td>
							<td align="center">$status</td>
							<td align="center">$val[effortRate]</td>
							<td align="center">$val[warpRate]</td>
							<td align="center">$val[chargeName]</td>
							<td align="center">$val[planBeginDate]</td>
							<td align="center">$val[actBeginDate]</td>
							<td align="center">$p1</td>
							<td align="center">$val[planEndDate]</td>
							<td align="center">$val[actEndDate]</td>
							<td align="center">$p2</td>
							<td align="center">$val[planDuration]</td>
							<td align="center">$val[realDuration]</td>
							<td align="center">$p3</td>
							<td align="center">$val[appraiseWorkload]</td>
							<td align="center">$val[actWorkload]</td>
							<td align="center">$p4</td>
						</tr>
EOT;
			}
			return $str;
		} else
			return '<tr><td colspan="20">暂无相关信息</td></tr>';

	}

	/*
	 *1.项目任务按任务状态分组信息列表模板
	 */
	function showProTkGStatusList($rows) {
		if ($rows) {
			$i = 0;
			$str = "";

			foreach ($rows as $key => $val) {
				$i++;
				//$status = $datadictDao->getDataNameByCode ( $val ['status'] );
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .=<<<EOT
						<tr id="tr_"  class="$classCss">
							<td align="center">$val[dataName]</td>
							<td align="center">$val[dataNum]</td>
						</tr>
EOT;
			}
		}
		return $str;
	}

	/*
	 *2.项目任务按任务类型分组信息列表模板
	 */
	function showProTkGTypeList($rows) {
		if ($rows) {
			$i = 0;
			$str = "";

			foreach ($rows as $key => $val) {
				$i++;
				//$status = $datadictDao->getDataNameByCode ( $val ['status'] );
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .=<<<EOT
						<tr id="tr_"  class="$classCss">
							<td align="center">$val[dataName]</td>
							<td align="center">$val[dataNum]</td>
							<td align="center">$val[dataPercent]%</td>
						</tr>
EOT;
			}
		}
		return $str;
	}
	/*
	 * 3.项目任务按任务偏差率分组信息列表模板
	 */
	function showProTkGVariaList($rows) {
		if ($rows) {
			$i = 0;
			$str = "";

			foreach ($rows as $key => $val) {
				$i++;
				//$status = $datadictDao->getDataNameByCode ( $val ['status'] );
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .=<<<EOT
						<tr id="tr_"  class="$classCss">
							<td align="center">$val[dataName]</td>
							<td align="center">$val[dataNum]</td>
							<td align="center">$val[dataPercent]%</td>
						</tr>
EOT;
			}
		}
		return $str;
	}
	/*
	 * 4.项目任务按任务完成质量分组信息列表模板
	 */
	function showProTkGQualityList($rows) {
		if ($rows) {
			$i = 0;
			$str = "";

			foreach ($rows as $key => $val) {
				$i++;
				//$status = $datadictDao->getDataNameByCode ( $val ['status'] );
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .=<<<EOT
						<tr id="tr_"  class="$classCss">
							<td align="center">$val[dataName]</td>
							<td align="center">$val[dataNum]</td>
							<td align="center">$val[dataPercent]</td>
						</tr>
EOT;
			}
		}
		return $str;
	}
	/*
	* 5.项目任务按任务责任主体分组信息列表模板
	*/
	function showProTkGChargeList($rows) {
		$str = "";
		if ($rows) {
			$i = 0;

			foreach ($rows as $key => $val) {
				$i++;
				//$status = $datadictDao->getDataNameByCode ( $val ['status'] );
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .=<<<EOT
						<tr id="tr_"  class="$classCss">
							<td align="center">$val[dataName]</td>
							<td align="center">$val[dataNum]</td>
							<td align="center">$val[dataPercent]%</td>
						</tr>
EOT;
			}
		} else {
			$str .= '<tr id="tr_"  class="$classCss">没有任务数据</tr>';

		}
		return $str;
	}
	/*
	 * 6.项目任务按任务成员任务进展分组信息列表模板
	 */
	function showProTkGActFinList($rows) {
		$str = "";
		if ($rows) {
			$i = 0;

			foreach ($rows as $key => $val) {
				$i++;
				//$status = $datadictDao->getDataNameByCode ( $val ['status'] );
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .=<<<EOT
						<tr id="tr_"  class="$classCss">
							<td align="center">$val[dataName]</td>
							<td align="center">$val[dataPercent]%</td>
						</tr>
EOT;
			}
		}
		return $str;
	}
	/*
	* 7.项目任务按任务成员任务偏差分组信息列表模板
	*/
	function showProTkGActVarList($rows) {
		$str = "";
		if ($rows) {
			$i = 0;
			foreach ($rows as $key => $val) {
				$i++;
				//$status = $datadictDao->getDataNameByCode ( $val ['status'] );
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .=<<<EOT
						<tr id="tr_"  class="$classCss">
							<td align="center">$val[dataName]</td>
							<td align="center">$val[dataPercent]%</td>
						</tr>
EOT;
			}
		}
		return $str;
	}

	/* -----------------------------------业务接口调用-------------------------------------------*/

	/**
	 * 重写获取方法
	 */
	//	function get_d($id){
	//		$condition = array ("id" => $id );
	////		print_r($condition);
	//		$rows = $this->find ( $condition );
	//		$datadict = new model_system_datadict_datadict();
	////		$rows['TypeOne'] = $datadict->getDataNameByCode($rows['TypeOne']);
	////		$rows['FirstOne'] = $datadict->getDataNameByCode($rows['FirstOne']);
	//		return $rows ;
	//	}
	/**
	 * @desription 项目基本信息获取方法
	 * @param tags
	 * @date 2010-9-28 下午02:36:05
	 */
	function TaskReadget_d($id) {
		$getm = new model_rdproject_task_tkadvanced();
		$gets = $getm->getTkAdByPTId($id);
		//echo($gets);
		$i = parent :: get_d($id);
		$i['projectTaskId'] = $gets;

		return $i;
	}
	/*
	 * 获取属于当前登录人的接收任务
	 */
	function getPRecievedTasks_d($searchArr) {
		$tkcondition = "";
		$tkcondition = $this->createQuery($tkcondition, $searchArr);
		$this->sort = "updateTime";
		$sql = "select c.id,c.name,c.projectId,c.projectCode,c.projectName,c.priority,c.status,c.effortRate,c.warpRate,c.chargeId,c.chargeName,"
		. "c.publishId,c.publishName,c.taskType,c.planBeginDate,c.planEndDate,c.actBeginDate,c.actEndDate,c.planDuration,c.realDuration,"
		. "c.appraiseWorkload,c.putWorkload,c.actWorkload,c.remark,c.endDayNum,c.planId,c.planCode,c.planName,"
		. "c.belongNode,c.belongNodeId,c.inspectInfo,c.isStone,c.markStoneName,c.stoneId,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,p.finishGrade"
		. " from oa_rd_task c left join (select db.taskId,db.finishGrade from ( select o.taskId,o.finishGrade from oa_rd_task_over o order by o.id desc) db group by db.taskId) p on c.id=p.taskId " . $tkcondition . " and c.status<>'WFB' " .
		"and c.id in(select distinct u.taskId from oa_rd_task_act_user u where u.actUserId='" . $_SESSION['USER_ID'] . "') ";

		//$this->asc = false;
		return $this->pageBySql($sql);
	}
	/*
	  * 获取当前登录人的分配任务(发布、创建的)
	  */
	function getPAllotTasks_d($searchArr) {
		$searchContion = "";
		$searchStr = "";
		if ($searchArr['name']) {
			$searchContion = " and c.name like '%" . $searchArr['name'] . "%'";
		}
		if ($searchArr['projectName']) {
			$searchContion = " and c.projectName like '%" . $searchArr['projectName'] . "%'";
		}
		if ($searchArr['priority'] && $searchArr['priority'] != null) {
			$searchStr .= " and c.priority='" . $searchArr['priority'] . "'";
		}
		if ($searchArr['status'] && $searchArr['status'] != null) {
			$searchStr .= " and c.status='" . $searchArr['status'] . "'";
		}
		$condition = " and (c.status!='WFB' and c.publishId='" . $_SESSION['USER_ID'] . "'" . $searchContion . ") or (c.createId='" . $_SESSION['USER_ID'] . "'" . $searchContion . ")";
		$this->sort = "updateTime desc,c.id";
		$sql = "select c.id,c.name,c.projectId,c.projectCode,c.projectName,c.priority,c.status,c.effortRate,c.warpRate,c.chargeId,c.chargeName," . "c.publishId,c.publishName,c.taskType,c.planBeginDate,c.planEndDate,c.actBeginDate,c.actEndDate,c.planDuration,c.realDuration," . "c.appraiseWorkload,c.putWorkload,c.actWorkload,c.remark,c.endDayNum,c.planId,c.planCode,c.planName," . "c.belongNode,c.belongNodeId,c.inspectInfo,c.isStone,c.markStoneName,c.stoneId,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,p.finishGrade" . " from oa_rd_task c left join (select db.taskId,db.finishGrade from ( select o.taskId,o.finishGrade from oa_rd_task_over o order by o.id desc) db group by db.taskId) p on c.id=p.taskId where 1=1 " . $searchStr . " " . $condition;

		return $this->pageBySql($sql);
	}
	/*
	   * 获取当前登录人的待审核任务
	   */
	/*
	   * 获取当前登录人的待审核任务
	   */
	function getPAuditTasks_d($searchArr) {
		$auditUserDao = new model_rdproject_task_tkaudituser();
		$auditUserSearch = array (
			"auditId" => $_SESSION['USER_ID']
		);
		$auditUserDao->searchArr = $auditUserSearch;
		$auditUserArr = $auditUserDao->listBySqlId("select_taskid");
		$idsArr = array ();
		$instatusArr = array (
			"DSH",
			"WTG",
			"TG"
		);
		$tkSearchArr = array ();
		if ($auditUserArr) {
			foreach ($auditUserArr as $key => $idsValue) {
				array_push($idsArr, $idsValue['taskId']);
			}
			$tkSearchArr = $searchArr;
			$tkSearchArr['ids'] = $idsArr;
			$tkSearchArr['instatus'] = $instatusArr;
			$this->searchArr = $tkSearchArr;
			$this->sort = "updateTime";
			//			$this->searchArr=array(
			//				"ids"=>$idsArr,
			//				"instatus"=>$instatusArr
			//			);
			//$sql="select c.* from ".$this->tbl_name." c where c.status='DSH' and c.id in(".$ids.")";
			return $this->pageBySqlId();
		} else
			return null;
	}

	/*
	   * 我的任务查看页面获取信息
	   *
	   * */
	function getTaskDetail_d($id) {
		//先找出任务的信息
		$taskdetail = $this->get_d($id);
		//优先级和状态的显示
		$datadictDao = new model_system_datadict_datadict();
		$tasktype = $datadictDao->getDataNameByCode($taskdetail['taskType']);
		$taskdetail['tasktype'] = $tasktype;
		$priority = $datadictDao->getDataNameByCode($taskdetail['priority']);
		$taskdetail['Priority'] = $priority;
		//找出审核人(有多个)
		$tkAuditUserDao = new model_rdproject_task_tkaudituser();
		$tkAuditUserDao->searchArr = array (
			"taskId" => $id
		);
		$tkAuditUsers = $tkAuditUserDao->listBySqlId();
		if (count($tkAuditUsers)) {
			$taskdetail['auditUserIds'] = $tkAuditUsers[0]['auditId'];
			$taskdetail['auditUserNames'] = $tkAuditUsers[0]['auditUser'];
		} else {
			$taskdetail['auditUserIds'] = "";
			$taskdetail['auditUserNames'] = "";
		}

		//同上找出参与人(有多个)
		$tkActUserDao = new model_rdproject_task_tkactuser();
		$tkActUserDao->searchArr = array (
			"taskId" => $id,
			"isActUser" => 0
		);
		$tkActUsers = $tkActUserDao->listBySqlId();
		$actUseIdArr = array ();
		$actUseNameArr = array ();
		if ($tkActUsers) {
			foreach ($tkActUsers as $key => $tkActUser) {
				array_push($actUseIdArr, $tkActUser['actUserId']);
				array_push($actUseNameArr, $tkActUser['userName']);
			}
			//$actUserIds=implode(",", $actUseIdArr).",";
			$actUserNames = implode(",", $actUseNameArr);

			//$taskdetail['auditUserIds']=$actUserIds;
			$taskdetail['actUserNames'] = $actUserNames;
		} else {
			$taskdetail['actUserIds'] = "";
			$taskdetail['actUserNames'] = "";
		}
		/*start:获取任务的变更次数*/
		$changeDao = new model_log_change_change();

		$taskdetail['changeNum'] = $changeDao->getChangeNum($this->tbl_name, $id);
		if (!$taskdetail['changeNum']) {
			$taskdetail['changeNum'] = 0;
		}
		/*end:获取任务的变更次数*/
		//最后返回一个完整的任务信息
		return $taskdetail;
	}

	/*
	 * 编辑任务信息时，获取任务相关信息
	 */
	function getEditTaskInfo_d($id) {

		//先找出任务的信息
		$taskdetail = $this->get_d($id);
		//优先级和状态的显示
		//		$datadictDao = new model_system_datadict_datadict ();
		//		$tasktype = $datadictDao->getDataNameByCode ( $taskdetail ['taskType'] );
		//		$taskdetail ['taskType'] = $tasktype;
		//
		//		$priority = $datadictDao->getDataNameByCode ( $taskdetail ['priority'] );
		//		$taskdetail ['priority'] = $priority;
		//找出审核人(有多个)
		$tkAuditUserDao = new model_rdproject_task_tkaudituser();
		$tkAuditUserDao->searchArr=array (
			"taskId" => $id
		);
		$tkAuditUsers = $tkAuditUserDao->listBySqlId();
		if (count($tkAuditUsers)) {
			$taskdetail['auditUserIds'] = $tkAuditUsers[0]['auditId'];
			$taskdetail['auditUserNames'] = $tkAuditUsers[0]['auditUser'];
		} else {
			$taskdetail['auditUserIds'] = "";
			$taskdetail['auditUserNames'] = "";
		}

		//同上找出参与人(有多个)
		$tkActUserDao = new model_rdproject_task_tkactuser();
		$tkActUserDao->searchArr = array (
			"taskId" => $id,
			"isActUser" => 0
		);
		$tkActUsers = $tkActUserDao->listBySqlId();
		//获取项目成员
		$rdmemberDao = new model_rdproject_team_rdmember();
		$rdmemberDao->searchArr=array(
			"projectId" => $taskdetail['projectId']
		);
		$rdmemberArr = $rdmemberDao->listBySqlId();
		$actUseIdArr = array ();
		$actUseNameArr = array ();
		$withinArr=array();
		$withoutArr=array();
		if ($tkActUsers) {
			foreach ($tkActUsers as $key => $tkActUser) {
				array_push($actUseIdArr, $tkActUser['actUserId']);
				array_push($actUseNameArr, $tkActUser['userName']);
			}

			foreach ( $rdmemberArr as $index=> $rdmember ){
				foreach ($tkActUsers as $key => $tkActUser) {
					if(  $rdmemberArr[$index]['memberId']==$tkActUsers[$key]['actUserId'] ){
						$withinArr[$index] = $tkActUsers[$key]['userName'];
						$withinIdArr[$index] = $tkActUsers[$key]['actUserId'];
					}
				}
			}
			//取得项目外任务参与人
			$withoutIdArr =  array_diff($actUseIdArr, $withinIdArr);
			$withoutArr =  array_diff($actUseNameArr, $withinArr);
			if( count($withinIdArr)>0 ){
				$withinStr = implode( ',',$withinArr ) . ",";
				$withinIdStr = implode( ',',$withinIdArr ) . ",";
			}else{
				$withinStr = "";
				$withinIdStr = "";
			}
			if( count($withoutIdArr)>0 ){
				$withoutStr = implode( ',',$withoutArr ) . ",";
				$withoutIdStr = implode( ',',$withoutIdArr ) . ",";
			}else{
				$withoutStr = "";
				$withoutIdStr = "";
			}
			$actUserIds = implode(",", $actUseIdArr) . ",";
			$actUserNames = implode(",", $actUseNameArr) . ",";

			$taskdetail['actUserIds'] = $actUserIds;
			$taskdetail['actUserNames'] = $actUserNames;
			$taskdetail['withinName'] = $withinStr;
			$taskdetail['withoutName'] = $withoutStr;
			$taskdetail['withinId'] = $withinIdStr;
			$taskdetail['withoutId'] = $withoutIdStr;
		} else {
			$taskdetail['withinName'] = "";
			$taskdetail['withoutName'] = "";
			$taskdetail['withinId'] = "";
			$taskdetail['withoutId'] = "";
			$taskdetail['actUserIds'] = "";
			$taskdetail['actUserNames'] = "";
		}
		/*start:-------前置任务信息-----------*/
		$tkFrontDao = new model_rdproject_task_tkfront();
		$tkFrontDao->searchArr = array (
			"taskId" => $id
		);
		$tkFronts = $tkFrontDao->listBySqlId();
		//print_r($tkAuditUsers);
		$frontTkNameArr = array ();
		$frontTkIdArr = array ();
		if ($tkAuditUsers) {
			foreach ($tkFronts as $key => $tkfront) {
				array_push($frontTkIdArr, $tkfront['frontTaskId']);
				array_push($frontTkNameArr, $tkfront['frontTaskName']);
			}

			$taskdetail['frontTkIds'] = implode(",", $frontTkIdArr);
			$taskdetail['frontTkNames'] = implode(",", $frontTkNameArr);
		} else {
			$taskdetail['frontTkIds'] = "";
			$taskdetail['frontTkNames'] = "";
		}
		/*end:-------前置任务信息-----------*/
		//		echo "<pre>";
		//		print_r($taskdetail);
		//最后返回一个完整的任务信息
		return $taskdetail;
	}
	/*
	 * 新增/发布 项目任务 新增任务相关信息
	 */
	function addTask_d($objectinfo) {
		try {
			$this->start_d();
			if ($objectinfo['planId'] == "")
				unset ($objectinfo['planId']);
			if ($objectinfo['projectId'] == "")
				unset ($objectinfo['projectId']);
			if ($objectinfo['belongNodeId'] == "") {
				$objectinfo['belongNodeId'] = -1;
				$objectinfo['belongNode'] = "";
			}
			//里程碑处理
			if (empty ($objectinfo['stoneId'])) {
				$objectinfo['isStone'] = 1;
				unset ($objectinfo['markStoneName']);
				unset ($objectinfo['stoneId']);
			}

			$id = parent :: add_d($objectinfo, true);
			//更新附件关联关系
			$this->updateObjWithFile($id);
			//参与人
			$tkActUserDao = new model_rdproject_task_tkactuser();
			//审核人
			$tkauditUserDao = new model_rdproject_task_tkaudituser();
			//前置任务
			$tkFrontDao = new model_rdproject_task_tkfront();

			$actUserIdArr = explode(",", $objectinfo['tkactuser']['actUserId']);
			$actUserArr = explode(",", $objectinfo['tkactuser']['userName']);
			$frontTkNameArr = explode(",", $objectinfo['tkfront']['frontTaskName']);
			$frontTkIdArr = explode(",", $objectinfo['tkfront']['frontTaskId']);

			$tkactuser = array ( "taskId" => $id ); //参与人
			$tkaudituser = array ( "taskId" => $id ); //审核人
			$tkfront = array ( "taskId" => $id ); //前置任务

			/*start:-----添加任务审核人信息---------*/
			if($objectinfo['tkaudituser']['auditId']){
				$tkaudituser = $objectinfo['tkaudituser'];
				$tkaudituser["taskId"] = $id;
				$tkauditUserDao->add_d($tkaudituser);
			}
			/*end:--------添加任务审核人信息---------*/

			/*start:-----添加任务参与人---------*/
			for ($i = 0; $i < count($actUserIdArr) - 1; $i++) {
				$tkactuser['actUserId'] = $actUserIdArr[$i];
				$tkactuser['userName'] = $actUserArr[$i];
				$tkactuser['isActUser'] = 0;
				$tkActUserDao->add_d($tkactuser);
			}

			if ($objectinfo['chargeId']) {
				$tkactuser['actUserId'] = $objectinfo['chargeId'];
				$tkactuser['userName'] = $objectinfo['chargeName'];
				$tkactuser['isActUser'] = 1;
				$tkActUserDao->add_d($tkactuser);
			}
			/*end:--添加任务参与人-*/

			/*start:---------添加任务的前置任务--------*/
			for ($k = 0; $k < count($frontTkNameArr); $k++) {
				$tkfront['frontTaskName'] = $frontTkNameArr[$k];
				$tkfront['frontTaskId'] = $frontTkIdArr[$k];
				$tkFrontDao->add_d($tkfront);
			}
			/*end:---------添加任务的前置任务--------*/

			/*start:----------添加计划任务后，更新计划的工作量----------*/
			$workPlanDao = new model_rdproject_plan_rdplan();
			if ($objectinfo['planId']) {
				$workPlanDao->workloadCount($objectinfo['planId'], $objectinfo['appraiseWorkload']);
				/*start:---------更新计划完成率-------------------*/
				if (isset ($objectinfo['publishId'])) {
					$workPlanDao->taskRecEff($objectinfo['planId']);
				}
				/*end:------------更新计划完成率-----------------*/

			}

			/*end:----------添加计划任务后，更新计划的工作量----------*/

			//里程碑开始
			if (!empty ($objectinfo['stoneId'])) {
				$stoneDate = $this->getMinAndMaxDate_d($objectinfo['stoneId']);
				$stoneDao = new model_rdproject_milestone_rdmilespoint();
				$stoneDao->updateMileInfo_d($objectinfo['stoneId'], $objectinfo, $stoneDate);
			}
			//里程碑结束

			$this->commit_d();
			//$this->rollBack();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}

	}
	/*
	 * 修改任务时，保存项目任务相关信息
	*/
	function editTask_d($objectinfo) {
		try {
			$this->start_d();
			//			echo "<pre>";
			//			print_r($objectinfo['tkfront']);
			if ($objectinfo['planId'] == "")
				unset ($objectinfo['planId']);
			if ($objectinfo['projectId'] == "")
				unset ($objectinfo['projectId']);
			if ($objectinfo['belongNodeId'] == "") {
				$objectinfo['belongNodeId'] = -1;
				$objectinfo['belongNode'] = "";
			}

			//里程碑处理
			if (empty ($objectinfo['stoneId'])) {
				$objectinfo['isStone'] = 1;
				unset ($objectinfo['markStoneName']);
				unset ($objectinfo['stoneId']);
			}

			$id = parent :: edit_d($objectinfo, true);
			//参与人
			$tkActUserDao = new model_rdproject_task_tkactuser();
			//审核人
			$tkauditUserDao = new model_rdproject_task_tkaudituser();
			//前置任务
			$tkFrontDao = new model_rdproject_task_tkfront();
			$auditUserIdArr = explode(",", $objectinfo['tkaudituser']['auditId']);
			$auditUserArr = explode(",", $objectinfo['tkaudituser']['auditUser']);
			$actUserIdArr = explode(",", $objectinfo['tkactuser']['actUserId']);
			$actUserArr = explode(",", $objectinfo['tkactuser']['userName']);
			$frontTkNameArr = explode(",", $objectinfo['tkfront']['frontTaskName']);
			$frontTkIdArr = explode(",", $objectinfo['tkfront']['frontTaskId']);

			$tkactuser = array ( "taskId" => $objectinfo['id'] ); //参与人
			$tkaudituser = array ( "taskId" => $objectinfo['id'] ); //审核人
			$tkfront = array ( "taskId" => $objectinfo['id'] ); //前置任务

			//删除任务审核人,参与人,前置任务信息信息 重新保存
			$tkauditUserDao->delete($tkactuser);
			$tkActUserDao->delete($tkaudituser);
			$tkFrontDao->delete($tkfront);
			/*start:-----添加任务审核人信息---------*/
			if($objectinfo['tkaudituser']['auditId']){
				$tkaudituser = $objectinfo['tkaudituser'];
				$tkaudituser['taskId'] = $objectinfo['id'];
				$tkauditUserDao->add_d($tkaudituser);
			}
			/*end:--------添加任务审核人信息---------*/

			/*start:-----添加任务参与人---------*/
			for ($i = 0; $i < count($actUserIdArr) - 1; $i++) {
				$tkactuser['actUserId'] = $actUserIdArr[$i];
				$tkactuser['userName'] = $actUserArr[$i];
				$tkactuser['isActUser'] = 0;
				$tkActUserDao->add_d($tkactuser);
			}
			if ($objectinfo['chargeId']) {
				$tkactuser['actUserId'] = $objectinfo['chargeId'];
				$tkactuser['userName'] = $objectinfo['chargeName'];
				$tkactuser['isActUser'] = 1;
				$tkActUserDao->add_d($tkactuser);
			}
			/*end:-----------添加任务参与人----------*/

			/*start:---------添加任务的前置任务--------*/
			for ($k = 0; $k < count($frontTkNameArr); $k++) {
				$tkfront['frontTaskName'] = $frontTkNameArr[$k];
				$tkfront['frontTaskId'] = $frontTkIdArr[$k];
				$tkFrontDao->add_d($tkfront);
			}

			/*end:---------添加任务的前置任务--------*/

			//里程碑开始
			if (!empty ($objectinfo['stoneId'])) {
				$stoneDate = $this->getMinAndMaxDate_d($objectinfo['stoneId']);
				$stoneDao = new model_rdproject_milestone_rdmilespoint();
				$stoneDao->updateMileInfo_d($objectinfo['stoneId'], $objectinfo, $stoneDate);
			}
			//里程碑结束

			$this->commit_d();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}
	/*
	 * 批量编辑任务
	 * @param tags
	 * @date 2010-9-26 上午10:46:27
	 */
	function editBatchEditor_d($objectinfo) {
		$rdtaskArr = array (
			"id" => "$id"
		);
		foreach ($rdtaskArr as $key => $value) {
			return parent :: edit_d($objectinfo, true);
		}
	}

	/*
	 * 获取实际结束时间任务在 规定时间内
	 */
	function getTkBetweenDate($beginDay, $endDay, $userId) {
		$this->searchArr = array (
			"u_actUserId" => $userId,
			"actEndDateD" => $beginDay,
			"actEndDateX" => $endDay
		);

		return parent :: listBySqlId("select_tk_actuser");
	}

	/*
	 * 根据ID串查找任务
	 */
	function findTaskByIds($ids) {
		$this->searchArr['taskIds'] = $ids;
		return $this->listBySqlId('schedule_list');
	}
	/*
	 * 发布任务,把任务状态改为WQD
	 */
	function publishTask_d($id, $userId, $userName) {
		//$sql;
		$object = $this->get_d($id);
		if (!isset ($object['appraiseWorkload'])) {
			return "任务信息不全，请编辑后再发布！";
		}
		$userDao = new model_rdproject_task_tkactuser();
		$actUserIdStr = $userDao->findUserByTaskId_d($id);
		$this->mail_d($object, $actUserIdStr,$id);
		$publishTask['id'] = $id;
		$publishTask['publishId'] = $userId;
		$publishTask['publishName'] = $userName;
		$publishTask['status'] = 'WQD';
		if (parent :: updateById($publishTask)) {
			$tkinfo = $this->get_d($id);
			if (isset ($tkinfo['planId'])) { //更新计划完成率
				$workPlanDao = new model_rdproject_plan_rdplan();
				$workPlanDao->taskRecEff($tkinfo['planId']);
			}
			return "任务发布成功!";
		} else
			return "任务发布失败!";
	}

	/*
	 * 获取查询工作进展的结果
	 */
	function getWorkScheduleRs($object) {

		//人员ID
		if (isset ($object['personIds'])) {
			$object['personIds'] = substr($object['personIds'], 0, -1);
			$this->searchArr['personIds'] = $object['personIds'];
		}
		//部门ID
		if (isset ($object['departmentIds'])) {
			$object['departmentIds'] = substr($object['departmentIds'], 0, -1);
			$this->searchArr['depId'] = $object['departmentIds'];
		}
		//项目ID
		if (isset ($object['w_projectId'])) {
			$this->searchArr['projectId'] = $object['w_projectId'];
		}

		//项目ID 多个
		if (isset ($object['projectIds'])) {
			$this->searchArr['projectIds'] = $object['projectIds'];
		}

		//项目成员 多个
		if (isset ($object['memberIds'])) {
			$this->searchArr['personIds'] = $object['memberIds'];
		}

		$this->searchArr['updateTimeD'] = $object['beginDate'];
		$this->searchArr['updateTimeX'] = $object['overDate'];
		$this->groupBy = " c.id,a.actUserId ";
		$rows = $this->pageBySqlId('work_schedule');
		//		print_r($rows);
		return $rows;
	}

	/*
	 * 动态渲染表头
	 */
	function changeHead($rows) {
		$week = array (
			'日',
			'一',
			'二',
			'三',
			'四',
			'五',
			'六'
		);
		$str =<<<EOT
			<tr class="main_tr_header">
				<th colspan="6">
				</th>
				{thfirst}
			</tr>
			<tr class="main_tr_header">
				<th width="100px">
					执行人
				</th>
				<th width="50px">
				</th>
				<th width="400px">
					任务名称
				</th>
				<th width="400px">
					所属项目
				</th>
				<th width="100px">
					状态
				</th>
				<th width="100px">
					完成率
				</th>
				{thsecond}
			</tr>
EOT;
		$beginStamp = $this->getTheDate($rows['beginDate']);
		$overStamp = $this->getTheDate($rows['overDate'], '0');
		$j = 0; //记录星期几
		for ($i = $beginStamp; $i <= $overStamp; $i += 86400) {
			$j++;
			$str = str_replace('{thsecond}', '<th width="50px">' . $week[date('w', $i)] . '</th>{thsecond}', $str);
			if ($j == '7') {
				$str = str_replace('{thfirst}', '<th colspan="7">' . date('Y-m-d', $i - (6 * 86400)) . "~" . date('Y-m-d', $i) . '</th>{thfirst}', $str);
				$j = 0;
			}
		}
		$str = str_replace('{thfirst}', '', $str);
		$str = str_replace('{thsecond}', '', $str);
		return $str;
	}

	/**
	 * 定位第一个周周一和最后一个周周末
	 */
	function getTheDate($time, $type = '1') {
		$stamp = strtotime($time);
		$weekday = date('w', $stamp);
		switch ($weekday) {
			case '0' :
				$tomoney = $stamp -6 * 86400;
				$tosunday = $stamp;
				break;
			case '1' :
				$tomoney = $stamp;
				$tosunday = $stamp +6 * 86400;
				break;
			case '2' :
				$tomoney = $stamp -1 * 86400;
				$tosunday = $stamp +5 * 86400;
				break;
			case '3' :
				$tomoney = $stamp -2 * 86400;
				$tosunday = $stamp +4 * 86400;
				break;
			case '4' :
				$tomoney = $stamp -3 * 86400;
				$tosunday = $stamp +3 * 86400;
				break;
			case '5' :
				$tomoney = $stamp -4 * 86400;
				$tosunday = $stamp +2 * 86400;
				break;
			case '6' :
				$tomoney = $stamp -5 * 86400;
				$tosunday = $stamp +1 * 86400;
				break;
		}
		if ($type) {
			return $tomoney;
		} else {
			return $tosunday;
		}
	}

	/*
	 * 数组转化函数 时间=>工作量
	 */
	function changeRows($rows) {
		if ($rows) {
			$rs = array ();
			foreach ($rows as $val) {
				if (isset ($rs[$val['executionDate']])) {
					$rs[$val['executionDate']] = bcadd($rs[$val['executionDate']],$val['workloadDay'],2);
				} else {
					$rs[$val['executionDate']] = $val['workloadDay'];
				}
			}
			return $rs;
		}
	}

	/*
	 * 根据任务id 更新任务状态
	 * @param $nStatus:为任务现在状态;$cStatus:为要改变的状态
	 */
	function updateTaskStatus_d($id, $nStatus, $cStatus, $lastTkInfo = null, $tksubmitinfo = null, $tkoverinfo = null) {
		$tkoverDao = new model_rdproject_task_tkover();
		$taskinfo = array (
			'id' => $id,
			'updateTime' => date("Y-m-d H:i:s"
		), 'ExaDT' => date("Y-m-d H:i:s"));
		$taskinfo['ExaDT'] = date("Y-m-d H:i:s");
		if ($nStatus == "WQD" && $cStatus = "JXZ") {
			$taskinfo['status'] = $cStatus;
			$taskinfo['actBeginDate'] = date("Y-m-d H:i:s");
		} else
			if (($nStatus == "DSH" && $cStatus == "TG")) {
				$taskinfo['actEndDate'] = $tksubmitinfo['subDate']; //通过时并更新任务的实际完成时间
				$taskinfo['realDuration'] = $tksubmitinfo['subDate'] - $lastTkInfo['actBeginDate'] + 1;
				$taskinfo['actWorkload'] = $tkoverinfo['informTime'];
				$taskinfo['status'] = $cStatus;
				$taskinfo['effortRate'] = 100;
				/*s:偏差率计算*/
				$finPlus = (strtotime($tksubmitinfo['subDate']) - strtotime($lastTkInfo['planEndDate'])) / 86400;
				$planPlus = (strtotime($lastTkInfo['planEndDate']) - strtotime($lastTkInfo['planBeginDate'])) / 86400;
				$tkWarpRate = round(($finPlus) / ($planPlus +1) * 100, 2);
				if ($tkWarpRate > 0)
					$taskinfo['warpRate'] = $tkWarpRate;
				else
					$taskinfo['warpRate'] = 0;
				/*e:偏差率计算*/
			} else
				if (($nStatus == "JXZ" && $cStatus == "DSH")) {
					$taskinfo['status'] = $cStatus;
					//$taskinfo['actEndDate']=date ( "Y-m-d H:i:s" );
				} else {
					$taskinfo['status'] = $cStatus;
				}
		return $this->updateById($taskinfo);
	}

	/*
	 * 根据计划id，判断该计划有没节点或者任务信息
	 */
	function isCleanPlan_d($planId) {
		$searchArr = array (
			"planId" => $planId
		);
		$condition = " planId=" . $planId;
		$tkNodeDao = new model_rdproject_task_tknode();

		if ($this->get_table_fields($this->tbl_name, $condition, "id")) {
			return true;

		} else
			if ($tkNodeDao->get_table_fields($tkNodeDao->tbl_name, $condition, "id")) {
				return true;
			} else {
				return false;
			}

	}

	/**
	 * 根据计划ID，获取计划下的任务数量
	 */
	function getCountByPlanId($planId) {
		$this->searchArr['planId'] = $planId;
		$rows = $this->listBySqlId('getCountByPlanId');
		return $rows[0]['tasknum'];
	}

	/*
	 *根据项目ID获取任务信息
	 */
	function getTkInfoByPId_d($searchArr) {
		$this->searchArr = $searchArr;
		$tdtaskinfo = $this->pageBySqlId();
		return $tdtaskinfo;

	}

	/*
	 * 获取项目进展视图任务信息
	 */
	function getTkProcessPro_d($searchArr) {
		$beginStamp = $this->getTheDate($searchArr['beginDate']);
		$endStamp = $this->getTheDate($searchArr['endDate'], "0");
		$this->searchArr = $searchArr;
		$tdtaskinfos = $this->pageBySqlId();
		$tkids = "";
		$workLogRow = array ();

		for ($i = 0; $i < count($tdtaskinfos); $i++) {
			$tkids .= $tdtaskinfos[$i]['id'];
			if ($i != (count($tdtaskinfos) - 1)) {
				$tkids .= ",";
			}
		}

		$worklogSql = "select  c.taskId,sum(c.workloadDay) as dayWork,c.executionDate from oa_rd_worklog c group by c.taskId,c.createTime having taskId in (" . $tkids . ") and c.executionDate>= '" . date('Y-m-d', $beginStamp) . "' and c.executionDate<='" . date('Y-m-d', $endStamp) . "'  order by c.executionDate asc;";
		if ($tkids != "") {
			$workLogRow = $this->findSql($worklogSql);

			for ($i = 0; $i < count($tdtaskinfos); $i++) {
				$begindate = $beginStamp;
				$enddate = $endStamp;

				for ($begindate; $begindate <= $enddate; $begindate = $begindate +24 * 60 * 60) {
					$tdtaskinfos[$i]['dayWork'][$begindate] = 0;
					if ($workLogRow) {
						foreach ($workLogRow as $worklog) {
							if ($worklog['taskId'] == $tdtaskinfos[$i]['id']) {
								if (isset ($worklog['executionDate'])) {
									$exeDate = strtotime($worklog['executionDate']);
									if ($exeDate == $begindate) {
										$tdtaskinfos[$i]['dayWork'][$begindate] = $worklog['dayWork'];
										break;

									}
								}

							}
						}
					}
				}

			}
		}

		return $tdtaskinfos;
	}
	/*
	 * 获取任务偏差视图任务信息
	 */
	function getTkDeviationPro_d($searchArr) {
		$this->searchArr = $searchArr;
		return $this->listBySqlId();
	}

	/*
	 * 根据月份查找任务时间在计划开始时间和计划结束时间之间的个人任务
	 */
	function getPerMonthTk_d($goadDate) {
		$this->searchArr = array (
			"u_actUserId" => $_SESSION['USER_ID']
		);
		function createCalBTk($tkinfo) {
			$tkinfon['EventID'] = $tkinfo['id'];
			$tkinfon['Date'] = $tkinfo['planBeginDate'];
			//$tkinfon['EndDateTime']=$tkinfo['planEndDate'];
			$tkinfon['URL'] = "?model=rdproject_task_rdtask&action=toReadTask&id=" . $tkinfo['id'];
			$tkinfon['Title'] = "●" . $tkinfo['name'];
			$tkinfon['Description'] = $tkinfo['name'];
			$tkinfon['CssClass'] = "sTask";
			return $tkinfon;
		}
		$tkinfosBegin = $this->listBySqlId("select_myreceived");

		function createCalETk($tkinfo) {
			$tkinfon['EventID'] = $tkinfo['id'];
			$tkinfon['Date'] = $tkinfo['planEndDate'];
			$tkinfon['URL'] = "?model=rdproject_task_rdtask&action=toReadTask&id=" . $tkinfo['id'];
			$tkinfon['Title'] = $tkinfo['name'] . "●";
			$tkinfon['Description'] = $tkinfo['name'];
			$tkinfon['CssClass'] = "eTask";
			return $tkinfon;
		}

		$this->searchArr = array (
			"u_actUserId" => $_SESSION['USER_ID']
		);
		$tkinfosEnd = $this->listBySqlId("select_myreceived");
		if(count($tkinfosBegin)>0&&isset($tkinfosBegin[0]['id'])){
			return model_common_util :: yx_array_merge(array_map("createCalBTk", $tkinfosBegin), array_map("createCalETk", $tkinfosEnd));
		}else{
			return $tkinfosBegin;
		}

	}

	/*
	 * 判断当前登录人是否是所选任务的负责人
	 */
	function isChargeUser_d($taskid) {
		$sql = "select c.id from " . $this->tbl_name . " c where c.id=" . $taskid . " and c.chargeId='" . $_SESSION['USER_ID'] . "'";
		if ($this->findSql($sql))
			return 0;
		else
			return 1;
	}
	/*
	 * 判断任务是否存在责任人
	 */
	function exsitChargeUser_d($id) {
		$rdtaskinfo = $this->get_d($id);
		if ($rdtaskinfo['chargeId'] != "")
			return 0;
		else {
			return 1;
		}
	}

	/**
	 * 只插入数据，进行业务操作
	 */
	function addSimple($object) {
		$object = $this->addCreateInfo($object);
		$newId = $this->create($object);
		return $newId;
	}

	/**
	 * 甘特图
	 */
	function showGantt($planId) {
		require_once WEB_TOR . 'module/gantt/BURAK_Gantt.class.php';

		$rows = $this->findAll(array (
			'planId' => $planId
		), 'id', 'id,name,effortRate,planBeginDate,planEndDate,planId,planName');
		//
		$planDao = new model_rdproject_plan_rdplan();
		$planRows = $planDao->find(array (
			'id' => $planId
		), 'id', 'id,planName,planBeginDate,planEndDate');
		//		print_r($planRows);
		$tkFontDao = new model_rdproject_task_tkfront();
		$taskIds = "";

		$g = new BURAK_Gantt();
		// set grid type
		$g->setGrid(1);
		// set Gantt colors
		$g->setColor("group", "000000");
		$g->setColor("progress", "660000");

		$g->addGroup('Group' . $planRows['id'], $planRows['planBeginDate'], $planRows['planEndDate'], $planRows['planName']);

		if (!empty ($rows)) {
			foreach ($rows as $val) {
				if (!empty ($val['planBeginDate']) && !empty ($val['planEndDate'])) {
					$taskIds .= $val['id'] . ',';
					$g->addTask($val['id'], $val['planBeginDate'], $val['planEndDate'], $val['effortRate'], $val['name'], 'Group' . $planRows['id']);
				}
			}

			$fontRows = $tkFontDao->getFontByTaskIds_d($taskIds);
			if (!empty ($fontRows)) {
				//				print_r($fontRows);
				foreach ($fontRows as $val) {
					$g->addRelation($val['frontTaskId'], $val['taskId'], "ES");
				}
			}

		}
		$g->outputGantt();
	}

	/**
	 * 根据任务ID修改任务的已投入工作量
	 */
	function updatePutload($id, $newLoad, $oldLoad = 0, $type = 'add') {
		if ($type = 'add') {
			$sql = "update oa_rd_task set putWorkload = putWorkload + '$newLoad' ,actWorkload = actWorkload + '$newLoad'  where id = '$id'";
		}
		elseif ($type = 'del') {
			$sql = "update oa_rd_task set putWorkload = putWorkload - '$newLoad' ,actWorkload = actWorkload + '$newLoad'  where id = '$id'";
		}
		elseif ($type = 'edit') {
			if ($newLoad > $oldLoad) {
				$addLoad = $newLoad - $oldLoad;
				$sql = "update oa_rd_task set putWorkload = putWorkload + '$addLoad' ,actWorkload = actWorkload + '$newLoad'  where id = '$id'";
			}
			elseif ($newLoad < $oldLoad) {
				$addLoad = $oldLoad - $newLoad;
				$sql = "update oa_rd_task set putWorkload = putWorkload - '$newLoad'  ,actWorkload = actWorkload + '$newLoad' where id = '$id'";
			}
			elseif ($newLoad = $oldLoad) {
				return;
			}
		}
		try {
			$this->query($sql);
		} catch (exception $e) {
			throw $e;
		}
	}

	/*Start:------------------------------------------按项目统计任务信息-----------------------------------*/
	/************************************组合统计 By LiuB 2011年7月26日15:01:14**************************************************************/
	/**
	 * 根据组合ID 获取组合下的项目Id
	 */
	function getGroup_d($gpId) {
		$sql = "select id  from oa_rd_project_view_group  where lft > (select  lft from oa_rd_group where id = $gpId )  AND  rgt<(select  rgt from oa_rd_group where id = $gpId)  AND type = 1";
		$arr = $this->findSql($sql);

		return $arr;
	}
	/************************************************************************************************************************************/
	/*
	 *1. 根据项目id 按状态分组统计任务个数
	 */
	function getGroupTkByStatus_d($projectId) {
		$sql = "select d.dataName,d.dataCode, count(t.id) as dataNum  from  oa_system_datadict d left join oa_rd_task t on(t.status=d.dataCode)    and t.projectId in ($projectId) where  d.parentCode='XMRWZT' group by d.dataCode";
		$arr = $this->findSql($sql);

		return $arr;
	}
	/*
	 * 2.根据项目id 按任务类型分组统计任务个数
	 */
	function getGroupTkByType_d($projectId) {
		$sql = "select d.dataName,d.dataCode, count(t.id) as dataNum  from  oa_system_datadict d left join oa_rd_task t on(t.taskType=d.dataCode) and t.projectId in ($projectId) where  d.parentCode='XMRWLX' group by d.dataCode";
		$tkAllNum = 0;
		$tkinfoArr = $this->findSql($sql);
		foreach ($tkinfoArr as $key => $tkinfo) {
			$tkAllNum += $tkinfo['dataNum'];
		}
		if ($tkAllNum != 0) {
			for ($i = 0; $i < count($tkinfoArr); $i++) {
				$tkinfoArr[$i]['dataPercent'] = round($tkinfoArr[$i]['dataNum'] * 100 / $tkAllNum);

			}
		} else {
			for ($i = 0; $i < count($tkinfoArr); $i++) {
				$tkinfoArr[$i]['dataPercent'] = 0;
			}
		}
		return $tkinfoArr;
	}

	/*
	 * 3.根据项目id 按任务偏差率统计任务个数
	 */
	function getGroupTkByVaria_d($projectId) {
		$rsql = "select count(t.id) as rdataNum  from  oa_rd_task t where t.warpRate=0 and t.projectId in ($projectId) ";
		$rTkinfo = $this->findSql($rsql);

		$gsql = "select count(t.id) as gdataNum  from  oa_rd_task t where t.warpRate<>0 and t.projectId in ($projectId)";
		$gTkinfo = $this->findSql($gsql);

		if (count($rTkinfo) > 0)
			$object[0]['dataNum'] = $rTkinfo[0]['rdataNum'];
		else
			$object[0]['dataNum'] = 0;
		$object[0]['dataName'] = "绿色";

		if (count($gTkinfo) > 0)
			$object[1]['dataNum'] = $gTkinfo[0]['gdataNum'];
		else
			$object[1]['dataNum'] = 0;
		$object[1]['dataName'] = "红色";

		if ($object[0]['dataNum'] + $object[1]['dataNum'] != 0) {
			$object[0]['dataPercent'] = round($object[0]['dataNum'] * 100 / ($object[0]['dataNum'] + $object[1]['dataNum']), 2);
			$object[1]['dataPercent'] = round($object[1]['dataNum'] * 100 / ($object[0]['dataNum'] + $object[1]['dataNum']), 2);
		} else {
			$object[0]['dataPercent'] = 0;
			$object[1]['dataPercent'] = 0;

		}
		return $object;
	}

	/*
	 * 4.根据项目id 按任务完成质量统计任务个数
	 */
	function getGroupTkByQuality_d($projectId) {
		$sql = "select d.dataName,d.dataCode,count(t.id) as dataNum  from  oa_system_datadict d left join oa_rd_task_over o on(o.finishGrade=d.dataCode) left join oa_rd_task t on(t.id=o.taskId) and t.projectId in ($projectId) where  d.parentCode='XMRWZPDJ' group by d.dataCode";
		$tkinfoArr = $this->findSql($sql);
		$tkAllNum = 0;
		foreach ($tkinfoArr as $key => $tkinfo) {
			$tkAllNum += $tkinfo['dataNum'];
		}
		if ($tkAllNum != 0) {
			for ($i = 0; $i < count($tkinfoArr); $i++) {
				$tkinfoArr[$i]['dataPercent'] = round($tkinfoArr[$i]['dataNum'] * 100 / $tkAllNum, 2);
			}
		} else {
			for ($i = 0; $i < count($tkinfoArr); $i++) {
				$tkinfoArr[$i]['dataPercent'] = 0;
			}
		}

		return $tkinfoArr;
	}

	/*
	 * 5.根据项目id 按任务责任主体统计任务个数
	 */
	function getGroupTkByCharge_d($projectId) {
		$sql = "select t.chargeName as dataName,count(t.id) as dataNum  from oa_rd_task t where t.projectId in ($projectId) group by t.chargeName";
		$tkinfoArr = $this->findSql($sql);

		$tkAllNum = 0;
		if ($tkinfoArr) {
			foreach ($tkinfoArr as $key => $tkinfo) {
				$tkAllNum += $tkinfo['dataNum'];
			}
			if ($tkAllNum != 0) {
				for ($i = 0; $i < count($tkinfoArr); $i++) {
					$tkinfoArr[$i]['dataPercent'] = round($tkinfoArr[$i]['dataNum'] * 100 / $tkAllNum, 2);
				}
			} else {
				for ($i = 0; $i < count($tkinfoArr); $i++) {
					$tkinfoArr[$i]['dataPercent'] = 0;
				}
			}
		}
		return $tkinfoArr;
	}
	/*
	 * 6.根据项目id 按任务任务进展统计任务个数
	 */
	function getGroupTkByActFin_d($projectId) {
		$sql = "select u.userName as dataName,round(sum(c.effortRate)/count(distinct c.id),2) as dataPercent from oa_rd_task_act_user u left join oa_rd_task c on  c.id = u.taskId  where c.projectId in ($projectId) group by u.actUserId";
		return $this->findSql($sql);
	}
	/*
	 * 7.根据项目id 按任务任务偏差率统计任务个数
	 */
	function getGroupTkByActVar_d($projectId) {
		$sql = "select u.userName as dataName,u.actUserId as dataCode,round(sum(t.warpRate) /count(t.id) ,2) as dataPercent  from  oa_rd_task_act_user u left join oa_rd_task t on(t.id=u.taskId) where t.projectId in ($projectId)  group by u.userName;";
		return $this->findSql($sql);
	}

	/*End:------------------------------------------按项目统计任务信息-----------------------------------*/

	/**
	 * 根据计划ID获取计划下任务的个数和任务的平均完成率和平均偏差率
	 */
	function getGroupTKByPlanId($planId) {
		$sql = "select round(sum(c.effortRate)/count(c.id),2) as effortRate,round(sum(c.warpRate)/count(c.id),2) as warpRate,count(c.id) as taskNum from oa_rd_task c where c.planId =" . $planId;
		return $this->findSql($sql);
	}

	/**
	 * 根据计划ID得出任务的总完成率和偏差率和个数
	 */
	function getInfoByPlanId($planId) {
		$sql = "select sum(c.effortRate) as effortRate,sum(c.warpRate) as warpRate,count(c.id) as taskNum from oa_rd_task c where c.planId =" . $planId;
		return $this->findSql($sql);
	}

	function showPlanAllView($rows, $startDate, $endDate) {
		//        echo "<pre>";
		//        print_r($rows);
		$week = array (
			'日',
			'一',
			'二',
			'三',
			'四',
			'五',
			'六'
		);
		$str =<<<EOT
			<tr class="main_tr_header">
				<th colspan="1">
				</th>
				{thfirst}
			</tr>
			<tr class="main_tr_header">
				<th width="150px">
					姓名
				</th>
				{thsecond}
			</tr>
EOT;
		$startStamp = $this->getTheDate($startDate);
		$overStamp = $this->getTheDate($endDate, '0');

		$j = 0; //记录星期几
		$k = 0; //记录有几列
		for ($i = $startStamp; $i <= $overStamp; $i += 86400) {
			$j++;
			$k++;
			$str = str_replace('{thsecond}', '<th width="50px">' . $week[date('w', $i)] . '</th>{thsecond}', $str);
			if ($j == '7') {
				$str = str_replace('{thfirst}', '<th colspan="7"><b>' . date('Y-m-d', $i - (6 * 86400)) . "</b>__<b>" . date('Y-m-d', $i) . '</b></th>{thfirst}', $str);
				$j = 0;
			}
		}
		$str = str_replace('{thfirst}', '', $str);
		$str = str_replace('{thsecond}', '', $str);
		/*e:列表头*/

		/*s:列表body*/
		if ($rows) {
			$i = 1;
			foreach ($rows as $key => $val) {
				$nBStamp = $startStamp;
				$nEStamp = $overStamp;
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .=<<<EOT
							<tr id="tr_$val[id]" pid="$val[id]" class="$classCss">
							<td height="25" align="center">$val[chargName]</td>
EOT;

				for ($i = $startStamp; $i <= $overStamp; $i += 86400) {
					$v = ($val[$startStamp] == null ? 0 : $val[$startStamp]);
					$str .=<<<EOT
							<td align="center">
							$v
							</td>
EOT;

				}
				$str .=<<<EOT
						</tr>
EOT;
				$i++;
			}
		} else
			$str .= '<tr><td colspan="50">暂无相关信息</td></tr>';

		/*e:列表body*/

		return $str;

	}

	function getNameAll($searchArr) {
		//		echo "<pre>";
		//		print_r($searchArr);
		$startDate = $searchArr['startDate'];
		$endDate = $searchArr['endDate'];
		$planBeginDate = date('Y-m-d', strtotime($startDate));
		$planEndDate = date('Y-m-d', strtotime($endDate));
		$chargeName = $searchArr['chargeName'];
		//		echo $planBeginDate."--------".$planEndDate."-------".$searchArr ['chargeName'];
		$nameSql = "select c.chargeId,c.chargeName from oa_rd_task c  where ((" .
		"c.planBeginDate>= '" . $planBeginDate . "' and  c.planBeginDate<='" . $planEndDate .
		"') or (c.planEndDate>= '" . $planBeginDate . "' and  c.planEndDate<='" . $planEndDate . "'))  and c.chargeName like'%" . $chargeName . "%' group by c.chargeId ,c.chargeName";
		$taskRow = $this->pageBySql($nameSql);
		return $taskRow;
	}

	function getPlanAll($searchArr) {
		$startDate = $searchArr['startDate'];
		$endDate = $searchArr['endDate'];
		$planBeginDate = date('Y-m-d', strtotime($startDate));
		$planEndDate = date('Y-m-d', strtotime($endDate));
		$chargeName = $searchArr['chargeName'];
		$taskSql = "select c.chargeId , c.planBeginDate,c.planEndDate , c.planEndDate-c.planBeginDate as dates ,c.appraiseWorkload from oa_rd_task c where ((" .
		"c.planBeginDate>= '" . $planBeginDate . "' and  c.planBeginDate<='" . $planEndDate .
		"') or (c.planEndDate>= '" . $planBeginDate . "' and  c.planEndDate<='" . $planEndDate . "'))  and c.chargeName like'%" . $chargeName . "%'";
		$taskRow = $this->findSql($taskSql);
		return $taskRow;
	}

	function edit($names, $tasks) {
		$data = array ();
		$data[chargName] = '';
		$return = array ();
		//		 echo "in edit  names.count==".count($names)."<br/>";
		foreach ($names as $key => $val) {
			$data[chargName] = $names[$key][chargeName];
			$return[$names[$key][chargeId]] = $data;
		}
		//		 echo "in edit  tasks.count==".count($tasks)."<br/>";
		foreach ($tasks as $key => $val) {
			$startStamp = strtotime($val[planBeginDate]);
			$overStamp = strtotime($val[planEndDate]);
			$dates = $val[dates];
			$appraiseWorkload = $val[appraiseWorkload];
			if ($dates != 0) {
				for ($i = $startStamp; $i <= $overStamp; $i += 86400) {
					$return[$val[chargeId]][$i] += round($appraiseWorkload / $dates, 2);
				}
			} else {
				$return[$val[chargeId]][$startStamp] += $appraiseWorkload;
			}
		}
		//     		echo "in edit  count==".count($return)."<br/>";
		return $return;
	}

	/**
	 * 上传EXCEl并导入其数据
	 * 将传入的数据加工并调用添加方法，若失败，返回失败信息
	 */
	function addBatchByExcel_d($objectArr) {
		try {
			$this->start_d();
			$flag = "";
			$flag1 = "";
			$flag2 = "";
			$tempStr1 = "";
			$tempStr2 = "";
			$temp = 1;
			foreach ($objectArr as $key => $val) {
				//将状态设置为“未发布”
				$val['status'] = 'WFB';
				//如果planName所对应的planId为空，证明数据库中没有此计划名的计划。
				if ($val['planId'] == '' && $val['planName'] != '') {
					$tempStr1 = $tempStr1 . $temp . ",";
					echo '(' . $tempStr1 . ')';
					$temp++;
					continue;
				}
				$id = $this->addTaskByExcel_d($val);
				if (!$id) {
					$tempStr2 = $tempStr2 . $temp . ",";
					echo '[' . $tempStr2 . ']';
				}
				$temp++;
			}
			if ($tempStr1 != "" || $tempStr2 != "") {
				throw new Exception();
			}
			$this->commit_d();
			return $flag;
		} catch (Exception $e) {
			if ($tempStr1 != "")
				$flag1 = "第" . $tempStr1 . "条数据所对应的计划不存在." . "导入失败！请重新上传！" . $flag1;
			if ($tempStr2 != "")
				$flag2 = "第" . $tempStr2 . "条数据导入失败！请重新上传！<br>";
			$flag = $flag1 . $flag2;
			$this->rollBack();
			return $flag;
		}
	}

	/*
	 * 新增/发布 项目任务 新增任务相关信息
	 */
	function addTaskByExcel_d($objectinfo) {
		$tkActUserDao = new model_rdproject_task_tkactuser();
		$tkauditUserDao = new model_rdproject_task_tkaudituser();
		if ($objectinfo['planId'] == '' || $objectinfo['planName'] == '') {
			unset ($objectinfo['planId']);
			unset ($objectinfo['planName']);
		}
		$id = parent :: add_d($objectinfo, true);

		$auditArr = $objectinfo['auditName'];
		/*start:-----添加任务审核人信息---------*/
		foreach ($auditArr as $key => $val) {
			$auditArr[$key]['taskId'] = $id;
		}
		$tkauditUserDao->createBatch($auditArr);
		/*end:--------添加任务审核人信息---------*/

		/*start:-----添加任务参与人---------*/
		$userArr=array();
		if(is_array($objectinfo['userName'])){
			$userArr = $objectinfo['userName'];
		}
		foreach ($userArr as $key => $val) {
			$userArr[$key]['taskId'] = $id;
			$userArr[$key]['isActUser'] = 0;
		}
		if ($objectinfo['chargeId']) {
			$tkactuser['actUserId'] = $objectinfo['chargeId'];
			$tkactuser['userName'] = $objectinfo['chargeName'];
			$tkactuser['taskId'] = $id;
			$tkactuser['isActUser'] = 1;
			array_push($userArr, $tkactuser);
		}

		$tkActUserDao->createBatch($userArr);
		/*end:--添加任务参与人-*/
		return $id;
	}

	/**
	 * 发布任务
	 */
	function mail_d($object, $actor,$id) {
		$peferer = explode('&action=',$_SERVER['HTTP_REFERER']);
		$url = $peferer[0]."&action=toReadTask&id=".$id;
		$addMsg = '您收到了名为：[' . $object['name'] . ']的任务，请尽快查看！</br>' .
				'点击查看任务：<a href="'.$url.'" target="_blank"><font color="blue">'.$object['name'].'</font></a><br>' .
				'地址：<a href="'.$url.'" target="_blank"><font color="blue">'.$url.'</font></a><br>' .
				'任务描述：<br>'.$object['remark'];
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->specialEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '发布', $object['name'], $actor, $addMsg, '1',null,$object['name']);

	}

	/**
	 * 获取当前里程碑点中最大结束时间和最小开始时间
	 */
	function getMinAndMaxDate_d($stoneId) {
		$sql = 'select min(planBeginDate) as planBeginDate,max(planEndDate) as planEndDate from ' . $this->tbl_name . ' where isStone = 0 and stoneId=' . $stoneId . ' limit 1';
		$rows = $this->_db->getArray($sql);
		if (empty ($rows)) {
			array (
				'planBeginDate' => null,
				'planEndDate' => null
			);
		} else {
			return $rows[0];
		}
	}

	/**
	 * 对与快要到期的任务进行预警（发送邮件）
	 */
	function sendMailForExpiredTask() {
		if (date("H") == 14 && date("i") == 00) {
			$mailDao = new includes_class_sendmail();
			$sql = "select c.id,c.name,ac.actUserId,ac.userName,(select u.Email from user u where u.USER_ID=ac.actUserId) as email from oa_rd_task c left join oa_rd_task_act_user ac on c.id=ac.taskId where c.planEndDate=current_Date() and c.status='JXZ'";
			$db = new mysql();
			$taskObjs = $db->getArray($sql);
			echo 2;
			foreach ($taskObjs as $key => $val) {
				if ($val['email']) {
					$title = "研发任务到期预警通知";
					$content = "您好，<font color='blue'>" . $val['userName'] . "</font>，您的研发任务《" . $val['name'] . "》将于今天截止，请您尽快完成或提交！";
					$address = array (
						$val['email'] => $val['userName']
					);
					$mailDao->send($title, $content, $address);
				}
			}
			unset ($mailDao);
		}
	}

	/*
	* 根据项目查询任务时间计划开始时间和计划结束时间之间在该月份的项目的所有任务
	* @linzx
	*/
	function getPorjectMonthTk_d($pjId) {

		$this->searchArr = array (
			"projectId" => $pjId
		);
		function createCalBTk($tkinfo) {
			$tkinfon['EventID'] = $tkinfo['id'];
			$tkinfon['Date'] = $tkinfo['planBeginDate'];
			$tkinfon['URL'] = "?model=rdproject_task_rdtask&action=toReadTask&id=" . $tkinfo['id'];
			$tkinfon['Title'] = "●" . $tkinfo['name'];
			$tkinfon['Description'] = $tkinfo['name'];
			$tkinfon['CssClass'] = "sTask";
			return $tkinfon;

		}
		$tkinfosBegin = array ();
		$tkinfosBegin = $this->listBySqlId("select_default");

		function createCalETk($tkinfo) {
			$tkinfon['EventID'] = $tkinfo['id'];
			$tkinfon['Date'] = $tkinfo['planEndDate'];
			$tkinfon['URL'] = "?model=rdproject_task_rdtask&action=toReadTask&id=" . $tkinfo['id'];
			$tkinfon['Title'] = $tkinfo['name'] . "●";
			$tkinfon['Description'] = $tkinfo['name'];
			$tkinfon['CssClass'] = "eTask";
			return $tkinfon;
		}
		$tkinfosEnd = array ();
		$tkinfosEnd = $this->listBySqlId("select_default");

		if (count($tkinfosEnd) > 0 && count($tkinfosBegin) > 0) {
			return model_common_util :: yx_array_merge(array_map("createCalBTk", $tkinfosBegin), array_map("createCalETk", $tkinfosEnd));
		} else {
			return $tkinfosEnd;
		}
	}

	/**********************************************我的任务列表导出  获取数据 start*****************************************************/
	/*
	 * 获取属于当前登录人的接收任务
	 */
	function getRrecievedRows_d($searchArr,$selectField) {
		$tkcondition = "";
		$tkcondition = $this->createQuery($tkcondition, $searchArr);
		$this->sort = "updateTime";
		$sql = "select c.id,c.name,c.projectId,c.projectCode,c.projectName,c.priority,c.status,c.effortRate,c.warpRate,c.chargeId,c.chargeName,"
		. "c.publishId,c.publishName,c.taskType,c.planBeginDate,c.planEndDate,c.actBeginDate,c.actEndDate,c.planDuration,c.realDuration,"
		. "c.appraiseWorkload,c.putWorkload,c.actWorkload,c.remark,c.endDayNum,c.planId,c.planCode,c.planName,"
		. "c.belongNode,c.belongNodeId,c.inspectInfo,c.isStone,c.markStoneName,c.stoneId,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,p.finishGrade"
		. " from oa_rd_task c left join (select db.taskId,db.finishGrade from ( select o.taskId,o.finishGrade from oa_rd_task_over o order by o.id desc) db group by db.taskId) p on c.id=p.taskId " . $tkcondition . " and c.status<>'WFB' " .
		"and c.id in(select distinct u.taskId from oa_rd_task_act_user u where u.actUserId='" . $_SESSION['USER_ID'] . "') ";

		//$this->asc = false;
		return $this->listBySql($sql);
	}
	/*
	  * 获取当前登录人的分配任务(发布、创建的)
	  */
	function getAllotRows_d($searchArr,$selectField) {
		$searchContion = "";
		$searchStr = "";
		if ($searchArr['name']) {
			$searchContion = " and c.name like '%" . $searchArr['name'] . "%'";
		}
		if ($searchArr['projectName']) {
			$searchContion = " and c.projectName like '%" . $searchArr['projectName'] . "%'";
		}
		if ($searchArr['priority'] && $searchArr['priority'] != null) {
			$searchStr .= " and c.priority='" . $searchArr['priority'] . "'";
		}
		if ($searchArr['status'] && $searchArr['status'] != null) {
			$searchStr .= " and c.status='" . $searchArr['status'] . "'";
		}
		$condition = " and (c.status!='WFB' and c.publishId='" . $_SESSION['USER_ID'] . "'" . $searchContion . ") or (c.createId='" . $_SESSION['USER_ID'] . "'" . $searchContion . ")";
		$this->sort = "updateTime desc,c.id";
		$sql = "select c.id,c.name,c.projectId,c.projectCode,c.projectName,c.priority,c.status,c.effortRate,c.warpRate,c.chargeId,c.chargeName," . "c.publishId,c.publishName,c.taskType,c.planBeginDate,c.planEndDate,c.actBeginDate,c.actEndDate,c.planDuration,c.realDuration," . "c.appraiseWorkload,c.putWorkload,c.actWorkload,c.remark,c.endDayNum,c.planId,c.planCode,c.planName," . "c.belongNode,c.belongNodeId,c.inspectInfo,c.isStone,c.markStoneName,c.stoneId,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,p.finishGrade" . " from oa_rd_task c left join (select db.taskId,db.finishGrade from ( select o.taskId,o.finishGrade from oa_rd_task_over o order by o.id desc) db group by db.taskId) p on c.id=p.taskId where 1=1 " . $searchStr . " " . $condition;

		return $this->listBySql($sql);
	}
	/*
	   * 获取当前登录人的待审核任务
	   */
	/*
	   * 获取当前登录人的待审核任务
	   */
	function getAuditRows_d($searchArr,$selectField) {
		$auditUserDao = new model_rdproject_task_tkaudituser();
		$auditUserSearch = array (
			"auditId" => $_SESSION['USER_ID']
		);
		$auditUserDao->searchArr = $auditUserSearch;
		$auditUserArr = $auditUserDao->listBySqlId("select_taskid");
		$idsArr = array ();
		$instatusArr = array (
			"DSH",
			"WTG",
			"TG"
		);
		$tkSearchArr = array ();
		if ($auditUserArr) {
			foreach ($auditUserArr as $key => $idsValue) {
				array_push($idsArr, $idsValue['taskId']);
			}
			$tkSearchArr = $searchArr;
			$tkSearchArr['ids'] = $idsArr;
			$tkSearchArr['instatus'] = $instatusArr;
			$this->searchArr = $tkSearchArr;
			$this->sort = "updateTime";
			//			$this->searchArr=array(
			//				"ids"=>$idsArr,
			//				"instatus"=>$instatusArr
			//			);
			//$sql="select c.* from ".$this->tbl_name." c where c.status='DSH' and c.id in(".$ids.")";
			return $this->listBySqlId();
		} else
			return null;
	}

	/**********************************************我的任务列表导出  获取数据 end*****************************************************/

}

?>