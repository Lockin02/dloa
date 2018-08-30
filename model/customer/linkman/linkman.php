<?php

/**
 * 联系人model层类
 */
class model_customer_linkman_linkman extends model_base {

	function __construct() {
		$this->tbl_name = "oa_customer_linkman";
		$this->sql_map = "customer/linkman/linkmanSql.php";
		parent::__construct ();
	}
	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------
	 *************************************************************************************************/

	function showlist($rows, $showpage) {
		$str = "";//返回的模板字符串
		if ($rows) {
			$i = $n = 0; //列表记录序号
			$datadictDao = new model_system_datadict_datadict ();
			foreach ( $rows as $key => $val ) {
				$i ++;
				$n = ($i%2)+1;
				$str .= <<<EOT
						<tr id="tr_$val[id]" class="TableLine$n">
							<td><input type="checkbox" name="datacb"  value="$val[id]" onClick="checkOne();"></td>
							<td align="center">$i</td>
							<td align="center">$val[customerName]</td>
							<td align="center">$val[linkmanName]</td>
							<td align="center">$val[phone]</td>
							<td align="center">$val[mobile]</td>
							<td align="center">$val[MSN]</td>
							<td align="center">$val[QQ]</td>
							<td align="center">$val[email]</td>
							<td align="center" id="m_$val[id] ">
								<a href="?model=customer_linkman_linkman&action=readInfo&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="查看< $val[linkmanName] >信息" class="thickbox">查看</a>
								<a href="?model=customer_linkman_linkman&action=init&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="修改< $val[linkmanName] >信息" class="thickbox">修改</a>
							</td>
						</tr>
EOT;
			}
		} else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str . '<tr><td colspan="50" style="text-align:center;">' . $showpage->show ( 6 ) . '</td></tr>';
	}

