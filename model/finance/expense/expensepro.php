<?php

/**
 * @author Show
 * @Date 2012年9月28日 星期五 11:07:32
 * @version 1.0
 * @description:报销申请费用明细(项目报销) Model层
 */
class model_finance_expense_expensepro extends model_base {

	function __construct() {
		$this->tbl_name = "cost_detail_project";
		$this->sql_map = "finance/expense/expenseproSql.php";
		parent :: __construct();
	}

	/**
	 * 根据传入的对象数组自动进行新增，修改，删除(主要用于解决主从表中对从表对象的批量操作)
	 * 判断规则：
	 * 1.如果id为空且isDelTag属性为1（这种情况属于如界面上添加后删除情况,后台啥都不做）
	 * 2.如果id为空，则新增
	 * 3.如果isDelTag属性为1，则删除
	 * 4.否则修改
	 * @param Array $objs
	 */
	function saveDelBatch($objs) {
		//实例化发票明细
		$expenseinvDao = new model_finance_expense_expenseinv();

		try{
			$returnObjs = array ();
			foreach ( $objs as $key => $val ) {
				$isDelTag=isset($val ['isDelTag'])?$val ['isDelTag']:NULL;
				if ((empty ( $val ['ID'] ) && $isDelTag== 1)) {

				} else if (empty ( $val ['ID'] )) {
					//如果新增数据金额为0，干掉
					if($val['CostMoney'] == 0){
						continue;
					}
					$expenseinv = $val['expenseinv'];
					unset($val['expenseinv']);

					//先新增费用部分
					$id = $this->add_d ( $val );
					$val ['ID'] = $id;
					array_push ( $returnObjs, $val );

					//再新增发票部分
					$addArr = array(
						'BillDetailID' =>$id,
						'BillAssID' =>$val['AssID'],
						'BillNo' =>''
					);
					$expenseinv = util_arrayUtil::setArrayFn($addArr,$expenseinv);
					$expenseinvDao->saveDelBatch($expenseinv);
				} else if ($isDelTag == 1) {
					//先删除费用
					$this->deletes ( $val ['ID'] );

					//再删除发票
					$expenseinvDao->deleteByDetailId($val['ID']);
				} else {
					//如果编辑金额为0，干掉
					if($val['CostMoney'] == 0){
						//先删除费用
						$this->deletes ( $val ['ID'] );

						//再删除发票
						$expenseinvDao->deleteByDetailId($val['ID']);
					}else{

						//出去发票信息
						$expenseinv = $val['expenseinv'];
						unset($val['expenseinv']);
						//先编辑费用部分
//    					echo "<pre>";
//						print_r($val);
						$this->edit_d ( $val );
						array_push ( $returnObjs, $val );

						//再编辑发票明细部分
						$addArr = array(
							'BillDetailID' => $val['ID'],
							'BillAssID' =>$val['AssID'],
							'BillNo' =>''
						);
						$expenseinv = util_arrayUtil::setArrayFn($addArr,$expenseinv);
						$expenseinvDao->saveDelBatch($expenseinv);
					}
				}
			}
			return $returnObjs;
		}catch(exception $e){
			echo $e->getMessage();
			throw $e;
			return false;
		}
	}

	/**
	 * 根据主键修改记录
	 */
	function updateById($object) {
		$condition = array ("ID" => $object ['ID'] );
		return $this->update ( $condition, $object );
	}
}
?>