<?php
/**
 * @author Administrator
 * @Date 2011年11月28日 10:42:46
 * @version 1.0
 * @description:员工借试用续借单 Model层
 */
 class model_projectmanagent_borrow_renew  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_borrow_renew";
		$this->sql_map = "projectmanagent/borrow/renewSql.php";
		parent::__construct ();
	}


	/**
	 * 重写add_d方法

	 */
	function add_d($object){
		try{
			$this->start_d();
			//插入主表信息
			$newId = parent::add_d($object,true);
			//插入从表信息
			//设备
			 if(!empty($object['renewequ'])){
			 	$renewEquDao = new model_projectmanagent_borrow_renewequ();
			    $renewEquDao->createBatch($object['renewequ'],array('renewId' => $newId ),'productName');

			    $licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $newId, 'objType' => $this->tbl_name , 'extType' => $renewEquDao->tbl_name ),
					'renewId',
					'license'
				);
			 }
			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	 /**
	  * 重写get_d
	  */
	  function get_d($id,$selection = null){
	  	 //提取主表信息
	  	 $rows = parent::get_d($id);
	  	 if(empty($selection)){
	  	 	$equDao = new model_projectmanagent_borrow_renewequ();
	  	 	$rows['renewequ'] = $equDao -> getDetail_d($id);
	  	 }else if(is_array($selection)){
	  	 	if(in_array('renewequ',$selection)){
				$equDao = new model_projectmanagent_borrow_renewequ();
				$rows['renewequ'] = $equDao->getDetail_d($id);
			}
	  	 }
	  	 return $rows;
	  }


	/**
	 * 查看页 查看源单信息
	 */
    function orBorrow($borrowId){
        $dao = new model_projectmanagent_borrow_borrow();
        $orInfo = $dao->get_d($borrowId);
        $borrow = $orInfo['Code'].='<img src="images\icon\view.gif" onclick="showOpenWin(\'?model=projectmanagent_borrow_borrow&action=proView&id='.$borrowId.'&perm=view\')">';
        return $borrow;
    }
   /**
    * 审批后更新源单据
    */
   function updateProBorrow($rows){
          $dao = new model_projectmanagent_borrow_borrow();
          //续借次数
          $renewNumT = $dao->find(array('id' => $rows['borrowId']),null,'renew');
          $renewNum = $renewNumT['renew'] + 1;
//          $endDate = $rows['reendDate'];
         $sql = "update oa_borrow_borrow set status = ".$rows['reStatus'].",renew = $renewNum where id = ".$rows['borrowId']."";
         $this->_db->query($sql);
   }
   /**
    * workflow  callback
    */
    function workflowCallBack($spid){
    	$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);
		$objId = $folowInfo ['objId'];
		if (! empty ( $objId )) {
			$rows= $this->get_d ( $objId );
                $this->updateProBorrow($rows);
		}
    }
 }
?>