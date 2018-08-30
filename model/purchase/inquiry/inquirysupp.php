<?php
/**
 * @description: 询价单供应商Model
 * @date 2010-12-24 上午10:08:29
 * @author oyzx
 * @version V1.0
 */
class model_purchase_inquiry_inquirysupp extends model_base{

	function __construct() {
		$this->tbl_name = "oa_purch_inquiry_supp";
		$this->sql_map = "purchase/inquiry/inquirysuppSql.php";
		parent :: __construct();
	}

/*****************************************页面跳转方法开始********************************************/

	/**询价单供应商查看
	*author can
	*2011-1-1
	* @param $rows		供应商数组
	*/
	function suppShow($rows){
	$str="";
		$i=0;
		if($rows){
			foreach($rows as $key=>$val){
				$i++;
				$str.=<<<EOT
					<tr>
					    <td>供应商-$i</td>
						<td>
							$val[suppName]
							<input type="hidden" id="supplier$i" value="$val[suppName]"/>
							<input type="hidden" id="supplierId$i" value="$val[suppId]" />
						</td>
						<td>$val[suppTel]</td>
						<td>$val[quote]</td>
						<td>
							<a onclick="javascript:showThickboxWin('index1.php?model=purchase_inquiry_inquirysupp&action=readQuotation&parentId=$val[id]&supplierName=$val[suppName]&quote=$val[quote]&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800')" href="#">供应商$i-报价单</a>
						</td>
					</tr>
EOT;
			}
		}else{
			$str="<tr align='center'><td colspan='50'>暂无供应商信息</td></tr>";
		}
		return $str;
	}

/*****************************************页面跳转方法结束********************************************/

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

	/**获取供应商信息
	*author can
	*2011-1-1
	* @param $parentId		询价单ID
	*/
	function getSuppByParentId($parentId){
	 	return $this->findAll(array('parentId'=>$parentId));
	 }


	 /*
	 * @desription 根据询价单ID获得指定供应商的ID值
	 * @param $id--询价单的ID
	 * @param $suppId--询价单指定的供应商ID
	 * @author qian
	 * @date 2011-1-10 下午04:12:23
	 */
	function getSuppIdByInquiry_d ($id,$suppId) {
		$condiction = array('parentId'=>$id,'id'=>$suppId);
		$rows = $this->findAll($condiction);
		return $rows;
	}


	/*
	 * @desription 根据询价单ID，联合报价单产品清单表查询数据
	 * @param $id--询价单的ID
	 * @param $suppId--询价单指定的供应商ID
	 * @author qian
	 * @date 2011-1-10 下午08:03:06
	 */
	function getSupp_d ($inquiryId,$suppId) {
		$this->searchArr = array('parentId'=>$inquiryId,'id'=>$suppId);
		$rows = $this->pageBySqlId('inqu_supp');
		return $rows;
	}

	/**根据采购询价单的ID，找到已经指定的供应商
	 * @param $parentId--询价单的ID
	 * @param $suppId--询价单指定的供应商ID
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

/*****************************************业务操作方法结束********************************************/
}
?>
