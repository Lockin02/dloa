<?php
/*合同采购model
 * Created on 2011-3-11
 *@author can
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
header("Content-type: text/html; charset=gb2312");

//引入接口
include_once WEB_TOR . 'model/purchase/external/planBasic.php';

class model_purchase_external_contract extends planBasic {

	private $externalDao; //外部对象dao接口
	private $externalEquDao; //外部对象设备dao接口

	function __construct() {
		$this->externalDao = new model_contract_contract_contract();
		$this->externalEquDao = new model_contract_contract_equ();

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
//		echo "<Pre>";
//		print_R($equs);
		$j = 0;
		if (is_array ( $equs )) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			$baseDao=new model_base();
			$datadictArr = $baseDao->getDatadicts ( "CGZJSX" );
			foreach ( $equs as $key => $val ) {
				$j = $i + 1;
				$contRemain = $val['number'] - $val['executedNum'];
				if( $contRemain <= 0 ){
					continue;
				}
				$executedNum = $val['executedNum']*1;
				$contNum = $val['number']*1;
				$issuedPurNum = $val['issuedPurNum']*1;
				$dateIssued = date("Y-m-d");
				$issuedNum = $val['number']*1 - $val['executedNum']*1 - $issuedPurNum;
				$exeNum = $val['exeNum']*1;

				$checkTypeStr=$baseDao->getDatadictsStr( $datadictArr ['CGZJSX'], $val['qualityCode'] );
				$str .= <<<EOT
					<tr><td>$j</td>
						<td>
							<input type="hidden" id="purchType$j" name="basic[equipment][$j][purchType]" value="{purchType}"/>
							<input type="text" id="productNumb$j" class='txtshort' name="basic[equipment][$j][productNumb]" value="$val[productCode]"/></td>
						<td>
							<input type="hidden" id="productId$j" name="basic[equipment][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="basic[equipment][$j][productName]" value="$val[productName]" class='txt'/></td>
						<td>
							<input type="text" id="pattem$j" name="basic[equipment][$j][pattem]" value="$val[productModel]" class='readOnlyTxt' readonly='readonly'/></td>
						<td>
							<input type="text" id="unitName$j" name="basic[equipment][$j][unitName]" value="$val[unitName]" class='readOnlyTxtShort'/></td>
						<td>
								<select  name="basic[equipment][$j][qualityCode]">$checkTypeStr</select>
							</td>
						<td>
						 <select name="basic[equipment][$j][testType]" id="testType$j" class="txtshort">
						  <option value="0">全检</option>
						  <option value="1">免检</option>
						  <option value="2">抽检</option>
			             </select>
			             </td>
						<td>
							<font color=green id="exeNum$j">$exeNum</font>
						</td>
						<td id="contNum$j">
							<font color=green>$contNum</font>
							<input type="hidden" id="contNum$j" value='$contNum' class="txtmiddle"/>
						</td>
                        <td>
							<font color=green>$executedNum</font>
                        </td>
						<td id="issuedPurNum$j">
							<font color=green>$issuedPurNum</font>
							<input type="hidden" id="remainNum$j" value='$issuedNum' class="txtmiddle"/>
							<input type="hidden" id="issuedPurNum$j" value='$issuedPurNum' class="txtmiddle"/>
						</td>
						<td>
							<input type="text" class="amount txtshort" id="amountAll$j" name="basic[equipment][$j][amountAll]" value="$issuedNum" onblur="checkThis($j)">
						</td>
						<td>
							<input type="text" id="dateIssued$j" name="basic[equipment][$j][dateIssued]"  readonly  class='readOnlyTxtShort' value="$dateIssued"/>
						</td>
						<td>
							<input type="text" id="dateHope$j" name="basic[equipment][$j][dateHope]" onfocus="WdatePicker()" readonly  class='txtshort'/>
						</td>
						<td>
							<input type="text" id="remark" name="basic[equipment][$j][remark]" class='txt'/>
								<input type="hidden" name="basic[equipment][$j][applyEquId]" value="$val[id]" />
								<input type="hidden" name="basic[equipment][$j][equObjAssId]" value="$val[id]" />
								<input type="hidden" name="basic[equipment][$j][uniqueCode]" value="$val[uniqueCode]" />
						</td>
						<td>
							<img src='images/closeDiv.gif' onclick="mydel(this,'mytable');" title='删除行'>
						</td>
					</tr>
EOT;
				$i ++;
			}

		}
		$str .='<input type="hidden" id="rowNum" value="'.$j.'"/>';
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
	 * @desription 通过物料Id获取产品数据
	 * @param $equIds
	 */
	function getItemsByEquIds($equIds) {
		$equList = $this->externalEquDao->getequInfoByids($equIds);
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
					'issuedPurNum'=>$paramArr ['issuedPurNum'],
					'equObjAssId'=>$paramArr['equObjAssId']
					);
		//TODO:下达采购计划时，对订单的处理
		if($paramArrs['equObjAssId']){
			return $this->externalEquDao->updateAmountIssued( $paramArrs['equObjAssId'] , $paramArrs['issuedPurNum']);
		}

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
					msg('下达成功');
				}else{
					msgGo('物料信息不完整，没有物料或数量为0，下达失败',"?model=projectmanagent_order_order&action=toLockStockTab&id="+$_POST['basic']['sourceId']+"&perm=view");
				}
			}else{		//从统一接口下面采购计划后的跳转页面
				parent::toShowPage($id);
			}
	}




}
?>

