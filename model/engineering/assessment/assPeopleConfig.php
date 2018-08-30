<?php
/*
 * Created on 2010-12-2
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class model_engineering_assessment_assPeopleConfig extends model_base{
	function __construct() {
		$this->tbl_name = "oa_esm_people_config";
		$this->sql_map = "engineering/assessment/assPeopleConfigSql.php";
		parent::__construct ();
	}


	/*
	 * 指标列表显示
	 */
	function showlevelList($rows) {
		$str = "";
		if ($rows) {
			$i = 0;

			foreach ( $rows as $key => $val ) {
				$i ++;
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$trid = $val['indexId'];
				$rowI=$i;
				$str .= <<<EOT
				<tr>
					<td>
						<input type="hidden"  name="assIndex[$i][levelId]" value="$val[levelId]">
						<input type="hidden" id="$val[indexId]" name="assIndex[$i][indexId]" value="$val[indexId]">
						<input type="hidden"  name="assIndex[$i][name]" value="$val[name]">$val[name]
					</td>
					<td>
						<input type="text" class="txt" id="weight$i" name="assIndex[$i][weight]" value="$val[weight]">
					</td>
				</tr>
EOT;
			}
		}return $str;

	}
	/*
	 * 指标配置列表显示
	 */
	function showIndexList($rows,$arr) {
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
	/*======================================业务处理=========================================*/

	/*
	 * 新增/修改人员等级指标信息
	 * @author huangzf
	 */

	 function add_d($objects,$levelId){
	 	/*start:删除指标*/
			$conditons=array(
				"levelId"=>$levelId
			);
			$this->delete($conditons);
		/*end:删除指标*/

	 	if($objects){
			/*start:重新保存指标信息*/

			$this->addBatch_d($objects);
			/*end:重新保存指标信息*/
	 	}

	 }

	/**
	 * @desription 获取数据方法
	 * @param tags
	 * @date 2010-11-13 上午10:08:06
	 */
	function getConInfo ($levelId) {//任务ID
		$this->searchArr['levelId'] = $levelId;
		return $this->pageBySqlId('assPeoCon');
	}

	/**
	 * @desription 根据等级id查询指标信息
	 * @param tags
	 * @date 2010-12-6 上午10:23:10
	 * @qiaolong
	 */
	function getbrowseInfo ($rankCodeId) {

//		$peopleConfigDao = new model_engineering_assessment_assPeopleConfig(); //等级指标配置dao
		$this->searchArr['levelId'] = $rankCodeId;
		$peopleconfigs = $this->listBySqlId('assConfigInfo');
		$configinfos = array ();
		foreach ($peopleconfigs as $key => $peopleconfig) {
			$indexId = $peopleconfig['indexId'];
			$assIndexDao = new model_engineering_assessment_assIndex(); //指标库dao
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

	/**
	 * @desription 根据指标id获取配置信息
	 * @param tags
	 * @date 2010-12-6 上午10:42:48
	 * @qiaolong
	 */
	function getIndexInfo ($indexId) {
		$assIndexDao = new model_engineering_assessment_assIndex();
		$assIndexDao->searchArr['id']=$indexId;
//		print_r($a);
		return $assIndexDao->pageBySqlId('select_assIndextree');

	}
	/**
	 * @desription 根据指标id获取配置信息
	 * @param tags
	 * @date 2010-12-6 上午10:42:48
	 * @qiaolong
	 */
	function getConfigInfo ($parentId) {
		$assConfigDao = new model_engineering_assessment_assConfig();
		$assConfigDao->searchArr['parentId']=$parentId;
//		print_r($a);
		return $assConfigDao->pageBySqlId('assConfigInfo');

	}
}
?>
