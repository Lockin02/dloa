<?php

/**
 * @author Administrator
 * @Date 2010��12��5�� 10:02:49
 * @version 1.0
 * @description:������־�ܱ� Model��
 */
class model_engineering_worklog_esmworklogweek extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_worklog_week";
		$this->sql_map = "engineering/worklog/esmworklogweekSql.php";
		parent :: __construct();
	}

	/**
	* @desription ������־�б���ʾ
	* @param tags
	* @date 2010-12-7 ����11:19:31
	* @qiaolong
	*/
	function showorkloglist($arr) {
		if ($arr) {
			$i = 0;
			$str = "";
			$datadictDao = new model_system_datadict_datadict ();
			foreach ($arr as $key => $val) {
				$i++;
				$proName="";
				$n = ($i % 2) + 1;
				 $workStatus = $datadictDao->getDataNameByCode ( $val ['workStatus'] );
				$workLogId = $arr[$key]['id'];
				$logproDao = new model_engineering_worklog_esmlogpro();
				$logproDao->searchArr['workLogId']=$workLogId;
				$rows = $logproDao->pageBySqlId('select_default');
				foreach($rows as $rkey=>$rval){
					$proName.=$rows[$rkey]['proName'].',';
				}
				$proName=substr($proName,0,-1);
				$str .=<<<EOT
				<tr id="tr_$val[id]" class="TableLine$n">
					<td align="center">$val[executionDate]</td>
					<td align="center">$val[weekDate]</td>
					<td align="center">$val[workPlace]</td>
					<td align="center">$workStatus</td>
					<td align="center">$proName</td>
					<td align="center">$val[description]</td>
					<td align="center">$val[remark]</td>
				</tr>
EOT;
			}
			return $str;
		}else{
				return $str= '<tr><td colspan="20">������ؼ�¼</td></tr>';
			}

	}
	/*
	 * �����ܱ��п���ָ����ϸ��Ϣ
	 */
	function showLevelList($rows) {
		if ($rows) {
			$i = 0;
			$str = "";
			foreach ($rows as $key => $val) {
				$i++;
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .=<<<EOT
				<tr>
							<td align="center">$i</td>
							<td align="center" class="main_td_align_left">
								<input type="hidden" id="assweek[assresults][$i][weight]" value="$val[weight]">
								<input type="hidden" name="assweek[assresults][$i][indicatorId]" value="$val[id]">
								<input type="hidden" name="assweek[assresults][$i][score]"  >
								<input type="hidden" name="assweek[assresults][$i][indicatorsName]" value="$val[name]">$val[name]
							</td>
							<td align="center" ><select class="select" onchange="subAssScore(this.value,this.weight)" weight="$val[weight]"  >
EOT;

				foreach ($val['options'] as $okey => $option) {
					$str .=<<<EOT
								<option value="$option[score]">$option[name]</option>
EOT;
				}
				$str .=<<<EOT
							</select>
							</td>
						</tr>
						<tr>
			</tr>
EOT;
			}
		}
		return $str;

	}
	/*
	 * �����ܱ��п���ָ����ϸ��Ϣ��
	 */
	function showLevelListView($rows) {
		if ($rows) {
			$i = 0;
			$str = "";
			foreach ($rows as $key => $val) {
				$i++;
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .=<<<EOT
				<tr>
							<td align="center">$i</td>
							<td align="center" class="main_td_align_left">
								<input type="hidden" id="levelId" name="assIndex[levelId]" value="$val[levelId]">
								<input type="hidden" id="id" name="assIndex[id]" value="$val[id]">
								<input type="hidden" id="name" name="assIndex[name]">$val[name]
							</td>
							<td align="center" ><select class="select">
EOT;

				foreach ($val['options'] as $okey => $option) {
						$valname =$val['options'][$okey][name];
						$str .=<<<EOT
									<option >$valname</option>
EOT;
				}
				$str .=<<<EOT
							</select></td>
						</tr>
						<tr>
						</tr>
EOT;
			}
		}
		return $str;

	}
	/*======================================ҵ����=========================================*/
	/**
	 * @desription ������־id��ȡ��־������Ϣ�Ϳ�����Ϣ
	 * @param tags
	 * @date 2010-12-7 ����10:36:51
	 * @qiaolong
	 */
	function getassweekInfo($id){
		$row = $this->get_d($id);
	    foreach($row as $key=>$val){
			$assweekDao = new model_engineering_assessment_assweek();
			$assweekDao->searchArr['weekLogId'] = $id;
	    	$assweekArr =  $assweekDao->pageBySqlId('select_default');
				$row['option']=$assweekArr[0];
	    }
	    return $row;
	}
	/**
	 * @desription �ҵĿ��������ȡ���ݷ���
	 * @param tags
	 * @date 2010-12-7 ����10:36:51
	 * @qiaolong
	 */
	function getMyasstaskInfo($assessmentId) {
		$this->searchArr['assessmentId'] = $assessmentId;
		$this->searchArr['subStatus'] = "ZBSHZ".","."ZBYKH";
		return $this->pageBySqlId('select_default');
	}
	/**
	 * @desription ���ݵ�½�˻�ȡ���ڰ��´����ܱ�
	 * @param tags
	 * @date 2010-12-8 ����03:03:03
	 * @qiaolong
	 */
	function getdtweeklogInfo($createId) {
		$peopleDao = new model_engineering_personnel_personnel();
		$peopleDao->searchArr['userCode'] = $createId;
		$row = $peopleDao->pageBySqlId('select_personnel');
		return $row;
	}
	/**
	 * @desription ������־id���ұ��ܵ���־
	 * @param tags
	 * @date 2010-12-7 ����11:15:42
	 * @qiaolong
	 */
	function getwgInfobyId($id) {
		$worklogDao = new model_engineering_worklog_esmworklog();
		$worklogDao->searchArr['weekId'] = $id;
		return $worklogDao->pageBySqlId('select_default');
	}
	/**
	 * @desription ��ȡ��Ա�ܱ�����
	 * @param tags
	 * @date 2010-12-15 ����01:55:16
	 * @qiaolong
	 */
	function getPsnweelog () {
		return $this->pageBySqlId('select_default');
	}
	/**
	 * @desription ������־�ȼ����Ҷ��ڵ�ָ������ص�ѡ����Ϣ
	 * @param tags
	 * @date 2010-12-9 ����09:25:53
	 * @qiaolong
	 */
	function getConfigbyrc($rankCode) {
		$peopleLevelDao = new model_engineering_assessment_assPeopleLevel();
		$peopleLevelDao->searchArr['levelName'] = $rankCode;
		$peoplelevels = $peopleLevelDao->listBySqlId('asspeopleLevelInfo');
//		echo "<pre>";
//		print_r($peoplelevels);
		$rankCodeId = $peoplelevels[0]['id'];
		$peopleConfigDao = new model_engineering_assessment_assPeopleConfig(); //�ȼ�ָ������dao
		$peopleConfigDao->searchArr['levelId'] = $rankCodeId;
		$peopleconfigs = $peopleConfigDao->listBySqlId('assConfigInfo');
//		echo "<pre>";
//		print_r($peopleconfigs);
		$configinfos = array ();
		foreach ($peopleconfigs as $key => $peopleconfig) {
			$indexId = $peopleconfig['indexId'];
			$assIndexDao = new model_engineering_assessment_assIndex(); //ָ���dao
			$assIndexDao->searchArr['id'] = $indexId;
			$configinfos = $assIndexDao->listBySqlId('select_assIndextree');
			$assConfigDao = new model_engineering_assessment_assConfig();
			$assConfigDao->searchArr['parentId'] = $indexId;
			$configOptions = $assConfigDao->listBySqlId('assConfigInfo');
			foreach ($configOptions as $optionkey => $option) {
				$peopleconfigs[$key]['options'][$optionkey] = $option;
			}

		}
//				echo "<pre>";
//		 		print_r($peopleconfigs);

		return $peopleconfigs;
	}
	/*
	 * ������Ŀid ��ȡ��������ܱ���Ϣ
	 */
	function getProWeekLog($pjId){
		//echo $pjId;
		$sql="select w.id ,w.weekTimes ,w.weekTitle ,w.subStatus,w.weekBeginDate ,w.weekEndDate ,w.rankCode ,w.directlyId ,w.directlyName ,w.existence ,w.improvement ,w.subStatus,w.isAttention ,w.depId ,w.depName ,w.createId ,w.createName ,w.createTime ,w.updateId ,w.updateName ,w.updateTime ,w.assessmentId ,w.assessmentName from oa_esm_worklog_week w where w.id in (select c.weekId from oa_esm_worklog c  right join oa_esm_worklog_proinfo p  on(c.id = p.workLogId)  where p.proId = ".$pjId." group by c.weekId)";
		//echo $sql;
		return $this->pageBySql($sql);
	}
	/**
	 * @desription �ܱ�����Excel(������)
	 * @param tags
	 * @date 2010-12-19 ����09:56:37
	 * @qiaolong
	 */
	function getworklogExcel ($search) {
		$sql = "select c.weekTimes,c.depName,c.createName, m.assLevel ,f.officeName from oa_esm_worklog_week c left join oa_esm_ass_week m on c.id = m.weekLogId  left join oa_esm_personal_baseinfo p on(p.userCode=c.createId)   left join oa_esm_office_baseinfo f on(f.id=p.officeId)  where 1=1 and c.subStatus='ZBYKH' and c.weekBeginDate >='".$search['beginTime']."'and c.weekBeginDate <='".$search['endTime']."' and f.officeName = '".$search['officeName']."'";
		return $arr = $this->listBySql($sql);

	}
		/**
	 * @desription �ܱ�����Excel(ȫ��)
	 * @param tags
	 * @date 2010-12-19 ����09:56:37
	 * @qiaolong
	 */
	function getworklogExcels () {
		return $arr = $this->listBySqlId('worklog_excel');
	}
}
?>