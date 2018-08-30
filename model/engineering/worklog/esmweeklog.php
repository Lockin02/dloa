<?php
/**
 * @author Show
 * @Date 2011��12��14�� ������ 10:00:57
 * @version 1.0
 * @description:�ܱ�(oa_esm_weeklog) Model��
 */
class model_engineering_worklog_esmweeklog extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_weeklog";
		$this->sql_map = "engineering/worklog/esmweeklogSql.php";
		parent::__construct ();
	}

	/**************** ��ʼ������Ϣ *******************/

	/**
	 * ״̬����
 	 */
	function rtSubStatus($thisVal){
		$val = null;
		switch ($thisVal){
			case 'YTJ'	 : $val = '���ύ' ;break;
			case 'YQR'	 : $val = '��ȷ��' ;break;
			case 'WTJ'	 : $val = 'δ�ύ' ;break;
			case 'BTG'	 : $val = '��ͨ��' ;break;
			default : $val = '';break;
		}
		return $val;
	}

	/****************** ��ɾ�Ĳ� **********************/

	/**
	 * �ܱ��ύ
	 */
	function submitLog_d($object){
		//����״̬Ϊ���ύ
		$object ['subStatus'] = 'YTJ';
		try{
			$this->start_d();

			//�����ܱ���Ϣ
			parent::edit_d($object,true);

			//������־״̬Ϊ���ύ
			$esmworklogDao = new model_engineering_worklog_esmworklog();
			$esmworklogDao->updateStatus_d($object['id'],'YTJ');

			//�ʼ�֪ͨ

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �ܱ����
	 */
	function auditLog_d($object){
//		echo "<pre>";
//		print_r($object);
//		die();

		$esmasspro = $object['esmasspro'];
		unset($object['esmasspro']);

		try{
			$this->start_d();

			if($object['exaResults'] == 'ZBKHJZ-02'){
				$object['subStatus'] = 'BTG';
			}
			$object['exaDate'] = day_date;

			//�����ܱ���Ϣ
			parent::edit_d($object,true);

			if($object['exaResults'] == 'ZBKHJZ-01'){

				//������־״̬Ϊ���ύ
				$esmworklogDao = new model_engineering_worklog_esmworklog();
				$esmworklogDao->updateStatus_d($object['id'],$object['subStatus'],1);

				//���˽��
				$esmrsindexDao = new model_engineering_worklog_esmrsindex();
				$esmrsindexDao->saveDelBatch($esmasspro);

				//��ѯ��ϸ��־
				$esmworklogArr = $esmworklogDao->findAll(array('weekId' => $object['id']));

				$projectArr = array();
				foreach($esmworklogArr as $key => $val){
					if($val['projectId'] && !isset($projectArr[$val['projectId']])){
						array_push($projectArr,$val['projectId']);
					}
				}

				if($projectArr){
					//ѭ�������������
					$esmactivityDao = new model_engineering_activity_esmactivity();
					$esmprojectDao = new model_engineering_project_esmproject();
					foreach($projectArr as $key => $val){
						$projectProcess = $esmactivityDao->getProjectProcess_d($val);
						$esmprojectDao->updateProjectProcess_d($val,$projectProcess);
					}
				}
			}else{
				//������־״̬Ϊ���ύ
				$esmworklogDao = new model_engineering_worklog_esmworklog();
				$esmworklogDao->updateStatus_d($object['id'],'BTG');
			}

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	//�ܱ����
	function backLog_d($id){
		try{
			$this->start_d();

			$object['id'] = $id;
			$object['exaResults'] = 'ZBKHJZ-02';
			$object['subStatus'] = 'BTG';
			$object['exaDate'] = day_date;

			//�����ܱ���Ϣ
			parent::edit_d($object,true);

			//������־״̬
			$esmworklogDao = new model_engineering_worklog_esmworklog();
			$esmworklogDao->updateStatus_d($object['id'],'BTG');

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}

	}

	/****************** ����ҵ���߼����� **************/

	/**
	 *
	 * �ж���־��Ӧ���ܱ��Ƿ����,�������
	 * @param  $object
	 */
	function checkIsWeeklog($object) {

        //��ȡԤ�����ܴ� ���ܴ�
        $weekDao = new model_engineering_baseinfo_week();
        $weekTimes = $weekDao->getWeekNoByDayTimes($object ['executionDate']);
		$week = date ( 'W', strtotime ( $object ['executionDate'] ) ); //��ȡִ�������ܴ�

		$userId = $_SESSION ['USER_ID'];

		//��ѯ�Ƿ���ڶ�Ӧ�ܱ�
		$weekArr = $this->find(array("weekTimes" => $weekTimes, 'createId' => $userId));

		//�ܱ������ھ������ܱ�
		if (! is_array ( $weekArr )) {
			$thisWeek  = $this->getWeekDate($week,date("Y"));
			$weekTitle = $_SESSION['USERNAME'] .'������־( '.$thisWeek[0] . ' ~ ' .$thisWeek[1] .')';
			$weekBeginDate = $thisWeek[0];
			$weekEndDate = $thisWeek[1];

			//������Ϣ��ȡ
			$personnelDao = new model_hr_personnel_personnel();
			$personnelArr = $personnelDao->getPersonnelInfo_d($userId);
			$depId = $personnelArr ['belongDeptId'];
			$depName = $personnelArr ['belongDeptName'];
			$rankCode = $personnelArr ['personLevel'];
			//��̥
			if(!$personnelArr){
				$userDao = new model_common_otherdatas ();
				$userInfo = $userDao->getUserDatas ( $userId, array ('DEPT_NAME', 'DEPT_ID' ) );
				$depId = $userInfo ['DEPT_ID'];
				$depName = $userInfo ['DEPT_NAME'];
			}

			//�����ܱ�����
			$myobj = array (
				"subStatus" => "WTJ",
				"weekTitle" => $weekTitle,
				"weekTimes" => $weekTimes,
				"weekBeginDate" => $weekBeginDate,
				"weekEndDate" => $weekEndDate,
				"depId" => $depId,
				"depName" => $depName,
				"rankCode" => $rankCode
			);

			//�����ܱ�
			$weekId = $this->add_d ( $myobj, true );

			return $weekId;
		} else {
			return $weekArr['id'];
		}
	}

	//��ȡ�ܵĿ�ʼ���ںͽ�������
	function getWeekDate($week,$year)
	{
		$timestamp = mktime(0,0,0,1,1,$year);
		$dayofweek = date("w",$timestamp);
		if( $week != 1)
			$distance = ($week-1)*7-$dayofweek+1;
		$passed_seconds = $distance * 86400;
		$timestamp += $passed_seconds;
		$firt_date_of_week = date("Y-m-d",$timestamp);
		if($week == 1)
			$distance = 7-$dayofweek;
		else
			$distance = 6;
		$timestamp += $distance * 86400;
		$last_date_of_week = date("Y-m-d",$timestamp);
		return array($firt_date_of_week,$last_date_of_week);
	}

	//�ж��������Ƿ��Ѿ����ڹرջ���������˵��ܱ�
	function isSetWeekLog_d($userId,$date){
        //��ȡԤ�����ܴ� ���ܴ�
        $weekDao = new model_engineering_baseinfo_week();
        $weekTimes = $weekDao->getWeekNoByDayTimes($date);
		$rs = $this->find(array('weekTimes' => $weekTimes ,'createId' => $userId),null,'subStatus');
		if($rs){
			if($rs['subStatus'] == 'YTJ'){//���ύ
				return 1;
			}
			if($rs['subStatus'] == 'YQR'){//��ȷ��
				return 2;
			}

			return false;
		}else{
			return false;
		}
	}

    //ָ�겿����Ⱦ
    function initAuditIndex_d($object){
        //��ȡ��˶�Ӧ����Ŀid
        $esmworklogDao = new model_engineering_worklog_esmworklog();
        $projectId = $esmworklogDao->getAuditProjectId_d($object['id']);
        //ָ���ȡ
        $esmassproDao = new model_engineering_assess_esmasspro();
        $esmassproObj = $esmassproDao->find(array('projectId'=>$projectId),null,'id');

        //�������Ŀid
        if($esmassproObj){
            $esmassindexDao = new model_engineering_assess_esmassproindex();
            $esmassindexArr = $esmassindexDao->findAll(array('assessId' => $esmassproObj['id']));

            $str = $this->initAudit_d($esmassindexArr,$object['id']);
            return $str;
        }else{
        	return '';
        }
    }

    //����ָ����Ⱦ
    function initAudit_d($esmassindexArr,$weekId){

        //��ȡ���ÿ��˵ȼ�
        $esmasslevelDao = new model_engineering_assess_esmasslevel();
        $esmasslevelArr = $esmasslevelDao->findAll(null,'upperLimit desc');
        $maxScore = $esmasslevelArr[0]['upperLimit'];
        $fistStr = $this->initLevelLimit_d($esmasslevelArr);
		$str =<<<EOT
			<tr>
	            <td class="form_text_left_three">���˷���</td>
	            <td class="form_text_right" colspan="5">
	            	<span id="scoreShow">$maxScore</span>$fistStr
	            </td>
	        </tr>
EOT;

        $str .= "<tr>";
//        echo "<pre>";
//        print_r($esmassindexArr);
        $esmassoptionDao = new model_engineering_assess_esmassprooption();
        foreach($esmassindexArr as $key => $val){
            //ָ��ѡ������
            $esmassoptionArr = $esmassoptionDao->findAll(array('detailId' => $val['id']),'score desc');
            $optionsArr = $this->initOptions_d($esmassoptionArr);

            if($key%3 == 0){
                $str .=<<<EOT
                    </tr><tr>
                        <td class="form_text_left_three">$val[indexName]</td>
                        <td class="form_text_right_three">
                            <select class="select" id="option$key" name="esmweeklog[esmasspro][$key][score]" onchange="changeOption($key);">$optionsArr[options]</select>
                            <input type="hidden" name="esmweeklog[esmasspro][$key][indexName]" value="$val[indexName]"/>
                            <input type="hidden" name="esmweeklog[esmasspro][$key][indexId]" value="$val[id]"/>
                            <input type="hidden" name="esmweeklog[esmasspro][$key][optionName]" value="$optionsArr[optionName]"/>
                            <input type="hidden" name="esmweeklog[esmasspro][$key][optionId]" value="$optionsArr[optionId]"/>
                            <input type="hidden" name="esmweeklog[esmasspro][$key][weekId]" value="$weekId"/>
                        </td>
EOT;
            }else{
                $str .=<<<EOT
                    <td class="form_text_left_three">$val[indexName]</td>
                    <td class="form_text_right_three">
                        <select class="select" id="option$key" name="esmweeklog[esmasspro][$key][score]" onchange="changeOption($key);">$optionsArr[options]</select>
                        <input type="hidden" name="esmweeklog[esmasspro][$key][indexName]" value="$val[indexName]"/>
                        <input type="hidden" name="esmweeklog[esmasspro][$key][indexId]" value="$val[id]"/>
                        <input type="hidden" id="optionName$key" name="esmweeklog[esmasspro][$key][optionName]" value="$optionsArr[optionName]"/>
                        <input type="hidden" id="optionId$key" name="esmweeklog[esmasspro][$key][optionId]" value="$optionsArr[optionId]"/>
                        <input type="hidden" name="esmweeklog[esmasspro][$key][weekId]" value="$weekId"/>
                    </td>
EOT;
            }
        }
        $str .=<<<EOT
            </tr>
EOT;
        return $str;
    }

    //�������ʼ����optionѡ��
    function initOptions_d($object,$thisVal = null){
        $str = null;
        $markOptionName = "";
        $markOptionId = "";
        foreach($object as $key => $val){
        	if($key == 0){
				$markOptionName = $val['optionName'];
				$markOptionId = $val['id'];
        	}
            if($thisVal == $val['id']){
                $str.='<option value="'.$val['score'].'" selected="selected" optionName="'.$val['optionName'].'" optionId="'.$val['id'].'">'.$val['optionName'] .'</option>';
            }else{
                $str.='<option value="'.$val['score'].'" optionName="'.$val['optionName'].'" optionId="'.$val['id'].'">'.$val['optionName'] .'</option>';
            }
        }
        return array( 'options' => $str, 'optionName' => $markOptionName , 'optionId' => $markOptionId);
    }

    //�ȼ�ƥ�����鴦��
    function initLevelLimit_d($esmasslevelArr){
        $str = null;
    	foreach($esmasslevelArr as $key => $val){
			$str .=<<<EOT
				<input type="hidden" id="scoreUpplerLimit$key" levelName="$val[name]" value="$val[upperLimit]"/>
				<input type="hidden" id="scoreLowerLimit$key" levelName="$val[name]" value="$val[lowerLimit]"/>
EOT;
    	}
    	return $str;
    }

    //��ʼ����������
    function initAuditIndexView_d($object){
        //��ȡ��˶�Ӧ����Ŀid
        $esmrsindexDao = new model_engineering_worklog_esmrsindex();
        $esmrsindexArr = $esmrsindexDao->findAll(array('weekId' => $object['id']));
		$str=<<<EOT
            <tr>
                <td class="form_text_left_three"><font style="font-weight:bold">���˷���</font></td>
                <td class="form_text_right_three"><font style="font-weight:bold">$object[rsScore]</font></td>
                <td class="form_text_left_three"><font style="font-weight:bold">�� �� ��</font></td>
                <td class="form_text_right_three"><font style="font-weight:bold">$object[assessmentName]</font></td>
                <td class="form_text_left_three"><font style="font-weight:bold">��������</font></td>
                <td class="form_text_right_three"><font style="font-weight:bold">$object[exaDate]</font></td>
            </tr>
EOT;
        //�������Ŀid
        if($esmrsindexArr){
	        foreach($esmrsindexArr as $key => $val){
	            if($key%3 == 0){
	                $str .=<<<EOT
	                    </tr><tr>
	                        <td class="form_text_left_three">$val[indexName]</td>
	                    <td class="form_text_right_three">$val[optionName]</td>
EOT;
	            }else{
	                $str .=<<<EOT
	                    <td class="form_text_left_three">$val[indexName]</td>
	                    <td class="form_text_right_three">$val[optionName]</td>
EOT;
	            }
	        }
	        $str .=<<<EOT
	            </tr>
EOT;
            return $str;
        }else{
        	return '�ܱ����޿�����Ϣ';
        }
    }
    /**
     *
     * excel����
     * @param $obj ������������
     */
    function getExcelInfo_d($obj){
    //	$param['projectName']=$obj['projectName'];
    	if($obj['weekBeginDate'])
    		$param['weekBeginDate_d']=$obj['weekBeginDate'];
    	if($obj['weekEndDate'])
    		$param['weekEndDate_d']=$obj['weekEndDate'];
    	if($obj['projectId'])
    		$param['projectId']=$obj['projectId'];
		$param['deptIds']=$obj['deptIds'];
		$this->searchArr=$param;

		$esmworklog=new model_engineering_worklog_esmworklog();
    	//$esmworklog->findAll(array('weekId'=>),'projectName,projectCode');
    	$this->sort="c.weekBeginDate";
		$this->asc="desc";
		return $row = $this->list_d('select_excelOut');
    }
}
?>