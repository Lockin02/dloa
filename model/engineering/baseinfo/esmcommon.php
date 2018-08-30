<?php
/*
 * Created on 2012-7-30
 * Created by Show
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_engineering_baseinfo_esmcommon extends model_base {

    function __construct() {
//        $this->tbl_name = "";
//        $this->sql_map = "engineering/baseinfo/epersonSql.php";
        parent :: __construct();
    }

    /**
     * 显示配置
     */
    function toViewConfig_d(){
        include (WEB_TOR."model/common/commonConfig.php");
        echo ' =================项目费用部分================== <br/>';
        //费用模板类型
        $COSTMODEL = isset($COSTMODEL) ? $COSTMODEL : '无此相关数组';
    	echo '当前费用模板为: ',$COSTMODEL['name'] , ", id :" , $COSTMODEL['id'] , "<br/>";
        //测试卡费对应的费用类型
        $CARDCOSTTYPE = isset($CARDCOSTTYPE) ? $CARDCOSTTYPE : '无此相关数组';
        echo '当前测试卡费对应的费用类型为: ',$CARDCOSTTYPE['name'] , ", id :" , $CARDCOSTTYPE['id'] , "<br/>";
        //人员租赁对应的费用类型
        $TEMPPERSONCOSTTYPE = isset($TEMPPERSONCOSTTYPE) ? $TEMPPERSONCOSTTYPE : '无此相关数组';
        echo '当前人员租赁对应的费用类型为: ',$TEMPPERSONCOSTTYPE['name'] , ", id :" , $TEMPPERSONCOSTTYPE['id'] , "<br/>";
        //租车费对应的费用类型
        $CARTRAVELFEECOSTTYPE = isset($CARTRAVELFEECOSTTYPE) ? $CARTRAVELFEECOSTTYPE : '无此相关数组';
        echo '当前租车费对应的费用类型为: ',$CARTRAVELFEECOSTTYPE['name'] , ", id :" , $CARTRAVELFEECOSTTYPE['id'] , "<br/>";
        //油费对应的费用类型
        $CARFUELFEECOSTTYPE = isset($CARFUELFEECOSTTYPE) ? $CARFUELFEECOSTTYPE : '无此相关数组';
        echo '当前油费对应的费用类型为: ',$CARFUELFEECOSTTYPE['name'] , ", id :" , $CARFUELFEECOSTTYPE['id'] , "<br/>";
        //路桥对应的费用类型
        $CARROADFEECOSTTYPE = isset($CARROADFEECOSTTYPE) ? $CARROADFEECOSTTYPE : '无此相关数组';
        echo '当前路桥对应的费用类型为: ',$CARROADFEECOSTTYPE['name'] , ", id :" , $CARROADFEECOSTTYPE['id'] , "<br/>";
        //停车费的费用类型
        $CARPARKINGFEECOSTTYPE = isset($CARPARKINGFEECOSTTYPE) ? $CARPARKINGFEECOSTTYPE : '无此相关数组';
        echo '当前路桥对应的费用类型为: ',$CARPARKINGFEECOSTTYPE['name'] , ", id :" , $CARPARKINGFEECOSTTYPE['id'] , "<br/>";

        echo '<br/> =================工程关联人资信息================== <br/>';
        //是否生成人员项目经理
        $ISADDPERSONPROJECTRECORD = isset($ISADDPERSONPROJECTRECORD) && $ISADDPERSONPROJECTRECORD == 1 ? $ISADDPERSONPROJECTRECORD : 0;
        $thisResult = $ISADDPERSONPROJECTRECORD == 1? '是' : '否';
        echo '项目关闭时生成人员项目经历 : ', $thisResult ," <span class='blue'>[功能未完成]</span> <br/>";
    }
}
?>
