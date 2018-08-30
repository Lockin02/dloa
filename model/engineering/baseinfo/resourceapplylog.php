<?php
/**
 * @author show
 * @Date 2014��6��20��
 * @version 1.0
 * @description:��Ŀ�豸���������¼ Model��
 */
class model_engineering_baseinfo_resourceapplylog extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_esm_resource_log";
        $this->sql_map = "engineering/baseinfo/resourceapplylogSql.php";
        parent::__construct();
    }

    /**
     * ��Ӳ�����¼
     */
    function addLog_d($applyId, $operationType, $description = null) {
        $obj = array(
            'applyId' => $applyId, 'operationType' => $operationType, 'description' => $description,
            'userId' => $_SESSION['USER_ID'],
            'userName' => $_SESSION['USERNAME'],
            'operationTime' => date('Y-m-d H:i:s')
        );
        return $this->add_d($obj);
    }


    /**
     * ��¼��Ϣ
     */
    function leftCycle_d($objects){
    	//ֻ�������һ��������Ϣ
    	$objects = array_reverse($objects);//������
    	$index = 0;
    	foreach($objects as $key => $val){
    		if($val['operationType'] == '����'){
    			if($index != 0){
    				unset($objects[$key]);//ɾ�������������Ϣ
    			}
    			$index++;
    		}
    	}
		$objects = array_reverse($objects);//ɾ��������
        $html = ""; // ������Ϣ
        foreach($objects as $k => $v){
            $rowNo = $k + 1;
            $html .=<<<EOT
                <h2 class="headline-1">
                    <a class="anchor-1" name="$rowNo"></a>
                    <span class="headline-1-index">$rowNo</span>
                    <span class="headline-content">$v[operationType]</span>
                </h2>
                <div class="para">
                       <br>
                       �� $v[operationTime] ��  ��  �� $v[userName] �� �����ɹ�
                </div>
EOT;
        }
        return $html;
    }
}