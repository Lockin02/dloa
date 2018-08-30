<?php
/**
 * @description: ��Ŀ��ͨ�ƻ�Model
 * @date 2010-9-18 ����11:39:04
 * @author oyzx
 * @version V1.0
 */
class model_engineering_plan_rdplan extends model_treeNode {

	/**
	 * @desription ���캯��
	 * @date 2010-9-18 ����11:23:53
	 */
	function __construct() {
		$this->tbl_name = "oa_rd_project_plan";
		$this->sql_map = "engineering/plan/rdplanSql.php";
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------------------*
	 **************************************************************************************************/

	/**
	 *  �ƻ���Դʹ��͸��ͼ
	 */
	function planTable($rows){
//		print_r($rows);
		$str =<<<EOT
			<table class="main_table">
				<tr class="main_tr_header">
					<th>
						���
					</th>
					<th>
						�ƻ�����
					</th>
					<th>
						���ƹ�����
					</th>
					<th>
						��Ͷ�빤����
					</th>
					<th>
						�����
					</th>
				</tr>
EOT;
		if($rows){
			$i = 0;
			foreach($rows as $key=> $val){
				$i++;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$str.=<<<EOT
					<tr class="$classCss">
						<td>
							$i
						</td>
						<td>
							$val[planName]
						</td>
						<td>
							$val[appraiseWorkload]
						</td>
						<td>
							$val[putWorkload]
						</td>
						<td>
							$val[effortRate] %
						</td>
					</tr>
EOT;
			}
		}else{
			$str.='<tr><td colspan="20">�����������</td></tr>';
		}
		$str.='</table>';
		return $str;
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊ�ӿڷ���,����Ϊ����ģ��������-------------------------------- *
	 **************************************************************************************************/
	/**
	 *  ��дadd
	 */
	public function add_d($node) {
//		$node['status'] = 'JHJXZ';
		$node['realBeginDate'] = $node['planBeginDate'];
		$purviewDao = new model_engineering_plan_rdpurview();
		try {
			$this->start_d ();
			//���������߲������ҽڵ�id
			$node = $this->createNode ( $node );

			//����ƻ�
			$newId = $this->addBase_d ( $node, true );

			//����������
			$this->updateObjWithFile($newId);

			//���������
			$this->recEff($newId);

			//���Ȩ��
			$purviewDao->addGroup($node['projectId'],$newId);

//			$this->rollBack ();
			$this->commit_d ();
			return $newId;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * �½��ƻ�--ͨ��ģ�嵼��
	 */
	function addByImport($node){
		$node['realBeginDate'] = $node['planBeginDate'];
		$purviewDao = new model_engineering_plan_rdpurview();
		$templatePlanDao = new model_engineering_template_rdtplan();

		try {
			$this->start_d ();
			//���������߲������ҽڵ�id
			$node = $this->createNode ( $node );

			//����ƻ�
			$newId = $this->addBase_d ( $node, true );

			//����������
			$this->updateObjWithFile($newId);

			//���������
			$this->recEff($newId);

			//���Ȩ��
			$purviewDao->addGroup($node['projectId'],$newId);

			//����ģ������
			$templatePlanDao->templateImport($node['templateId'],$node['projectId'],$node['projectName'],$newId,$node['planName']);

//			$this->rollBack ();
			$this->commit_d ();
			return $newId;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * �½��ƻ��ǻ�ȡ�ϼ��ƻ�����Ŀ��Ϣ
	 */
	function rpGetProjectInfo($pjId,$pnId){
		if(empty($pnId)){
			$returnArr['0']['parentId'] = "-1";
			$returnArr['0']['parentName'] = "��̱��ƻ�";
		}else{
			$planArr = $this->rpArrById_d($pnId);
			$returnArr['0']['parentId'] = $planArr['0']['id'];
			$returnArr['0']['parentName'] = $planArr['0']['planName'];
		}
		$pjDao = new model_engineering_project_engineering();
		$pjArr = $pjDao->rpArrById_d($pjId);
		$returnArr['0']['projectId'] = $pjArr['0']['id'];
		$returnArr['0']['projectCode'] = $pjArr['0']['projectCode'];
		$returnArr['0']['projectName'] = $pjArr['0']['projectName'];
		return $returnArr;
	}

	/**
	 * �޸ļƻ�ʱ��ȡ��ؼƻ���Ϣ
	 */
	function getPlanInfoByEdit($pnId){
		$rows = $this->get_d($pnId);
		$rows = $this->filterField('�ֶ�����',$rows);
		if($rows['parentId'] == -1 ){
			$rows['parentName'] = "��̱��ƻ�";
		}else{
			$trows = $this->get_d($rows['parentId']);
			$rows['parentName'] = $trows['planName'];
		}
		return $rows;
	}

	/**
	 * @desription ͨ���ƻ�Id��ȡ����
	 * @param tags
	 * @return return_type
	 * @date 2010-9-25 ����07:02:03
	 */
	function rpArrById_d ($pnId) {
		$this->searchArr['id'] = $pnId;
		$rows = $this->listBySqlId('select_readAll');
		return $rows;
	}

	/**
	 * ��дedit_d����
	 */
	function edit_d($object){
		$object = $this->addUpdateInfo ( $object );
		return $this->updateById ( $object );
	}

	/**
	 * ����ƻ��Ƿ��ɾ��
	 */
	function canDelete($id){
		$this->searchArr['id'] = $id;
		$rows = $this->listBySqlId('canDelete');

		if($rows['0']['leaf']){
			$taskDao = new model_engineering_task_rdtask();
			$isClean = $taskDao->isCleanPlan_d($id);
			if(!$isClean){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}

	/**
	 * ɾ���ƻ�������д
	 */
	function deletes($ids) {
		try {
			$this->start_d ();
			$this->recEffDel($ids);
			parent::deletes ( $ids );
//			$this->rollBack ();
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * ����������
	 * �ɴ���4������
	 * ���� 1 ���ƻ�ID
	 * ���� 2 ���ı��ĵĹ�����
	 * ���� 3 ���ı�ǰ�Ĺ�����
	 * ���� 4 ���������� ��Ϊ�� add(Ĭ��)��ɾ del, �� edit
	 */
	function workloadCount( $id , $newLoad , $oldLoad = 0 , $type = 'add'){
		if($type = 'add'){
			$sql = "update oa_rd_project_plan set appraiseWorkload = appraiseWorkload + '$newLoad'  where id = '$id'";
		}elseif($type = 'del'){
			$sql = "update oa_rd_project_plan set appraiseWorkload = appraiseWorkload - '$newLoad'  where id = '$id'";
		}elseif($type = 'edit'){
			if($newLoad > $oldLoad){
				$addLoad = $newLoad - $oldLoad;
				$sql = "update oa_rd_project_plan set appraiseWorkload = appraiseWorkload + '$addLoad'  where id = '$id'";
			}elseif($newLoad < $oldLoad){
				$addLoad = $oldLoad - $newLoad;
				$sql = "update oa_rd_project_plan set appraiseWorkload = appraiseWorkload - '$newLoad'  where id = '$id'";
			}elseif($newLoad = $oldLoad){
				return ;
			}
		}
		try{
			$this->query($sql);
		}catch(exception $e){
			throw $e;
		}
	}

	/**
	 * �����Ͷ�빤����ͬʱ����ƻ��������
	 * �ƻ������ = ��������� / �������
	 */
	function addPutLoad($id,$load = 0,$taskeffect = 0){
		$taskDao = new model_engineering_task_rdtask();
		$rows = $taskDao->getGroupTKByPlanId($id);
		$sql = "update oa_rd_project_plan set putWorkload = putWorkload + '$load',effortRate = '". $rows[0]['effortRate'] ."',warpRate = '". $rows[0]['warpRate'] ."',realEndDate = '".date('Y-m-d')."'  where id = '$id'";
		try{
			$this->query($sql);
		}catch(exception $e){
			throw $e;
		}
	}

	/**
	 * ���/ɾ���ƻ�ʱ�����¼���ƻ��������--�ƻ�����Ӽƻ�ʱʹ��
	 * ��Ը��ڵ�������¼���
	 */
	function recEff($id){
		$planInfo = $this->find(array('id' => $id),null,'parentId');
		if(!isset($taskDao)){
			$taskDao = new model_engineering_task_rdtask();
		}
		if($planInfo['parentId'] == -1){
			return true;
		}
		else{
			$planRows = $this->getInfoByParentId($planInfo['parentId']);
			$rowsPTask = $taskDao->getInfoByPlanId($planInfo['parentId']);

			$effortRate = round(($planRows[0]['effortRate'] + $rowsPTask[0]['effortRate'])/($planRows[0]['planNum'] + $rowsPTask[0]['taskNum']),2);

			$updatesql = "update oa_rd_project_plan set effortRate = '$effortRate' where id = '".$planInfo['parentId']."'";
			try{
				$this->query($updatesql);
			}catch(exception $e){
				throw $e;
			}
			return $this->recEff($planInfo['parentId']);
		}
	}

	function recEffDel($id){
		$planInfo = $this->find(array('id' => $id),null,'parentId');
		if(!isset($taskDao)){
			$taskDao = new model_engineering_task_rdtask();
		}
		if($planInfo['parentId'] == -1){
			return true;
		}
		else{
			$planRows = $this->getInfoByParentId($planInfo['parentId']);
			$rowsPTask = $taskDao->getInfoByPlanId($planInfo['parentId']);

			$effortRate = round(($planRows[0]['effortRate'] + $rowsPTask[0]['effortRate'])/($planRows[0]['planNum'] + $rowsPTask[0]['taskNum'] - 1),2);

			$updatesql = "update oa_rd_project_plan set effortRate = '$effortRate' where id = '".$planInfo['parentId']."'";
			try{
				$this->query($updatesql);
			}catch(exception $e){
				throw $e;
			}
			return $this->recEff($planInfo['parentId']);
		}
	}

	/**
	 * ��������ļƻ�������ʣ������ϼ���
	 */
	function taskRecEff($id){
		$planInfo = $this->find(array('id' => $id),null,'parentId');
		if(!isset($taskDao)){
			$taskDao = new model_engineering_task_rdtask();
		}
		$planRows = $this->getInfoByParentId($id);
		$rowsPTask = $taskDao->getInfoByPlanId($id);

		$effortRate = round(($planRows[0]['effortRate'] + $rowsPTask[0]['effortRate'])/($planRows[0]['planNum'] + $rowsPTask[0]['taskNum']),2);

		$updatesql = "update oa_rd_project_plan set effortRate = '$effortRate' where id = '".$id."'";
		try{
			$this->query($updatesql);
		}catch(exception $e){
			throw $e;
		}
		if($planInfo['parentId'] == -1){
			return true;
		}
		else{
			return $this->recEff($planInfo['parentId']);
		}
	}

	/**
	 * ��ѯ���ڵ�Ϊ $parentId �ļƻ� ��������� �� ƫ���� �� �ƻ�����
	 */
	function getInfoByParentId($parentId){
		$sql = "select sum(p.effortRate) as effortRate,sum(p.warpRate) as warpRate,count(p.id) as planNum from oa_rd_project_plan p where p.parentId = '$parentId'";
		return $this->findSql($sql);
	}

	/**
	 * ��ȡͳ��
	 */
	function getTheRows($projectId){
		$this->searchArr['projectId'] = $projectId;
		$this->asc = false;
		return $this->listBySqlId('easy_list');
	}

	/**
	 * �ƻ���Դʹ��͸��ͼ-ͼ��
	 */
	function planCharts($rows){
		if($rows){
			$i = 0;
			$outArr = array ();
			foreach($rows as $val){
				$outArr[$i][1] = $val['planName'];
				$outArr[$i][2] = $val['appraiseWorkload'];
				$outArr[$i][3] = $val['putWorkload'];
				$i++;
			}
		}
		$fusionCharts = new model_common_fusionCharts();
//		return $fusionCharts->mixCharts($outArr,'�ƻ���Դʹ��͸��ͼ',null,'h','450','300',array('���ƹ�����','��Ͷ�빤����'));

		$chartConf = array('exportFileName'=>"�ƻ���Դʹ��͸��ͼ",
				'caption'=>"�ƻ���Դʹ��͸��ͼ",
				'exportAtClient'=>0,                    //0: �����������У� 1�� �ͻ�������
                'exportAction'=>"download",              //����Ƿ��������У�֧�����������or����������
                '$numberSuffix' =>"h",                   //��׺
                'numVisiblePlot'=>5,                     //������������
		);
		return $fusionCharts->showBarChart($outArr,"MSColumnLine3D.swf",$chartConf );
	}

	/**
	 * �ƻ�ʵ�ʽ�չ͸��ͼ
	 */
	function planSchedule($rows){
		if($rows){
			$i = 0;
			$outArr = array ();
			foreach($rows as $val){
				$outArr[$i][1] = $val['planName'];
				$outArr[$i][2] = $val['effortRate'];
				$i++;
			}
		}

		$fusionCharts = new model_common_fusionCharts();

		$chartConf = array('exportFileName'=>"�ƻ�ʵ�ʽ�չ͸��ͼ",
		'caption'=>"�ƻ�ʵ�ʽ�չ͸��ͼ",
		'exportAtClient'=>0,                    //0: �����������У� 1�� �ͻ�������
        'exportAction'=>"download",              //����Ƿ��������У�֧�����������or����������
        '$numberSuffix' =>"%",                   //��׺
		);
		return $fusionCharts->showCharts($outArr,"Column2D.swf",$chartConf );
	}

	/**
	 * �����ƻ�
	 */
	function issue($id){
		$object = array( 'id' => $id,'status' => 'JHJXZ','realBeginDate' => date('Y-m-d'));
		return $this->edit_d($object);
	}

	/**
	 * �رս���
	 */
	function closeAndBalance($id,$planEndDate){
		$WarpRate = $this->planWarpRate($planEndDate);
		$object = array( 'id' => $id,'status' => 'JHWC','realEndDate' => date('Y-m-d'),'warpRate' => $WarpRate);
		return $this->edit_d($object);
	}

	/**
	 * �ƻ�ƫ���ʽ���
	 */
	function planWarpRate($planEndDate,$realEndDate = null){
		if(empty($realEndDate)){
			$realEndDate = date('Y-m-d');
		}
		$ri = ($realEndDate - $planEndDate) / $planEndDate  * 100 ;
	}
}
?>
