<?php
/**
 * @author Administrator
 * @Date 2010年12月21日 20:57:46
 * @version 1.0
 * @description:盘点入库 Model层
 */
 class model_stock_checkinfo_stockcheckinstock  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_check_instock";
		$this->sql_map = "stock/checkinfo/stockcheckinstockSql.php";
		parent::__construct ();
	}
	/**
	 * @desription 根据盘点ID查找产品信息
	 * @param tags
	 * @date 2010-12-22 下午02:40:11
	 * @qiaolong
	 */
	function getCheckProductInfo_d ($checkId) {
		$productDao = new model_stock_checkinfo_stockinstocklist();
		$productDao->searchArr['checkId'] = $checkId;
		return $rows = $productDao->listBySqlId('select_default');
	}
	/**
	 * @desription 盘点产品列表显示
	 * @param tags
	 * @date 2010-12-22 下午02:45:41
	 * @qiaolong
	 */
	function showProductInfoList ($rows) {
		$str = "";
		if ($rows) {
			$i = 0;
			foreach ( $rows as $key => $val ) {
				$i ++;
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .= <<<EOT
						<tr  class="$classCss">
							<td align="center">$i</td>
							<td align="center">$val[typecode]</td>
							<td align="center">$val[proType]</td>
							<td align="center">$val[sequence]</td>
							<td align="center">$val[productName]</td>
							<td align="center">$val[adjust]</td>
						</tr>
EOT;
			}
		}
		return $str;
	 }
	 /**
	 * @desription 盘点信息修改页面产品列表显示方法
	 * @param tags
	 * @date 2010-12-23 上午09:24:43
	 * @qiaolong
	 */
	function showProductInfoEdit ($rows) {
		$str = "";
		if (is_array ( $rows )) {
			$i = 0;
			foreach ( $rows as $key => $val ) {
				$i++;
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .= <<<EOT
						<tr  class="$classCss">
							<td align="center">$i</td>
							<td align="center"><input type="text" class="readOnlyTxt"  value="$val[typecode]" name="stockcheckinstock[productsdetail][$i][typecode]" id="typecode" size="15" readonly></td>
							<td align="center"><input type="text" class="readOnlyTxt"  value="$val[proType]" name="stockcheckinstock[productsdetail][$i][proType]" id="proType" size="15" readonly></td>
							<td align="center"><input type="text" class="readOnlyTxt"  value="$val[sequence]" name="stockcheckinstock[productsdetail][$i][sequence]" id="sequence" size="15" readonly></td>
							<td align="center"><input type="text" value="$val[productName]" name="stockcheckinstock[productsdetail][$i][productName]" id="productName" size="15"  readonly>
											   <input type="hidden" value="$val[productId]" name="stockcheckinstock[productsdetail][$i][productId]" id="productId" size="15" ></td>
							<td align="center"><input type="text" value="$val[adjust]" name="stockcheckinstock[productsdetail][$i][adjust]" id="adjust" size="15"></td>
							<td align="center"><img src='images/closeDiv.gif' onclick='mydel(this,"productslist")' title='删除行'></td>
						</tr>
EOT;
			}
		}
		return $str;
	 }
	 /**
	 * @desription 我的审批任务列表获取数据方法
	 * @param tags
	 * @date 2010-12-22 下午04:39:45
	 * @qiaolong
	 */
	function mychecktaskinfo_d ($auditNameId) {
		$this->searchArr['auditUserId']=$auditNameId;
		$this->searchArr['ExaStatus']='部门审批';
		return $this->pageBySqlId('select_default');

	}
	/**
	 * @desription 盘点信息保存方法
	 * @param tags
	 * @date 2010-12-23 下午06:35:28
	 * @qiaolong
	 */
	function add_d($stockcheckinstock) {
		try{
			$this->start_d();
			$id = parent :: add_d($stockcheckinstock,true);
			$this->updateObjWithFile($id);
			if(is_array($stockcheckinstock['productsdetail'])){
				$checkproductDao = new model_stock_checkinfo_stockinstocklist();
				foreach($stockcheckinstock['productsdetail'] as $key => $value){
					if (!empty ($value['typecode'])) {
						$value['checkId'] = $id;
						$checkproductDao->add_d($value,true);
					}
				}
			}
			$this->commit_d();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}

	}
	/**
	 * 盘点信息修改保存方法
	 */
	function edit_d($stockcheckinstock) {
		try {
			$this->start_d();
			$this->updateObjWithFile($stockcheckinstock['id']);
			parent :: edit_d($stockcheckinstock,true);
			$checkproductDao = new model_stock_checkinfo_stockinstocklist();
			//删除所有配件信息
			$checkproductDao->deleteByHardWareId($stockcheckinstock['id']);
			//重新新增配件信息
			if (is_array($stockcheckinstock['productsdetail'])) {
				foreach ($stockcheckinstock['productsdetail'] as $key => $value) {
					if (!empty ($value['typecode'])) {
						$value['checkId'] = $stockcheckinstock['id'];
						$checkproductDao->add_d($value);
					}
				}
			}

			$this->commit_d();
			return $stockcheckinstock;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}
	/**
	 * @desription 根据盘点单号获取相关产品数量
	 * @param tags
	 * @date 2010-12-24 上午11:11:17
	 * @qiaolong
	 */
	function getProductInfo ($id) {
		$productDao = new model_stock_checkinfo_stockinstocklist();
		$productDao->searchArr['checkId']=$id;
		return $rows = $productDao->pageBySqlId('select_default');
	}

	/**盘点入库
	*author can
	*2011-1-20
	*/
	function intoShockInfo_d($object){
		try{
			$this->start_d();
//			$this->searchArr['id']=$object['id'];
			$condiction=array('id'=>$object['id']);
//			$rows=$this->listBySqlId('select_default');
			$rows=$this->get_d($object['id']);
			//获取盘点产品
			$arr=$this->getProductInfo($object['id']);

			//根据仓库ID，获取产品
//			$stockProDao=new model_stock_inventoryinfo_inventoryinfo();
//			$stockPros=$stockProDao->getInfoFromIds_d($rows['stockId']);

			$stockCode=	$rows['stockCode'];
//			foreach($stockPros as $spKey=>$skVal){
//				foreach($arr as $key=>$val){
//					if($val['productId']==$skVal['productId']){
//						echo"***";
//						$flag=true;
//					}
//					else{
//						echo 11111;
//						$flag=false;
//						throw new Exception('库存没有此类产品！');
//					}
//				}
//			}
			if(!$arr){
				throw new Exception('库存没有此类产品！');
			}
			$this->updateField($condiction,'ExaStatus','已入库');
			foreach($arr as $key=>$val){
				if($object['checkType']=='PDPK'){
					$adjust=-$val['adjust'];
				}else{
					$adjust=$val['adjust'];
				}
				$productId=$val['productId'];
			    $sql = "update oa_stock_inventory_info  set exeNum=exeNum+$adjust,actNum=actNum+$adjust where stockCode='$stockCode' and productId='$productId'";

				$this->query($sql);
			}


			$this->commit_d();
			return true;
		}catch (Exception $e) {
			$this->rollBack();
			return null;
		}

	}
 }
?>