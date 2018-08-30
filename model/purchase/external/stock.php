<?php
header("Content-type: text/html; charset=gb2312");

//����ӿ�
include_once WEB_TOR . 'model/purchase/external/planBasic.php';

/**
 * @description: ����ɹ����model
 * @date 2010-12-17 ����04:28:25
 * @author oyzx
 * @version V1.0
 */
class model_purchase_external_stock extends planBasic {

	private $externalDao; //�ⲿ����dao�ӿ�
	private $externalEquDao; //�ⲿ�����豸dao�ӿ�

	function __construct() {
		$this->externalDao = new model_stock_fillup_fillup();
		$this->externalEquDao = new model_stock_fillup_filluppro();

		//���ó�ʼ�����������
		$this->objass = new model_common_objass();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription ��Ӳɹ��ƻ�-��ʾ�б�
	 * @param tags
	 * @date 2010-12-17 ����05:17:09
	 */
	function showAddList($equs,$mainRows) {
       if( is_array( $equs ) ){
		$i = 0;
		$baseDao=new model_base();
		$datadictArr = $baseDao->getDatadicts ( "CGZJSX" );
		$checkTypeStr=$baseDao->getDatadictsStr( $datadictArr ['CGZJSX']);
		foreach ($equs as $key => $val) {
            if($val[stockNum]-$val[issuedPurNum]>0){
			++ $i;
            $YMD = date("Y-m-d");
           	$val[amountAll] = $val[stockNum]-$val[issuedPurNum];
			$str .=<<<EOT
						<tr height="28" align="center">
							<td>
								$i
							</td>
							<td>
								<input type="text"  class="readOnlyTxtItem" name="basic[equipment][$key][productNumb]" value="$val[sequence]" />
							</td>
							<td>
								<input type="text"  class="readOnlyTxtItem"  name="basic[equipment][$key][productName]" value="$val[productName]" />
								<input type="hidden" name="basic[equipment][$key][productId]" value="$val[productId]" />
							</td>
							<td>
								<input type="text" id="pattem$i" name="basic[equipment][$key][pattem]" value="$val[pattern]" class="readOnlyTxtItem" readonly='readonly'/>
							</td>
							<td>
								<input type="text" id="unitName$i" name="basic[equipment][$key][unitName]" value="$val[unitName]"   class="readOnlyTxtMin"/>
							</td>
							<td>
								<select  name="basic[equipment][$key][qualityCode]">$checkTypeStr</select>
							</td>
							<td>
								<input type="text" class="amount txtshort" id="list_amountAll$key" name="basic[equipment][$key][amountAll]" value="$val[amountAll]" onblur="addPlan(this);">
								<input type="hidden" name="amountAll" value="$val[amountAll]" >
							</td>
							<td>
								<input type="text" class="txtshort" name="basic[equipment][$key][dateIssued]" value="$YMD" onfocus="WdatePicker()" readonly >
							</td>
							<td>
								<input type="text" class="txtshort" name="basic[equipment][$key][dateHope]" value="$val[intentArrTime]" onfocus="WdatePicker()" readonly >
							</td>
							<td>
								<input type="text" class="txt" name="basic[equipment][$key][remark]"></input>
								<input type="hidden" id="list_applyEquId$key" name="basic[equipment][$key][applyEquId]" value="$val[id]" />
								<input type="hidden" name="basic[equipment][$key][equObjAssId]" value="$val[id]" />
								<input type="hidden" name="basic[equipment][$key][purchType]" value="stock" />
							</td>
							<td>
							<img src="images/closeDiv.gif" onclick="mydel(this , 'mytable')" title="ɾ����" />
						    </td>
						</tr>

EOT;
            }
		}
		if($i==0){
			$str="<tr align='center'><td colspan='50'>���޲���ɹ������嵥��Ϣ</td></tr>";
		}
		$str .=<<<EOT
					<input type="hidden" name="basic[objAssId]" value="$mainRows[id]" />
					<input type="hidden" name="basic[objAssName]" value="����ɹ�" />
					<input type="hidden" name="basic[objAssType]" value="stock" />
					<input type="hidden" name="basic[objAssCode]" value="$mainRows[fillupCode]" />
					<input type="hidden" name="basic[equObjAssType]" value="stock_equ" />
EOT;
	}else{
		$str="<tr align='center'><td colspan='50'>���޲���ɹ������嵥��Ϣ</td></tr>";
	}
		return $str;
	}
	/**
	 * @desription ���ⵥ��Ʒ�б���ʾ
	 * @param tags
	 * @date 2011-1-17 ����03:16:47
	 * @qiaolong
	 */
	function showProlist ($rows) {
 		if ( is_array( $rows ) ) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ($rows as $key => $val) {
				$str .=<<<EOT
					<tr align="center" >
					<td class="tabledata">
						<!--input type="hidden" value="$val[id]"-->
						<input type="hidden" name="basic[equipment][$key][contOnlyId]" value="$val[id]">
						<input type="text" size="15" name="basic[equipment][$key][productNumb]" value="$val[sequence]" readOnly class="readOnlyTxt" >
					</td>
					<td class="tabledata">
						<input type="text" size="20" name="basic[equipment][$key][productName]" value="$val[productName]"  id="productName$i" readOnly class="readOnlyTxt" />
						<input type="hidden" name="basic[equipment][$key][productId]" value="$val[productId]"  id="productId$i" >
					</td>
					<td class="tabledata">
						<input type="text" size="15" name="basic[equipment][$key][amountAll]" value="$val[stockNum]"  readOnly class="readOnlyTxt"/>
					</td>
					<td class="tabledata">
						<input type="text" size="15" name="basic[equipment][$key][dateIssued]" value="$val[intentArrTime]"  readOnly class="readOnlyTxt" />
					</td>
					<td class="tabledata">
						<input type="text" size="15" name="basic[equipment][$key][dateHope]"  onfocus="WdatePicker()"/>
					</td>
					<td class="tabledata">
						<input type="text" name="basic[equipment][$key][remark]" size="15" />
						<input type="hidden" name="basic[equipment][$key][purchType]" value="stock" />
					</td>
					</tr>
EOT;
		$i++;
			}
			return $str;
			}
	 }

