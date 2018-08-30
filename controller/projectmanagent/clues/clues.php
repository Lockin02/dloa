<?php
/**
 * @author LiuBo
 * @Date 2011��3��3�� 10:40:34
 * @version 1.0
 * @description:��ǰ�������Ʋ� ��ǰ����
 */
class controller_projectmanagent_clues_clues extends controller_base_action {

	function __construct() {
		$this->objName = "clues";
		$this->objPath = "projectmanagent_clues";
		parent::__construct ();
	 }

	/*
	 * ��ת����ǰ����
	 */
    function c_page() {
      $this->display('list');
    }





    /**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		 $this->assign('createId', $_SESSION['USER_ID']);
	     $this->assign('createName', $_SESSION['USERNAME']);
	     $this->assign('createNameId' , $_SESSION['USER_ID']);
	     $this->assign('trackman' , $_SESSION['USERNAME']);
	     $this->assign('trackmanId' , $_SESSION['USER_ID']);
	     $this->assign('createTime' , date("Y-m-d"));
		$this->showDatadicts(array('role'=>'ROLE'));

         $dept =  new model_common_otherdatas();
         $this->assign('createSection' ,$dept->getUserDatas( $_SESSION['USER_ID'] , 'DEPT_NAME'));
         $this->assign('createSectionId' , $_SESSION['DEPT_ID']);
		 $this->display ( 'add' );
	}
	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		if ($id) {
			msgGo( '��ӳɹ���');
		}
		//$this->listDataDict();
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
        $this->permCheck();
		$rows = $this->service->get_d ( $_GET ['id'] );
		$linkmanDao=new model_projectmanagent_clues_linkman();
		$linkmanRows=$linkmanDao->getLinkmanByCluesId_d($_GET ['id']);
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
        if (!empty ($rows['trackmaninfo'])){
        	 $trackmanId = array();
    		foreach($rows['trackmaninfo'] as $key=>$val){
               $trackmanId[$key] = $rows['trackmaninfo'][$key]['trackmanId'];
    		}

    		$trackmanIds=implode(',',$trackmanId);
    		$this->assign('trackmanId' ,$trackmanIds);
        }else {
        	$this->assign('trackman' , $_SESSION['USERNAME']);
        	$this->assign('trackmanId' , $_SESSION['USER_ID']);
        }
        $this->assign('linkmanList',$linkmanDao->showEditList_d($linkmanRows));
        $length=count($linkmanRows);//��ȡ��������ĳ���
		for($i=0;$i<$length;$i++){
			$j=$i+1;
			$this->showDatadicts ( array ('roleCode'.$j => 'ROLE' ), $linkmanRows[$i] ['roleCode'] );
		}
	    $this->assign('linkmanLeng',$length);
	    $this->showDatadicts(array ( 'customerType' => 'KHLX' ), $rows['customerType']);
	    $this->showDatadicts(array ( 'cluesSource' => 'XSLY' ), $rows['cluesSource']);
		$this->showDatadicts(array('role'=>'ROLE'));
	    $this->display ( 'edit' );

	}

	/**
	 * �޸ķ���
	 */
	function c_edit(){
		$id = $this->service->edit_d ( $_POST [$this->objName],true);
		if ($id) {
			msg( '�޸ĳɹ���' );
		}
	}

/***********************************************************************************************/
	/**
	 * �����鿴Tabҳ��
	 */
	 function c_toViewTab() {
	 	$this->permCheck();
	 	$this->assign('id' , $_GET['id']);
	 	$this->display( 'view-tab');
	 }
	 /**
	  * �鿴Tab--������Ϣ
	  */
	 function c_toView() {

	 	$rows = $this->service->get_d ( $_GET ['id'] );
		$linkmanDao=new model_projectmanagent_clues_linkman();
		$linkmanRows=$linkmanDao->getLinkmanByCluesId_d($_GET ['id']);

	 	foreach ( $rows as $key => $val ) {
             $this->assign ( $key, $val );
         }
        $this->assign('linkmanList',$linkmanDao->viewList_d($linkmanRows));
		$this->assign ( 'customerType', $this->getDataNameByCode ( $rows['customerType'] ) );
		$this->assign ( 'cluesSource', $this->getDataNameByCode ( $rows['cluesSource'] ) );
	 	$this->display('view');
	 }