	/**
	 * 在客户信息中显示联系人列表
	 */
	function showlistInCustomer($rows, $showpage) {
		$str = "";
		if ($rows) {
			$i = $n = 0;
			foreach ($rows as $key => $val) {
				$i++;
				$n = ($i%2)+1;
				$str .=<<<EOT
					<tr id="tr_$val[id]" class="TableLine$n">
						<td align="center">$i</td>
							<td align="center">$val[customerName]</td>
							<td align="center">$val[linkmanName]</td>
							<td align="center">$val[phone]</td>
							<td align="center">$val[mobile]</td>
							<td align="center">$val[email]</td>
						<td>
							<p>
								<a href="?model=customer_linkman_linkman&action=readInfoInS&id=$val[id]" title="查看< $val[linkmanName] >信息">查看</a>
							</p>
					    </td>
					</tr>
EOT;
			}

		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str . '<tr><td colspan="10" style="text-align:center;">' . $showpage->show(6) . '</td></tr>';
		//return $str;
	}

	/***************************************************************************************************
	 * ------------------------------以下为公用接口方法,可以为其他模块所调用------------------------------
	 *************************************************************************************************/

	/**
	 * 解析性别
	 */
	function showSexInRead($value){
		if ($value == 1) {
			$value = "未知";
		}else if ($value == 2) {
			$value = "男";
		} else if($value== 3){
			$value = "女";
		}
		return $value;
	}

	function showSexInEdit($value){
		$value1 = $value2 = $value3 = "";
		if ($value == 1) {
			$value1 = "selected";
		}else if ($value == 2) {
			$value2 = "selected";
		} else if($value== 3){
			$value3 = "selected";
		}
		$str=<<<EOT
			<option value="1" $value1>未知</option>
			<option value="2" $value2>男</option>
			<option value="3" $value3>女</option>
EOT;
		return $value;
	}

	/**
	 * 根据主键获取对象
	 */
	function get_d($id,$seltype = "read") {
		$this->searchArr  = array ("id" => $id );
		$arr = $this->listBySqlId ();
		if($seltype == "read"){
			$arr[0]['sex'] = $this->showSexInRead($arr[0]['sex']);
		}else{
			$arr[0]['sex'] = $this->showSexInEdit($arr[0]['sex']);
		}
		return $arr [0];
	}

	/**
	 * 获取相关联系人列表
	 */
	function getLinkManBySId(){
		$arr = $this->listBySqlId ();
		return $arr;
	}

	/**
	 * 重写PAGE方法
	 */
	function page_d() {
		return $this->pageBySqlId ();
	}

	/**
	 * 获取区域权限
	 */
	function getAreaIds_d(){
		$areaDao = new model_system_region_region();
		return $areaDao->getUserAreaId($_SESSION['USER_ID'],0);
	}

	/**
	 * 处理联系人数据
	 */
	 function linkmanInfo($row){
               foreach ($row as $k => $v){
               	    $cusId[$k] = $row[$k]['id'];
               }
               $rows = array();
               if(!empty($cusId)){
               	   $cusId = implode(",",$cusId);
		           $sql = "select l.id,l.customerId,l.linkmanName,l.sex,l.weight,l.age,l.duty," .
		           		  "l.remark,l.height,l.phone,l.mobile,l.address,l.MSN,l.QQ,c.areaName,c.email,c.id as cusId,c.Name as customerName" .
		           		  " from oa_customer_linkman l left join customer c on l.customerId=c.id where customerId in (".$cusId.")";
		           $rows = $this->_db->getArray($sql);
               }
               return $rows;

	 }

	 /**
	  * 客户联系人导入 by Liub
	  */
	 function importExcel($stockArr) {
		try {
			$this->start_d ();
			set_time_limit ( 0 );
			$resultArr = array ();//结果数组
            $addArr = array();//正确信息数组
			foreach ( $stockArr as $key => $obj ) {
				   $cusName = $obj['customerName'];
				   $sql = "select id from customer where Name = '$cusName'"; //查找客户ID
	               $cus =  $this->_db->getArray($sql); //
	               foreach($cus as $k => $v){
                       $cusId = $v['id'];
	               }
	               $linkName = $obj['linkmanName'];
	               $linkSql = "select id from oa_customer_linkman where linkmanName = '$linkName'";
	               $linkmanName =  $this->_db->getArray($linkSql); //
                  if(!empty($obj['customerName']) && !empty($obj['linkmanName']) && !empty($cus) && empty($linkmanName)){
//                      $addArr[$key]['customerName'] = $obj['customerName'];
                      $addArr[$key]['customerId'] = $cusId;
                      $addArr[$key]['linkmanName'] = $obj['linkmanName'];
                      $addArr[$key]['sex'] = $obj['sex'];
                      $addArr[$key]['height'] = $obj['height'];
                      $addArr[$key]['weight'] = $obj['weight'];
                      $addArr[$key]['age'] = $obj['age'];
                      $addArr[$key]['duty'] = $obj['duty']; //职务
                      $addArr[$key]['phone'] = $obj['phone'];
                      $addArr[$key]['mobile'] = $obj['mobile'];
                      $addArr[$key]['MSN'] = $obj['MSN'];
                      $addArr[$key]['QQ'] = $obj['QQ'];
                      $addArr[$key]['address'] = $obj['address'];
                      $addArr[$key]['remark'] = $obj['remark'];

                      array_push ( $resultArr, array ("docCode" => $obj['linkmanName'], "result" => "导入成功！" ) );
                  }else if(empty($obj['customerName']) || empty($cus)){
                  	  array_push ( $resultArr, array ("docCode" => $obj['linkmanName'], "result" => "失败！客户名称为空或不存在" ) );
                  }else if(empty($obj['linkmanName'])){
                  	  array_push ( $resultArr, array ("docCode" => $obj['linkmanName'], "result" => "失败！联系人名称为空" ) );
                  }else if(!empty($linkmanName)){
                      array_push ( $resultArr, array ("docCode" => $obj['linkmanName'], "result" => "失败！联系人名称已存在" ) );
                  }
			}
                  $this->addBatch_d($addArr);
			$this->commit_d ();
			return $resultArr;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}



    /**
     * 更新贝讯联系人信息数据
     */
    function updateLinkmanBX(){
    	$this->titleInfo("正在获取需要插入的联系人数据...");
    	//获取贝讯客户表数据
    	$rowSql = "select c.Name,l.* from oa_customer_linkman_bx l left join customer_bx c on l.customerId = c.id;";
    	$BXrow = $this->_db->getArray($rowSql);
        $this->titleInfo("获取数据完成,开始准备插入数据...");
        foreach($BXrow as $k => $v){
        	$this->handleData($v);
        }
        $this->titleInfo("<input type='button' onclick='history.back()' value='返回'>");
    }


 //整理数据
   function handleData($row){
   	   //根据客户名称判断关联的客户是否存在并反写客户id
   	   $cSql = "select id from customer where Name = '".$row['Name']."'";
   	   $cArr = $this->_db->getArray($cSql);
   	   if(empty($cArr)){
   	   	   $this->titleInfo("<span style='color:red'>× </span>贝讯联系人【".$row['linkmanName']."】 未找到关联客户.    ");
   	   }else{
   	   	  $row['customerId'] = $cArr[0]['id'];
   	   	  //判断联系人是否重复
   	   	  $tt = $this->find(array("linkmanName"=>$row['linkmanName'],"duty"=>$row['duty']));
   	   	  if(!empty($tt)){
   	   	  	$this->titleInfo("<span style='color:blue'>  ○</span>贝讯联系人【".$row['linkmanName']."】 系统内已存在.    ");
   	   	  }else{
   	   	      unset($row['Name']);
	   	   	  unset($row['id']);
	   	   	  $this->add_d($row,false);
	   	   	  $this->titleInfo("<span style='color:black'> √ </span>贝讯联系人【".$row['linkmanName']."】 插入成功.    ");
   	   	  }
   	   }
   }


    //提示信息
	 function titleInfo($ff){
	 	echo str_pad($ff,4096).'<hr />';
		flush();
		ob_flush();
		sleep(0.1);
	 }
}
?>