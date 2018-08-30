<?php
/**
 * @description: ѯ�۵���Ӧ��Model
 * @date 2010-12-24 ����10:08:29
 * @author oyzx
 * @version V1.0
 */
class model_purchase_inquiry_inquirysupp extends model_base{

	function __construct() {
		$this->tbl_name = "oa_purch_inquiry_supp";
		$this->sql_map = "purchase/inquiry/inquirysuppSql.php";
		parent :: __construct();
	}

/*****************************************ҳ����ת������ʼ********************************************/

	/**ѯ�۵���Ӧ�̲鿴
	*author can
	*2011-1-1
	* @param $rows		��Ӧ������
	*/
	function suppShow($rows){
	$str="";
		$i=0;
		if($rows){
			foreach($rows as $key=>$val){
				$i++;
				$str.=<<<EOT
					<tr>
					    <td>��Ӧ��-$i</td>
						<td>
							$val[suppName]
							<input type="hidden" id="supplier$i" value="$val[suppName]"/>
							<input type="hidden" id="supplierId$i" value="$val[suppId]" />
						</td>
						<td>$val[suppTel]</td>
						<td>$val[quote]</td>
						<td>
							<a onclick="javascript:showThickboxWin('index1.php?model=purchase_inquiry_inquirysupp&action=readQuotation&parentId=$val[id]&supplierName=$val[suppName]&quote=$val[quote]&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800')" href="#">��Ӧ��$i-���۵�</a>
						</td>
					</tr>
EOT;
			}
		}else{
			$str="<tr align='center'><td colspan='50'>���޹�Ӧ����Ϣ</td></tr>";
		}
		return $str;
	}

/*****************************************ҳ����ת��������********************************************/

/*****************************************ҵ�����������ʼ********************************************/
    /**��ӹ�Ӧ�̡���Ʒ�嵥
	*author can
	*2010-12-31
	*/
	function addProduct_d($object){
		try{
			$this->start_d();
			$id=$object['id'];
			$condiction=array('id'=>$id);
			$quote=$object['quote'];
			if($object ['paymentCondition']!="YFK"){
				$object ['payRatio']="";
			}
            //���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict ();
			$object ['paymentConditionName'] =  $datadictDao->getDataNameByCode ( $object['paymentCondition'] );
			$this->edit_d($object,true);
			//��ӹ�Ӧ�̡���Ʒ�嵥
			$suppproDao=new model_purchase_inquiry_inquirysupppro();
			$productRows=$object['inquirysupppro'];
			$parentId=array('parentId'=>$id);
			$suppproDao->delete($parentId);
			unset($object['inquirysupppro']);
			if($productRows){
				foreach($productRows as $key=>$val){
					$val['parentId']=$id;
					$suppproDao->add_d($val);
				}
			}

			$this->commit_d();

			return $object;
		}catch(Exception $e){
			$this->rollBack ();
			return null;
		}
	}

	/**���ݲɹ�ѯ�۵���ID����ɾ����ص��豸�嵥�������±���
	*author can
	*2011-1-3
	*/
	function addEditPro_d($pro){
		try{
			$this->start_d();
			if($pro ['paymentCondition']!="YFK"){
				$pro ['payRatio']="";
			}
            //���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict ();
			$pro ['paymentConditionName'] =  $datadictDao->getDataNameByCode ( $pro['paymentCondition'] );
			$this->edit_d($pro,true);     //�����ܱ����ֶ�
			$id=$pro['id'];

			$suppproDao=new model_purchase_inquiry_inquirysupppro();
			$parentId=array('parentId'=>$id);
			$suppproDao->delete($parentId);
			if($pro['inquirysupppro']){
				foreach($pro['inquirysupppro'] as $key=>$val){
					$val['parentId']=$id;
					$suppproDao->add_d($val);
				}
			}

			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack ();
			return null;
		}
	}

	/**��ȡ��Ӧ����Ϣ
	*author can
	*2011-1-1
	* @param $parentId		ѯ�۵�ID
	*/
	function getSuppByParentId($parentId){
	 	return $this->findAll(array('parentId'=>$parentId));
	 }


	 /*
	 * @desription ����ѯ�۵�ID���ָ����Ӧ�̵�IDֵ
	 * @param $id--ѯ�۵���ID
	 * @param $suppId--ѯ�۵�ָ���Ĺ�Ӧ��ID
	 * @author qian
	 * @date 2011-1-10 ����04:12:23
	 */
	function getSuppIdByInquiry_d ($id,$suppId) {
		$condiction = array('parentId'=>$id,'id'=>$suppId);
		$rows = $this->findAll($condiction);
		return $rows;
	}


	/*
	 * @desription ����ѯ�۵�ID�����ϱ��۵���Ʒ�嵥���ѯ����
	 * @param $id--ѯ�۵���ID
	 * @param $suppId--ѯ�۵�ָ���Ĺ�Ӧ��ID
	 * @author qian
	 * @date 2011-1-10 ����08:03:06
	 */
	function getSupp_d ($inquiryId,$suppId) {
		$this->searchArr = array('parentId'=>$inquiryId,'id'=>$suppId);
		$rows = $this->pageBySqlId('inqu_supp');
		return $rows;
	}

	/**���ݲɹ�ѯ�۵���ID���ҵ��Ѿ�ָ���Ĺ�Ӧ��
	 * @param $parentId--ѯ�۵���ID
	 * @param $suppId--ѯ�۵�ָ���Ĺ�Ӧ��ID
	*2011-1-18
	 */
	function getAssignSupp ($parentId,$suppId) {
		$this->searchArr=array('parentId'=>$parentId,'id'=>$suppId);
		$this->groupBy = 'p.id';
		$rows = $this->pageBySqlId('lin_supp');
		$suppEquDao = new model_purchase_inquiry_inquirysupppro();
		$rows['0']['suppEqu'] = $suppEquDao->getSuppInquiry_d( $rows['0']['id'] );
		return $rows;
	}

/*****************************************ҵ�������������********************************************/
}
?>
