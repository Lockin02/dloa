<?php
/**
 * @author Administrator
 * @Date 2012��12��14�� ������ 15:17:39
 * @version 1.0
 * @description:�ɹ�����_��Ӧ������Ϣ Model��
 */
 class model_purchase_contract_applysupp  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_purch_apply_supp";
		$this->sql_map = "purchase/contract/applysuppSql.php";
		parent::__construct ();
	}

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
			$suppproDao=new model_purchase_contract_applysuppequ();
			$productRows=$object['applysuppequ'];
			$parentId=array('parentId'=>$id);
			$suppproDao->delete($parentId);
			unset($object['applysuppequ']);
			if($productRows){
				foreach($productRows as $key=>$val){
					$val['parentId']=$id;
					$suppproDao->add_d($val);
				}
			}
			//���¸���������ϵ
			$this->updateObjWithFile ( $id);

			//��������
			if (isset ( $_POST ['fileuploadIds'] ) && is_array ( $_POST ['fileuploadIds'] )) {
				$uploadFile = new model_file_uploadfile_management ();
				$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id );
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

			$suppproDao=new model_purchase_contract_applysuppequ();
			$parentId=array('parentId'=>$id);
			$suppproDao->delete($parentId);
			if($pro['applysuppequ']){
				foreach($pro['applysuppequ'] as $key=>$val){
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


	/**��ӻ��޸�ѯ�۵�ʱ��ɾ����Ӧ����Ϣ
	*/
	function c_del(){
		$id=$_POST['id'];
		$flag=$this->service->deletes($id);
		echo $flag;
	}

		/**��ȡ��Ӧ����Ϣ
	*/
	function getSuppByParentId($parentId){
		$arr = $this->findAll(array('parentId'=>$parentId));
		foreach($arr as $k => $v){
			//ѭ����ȡ��Ӧ�����¿��˽�� id
			$sql = "select id from oa_supp_suppasses where suppId = '".$v['suppId']."' order by id desc limit 0,1";
			$tArr = $this->_db->getArray($sql);
			$arr[$k]['sid'] = $tArr[0]['id'];
		}
	 	return $arr;
	 }


 }
?>