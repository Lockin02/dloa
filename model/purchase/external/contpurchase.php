<?php
/*�����ɹ�model
 * Created on 2011-3-11
 *@author can
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
header("Content-type: text/html; charset=gb2312");

//����ӿ�
include_once WEB_TOR . 'model/purchase/external/planBasic.php';

class model_purchase_external_contpurchase extends planBasic {

	private $externalDao; //�ⲿ����dao�ӿ�
	private $externalEquDao; //�ⲿ�����豸dao�ӿ�

	function __construct() {
		$this->externalDao = new model_projectmanagent_order_order();
		$this->externalEquDao = new model_projectmanagent_order_orderequ();

		//���ó�ʼ�����������
		$this->objass = new model_common_objass();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------------------*
	 **************************************************************************************************/
	/**
	 * @desription ͳһ�ӿ���Ӳɹ��ƻ�-��ʾ�б�
	 * @param $equs
	 * @param $mianRows
	 * @date 2010-12-17 ����05:17:09
	 */
	function showAddList($equs,$mianRows) {
		$j = 0;
		if (is_array ( $equs )) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $equs as $key => $val ) {
				$j = $i + 1;
				$contNum = $val['number']*1;
				$issuedPurNum = $val['issuedPurNum']*1;
				$dateIssued = date("Y-m-d");
				$issuedNum = $val['issuedNum']*1;
				$exeNum = $val['exeNum']*1;
				if( $issuedNum <= 0 ){
					continue;
				}
				$str .= <<<EOT
					<tr><td>$j</td>
						<td>
							<input type="hidden" id="purchType$j" name="basic[equipment][$j][purchType]" value="{purchType}"/>
							<input type="text" id="productNumb$j" class='txtshort' name="basic[equipment][$j][productNumb]" value="$val[productNo]"/></td>
						<td>
							<input type="hidden" id="productId$j" name="basic[equipment][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="basic[equipment][$j][productName]" value="$val[productName]" class='txtmiddle'/></td>
						<td>
							<input type="text" id="pattem$j" name="basic[equipment][$j][pattem]" value="$val[productModel]" class='readOnlyTxtItem' readonly='readonly'/></td>
						<td>
							<font color=green id="exeNum$j">$exeNum</font>
						</td>
						<td id="contNum$j">
							<font color=green>$contNum</font>
							<input type="hidden" id="contNum$j" value='$contNum' class="txtmiddle"/>
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
							<input type="text" id="unitName$j" name="basic[equipment][$j][unitName]" value="$val[unitName]" class='txtshort'/></td>
						<td>
							<input type="text" id="dateIssued$j" name="basic[equipment][$j][dateIssued]" onfocus="WdatePicker()" readonly  class='txtshort' value="$dateIssued"/>
						</td>
						<td>
							<input type="text" id="dateHope$j" name="basic[equipment][$j][dateHope]" onfocus="WdatePicker()" readonly  class='txtshort'/>
						</td>
						<td>
							<input type="text" id="remark" name="basic[equipment][$j][remark]" class='txt'/>
								<input type="hidden" name="basic[equipment][$j][applyEquId]" value="$val[id]" />
								<input type="hidden" name="basic[equipment][$j][equObjAssId]" value="$val[id]" />
								<input type="hidden" name="basic[equipment][$j][uniqueCode]" value="$val[uniqueCode]" />
								<input type="hidden" id="purchType$j" name="basic[equipment][$j][purchType]" value="oa_sale_order" />
						</td>
						<td>
							<img src='images/closeDiv.gif' onclick="mydel(this,'mytable');" title='ɾ����'>
						</td>
					</tr>
EOT;
				$i ++;
			}

		/*if($i==0){
			$str="<tr align='center'><td colspan='50'>���޲ɹ������嵥��Ϣ</td></tr>";
		}*/

		}
		$str .='<input type="hidden" id="rowNum" value="'.$j.'"/>';
		return $str;
	}

	/***************************************************************************************************
	 * ------------------------------���½ӿڷ���,�ɹ�����ģ�����---------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription ͨ������Id��ȡ��Ʒ����
	 * @param $orderId
	 * @date 2010-12-17 ����05:05:57
	 */
	function getItemsByParentId($orderId) {
		$equList = $this->externalEquDao->getDetail_d($orderId);
		$lockDao=new model_stock_lock_lock();
		foreach( $equList as $key => $val ){
			//�õ������������
			$lockNum=$lockDao->getEquStockLockNum($val['id']);
			//��������´�Ĳɹ�����
			$equList[$key]['issuedNum'] = $equList[$key]['number']  -$lockNum- $equList[$key]['issuedPurNum'] - $equList[$key]['issuedProNum'];
		}
		return $equList;
	}

	/**
	 * ���ݲɹ����͵�ID����ȡ����Ϣ
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
	 * ���ݲ�ͬ�����Ͳɹ��ƻ�������ҵ����
	 *
	 * @param $paramArr
	 */
	function dealInfoAtPlan ($paramArr){
		$paramArrs=array(
					'uniqueCode'=>$paramArr ['uniqueCode'],
					'issuedPurNum'=>$paramArr ['issuedPurNum'],
					'equObjAssId'=>$paramArr['equObjAssId']
					);
		//TODO:�´�ɹ��ƻ�ʱ���Զ����Ĵ���
		if($paramArrs['equObjAssId']){
			return $this->externalEquDao->updateAmountIssued( $paramArrs['equObjAssId'] , $paramArrs['issuedPurNum']);
		}

	}

	/**�������´�ɹ�����
	*author can
	*2011-3-22
	*/
	function updateAmountIssued($id,$issuedNum,$lastIssueNum){
		return $this->externalEquDao->updateAmountIssued($id,$issuedNum,$lastIssueNum);
	}

	/**
	 * �´�ɹ��ƻ���ҳ��ԭ��ת
	 *
	 * @param tags
	 * @return return_type
	 */
	function toShowPage ($id,$type=null) {
			if($type){    //�Ӷ�������ҳ���´�ɹ��ƻ����ҳ����ת
				if($id){
					msg('�´�ɹ�');
				}else{
					msgGo('������Ϣ��������û�����ϻ�����Ϊ0���´�ʧ��',"?model=projectmanagent_order_order&action=toLockStockTab&id="+$_POST['basic']['sourceId']+"&perm=view");
				}
			}else{		//��ͳһ�ӿ�����ɹ��ƻ������תҳ��
				parent::toShowPage($id);
			}
	}




}
?>

