<?php
/**
 * @author Administrator
 * @Date 2012-10-25 15:02:55
 * @version 1.0
 * @description:设备基本信息 Model层
 */
 class model_equipment_budget_budgetbaseinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_equ_budget_baseinfo";
		$this->sql_map = "equipment/budget/budgetbaseinfoSql.php";
		parent::__construct ();
	}
  /**
   * 预算表选择获取
   */
   function budgetChooseiframe_d($budgetTypeId){
   	 if(!empty($budgetTypeId)){
   	 	$sql = "select * from oa_equ_budget_baseinfo where budgetTypeId = ".$budgetTypeId." and useStatus = '1'";
       $arr = $this->_db->getArray($sql);
      $n = 0;
      $num = count($arr)%5;
      foreach($arr as $k=>$v){
        $equName = $v['equName'];
        $i = $k+1;
       $equName1 = $arr[$n]['equName'];
        $equId1 = $arr[$n]['id'];
       $equName2 = $arr[$n+1]['equName'];
        $equId2 = $arr[$n+1]['id'];
       $equName3 = $arr[$n+2]['equName'];
        $equId3 = $arr[$n+2]['id'];
       $equName4 = $arr[$n+3]['equName'];
        $equId4 = $arr[$n+3]['id'];
       $equName5 = $arr[$n+4]['equName'];
        $equId5 = $arr[$n+4]['id'];
       if($i%5 == "0" && $i != "1"){
           $str .= <<<EOT
               <tr align="center">
                 <td><a style="cursor:pointer;" onclick="chooseEqu($equId1)">$equName1</a></td>
                 <td><a style="cursor:pointer;" onclick="chooseEqu($equId2)">$equName2</a></td>
                 <td><a style="cursor:pointer;" onclick="chooseEqu($equId3)">$equName3</a></td>
                 <td><a style="cursor:pointer;" onclick="chooseEqu($equId4)">$equName4</a></td>
                 <td><a style="cursor:pointer;" onclick="chooseEqu($equId5)">$equName5</a></td>
               <tr>
EOT;
         $n += 5 ;
       }else{
       	  $str .= "";
       }
      }
      if($num != 0){
        $arrT = array_reverse($arr);
         $str .= <<<EOT
               <tr align="center">
                 <td><a style="cursor:pointer;" onclick="chooseEqu($equId1)">$equName1</a></td>
                 <td><a style="cursor:pointer;" onclick="chooseEqu($equId2)">$equName2</a></td>
                 <td><a style="cursor:pointer;" onclick="chooseEqu($equId3)">$equName3</a></td>
                 <td><a style="cursor:pointer;" onclick="chooseEqu($equId4)">$equName4</a></td>
                 <td><a style="cursor:pointer;" onclick="chooseEqu($equId5)">$equName5</a></td>
               <tr>
EOT;
      }
   	 }else{
   	 	$str = "";
   	 }
      return $str;
   }

   /**
    * 是否开启设备
    */
   function ajaxUseStatus_d($id,$flag){
   	    try {
			$sql = "update oa_equ_budget_baseinfo set useStatus = '".$flag."' where id = '".$id."'";
			$this->_db->query($sql);

			if($flag == '0'){
				$unflag = '1';
			}else{
				$unflag = '0';
			}
            $oldObj = array("useStatus" => $unflag,"id"=>$id);
			$obj = array("useStatus" => $flag,"id"=>$id);


			//更新操作日志
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $obj );

			return true;
		} catch (Exception $e) {
			throw $e;
			return false;
		}
   }

   /**
    * ajax 清空数据
    */
   function ajaxEmptyData_d(){
        try {
			$emptysql = "drop table if exists oa_equ_budget_baseinfo_delTemp;";
			$this->_db->query($emptysql);
			$createTempsql = "create table oa_equ_budget_baseinfo_delTemp select * from oa_equ_budget_baseinfo; ";
			$this->_db->query($createTempsql);
			$deletesql = "delete from oa_equ_budget_baseinfo";
			$this->_db->query($deletesql);

			return true;
		} catch (Exception $e) {
			throw $e;
			return false;
		}
   }


	/**
	 * 添加对象
	 */
	function add_d($obj) {
		try {

			$id = parent::add_d ( $obj, true );

			//更新操作日志
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->addObjLog ( $this->tbl_name, $id, $obj );

			$this->commit_d ();
			return $id;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}

	}
	/**
	 * 修改
	 */
	function edit_d($obj) {
		try {
			$this->start_d ();
			$oldObj = $this->get_d ( $obj ['id'] );
			parent::edit_d ( $obj, true );

			//更新操作日志
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $obj );

			$this->commit_d ();
			return $obj;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}

	}
	/**
	 * 根据类型名称 查找类型Id
	 */
	function getbudTypeIdByName($budgetTypeName){
         $sql = "select id from oa_equ_budget_type where budgetType = '".$budgetTypeName."'";
         $arr = $this->_db->getArray($sql);
         return $arr[0]['id'];
	}
	/*
	 * 转换时间戳
	 */
	function transitionTime($timestamp){
		$time = "";
		if(!empty($timestamp)){
			if(mktime ( 0, 0, 0, 1, $timestamp - 1, 1900 )> '2000-01-01'){
			$wirteDate = mktime(0, 0, 0, 1, $timestamp - 1, 1900);
		    $time = date("Y-m-d", $wirteDate);
			}else{
				$time=$timestamp;
			}

		}
		 return $time;
	}

}
?>