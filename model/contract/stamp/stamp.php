<?php
/**
 * @author Show
 * @Date 2011年12月16日 星期五 11:24:28
 * @version 1.0
 * @description:盖章记录(oa_sale_stamp) Model层
 */
class model_contract_stamp_stamp  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_stamp";
		$this->sql_map = "contract/stamp/stampSql.php";
		parent::__construct ();
	}

	/********************** 策略部分 **********************/

	//策略类数组
	//不同类型入库申请策略类,根据需要在这里进行追加
	private $relatedStrategyArr = array (
		'HTGZYD-01' => 'model_contract_stamp_strategy_outsourcing', //外包合同
		'HTGZYD-02' => 'model_contract_stamp_strategy_other', //其他合同
		'HTGZYD-03' => 'model_contract_stamp_strategy_purcontract', //采购订单
		'HTGZYD-04' => 'model_contract_stamp_strategy_contract', //鼎利合同
        'HTGZYD-05' => 'model_contract_stamp_strategy_notcontract', //非合同类
        'HTGZYD-06' => 'model_contract_stamp_strategy_invoiceapply', //开票申请
        'HTGZYD-07' => 'model_contract_stamp_strategy_rentcar' //租车合同
	);

	/**
	 * 根据数据类型返回类
	 */
	public function getClass($objType){
		return $this->relatedStrategyArr[$objType];
	}

	//对应业务代码
	private $relatedCode = array (
		'HTGZYD-01' => 'outsourcing', //外包合同
		'HTGZYD-02' => 'other', //其他合同
		'HTGZYD-03' => 'purcontract', //采购订单
		'HTGZYD-04' => 'contract', //鼎利合同
        'HTGZYD-05' => 'notcontract', //非合同类
        'HTGZYD-06' => 'invoiceapply', //开票申请
        'HTGZYD-07' => 'rentcar' //租车合同
	);

	/**
	 * 根据类型返回业务名称
	 */
	public function getBusinessCode($objType){
		return $this->relatedCode[$objType];
	}

	//对应查看页面地址
	private $relatedType = array (
			'HTGZYD-01' => 'contract_outsourcing_outsourcing', //外包合同
			'HTGZYD-02' => 'contract_other_other', //其他合同
			'HTGZYD-03' => 'purchase_contract_purchasecontract', //采购订单
			'HTGZYD-04' => 'contract_contract_contract', //鼎利合同
			'HTGZYD-05' => 'contract_stamp_stampapply', //非合同类
			'HTGZYD-06' => 'finance_invoiceapply_invoiceapply', //开票申请
			'HTGZYD-07' => 'outsourcing_contract_rentcar' //租车合同
	);

	/**
	 * 根据数据类型返回url
	 */
	public function getType($objType){
		return $this->relatedType[$objType];
	}

    /**
	 * 获取对象业务信息
	 */
	public function getObjInfo_d($obj,istamp $strategy){
		//获取内容
		$rs = $strategy->getObjInfo_i($obj);

		return $rs;
	}

    /**
	 * 获取数据信息 - 盖章确认时渲染内容
	 */
	public function initStamp_d($obj,istamp $strategy){
		//获取内容
		$rs = $strategy->initStamp_i($obj);

		return $rs;
	}

	/**
	 * 回调盖章确认功能
	 */
	public function updateContract_d($obj,istamp $strategy){
		//获取内容
		$rs = $strategy->updateContract_i($obj);

		return $rs;
	}

    /**
     * 回调盖章确认功能
     */
    public function editStampType_d($obj,istamp $strategy){
        //获取内容
        $rs = $strategy->editStampType_i($obj);

        return $rs;
    }

    /**
     * 修改盖章类型以及状态
     */
    public function editStampTypeAndStatus_d($obj,istamp $strategy){
        //获取内容
        $rs = $strategy->editStampTypeAndStatus_i($obj);

        return $rs;
    }

    /**
     * 充值盖章状态
     */
    public function resetStampType_d($obj,istamp $strategy){
        //获取内容
        $rs = $strategy->resetStampType_i($obj);

        return $rs;
    }

	/**
	 * 盖章状态
	 */
	public function rtStampType_d($status){
		if($status == 1){
			return '已盖章';
		}elseif($status == 0){
			return '未盖章';
		}else{
			return '已关闭';
		}
	}

	/********************** 业务接口 ************************/

    /**
     * 编辑盖章申请
     */
    function editWithBusiness_d($object){
        try{
            $this->start_d();//事务开启
            $this->edit_d($object);

            //获取本期的全部盖章类型，并组合成字符串
            $allStampType = $this->get_table_fields($this->tbl_name," contractType = '".$object['contractType']."' and contractId = ".$object['contractId'] . " and batchNo = ".$object['batchNo'] , "group_concat(stampType)" );
			$object['stampType'] = $allStampType;

            //更新盖章信息
            $newClass = $this->getClass($object['contractType']);
            $initObj = new $newClass();
            $rs = $this->editStampType_d($object,$initObj);

            $this->commit_d();//提交事务
            return true;
        }catch(Exception $e){
            $this->rollBack();
            return false;
        }

    }

	/**
	 * 盖章确认操作
	 */
	function confirmStamp_d($object){
		try{

			$this->start_d();//事务开启

			//盖章确认
			$object['stampUserId'] = $_SESSION['USER_ID'];
			$object['stampUserName'] = $_SESSION['USERNAME'];
			$object['stampDate'] = day_date;
			$object['status'] = 1;
			$this->edit_d($object);

            // PMS 557 新增的一类盖章申请【投资申报】,只有新OA接口会生成,不需要做关联的源单处理
            if($object['contractType'] != 'HTGZYD-TB'){
                $rs = $this->find(array('contractId' => $object['contractId'],'contractType'=>$object['contractType'],'status' => 0),null,'id');
                if(!is_array($rs)){
                    //更新盖章信息
                    $newClass = $this->getClass($object['contractType']);
                    $initObj = new $newClass();
                    $rs = $this->updateContract_d($object,$initObj);
                }

                //单独申请
                if(!empty($object['applyId'])){
                    $appRs = $this->find(array('applyId' => $object['applyId'],'status' => 0),null,'id');
                    if(!is_array($appRs)){
                        //更新合同盖章信息
                        $newClass = $this->getClass($object['contractType']);
                        $initObj = new $newClass();
                        $rs = $this->updateContract_d($object,$initObj);
                    }
                }
            }

			//确认盖章申请
			if(!empty($object['applyId'])){
				$obj['id'] = $object['applyId'];
				$obj['stampUserId'] = $_SESSION['USER_ID'];
				$obj['stampUserName'] = $_SESSION['USERNAME'];
				$obj['stampDate'] = day_date;
				$obj['status'] = 1;
				//业务经办人信息
				$obj['attnId'] = $_SESSION['USER_ID'];
				$obj['attn'] = $_SESSION['USERNAME'];
				$obj['attnDeptId'] = $_SESSION['DEPT_ID'];
				$obj['attnDept'] = $_SESSION['DEPT_NAME'];
				$stampapplyDao = new model_contract_stamp_stampapply();
				$stampapplyDao->edit_d($obj);			
			}
			$this->commit_d();//提交事务
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 确认盖章操作
	 */
	 function confirmedSealed_d( $id ){
	 	try{
			$this->start_d();//事务开启

			//盖章确认
			$updateArr = array(
				'id' => $id,
				'status' => 1,
				'stampUserId' => $_SESSION['USER_ID'],
				'stampUserName' => $_SESSION['USERNAME'],
				'stampDate' => day_date
			);
			$this->edit_d($updateArr);

			//获取最后盖章的类型
			$obj = $this->find(array('id' => $id),null,'stampType,contractId,contractType,applyId');

			$rs = $this->find(array('contractId' => $obj['contractId'],'contractType'=>$obj['contractType'],'status' => 0),null,'id');
			if(!is_array($rs)){
				//更新合同盖章信息
				$newClass = $this->getClass($obj['contractType']);
				$initObj = new $newClass();
				$rs = $this->updateContract_d($obj,$initObj);
			}

			//单独申请
			if(!empty($obj['applyId'])){
				$appRs = $this->find(array('applyId' => $obj['applyId'],'status' => 0),null,'id');
				if(!is_array($appRs)){
					//更新合同盖章信息
					$newClass = $this->getClass($obj['contractType']);
					$initObj = new $newClass();
					$rs = $this->updateContract_d($obj,$initObj);
				}
			}

			$this->commit_d();//提交事务
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	 }

	/**
	 * 批量盖章
	 */
	function batchStamp_d($object){
		try{
			if(is_array($object)){
				foreach($object as $key => $val){
					$this->confirmedSealed_d($val);
				}
			}
			return true;
		}catch(Exception $e){
			return false;
		}
  	}

  	/**
  	 * 获取对应负责盖章类型
  	 */
  	function getStampTypeList_d(){
		$stampConfigDao = new model_system_stamp_stampconfig();
		$rs = $stampConfigDao->getStampTypeList_d($_SESSION['USER_ID']);

		$newArr = array();
		if(is_array($rs)){
			foreach($rs as $key => $val){
				array_push($newArr,$val['name']);
			}
		}
		return $newArr;
  	}

  	/**
  	 * 多盖章申请
  	 */
  	function addStamps_d($object){
  		if(empty($object['attnId'])){
  			$object['attn'] = $object['applyUserName'];
  			$object['attnId'] = $object['applyUserId'];
  		}
  		if(strpos($object['stampType'],',') === FALSE){
			return $this->add_d($object);
  		}else{
			try{
				$this->start_d();

				$stampTypes = $object['stampType'];
				$stampArr = explode(',',$stampTypes);
				foreach($stampArr as $key => $val){
					if(!empty($val)){
						$object['stampType'] = $val;
						$this->add_d($object);
					}
				}

				$this->commit_d();
			}catch(Exception $e){
				$this->rollBack();
				return true;
			}
  		}
  	}

  	/**
  	 * 关闭现有申请中的盖章
  	 */
  	function closeWaiting_d($contractId,$contractType){
		try{
			$this->update(array('contractId' => $contractId,'contractType' => $contractType,'status' => 0),array('status' => 2));
			return true;
		}catch(Exception $e){
			return false;
		}
  	}

  	/**
  	 * 关闭盖章申请
  	 */
  	function close_d($id){
		$obj = $this->get_d($id);

		//设置合同类型
		$newClass = $this->getClass($obj['contractType']);
		$initObj = new $newClass();

		try{
			$this->start_d();

			//关闭操作
			parent::edit_d(array('id' => $id ,'status' => 2));

			//构建章查询sql
			$sql = "select GROUP_CONCAT(c.stampType) as stampType from oa_sale_stamp c where c.contractId = '".$obj['contractId']."' and c.contractType = '".$obj['contractType']."' and c.batchNo = ".$obj['batchNo']." and c.status <> 2 group by c.contractId";
			$rs = $this->_db->getArray($sql);

			//设置原始章信息
			$obj['orgStampType'] = $obj['stampType'];

			if(!empty($rs)){
				//查询设置章的状态
				$sql = "select c.id from oa_sale_stamp c where c.contractId = '".$obj['contractId']."' and c.contractType = '".$obj['contractType']."' and c.batchNo = ".$obj['batchNo']." and c.status = 0";
				$rs2 = $this->_db->getArray($sql);

				if(!empty($rs2)){
//					print_r($obj);
					$obj['stampType'] = $rs[0]['stampType'];
					$this->editStampTypeAndStatus_d($obj,$initObj);
				}else{
					//更新盖章信息
					$this->editStampType_d($obj,$initObj);
				}

			}else{
				//更新盖章信息
				$this->resetStampType_d($obj,$initObj);
			}

			$this->commit_d();

			$this->closeMail_d($obj);

			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
  	}

  	/**
  	 * 盖章关闭邮件发送功能
  	 */
  	function closeMail_d($object){
  		//组织邮件内容
    	$datadictDao = new model_system_datadict_datadict();
    	$contractType = $datadictDao->getDataNameByCode($object['contractType']);
        $str = $_SESSION['USERNAME'].'已经关闭了 '.$contractType .' : ' .$object['contractCode'] . ' 的[ ' . $object['orgStampType']. ' ] 盖章申请';

		//获取邮件配置信息
//		include (WEB_TOR."model/common/mailConfig.php");
//		$mailArr = isset($mailUser[$this->tbl_name][0]) ? $mailUser[$this->tbl_name][0] : '';
		//查询盖章对应负责人
		$stampConfigDao = new model_system_stamp_stampconfig();
		$stampCongigArr = $stampConfigDao->find(array('stampName' => $object['orgStampType']),null,'principalId');

		if(empty($stampCongigArr)){
			return false;
		}

        $emailDao = new model_common_mail();
		$emailInfo = $emailDao->mailClear('关闭盖章申请',$stampCongigArr['principalId'],$str);

  	}
  	/**
  	 *
  	 * 检查是否存在盖章，并是未确认盖章
  	 * @param $obj
  	 */
  	function checkStamp($obj){
  		$sql="status !='1' and contractId='{$obj['contractId']}' and contractType='{$obj['contractType']}'";
  		if(!$this->findCount($sql)) {   //如果不存在则到盖章申请表找
  			$stampapplyModel=new model_contract_stamp_stampapply();
  			$sql="ExaStatus !='完成' and contractId='{$obj['contractId']}' and contractType='{$obj['contractType']}'";
  			return $stampapplyModel->findCount($sql);
  		}else{
  			return 1;
  		}
  	}
}