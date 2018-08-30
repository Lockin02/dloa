<?php
/**
 * @author Show
 * @Date 2012年8月31日 星期五 14:53:12
 * @version 1.0
 * @description:员工试用计划明细 Model层
 */
 class model_hr_trialplan_trialplandetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_trialplan_detail";
		$this->sql_map = "hr/trialplan/trialplandetailSql.php";
		parent::__construct ();
	}

    //数据字典字段处理
    public $datadictFieldArr = array(
    	'taskType'
    );

    /**
     * 返回任务性质
     */
    function rtIsNeed_c($thisVal){
        if($thisVal == 1){
            return '必须';
        }else{
            return '可选';
        }
    }

    /**
     * 返回完成方式
     */
    function rtCloseType_c($thisVal){
        if($thisVal == 0){
            return '审核';
        }else{
            return '立即';
        }
    }

	/************** 增删改查 *******************/
	//批量新增任务明细
	function batchAdd_d($object,$addInfo){
		try{
			$this->start_d();

			$newId = "";
			$lastArr = array();
			$rtArr = array();
			//任务扩展信息 - 评分规则
			$trialplandetailexDao = new model_hr_trialplan_trialplandetailex();
			//任务模版扩展信息 - 评分规则
			$trialplantemdetailexDao = new model_hr_baseinfo_trialplantemdetailex();

			foreach($object as $key => $val){
				//加载前置任务
//				if(empty($val['beforeName'])){
//					$val['status'] = 1;
//				}
				$val['status'] = 0;
				//数组合并
				$lastArr = array_merge($val,$addInfo);

				//如果存在积分规则，加入并调整isRule字段
				if(!empty($lastArr['isRule'])){
					$exArr = $trialplantemdetailexDao->getRule_d($lastArr['isRule']);
					$newRuleIds = $trialplandetailexDao->setRule_d($exArr);
					$lastArr['isRule'] = $newRuleIds['isRule'];
				}

				//插入数据
				$newId = parent::add_d($lastArr);

				//构建返回数组
//				if(empty($rtArr)){
//					$rtArr = $lastArr;
//					$rtArr['id'] = $newId;
//				}
			}

			$this->commit_d();
			return $rtArr;
		}catch(exception $e){
			$this->rollBack();
			throw $e;
			return false;
		}
	}

	//新增
	function add_d($object){
		//任务主对象
		$mainObj = $object['trialplandetail'];
		//计划主对象
		$planObj = $object['trialplan'];

		try{
			$this->start_d();

			//任务本身处理
			$object = $this->processDatadict($object);
			parent::add_d($mainObj);

			//计划处理
			$trialPlanDao = new model_hr_trialplan_trialplan();
			$trialPlanDao->update(
				array('id' => $mainObj['planId']),
				array('scoreAll' => $planObj['scoreAll'],'baseScore' => $planObj['baseScore'])
			);

            //人员信息处理
            $personnelDao = new model_hr_personnel_personnel();
            $personnelDao->update(
                array('userAccount' => $mainObj['memberId']),
                array('baseScore' => $planObj['baseScore'])
            );

			$this->commit_d();
			return true;
		}catch(exception $e){
			echo $e->getMessage();
			$this->rollBack();
			return false;
		}
	}

	//编辑
	function edit_d($object){
        //任务主对象
        $mainObj = $object['trialplandetail'];
        //计划主对象
        $planObj = $object['trialplan'];

        try{
            $this->start_d();

            //任务本身处理
            $object = $this->processDatadict($mainObj);
            parent::edit_d($mainObj);

            //计划处理
            $trialPlanDao = new model_hr_trialplan_trialplan();
            $trialPlanDao->update(
                array('id' => $mainObj['planId']),
                array('scoreAll' => $planObj['scoreAll'],'baseScore' => $planObj['baseScore'])
            );

            //人员信息处理
            $personnelDao = new model_hr_personnel_personnel();
            $personnelDao->update(
                array('userAccount' => $mainObj['memberId']),
                array('baseScore' => $planObj['baseScore'])
            );

            $this->commit_d();
            return true;
        }catch(exception $e){
            echo $e->getMessage();
            $this->rollBack();
            return false;
        }
	}

    /***************************** 业务逻辑 ***************************/
    /**
     * 审核评分
     */
    function score_d($object){
        try{
        	$this->start_d();
            //更新本记录
            $object['status'] = 3;
            parent::edit_d($object,true);

            //获取任务的最新进度
            $countInfo = $this->countInfo_d($object['planId']);
            $baseScore = $countInfo[0];
            $trialPlanProcess = $countInfo[1];

			//计划是否已完成
            $planIsOver = $this->planIsComplate_d($object['planId']);

            //更新人事信息
            $personnelDao = new model_hr_personnel_personnel();

            if($planIsOver){
	            $personnelArr = array(
	                'trialTaskId' => '',
	                'trialTask' => '',
	                'trialPlanId' => '',
	                'trialPlan' => '计划已完成',
	                'allScore' => $baseScore,
	                'trialPlanProcess' => $trialPlanProcess
	            );
            }else{
	            $personnelArr = array(
	                'allScore' => $baseScore,
	                'trialPlanProcess' => $trialPlanProcess
	            );
            }
            $personnelDao->updatePersonnel_d($object['memberId'],$personnelArr);

            $this->commit_d();
            return true;
        }catch(exception $e){
            echo $e;
            $this->rollBack();
            return false;
        }
    }

    /**
     * 开始下一级任务
     */
    function beginNext_d($id){
        //获取下一级id
        $obj = $this->find(array('beforeId' => $id));
        if($obj){
            try{
                //更新状态
                $this->updateStatus_d($obj['id'],1);
                return $obj;
            }catch(exception $e){
                throw $e;
                return false;
            }
        }else{//没有下一级直接返回
        	return true;
        }
    }

    /**
     * 状态更新方法
     */
    function updateStatus_d($id,$status){
        try{
            //条件数组
            $coditionArr = array('id' => $id);
            //更新内容数组
            $updateArr = array('status' => $status);
            $updateArr = $this->addUpdateInfo($updateArr);

            return $this->update($coditionArr,$updateArr);
        }catch(exception $e){
            throw $e;
            return false;
        }
    }

    /**
     * 获取计划任务进度
     */
    function countInfo_d($planId){
        $this->searchArr = array(
            'planId' => $planId,
            'status' => 3
        );
        $rs = $this->list_d('countAll');
        if($rs){
        	//当前积分
            $baseScore = $rs[0]['baseScore'];

			//获取计划得分
			$trialPlanDao = new model_hr_trialplan_trialplan();
			$trialPlanArr = $trialPlanDao->find(array('id'=>$planId),null,'baseScore');
			if($trialPlanArr['baseScore'] < $baseScore){
				return array($baseScore,100);
			}else{
				return array($baseScore,bcmul(bcdiv($baseScore,$trialPlanArr['baseScore'],2),100));
			}
        }else{
        	return 0;
        }
    }

    /**
     * 提交方法
     */
    function handup_d($object){
		$object['status'] = 2;
		return parent::edit_d($object,true);
    }

    /**
     * 完成
     */
    function complate_d($id){
    	//获取对象
		$obj = $this->get_d($id);
        try{
        	$this->start_d();
            //更新本记录
            $obj['status'] = 3;
            $obj['baseScore'] = $obj['taskScore'];
            $obj['handupDate'] = day_date;
            parent::edit_d($obj,true);

            //获取任务的最新进度
            $countInfo = $this->countInfo_d($obj['planId']);
            $baseScore = $countInfo[0];
            $trialPlanProcess = $countInfo[1];

			//计划是否已完成
            $planIsOver = $this->planIsComplate_d($obj['planId']);

            //更新人事信息
            $personnelDao = new model_hr_personnel_personnel();

            if($planIsOver){
	            $personnelArr = array(
	                'trialTaskId' => '',
	                'trialTask' => '',
	                'trialPlanId' => '',
	                'trialPlan' => '计划已完成',
	                'allScore' => $baseScore,
	                'trialPlanProcess' => $trialPlanProcess
	            );
            }else{
	            $personnelArr = array(
	                'trialTaskId' => '',
	                'trialTask' => '',
	                'allScore' => $baseScore,
	                'trialPlanProcess' => $trialPlanProcess
	            );
            }
            $personnelDao->updatePersonnel_d($obj['memberId'],$personnelArr);

            //启用下一级记录
//			$nextObj = $this->beginNext_d($obj['id']);
//
//			if(is_array($nextObj)){
//	            //更新人事信息
//	            $personnelDao = new model_hr_personnel_personnel();
//	            $personnelArr = array(
//	                'trialTaskId' => $nextObj['id'],
//	                'trialTask' => $nextObj['taskName'],
//	                'allScore' => $baseScore,
//	                'trialPlanProcess' => $trialPlanProcess
//	            );
//	            $personnelDao->updatePersonnel_d($obj['memberId'],$personnelArr);
//			}else{
//	            //更新人事信息
//	            $personnelDao = new model_hr_personnel_personnel();
//	            $personnelArr = array(
//	                'trialTaskId' => '',
//	                'trialTask' => '',
//	                'trialPlanId' => '',
//	                'trialPlan' => '计划已完成',
//	                'allScore' => $baseScore,
//	                'trialPlanProcess' => $trialPlanProcess
//	            );
//	            $personnelDao->updatePersonnel_d($obj['memberId'],$personnelArr);
//			}

            $this->commit_d();
            return true;
        }catch(exception $e){
            echo $e;
            $this->rollBack();
            return false;
        }
    }

    /**
     * 任务启动
     */
    function begin_d($id){
        try{
        	$this->start_d();
            //更新本记录
            $obj['id'] = $id;
            $obj['status'] = 1;
            parent::edit_d($obj,true);

			//获取本记录
            $obj = parent::get_d($id);

            //更新人事信息
            $personnelDao = new model_hr_personnel_personnel();
	            $personnelArr = array(
	                'trialTaskId' => $obj['id'],
	                'trialTask' => $obj['taskName']
	            );
            $personnelDao->updatePersonnel_d($obj['memberId'],$personnelArr);

            $this->commit_d();
            return true;
        }catch(exception $e){
            echo $e;
            $this->rollBack();
            return false;
        }
    }

    /**
     * 判断任务是否已经完成
     */
   	function isComplate_d($planId,$taskName){
   		$taskName = util_jsonUtil::iconvUTF2GB($taskName);
		$obj = $this->find(array('planId' => $planId,'taskName' => $taskName));
		if($obj['status'] == 3){
			return true;
		}else{
			return false;
		}
   	}

   	/**
   	 * 判断任务是否已全部完成
   	 */
   	function planIsComplate_d($planId){
		$this->searchArr = array(
			'planId' => $planId,
			'statusNo' => 3,
			'isNeed' => 1
		);
		$rs = $this->list_d();
		if($rs){
			return false;
		}else{
			return true;
		}
   	}

    /**
     * 关闭任务
     */
    function close_d($id){
        try{
            $this->start_d();
            //更新本记录
            $obj['id'] = $id;
            $obj['status'] = 4;
            parent::edit_d($obj);

            //获取本记录
            $obj = parent::get_d($id);

            //获取总积分
            $this->searchArr = array(
                'planId' => $obj['planId'],
                'statusNo' => 4
            );
            $rs = $this->list_d('countAll');

            //计划处理
            $trialPlanDao = new model_hr_trialplan_trialplan();
            $trialPlanDao->update(
                array('id' => $obj['planId']),
                array('scoreAll' => $rs[0]['taskScore'])
            );

            $this->commit_d();
            return true;
        }catch(exception $e){
            echo $e;
            $this->rollBack();
            return false;
        }
    }

	/**
	 * 获取积分规则信息
	 */
	function getRuleInfo_d($isRuleVal){
		$trialplandetailexDao = new model_hr_trialplan_trialplandetailex();
		return $trialplandetailexDao->getRuleInfo_d($isRuleVal);
	}

	/**
	 * 根据培训计划id获取计划信息
	 */
	function getTrialPlanInfo_d($planId){
		$trialPlanDao = new model_hr_trialplan_trialplan();
		$trialPlanObj = $trialPlanDao->find(array('id' => $planId));

		$newObj['planScoreAll'] = $trialPlanObj['scoreAll'];
		$newObj['planBaseScore'] = $trialPlanObj['baseScore'];
		return $newObj;
	}

	//获取前置任务
	function getBeforeTask_d($planId,$id = null){
		$this->searchArr = array(
			'planId' => $planId
		);
		if($id){
			$this->searchArr['noId'] = $id;
		}
		$rs = $this->list_d();
		if($rs){
			return $rs;
		}else{
			return false;
		}
	}

	//显示任务信息
	function showTaskSel_d($taskArr,$taskId){
		if (is_array ( $taskArr )) {
			foreach ( $taskArr as $k => $v ) {
				if ($v ['id'] == $taskId)
					$str .= '<option value="' . $v ['id'] . '" title="'.$v ['remark'].'" selected>';
				else
					$str .= '<option value="' . $v ['id'] . '" title="'.$v ['remark'].'">';
				$str .= $v ['taskName'];
				$str .= '</option>';
			}
		}
		return $str;
	}
}
?>