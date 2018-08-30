<?php
/**
 * @author zengqin
 * @Date 2015-2-10
 * @version 1.0
 * @description:费用预算Model
 */
class model_finance_budget_budget extends model_base{

	function __construct() {
		$this->tbl_name = "oa_finance_budget";
		$this->sql_map = "finance/budget/budgetSql.php";
		parent::__construct ();
	}

	/**
	 * 新增
	 */
	 function add_d($object){
		 try {
            $this->start_d();
			$budgetId = parent :: add_d($object, true);
			$budgetDetails = $object['budgetDetail'];  //预算明细
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
	  * 编辑
	  */
	function edit_d($object){
	   try {
	   		$budgetId=$object['id'];
            $this->start_d();
			$flag = parent :: edit_d($object, true);
			$budgetDetails = $object['budgetDetail'];  //预算明细
			$budgetDetailDao = new model_finance_budget_budgetDetail();
			$budgetLogDao = new model_finance_budget_budgetLog();
			foreach($budgetDetails as $key=>$val){
				$oldDetail = $budgetDetailDao->get_d($val['id']);
				foreach($val as $key2=>$val2){
					if($key2!='isProvinceVisible'&&$val2!=$oldDetail[$key2]){ //省经理是否可见的修改 不做历史记录
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
     * 根据报销对象维护预算表中的相关字段
     * @param expenseId expense对象主键
     */
   function updateBudgetDetail($expenseId){
   		$expenseDao = new model_finance_expense_expense();
   		$expense = $expenseDao->get_d($expenseId);
		$detailType = $expense['DetailType'];
		$ProjectNo = $expense['ProjectNo'];
		try{
			$this->start_d();
			if($detailType==4&&$ProjectNo==''){ //售前费用(排除掉PK项目)
				$budgetDetailDao = new model_finance_budget_budgetDetail();
				$areaId = $expense['salesAreaId'];

				$areaId = $budgetDetailDao->checkArea($areaId); //判断区域是否属于广州贝讯

				$condition = array("areaId"=>$areaId,"year"=>date("Y",time()),"expenseTypeCode"=>"SQFY");
				$budgetDetail = $budgetDetailDao->find($condition);
				$final = $budgetDetail['final']; //区域总决算
				$amount = $expense['Amount'];
				$final = $final>0?$final:0;
				$budgetDetail['final']=floatval($final+$amount);
				$currentSeason = $budgetDetailDao->currentSeason(); //获取当前季度
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
	 * 费用报销审批不通过或者撤销审批，需从决算中减去不通过的费用
	 * @param expenseId expense对象主键
	 */
	function subFinal($expenseId){
		$expenseDao = new model_finance_expense_expense();
   		$expense = $expenseDao->get_d($expenseId);
		$detailType = $expense['DetailType'];
		try{
			$this->start_d();
			if($detailType==4){ //售前费用
				$budgetDetailDao = new model_finance_budget_budgetDetail();

				$areaId = $expense['salesAreaId'];
				$areaId = $budgetDetailDao->checkArea($areaId); //判断区域是否属于广州贝讯

				$condition = array("areaId"=>$areaId,"year"=>date("Y",time()),"expenseTypeCode"=>"SQFY");
				$budgetDetail = $budgetDetailDao->find($condition);
				$final = $budgetDetail['final']; //区域总决算
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
	 * 批量删除对象并删除从表相关数据
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
	 * 判断同年份的预算是否已存在
	 * @param year 年份 e.g:2015
	 * @param expenseCode 费用类型编码 e.g:SQFY
	 * @param expenseClass 费用小类  e.g:销售费用
	 * @return 1 已存在 0 不存在
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