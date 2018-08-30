<?php

/**
 * @author show
 * @Date 2015年2月6日 10:37:03
 * @version 1.0
 * @description:项目关闭申请 Model层
 */
class model_engineering_close_esmclose extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_esm_project_close";
		$this->sql_map = "engineering/close/esmcloseSql.php";
		parent::__construct();
	}

	/**
	 * 获取项目信息
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
	 * 重写新增
	 * @param $object
	 * @return bool
	 */
	function add_d($object) {
		$esmCloseDetail = $object['esmclosedetail'];
		unset($object['esmclosedetail']);

		try {
			$this->start_d();

			// 新增
			$newId = parent::add_d($object, true);

			// 明细更新
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
	 * 重写编辑
	 * @param $object
	 * @return bool
	 */
	function edit_d($object) {
		$esmCloseDetail = $object['esmclosedetail'];
		unset($object['esmclosedetail']);

		try {
			$this->start_d();

			// 新增
			parent::edit_d($object, true);

			// 明细更新
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
	 * 审批完跳转
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
	 * 判断规则是否处理完毕
	 * @param $id
	 * @return bool
	 */
	function isAllDeal_d($id) {
		$obj = $this->find(array('id' => $id), null, 'projectId');
		// 明细更新
		$esmCloseDetailDao = new model_engineering_close_esmclosedetail();
		return $esmCloseDetailDao->isAllDeal_d($obj['projectId']);
	}

    /**
     * 查询项目是否存在正在处理的关闭申请
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