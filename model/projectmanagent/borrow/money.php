<?php
/**
 * @author Administrator
 * @Date 2011年11月22日 17:08:26
 * @version 1.0
 * @description:借试用物料金额设置 Model层
 */
 class model_projectmanagent_borrow_money  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_borrow_money_config";
		$this->sql_map = "projectmanagent/borrow/moneySql.php";
		parent::__construct ();
	}


/***********************************客户借试用 金额控制********开始*********************************************/
	/**
	 * 初始化 客户借试用金额限制 表单
	 */
	function initTable($arr){
        $str = "";
		$i = 0;
		foreach ( $arr as $key => $val ) {
			$i ++;
			                 $str .= <<<EOT
					<tr>
					    <td>$i</td>
						<td><input type="text" class="readOnlyTxtShort" name="money[cusMoney][$i][areaName]" id="areaName$i"  value="$val[areaName]"/>
			                <input type="hidden" name="money[cusMoney][$i][areaCode]" id="areaCode$i" value="$val[id]" /></td>
			            <td><input type="text" class="txt" name="money[cusMoney][$i][maxMoney]" id="maxMoney$i" value="0" />
			                <input type="hidden" name="money[cusMoney][$i][borrowType]" value="客户" />
			                <input type="hidden" name="money[cusMoney][$i][controlType]" value="area" />
				</tr>
EOT;

		}
		return array ($str, $i );
	}

	/*
	 * 初始化处理方法
	 */
	 function initMoney_d($object){
 		 	try{
				$this->start_d();

	            $this->delete();
				$this->createBatch($object['cusMoney']);

				$this->commit_d();
	//			$this->rollBack();
				return true;
			}catch(exception $e){
				return false;
			}
	  }
     /*
      *修改金额
      */

     function editM_d($object){
     	try{
     		$this->start_d();
     		$sql = "update oa_borrow_money_config set maxMoney =  ".$object['maxMoney']." where id = ".$object['id']." ";
            $this->_db->query($sql);
            $this->commit_d();
            return true;
     	}catch(exception $e){
     		return false;
     	}
     }

/***********************************客户借试用 金额控制********结束*********************************************/
/***********************************员工借试用 金额控制********开始*********************************************/



/***********************************员工借试用 金额控制********结束*********************************************/

     /**
      * 获取某个区域的金额设置
      */
     function getMoneyByAreaId($areaId){
		$this->searchArr=array("areaCode"=>$areaId);
		$list= $this->list_d();
		if(count($list)>0){
			return $list[0];
		}else{
			return array("areaCode"=>$areaId,"maxMoney"=>0.00);
		}
     }

	 /**
	  * 获取账户金额设置
	  */
      function getMoneyByUserId($userId){
		$this->searchArr=array("userId"=>$userId);
		$list= $this->list_d();
		//如果有设置账户金额，优先使用
		if(!empty($list[0])){
			return $list[0];
		}else{
			$moneyArr=array("userId"=>$userId);
			//否则取部门跟角色中设置的最小金额
			$deptDao=new model_deptuser_dept_dept();
			$dept=$deptDao->getDeptByUserId($userId);
			$deptMoney=$this->getMoneyByDeptId($dept['id']);

			$userDao=new model_deptuser_user_user();
			$user=$userDao->getUserById($userId);
			$jobsId=$user['jobs_id'];
			$roleMoney=$this->getMoneyByRoleId($jobsId);
			$deptuserMoney=$deptMoney['deptuserMoney'];
			$roleMaxMoney=$roleMoney['maxMoney'];
			if(isset($deptuserMoney)&&isset($roleMaxMoney)){
				if($deptuserMoney<$roleMaxMoney){
					$moneyArr['maxMoney']=$deptuserMoney;
				}else{
					$moneyArr['maxMoney']=$roleMaxMoney;
				}
			}else if(isset($deptuserMoney)){
				$moneyArr['maxMoney']=$deptuserMoney;
			}else{
				$moneyArr['maxMoney']=$roleMaxMoney;
			}
			return $moneyArr;
		}
      }

      /**
	  * 获取部门金额设置
	  */
      function getMoneyByDeptId($deptId){
		$this->searchArr=array("deptId"=>$deptId);
		$list= $this->list_d();
		return $list[0];
      }


      /**
	  * 获取角色金额设置
	  */
      function getMoneyByRoleId($roleId){
		$this->searchArr=array("roleId"=>$roleId);
		$list= $this->list_d();
		return $list[0];
      }

 }
?>

