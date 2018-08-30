<?php
/**
 * @author Administrator
 * @Date 2012年2月14日 9:28:54
 * @version 1.0
 * @description:借试用初始化数据表 Model层
 */
 class model_projectmanagent_borrow_initialize  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_borrow_initialize";
		$this->sql_map = "projectmanagent/borrow/initializeSql.php";
		parent::__construct ();
	}

/**
 * 更新初始化数据的归还数量
 * @param $userId 借用人ID
 * @param $productId 物料ID
 * @param $backNum 归还数量
 */
 function updateInitializeBackNum($userId,$productId,$backNum){
           $findUserSql = "select id from oa_borrow_initialize where applyUserId = '$userId' and productId = '$productId'";
           $findUser =  $this->_db->getArray($findUserSql);

 		   if(empty($findUser)){
 		   	   throw new Exception ( "归还数据未找到对应的初始化信息" );
 		   }else{
 		   	   $findNumSql = "select(number-backNum) as Num from oa_borrow_initialize where applyUserId = '$userId' and productId = '$productId'";
               $findNum =  $this->_db->getArray($findNumSql);
               if($backNum > $findNum[0]['Num']){
               	   throw new Exception ( "归还数量已大于初始化借用未归还数量" );
               }else{
	              $sql = "update ".$this->tbl_name." set backNum = backNum + ".$backNum." where applyUserId = '$userId' and productId = '$productId'";
	              $this->_db->query($sql);
	 		   }
 		   }
       }
 /**
  * 借用初始化数据归还 反审
  */
function reUpdateInitializeBackNum($userId,$productId,$backNum){
	       $backNum=$backNum*(-1);
              $sql = "update ".$this->tbl_name." set backNum = backNum + ".$backNum." where applyUserId = '$userId' and productId = '$productId'";
              $this->_db->query($sql);

 }
/**********************************借试用报表 初始化数据*****************************************************/
     function initializeReportInfo(){
		 $rows = $this->pageBySqlId('select_default');
		 return $rows;
     }

   function initializeReportInfoT($searchArr){
         $this->searchArr['applyUserId'] = $searchArr;
		 $rows = $this->pageBySqlId('select_default');

		 return $rows;
     }
/**********************************借试用报表 初始化数据+******END*************************************************/

	/**
	 * 更新初始化数据的归还数量
	 */
	function renewInitBorrowEquNum(){
		try{
			$this->start_d();
			$this->query("delete from oa_borrow_r_allocat");
			$this->query("update oa_borrow_equ set backNum=0 where borrowId in(select id from oa_borrow_borrow where initTip=1)");
			$sql="select a.id,pickCode,customerId,ai.productId,ai.allocatNum from oa_stock_allocation a " .
					"inner join oa_stock_allocation_item  ai on(ai.mainId=a.id) where ai.relDocId=0 and toUse='CHUKUGUIH'  " .
					"and a.auditDate>'2012-03-01' and docStatus='YSH'";
			$allocatArr=$this->findSql($sql);
			foreach($allocatArr as $key=>$val){
				$allocatId=$val['id'];
				$pickCode=$val['pickCode'];
				$productId=$val['productId'];
				$allocatNum=$val['allocatNum'];
				$customerId=$val['customerId'];
				$sqlplus="";
				if(!empty($customerId)){
					$sqlplus.=" and customerId=$customerId";
				}
				$sql="select e.id ,number,backNum,b.createId,productId,e.borrowId from oa_borrow_equ e left join oa_borrow_borrow b " .
						"on b.id=e.borrowId where b.initTip=1   and " .
						"createId='$pickCode' and productId=$productId $sqlplus and number>backNum";
				$equArr=$this->findSql($sql);
				$rAllocatNum=$allocatNum;
				foreach($equArr as $k=>$v){
					$number=$v['number'];
					$backNum=$v['backNum'];
					$equId=$v['id'];
					$borrowId=$v['borrowId'];
					$rBackNum=$number-$backNum;
					if($rAllocatNum>$rBackNum){
						$sql="update oa_borrow_equ set backNum=$number where id=$equId";
						$this->query($sql);
						$sql="insert into oa_borrow_r_allocat (borrowId,borrowEquId,allocatId,allocatNum)values($borrowId,$equId,$allocatId,$rBackNum)";
						$this->query($sql);

						$rAllocatNum-=$rBackNum;
					}else{
						$sql="update oa_borrow_equ set backNum=backNum+$rAllocatNum where id=$equId";
						$this->query($sql);
						$sql="insert into oa_borrow_r_allocat (borrowId,borrowEquId,allocatId,allocatNum)values($borrowId,$equId,$allocatId,$rAllocatNum)";
						$this->query($sql);
						break;
					}
				}


			}
			$this->commit_d();
			return 1;
		}catch(Exception $e){

			$this->rollBack();
			return $e->getMessage();

		}


	}

 }
?>