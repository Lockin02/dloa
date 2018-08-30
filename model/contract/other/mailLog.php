<?php
/**
 * Created by PhpStorm.
 * User: Kuangzw
 * Date: 2018/1/9
 * Time: 14:37
 */
class model_contract_other_mailLog extends model_base
{
    function __construct() {
        $this->tbl_name = "oa_sale_other_mailLog";
        $this->sql_map = "contract/other/mailLogSql.php";
        parent::__construct();
    }

    /**
     * 返回邮件发送记录
     * @param $contractId
     * @return mixed
     */
    function getMap_d($contractId) {
        $data = $this->find(array('contractId' => $contractId));

        if ($data) {
            return json_decode($data['details'], true);
        } else {
            false;
        }
    }

    /**
     * @param $contractId
     * @param $map
     */
    function setMap_d($contractId, $map) {
        $data = $this->find(array('contractId' => $contractId));

        if ($data) {
            $data['details'] = json_encode($map);
            $this->edit_d($data);
        } else {
            $this->add_d(array(
                'contractId' => $contractId,
                'details' => json_encode($map)
            ));
        }
    }
}