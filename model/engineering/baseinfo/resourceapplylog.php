<?php
/**
 * @author show
 * @Date 2014年6月20日
 * @version 1.0
 * @description:项目设备申请操作记录 Model层
 */
class model_engineering_baseinfo_resourceapplylog extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_esm_resource_log";
        $this->sql_map = "engineering/baseinfo/resourceapplylogSql.php";
        parent::__construct();
    }

    /**
     * 添加操作记录
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
     * 记录信息
     */
    function leftCycle_d($objects){
    	//只保留最后一条审批信息
    	$objects = array_reverse($objects);//反序处理
    	$index = 0;
    	foreach($objects as $key => $val){
    		if($val['operationType'] == '审批'){
    			if($index != 0){
    				unset($objects[$key]);//删除多余的审批信息
    			}
    			$index++;
    		}
    	}
		$objects = array_reverse($objects);//删除后反序处理
        $html = ""; // 返回信息
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
                       【 $v[operationTime] 】  由  【 $v[userName] 】 操作成功
                </div>
EOT;
        }
        return $html;
    }
}