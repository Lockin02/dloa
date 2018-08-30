<?php
/**
 * @author HaoJin
 * @Date 2016年12月8日 11:35:28
 * @version 1.0
 * @description:合同执行轨迹新表 控制层
 */
class controller_contract_contract_tracks extends controller_base_action
{
    function __construct()
    {
        $this->objName = "tracks";
        $this->objPath = "contract_contract";
        //		$this->lang="contract";//语言包模块
        parent :: __construct();
    }

}