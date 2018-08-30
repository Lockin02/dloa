<?php
/**
 * @author Administrator
 * @Date 2012��1��12�� 15:55:18
 * @version 1.0
 * @description:��Ӧ������ Model��
 */
 include_once WEB_TOR . "module/phpExcel/classes/PHPExcel.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Cell.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";

 class model_supplierManage_assessment_supasses  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_suppasses";
		$this->sql_map = "supplierManage/assessment/supassesSql.php";
		parent::__construct ();
	}


	/**
	 * �½�������������ϸ��
	 */

	function add_d($object,$actType = ""){
		try{
			$this->start_d();
			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict ();
//			$object['ExaStatus']='δ�ύ';
			$object['assesState']='1';
			$object['assessTypeName'] =  $datadictDao->getDataNameByCode ( $object['assessType'] );
			$suppCode=$this->get_table_fields('oa_supp_lib','id='.$object['suppId'],'busiCode');
			$object['formCode']=date('YmdHi').$suppCode;
			$object['formDate']=date("Y-m-d");
			$assesYear="";
			$assesQuarter="";
			if($object['assessType']=='gysnd'){
                if($object['assesYear']!=""){
                    $assesYear=$object['assesYear'];
                }else{
                    $assesYear=date("Y")-1;
                }
			}else if($object['assessType']=='gysjd'){
                if($object['assesQuarter']!=""){
                    $assesQuarter=$object['assesQuarter'];
                    $assesYear=$object['assesYear'];
                }else{
                    if(date("n")<4){
                        $assesYear=date("Y")-1;
                        $assesQuarter="4";
                    }else{
                        $assesYear=date("Y");
                        switch(date("n")){
                            case "4":$assesQuarter="1";break;
                            case "5":$assesQuarter="1";break;
                            case "6":$assesQuarter="1";break;
                            case "7":$assesQuarter="2";break;
                            case "8":$assesQuarter="2";break;
                            case "9":$assesQuarter="2";break;
                            case "10":$assesQuarter="3";break;
                            case "11":$assesQuarter="3";break;
                            case "12":$assesQuarter="3";break;
                        }
                    }
                }
			}
			$object['assesYear']=$assesYear;
			$object['assesQuarter']=$assesQuarter;

			$object['formBelong'] = $_SESSION['USER_COM'];
			$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$object['businessBelong'] = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];
			$id=parent::add_d($object,true);

            $assesManId = array();
			//������ϸ��
			$assesmentitemDao=new model_supplierManage_assessment_assesmentitem();
			if(is_array($object['assesmentitem'])){
				foreach($object['assesmentitem'] as $key=>$val){
				    if(!in_array($val['assesManId'],$assesManId)){
                        $assesManId[] = $val['assesManId'];
                    }
					$val['parentId']=$id;
					$val['parentCode']=$object['formCode'];
					$assesmentitemDao->add_d($val);
				}
			}


			//��������С����Ա
			$menberDao=new model_supplierManage_assessment_assessmentmenber();
			if( is_array($object['menber'])){
				$assesManIdArr=explode(",",$object['menber']['assesManId']);
				$assesManNameArr=explode(",",$object['menber']['assesManName']);
				if(is_array($assesManIdArr)){
					foreach($assesManIdArr as $key=>$val){
						$menber['formCode']=$object['formCode'];
						$menber['parentId']=$id;
						$menber['assesManId']=$val;
						$menber['assesManName']=$assesManNameArr[$key];
						$menberDao->add_d($menber);
					}
				}
			}

            if(!empty($assesManId) && $actType == "audit"){
                // �ύ��,��������������˷���һ���ʼ�֪ͨ
                $assesManIds = implode(",",$assesManId);
                $emailDao = new model_common_mail();
                $receiveusers = $assesManIds;
                $emailInfo = $emailDao->toApplySuppasses(1, $_SESSION['USERNAME'],"",$object['formCode'],$object['suppName'],$object['assessTypeName'],$receiveusers);
            }

			//���¸���������ϵ
			$this->updateObjWithFile ( $id);

			//��������
			if (isset ( $_POST ['fileuploadIds'] ) && is_array ( $_POST ['fileuploadIds'] )) {
				$uploadFile = new model_file_uploadfile_management ();
				$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id);
			}
			//������������״̬
			if($object['taskId']>0){
				$taskDao=new model_supplierManage_assessment_task();
				$taskDao->accepTask_d($object['taskId'],'2');
			}

			$this->commit_d();
			return $id;

		}catch(Exception $e){
			$this->rollBack();
		}
	}


	/**
  * �½�������������ϸ��
  */

     function edit_d($object,$actType = ""){
         try{
             $this->start_d();
             $id=parent::edit_d($object,true);
             $assesManId = array();
             //������ϸ��
             $assesmentitemDao=new model_supplierManage_assessment_assesmentitem();
             if(is_array($object['assesmentitem'])){
                 foreach($object['assesmentitem'] as $key=>$val){
                     if(!in_array($val['assesManId'],$assesManId)){
                         $assesManId[] = $val['assesManId'];
                     }
                     $val['parentId']=$object['id'];
//					$val['parentCode']=$object['formCode'];
                     $assesmentitemDao->edit_d($val);
                 }
             }

             if(!empty($assesManId) && $actType == "audit"){
                 // �ύ��,��������������˷���һ���ʼ�֪ͨ
                 $assesManIds = implode(",",$assesManId);
                 $emailDao = new model_common_mail();
                 $receiveusers = $assesManIds;
                 $emailInfo = $emailDao->toApplySuppasses(1, $_SESSION['USERNAME'],"",$object['formCode'],$object['suppName'],$object['assessTypeName'],$receiveusers);
             }

             $menberDao=new model_supplierManage_assessment_assessmentmenber();
             //ɾ����������Ա
             $deleteCondition=array('parentId'=>$object['id']);
             $menberDao->delete($deleteCondition);

             //��������С����Ա
             if( is_array($object['menber'])){
                 $assesManIdArr=explode(",",$object['menber']['assesManId']);
                 $assesManNameArr=explode(",",$object['menber']['assesManName']);
                 if(is_array($assesManIdArr)){
                     foreach($assesManIdArr as $key=>$val){
                         $menber['formCode']="";
                         $menber['parentId']=$object['id'];
                         $menber['assesManId']=$val;
                         $menber['assesManName']=$assesManNameArr[$key];
                         $menberDao->add_d($menber);
                     }
                 }
             }

             //���¸���������ϵ
             $this->updateObjWithFile ( $id);

             //��������
             if (isset ( $_POST ['fileuploadIds'] ) && is_array ( $_POST ['fileuploadIds'] )) {
                 $uploadFile = new model_file_uploadfile_management ();
                 $uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $object['id']);
             }
             $this->commit_d();
             return $id;

         }catch(Exception $e){
             $this->rollBack();
         }
     }

     /**
      * �������ִ���
      */

     function asses_d($object){
         try{
             $this->start_d();
             //������ϸ��
             $assesmentitemDao=new model_supplierManage_assessment_assesmentitem();
             if(is_array($object['assesmentitem'])){
                 foreach($object['assesmentitem'] as $key=>$val){
                     if($val['assesScore']>0){
                         $val['affstate']=1;
                     }
                     //�ж��Ƿ��б䶯������
//                     if($val['assesManId']){
//
//                     }
                     $assesmentitemDao->edit_d($val);
                 }
             }
             //������ɱ�־
             $isDoneFlag=false;
             //�ж��Ƿ����������
             if($assesmentitemDao->findCount("affstate<>'1' and parentId=".$object['id'])==0){
                //��ȡ������ϸ
                $itemRows=$assesmentitemDao->getItemByParentId_d($object['id']);
                 if(is_array($itemRows)){
                        //���������ܷ�
                     $sumScore=$assesmentitemDao->getSumscoreByParentId_d($object['id']);
                     //���������ȼ�
                     $suppGrade="";
                     if($object['assessType']=="gysjd"||$object['assessType']=="gysnd"){
                        if($sumScore>90){
                            $suppGrade="A";
                        }else if($sumScore==75||$sumScore>75){
                            $suppGrade="B";
                        }else if($sumScore==60||$sumScore>60){
                            $suppGrade="C";
                        }else if($sumScore<60){
                            $suppGrade="D";
                        }
                     }else if($object['assessType']=="xgyspg"){
                         if($sumScore==70||$sumScore>70){
                             $suppGrade="C";
                         }
                     }
                     //���������ּܷ��ȼ�
                     $updateArr = array(
                         'id' => $object['id'],
                         'totalNum' => $sumScore,
                         'suppGrade' =>$suppGrade
                     );
                      $this->updateById($updateArr);
                 }
                 $isDoneFlag=true;
             }
             $this->commit_d();
             return $isDoneFlag;
         }catch(Exception $e){
             $this->rollBack();
         }
     }

	/**
	 * ����������Ӧ��
	 *
	 */
	function addSecondAss_d ($object) {
		try{
			$this->start_d();

			//���¹���������״̬
			$arrivaObj=array("id"=>$object['parentId'],
								"assesState"=>"3");
			$this->updateById($arrivaObj);

			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict ();
			$object['ExaStatus']='δ�ύ';
			$object['assesState']='1';
			$object['assessTypeName'] =  $datadictDao->getDataNameByCode ( $object['assessType'] );
			$suppCode=$this->get_table_fields('oa_supp_lib','id='.$object['suppId'],'busiCode');
			$object['formCode']=date('YmdHi').$suppCode;
			$object['formDate']=date("Y-m-d");
			$id=parent::add_d($object,true);
			//������ϸ��
			$assesmentitemDao=new model_supplierManage_assessment_assesmentitem();
			if(is_array($object['assesmentitem'])){
				foreach($object['assesmentitem'] as $key=>$val){
					unset($val['id']);
					$val['parentId']=$id;
					$val['parentCode']=$object['formCode'];
					$assesmentitemDao->add_d($val);
				}
			}
			//��������С����Ա
			$menberDao=new model_supplierManage_assessment_assessmentmenber();
			if( is_array($object['menber'])){
				$assesManIdArr=explode(",",$object['menber']['assesManId']);
				$assesManNameArr=explode(",",$object['menber']['assesManName']);
				if(is_array($assesManIdArr)){
					foreach($assesManIdArr as $key=>$val){
						$menber['formCode']=$object['formCode'];
						$menber['parentId']=$id;
						$menber['assesManId']=$val;
						$menber['assesManName']=$assesManNameArr[$key];
						$menberDao->add_d($menber);
					}
				}
			}

			//���¸���������ϵ
			$this->updateObjWithFile ( $id);

			//��������
			if (isset ( $_POST ['fileuploadIds'] ) && is_array ( $_POST ['fileuploadIds'] )) {
				$uploadFile = new model_file_uploadfile_management ();
				$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id);
			}
			$this->commit_d();
			return $id;

		}catch(Exception $e){
			$this->rollBack();
		}

	}
	/**
	 *��Ӧ����������������
	 *
	 */
	 function dealSuppass_d($object){
	 	$flibraryDao=new model_supplierManage_formal_flibrary();
	 	if($object['assessType']=="xgyspg"){//�����Ϊ�¹�Ӧ������������
			if($object['isFirst']==1&&$object['totalNum']>70||$object['totalNum']==70){//�����������Ǻϸ�����¹�Ӧ�̵ĵȼ�ΪC��
				$flibraryDao->updateField('id='.$object['suppId'],'suppGrade','C');
			}else if($object['isFirst']==2&&$object['totalNum']>70||$object['totalNum']==70){//��������
				$flibraryDao->updateField('id='.$object['suppId'],'suppGrade','C');
			}else if($object['isFirst']==2&&$object['totalNum']<70){//��������
				$flibraryDao->updateField('id='.$object['suppId'],'suppGrade','D');//��������������ϸ񣬸ս���Ӧ�̵ĵȼ���Ϊ���ϸ�
			}
	 	}
	 	if($object['assessType']=="gysnd"){//��Ӧ����ȿ���
	 		$assesYear=substr($object['formDate'],0,4)-1;//�������
	 		//��ȡ��Ӧ�̼��ȿ�����Ϣ
			$searchArr = array (
				"assesYear" => $assesYear,
				"assessType" => "gysnd",
				"suppId"=> $object['suppId'],
				"ExaStatus"=> "���"
			);
			$this->__SET ( 'searchArr', $searchArr );
			$rows = $this->listBySqlId ("select_assesInfo");
			$avgTotalNum=0;//���ȿ���ƽ����
			if(is_array($rows)){
				$numb=0;//��������
				$sumTotalNum=0;
				foreach($rows as $key=>$val){
					$numb++;
					$sumTotalNum=$sumTotalNum+$val['totalNum'];
				}
				$avgTotalNum=$sumTotalNum/$numb;
			}
			$resultNumb=$avgTotalNum*0.6+$object['totalNum']*0.4;//��ȿ������յ÷�Ӧ��
			if($resultNumb>90){
				$yearSupGrade="A";
				$flibraryDao->updateField('id='.$object['suppId'],'suppGrade','A');
			}else if($resultNumb>75){
				$yearSupGrade="B";
				$flibraryDao->updateField('id='.$object['suppId'],'suppGrade','B');
			}else if($resultNumb>60||$resultNumb==60){
				$yearSupGrade="C";
				$flibraryDao->updateField('id='.$object['suppId'],'suppGrade','C');
			}else {
				$yearSupGrade="D";
				$flibraryDao->updateField('id='.$object['suppId'],'suppGrade','D');
			}
			$asseseArr=array(
				"id"=>$object['id'],
				"yearSupGrade"=>$yearSupGrade,
				"yearTotal"=>$resultNumb,
			);
			$this->edit_d($asseseArr,true);
	 	}
	 	if($object['assessType']=="gysjd"){//��Ӧ����ȿ���
	 		$assesYear=substr($object['formDate'],0,4);//�������
	 		//��ȡ��Ӧ�̼��ȿ�����Ϣ
			$searchArr = array (
				"year" => $assesYear,
				"assessType" => "gysjd",
				"suppId"=> $object['suppId'],
				"ExaStatus"=> "���"
			);
			$this->__SET ( 'searchArr', $searchArr );
			$rows = $this->listBySqlId ("select_assesInfo");
			if(is_array($rows)){
				$numb=0;//��������
				foreach($rows as $key=>$val){
					if($val['totalNum']<60){
						$numb++;
					}
				}
				if($numb>2){//���ȿ������μ��������ϲ��ϸ񣬴Ӻϸ��ɾ��
					$flibraryDao->updateField('id='.$object['suppId'],'suppGrade','D');
				}
			}
			if($this->isGradeToB_d($object['suppId'])){//�ж��¹�Ӧ���Ƿ��������ο�����90�ֽ���ΪB��
				$flibraryDao->updateField('id='.$object['suppId'],'suppGrade','B');
			}
	 	}

	 }

	 /**�жϹ�Ӧ�̸ü����Ƿ��ѽ��м��ȿ���
	 * @author suxc
	 *
	 */
	  function isAssesQuarter_d($suppId){
	  	$month=date("n");
	  	switch($month){
	  		case "1":$beginDate=date("Y")."-01";$endDate=date("Y")."-03";break;
	  		case "2":$beginDate=date("Y")."-01";$endDate=date("Y")."-03";break;
	  		case "3":$beginDate=date("Y")."-01";$endDate=date("Y")."-03";break;
	  		case "4":$beginDate=date("Y")."-04";$endDate=date("Y")."-06";break;
	  		case "5":$beginDate=date("Y")."-04";$endDate=date("Y")."-06";break;
	  		case "6":$beginDate=date("Y")."-04";$endDate=date("Y")."-06";break;
	  		case "7":$beginDate=date("Y")."-07";$endDate=date("Y")."-09";break;
	  		case "8":$beginDate=date("Y")."-07";$endDate=date("Y")."-09";break;
	  		case "9":$beginDate=date("Y")."-07";$endDate=date("Y")."-09";break;
	  		case "10":$beginDate=date("Y")."-10";$endDate=date("Y")."-12";break;
	  		case "11":$beginDate=date("Y")."-10";$endDate=date("Y")."-12";break;
	  		case "12":$beginDate=date("Y")."-10";$endDate=date("Y")."-12";break;
	  	}
 		//��ȡ��Ӧ�̼��ȿ�����Ϣ
		$searchArr = array (
			"beginDate" => $beginDate,
			"endDate" => $endDate,
			"assessType" => "gysjd",
			"suppId"=>$suppId
		);
		$this->__SET ( 'searchArr', $searchArr );
		$rows = $this->listBySqlId ("select_assesInfo");
		if(is_array($rows)){
			return 0;
		}else{
			return 1;
		}

	  }

	 /**�жϹ�Ӧ�̸ü����Ƿ��ѽ�����ȿ���
	 * @author suxc
	 *
	 */
	  function isAssesYear_d($suppId){
	 		$assesYear=date("Y");//�������
	 		//��ȡ��Ӧ����ȿ�����Ϣ
			$searchArr = array (
				"year" => $assesYear,
				"assessType" => "gysnd",
				"suppId"=> $suppId
			);
			$this->__SET ( 'searchArr', $searchArr );
			$rows = $this->listBySqlId ("select_assesInfo");
		$this->__SET ( 'searchArr', $searchArr );
		$rows = $this->listBySqlId ("select_assesInfo");
		if(is_array($rows)){
			return 0;
		}else{
			return 1;
		}

	  }

	 /**�жϹ�Ӧ�̸ü����Ƿ��ѽ�����ȿ���
	 * @author suxc
	 *
	 */
	  function isAssesNew_d($suppId){
	 		//��ȡ��Ӧ���¹�Ӧ�̿�����Ϣ
			$searchArr = array (
				"assessType" => "xgyspg",
				"suppId"=> $suppId
			);
			$this->__SET ( 'searchArr', $searchArr );
			$rows = $this->listBySqlId ("select_assesInfo");
		$this->__SET ( 'searchArr', $searchArr );
		$rows = $this->listBySqlId ("select_assesInfo");
		if(is_array($rows)){
			return 0;
		}else{
			return 1;
		}

	  }

	 /**�ж��¹�Ӧ���Ƿ��������ο�����90�ֽ���ΪB��
	 * @author Administrator
	 *
	 */
	 function isGradeToB_d($suppId){
 		//��ȡ��Ӧ�̹�Ӧ�̿�����Ϣ
		$searchArr = array (
			"assessTypeArr" => "gysnd,gysjd",
			"suppId"=> $suppId
		);
		$this->__SET ( 'searchArr', $searchArr );
		$rows = $this->listBySqlId ("select_assesInfo");
		if(is_array($rows)){
			if(count($rows)==3){
				$flag=true;
				foreach($rows as $key=>$val){ //�ж��¹�Ӧ���Ƿ��������ο�����90�ֽ���ΪB��
					if($val['totalNum']<90||$val['ExaStatus']!="���"){
						$flag=false;
						break;
					}
				}
				return $flag;
			}
		}

	 }
    /**ɾ������
	*/
	function deletesInfo_d($id) {
		try {
			$this->start_d();
			$row=$this->get_d($id);
			if(is_array($row)&&$row['taskId']>0){//������������״̬
				$taskDao=new model_supplierManage_assessment_task();
				$taskDao->accepTask_d($row['taskId'],'1');

			}

			$this->deletes ($id);
			$this->commit_d();
			return true;
		}catch ( Exception $e ) {
			$this->rollBack();
			return false;
		}
	}

     /**�ύ����
      */
     function sumbitAsses_d($id) {
         try {
             $this->start_d();
             $this->updateField(array(
                 "id" =>$id
             ),"ExaStatus","������");

             // �ύ��,��������������˷���һ���ʼ�֪ͨ
             $getInfoSql = "select s.id,s.formCode,s.assessTypeName,s.suppName,d.assesMan,d.assesManId from oa_supp_suppasses s left join (SELECT parentId,GROUP_CONCAT(t.assesMan) as assesMan,GROUP_CONCAT(t.assesManId) as assesManId from (select parentId,assesMan,assesManId from oa_supp_suppasses_detail where affstate = 0 group by parentId,assesManId)t group by t.parentId)d on d.parentId = s.id where s.id = {$id}";
             $infoArr = $this->_db->getArray($getInfoSql);
             if($infoArr){
                 $emailDao = new model_common_mail();
                 $receiveusers = $infoArr[0]['assesManId'];
                 $emailInfo = $emailDao->toApplySuppasses(1, $_SESSION['USERNAME'],"",$infoArr[0]['formCode'],$infoArr[0]['suppName'],$infoArr[0]['assessTypeName'],$receiveusers);
             }

             $this->commit_d();
             return true;
         }catch ( Exception $e ) {
             $this->rollBack();
             return false;
         }
     }
     /**
      * ��ȡ���е�ǰ��¼��Ϊȷ���˵�����ID
      */
     function affirmUserInfo($userId){
         $sql = "select parentId from oa_supp_suppasses_detail where find_in_set('".$userId."',assesManId)  group by parentId";
         $arr = $this->_db->getArray($sql);
         $ids='';
         if(is_array($arr)){
             foreach ($arr as $v){
                 $ids .= $v['parentId'].",";
             }
             $ids = rtrim($ids, ',');
         }

         return $ids;
     }

     /**
      * ��ȡ�����쵼ID
      */
     function getDeptLeader($parentId){
         $sql = "select group_concat(m.ViceManager) as leaderIds from  oa_supp_suppasses_detail d left join department m on d.assesDeptId=m.DEPT_ID where d.parentId=".$parentId." group by d.parentId";
         $arr = $this->_db->getArray($sql);
         $leaderIds='';
         if(is_array($arr)){
             $leaderIds=$arr[0]['leaderIds'];
         }
         return $leaderIds;
     }

	/**
	 * ����2012��Ӧ��������Ϣ
	 *
	 */
	 function addFistData_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();
		$inArr = array();//��������
		$userArr = array();//�û�����
		$deptArr = array();//��������
		$jobsArr = array();//ְλ����
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){

				//������ѭ��
				foreach($excelData as $key => $val){
					if($key <2){
						continue ;
					}
					$actNum = $key - 1;
					if(empty($val[2]) && empty($val[1])){
						continue;
					}else{
						//��������
						$inArr = array();


						//Ա������
						if(!empty($val[1])){
							if(!isset($userArr[$val[0]])){
								$rs=$this->get_table_fields('oa_supp_lib', "busiCode='".$val[1]."'", 'id');
								if(!empty($rs)&&$rs>0){
									$inArr['suppId']=$rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<font color=red>����ʧ��!�����ڵĹ�Ӧ�̱��</font>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
//							$inArr['userNo'] = $val[0];
							$inArr['formCode']=date('YmdHi').$val[1];
							$inArr['assesYear']='2012';
							$inArr['assesQuarter']='1';
							$inArr['assessType']='gysjd';
							$inArr['assessTypeName']='��Ӧ�̼��ȿ���';
							$inArr['ExaStatus']='���';
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��Ӧ�̱��Ϊ��</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//��Ӧ������
						if(!empty($val[2])){
							$val[2] = trim($val[2]);
							$inArr['suppName'] = $val[2];
						}

						//��Ӫ��Ʒ
						if(!empty($val[3])){
							$inArr['mainProduct'] = $val[3];
						}

						//�ɹ�������
						if(!empty($val[4])){
							$userIdArr=$otherDataDao->getUserID(trim($val[4]));
							$inArr['assesManId'] = $userIdArr[0]['USER_ID'];
							$inArr['assesManName'] = $val[4];
						}

						//
						if(!empty($val[5])){
						}


						//��һ������������
						if(!empty($val[9])){
							$val[9] = trim($val[9]);
							$inArr['totalNum'] = $val[9];
							if($val[9]>90){
								$inArr['suppGrade'] = 'A';
							}else if($val[9]==75||$val[9]>75){
								$inArr['suppGrade'] ='B';
							}else if($val[9]>60||$val[9]==60){
								$inArr['suppGrade'] ='C';
							}else if($val[9]<60){
								$inArr['suppGrade'] = 'D';
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��һ������������Ϊ��</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}


//						print_r($inArr);
						$newId = parent::add_d($inArr,true);
						if($newId){
							$tempArr['result'] = '����ɹ�';
						}else{
							$tempArr['result'] = '����ʧ��';
						}
						$tempArr['docCode'] = '��' . $actNum .'������';
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			}
		}
	}

	/**
	 * workflow callback
	 */
	 function workflowCallBack($spid){
	 	$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);
		$objId = $folowInfo ['objId'];
		if (! empty ( $objId )) {
			$rows= $this->get_d ( $objId );
			if($rows['ExaStatus']=="���"){
				//�������ͨ���������;����
				$this->dealSuppass_d($rows);
			}
		}
	 }
}
?>