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

class model_purchase_external_orderpurchase extends planBasic {

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
							<img src="images/closeDiv.gif" onclick="mydel(this , 'mytable')" title="ɾ����" />
						    </td>
						</tr>

EOT;
			}
		}
		if($i==0){
			$str="<tr align='center'><td colspan='50'>���޲ɹ��ƻ��嵥��Ϣ</td></tr>";
		}
		$str .=<<<EOT
					<input type="hidden" name="basic[objAssId]" value="$mianRows[id]" />
					<input type="hidden" name="basic[objAssName]" value="$mianRows[orderName]" />
					<input type="hidden" name="basic[objAssType]" value="order" />
					<input type="hidden" name="basic[objAssCode]" value="$mianRows[orderCode]" />
					<input type="hidden" name="basic[equObjAssType]" value="order" />
EOT;
	}else{
		$str="<tr align='center'><td colspan='50'>���޲ɹ��ƻ��嵥��Ϣ</td></tr>";
	}
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
					'issuedPurNum'=>$paramArr ['issuedPurNum']
					);
		//TODO:�´�ɹ��ƻ�ʱ���Զ����Ĵ���
		return $this->externalEquDao->updateEquipmentQuantity( $paramArrs , 'add');
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
					msgGo('�´�ɹ�',"?model=projectmanagent_order_order&action=toLockStockTab&id="+$_POST['basic']['sourceId']+"&perm=view");
				}else{
					msgGo('������Ϣ��������û�����ϻ�����Ϊ0���´�ʧ��',"?model=projectmanagent_order_order&action=toLockStockTab&id="+$_POST['basic']['sourceId']+"&perm=view");
				}
			}else{		//��ͳһ�ӿ�����ɹ��ƻ������תҳ��
				parent::toShowPage($id);
			}
	}




}
?>

