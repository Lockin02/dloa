<?php
/*订单采购model
 * Created on 2011-3-11
 *@author can
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
header("Content-type: text/html; charset=gb2312");

//引入接口
include_once WEB_TOR . 'model/purchase/external/planBasic.php';

class model_purchase_external_orderpurchase extends planBasic {

	private $externalDao; //外部对象dao接口
	private $externalEquDao; //外部对象设备dao接口

	function __construct() {
		$this->externalDao = new model_projectmanagent_order_order();
		$this->externalEquDao = new model_projectmanagent_order_orderequ();

		//调用初始化对象关联类
		$this->objass = new model_common_objass();
	}

	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------------------*
	 **************************************************************************************************/
	/**
	 * @desription 统一接口添加采购计划-显示列表
	 * @param $equs
	 * @param $mianRows
	 * @date 2010-12-17 下午05:17:09
	 */
	function showAddList($equs,$mianRows) {
       if(is_array($equs)){
		$i = 0;
		foreach ($equs as $key => $val) {
			if($val[issuedNum]>0){
				++ $i;
	            $YMD = date("Y-m-d");
				$str .=<<<EOT
						<tr height="28" align="center">
							<td>
								$i
							</td>
							<td>
								 $val[productNo]
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								<input type="text" class="amount txtshort" name="basic[equipment][$key][amountAll]" value="$val[issuedNum]" onblur="addPlan(this);">
								<input type="hidden" name="amountAll" value="$val[issuedNum]" >
							</td>
							<td>
								<input type="text" class="txtshort" name="basic[equipment][$key][dateIssued]" value="$YMD" onfocus="WdatePicker()" readonly >
							</td>
							<td>
								<input type="text" class="txtshort" name="basic[equipment][$key][dateHope]" value="$val[projArraDate]" onfocus="WdatePicker()" readonly >
								<input type="hidden" name="basic[equipment][$key][equObjAssId]" value="$val[id]" />
								<input type="hidden" name="basic[equipment][$key][uniqueCode]" value="$val[uniqueCode]" />
								<input type="hidden" name="basic[equipment][$key][productNumb]" value="$val[productNo]" />
								<input type="hidden" name="basic[equipment][$key][productName]" value="$val[productName]" />
								<input type="hidden" name="basic[equipment][$key][productId]" value="$val[productId]" />
								<input type="hidden" name="basic[equipment][$key][purchType]" value="order" />
							</td>
							<td>
								<textarea  name="basic[equipment][$key][remark]"></textarea>
							</td>
							<td>
							<img src="images/closeDiv.gif" onclick="mydel(this , 'mytable')" title="删除行" />
						    </td>
						</tr>

EOT;
			}
		}
		if($i==0){
			$str="<tr align='center'><td colspan='50'>暂无采购计划清单信息</td></tr>";
		}
		$str .=<<<EOT
					<input type="hidden" name="basic[objAssId]" value="$mianRows[id]" />
					<input type="hidden" name="basic[objAssName]" value="$mianRows[orderName]" />
					<input type="hidden" name="basic[objAssType]" value="order" />
					<input type="hidden" name="basic[objAssCode]" value="$mianRows[orderCode]" />
					<input type="hidden" name="basic[equObjAssType]" value="order" />
EOT;
	}else{
		$str="<tr align='center'><td colspan='50'>暂无采购计划清单信息</td></tr>";
	}
		return $str;
	}

	/***************************************************************************************************
	 * ------------------------------以下接口方法,可供其他模块调用---------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription 通过订单Id获取产品数据
	 * @param $orderId
	 * @date 2010-12-17 下午05:05:57
	 */
	function getItemsByParentId($orderId) {
		$equList = $this->externalEquDao->getDetail_d($orderId);
		$lockDao=new model_stock_lock_lock();
		foreach( $equList as $key => $val ){
			//拿到库存锁定数量
			$lockNum=$lockDao->getEquStockLockNum($val['id']);

			//计算出可下达的采购数量
			$equList[$key]['issuedNum'] = $equList[$key]['number']  -$lockNum- $equList[$key]['issuedPurNum'] - $equList[$key]['issuedProNum'];
		}
		return $equList;
	}

	/**
	 * 根据采购类型的ID，获取其信息
	 *
	 * @param $id
	 * @return return_type
	 */
	function getInfoList ($id) {
		$mainRows=$this->externalDao->get_d($id);
		$mainRows['sourceName']=$mainRows['orderName'];
		$mainRows['sourceCode']=$mainRows['orderCode'];
		return $mainRows;
	}

	/**
	 * 根据不同的类型采购计划，进行业务处理
	 *
	 * @param $paramArr
	 */
	function dealInfoAtPlan ($paramArr){
		$paramArrs=array(
					'uniqueCode'=>$paramArr ['uniqueCode'],
					'issuedPurNum'=>$paramArr ['issuedPurNum']
					);
		//TODO:下达采购计划时，对订单的处理
		return $this->externalEquDao->updateEquipmentQuantity( $paramArrs , 'add');
	}

	/**更新已下达采购数量
	*author can
	*2011-3-22
	*/
	function updateAmountIssued($id,$issuedNum,$lastIssueNum){
		return $this->externalEquDao->updateAmountIssued($id,$issuedNum,$lastIssueNum);
	}

	/**
	 * 下达采购计划后页面原跳转
	 *
	 * @param tags
	 * @return return_type
	 */
	function toShowPage ($id,$type=null) {
			if($type){    //从订单处理页面下达采购计划后的页面跳转
				if($id){
					msgGo('下达成功',"?model=projectmanagent_order_order&action=toLockStockTab&id="+$_POST['basic']['sourceId']+"&perm=view");
				}else{
					msgGo('物料信息不完整，没有物料或数量为0，下达失败',"?model=projectmanagent_order_order&action=toLockStockTab&id="+$_POST['basic']['sourceId']+"&perm=view");
				}
			}else{		//从统一接口下面采购计划后的跳转页面
				parent::toShowPage($id);
			}
	}




}
?>