	/***************************************************************************************************
	 * ------------------------------���½ӿڷ���,�ɹ�����ģ�����---------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription ͨ�����ⵥId��ȡ��Ʒ����
	 * @param $parentId		���ⵥID
	 */
	function getItemsByParentId ($parentId) {
		$itemRows=$this->externalEquDao->getItemByFillUpId($parentId);
		return $itemRows;
	}

	/**
	 * ���ݲɹ����͵�ID����ȡ����Ϣ
	 *
	 * @param $id	���ⵥID
	 */
	function getInfoList ($id) {
		$mainRows=$this->externalDao->get_d($id);
		$mainRows['sourceName']=$mainRows['fillupCode'];
		$mainRows['sourceCode']=$mainRows['fillupCode'];
		return $mainRows;
	}
	/**
	 * ���ݲ�ͬ�����Ͳɹ��ƻ�������ҵ����
	 *
	 * @param $paramArr		��������
	 */
	function dealInfoAtPlan ($paramArr){
		$fillupEquId=$paramArr['equObjAssId'];
		$issuedPurNum=$paramArr['issuedPurNum'];
		return $this->externalEquDao->updateIssuedPurNum($fillupEquId,$issuedPurNum);
	}

	/**�������´�ɹ�����
	*author can
	*2011-3-22
	*/
	function updateAmountIssued($id,$issuedNum,$lastIssueNum){
		return $this->externalEquDao->updateAmountIssued($id,$issuedNum,$lastIssueNum);
	}




	/**
	 * @desription ���Ҳ����Ʒ
	 * @param $id	���ⵥID
	 * @date 2011-1-17 ����03:11:27
	 * @qiaolong
	 */
	function getFillupProInfo ($id) {
		$filluppro = new model_stock_fillup_filluppro();
		$filluppro->searchArr['fillUpId']=$id;
		$fillupPros = $filluppro->listBySqlId('select_default');
		return $fillupPros;
	}
	/**
	 * @desription ��ȡ���ⵥ��Ϣ
	 * @param $id	���ⵥID
	 * @date 2011-1-17 ����03:31:50
	 * @qiaolong
	 */
	function getFillupInfo ($id) {
		$fillupDao = new model_stock_fillup_fillup();
		$fillupDao->searchArr['id']=$id;
		$fillups = $fillupDao->listBySqlId('select_default');
		return $fillups;
	}

	/**
	 * �´�ɹ��ƻ���ҳ��ԭ��ת
	 *
	 * @param $id	�ɹ����뱣��ID
	 */
	function toShowPage ($id,$type=null) {
			if($type){    //�Ӳ������ҳ���´�ɹ��ƻ����ҳ����ת
				if($id){
					msg('�´�ɹ�');
				}else{
					msg('������Ϣ��������û�����ϻ�����Ϊ0���´�ʧ��');
				}
			}else{		//��ͳһ�ӿ�����ɹ��ƻ������תҳ��
				parent::toShowPage($id);
			}
	}

}
?>
