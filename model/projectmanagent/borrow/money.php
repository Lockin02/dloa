<?php
/**
 * @author Administrator
 * @Date 2011��11��22�� 17:08:26
 * @version 1.0
 * @description:���������Ͻ������ Model��
 */
 class model_projectmanagent_borrow_money  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_borrow_money_config";
		$this->sql_map = "projectmanagent/borrow/moneySql.php";
		parent::__construct ();
	}


/***********************************�ͻ������� ������********��ʼ*********************************************/
	/**
	 * ��ʼ�� �ͻ������ý������ ��
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
			                <input type="hidden" name="money[cusMoney][$i][borrowType]" value="�ͻ�" />
			                <input type="hidden" name="money[cusMoney][$i][controlType]" value="area" />
				</tr>
EOT;

		}
		return array ($str, $i );
	}

	/*
	 * ��ʼ��������
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
      *�޸Ľ��
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

/***********************************�ͻ������� ������********����*********************************************/
/***********************************Ա�������� ������********��ʼ*********************************************/



/***********************************Ա�������� ������********����*********************************************/

     /**
      * ��ȡĳ������Ľ������
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
	  * ��ȡ�˻��������
	  */
      function getMoneyByUserId($userId){
		$this->searchArr=array("userId"=>$userId);
		$list= $this->list_d();
		//����������˻�������ʹ��
		if(!empty($list[0])){
			return $list[0];
		}else{
			$moneyArr=array("userId"=>$userId);
			//����ȡ���Ÿ���ɫ�����õ���С���
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
	  * ��ȡ���Ž������
	  */
      function getMoneyByDeptId($deptId){
		$this->searchArr=array("deptId"=>$deptId);
		$list= $this->list_d();
		return $list[0];
      }


      /**
	  * ��ȡ��ɫ�������
	  */
      function getMoneyByRoleId($roleId){
		$this->searchArr=array("roleId"=>$roleId);
		$list= $this->list_d();
		return $list[0];
      }

 }
?>

