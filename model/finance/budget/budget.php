<?php
/**
 * @author zengqin
 * @Date 2015-2-10
 * @version 1.0
 * @description:����Ԥ��Model
 */
class model_finance_budget_budget extends model_base{

	function __construct() {
		$this->tbl_name = "oa_finance_budget";
		$this->sql_map = "finance/budget/budgetSql.php";
		parent::__construct ();
	}

	/**
	 * ����
	 */
	 function add_d($object){
		 try {
            $this->start_d();
			$budgetId = parent :: add_d($object, true);
			$budgetDetails = $object['budgetDetail'];  //Ԥ����ϸ
			foreach($budgetDetails as $key=>$val){
				$budgetDetailDao = new model_finance_budget_budgetDetail();
				$val['mainId']=$budgetId;
				$val['expenseTypeId']=$object['expenseTypeId'];
				$val['expenseTypeCode']=$object['expenseTypeCode'];
				$val['expenseType']=$object['expenseType'];
				$val['year']=$object['year'];
				$val['expenseClass']=$object['expenseClass'];
				$budgetDetailDao->add_d($val,true);
			}
			$this->commit_d();
			return $budgetId;
		 }catch (exception $e) {
            $this->rollBack();
            return false;
        }
	 }

	 /**
	  * �༭
	  */
	function edit_d($object){
	   try {
	   		$budgetId=$object['id'];
            $this->start_d();
			$flag = parent :: edit_d($object, true);
			$budgetDetails = $object['budgetDetail'];  //Ԥ����ϸ
			$budgetDetailDao = new model_finance_budget_budgetDetail();
			$budgetLogDao = new model_finance_budget_budgetLog();
			foreach($budgetDetails as $key=>$val){
				$oldDetail = $budgetDetailDao->get_d($val['id']);
				foreach($val as $key2=>$val2){
					if($key2!='isProvinceVisible'&&$val2!=$oldDetail[$key2]){ //ʡ�����Ƿ�ɼ����޸� ������ʷ��¼
						$log['detailId']=$val['id'];
						$log['modifyField']=$key2;
						$log['beforeModify']=$oldDetail[$key2];
						$log['afterModify']=$val2;
						$log['updateId']=$_SESSION['USER_ID'];
						$log['updateName']=$_SESSION['USER_NAME'];
						$log['updateTime']=date("Y-m-d H:i:s");
						$budgetLogDao->add_d($log);
					}
				}
				if(!isset($val['isProvinceVisible'])){
					$val['isProvinceVisible']=0;
				}
				$detailFlag = $budgetDetailDao->edit_d($val,true);
			}
			$this->commit_d();
			return $flag;
		}catch (exception $e) {
            $this->rollBack();
            return false;
        }
	}
	 /**
     * ���ݱ�������ά��Ԥ����е�����ֶ�
     * @param expenseId expense��������
     */
   function updateBudgetDetail($expenseId){
   		$expenseDao = new model_finance_expense_expense();
   		$expense = $expenseDao->get_d($expenseId);
		$detailType = $expense['DetailType'];
		$ProjectNo = $expense['ProjectNo'];
		try{
			$this->start_d();
			if($detailType==4&&$ProjectNo==''){ //��ǰ����(�ų���PK��Ŀ)
				$budgetDetailDao = new model_finance_budget_budgetDetail();
				$areaId = $expense['salesAreaId'];

				$areaId = $budgetDetailDao->checkArea($areaId); //�ж������Ƿ����ڹ��ݱ�Ѷ

				$condition = array("areaId"=>$areaId,"year"=>date("Y",time()),"expenseTypeCode"=>"SQFY");
				$budgetDetail = $budgetDetailDao->find($condition);
				$final = $budgetDetail['final']; //�����ܾ���
				$amount = $expense['Amount'];
				$final = $final>0?$final:0;
				$budgetDetail['final']=floatval($final+$amount);
				$currentSeason = $budgetDetailDao->currentSeason(); //��ȡ��ǰ����
				switch($currentSeason){
					case 'first':
						$firstFinal = floatval($budgetDetail['firstFinal']);
						$budgetDetail['firstFinal'] =floatval($firstFinal+$amount);
						break;
					case 'second':
						$secondFinal = floatval($budgetDetail['secondFinal']);
						$budgetDetail['secondFinal'] = $secondFinal+$amount;
						break;
					case 'third':
						$thirdFinal = floatval($budgetDetail['thirdFinal']);
						$budgetDetail['thirdFinal'] = $thirdFinal+$amount;
						break;
					case 'fourth':
						$fourthFinal = floatval($budgetDetail['fourthFinal']);
						$budgetDetail['fourthFinal'] = $fourthFinal+$amount;
						break;
				}
				$budgetDetailDao->updateById($budgetDetail);
				$budgetDao = new model_finance_budget_budget();
				$condition = array("year"=>date("Y",time()),"expenseTypeCode"=>"SQFY");
				$budget = $budgetDao->find($condition);
				$budgetFinal = $budget['final'];
				$budgetFinal = $budgetFinal>0?$budgetFinal:0;
				$budget['final']=floatval($budgetFinal+$amount);
				$budgetDao->updateById($budget);
			}
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
    }

	/**
	 * ���ñ���������ͨ�����߳�����������Ӿ����м�ȥ��ͨ���ķ���
	 * @param expenseId expense��������
	 */
	function subFinal($expenseId){
		$expenseDao = new model_finance_expense_expense();
   		$expense = $expenseDao->get_d($expenseId);
		$detailType = $expense['DetailType'];
		try{
			$this->start_d();
			if($detailType==4){ //��ǰ����
				$budgetDetailDao = new model_finance_budget_budgetDetail();

				$areaId = $expense['salesAreaId'];
				$areaId = $budgetDetailDao->checkArea($areaId); //�ж������Ƿ����ڹ��ݱ�Ѷ

				$condition = array("areaId"=>$areaId,"year"=>date("Y",time()),"expenseTypeCode"=>"SQFY");
				$budgetDetail = $budgetDetailDao->find($condition);
				$final = $budgetDetail['final']; //�����ܾ���
				$amount = $expense['Amount'];
				$final = $final>0?$final:0;
				$budgetDetail['final']=floatval($final-$amount);
				$currentSeason = $budgetDetailDao->currentSeason();
				switch($currentSeason){
					case 'first':
						$firstFinal = floatval($budgetDetail['firstFinal']);
						$budgetDetail['firstFinal'] =floatval($firstFinal-$amount);
						break;
					case 'second':
						$secondFinal = floatval($budgetDetail['secondFinal']);
						$budgetDetail['secondFinal'] = $secondFinal-$amount;
						break;
					case 'third':
						$thirdFinal = floatval($budgetDetail['thirdFinal']);
						$budgetDetail['thirdFinal'] = $thirdFinal-$amount;
						break;
					case 'fourth':
						$fourthFinal = floatval($budgetDetail['fourthFinal']);
						$budgetDetail['fourthFinal'] = $fourthFinal-$amount;
						break;
				}
				$budgetDetailDao->updateById($budgetDetail);
				$budgetDao = new model_finance_budget_budget();
				$condition = array("year"=>date("Y",time()),"expenseTypeCode"=>"SQFY");
				$budget = $budgetDao->find($condition);
				$budgetFinal = $budget['final'];
				$budgetFinal = $budgetFinal>0?$budgetFinal:0;
				$budget['final']=floatval($budgetFinal-$amount);
				$budgetDao->updateById($budget);
			}
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}
	/**
	 * ����ɾ������ɾ���ӱ��������
	 */
	function deletes_d($ids) {
		try {
			$this->start_d();
			$this->deletes ( $ids );
			$budgetDetailDao = new model_finance_budget_budgetDetail();
			$condition = " $budgetDetailDao->tbl_name.mainId in ($ids)";
			$budgetDetailDao->delete($condition);
			$this->commit_d();
			return true;
		} catch ( Exception $e ) {
			throw $e;
			$this->rollBack();
			return false;
		}
	}
	/**
	 * �ж�ͬ��ݵ�Ԥ���Ƿ��Ѵ���
	 * @param year ��� e.g:2015
	 * @param expenseCode �������ͱ��� e.g:SQFY
	 * @param expenseClass ����С��  e.g:���۷���
	 * @return 1 �Ѵ��� 0 ������
	 */
	 function checkUnique($year,$expenseCode,$expenseClass){
		$conditions = array("year"=>$year,"expenseCode"=>$expenseCode,"expenseClass"=>$expenseClass);
		$this->searchArr = $conditions;
		$budgets = $this->list_d();
		if(is_array($budgets)&&sizeof($budgets)>0){
			return 1;
		}else{
			return 0;
		}
	 }
}
 ?>