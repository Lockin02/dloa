<?php
/**
 * @author Administrator
 * @Date 2011��11��28�� 10:42:46
 * @version 1.0
 * @description:Ա�����������赥 Model��
 */
 class model_projectmanagent_borrow_renew  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_borrow_renew";
		$this->sql_map = "projectmanagent/borrow/renewSql.php";
		parent::__construct ();
	}


	/**
	 * ��дadd_d����

	 */
	function add_d($object){
		try{
			$this->start_d();
			//����������Ϣ
			$newId = parent::add_d($object,true);
			//����ӱ���Ϣ
			//�豸
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
	  * ��дget_d
	  */
	  function get_d($id,$selection = null){
	  	 //��ȡ������Ϣ
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
	 * �鿴ҳ �鿴Դ����Ϣ
	 */
    function orBorrow($borrowId){
        $dao = new model_projectmanagent_borrow_borrow();
        $orInfo = $dao->get_d($borrowId);
        $borrow = $orInfo['Code'].='<img src="images\icon\view.gif" onclick="showOpenWin(\'?model=projectmanagent_borrow_borrow&action=proView&id='.$borrowId.'&perm=view\')">';
        return $borrow;
    }
   /**
    * ���������Դ����
    */
   function updateProBorrow($rows){
          $dao = new model_projectmanagent_borrow_borrow();
          //�������
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