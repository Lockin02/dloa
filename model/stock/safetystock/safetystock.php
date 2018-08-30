<?php
/**
 * @author huangzf
 * @Date 2012��8��20�� ����һ 20:43:04
 * @version 1.0
 * @description:��ȫ����б� Model��
 */
class model_stock_safetystock_safetystock extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_safetystock";
		$this->sql_map = "stock/safetystock/safetystockSql.php";
		parent::__construct ();
	}

	/**
	 *
	 * �����Ϳ�澯�������嵥ģ����ʾ
	 * @param $rows
	 */
	function showAnlyseItemList($rows) {
		$str = ""; //���ص�ģ���ַ���
		if (is_array ( $rows )) {
			$i = 0; //�б��¼���
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
	 * ��Ӷ���
	 */
	function add_d($object) {
		return parent::add_d ( $object, true );
	}

	/**
	 * ���������޸Ķ���
	 */
	function edit_d($object) {
		return parent::edit_d ( $object, true );
	}

	/**
	 *
	 * ��ȡ���ϵĿ������
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
	 * ������ȡ��ʱ��������Ϳ�������
	 */
	function findAnalyseItem() {
		$this->this_limit ['��������'] ;
		$condition=" and s.managerType in(".($this->this_limit ['��������']?$this->this_limit ['��������']:'null').")";
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
	 * ��ȡ�������µ���
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
	 * ��ȡ��ʹ��ѯ�Ľű�
	 */
	function getCountSql_d(){
		//��ȡsql
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
						where c.isTemp=0 and c.state=7 and c.ExaStatus='���' group by p.productId
				) p on c.productId = p.productId
			where 1";
		return $sql;
	}
}