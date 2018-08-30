<?php
/**
 * @author Show
 * @Date 2011年12月14日 星期三 10:00:57
 * @version 1.0
 * @description:周报(oa_esm_weeklog) Model层
 */
class model_engineering_worklog_esmweeklog extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_weeklog";
		$this->sql_map = "engineering/worklog/esmweeklogSql.php";
		parent::__construct ();
	}

	/**************** 初始设置信息 *******************/

	/**
	 * 状态数组
 	 */
	function rtSubStatus($thisVal){
		$val = null;
		switch ($thisVal){
			case 'YTJ'	 : $val = '已提交' ;break;
			case 'YQR'	 : $val = '已确认' ;break;
			case 'WTJ'	 : $val = '未提交' ;break;
			case 'BTG'	 : $val = '不通过' ;break;
			default : $val = '';break;
		}
		return $val;
	}

	/****************** 增删改查 **********************/

	/**
	 * 周报提交
	 */
	function submitLog_d($object){
		//设置状态为已提交
		$object ['subStatus'] = 'YTJ';
		try{
			$this->start_d();

			//更新周报信息
			parent::edit_d($object,true);

			//更新日志状态为已提交
			$esmworklogDao = new model_engineering_worklog_esmworklog();
			$esmworklogDao->updateStatus_d($object['id'],'YTJ');

			//邮件通知

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 周报审核
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

			//更新周报信息
			parent::edit_d($object,true);

			if($object['exaResults'] == 'ZBKHJZ-01'){

				//更新日志状态为已提交
				$esmworklogDao = new model_engineering_worklog_esmworklog();
				$esmworklogDao->updateStatus_d($object['id'],$object['subStatus'],1);

				//考核结果
				$esmrsindexDao = new model_engineering_worklog_esmrsindex();
				$esmrsindexDao->saveDelBatch($esmasspro);

				//查询详细日志
				$esmworklogArr = $esmworklogDao->findAll(array('weekId' => $object['id']));

				$projectArr = array();
				foreach($esmworklogArr as $key => $val){
					if($val['projectId'] && !isset($projectArr[$val['projectId']])){
						array_push($projectArr,$val['projectId']);
					}
				}

				if($projectArr){
					//循环更新任务进度
					$esmactivityDao = new model_engineering_activity_esmactivity();
					$esmprojectDao = new model_engineering_project_esmproject();
					foreach($projectArr as $key => $val){
						$projectProcess = $esmactivityDao->getProjectProcess_d($val);
						$esmprojectDao->updateProjectProcess_d($val,$projectProcess);
					}
				}
			}else{
				//更新日志状态为已提交
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

	//周报打回
	function backLog_d($id){
		try{
			$this->start_d();

			$object['id'] = $id;
			$object['exaResults'] = 'ZBKHJZ-02';
			$object['subStatus'] = 'BTG';
			$object['exaDate'] = day_date;

			//更新周报信息
			parent::edit_d($object,true);

			//更新日志状态
			$esmworklogDao = new model_engineering_worklog_esmworklog();
			$esmworklogDao->updateStatus_d($object['id'],'BTG');

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}

	}

	/****************** 其他业务逻辑部分 **************/

	/**
	 *
	 * 判断日志对应的周报是否存在,否则添加
	 * @param  $object
	 */
	function checkIsWeeklog($object) {

        //获取预定义周次 年周次
        $weekDao = new model_engineering_baseinfo_week();
        $weekTimes = $weekDao->getWeekNoByDayTimes($object ['executionDate']);
		$week = date ( 'W', strtotime ( $object ['executionDate'] ) ); //获取执行日期周次

		$userId = $_SESSION ['USER_ID'];

		//查询是否存在对应周报
		$weekArr = $this->find(array("weekTimes" => $weekTimes, 'createId' => $userId));

		//周报不存在就新增周报
		if (! is_array ( $weekArr )) {
			$thisWeek  = $this->getWeekDate($week,date("Y"));
			$weekTitle = $_SESSION['USERNAME'] .'工作日志( '.$thisWeek[0] . ' ~ ' .$thisWeek[1] .')';
			$weekBeginDate = $thisWeek[0];
			$weekEndDate = $thisWeek[1];

			//人事信息获取
			$personnelDao = new model_hr_personnel_personnel();
			$personnelArr = $personnelDao->getPersonnelInfo_d($userId);
			$depId = $personnelArr ['belongDeptId'];
			$depName = $personnelArr ['belongDeptName'];
			$rankCode = $personnelArr ['personLevel'];
			//备胎
			if(!$personnelArr){
				$userDao = new model_common_otherdatas ();
				$userInfo = $userDao->getUserDatas ( $userId, array ('DEPT_NAME', 'DEPT_ID' ) );
				$depId = $userInfo ['DEPT_ID'];
				$depName = $userInfo ['DEPT_NAME'];
			}

			//创建周报数组
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

			//新增周报
			$weekId = $this->add_d ( $myobj, true );

			return $weekId;
		} else {
			return $weekArr['id'];
		}
	}

	//获取周的开始日期和结束日期
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

	//判断日期内是否已经存在关闭或者正在审核的周报
	function isSetWeekLog_d($userId,$date){
        //获取预定义周次 年周次
        $weekDao = new model_engineering_baseinfo_week();
        $weekTimes = $weekDao->getWeekNoByDayTimes($date);
		$rs = $this->find(array('weekTimes' => $weekTimes ,'createId' => $userId),null,'subStatus');
		if($rs){
			if($rs['subStatus'] == 'YTJ'){//已提交
				return 1;
			}
			if($rs['subStatus'] == 'YQR'){//已确认
				return 2;
			}

			return false;
		}else{
			return false;
		}
	}

    //指标部分渲染
    function initAuditIndex_d($object){
        //获取审核对应的项目id
        $esmworklogDao = new model_engineering_worklog_esmworklog();
        $projectId = $esmworklogDao->getAuditProjectId_d($object['id']);
        //指标获取
        $esmassproDao = new model_engineering_assess_esmasspro();
        $esmassproObj = $esmassproDao->find(array('projectId'=>$projectId),null,'id');

        //如果有项目id
        if($esmassproObj){
            $esmassindexDao = new model_engineering_assess_esmassproindex();
            $esmassindexArr = $esmassindexDao->findAll(array('assessId' => $esmassproObj['id']));

            $str = $this->initAudit_d($esmassindexArr,$object['id']);
            return $str;
        }else{
        	return '';
        }
    }

    //考核指标渲染
    function initAudit_d($esmassindexArr,$weekId){

        //获取配置考核等级
        $esmasslevelDao = new model_engineering_assess_esmasslevel();
        $esmasslevelArr = $esmasslevelDao->findAll(null,'upperLimit desc');
        $maxScore = $esmasslevelArr[0]['upperLimit'];
        $fistStr = $this->initLevelLimit_d($esmasslevelArr);
		$str =<<<EOT
			<tr>
	            <td class="form_text_left_three">考核分数</td>
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
            //指标选项生成
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

    //将数组初始化成option选项
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

    //等级匹配数组处理
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

    //初始化考核内容
    function initAuditIndexView_d($object){
        //获取审核对应的项目id
        $esmrsindexDao = new model_engineering_worklog_esmrsindex();
        $esmrsindexArr = $esmrsindexDao->findAll(array('weekId' => $object['id']));
		$str=<<<EOT
            <tr>
                <td class="form_text_left_three"><font style="font-weight:bold">考核分数</font></td>
                <td class="form_text_right_three"><font style="font-weight:bold">$object[rsScore]</font></td>
                <td class="form_text_left_three"><font style="font-weight:bold">考 核 人</font></td>
                <td class="form_text_right_three"><font style="font-weight:bold">$object[assessmentName]</font></td>
                <td class="form_text_left_three"><font style="font-weight:bold">考核日期</font></td>
                <td class="form_text_right_three"><font style="font-weight:bold">$object[exaDate]</font></td>
            </tr>
EOT;
        //如果有项目id
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
        	return '周报暂无考核信息';
        }
    }
    /**
     *
     * excel导出
     * @param $obj 根据条件导出
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