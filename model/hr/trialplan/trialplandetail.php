<?php
/**
 * @author Show
 * @Date 2012��8��31�� ������ 14:53:12
 * @version 1.0
 * @description:Ա�����üƻ���ϸ Model��
 */
 class model_hr_trialplan_trialplandetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_trialplan_detail";
		$this->sql_map = "hr/trialplan/trialplandetailSql.php";
		parent::__construct ();
	}

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
    	'taskType'
    );

    /**
     * ������������
     */
    function rtIsNeed_c($thisVal){
        if($thisVal == 1){
            return '����';
        }else{
            return '��ѡ';
        }
    }

    /**
     * ������ɷ�ʽ
     */
    function rtCloseType_c($thisVal){
        if($thisVal == 0){
            return '���';
        }else{
            return '����';
        }
    }

	/************** ��ɾ�Ĳ� *******************/
	//��������������ϸ
	function batchAdd_d($object,$addInfo){
		try{
			$this->start_d();

			$newId = "";
			$lastArr = array();
			$rtArr = array();
			//������չ��Ϣ - ���ֹ���
			$trialplandetailexDao = new model_hr_trialplan_trialplandetailex();
			//����ģ����չ��Ϣ - ���ֹ���
			$trialplantemdetailexDao = new model_hr_baseinfo_trialplantemdetailex();

			foreach($object as $key => $val){
				//����ǰ������
//				if(empty($val['beforeName'])){
//					$val['status'] = 1;
//				}
				$val['status'] = 0;
				//����ϲ�
				$lastArr = array_merge($val,$addInfo);

				//������ڻ��ֹ��򣬼��벢����isRule�ֶ�
				if(!empty($lastArr['isRule'])){
					$exArr = $trialplantemdetailexDao->getRule_d($lastArr['isRule']);
					$newRuleIds = $trialplandetailexDao->setRule_d($exArr);
					$lastArr['isRule'] = $newRuleIds['isRule'];
				}

				//��������
				$newId = parent::add_d($lastArr);

				//������������
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

	//����
	function add_d($object){
		//����������
		$mainObj = $object['trialplandetail'];
		//�ƻ�������
		$planObj = $object['trialplan'];

		try{
			$this->start_d();

			//��������
			$object = $this->processDatadict($object);
			parent::add_d($mainObj);

			//�ƻ�����
			$trialPlanDao = new model_hr_trialplan_trialplan();
			$trialPlanDao->update(
				array('id' => $mainObj['planId']),
				array('scoreAll' => $planObj['scoreAll'],'baseScore' => $planObj['baseScore'])
			);

            //��Ա��Ϣ����
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

	//�༭
	function edit_d($object){
        //����������
        $mainObj = $object['trialplandetail'];
        //�ƻ�������
        $planObj = $object['trialplan'];

        try{
            $this->start_d();

            //��������
            $object = $this->processDatadict($mainObj);
            parent::edit_d($mainObj);

            //�ƻ�����
            $trialPlanDao = new model_hr_trialplan_trialplan();
            $trialPlanDao->update(
                array('id' => $mainObj['planId']),
                array('scoreAll' => $planObj['scoreAll'],'baseScore' => $planObj['baseScore'])
            );

            //��Ա��Ϣ����
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

    /***************************** ҵ���߼� ***************************/
    /**
     * �������
     */
    function score_d($object){
        try{
        	$this->start_d();
            //���±���¼
            $object['status'] = 3;
            parent::edit_d($object,true);

            //��ȡ��������½���
            $countInfo = $this->countInfo_d($object['planId']);
            $baseScore = $countInfo[0];
            $trialPlanProcess = $countInfo[1];

			//�ƻ��Ƿ������
            $planIsOver = $this->planIsComplate_d($object['planId']);

            //����������Ϣ
            $personnelDao = new model_hr_personnel_personnel();

            if($planIsOver){
	            $personnelArr = array(
	                'trialTaskId' => '',
	                'trialTask' => '',
	                'trialPlanId' => '',
	                'trialPlan' => '�ƻ������',
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
     * ��ʼ��һ������
     */
    function beginNext_d($id){
        //��ȡ��һ��id
        $obj = $this->find(array('beforeId' => $id));
        if($obj){
            try{
                //����״̬
                $this->updateStatus_d($obj['id'],1);
                return $obj;
            }catch(exception $e){
                throw $e;
                return false;
            }
        }else{//û����һ��ֱ�ӷ���
        	return true;
        }
    }

    /**
     * ״̬���·���
     */
    function updateStatus_d($id,$status){
        try{
            //��������
            $coditionArr = array('id' => $id);
            //������������
            $updateArr = array('status' => $status);
            $updateArr = $this->addUpdateInfo($updateArr);

            return $this->update($coditionArr,$updateArr);
        }catch(exception $e){
            throw $e;
            return false;
        }
    }

    /**
     * ��ȡ�ƻ��������
     */
    function countInfo_d($planId){
        $this->searchArr = array(
            'planId' => $planId,
            'status' => 3
        );
        $rs = $this->list_d('countAll');
        if($rs){
        	//��ǰ����
            $baseScore = $rs[0]['baseScore'];

			//��ȡ�ƻ��÷�
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
     * �ύ����
     */
    function handup_d($object){
		$object['status'] = 2;
		return parent::edit_d($object,true);
    }

    /**
     * ���
     */
    function complate_d($id){
    	//��ȡ����
		$obj = $this->get_d($id);
        try{
        	$this->start_d();
            //���±���¼
            $obj['status'] = 3;
            $obj['baseScore'] = $obj['taskScore'];
            $obj['handupDate'] = day_date;
            parent::edit_d($obj,true);

            //��ȡ��������½���
            $countInfo = $this->countInfo_d($obj['planId']);
            $baseScore = $countInfo[0];
            $trialPlanProcess = $countInfo[1];

			//�ƻ��Ƿ������
            $planIsOver = $this->planIsComplate_d($obj['planId']);

            //����������Ϣ
            $personnelDao = new model_hr_personnel_personnel();

            if($planIsOver){
	            $personnelArr = array(
	                'trialTaskId' => '',
	                'trialTask' => '',
	                'trialPlanId' => '',
	                'trialPlan' => '�ƻ������',
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

            //������һ����¼
//			$nextObj = $this->beginNext_d($obj['id']);
//
//			if(is_array($nextObj)){
//	            //����������Ϣ
//	            $personnelDao = new model_hr_personnel_personnel();
//	            $personnelArr = array(
//	                'trialTaskId' => $nextObj['id'],
//	                'trialTask' => $nextObj['taskName'],
//	                'allScore' => $baseScore,
//	                'trialPlanProcess' => $trialPlanProcess
//	            );
//	            $personnelDao->updatePersonnel_d($obj['memberId'],$personnelArr);
//			}else{
//	            //����������Ϣ
//	            $personnelDao = new model_hr_personnel_personnel();
//	            $personnelArr = array(
//	                'trialTaskId' => '',
//	                'trialTask' => '',
//	                'trialPlanId' => '',
//	                'trialPlan' => '�ƻ������',
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
     * ��������
     */
    function begin_d($id){
        try{
        	$this->start_d();
            //���±���¼
            $obj['id'] = $id;
            $obj['status'] = 1;
            parent::edit_d($obj,true);

			//��ȡ����¼
            $obj = parent::get_d($id);

            //����������Ϣ
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
     * �ж������Ƿ��Ѿ����
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
   	 * �ж������Ƿ���ȫ�����
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
     * �ر�����
     */
    function close_d($id){
        try{
            $this->start_d();
            //���±���¼
            $obj['id'] = $id;
            $obj['status'] = 4;
            parent::edit_d($obj);

            //��ȡ����¼
            $obj = parent::get_d($id);

            //��ȡ�ܻ���
            $this->searchArr = array(
                'planId' => $obj['planId'],
                'statusNo' => 4
            );
            $rs = $this->list_d('countAll');

            //�ƻ�����
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
	 * ��ȡ���ֹ�����Ϣ
	 */
	function getRuleInfo_d($isRuleVal){
		$trialplandetailexDao = new model_hr_trialplan_trialplandetailex();
		return $trialplandetailexDao->getRuleInfo_d($isRuleVal);
	}

	/**
	 * ������ѵ�ƻ�id��ȡ�ƻ���Ϣ
	 */
	function getTrialPlanInfo_d($planId){
		$trialPlanDao = new model_hr_trialplan_trialplan();
		$trialPlanObj = $trialPlanDao->find(array('id' => $planId));

		$newObj['planScoreAll'] = $trialPlanObj['scoreAll'];
		$newObj['planBaseScore'] = $trialPlanObj['baseScore'];
		return $newObj;
	}

	//��ȡǰ������
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

	//��ʾ������Ϣ
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