    /**
     * �鿴Tab--���ټ�¼
     */
    function c_toRecord() {
    	$this->assign('trackName', $_SESSION['USERNAME']);
    	$this->assign('cluesId' , $_GET['id']);
    	$this->display('trackrecord');
    }

    /**
     * �鿴Tab���ر���Ϣ
     */
    function c_toCloseInfo(){
    	$rows = $this->service->get_d ( $_GET ['id'] );
    	if($rows['status'] == '2'){
    		foreach ( $rows as $key => $val ) {
				$this->assign ( $key, $val );
	        }
			$this->display('closeInfo');
    	}else if ($rows['status'] == '3'){
    		foreach ( $rows as $key => $val ) {
				$this->assign ( $key, $val );
	        }
			$this->display('pauseInfo');
    	}else {
    		$this->display('none');
    	}

    }

    /**
     * @description �ر�����
     */
    function c_closeClues(){
    	$rows = $_POST;
    	$flag = $this->service->closeClues_d($rows);
    	if($flag){
    		msg('�رճɹ�');
    	}else{
    		msg('�ر�ʧ��');
    	}

    }

    /**
     * @description ��ͣ����
     */
    function c_pauseClues(){
    	$rows = $_POST;
    	$flag = $this->service->pauseClues_d($rows);
    	if($flag){
    		msg('��ͣ�ɹ�');
    	}else{
    		msg('��ͣʧ��');
    	}

    }

    /**
     * @description �ָ�����
     */
    function c_recoverClues(){
    	$rows = $_POST;
    	$flag = $this->service->recoverClues_d($rows);
    	if($flag){
    		msg('�ָ��ɹ�');
    	}else{
    		msg('�ָ�ʧ��');
    	}

    }

	   /**
	    * ָ��������
	    */
	   function c_toTrackman() {
	   	  $this->permCheck();
	   	  $row = $this->service->get_d($_GET['id']);
          foreach ($row as $key => $val) {
			$this->assign($key , $val);
          }
          //��ȡ������Id
           $trackmanId = array();
    		foreach($row['trackmaninfo'] as $key=>$val){
               $trackmanId[$key] = $row['trackmaninfo'][$key]['trackmanId'];
    		}

    		$trackmanIds=implode(',',$trackmanId);
    		$this->assign('trackmanId' ,$trackmanIds);
	   	  $this->display('assigntrack');
	   }

     /**
      * �ƽ�������ת����
      * by MaiZP  2011-8-17 9:31:24
      */
		function c_transferClues(){
		        $this->permCheck();
		        $row = $this->service->get_d($_GET['id']);

				foreach ($row as $key => $val) {
					$this->assign($key, $val);
				}
				$this->assign('trackUser' , $_SESSION['USERNAME']);
				$this->assign('trackUserId' , $_SESSION['USER_ID']);
		        $this->display('transfer');
		    }
        /**
         * �ƽ������޸ķ���
         * by MaiZP
         */
       function c_transfer(){
		      	$id = $this->service->transfer_d($_POST[$this->objName], true);
				if ($id) {
					msg('�޸ĳɹ���');
				}
		      }
		/**
		 * ��������ָ�������Ÿ�����
		 */
       function c_deptTrackman(){
       	        $this->permCheck();
       	        $rowA = $this->service->get_d($_GET['id']);
                $rows = $this->service->deptTrackInfo($rowA);
				foreach ($rows as $key => $val) {
					$this->assign($key, $val);
				}

                $deptman = implode (',',$rows['deptman']);
                $deptmanId = implode (',',$rows['deptmanId']);
                $deptmanOther = implode (',',$rows['deptManOther']);
                $deptmanOtherId = implode (',',$rows['deptManOtherId']);

                $this->assign('deptTrackman' , $deptman);
                $this->assign('deptTrackmanId' , $deptmanId);
                $this->assign('deptTrackmanOther' , $deptmanOther);
                $this->assign('deptTrackmanOtherId' , $deptmanOtherId);
				$this->assign('trackUser' , $_SESSION['USERNAME']);
				$this->assign('trackUserId' , $_SESSION['USER_ID']);
		        $this->display('depttrackman');
       }
       /**
        * ��������ָ�������Ÿ����˷���
        */
       function c_deptTrack(){
           $id = $this->service->deptTrack_d($_POST[$this->objName], true);
				if ($id) {
					msg('�޸ĳɹ���');
				}
       }

