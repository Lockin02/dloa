<?php

/**
 * ���´�Model����
 * Created on 2010-11-29
 * author can
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_engineering_officeinfo_officeinfo extends model_base
{
    function __construct()
    {
        $this->tbl_name = "oa_esm_office_baseinfo";
        $this->sql_map = "engineering/officeinfo/officeinfoSql.php";
        parent::__construct();
    }

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array('productLine', 'module');

    /*=============================ҵ����=================================*/

    /**
     * �������´���Ϣ  �������´���������η�Χ
     * @param $object
     * @param bool $isAddInfo
     * @return bool|null
     */
    function add_d($object, $isAddInfo = false)
    {
        try {
            $this->start_d();
            //�����ֵ�
            $object = $this->processDatadict($object);
            $id = parent::add_d($object, true);

            /*start---------------������η�Χ------------------------*/
            /*�ָ����η�Χ���ƺ�ID*/
            $rangeDao = new model_engineering_officeinfo_range();
            $rangeArr = explode(",", $object['rangeName']);
            $rangeCodeArr = explode(",", $object['rangeCode']);
            $rangeIdArr = explode(",", $object['rangeId']);
            $tempRangeInfo['officeId'] = $id;
            $tempRangeInfo['officeName'] = $object['officeName'];
            for ($j = 0; $j < count($rangeArr); $j++) {
                $tempRangeInfo['proName'] = $rangeArr[$j];
                $tempRangeInfo['proCode'] = $rangeCodeArr[$j];
                $tempRangeInfo['proId'] = $rangeIdArr[$j] ? $rangeIdArr[$j] : 0;
                $tempRangeInfo['mainManager'] = $object['mainManager'];
                $tempRangeInfo['mainManagerId'] = $object['mainManagerId'];
                $tempRangeInfo['businessBelong'] = $object['businessBelong'];
                $tempRangeInfo['businessBelongName'] = $object['businessBelongName'];
                $tempRangeInfo['formBelong'] = $object['formBelong'];
                $tempRangeInfo['formBelongName'] = $object['formBelongName'];
                $tempRangeInfo['head'] = $object['head'];
                $tempRangeInfo['headId'] = $object['headId'];
                $tempRangeInfo['assistant'] = $object['assistant'];
                $tempRangeInfo['assistantId'] = $object['assistantId'];
                $tempRangeInfo['productLine'] = $object['productLine'];
                $tempRangeInfo['productLineName'] = $object['productLineName'];
                $tempRangeInfo['state'] = $object['state'];
                $rangeDao->add_d($tempRangeInfo);
            }
            /*end---------------������η�Χ-------------------------*/

            //����ʡ�ݸ����˵���Χ����
            $rangeDao->updateEsmManager_d($id, $object['businessBelong'], $object['productLine']);

            $this->commit_d();
            return $id;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * �༭���´���Ϣ �༭���´���������η�Χ
     * @param $object
     * @return null
     */
    function edit_d($object)
    {
        try {
            $this->start_d();
            //�����ֵ�
            $object = $this->processDatadict($object);
            parent::edit_d($object, true);

            /*start---------------�༭���η�Χ------------------------*/
            $rangeDao = new model_engineering_officeinfo_range();
            //ɾ�����´���������η�Χ,���±���
            $range = array("officeId" => $object['id']); //���η�Χ
            $rangeDao->delete($range);

            $rangeArr = explode(",", $object['rangeName']);
            $rangeCodeArr = explode(",", $object['rangeCode']);
            $rangeIdArr = explode(",", $object['rangeId']);

            $tempRangeInfo['officeId'] = $object['id'];
            $tempRangeInfo['officeName'] = $object['officeName'];

            for ($j = 0; $j < count($rangeArr); $j++) {
                $tempRangeInfo['proName'] = $rangeArr[$j];
                $tempRangeInfo['proCode'] = $rangeCodeArr[$j];
                $tempRangeInfo['proId'] = $rangeIdArr[$j] ? $rangeIdArr[$j] : 0;
                $tempRangeInfo['mainManager'] = $object['mainManager'];
                $tempRangeInfo['mainManagerId'] = $object['mainManagerId'];
                $tempRangeInfo['businessBelong'] = $object['businessBelong'];
                $tempRangeInfo['businessBelongName'] = $object['businessBelongName'];
                $tempRangeInfo['formBelong'] = $object['formBelong'];
                $tempRangeInfo['formBelongName'] = $object['formBelongName'];
                $tempRangeInfo['head'] = $object['head'];
                $tempRangeInfo['headId'] = $object['headId'];
                $tempRangeInfo['assistant'] = $object['assistant'];
                $tempRangeInfo['assistantId'] = $object['assistantId'];
                $tempRangeInfo['productLine'] = $object['productLine'];
                $tempRangeInfo['productLineName'] = $object['productLineName'];
                $tempRangeInfo['state'] = $object['state'];
                $rangeDao->add_d($tempRangeInfo);
            }
            /*end---------------�༭���η�Χ-------------------------*/

            //����ʡ�ݸ����˵���Χ����
            $rangeDao->updateEsmManager_d($object['id'], $object['businessBelong'], $object['productLine']);

            $this->commit_d();
            return $object['id'];
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * ��ȡ���´������Ӧ��ʡ������
     * @param $userId
     * @return string
     */
    function getProvinceNames_d($userId)
    {
        $sql = "select group_concat(c.proName) as proName,group_concat(c.proCode) as proCode
            from (
                select r.officeId,r.proName,r.proCode from oa_esm_office_range r
                where r.officeId in (
                    select c.id from oa_esm_office_baseinfo c left join oa_esm_office_managerinfo m
                    on c.id = m.officeId where m.userCode = '$userId'
                    ) group by proCode
                ) c";
        $rs = $this->_db->getArray($sql);
        if (is_array($rs)) {
            return $rs[0]['proName'];
        } else {
            return '';
        }
    }

    /**
     * ��ȡ���´���Ӧʡ�ݵ�����
     * @param $ids
     * @return string
     */
    function getProNamesByIds_d($ids)
    {
        $sql = "select group_concat( c.proName ) as proName
            from (select proName from oa_esm_office_range where officeId in($ids)) c";
        $rs = $this->_db->getArray($sql);
        if (is_array($rs)) {
            return $rs[0]['proName'];
        } else {
            return '';
        }
    }

    /**
     * ��ȡ���´�id
     * @param null $userId
     * @return string
     */
    function getOfficeIds_d($userId = null)
    {
        $userId = empty($userId) ? $_SESSION['USER_ID'] : $userId;
        $this->searchArr = array('findMainAndHead' => $userId);
        $rs = $this->list_d();
        if (is_array($rs)) {
            $idArr = array();
            foreach ($rs as $val) {
                array_push($idArr, $val['id']);
            }
            return implode($idArr, ',');
        } else {
            return '';
        }
    }

    /**
     * �жϰ��´��Ƿ���Ŀ����
     * @param $id
     * @return bool|mixed
     */
    function isProjected_d($id)
    {
        $projectDao = new model_engineering_project_esmproject();
        return $projectDao->find(array('officeId' => $id));
    }

    /**
     * ���ݰ��´�����,������,������˾��ȡid
     * @param $name
     * @param $productLineName
     * @param $businessBelongName
     * @return mixed
     */
    function getIdByName($name, $productLineName, $businessBelongName)
    {
        $rs = $this->find(
            array('officeName' => $name, 'productLineName' => $productLineName,
                'businessBelongName' => $businessBelongName), null, 'id');
        return $rs['id'];
    }

    /**
     * ���ݰ��´����ƻ�ȡid,�������ź͹�������id
     * @param $name
     * @return bool|mixed
     */
    function getIdByOfficeName($name)
    {
        return $this->find(array('officeName' => $name), null, 'id,feeDeptName,feeDeptId,businessBelong,businessBelongName,productLine,productLineName');
    }

    /**
     * ��ʼ�����´���Ϣ
     * @return bool
     */
    function initOffice_d()
    {
        $rs = $this->findAll();
        if ($rs) {
            foreach ($rs as $val) {
                $codeArr = explode(',', $val['rangeCode']);
                $codeStr = "'" . implode($codeArr, "','") . "'";

                $sql = "update " . $this->tbl_name .
                    " set rangeId = (select GROUP_CONCAT(cast(id as char(100))) from oa_system_province_info where provinceCode in (" . $codeStr . ")) where id = " . $val['id'];
                $this->query($sql);
            }
        }
        return false;
    }

    /**
     * ��ȡ��Ҫ���µ���Ϣ
     * @param $provinceId ʡ��id
     * @param $businessBelong ������˾
     * @param $productLine ��Ʒ��
     * @return mix
     */
    function getNeedUpdateManager_d($provinceId, $businessBelong, $productLine)
    {
        $this->searchArr = array('rangeIdFind' => $provinceId, 'businessBelong' => $businessBelong, 'productLine' => $productLine);
        $rs = $this->list_d();
        if ($rs) {
            return $rs;
        } else {
            return true;
        }
    }

    /**
     * ��ȡ����ͼ
     * @param $ids
     * @return array|bool
     */
    function getOfficeMap_d($ids)
    {
        $this->searchArr = array('ids' => $ids);
        $rs = $this->list_d();
        if (empty($rs)) {
            return false;
        } else {
            $map = array();
            foreach ($rs as $v) {
                $map[$v['id']] = $v;
            }
            return $map;
        }
    }
}