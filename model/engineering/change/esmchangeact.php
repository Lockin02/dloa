<?php
/**
 * @author Show
 * @Date 2012��12��15�� ������ 15:21:07
 * @version 1.0
 * @description:��Ŀ��Χ����� Model��
 */
class model_engineering_change_esmchangeact extends model_treeNode {

	function __construct() {
		$this->tbl_name = "oa_esm_change_activity";
		$this->sql_map = "engineering/change/esmchangeactSql.php";
		parent :: __construct();
	}

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
    	'workloadUnit'
    );

	//��������
	public $treeCondFields = array('changeId');

    //����������� ----------------------------------------//
    //����
    function add_d($object){
        try{
            $this->start_d();

			//���������changeId
			if(!$object['changeId']){
	            //�ж��Ƿ��Ѿ����ڱ������
	            $esmchangeDao = new model_engineering_change_esmchange();
	            $changeId = $esmchangeDao->getChangeId_d($object['projectId']);

	            //������ڵ��ֵ��-1����ת��
				if($object['parentId'] == PARENT_ID){
					$object['parentId'] = $this->getChangeRoot_d($changeId);
				}else{
					$parentObj = $this->getChangInfo_d($object['parentId'],$changeId);
					$object['parentId'] = $parentObj['id'];
				}
           		$object['changeId'] = $changeId;
			}

            //������������
            $object['isChanging'] = '1';
            $object['changeAction'] = 'add';
            $newId = $this->addOrg_d($object,true);

            $this->commit_d();
            return $newId;
        }catch(Exception $e){
            $this->rollBack();
            throw $e;
        }
    }

    //���ԭ����
    function addOrg_d($object){
        try{
        	$object = $this->processDatadict($object);
            parent::add_d($object,true);

            // ���±������Ŀ�ʼ����������
            return $this->updateChangeDate_d($object['changeId'],$object['projectId']);
        }catch(Exception $e){
            throw $e;
        }
    }

    //���ԭ����
    function edit_d($object){
        try{
            $this->start_d();

            //ԭ����״̬����
            $esmactivityDao = new model_engineering_activity_esmactivity();
            $esmactivityDao->changeInfoSet_d($object['id'],'edit');

            //�ж��Ƿ��Ѿ����ڱ������
            $esmchangeDao = new model_engineering_change_esmchange();
            $changeId = $esmchangeDao->getChangeId_d($object['projectId']);

			if(!$object['changeId']){
	            //������ڵ��ֵ��-1����ת��
				if($object['parentId'] == PARENT_ID){
					$object['parentId'] = $this->getChangeRoot_d($changeId);
				}else{
					$parentObj = $this->getChangInfo_d($object['parentId'],$changeId);
					$object['parentId'] = $parentObj['id'];
				}
           		$object['changeId'] = $changeId;
			}

            //�༭��������
            $changeObj = $this->getChangeActivityInfo_d($object['id'],$changeId);
            $object['id'] = $changeObj['id'];
            $object['isChanging'] = '1';
            $object['changeAction'] = 'edit';
            $this->editOrg_d($object,true);

            $this->commit_d();
            return true;
        }catch(Exception $e){
            $this->rollBack();
            throw $e;
        }
    }

    //���ԭ����
    function editOrg_d($object){
        try{
        	$object = $this->processDatadict($object);
             parent::edit_d($object,true);

            // ���±������Ŀ�ʼ����������
            return $this->updateChangeDate_d($object['changeId'],$object['projectId']);
        }catch(Exception $e){
            throw $e;
        }
    }

    //����ת��
    function move_d($object,$esmactivityDao = null){
		try {
			$this->start_d ();

            //�ж��Ƿ��Ѿ����ڱ������
            $esmchangeDao = new model_engineering_change_esmchange();
            $changeId = $esmchangeDao->getChangeId_d($object['projectId']);

			//����Ѿ���changid���Ͳ������⴦��
			if(!$object['changeId']){
		        //��ȡ�����¼�еĸ��ڵ�
		        $changeObj = $this->getChangInfo_d($object['parentId'],$changeId);
				$object['parentId'] = $changeObj['id'];
			}
			$object['isChangeParent'] = 1;
			$object['changeId'] = $changeId;

			//��ȡ����Ϣ
			$parentObj = $this->get_d($object['parentId']);
			if(!empty($parentObj['activityId'])){//������ڵ����ԭid�������ö�Ӧԭ����Ϊ�ڱ��
	            //���ڵ�༭
	            $esmactivityDao->changeInfoSet_d($parentObj['activityId'],'edit');
	            $this->changeInfoSet_d($parentObj['id'],'edit');
			}

            //��ո��ڵ�����
			$this->clearActivity_d($object['parentId'],$changeId);

			//ת�Ʋ���
			$newId = $this->add_d( $object );

            //�����ϼ����������Ϣ
            $this->updateAllParent_d($newId,$object);

			$this->commit_d ();
			return $newId;
		} catch ( Exception $e ) {
			$this->rollBack ();
            throw $e;
		}
    }

	/**
	 * @desription ����ɾ�����ڵ�
	 * @param $ids:���ڵ�id���ϣ���,����
	 */
	function deletes_d($id,$changeId = null,$projectId = null) {
		try {
			$this->start_d ();

			//��ȡ�ӽڵ�
			$node=$this->get_d($id);
			//����Ǳ����¼ ���� ��ǰ��¼�Ǳ�������ģ������ֱ��ɾ��
			if($changeId && empty($node['activityId'])){
				$childNodes=$this->getChildrenByNode ( $node );
	            if($childNodes){
	                foreach($childNodes as $val){
	                    $this->deleteNodes ( $val['id'] );
	                    parent::deletes ($val['id']);
	                }
	            }
				parent::deletes ( $id );
			}else{
	            //ԭ����״̬����
	            $esmactivityDao = new model_engineering_activity_esmactivity();

				//������ʱ������¼idΪ�գ���˵������û�б�����뵥�������ɾ������
				if(empty($changeId)){
					//�����������
		            $esmchangeDao = new model_engineering_change_esmchange();
		            $changeId = $esmchangeDao->getChangeId_d($projectId);

		            //��ѯ�����¼�ж�Ӧ�������¼
            		$changeObj = $this->getChangeActivityInfo_d($id,$changeId);

					//��ȡ�ӽڵ�
					$node=$this->get_d($changeObj['id']);
       				$this->changeInfoSet_d($changeObj['id'],'delete');
				}else{
					//��ȡ�ӽڵ�
					$node=$this->get_d($id);
       				$this->changeInfoSet_d($id,'delete');
				}

				$childNodes=$this->getChildrenByNode ( $node );
	            if($childNodes){
	                foreach($childNodes as $val){
	           			$esmactivityDao->changeInfoSet_d($val['activityId'],'delete');
	           			$this->changeInfoSet_d($val['id'],'delete');
	                }
	            }
	            //���ڵ�༭
	            $esmactivityDao->changeInfoSet_d($node['activityId'],'delete');
			}

            // ���±������Ŀ�ʼ����������
            $this->updateChangeDate_d($node['changeId'],$node['projectId']);

			//toDO ɾ���ڵ��µ��ӽڵ�
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw $e;
		}
	}

    //����ԭʼ�����¼
    function createActivity_d($arr,$changeId,$projectInfo = null){
        try{
            $this->start_d();

            if($arr){
				//��ʼ���������Сֵ
	            $min = null;
	            $max = null;

	            //ѭ������
	            foreach($arr as $val){
					//���������С
					$min = empty($min) ? $val['lft'] : min($val['lft'],$min);
					$max = empty($max) ? $val['rgt'] : max($val['rgt'],$max);
	            }

				//��ʼ�����ø��ڵ�
	            $object = array(
	            	'lft' => $min - 1,
	            	'rgt' => $max + 1,
	            	'parentId' => -1,
	            	'isRoot' => 1,
	            	'changeId' => $changeId,
	            	'projectId' => $val['projectId'],
	            	'projectCode' => $val['projectCode'],
	            	'projectName' => $val['projectName']
	            );
	            $newId = parent::add_d($object,true);

				//����parentId����
				$cacheArr = array( -1 => $newId);//��������id�ı� ���id => ��ʽid

	            //ѭ������
	            foreach($arr as $val){
					$val['changeId'] = $changeId;
					$val['activityId'] = $val['id'];
					$val['parentId'] = $cacheArr[$val['parentId']];
					unset($val['id']);

					$newId = parent::add_d($val,true);

					//���������ڵ㣬���������
					if(empty($val['workloadUnit'])){
						$this->clearActivity_d($newId,$changeId);
					}

					//��������
					$cacheArr[$val['activityId']] = $newId;
	            }
            }else{
				//��ʼ�����ø��ڵ�
	            $object = array(
	            	'lft' => 1,
	            	'rgt' => 2,
	            	'parentId' => -1,
	            	'isRoot' => 1,
	            	'changeId' => $changeId,
	            	'projectId' => $projectInfo['id'],
	            	'projectCode' => $projectInfo['projectCode'],
	            	'projectName' => $projectInfo['projectName']
	            );
	            parent::add_d($object,true);
            }

            $this->commit_d();
            return true;
        }catch(Exception $e){
            $this->rollBack();
            throw $e;
        }
    }

	//������շ�����Ϊ��ʹ���ݿ���������
	function clearActivity_d($id,$changeId){
		$sql = "update ".$this->tbl_name. " set
				budgetAll = null,workload = null,workloadUnit = null ,workloadUnitName = null,
				workContent = null,remark = null
			where id = ".$id ." and changeId = ".$changeId;
		return $this->_db->query($sql);
	}

	/**
	 * ��Ŀ��Χ - �����ϼ� - �ݹ麯��
	 * $id ��Χid
	 * @obj ���ض��� - ��Ҫ������ɾ���󣬷�ֹ������Ϣ��ѯ����
	 */
	function updateAllParent_d($id,$obj = null){
		if(empty($obj)){
			$obj = $this->get_d($id);
		}

		//��ѯ��Χ�����������Լ�����
		$sql = "select
				min(planBeginDate) as planBeginDate,max(planEndDate) as planEndDate,
				round((UNIX_TIMESTAMP( max(planEndDate) ) - UNIX_TIMESTAMP( min(planBeginDate) ) )/(3600 *24)) + 1 as days,
				round(sum(workRate*process/100),2) as process
			from ". $this->tbl_name ." where parentId = ".$obj['parentId'];
		$rs = $this->_db->getArray($sql);
		$this->update(
			array('id' => $obj['parentId']),
			array('planBeginDate' => $rs[0]['planBeginDate'],'planEndDate' => $rs[0]['planEndDate'],'days' => $rs[0]['days'],'process' => $rs[0]['process'])
		);

		//����Ѿ��Ǹ��ڵ㣬ֱ�ӷ���
		if($obj['parentId'] == PARENT_ID){
			return true;
		}else{
			return $this->updateAllParent_d($obj['parentId']);
		}
	}

    /**
     *  ���±����¼�ļƻ���ʼ�ͼƻ�����
     */
    function updateChangeDate_d($changeId,$projectId){
        if(!$changeId){
            //�ж��Ƿ��Ѿ����ڱ������
            $esmchangeDao = new model_engineering_change_esmchange();
            $changeId = $esmchangeDao->getChangeId_d($projectId);
        }
        $sql = "select
				min(planBeginDate) as planBeginDate,max(planEndDate) as planEndDate
			from ". $this->tbl_name ." where changeId = $changeId AND planBeginDate <> '0000-00-00' AND planBeginDate IS NOT NULL
                AND planEndDate <> '0000-00-00' AND planEndDate IS NOT NULL";
        $rs = $this->_db->getArray($sql);

        //��ȡ��Ŀ�еļƻ���������
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectArr = $esmprojectDao->find(array('id'=>$projectId),null,'planEndDate,salesman,salesmanId');
        $projectStamp = strtotime($esmprojectArr['planEndDate']);

        //���±�����뵥�еļƻ�����
        $changeStamp = strtotime($rs[0]['planEndDate']);
        $esmchangeDao = new model_engineering_change_esmchange();
        $esmchangeObj = array('planBeginDate' => $rs[0]['planBeginDate'],
            'planEndDate' => $rs[0]['planEndDate'],'newPlanEndDate' => $rs[0]['planEndDate'],
			'expectedDuration' => (strtotime($rs[0]['planEndDate']) - strtotime($rs[0]['planBeginDate'])) / 86400 + 1);
        //����������Ŀ���ڣ���ô��ȡ��Ŀ��������Ա����������
        if($projectStamp < $changeStamp && $esmprojectArr['salesman']){
            $esmchangeObj['salesman'] = $esmprojectArr['salesman'];
            $esmchangeObj['salesmanId'] = $esmprojectArr['salesmanId'];
        }else{
            $esmchangeObj['salesman'] = '';
            $esmchangeObj['salesmanId'] = '';
        }
        return $esmchangeDao->update(array('id' => $changeId),$esmchangeObj);
    }

    /**
     *  ���±����¼�ļƻ���ʼ�ͼƻ�����
     */
    function checkIsLate_d($changeId,$projectId){
        //��ȡ��Ŀ�еļƻ���������
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectArr = $esmprojectDao->find(array('id'=>$projectId),null,'planEndDate');
        $projectStamp = strtotime($esmprojectArr['planEndDate']);

        //���±�����뵥�еļƻ�����
        $changeInfo = $this->find(array('id' => $changeId),null,'$planEndDate');
        $changeStamp = strtotime($changeInfo['$planEndDate']);

        return $projectStamp != $changeStamp ? 1 : 0;
    }

	/********************* ҵ�����ݻ�ȡ *********************/
	/**
	 * ��ȡ����ķ�Χ
	 */
	function getChangeActivity_d($changeId){
		return $this->findAll(array('changeId' => $changeId));
	}

    /**
     * ��ȡ��Ŀ��Χ
     */
    function getActivity_d($activityId){
        $activityDao = new model_engineering_activity_esmactivity();
        return $activityDao->get_d($activityId);
    }

    /**
     * ��ȡ��Ŀ
     */
    function getProject_d($projectId){
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectObj = $esmprojectDao->find(array( 'id' => $projectId),null,'id,projectCode,projectName');
		$esmprojectObj['projectId'] = $esmprojectObj['id'];
		unset($esmprojectObj['id']);
        return $esmprojectObj;
    }

    //��ȡ�������Ŀ������Ϣ
    function getChangeActivityInfo_d($activityId,$changeId){
		return $this->find(array('activityId' => $activityId ,'changeId' => $changeId));
    }

	/********************* ���ݻ�ȡ�Լ���ʾ *****************/

	//������ʾ����
	function initEdit_d($changeId){
		$str = "";
		$rows = $this->findAll(array('changeId' => $changeId,'isDel' => 0));
		if($rows){
			$i = 0;
			//ʵ��������Ԥ��
			$esmchangeperDao = new model_engineering_change_esmchangeper();
			//ʵ��������Ԥ��
			$esmchangebudDao = new model_engineering_change_esmchangebud();

			foreach($rows as $key => $val){
				++$i;
				$trClass = $key %2 == 0 ? 'tr_odd' : 'tr_even';
				//�����Ŀ��Χ�Ѿ��޸ģ�����ʾ�༭
				if($val['isChange'] == 1){
					$viewStyle = "style='display:none'";
					$editStyle = "";
				}else{
					$viewStyle = "";
					$editStyle = "style='display:none'";
				}

				$str.=<<<EOT
					<tr class="$trClass trView$key" id="trView$key" $viewStyle>
						<td valign="top">
							<img style="cursor:pointer;" src="images/removeline.png" title="ɾ������" onclick="delActivity(this,$key)"/>
						</td>
						<td valign="top">$i</td>
						<td align="left" valign="top">
							$val[activityName]
							<img src="images/changeedit.gif" id="imgInv24" onclick="changeActivity($key)" title="���������"/>
						</td>
						<td valign="top">$val[workRate]</td>
						<td valign="top">$val[planBeginDate]</td>
						<td valign="top">$val[planBeginDate]</td>
						<td valign="top">$val[days]</td>
						<td valign="top">$val[workload]</td>
						<td valign="top">$val[workloadUnitName]</td>
						<td valign="top"><span class="formatMoney">$val[budgetAll]</span></td>
						<td align="left" valign="top">$val[workContent]</td>
					</tr>
					<tr class="$trClass trEdit$key" id="trActivity$key" $editStyle>
						<td valign="top">
							<img style="cursor:pointer;" src="images/removeline.png" title="ɾ������" onclick="delActivity(this,$key)"/>
						</td>
						<td valign="top">$i</td>
						<td align="left" valign="top">
							<input type="text" class="txt" name="esmchange[esmactivity][$key][activityName]" id="activityName$key" value="$val[activityName]"/>
							<input type="hidden" name="esmchange[esmactivity][$key][activityId]" id="activityId$key" value="$val[activityId]"/>
							<input type="hidden" name="esmchange[esmactivity][$key][id]" value="$val[id]"/>
							<input type="hidden" name="esmchange[esmactivity][$key][projectId]" value="$val[projectId]"/>
							<input type="hidden" name="esmchange[esmactivity][$key][projectCode]" value="$val[projectCode]"/>
							<input type="hidden" name="esmchange[esmactivity][$key][projectName]" value="$val[projectName]"/>
							<input type="hidden" id="isChange$key" name="esmchange[esmactivity][$key][isChange]" value="$val[isChange]"/>
						</td>
						<td valign="top">
							<input type="text" class="txtshort" name="esmchange[esmactivity][$key][workRate]" id="workRate$key" value="$val[workRate]"/>
						</td>
						<td valign="top">
							<input type="text" class="txtshort Wdate" style="width:90px" id="actPlanBeginDate$key" name="esmchange[esmactivity][$key][planBeginDate]" value="$val[planBeginDate]" onblur="timeCheckAct(this,$key);" onfocus="WdatePicker();"/>
						</td>
						<td valign="top">
							<input type="text" class="txtshort Wdate" style="width:90px" id="actPlanEndDate$key" name="esmchange[esmactivity][$key][planEndDate]" value="$val[planEndDate]" onblur="timeCheckAct(this,$key);" onfocus="WdatePicker();"/>
						</td>
						<td valign="top">
							<input type="text" class="txtmin" id="actDays$key" name="esmchange[esmactivity][$key][days]" value="$val[days]" style="width:50px"/>
						</td>
						<td valign="top">
							<input type="text" class="txtmin" id="actWorkload$key" name="esmchange[esmactivity][$key][workload]" value="$val[workload]" style="width:50px"/>
						</td>
						<td valign="top">
							<input type="text" class="readOnlyTxtShort" name="esmchange[esmactivity][$key][workloadUnitName]" value="$val[workloadUnitName]" readonly="readonly" style="width:50px"/>
							<input type="hidden" id="actWorkloadUnit$key" name="esmchange[esmactivity][$key][workloadUnit]" value="$val[workloadUnit]"/>
						</td>
						<td valign="top">
							<input type="text" class="readOnlyTxtShort formatMoney" id="budgetAll$key" name="esmchange[esmactivity][$key][budgetAll]" value="$val[budgetAll]" readonly="readonly"/>
						</td>
						<td align="left" valign="top">
							<input type="text" class="txt" name="esmchange[esmactivity][$key][workContent]" style="width:300px" value="$val[workContent]"/>
							<input type="hidden" name="esmchange[esmactivity][$key][remark]" value="$val[remark]"/>
						</td>
					</tr>
EOT;
				//����Ԥ�����
				$esmpersonArr = $esmchangeperDao->findAll(array('activityId' => $val['id'],'isDel' => 0));
				$pi = 0;
				$str.=<<<EOT
					<tr class="$trClass trEdit$key" id="outTrPerson$key" $editStyle>
						<td valign="top" colspan="2">����Ԥ��</td>
						<td valign="top" colspan="9">
							<table class="form_in_table">
								<tr class="main_tr_header">
									<th style="width:40px">
								 		<img src="images/add_item.png" onclick="addPerson($key)" title="�����"/>
								 	</th>
									<th>��Ա�ȼ�</th>
									<th>Ԥ�ƿ�ʼ</th>
									<th>Ԥ�ƽ���</th>
									<th>����</th>
									<th>����</th>
									<th>��������</th>
									<th>�����ɱ�</th>
									<th>�����ɱ����</th>
									<th>��ע��Ϣ</th>
								</tr>
							</thead>
							<tbody id="tblPerson$key">
EOT;
				foreach($esmpersonArr as $k => $v){
					$innerI = $key."_".$k;
					$str.=<<<EOT
						<tr class="$trClass trEdit$key" id="trPerson$innerI" $editStyle>
							<td valign="top">
								<img style="cursor:pointer;" src="images/removeline.png" title="ɾ������Ԥ��" onclick="alert('ɾ������Ԥ��');"/>
							</td>
							<td align="left" valign="top">
								<input type="text" class="txtmiddle" id="personLevel$innerI" name="esmchange[esmactivity][$key][esmperson][$k][personLevel]" value="$v[personLevel]"/>
								<input type="hidden" id="personLevelId$innerI" name="esmchange[esmactivity][$key][esmperson][$k][id]" value="$v[id]" trNo="$key" innerTrNo="$k"/>
								<input type="hidden" name="esmchange[esmactivity][$key][esmperson][$k][orgId]" value="$v[orgId]"/>
								<input type="hidden" name="esmchange[esmactivity][$key][esmperson][$k][personLevelId]" value="$v[personLevelId]"/>
								<input type="hidden" name="esmchange[esmactivity][$key][esmperson][$k][projectId]" value="$v[projectId]"/>
								<input type="hidden" name="esmchange[esmactivity][$key][esmperson][$k][projectCode]" value="$v[projectCode]"/>
								<input type="hidden" name="esmchange[esmactivity][$key][esmperson][$k][projectName]" value="$v[projectName]"/>
								<input type="hidden" name="esmchange[esmactivity][$key][esmperson][$k][activityName]" value="$v[activityName]"/>
							</td>
							<td valign="top">
								<input type="text" class="txtmiddle Wdate" style="width:90px" id="perPlanBeginDate$innerI" name="esmchange[esmactivity][$key][esmperson][$k][planBeginDate]" value="$v[planBeginDate]" onblur="timeCheckPerson(this,$key,$k);" onfocus="WdatePicker();"/>
							</td>
							<td valign="top">
								<input type="text" class="txtmiddle Wdate" style="width:90px" id="perPlanEndDate$innerI" name="esmchange[esmactivity][$key][esmperson][$k][planEndDate]" value="$v[planEndDate]" onblur="timeCheckPerson(this,$key,$k);" onfocus="WdatePicker();"/>
							</td>
							<td valign="top">
								<input type="text" class="txtmin" id="perDays$innerI" name="esmchange[esmactivity][$key][esmperson][$k][days]" value="$v[days]" style="width:50px" onblur="calPersonBatch('$innerI')"/>
								<input type="hidden" id="perPrice$innerI" name="esmchange[esmactivity][$key][esmperson][$k][price]" value="$v[price]"/>
								<input type="hidden" id="perCoefficient$innerI" name="esmchange[esmactivity][$key][esmperson][$k][coefficient]" value="$v[coefficient]"/>
							</td>
							<td valign="top">
								<input type="text" class="txtmin" id="perNumber$innerI" name="esmchange[esmactivity][$key][esmperson][$k][number]" value="$v[number]" style="width:50px" onblur="calPersonBatch('$innerI')"/>
							</td>
							<td valign="top">
								<input type="text" class="readOnlyTxtMiddle" id="perPersonDays$innerI" style="width:90px" name="esmchange[esmactivity][$key][esmperson][$k][personDays]" value="$v[personDays]" readonly="readonly"/>
							</td>
							<td valign="top">
								<input type="text" class="readOnlyTxtMiddle" id="perPersonCostDays$innerI" style="width:90px" name="esmchange[esmactivity][$key][esmperson][$k][personCostDays]" value="$v[personCostDays]" readonly="readonly"/>
							</td>
							<td valign="top">
								<input type="text" class="readOnlyTxtMiddle formatMoney" id="perPersonCost$innerI" style="width:90px" name="esmchange[esmactivity][$key][esmperson][$k][personCost]" value="$v[personCost]" readonly="readonly"/>
							</td>
							<td align="left" valign="top">
								<input type="text" class="txt" name="esmchange[esmactivity][$key][esmperson][$k][remark]" value="$v[remark]"/>
							</td>
						</tr>
EOT;
				}
				$str.=<<<EOT
							</tbody>
						</table>
					</td>
				</tr>
EOT;

				//����Ԥ�����
				$esmbudgetArr = $esmchangebudDao->findAll(array('activityId' => $val['id'],'isDel' => 0));
				$bgi = 0;
				$str.=<<<EOT
					<tr class="$trClass trEdit$key" id="outTrBudget$key" $editStyle>
						<td valign="top" colspan="2">����Ԥ��</td>
						<td valign="top" colspan="9">
							<table class="form_in_table">
								<tr class="main_tr_header">
									<th style="width:40px">
								 		<img src="images/add_item.png" onclick="addBudget($key)" title="�����"/>
								 	</th>
									<th>���÷���</th>
									<th>Ԥ������</th>
									<th>����</th>
									<th>����1</th>
									<th>����2</th>
									<th>Ԥ����</th>
									<th style="width:300px">��ע��Ϣ</th>
								</tr>
							</thead>
							<tbody id="tblBudget$key">
EOT;
				foreach($esmbudgetArr as $k => $v){
					$innerI = $key."_".$k;
					$str.=<<<EOT
						<tr class="$trClass trEdit$key" id="trBudget$innerI" $editStyle>
							<td valign="top">
								<img style="cursor:pointer;" src="images/removeline.png" title="ɾ������Ԥ��" onclick="alert('ɾ������Ԥ��');"/>
							</td>
							<td valign="top">
								<input type="text" class="readOnlyTxtMiddle" id="budParentName$innerI" name="esmchange[esmactivity][$key][esmbudget][$k][parentName]" value="$v[parentName]"/>
								<input type="hidden" id="budParentId$innerI" name="esmchange[esmactivity][$key][esmbudget][$k][id]" value="$v[id]"/>
								<input type="hidden" name="esmchange[esmactivity][$key][esmbudget][$k][orgId]" value="$v[orgId]"/>
								<input type="hidden" name="esmchange[esmactivity][$key][esmbudget][$k][parentId]" value="$v[parentId]"/>
								<input type="hidden" name="esmchange[esmactivity][$key][esmbudget][$k][projectId]" value="$v[projectId]"/>
								<input type="hidden" name="esmchange[esmactivity][$key][esmbudget][$k][projectCode]" value="$v[projectCode]"/>
								<input type="hidden" name="esmchange[esmactivity][$key][esmbudget][$k][projectName]" value="$v[projectName]"/>
								<input type="hidden" name="esmchange[esmactivity][$key][esmbudget][$k][activityName]" value="$val[activityName]"/>
							</td>
							<td valign="top">
								<input type="text" class="txtmiddle" id="budBudgetName$innerI" name="esmchange[esmactivity][$key][esmbudget][$k][budgetName]" value="$v[budgetName]" trNo="$key" innerTrNo="$k"/>
								<input type="hidden" id="budBudgetId$innerI" name="esmchange[esmactivity][$key][esmbudget][$k][budgetId]" value="$v[budgetId]"/>
							</td>
							<td valign="top">
								<input type="text" id="budPrice$innerI" class="txtshort" name="esmchange[esmactivity][$key][esmbudget][$k][price]" onblur="calBudget(this,$key,$k);" value="$v[price]"/>
							</td>
							<td valign="top">
								<input type="text" id="budNumberOne$innerI" class="txtshort" name="esmchange[esmactivity][$key][esmbudget][$k][numberOne]" onblur="calBudget(this,$key,$k);" value="$v[numberOne]"/>
							</td>
							<td valign="top">
								<input type="text" id="budNumberTwo$innerI" class="txtshort" name="esmchange[esmactivity][$key][esmbudget][$k][numberTwo]" onblur="calBudget(this,$key,$k);" value="$v[numberTwo]"/>
							</td>
							<td valign="top">
								<input type="text" id="budAmount$innerI" class="txtshort formatMoney" name="esmchange[esmactivity][$key][esmbudget][$k][amount]" value="$v[amount]"/>
							</td>
							<td valign="top">
								<input type="text" class="txtlong" name="esmchange[esmactivity][$key][esmbudget][$k][remark]" value="$v[remark]"/>
							</td>
						</tr>
EOT;
				}
				$str.=<<<EOT
							</tbody>
						</table>
					</td>
				</tr>
EOT;
    		}
		}
		return $str;
	}

	//������ʾ����
	function initView_d($changeId){
		$str = "";

		//��ȡ���ڵ�
		$parentId = $this->getChangeRoot_d($changeId);

		$rows = $this->findAll(array('changeId' => $changeId,'isRoot' => 0),'lft');
		if($rows){
			$i = 0;
			$mark = null;//��ʶλ
			$level = 0;//����
			$isCal = 0;//�Ƿ��Ѿ����㼶��

			foreach($rows as $key => $val){
				$workRateCss = $val['parentId'] == $parentId ? 'font-weight:bold;' : '';

				if($val['parentId'] == $parentId){
					//���ñ�־λ
					$mark = $val['id'];
					//���ü���
					$level = 0;
					$isCal = 0;
				}else if( $mark == $val['parentId']){
					if(!$isCal){
						$level ++;
						$isCal = 1;
					}
				}else{

				}
				//���¼���ʽ��Ⱦ
				$appendStr = $this->rtAppendStr_v($level);

				if($level == 0){
					++$i;
					$trClass = $i %2 == 0 ? 'tr_odd' : 'tr_even';
					$j = $i;
				}else{
					$j = '';
				}
				
				if($val['planBeginDate'] == '0000-00-00'){
					$val['planBeginDate'] = '';
				}
				
				if($val['planEndDate'] == '0000-00-00'){
					$val['planEndDate'] = '';
				}
				
				//�����Ŀ��Χ�Ѿ��޸ģ�����ʾ�༭
				if($val['changeAction'] != ""){
					$str.=<<<EOT
						<tr class="$trClass" id="trActivity$key">
							<td valign="top">$j</td>
							<td align="left" valign="top">
EOT;
					//����
					if($val['changeAction'] == "edit"){
						$str.=<<<EOT
								$appendStr<img src="images/changeedit.gif" id="editImg$key" isHide="0" title="���������"/><span class='red'>$val[activityName]</span>
EOT;
					}elseif($val['changeAction'] == 'add'){
						$str.=<<<EOT
								$appendStr<img src="images/new.gif" id="editImg$key" isHide="0" title="����������"/><span class='red'>$val[activityName]</span>
EOT;
					}else{
						$str.=<<<EOT
								$appendStr<span class='red' style='text-decoration:line-through;' title='ɾ��������'>$val[activityName]</span>
EOT;
					}
					$str.=<<<EOT
							</td>
							<td valign="top" style="$workRateCss">$val[workRate]</td>
							<td valign="top">$val[planBeginDate]</td>
							<td valign="top">$val[planEndDate]</td>
							<td valign="top">$val[days]</td>
							<td valign="top">$val[workload]</td>
							<td valign="top">$val[workloadUnitName]</td>
							<td align="left" valign="top">$val[workContent]</td>
						</tr>
EOT;
				}else{
					$str.=<<<EOT
						<tr class="$trClass" id="trView$key">
							<td valign="top">$j</td>
							<td align="left" valign="top">
								$appendStr $val[activityName]
							</td>
							<td valign="top" style="$workRateCss">$val[workRate]</td>
							<td valign="top">$val[planBeginDate]</td>
							<td valign="top">$val[planEndDate]</td>
							<td valign="top">$val[days]</td>
							<td valign="top">$val[workload]</td>
							<td valign="top">$val[workloadUnitName]</td>
							<td align="left" valign="top">$val[workContent]</td>
						</tr>
EOT;
				}
			}
		}
		return $str;
	}

	/**
	 * ��ȡ�����������Ϣ
	 */
	function getChangeArr_d($changeactId,$isChanging = null){
		$condition = array('changeId' => $changeactId);
		if($isChanging !== null){
			$condition['isChanging'] = $isChanging ;
		}
		return $this->findAll($condition,'lft');
	}

    /**
     * ��ԭ���״̬
     */
    function rollBackChangeInfo_d($changeId){
		return $this->update(array('changeId' => $changeId),array('isChanging' => 0));
    }

    /**
     * ��ȡ����ĸ�Ŀ¼
     */
    function getChangeRoot_d($changeId){
		$obj = $this->find(array('changeId' => $changeId,'isRoot' => 1),null,'id');
		return $obj['id'];
    }

    /**
     * ��ȡ�����¼���е�����
     */
    function getChangInfo_d($activityId,$changeId){
		return $this->find(array('changeId' => $changeId,'activityId' => $activityId));
    }

	/**
	 * ����ǰ�ÿո�
	 */
	function rtAppendStr_v($level){
		if($level == 0){
			return "";
		}
		$str = "";
		for($i = 0 ; $i < $level ; $i++){
			$str.="&nbsp;&nbsp;&nbsp;";
		}
		return $str.'�� ';
	}

    //��� �༭����ʱ����״̬
    //���������࣬add ,edit ,delete
    function changeInfoSet_d($id,$thisAction = 'add'){
		return $this->update(array('id' => $id),array('changeAction' => $thisAction,'isChanging' => 1));
    }

    /**
     *
     * ������Ŀid������Ŀ������ռ���ܺ�
     * @param $projectId
     */
    function workRateCount($changeId,$parentId = -1){
    	$num=0;
    	if($parentId == -1){
    		$parentNode = $this->findAll(array('parentId'=> $parentId,'changeId'=>$changeId), null, 'id');
	    	$parentNodeId = $parentNode[0]['id'];
	    	$workRateRow=$this->findAll(array('parentId'=> $parentNodeId ,'changeId'=>$changeId), null, 'workRate,changeAction');
    	}else{
	    	$workRateRow=$this->findAll(array('parentId'=> $parentId ,'changeId'=>$changeId), null, 'workRate,changeAction');
    	}

    	$workRateRow=array_filter($workRateRow);
    	if(!is_array($workRateRow)||empty($workRateRow))
    		return 0;
    	foreach ($workRateRow as $value) {
            if($value['changeAction'] != 'delete'){
                $num+=$value['workRate'];
            }
    	}
    	return $num;
    }

	/**
     *
     * ������Ŀid������Ŀ����-�¼�����-����ռ���ܺ�
     * @param $projectId
     */
	function workRateCountNew($changeId,$parentId,$result){
    	$num=0;
		if(empty($result)){
			$result = array('count' => 100,'parentName' => null );
		}
		if($parentId == -1){
			$idRow=$this->findAll(array('parentId'=>$parentId,'changeId'=>$changeId), null, 'id,changeAction');
			$idRow=array_filter($idRow);
			if(!empty($idRow)){
				foreach($idRow as $value){
					$workRateNext = $this->workRateCountNew($changeId,$value['id'],$result);
					if( $workRateNext['count'] !=100 ){
						if(empty($workRateNext['parentName'])){
							$result['parentName']=$this->getParentName($value['id']);
						}
						else{
							$result['parentName']=$workRateNext['parentName'];
						}
						$result['count']=$workRateNext['count'];
						return $result;
					}
				}
					return $result;
			}
			else{
				return $result;
			}
		}else{
			$idWorkRate =  $this->getIdWorkRate($changeId,$parentId);
			if(!empty($idWorkRate)){ //�����û���¼�����
				foreach($idWorkRate as $v){
					if($v['changeAction'] == 'delete'){
						continue;
					}
					$num+=$v['workRate'];
				}
				if($num!=100){
					if(empty($result['parentName'])){
						$result['parentName'] = $this->getParentName($idWorkRate[0]['id']);
					}
					$result['count']=$num;
					return $result;
				}
				foreach($idWorkRate as $v){
					$workRateNext = $this->workRateCountNew($changeId,$v['id'],$result);
					if( $workRateNext['count'] !=100 ){
						if(empty($workRateNext['parentName'])){
						$result['parentName']= $this->getParentName($v['id']);
						}
						else{
							$result['parentName']=$workRateNext['parentName'];
						}
						$result['count']=$workRateNext['count'];
						return $result;
					}
				}
			}
			return $result;
		}
	}

	//��ȡ�ϼ���������
	function getParentName ($id){
		$parentName = $this-> findAll(array('id'=>$id), null, 'parentName', null);
		return $parentName[0]['parentName'];
	}

	//��ȡ�����ٷֱȺ�ID
	function getIdWorkRate ($changeId,$parentId){
		$idWorkRateRow = $this->findAll(array('parentId'=>$parentId,'changeId'=>$changeId), null, 'id,workRate,changeAction', null);
		return array_filter($idWorkRateRow);
	}
}