	   /**
	    * ��ת���ر�����ҳ��
	    */
	   function c_toCluesClose() {
	   	  $row = $this->service->get_d($_GET['id']);
          foreach ($row as $key => $val) {
        	$this->assign($key , $val);
        }
//          $this->assign('userName' , $_SESSION['USERNAME']);
//          $this->assign('dateTime' , date('Y-m-d'));

	   	  $this->display('cluesclose');
	   }

	   /**
	    * ��ת����ͣ����ҳ��
	    */
	   function c_toPause() {
	   	  $this->permCheck();
	   	  $row = $this->service->get_d($_GET['id']);
          foreach ($row as $key => $val) {
        	$this->assign($key , $val);
        }

	   	  $this->display('pause');
	   }


	   /**
	    * ��ת���ָ�����ҳ��
	    */
	   function c_toRecover() {
	   	  $this->permCheck();
	   	  $row = $this->service->get_d($_GET['id']);
          foreach ($row as $key => $val) {
        	$this->assign($key , $val);
        }

	   	  $this->display('recover');
	   }
	 /**
	  * �ҵ�����Tab
	  */
	  function c_toMyclues() {
	  	$this->display('myclues-tab');
	  }

	  /**
	   * ��ע�������
	   */
	   function c_toRegister() {
	   	  $this->assign('userName', $_SESSION['USERNAME']);
	   	  $this->display('register');
	   }
	   /**
	   * �Ҹ��ٵ�����
	   */
	   function c_toTrack() {
	   	  $this->assign('userName', $_SESSION['USERNAME']);
	   	  $this->display('track');
	   }

	/**
	 * ��д�Ҹ��ٵ�����PageJson����
	 */
	 function c_toTrackPageJson() {
	 	$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        $service->searchArr['trackmanIdForS'] = $_SESSION['USER_ID'];
        $rows = $service->pageBySqlId("select_clues");
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	 }
	      /**********************************************************************/
	/**
	 * ����������Ϣ
	 * by maizp
	 */
 	function c_deptClues(){
 		$this->display('deptlist');
 	}

/******************start��Ϣ�б���**************************/

	function c_exportExcel() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(600);
		$statusArr = array ('0' => '����', '1' => '��ת�̻�', '2' => '�ر�', '3' => '��ͣ' );

		$colIdStr = $_GET ['colId'];
		$colNameStr = $_GET ['colName'];
		$status = $_GET ['status'];
		//��ͷId����
		$colIdArr = explode ( ',', $colIdStr );
		$colIdArr = array_filter ( $colIdArr );
		//��ͷName����
		$colNameArr = explode ( ',', $colNameStr );
		$colNameArr = array_filter ( $colNameArr );
		//��ͷ����
		$colArr = array_combine ( $colIdArr, $colNameArr );
		$searchArr ['status'] = $status;

		foreach ( $searchArr as $key => $val ) {
			if ($searchArr [$key] === null || $searchArr [$key] === '' || $searchArr [$key] == 'undefined') {
				unset ( $searchArr [$key] );
			}
		}
		$this->service->searchArr = $searchArr;
		$rows = $this->service->listBySqlId ( 'select_default' );

		foreach ( $rows as $index => $row ) {
			foreach ( $row as $key => $val ) {
				if ($key == 'status') {
					$rows [$index] [$key] = $statusArr [$val];
				}
			}
		}

		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip ( $colIdArr );
		foreach ( $rows as $key => $row ) {
			foreach ( $colIdArr as $index => $val ) {
				$colIdArr [$index] = $row [$index];
			}
			array_push ( $dataArr, $colIdArr );
		}
        foreach($dataArr as $key=>$val){
			$dataArr[$key]['customerType']=$this->getDataNameByCode($val['customerType']);
		}

		return model_contract_common_contExcelUtil::export2ExcelUtil ( $colArr, $dataArr );
	}
/******************start��Ϣ�б���************end**************/
 }
?>