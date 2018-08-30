<?php
/**
 * @author LiuBo
 * @Date 2011年3月3日 10:40:34
 * @version 1.0
 * @description:售前线索控制层 售前线索
 */
class controller_projectmanagent_clues_clues extends controller_base_action {

	function __construct() {
		$this->objName = "clues";
		$this->objPath = "projectmanagent_clues";
		parent::__construct ();
	 }

	/*
	 * 跳转到售前线索
	 */
    function c_page() {
      $this->display('list');
    }





    /**
	 * 跳转到新增页面
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
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		if ($id) {
			msgGo( '添加成功！');
		}
		//$this->listDataDict();
	}

	/**
	 * 初始化对象
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
        $length=count($linkmanRows);//获取物料数组的长度
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
	 * 修改方法
	 */
	function c_edit(){
		$id = $this->service->edit_d ( $_POST [$this->objName],true);
		if ($id) {
			msg( '修改成功！' );
		}
	}

/***********************************************************************************************/
	/**
	 * 线索查看Tab页面
	 */
	 function c_toViewTab() {
	 	$this->permCheck();
	 	$this->assign('id' , $_GET['id']);
	 	$this->display( 'view-tab');
	 }
	 /**
	  * 查看Tab--基本信息
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
     * 查看Tab--跟踪记录
     */
    function c_toRecord() {
    	$this->assign('trackName', $_SESSION['USERNAME']);
    	$this->assign('cluesId' , $_GET['id']);
    	$this->display('trackrecord');
    }

    /**
     * 查看Tab－关闭信息
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
     * @description 关闭线索
     */
    function c_closeClues(){
    	$rows = $_POST;
    	$flag = $this->service->closeClues_d($rows);
    	if($flag){
    		msg('关闭成功');
    	}else{
    		msg('关闭失败');
    	}

    }

    /**
     * @description 暂停线索
     */
    function c_pauseClues(){
    	$rows = $_POST;
    	$flag = $this->service->pauseClues_d($rows);
    	if($flag){
    		msg('暂停成功');
    	}else{
    		msg('暂停失败');
    	}

    }

    /**
     * @description 恢复线索
     */
    function c_recoverClues(){
    	$rows = $_POST;
    	$flag = $this->service->recoverClues_d($rows);
    	if($flag){
    		msg('恢复成功');
    	}else{
    		msg('恢复失败');
    	}

    }

	   /**
	    * 指定跟踪人
	    */
	   function c_toTrackman() {
	   	  $this->permCheck();
	   	  $row = $this->service->get_d($_GET['id']);
          foreach ($row as $key => $val) {
			$this->assign($key , $val);
          }
          //获取跟踪人Id
           $trackmanId = array();
    		foreach($row['trackmaninfo'] as $key=>$val){
               $trackmanId[$key] = $row['trackmaninfo'][$key]['trackmanId'];
    		}

    		$trackmanIds=implode(',',$trackmanId);
    		$this->assign('trackmanId' ,$trackmanIds);
	   	  $this->display('assigntrack');
	   }

     /**
      * 移交线索跳转方法
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
         * 移交线索修改方法
         * by MaiZP
         */
       function c_transfer(){
		      	$id = $this->service->transfer_d($_POST[$this->objName], true);
				if ($id) {
					msg('修改成功！');
				}
		      }
		/**
		 * 部门主管指定本部门跟踪人
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
        * 部门主管指定本部门跟踪人方法
        */
       function c_deptTrack(){
           $id = $this->service->deptTrack_d($_POST[$this->objName], true);
				if ($id) {
					msg('修改成功！');
				}
       }

	   /**
	    * 跳转到关闭线索页面
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
	    * 跳转到暂停线索页面
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
	    * 跳转到恢复线索页面
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
	  * 我的线索Tab
	  */
	  function c_toMyclues() {
	  	$this->display('myclues-tab');
	  }

	  /**
	   * 我注册的线索
	   */
	   function c_toRegister() {
	   	  $this->assign('userName', $_SESSION['USERNAME']);
	   	  $this->display('register');
	   }
	   /**
	   * 我跟踪的线索
	   */
	   function c_toTrack() {
	   	  $this->assign('userName', $_SESSION['USERNAME']);
	   	  $this->display('track');
	   }

	/**
	 * 重写我跟踪的线索PageJson方法
	 */
	 function c_toTrackPageJson() {
	 	$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息

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
	 * 部门线索信息
	 * by maizp
	 */
 	function c_deptClues(){
 		$this->display('deptlist');
 	}

/******************start信息列表导出**************************/

	function c_exportExcel() {
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(600);
		$statusArr = array ('0' => '正常', '1' => '已转商机', '2' => '关闭', '3' => '暂停' );

		$colIdStr = $_GET ['colId'];
		$colNameStr = $_GET ['colName'];
		$status = $_GET ['status'];
		//表头Id数组
		$colIdArr = explode ( ',', $colIdStr );
		$colIdArr = array_filter ( $colIdArr );
		//表头Name数组
		$colNameArr = explode ( ',', $colNameStr );
		$colNameArr = array_filter ( $colNameArr );
		//表头数组
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

		//匹配导出列
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
/******************start信息列表导出************end**************/
 }
?>