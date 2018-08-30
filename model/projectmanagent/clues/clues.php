<?php
/**
 * @author LiuBo
 * @Date 2011��3��3�� 10:40:34
 * @version 1.0
 * @description:��ǰ���� Model�� ��ǰ����
 */
 class model_projectmanagent_clues_clues  extends model_base {

 	private $status;	//����״̬λ

 	public $statusDao; //״̬��

	function __construct() {
		$this->tbl_name = "oa_sale_clues";
		$this->sql_map = "projectmanagent/clues/cluesSql.php";
		parent::__construct ();

		$this->statusDao = new model_common_status();
		$this->statusDao->status = array(
			0 => array(
				'statusEName' => 'normal',
				'statusCName' => '����',
				'key' => '0'
			),
			1 => array(
				'statusEName' => 'transferred',
				'statusCName' => '��ת�̻�',
				'key' => '1'
			),
			2 => array(
				'statusEName' => 'close',
				'statusCName' => '�ر�',
				'key' => '2'
			),
			3 => array(
				'statusEName' => 'Pause',
				'statusCName' => '��ͣ',
				'key' => '3'
			)

		);

		//���ó�ʼ������������
		parent::setObjAss();
	}

    /**
     * ��дadd_d����
     */
     function add_d($rows) {
     	try{
             $this->start_d();

             //�½�������ʱ��������״̬Ĭ���ǡ�������
             $rows['status'] = $this->statusDao->statusEtoK('normal');

             $id = parent::add_d($rows,true);

             //�����������Ϣ
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
             //��ӿͻ���ϵ��  add by suxc 2011-08-13
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
	 * ��дedit����
	 */
	function edit_d($object, $isEditInfo = false) {
		try{
			$this->start_d();
			//�޸�������Ϣ
			parent::edit_d($object,true);

			$cluesId = $object['id'];

              //�����������Ϣ
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
	             //��ӿͻ���ϵ��  add by suxc 2011-08-13
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
	 *  ��дget_d����
	 */
	function get_d($id) {
		//��ȡ������Ϣ
		$rows = parent::get_d($id);

       //��ȡ���������˵���Ϣ
			$trackmanDao = new model_projectmanagent_clues_trackman();
			$conditions = array('cluesId' => $id);
			$rows['trackmaninfo'] = $trackmanDao->findAll($conditions);

		 return $rows;
	}

     /**
      * @description �ر�����
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
      * @description ��ͣ����
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
      * @description �ָ�����
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
			//�޸���������ĸ�������Ϣ
            $cluesinfo = $this->get_d($cluesId);
            $cluesTrackman = $cluesinfo['trackman'];//��ȡ�̻��ĸ�������Ϣ
            //�����������Ϣ�����滻���Ӧ�ĸ�����
            $cluesTrackman = explode(",",$cluesTrackman);
            foreach($cluesTrackman as $k=>$v){
                 if($cluesTrackman[$k] == $object['trackUser']){
                      $cluesTrackman[$k] = $object['trackman'];
                 }
            }
            $cluesTrackman = implode(",",$cluesTrackman);
            $sql = "update ".$this->tbl_name." set trackman = '".$cluesTrackman."' where id = '$cluesId'";
            $this->_db->query($sql);
            //�޸���������ĸ�������Ϣ ����
            //�޸ĸ����˴ӱ������Ϣ
            $cont = array("cluesId" => $cluesId , "trackmanId" => $object['trackUserId'] );
            $trackmanDao = new model_projectmanagent_clues_trackman();
            $track = $trackmanDao->find($cont);//��ȡ�����˴ӱ�����Ҫ�޸ĵ�������Ϣ
            $trackSql = "update ".$trackmanDao->tbl_name." set trackman = '".$object['trackman']."' , trackmanId = '".$object['trackmanId']."' where id = '$track[id]'";
            $this->_db->query($trackSql);
             //�޸ĸ����˴ӱ������Ϣ ����

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}
/***********************************************************************/
     /**
      * ����������ѡ������� ҳ����Ҫ����Ϣ
      */
     function deptTrackInfo($row){

           $dept =  new model_common_otherdatas();
           $deptName = $dept->getUserDatas( $_SESSION['USER_ID'] , 'DEPT_NAME');//��ǰ��¼�����ڲ�������
           $ditpId = $_SESSION['DEPT_ID'];//��ǰ��¼�����ڲ���Id
           $row['deptName'] = $deptName;
           $row['deptId']= $row['deptName'];
           $trackmanInfo = $row['trackmaninfo'];
           $deptMan = array();//�����¼�����ڲ��Ÿ���������
           $deptManOther = array();//����������
           foreach ( $trackmanInfo as $key => $val){
           	    $trackmanInfo[$key]['deptName'] = $dept->getUserDatas( $trackmanInfo[$key]['trackmanId'] , 'DEPT_NAME');//���ݵ�¼��ID �鵽�����ڲ�������
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
      *��������ָ�������Ÿ����˷���
      */
      function deptTrack_d($object) {
		try{
			$this->start_d();
			$cluesId = $object['id'];
            //������������ĸ�������Ϣ
			$deptTrackmanNew = explode(',',$object['deptTrackmanOther']);
            $trackman = explode(',',$object['trackman']);

            foreach ($trackman as $key => $val){
            	array_push ($deptTrackmanNew , $trackman[$key]);
            }

            $deptTrackmanA = $deptTrackmanNew;//��������˴ӱ�����Ҫ������
            $deptTrackmanNew = implode(',',$deptTrackmanNew);

            //�޸���������ĸ�������Ϣ
            $sql = "update ".$this->tbl_name." set trackman = '".$deptTrackmanNew."' where id = '$cluesId'";
            $this->_db->query($sql);

            //�޸ĸ����˴ӱ������Ϣ
            $deptTrackmanNewId = explode(',',$object['deptTrackmanOtherId']);
            $trackmanId = explode(',',$object['trackmanId']);
            foreach ($trackmanId as $key => $val){
            	array_push ($deptTrackmanNewId , $trackmanId[$key]);
            }

             $trackmanNews = array();//Ҫ������ٱ������
             foreach($deptTrackmanNewId as $key =>$val) {
             	$trackmanNews[$key]['trackmanId'] = $val;
             	$trackmanNews[$key]['trackman'] = $deptTrackmanA[$key];
             	$trackmanNews[$key]['cluesId'] = $cluesId;
             	$trackmanNews[$key]['cluesName'] = $object['cluesName'];
             }

             $trackmanDao = new model_projectmanagent_clues_trackman();
             $trackmanDao->delete(array('cluesId' => $cluesId));
             $trackmanDao -> createBatch($trackmanNews);
             //�޸ĸ����˴ӱ������Ϣ ����

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