<?php
/**
 * @author Show
 * @Date 2011��11��25�� ������ 9:38:59
 * @version 1.0
 */
class model_engineering_baseinfo_esmNotice extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_notice";
		$this->sql_map = "engineering/baseinfo/esmNoticeSql.php";
		parent::__construct ();
    }

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array('category');

    /**
     * @param $object
     * @return bool|null
     */
    function add_d($object) {
        try {
            $this->start_d();

            // �����ж�
            if (isset($_POST['fileuploadIds'])) {
                $object['hasFile'] = 1;
            }

            //�����ֵ�
            $object = $this->processDatadict($object);
            $id = parent::add_d($object, true);

            //���¸���������ϵ
            $this->updateObjWithFile($id);

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
    function edit_d($object) {
        try {
            $this->start_d();

            $fileDao = new model_file_uploadfile_management();
            $files = $fileDao->getFilesByObjId($object['id'], $this->tbl_name);
            $object['hasFile'] = empty($files) ? 0 : 1;

            //�����ֵ�
            $object = $this->processDatadict($object);
            parent::edit_d($object, true);

            $this->commit_d();
            return $object['id'];
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * ����֪ͨ�Ƿ��и���
     * @param $id
     * @return null
     */
    function updateHasFile_d($id) {
        $fileDao = new model_file_uploadfile_management();
        $files = $fileDao->getFilesByObjId($id, $this->tbl_name);

        return $this->edit_d(array(
            'id' => $id,
            'hasFile' => empty($files) ? 0 : 1
        ));
    }
}