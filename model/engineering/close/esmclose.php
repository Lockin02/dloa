<?php

/**
 * @author show
 * @Date 2015��2��6�� 10:37:03
 * @version 1.0
 * @description:��Ŀ�ر����� Model��
 */
class model_engineering_close_esmclose extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_esm_project_close";
		$this->sql_map = "engineering/close/esmcloseSql.php";
		parent::__construct();
	}

	/**
	 * ��ȡ��Ŀ��Ϣ
	 * @param $projectId
	 * @return bool|mixed
	 */
	function getProjectInfo_d($projectId) {
		$projectDao = new model_engineering_project_esmproject();
		$projectInfo = $projectDao->find(array('id' => $projectId), null, 'projectCode,projectName');
		$projectInfo['projectId'] = $projectId;
		return $projectInfo;
	}

	/**
	 * ��д����
	 * @param $object
	 * @return bool
	 */
	function add_d($object) {
		$esmCloseDetail = $object['esmclosedetail'];
		unset($object['esmclosedetail']);

		try {
			$this->start_d();

			// ����
			$newId = parent::add_d($object, true);

			// ��ϸ����
			$esmCloseDetailDao = new model_engineering_close_esmclosedetail();
			$esmCloseDetail = util_arrayUtil::setArrayFn(array('projectId' => $object['projectId']), $esmCloseDetail);
			$esmCloseDetailDao->saveDelBatch($esmCloseDetail);

			$this->commit_d();
			return $newId;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��д�༭
	 * @param $object
	 * @return bool
	 */
	function edit_d($object) {
		$esmCloseDetail = $object['esmclosedetail'];
		unset($object['esmclosedetail']);

		try {
			$this->start_d();

			// ����
			parent::edit_d($object, true);

			// ��ϸ����
			$esmCloseDetailDao = new model_engineering_close_esmclosedetail();
			$esmCloseDetail = util_arrayUtil::setArrayFn(array('projectId' => $object['projectId']), $esmCloseDetail);
			$esmCloseDetailDao->saveDelBatch($esmCloseDetail);

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��������ת
	 * @param $spid
	 * @return bool
	 */
	function dealAfterAudit_d($spid) {
		$otherDatas = new model_common_otherdatas();
		$flowInfo = $otherDatas->getStepInfo($spid);
		$objId = $flowInfo['objId'];
		$object = $this->get_d($objId);
		if ($object['ExaStatus'] == AUDITED) {
			$projectDao = new model_engineering_project_esmproject();
			$projectInfo = $projectDao->find(array('id' => $object['projectId']), null,
				'id,projectCode,contractId,contractType');
			$projectInfo['status'] = 'GCXMZT03';
            $projectInfo['ExaStatus'] = AUDITED;
			$projectInfo['closeReason'] = $object['content'];
			$projectDao->close_d($projectInfo);
		}
		return true;
	}

	/**
	 * �жϹ����Ƿ������
	 * @param $id
	 * @return bool
	 */
	function isAllDeal_d($id) {
		$obj = $this->find(array('id' => $id), null, 'projectId');
		// ��ϸ����
		$esmCloseDetailDao = new model_engineering_close_esmclosedetail();
		return $esmCloseDetailDao->isAllDeal_d($obj['projectId']);
	}

    /**
     * ��ѯ��Ŀ�Ƿ�������ڴ���Ĺر�����
     * @param $projectId
     * @return bool
     */
    function hasProcessingApply_d($projectId) {
		$obj = $this->find(array('projectId' => $projectId, 'ExaStatus' => AUDITING), null, 'id');
        if ($obj) {
            return true;
        } else {
            return false;
        }
    }
}