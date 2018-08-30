<?php
/**
 * @author show
 * @Date 2013年10月17日 15:38:39
 * @version 1.0
 * @description:项目周预决算 Model层
 */
class model_engineering_weekreport_weekfee extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_project_weekfee";
		$this->sql_map = "engineering/weekreport/weekfeeSql.php";
		parent :: __construct();
	}

	/**
	 * 获取
	 */
	function getWeek_d($projectId = null,$weekNo = null,$mainId = null){
		if($mainId){
			$obj = $this->find(array('mainId' => $mainId));
			if(empty($obj)){
				return $this->getNew_d($projectId,$weekNo);
			}
			return $this->getNow_d($projectId,$weekNo,$mainId);
		}else{
			return $this->getNew_d($projectId,$weekNo);
		}
	}

	/**
	 * 获取新纪录
	 */
	function getNew_d($projectId,$weekNo){
		//需要先查个项目编号
		$esmprojectDao = new model_engineering_project_esmproject();
		$esmprojectObj = $esmprojectDao->find(array('id' => $projectId),null,'projectCode,budgetEqu');

		//获取预算
        $esmbudgetDao = new model_engineering_budget_esmbudget();
        $esmbudgetDao->getParam ( array('projectId' => $projectId,'sort'=>'c.budgetType,c.parentName'));
        $budgetArr = $esmbudgetDao->list_d();

        //费用获取
        $expenseDao = new model_finance_expense_expense();
        $expenseArr = $expenseDao->getFeeDetail_d($esmprojectObj['projectCode']);
        $newArr = array();
        if($expenseArr){
            $allCostMoney = 0;
            foreach($expenseArr as $key => $val){
                $newArr[$val['CostTypeName']] = $val['CostMoney'];
                $allCostMoney = bcadd($allCostMoney,$val['CostMoney'],2);
            }
        }

		//人力费用获取
		$esmmemberDao = new model_engineering_member_esmmember();
		$esmmemberObj = $esmmemberDao->getFeePerson_d($projectId);
		$feePerson = $esmmemberObj['feePerson'];

		//如果有查询到人力费用，则加载到数组中
		$mark = 0;
		if($feePerson){
			foreach($budgetArr as &$val){
				unset($val['id']);
                //这边加载人力金额
				if($val['budgetType'] == 'budgetPerson' && empty($mark)){
					$mark = 1;
					$val['fee'] = $feePerson;
				}
                //加载现场决算
                if(isset($newArr[$val['budgetName']])){
                    $val['fee'] = $newArr[$val['budgetName']];
                    unset($newArr[$val['budgetName']]);
                }
                $val['budget'] = $val['amount'];
			}
		}

        //如果还有没加载的决算,自动生成一行
        if(!empty($newArr)){
            $thisAmount = 0;
            foreach($newArr as $v){
            	$thisAmount = bcadd($thisAmount,$v);
            }
            array_push($budgetArr,array('parentName' => '其他现场决算','budgetName' => '其他现场决算','budget' => 0,'fee' => $thisAmount));
        }

		if(empty($mark)){//如果没找到人力预算，则加到总类型中
			array_push($budgetArr,array('parentName' => '人力预算','budgetName' => '人力预算','budget' => 0,'fee' => $feePerson));
		}

		//获取设备决算
        $esmDeviceFeeDao = new model_engineering_resources_esmdevicefee();
        $feeEqu = bcadd($esmprojectObj['feeEqu'], $esmDeviceFeeDao->getDeviceFee_d($projectId),2);
		array_push($budgetArr,array('parentName' => '设备预算','budgetName' => '设备预算','budget' => $esmprojectObj['budgetEqu'],'fee' => $feeEqu));

		return $budgetArr;
	}

	/**
	 * 获取已存在的项目进展信息
	 */
	function getNow_d($projectId,$weekNo,$mainId){
		$arr = $this->findAll(array('mainId' => $mainId));
		return $arr;
	}

	/**
	 * 显示表格
	 */
	function showWeek_d($object){
		$str= "";
		if($object){
			$tdStr = null;//内容字符串
			$allBudget = 0;
			$allFee = 0;
			foreach($object as $key => $val){
				//判断行显示效果
				$trClass = $key%2 == 0 ? 'tr_odd' : 'tr_even';
				$budget = number_format($val['budget'],2);
				$fee = number_format($val['fee'],2);
				$process = $budget==0 ? '--' : bcmul(bcdiv($val['fee'],$val['budget'],4),100,2);
				//行字符串
				$tdStr.=<<<EOT
					<tr class="$trClass">
						<td align="left">
							{$val['parentName']}
							<input type="hidden" name="statusreport[weekfee][$key][id]" value="{$val['id']}"/>
							<input type="hidden" name="statusreport[weekfee][$key][parentName]" value="{$val['parentName']}"/>
							<input type="hidden" name="statusreport[weekfee][$key][budgetName]" value="{$val['budgetName']}"/>
							<input type="hidden" name="statusreport[weekfee][$key][budget]" value="{$val['budget']}"/>
							<input type="hidden" name="statusreport[weekfee][$key][fee]" value="{$val['fee']}"/>
							<input type="hidden" name="statusreport[weekfee][$key][processAct]" value="$process"/>
						</td>
						<td align="left">{$val['budgetName']}</td>
						<td align="right">$budget</td>
						<td align="right">$fee</td>
						<td align="right">$process %</td>
					</tr>
EOT;
				$allBudget = bcadd($allBudget,$val['budget'],2);
                $allFee = bcadd($allFee,$val['fee'],2);
			}

			//总预算处理
			$allProcess = $allBudget == 0 ? '--' : bcmul(bcdiv($allFee,$allBudget,4),100,2);
			$allBudget = number_format($allBudget,2);
			$allFee = number_format($allFee,2);
			//表头处理
			$str =<<<EOT
				<table class="form_in_table" style="width:800px;">
					<thead>
						<tr class="main_tr_header">
							<th width="20%">大类</th>
							<th width="20%">小类</th>
							<th width="20%">预算</th>
							<th width="20%">决算</th>
							<th width="20%">费用进度</th>
						</tr>
					</thead>
					$tdStr
					<tr class="tr_count">
						<td></td>
						<td></td>
						<td align="right">$allBudget</td>
						<td align="right">$allFee</td>
						<td align="right">$allProcess %</td>
					</tr>
				</table>
EOT;
		}
		return $str;
	}
	/**
	 * 更新表记录
	 */
	function update_d($mainId,$weeklogInfo){
		try{
			$this->start_d();
			foreach ($weeklogInfo as $key => $val){
				$this->update(array('mainId' => $mainId,'budgetName' => $val['budgetName']),$val);
			}
			$this->commit_d();
		}catch (Exception $e){
			$this->rollBack();
		}
	}
}