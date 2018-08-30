<?php
/**
 * @author show
 * @Date 2013��10��17�� 15:38:39
 * @version 1.0
 * @description:��Ŀ��Ԥ���� Model��
 */
class model_engineering_weekreport_weekfee extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_project_weekfee";
		$this->sql_map = "engineering/weekreport/weekfeeSql.php";
		parent :: __construct();
	}

	/**
	 * ��ȡ
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
	 * ��ȡ�¼�¼
	 */
	function getNew_d($projectId,$weekNo){
		//��Ҫ�Ȳ����Ŀ���
		$esmprojectDao = new model_engineering_project_esmproject();
		$esmprojectObj = $esmprojectDao->find(array('id' => $projectId),null,'projectCode,budgetEqu');

		//��ȡԤ��
        $esmbudgetDao = new model_engineering_budget_esmbudget();
        $esmbudgetDao->getParam ( array('projectId' => $projectId,'sort'=>'c.budgetType,c.parentName'));
        $budgetArr = $esmbudgetDao->list_d();

        //���û�ȡ
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

		//�������û�ȡ
		$esmmemberDao = new model_engineering_member_esmmember();
		$esmmemberObj = $esmmemberDao->getFeePerson_d($projectId);
		$feePerson = $esmmemberObj['feePerson'];

		//����в�ѯ���������ã�����ص�������
		$mark = 0;
		if($feePerson){
			foreach($budgetArr as &$val){
				unset($val['id']);
                //��߼����������
				if($val['budgetType'] == 'budgetPerson' && empty($mark)){
					$mark = 1;
					$val['fee'] = $feePerson;
				}
                //�����ֳ�����
                if(isset($newArr[$val['budgetName']])){
                    $val['fee'] = $newArr[$val['budgetName']];
                    unset($newArr[$val['budgetName']]);
                }
                $val['budget'] = $val['amount'];
			}
		}

        //�������û���صľ���,�Զ�����һ��
        if(!empty($newArr)){
            $thisAmount = 0;
            foreach($newArr as $v){
            	$thisAmount = bcadd($thisAmount,$v);
            }
            array_push($budgetArr,array('parentName' => '�����ֳ�����','budgetName' => '�����ֳ�����','budget' => 0,'fee' => $thisAmount));
        }

		if(empty($mark)){//���û�ҵ�����Ԥ�㣬��ӵ���������
			array_push($budgetArr,array('parentName' => '����Ԥ��','budgetName' => '����Ԥ��','budget' => 0,'fee' => $feePerson));
		}

		//��ȡ�豸����
        $esmDeviceFeeDao = new model_engineering_resources_esmdevicefee();
        $feeEqu = bcadd($esmprojectObj['feeEqu'], $esmDeviceFeeDao->getDeviceFee_d($projectId),2);
		array_push($budgetArr,array('parentName' => '�豸Ԥ��','budgetName' => '�豸Ԥ��','budget' => $esmprojectObj['budgetEqu'],'fee' => $feeEqu));

		return $budgetArr;
	}

	/**
	 * ��ȡ�Ѵ��ڵ���Ŀ��չ��Ϣ
	 */
	function getNow_d($projectId,$weekNo,$mainId){
		$arr = $this->findAll(array('mainId' => $mainId));
		return $arr;
	}

	/**
	 * ��ʾ���
	 */
	function showWeek_d($object){
		$str= "";
		if($object){
			$tdStr = null;//�����ַ���
			$allBudget = 0;
			$allFee = 0;
			foreach($object as $key => $val){
				//�ж�����ʾЧ��
				$trClass = $key%2 == 0 ? 'tr_odd' : 'tr_even';
				$budget = number_format($val['budget'],2);
				$fee = number_format($val['fee'],2);
				$process = $budget==0 ? '--' : bcmul(bcdiv($val['fee'],$val['budget'],4),100,2);
				//���ַ���
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

			//��Ԥ�㴦��
			$allProcess = $allBudget == 0 ? '--' : bcmul(bcdiv($allFee,$allBudget,4),100,2);
			$allBudget = number_format($allBudget,2);
			$allFee = number_format($allFee,2);
			//��ͷ����
			$str =<<<EOT
				<table class="form_in_table" style="width:800px;">
					<thead>
						<tr class="main_tr_header">
							<th width="20%">����</th>
							<th width="20%">С��</th>
							<th width="20%">Ԥ��</th>
							<th width="20%">����</th>
							<th width="20%">���ý���</th>
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
	 * ���±��¼
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