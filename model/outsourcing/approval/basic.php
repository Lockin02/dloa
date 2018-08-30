<?php
/**
 * @author Administrator
 * @Date 2013年11月19日 星期二 23:55:18
 * @version 1.0
 * @description:外包立项 Model层 合同状态
                            0.未提交
                            1.审批中
                            2.执行中
                            3.已关闭
                            4.变更中
 */
 class model_outsourcing_approval_basic  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_approval";
		$this->sql_map = "outsourcing/approval/basicSql.php";
		parent::__construct ();
	}

	//公司权限处理 TODO
	protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分

	/**新增*/
	function add_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['formCode']="WBL".date("Ymd").time();
			$object['outsourcingName'] = $datadictDao->getDataNameByCode($object['outsourcing']);
			$object['taxPoint'] = $datadictDao->getDataNameByCode($object['taxPointCode']);
			$object['payTypeName'] = $datadictDao->getDataNameByCode($object['payType']);
			//项目整包取数
			$projectRental = $object['projectRental'];

			//获取归属公司名称
			$object['formBelong'] = $_SESSION['USER_COM'];
			$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$object['businessBelong'] = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

			//新增主表信息
			$id = parent :: add_d($object, true);

			$persronRentalDao=new model_outsourcing_approval_persronRental();
			if(is_array($object['personList'])&&$object['outsourcing']=='HTWBFS-02'){//人员租赁
				foreach($object['personList'] as $key=>$val){
					$val['mainId']=$id;
					$persronRentalDao->add_d($val);
				}
			}
			if(is_array($object['projectRental'])&&($object['outsourcing']=='HTWBFS-03'||$object['outsourcing']=='HTWBFS-01')){//整包/分包
				$projectrentalDao = new model_outsourcing_approval_projectRental();
				$projectRental = $projectrentalDao->dataFormat_d($projectRental);//格式化数据,转成正常数据
				$projectRental = util_arrayUtil :: setArrayFn(array('mainId' => $id), $projectRental);
				$projectrentalDao->saveDelBatch($projectRental);
			}

			//更新外包申请单状态
			if($object['applyId']){
				$applyDao=new model_outsourcing_outsourcing_apply();
				$applyDao->updateField('id='.$object['applyId'],'state','1');
			}
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

	/**编辑*/
	function edit_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['outsourcingName'] = $datadictDao->getDataNameByCode($object['outsourcing']);
			$object['taxPoint'] = $datadictDao->getDataNameByCode($object['taxPointCode']);
			$object['payTypeName'] = $datadictDao->getDataNameByCode($object['payType']);

			//项目整包取数
			$projectRental = $object['projectRental'];
			//新增主表信息
			$id = parent :: edit_d($object, true);

			if(is_array($object['personList'])&&$object['outsourcing']=='HTWBFS-02'){//人员租赁
				$persronRentalDao=new model_outsourcing_approval_persronRental();
				$persronRentalDao->delete(array ('mainId' =>$object['id']));
				foreach($object['personList'] as $key=>$val){
					$val['mainId']=$object['id'];
					$persronRentalDao->add_d($val);
				}
			}
			if(is_array($projectRental)&&($object['outsourcing']=='HTWBFS-03'||$object['outsourcing']=='HTWBFS-01')){//整包/分包
				$projectrentalDao = new model_outsourcing_approval_projectRental();
				$projectRental = $projectrentalDao->dataFormat_d($projectRental);//格式化数据,转成正常数据
				$projectRental = util_arrayUtil :: setArrayFn(array('mainId' => $object['id']), $projectRental);
				$projectrentalDao->saveDelBatch($projectRental);
			}

			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

	/**
	 * 批量删除对象
	 */
	function deletes_d($ids) {
		try {
			$row=$this->get_d($ids);
			//更新外包申请单状态
			if($row['applyId']){
				$applyDao=new model_outsourcing_outsourcing_apply();
				$applyDao->updateField('id='.$row['applyId'],'state','0');
			}
			$this->deletes ( $ids );
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}

	/**
	 *审批处理业务
	 */
	function dealAduit_d($id) {
		try {
			$row=$this->get_d($id);
			$personnelDao=new model_outsourcing_supplier_personnel();
			if($row['outsourcing']=='HTWBFS-02'){//人员租赁
				$persronRentalDao=new model_outsourcing_approval_persronRental();
				$$persronRows=$persronRentalDao->findAll(array ('mainId' =>$id));
				if(is_array($$persronRows)){
					foreach($$persronRows as $key=>$val){
						$suppCode=$this->get_table_fields('oa_outsourcesupp_supplib', "id='".$val['suppId']."'", 'suppCode');
						$userId=$this->get_table_fields('user', "USER_ID='".$suppCode."'", 'USER_ID');
						if(!$userId){
							//添加oa账号
							$userArr=array(
					 					   'userName' =>$val['suppName'],
					                       'userId' =>$suppCode,
					                       'sex' =>'男',
					                       'email' =>''
				                       );
						    $userId=$personnelDao->createUser_d($userArr);
						}
					}

				}
			}
			if(($row['outsourcing']=='HTWBFS-03'||$row['outsourcing']=='HTWBFS-01')){//整包/分包
				if($row['suppId']){
					$userId=$this->get_table_fields('user', "USER_ID='".$row['suppCode']."'", 'USER_ID');
					if(!$userId){
						//添加oa账号
						$userArr=array(
				 					   'userName' =>$row['suppName'],
				                       'userId' =>$row['suppCode'],
				                       'sex' =>'男',
				                       'email' =>''
			                       );
					    $userId=$personnelDao->createUser_d($userArr);
					}
				}
			}
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}

		/***************** S 变更系列 *********************/
	/**
	 * 变更操作
	 */
	function change_d($object){

		try{
			$this->start_d();
			$projectrentalDao = new model_outsourcing_approval_projectRental();

			//数据字典处理
			$datadictDao = new model_system_datadict_datadict();
			$object['outsourcingName'] = $datadictDao->getDataNameByCode($object['outsourcing']);
			$object['taxPoint'] = $datadictDao->getDataNameByCode($object['taxPointCode']);
			$object['payTypeName'] = $datadictDao->getDataNameByCode($object['payType']);

			//实例化变更类
			$changeLogDao = new model_common_changeLog ( 'outsourcingapproval' );

			if(is_array($object['personList'])&&$object['outsourcing']=='HTWBFS-02'){//人员租赁
				foreach($object['personList'] as $key=>$val){
					if(isset($val['id'])){
						$object['personList'][$key]['oldId']=$val['id'];
						unset($object['personList'][$key]['id']);
					}
				}
			}
			if(is_array($object['projectRental'])&&($object['outsourcing']=='HTWBFS-03'||$object['outsourcing']=='HTWBFS-01')){//整包/分包
				$object['projectRental'] = $projectrentalDao->dataFormat_d($object['projectRental']);//格式化数据,转成正常数据
				foreach($object['projectRental'] as $key=>$val){
					if(isset($val['id'])){
						$object['projectRental'][$key]['oldId']=$val['id'];
						unset($object['projectRental'][$key]['id']);
					}
				}
			}

			//附件处理
//			$object['uploadFiles'] = $changeLogDao->processUploadFile ( $object, $this->tbl_name );

			//建立变更信息
			$tempObjId = $changeLogDao->addLog ( $object );

			$this->commit_d();
			return $tempObjId;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}


	/**
	 * 变更审批完成后更新状态
	 */
	function dealAfterAuditChange_d($objId,$userId){
		$obj = $this->get_d($objId);
	 	if($obj['ExaStatus'] == AUDITED){
	 		try{
	 			$this->start_d();

				//变更信息处理
		 		$changeLogDao = new model_common_changeLog ( 'outsourcingapproval' );
				$changeLogDao->confirmChange_d ( $obj );

                $this->update(array('id'=>$obj['originalId']),array('ExaStatus' => '完成'));
				if($obj['applyId']){
					$applyDao=new model_outsourcing_outsourcing_apply();
					$applyDao->updateField('id='.$obj['applyId'],'state','4');
				}

	 			$this->commit_d();
	 			return 1;
	 		}catch(Exception $e){
	 			$this->rollBack();
	 			return 1;
	 		}
        }else{
            try{
                $this->start_d();

                $this->update(array('id'=>$obj['originalId']),array('ExaStatus' => '完成'));//更新外包申请单状态
				if($obj['applyId']){
					$applyDao=new model_outsourcing_outsourcing_apply();
					$applyDao->updateField('id='.$obj['applyId'],'state','4');
				}

                $this->commit_d();
                return 1;
            }catch(Exception $e){
                $this->rollBack();
                return 1;
            }
        }
	 	return 1;
	}

	/*
	 * 发送邮件通知外包申请流相关人员
	 */
	 function sendApplyMail_d( $obj ) {
	 	$applyDao = new model_outsourcing_outsourcing_apply();
	 	$receiverId = $applyDao->getMailReceiver_d( $obj['applyId'] );
	 	$applyObj = $applyDao->get_d( $obj['applyId'] );
	 	$esmprojectDao = new model_engineering_project_esmproject();
		$esmprojectObj = $esmprojectDao->get_d($applyObj['projectId']);
	 	$emailDao = new model_common_mail();
	 	$mailContent = '您好！此邮件为外包立项审批通过通知，详细信息如下：<br>'.
		'单据编号：<span style="color:blue">'.$applyObj['applyCode'].
		'</span><br>归属区域：<span style="color:blue">'.$esmprojectObj['officeName'].
		'</span><br>项目省份：<span style="color:blue">'.$esmprojectObj['province'].
		'</span><br>项目名称：<span style="color:blue">'.$applyObj['projecttName'].
		'</span><br>项目编号：<span style="color:blue">'.$applyObj['projectCode'].
		'</span><br>申请人：<span style="color:blue">'.$applyObj['createName'].
		'</span><br>申请时间：<span style="color:blue">'.$applyObj['createTime'].
		'</span><br>';
	 	if($obj['outsourcing']=='HTWBFS-03' || $obj['outsourcing']=='HTWBFS-01') {
	 		$mailContent .= '外包供应商：<span style="color:blue">'.$obj['suppName'].
		'</span><br>开始日期：<span style="color:blue">'.$obj['beginDate'].
		'</span><br>结束日期：<span style="color:blue">'.$obj['endDate'].
		'</span>';
	 	}else {
			$mailContent .= '人员租赁信息:<br><table border="1px" cellspacing="0px" style="text-align:center"><tr rowspan="2" style="background-color:#fff999"><td rowspan="2" width="50px">级别</td><td rowspan="2" width="50px">姓名</td><td rowspan="2">归属外包供应商</td><td colspan="3">租赁周期</td><td colspan="4">价格对比</td><td rowspan="2" width="80px">工作技能要求</td><td rowspan="2" width="100px">备注</td></tr><tr style="background-color:#fff999"><td>租赁开始日期</td><td>租赁结束日期</td><td>天数</td><td>服务人力成本单价(元/天)</td><td>服务人力成本</td><td>外包单价(元/天)</td><td>外包价格</td></tr>';
			$persronRentalDao = new model_outsourcing_approval_persronRental();
			$persronRentalRows = $persronRentalDao->findAll(array('mainId' => $obj['id']));
			if (is_array ($persronRentalRows)) {
				foreach($persronRentalRows as $k => $v) {
					$mailContent .= '<tr><td>'.$v['personLevelName'].'</td><td>'.$v['pesonName'].'</td><td>'.$v['suppName'].'</td><td>'.$v['beginDate'].'</td><td>'.$v['endDate'].'</td><td>'.$v['totalDay'].'</td><td>'.number_format($v['inBudgetPrice'],2).'</td><td>'.number_format($v['selfPrice'],2).'</td><td>'.number_format($v['outBudgetPrice'],2).'</td><td>'.number_format($v['rentalPrice'],2).'</td><td style="text-align:left">'.$v['skillsRequired'].'</td><td style="text-align:left">'.$v['remark'].'</td></tr>';
				}
			}
			$mailContent .= '</table>';
	 	}
	 	$emailDao->mailClear("外包申请受理结果" ,$receiverId ,$mailContent);
	 }

	 /**
	  * workflow callback
	  */
	  function workflowCallBack($spid){
	  	$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);
		if($folowInfo['examines']=="ok"){  //审批通过
			$obj = $this->get_d ( $folowInfo['objId'] );
		 	$this->dealAduit_d ( $folowInfo['objId'] );
	//				$this->service->sendApplyMail_d($obj);
		}
	  }

 }
?>