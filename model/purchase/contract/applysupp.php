<?php
/**
 * @author Administrator
 * @Date 2012年12月14日 星期五 15:17:39
 * @version 1.0
 * @description:采购订单_供应商主信息 Model层
 */
 class model_purchase_contract_applysupp  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_purch_apply_supp";
		$this->sql_map = "purchase/contract/applysuppSql.php";
		parent::__construct ();
	}

	/*****************************************业务操作方法开始********************************************/
    /**添加供应商—产品清单
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
            //处理数据字典字段
			$datadictDao = new model_system_datadict_datadict ();
			$object ['paymentConditionName'] =  $datadictDao->getDataNameByCode ( $object['paymentCondition'] );
			$this->edit_d($object,true);
			//添加供应商—产品清单
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
			//更新附件关联关系
			$this->updateObjWithFile ( $id);

			//附件处理
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

		/**根据采购询价单的ID，先删除相关的设备清单，再重新保存
	*author can
	*2011-1-3
	*/
	function addEditPro_d($pro){
		try{
			$this->start_d();
			if($pro ['paymentCondition']!="YFK"){
				$pro ['payRatio']="";
			}
            //处理数据字典字段
			$datadictDao = new model_system_datadict_datadict ();
			$pro ['paymentConditionName'] =  $datadictDao->getDataNameByCode ( $pro['paymentCondition'] );
			$this->edit_d($pro,true);     //更新总报价字段
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


	/**添加或修改询价单时，删除供应商信息
	*/
	function c_del(){
		$id=$_POST['id'];
		$flag=$this->service->deletes($id);
		echo $flag;
	}

		/**获取供应商信息
	*/
	function getSuppByParentId($parentId){
		$arr = $this->findAll(array('parentId'=>$parentId));
		foreach($arr as $k => $v){
			//循环获取供应商最新考核结果 id
			$sql = "select id from oa_supp_suppasses where suppId = '".$v['suppId']."' order by id desc limit 0,1";
			$tArr = $this->_db->getArray($sql);
			$arr[$k]['sid'] = $tArr[0]['id'];
		}
	 	return $arr;
	 }


 }
?>