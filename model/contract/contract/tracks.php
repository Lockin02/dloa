<?php
/**
 * @author HaoJin
 * @Date 2016��12��8�� 11:35:28
 * @version 1.0
 * @description:��ִͬ�й켣�±� Model��
 */

class model_contract_contract_tracks extends model_base
{
    function __construct()
    {
        $this->tbl_name = "oa_contract_schdl_record";
        $this->sql_map = "contract/contract/tracksSql.php";
        parent :: __construct();
    }

    /**
     * ��Ӻ�ͬ�������ݼ�¼
     * @param $obj
     * @return string
     */
    function addRecord($obj){
        $object = array();
        $object['contractId'] = isset($obj['contractId'])? $obj['contractId'] : '';//��ͬID
        $object['contractCode'] = isset($obj['contractCode'])? $obj['contractCode'] : '';//��ͬ���
        $object['exePortion'] = isset($obj['exePortion'])? $obj['exePortion'] : '';//��ִͬ�н���
        $object['schedule'] = isset($obj['schedule'])? $obj['schedule'] : '';//��ͬ����
        $object['modelName'] = isset($obj['modelName'])? $obj['modelName'] : '';//�ڵ�ģ����
        $object['operationName'] = isset($obj['operationName'])? $obj['operationName'] : '';//�ڵ������
        $object['result'] = isset($obj['result'])? $obj['result'] : '';//�ڵ�����������
        $object['recordTime'] = isset($obj['recordTime'])? $obj['recordTime'] : '';//�ڵ��¼����
        $object['expand1'] = isset($obj['expand1'])? $obj['expand1'] : '';//��չ�ֶ�1
        $object['expand2'] = isset($obj['expand2'])? $obj['expand2'] : '';//��չ�ֶ�2
        $object['expand3'] = isset($obj['expand3'])? $obj['expand3'] : '';//��չ�ֶ�3
        $object['createTime'] = isset($obj['createTime'])? $obj['createTime'] : time();//��¼���ʱ��
        $object['createId'] = isset($obj['createId'])? $obj['createId'] : $_SESSION['USER_ID'];//��¼�����û�ID
        $object['remarks'] = isset($obj['remarks'])? $obj['remarks'] : '';//��ע

        //����������Ϣ
        $newId = parent :: add_d($object);
        return $newId;
    }

    /**
     * ��Ӻ�ͬ�������ݼ�¼
     * @param $contractId ��ͬID
     * @param $modelName ����ִ�а����
     * ����ʼִ�У�'contractBegin',��Ʊ��¼��'invoiceMoney',
     *      �����¼��'incomeMoney',ǩ��ֽ�ʺ�ͬ��'contractSignIn',��ִͬ�н�����'contractComplete',��ͬ�رգ�'contractClose'��
     * @param string $searchType ��ѯ���� ��max:��ͬ��¼�е�����һ����match:�������������м�¼��
     * @param string $matchIDParam ƥ��ID���ֶ��� ��ѯ����Ϊmatchʱʹ��
     * @param $advCondition
     * @return array
     */
    function getRecord($contractId,$modelName,$searchType = 'max',$matchIDParam = '',$advCondition = ''){
        $sql1 =<<<EOT
        SELECT
            id,contractId,exePortion,operationName,expand1,expand2,expand3
        FROM
            oa_contract_schdl_record
        WHERE
            contractId = '$contractId'
        AND modelName = '$modelName'
EOT;
        // ���ݲ�ѯ������ѡ���ѯ���
        $sql = '';
        switch($searchType){
            case 'max':
                $sql = $sql1.$advCondition." ORDER BY id DESC LIMIT 1;";
                break;
            case 'match':
                $sql = $sql1.$advCondition.';';
                break;
        }

        // ��ѯ����
        $searchData = $this->_db->getArray($sql);

        // ��������
        $backArr['msg'] = '';
        $backArr['data'] = array();
//        $backArr['sql'] = $sql;
        if(!empty($searchData)){
            $backArr['msg'] = 'ok';
            if($matchIDParam != ''){
                $backArr['data']['detail'] = array();
                foreach($searchData as $v){
                    $backArr['data']['detail'][$v[$matchIDParam]] = $v;
                }
            }else{
                $backArr['data'] = $searchData[0];
            }
        }
        return $backArr;
    }
}