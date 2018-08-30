<?php
/**
 * @author LiuBo
 * @Date 2011年3月3日 10:40:34
 * @version 1.0
 * @description:售前线索 Model层 售前线索
 */
 class model_projectmanagent_clues_clues  extends model_base {

 	private $status;	//线索状态位

 	public $statusDao; //状态类

	function __construct() {
		$this->tbl_name = "oa_sale_clues";
		$this->sql_map = "projectmanagent/clues/cluesSql.php";
		parent::__construct ();

		$this->statusDao = new model_common_status();
		$this->statusDao->status = array(
			0 => array(
				'statusEName' => 'normal',
				'statusCName' => '正常',
				'key' => '0'
			),
			1 => array(
				'statusEName' => 'transferred',
				'statusCName' => '已转商机',
				'key' => '1'
			),
			2 => array(
				'statusEName' => 'close',
				'statusCName' => '关闭',
				'key' => '2'
			),
			3 => array(
				'statusEName' => 'Pause',
				'statusCName' => '暂停',
				'key' => '3'
			)

		);

		//调用初始化对象陪着类
		parent::setObjAss();
	}

    /**
     * 重写add_d方法
     */
     function add_d($rows) {
     	try{
             $this->start_d();

             //新建线索的时候，线索的状态默认是“正常”
             $rows['status'] = $this->statusDao->statusEtoK('normal');

             $id = parent::add_d($rows,true);

             //保存跟踪人信息
             $trackmanNews = array();
             $trackmanId = explode("," , $rows['trackmanId']);
             $trackman = explode("," ,$rows['trackman']);

             foreach($trackmanId as $key =>$val) {
             	$trackmanNews[$key]['trackmanId'] = $val;
             	$trackmanNews[$key]['trackman'] = $trackman[$key];
             	$trackmanNews[$key]['cluesId'] = $id;
             	$trackmanNews[$key]['cluesName'] = $rows['cluesName'];
             }
			$linkmanDao=new model_projectmanagent_clues_linkman();
			$datadictDao = new model_system_datadict_datadict ();
             //添加客户联系人  add by suxc 2011-08-13
             if(is_array($rows['linkman'])){
             	foreach($rows['linkman'] as $lKey=>$lVal){
             		if($lVal['linkmanName']!=""){
						$lVal['cluesId'] = $id;
						$lVal['cluesName'] = $rows['cluesName'];
						$lVal['roleName'] = $datadictDao->getDataNameByCode ( $lVal['roleCode'] );
						$linkmanDao->add_d($lVal);
             		}
             	}
             }

             $trackmanDao = new model_projectmanagent_clues_trackman();
             $trackmanDao -> createBatch($trackmanNews);

             $this->commit_d();
             return true;
     	}catch (exception $e){
     		$this->rollBack();
     		return false;
     	}
     }

/**
	 * 重写edit方法
	 */
	function edit_d($object, $isEditInfo = false) {
		try{
			$this->start_d();
			//修改主表信息
			parent::edit_d($object,true);

			$cluesId = $object['id'];

              //保存跟踪人信息
             $trackmanNews = array();
             $trackmanId = explode("," , $object['trackmanId']);
             $trackman = explode("," ,$object['trackman']);
             foreach($trackmanId as $key =>$val) {
             	$trackmanNews[$key]['trackmanId'] = $val;
             	$trackmanNews[$key]['trackman'] = $trackman[$key];
             	$trackmanNews[$key]['cluesId'] = $cluesId;
             	$trackmanNews[$key]['cluesName'] = $object['cluesName'];
             }
			if($object['isEdit']=="edit"){
				$linkmanDao=new model_projectmanagent_clues_linkman();
				$datadictDao = new model_system_datadict_datadict ();
	            $linkmanDao->delete(array('cluesId' => $cluesId));
	             //添加客户联系人  add by suxc 2011-08-13
	             if(is_array($object['linkman'])){
	             	foreach($object['linkman'] as $lKey=>$lVal){
	             		if($lVal['linkmanName']!=""){
							$lVal['cluesId'] = $cluesId;
							$lVal['cluesName'] = $object['cluesName'];
							$lVal['roleName'] = $datadictDao->getDataNameByCode ( $lVal['roleCode'] );
							$linkmanDao->add_d($lVal);
	             		}
	             	}
	             }

			}

             $trackmanDao = new model_projectmanagent_clues_trackman();
             $trackmanDao->delete(array('cluesId' => $cluesId));
             $trackmanDao -> createBatch($trackmanNews);
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 *  重写get_d方法
	 */
	function get_d($id) {
		//提取主表信息
		$rows = parent::get_d($id);

       //获取线索跟踪人的信息
			$trackmanDao = new model_projectmanagent_clues_trackman();
			$conditions = array('cluesId' => $id);
			$rows['trackmaninfo'] = $trackmanDao->findAll($conditions);

		 return $rows;
	}

     /**
      * @description 关闭线索
      */
     function closeClues_d($rows){
     	if(is_array($rows)){
     		$rows['clues']['status'] = $this->statusDao->statusEtoK('close');
     	    $rows['clues']['closeTime'] = date('Y-m-d');
			$rows['clues']['closeId'] = $_SESSION['USER_ID'];
			$rows['clues']['closeName'] = $_SESSION['USERNAME'];
			$condiction = array('id' => $rows['id']);
			$flag = $this->update($condiction,$rows['clues']);
			return $flag;
     	}
     	return false;
     }

     /**
      * @description 暂停线索
      */
     function pauseClues_d($rows){
     	if(is_array($rows)){
     		$rows['clues']['status'] = $this->statusDao->statusEtoK('Pause');
     	    $rows['clues']['closeTime'] = date('Y-m-d');
			$rows['clues']['closeId'] = $_SESSION['USER_ID'];
			$rows['clues']['closeName'] = $_SESSION['USERNAME'];
			$condiction = array('id' => $rows['id']);
			$flag = $this->update($condiction,$rows['clues']);
			return $flag;
     	}
     	return false;
     }

     /**
      * @description 恢复线索
      */
     function recoverClues_d($rows){
     	if(is_array($rows)){
     		$rows['clues']['status'] = $this->statusDao->statusEtoK('normal');
     	    $rows['clues']['recoverTime'] = date('Y-m-d');
			$rows['clues']['recoverId'] = $_SESSION['USER_ID'];
			$rows['clues']['recoverName'] = $_SESSION['USERNAME'];
			$condiction = array('id' => $rows['id']);
			$flag = $this->update($condiction,$rows['clues']);
			return $flag;
     	}
     	return false;
     }
 /*****************************************************/
     function transfer_d($object) {
		try{
			$this->start_d();
			$cluesId = $object['id'];
			//修改线索表里的跟踪人信息
            $cluesinfo = $this->get_d($cluesId);
            $cluesTrackman = $cluesinfo['trackman'];//获取商机的跟踪人信息
            //处理跟踪人信息，并替换相对应的跟踪人
            $cluesTrackman = explode(",",$cluesTrackman);
            foreach($cluesTrackman as $k=>$v){
                 if($cluesTrackman[$k] == $object['trackUser']){
                      $cluesTrackman[$k] = $object['trackman'];
                 }
            }
            $cluesTrackman = implode(",",$cluesTrackman);
            $sql = "update ".$this->tbl_name." set trackman = '".$cluesTrackman."' where id = '$cluesId'";
            $this->_db->query($sql);
            //修改线索表里的跟踪人信息 结束
            //修改跟踪人从表里的信息
            $cont = array("cluesId" => $cluesId , "trackmanId" => $object['trackUserId'] );
            $trackmanDao = new model_projectmanagent_clues_trackman();
            $track = $trackmanDao->find($cont);//获取跟踪人从表里需要修改的那条信息
            $trackSql = "update ".$trackmanDao->tbl_name." set trackman = '".$object['trackman']."' , trackmanId = '".$object['trackmanId']."' where id = '$track[id]'";
            $this->_db->query($trackSql);
             //修改跟踪人从表里的信息 结束

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}
/***********************************************************************/
     /**
      * 处理部门主管选择跟踪人 页面需要的信息
      */
     function deptTrackInfo($row){

           $dept =  new model_common_otherdatas();
           $deptName = $dept->getUserDatas( $_SESSION['USER_ID'] , 'DEPT_NAME');//当前登录人所在部门名称
           $ditpId = $_SESSION['DEPT_ID'];//当前登录人所在部门Id
           $row['deptName'] = $deptName;
           $row['deptId']= $row['deptName'];
           $trackmanInfo = $row['trackmaninfo'];
           $deptMan = array();//定义登录人所在部门跟踪人数组
           $deptManOther = array();//其他跟踪人
           foreach ( $trackmanInfo as $key => $val){
           	    $trackmanInfo[$key]['deptName'] = $dept->getUserDatas( $trackmanInfo[$key]['trackmanId'] , 'DEPT_NAME');//根据登录人ID 查到其所在部门名称
           	    if ($trackmanInfo[$key]['deptName'] == $deptName){
                        $deptMan[$key]['name'] = $trackmanInfo[$key]['trackman'];
                        $deptMan[$key]['id'] = $trackmanInfo[$key]['trackmanId'];
           	    }else {
           	    	$deptManOther[$key]['name'] = $trackmanInfo[$key]['trackman'];
           	    	$deptManOther[$key]['id'] = $trackmanInfo[$key]['trackmanId'];
           	    }
           }
           foreach($deptMan as $key => $val){
           	   $row['deptman'][$key] = $deptMan[$key]['name'];
           	   $row['deptmanId'][$key] = $deptMan[$key]['id'];
           }
           foreach($deptManOther as $key => $val){
           	   $row['deptManOther'][$key] = $deptManOther[$key]['name'];
           	   $row['deptManOtherId'][$key] = $deptManOther[$key]['id'];
           }
           return $row;
     }
     /**
      *部门主管指定本部门跟踪人方法
      */
      function deptTrack_d($object) {
		try{
			$this->start_d();
			$cluesId = $object['id'];
            //处理线索表里的跟踪人信息
			$deptTrackmanNew = explode(',',$object['deptTrackmanOther']);
            $trackman = explode(',',$object['trackman']);

            foreach ($trackman as $key => $val){
            	array_push ($deptTrackmanNew , $trackman[$key]);
            }

            $deptTrackmanA = $deptTrackmanNew;//处理跟踪人从表是需要的数组
            $deptTrackmanNew = implode(',',$deptTrackmanNew);

            //修改线索表里的跟踪人信息
            $sql = "update ".$this->tbl_name." set trackman = '".$deptTrackmanNew."' where id = '$cluesId'";
            $this->_db->query($sql);

            //修改跟踪人从表里的信息
            $deptTrackmanNewId = explode(',',$object['deptTrackmanOtherId']);
            $trackmanId = explode(',',$object['trackmanId']);
            foreach ($trackmanId as $key => $val){
            	array_push ($deptTrackmanNewId , $trackmanId[$key]);
            }

             $trackmanNews = array();//要存入跟踪表的数组
             foreach($deptTrackmanNewId as $key =>$val) {
             	$trackmanNews[$key]['trackmanId'] = $val;
             	$trackmanNews[$key]['trackman'] = $deptTrackmanA[$key];
             	$trackmanNews[$key]['cluesId'] = $cluesId;
             	$trackmanNews[$key]['cluesName'] = $object['cluesName'];
             }

             $trackmanDao = new model_projectmanagent_clues_trackman();
             $trackmanDao->delete(array('cluesId' => $cluesId));
             $trackmanDao -> createBatch($trackmanNews);
             //修改跟踪人从表里的信息 结束

//            $this->rollBack();
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}
 }
?>