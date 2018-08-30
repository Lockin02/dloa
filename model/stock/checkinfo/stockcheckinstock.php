<?php
/**
 * @author Administrator
 * @Date 2010��12��21�� 20:57:46
 * @version 1.0
 * @description:�̵���� Model��
 */
 class model_stock_checkinfo_stockcheckinstock  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_check_instock";
		$this->sql_map = "stock/checkinfo/stockcheckinstockSql.php";
		parent::__construct ();
	}
	/**
	 * @desription �����̵�ID���Ҳ�Ʒ��Ϣ
	 * @param tags
	 * @date 2010-12-22 ����02:40:11
	 * @qiaolong
	 */
	function getCheckProductInfo_d ($checkId) {
		$productDao = new model_stock_checkinfo_stockinstocklist();
		$productDao->searchArr['checkId'] = $checkId;
		return $rows = $productDao->listBySqlId('select_default');
	}
	/**
	 * @desription �̵��Ʒ�б���ʾ
	 * @param tags
	 * @date 2010-12-22 ����02:45:41
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
	 * @desription �̵���Ϣ�޸�ҳ���Ʒ�б���ʾ����
	 * @param tags
	 * @date 2010-12-23 ����09:24:43
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
							<td align="center"><img src='images/closeDiv.gif' onclick='mydel(this,"productslist")' title='ɾ����'></td>
						</tr>
EOT;
			}
		}
		return $str;
	 }
	 /**
	 * @desription �ҵ����������б��ȡ���ݷ���
	 * @param tags
	 * @date 2010-12-22 ����04:39:45
	 * @qiaolong
	 */
	function mychecktaskinfo_d ($auditNameId) {
		$this->searchArr['auditUserId']=$auditNameId;
		$this->searchArr['ExaStatus']='��������';
		return $this->pageBySqlId('select_default');

	}
	/**
	 * @desription �̵���Ϣ���淽��
	 * @param tags
	 * @date 2010-12-23 ����06:35:28
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
	 * �̵���Ϣ�޸ı��淽��
	 */
	function edit_d($stockcheckinstock) {
		try {
			$this->start_d();
			$this->updateObjWithFile($stockcheckinstock['id']);
			parent :: edit_d($stockcheckinstock,true);
			$checkproductDao = new model_stock_checkinfo_stockinstocklist();
			//ɾ�����������Ϣ
			$checkproductDao->deleteByHardWareId($stockcheckinstock['id']);
			//�������������Ϣ
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
	 * @desription �����̵㵥�Ż�ȡ��ز�Ʒ����
	 * @param tags
	 * @date 2010-12-24 ����11:11:17
	 * @qiaolong
	 */
	function getProductInfo ($id) {
		$productDao = new model_stock_checkinfo_stockinstocklist();
		$productDao->searchArr['checkId']=$id;
		return $rows = $productDao->pageBySqlId('select_default');
	}

	/**�̵����
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
			//��ȡ�̵��Ʒ
			$arr=$this->getProductInfo($object['id']);

			//���ݲֿ�ID����ȡ��Ʒ
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
//						throw new Exception('���û�д����Ʒ��');
//					}
//				}
//			}
			if(!$arr){
				throw new Exception('���û�д����Ʒ��');
			}
			$this->updateField($condiction,'ExaStatus','�����');
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