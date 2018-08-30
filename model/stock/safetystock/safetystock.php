<?php
/**
 * @author huangzf
 * @Date 2012年8月20日 星期一 20:43:04
 * @version 1.0
 * @description:安全库存列表 Model层
 */
class model_stock_safetystock_safetystock extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_safetystock";
		$this->sql_map = "stock/safetystock/safetystockSql.php";
		parent::__construct ();
	}

	/**
	 *
	 * 库存最低库存警告物料清单模板显示
	 * @param $rows
	 */
	function showAnlyseItemList($rows) {
		$str = ""; //返回的模板字符串
		if (is_array ( $rows )) {
			$i = 0; //列表记录序号
			foreach ( $rows as $key => $val ) {
				$seNum = $i + 1;
				if (($seNum % 2) == "0") {
					$bgcolor = "#FFFFE0";
				} else {
					$bgcolor = "#fffef9";
				}

				$fillUpNum = $val ['minNum'] - $val ['actNum'];
				$lastPrice = $this->getLastPrice ( $val ['productId'] );
				$actNum=$this->getProActNum($val ['productId']);

				$str .= <<<EOT
				<tr align="center" bgcolor="$bgcolor">
                               <td >
                                    $seNum
                                </td>
                                <td>
									 $val[productCode]
									 <input type="hidden" value="$val[productCode]" id="productCode$i" />
                                </td>
                             	 <td >
									 $val[productName]
									 <input type="hidden" value="$val[productName]" id="productName$i" />
                                </td>
                                <td>
                                     $val[pattern]
                                     <input type="hidden" value="$val[pattern]" id="pattern$i" />
                                </td>
                               <td >
                               		 $val[unitName]
                               		 <input type="hidden" value="$val[unitName]" id="unitName$i" />
                                </td>
                                <td >
                                	 $actNum
                                </td>
                                <td >
                                    $val[minNum]
                                </td>
                                <td>
                                    $val[maxNum]
                                </td>
                                <td>
                                    $val[loadNum]
                                </td>
                                <td>
                                    $val[useFull]
                                </td>
                                <td>
                                    $val[moq]
                                </td>
                                <td>
                                    $lastPrice
                                </td>
                                <td>
                                    $val[purchUserName]
                                </td>
                                <td>
                                    $val[prepareDay]
                                </td>
                                <td>
                                    $val[minAmount]
                                </td>
                                <td>
                                    <input type="checkbox" class="txtshort" seNum="$i"  id="productId$i" />
                                </td>
                                <td>
                                    <input type="text" class="txtshort" value="$fillUpNum" id="fillNum$i" />
                                </td>
                </tr>
EOT;
				$i ++;
			}
		}
		return $str;
	}

	/**
	 * 添加对象
	 */
	function add_d($object) {
		return parent::add_d ( $object, true );
	}

	/**
	 * 根据主键修改对象
	 */
	function edit_d($object) {
		return parent::edit_d ( $object, true );
	}

	/**
	 *
	 * 获取物料的库存数量
	 * @param $productId
	 */
	function getProActNum($productId) {
		$inventDao = new model_stock_inventoryinfo_inventoryinfo ();
		$stockSystemDao = new model_stock_stockinfo_systeminfo ();
		$stockObj = $stockSystemDao->get_d ( 1 );
		$inventDao->searchArr = array ("stockId" => $stockObj ['salesStockId'], "productId" => $productId );
		$inventProArr = $inventDao->list_d ();
		if (is_array ( $inventProArr )) {
			return $inventProArr [0] ['actNum'];
		}
		{
			return "0";
		}
	}

	/**
	 *
	 * 分析获取即时库存低于最低库存的物料
	 */
	function findAnalyseItem() {
		$this->this_limit ['管理类型'] ;
		$condition=" and s.managerType in(".($this->this_limit ['管理类型']?$this->this_limit ['管理类型']:'null').")";
//		echo "select s.* from
//									oa_stock_safetystock s left join oa_stock_inventory_info  i
//													on(s.productId=i.productId) where i.stockId=3 and (i.actNum-s.minNum)<=0;
//		".$condition ;
//echo $condition;
		return $this->findSql ( "select s.* from
									oa_stock_safetystock s left join oa_stock_inventory_info  i
													on(s.productId=i.productId) where i.stockId=3 and (i.actNum-s.minNum)<=0
		".$condition );
	}

	/**
	 *
	 * 获取物料最新单价
	 * @param unknown_type $productId
	 */
	function getLastPrice($productId) {
		$orderEquDao = new model_purchase_contract_equipment ();
		$productDao = new model_stock_productinfo_productinfo ();
		$productObj = $productDao->get_d ( $productId );
		$orderObj = $orderEquDao->getHistoryInfo_d ( $productObj ['productName'], date ( "Y-m-d" ) );
		if (is_array ( $orderObj )) {
			return $orderObj ['applyPrice'];
		} else {
			return 0;
		}
	}

	/**
	 * 获取即使查询的脚本
	 */
	function getCountSql_d(){
		//获取sql
		$systeminfoDao = new model_stock_stockinfo_systeminfo();
		$stockInfo = $systeminfoDao->find();
		$sql = "select
				c.id ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.actNum ,c.minNum ,c.maxNum ,
				c.useFull ,c.moq ,c.price ,c.purchUserCode ,c.purchUserName ,c.prepareDay ,c.minAmount ,
				c.isFillUp ,c.fillNum ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,
				c.updateTime,c.managerType,i.saleStock,i.oldEquStock,if(p.onWayAmount is null,0,p.onWayAmount) as  loadNum,
				c.manageDept,c.manageDeptId
			from
				oa_stock_safetystock c
				left join
				(select
					productId,sum(if(stockId = '{$stockInfo['salesStockId']}',actNum,0)) as saleStock,sum(if(stockId = '{$stockInfo['outStockId']}',actNum,0)) as oldEquStock
				from
					oa_stock_inventory_info
				where stockId in ({$stockInfo['salesStockId']},{$stockInfo['outStockId']})
				group by productId) i on c.productId = i.productId
				left join (
					select productId,cast(if(SUM(p.amountAll-p.amountIssued) is null,0,SUM(p.amountAll-p.amountIssued)) as decimal (10, 0)) as onWayAmount
						from oa_purch_apply_equ p left join oa_purch_apply_basic c on c.id=p.basicId
						where c.isTemp=0 and c.state=7 and c.ExaStatus='完成' group by p.productId
				) p on c.productId = p.productId
			where 1";
		return $sql;
	}
}