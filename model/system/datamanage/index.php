<?php
class model_system_datamanage_index extends model_base
{
	
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'data_dictionary';
	}
	/**
	 * 表列表
	 */
	function model_table_list($keyword=null)
	{
		$result = $this->_db->list_tables();
		while (($tb =$this->fetch_array($result,MYSQL_NUM))!=false)
		{
			if ($keyword && strpos($tb[0],$keyword)===false)
			{
				continue;
			}
			$str .='<tr>';
			$str .='<td><input type="checkbox" name="table[]" value="'.$tb[0].'" /></td>';
			$str .='<td><b>'.$tb[0].'</b></td><td align="left" width="65%">';
						
			$query = mysql_query("select * from $tb[0]");
            while (($field = mysql_fetch_field($query))!=false)
            {
            	$str .=$field->name.'　';
            }
			$str .='</td><td><a href="?model='.$_GET['model'].'&action=edit_field&table='.trim($tb[0]).'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800" class="thickbox">查看或修改</a></td></tr>';
			//var_dump($tb);
			//exit();
			//echo $tb[0].'<br />';
		}
		return $str;
	}
	/**
	 * 表字段
	 * @param $table
	 */
	function model_table_field($table,$html=true)
	{
		$query = $this->query("show full columns from $table");
		$str = '';
		$i = 0;
		$data = array();
		while (($row = $this->fetch_array($query))!=false)
		{
			if ($html)
			{
				//var_dump($row);
				//echo '<hr />';
				$i++;
				$str .='<tr>';
				$str .='<input type="hidden" name="Field[]" value="'.$row['Field'].'" />';
				$str .='<input type="hidden" name="Default['.$row['Field'].']" value="'.$row['Default'].'" />';
				$str .='<input type="hidden" name="Type['.$row['Field'].']" value="'.$row['Type'].'" />';
				$str .='<input type="hidden" name="Collation['.$row['Field'].']" value="'.$row['Collation'].'" />';
				$str .='<input type="hidden" name="Extra['.$row['Field'].']" value="'.$row['Extra'].'" />';
				$str .='<td>'.$i.'</td>';
				$str .='<td>'.$row['Field'].'</td>';
				$str .='<td>'.$row['Type'].'</td>';
				$str .='<td>'.$row['Null'].'</td>';
				$str .='<td>'.$row['Default'].'</td>';
				$str .='<td>'.($row['Key'] ? 'YES' : 'NO').'</td>';
				$str .='<td><input type="text" name="Comment['.$row['Field'].']" value="'.$row['Comment'].'" /></td>';
				$str .='<tr>';
			}else{
				$data[] = $row;
			}
			
		}
		
		return $html ? $str : $data;
	}
	/**
	 * 
	 * @param $data
	 */
	function model_table_comment($data)
	{
		
	}
	/**
	 * 修改字段注释
	 * @param $data
	 */
	function model_field_comment($data)
	{
		if ($data['table'] && $data['Field'])
		{
			try {
				foreach ($data['Field'] as $key=>$val)
				{
					$Comment = $data['Comment'][$val] ? iconv('gbk','utf-8//IGNORE',$data['Comment'][$val]) : '';
					$Extra = $data['Extra'][$val]=='auto_increment' ? 'AUTO_INCREMENT' : '';
					$Default = $data['Default'][$val]!='' ? "DEFAULT '".$data['Default'][$val]."'" : '';
					$this->query("alter table ".$data['table']." modify column $val ".$data['Type'][$val]." $Default $Extra comment '".trim($Comment)."'");
				}
				return true;
			} catch (Exception $e) {
				return false;
			}
			
		}else{
			return false;
		}
	}
	
	function toWord($table) 
	{
        set_time_limit(0);
		header('Content-type: application/doc');
        header('Content-Disposition: attachment; filename="'.time().'.doc"');
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"
       xmlns:w="urn:schemas-microsoft-com:office:word" 
       xmlns="[url=http://www.w3.org/TR/REC-html40]http://www.w3.org/TR/REC-html40[/url]">
                <head>
                <meta http-equiv="Content-Type" content="text/html; charset=GBK" />
                <title>'.time().'表</title>
                </head>
                <body><div align="center">';
        if (is_array($table))
        {
        	foreach ($table as $val)
        	{
        		echo '<table border="0"><tr><td><h2>'.$val.' 表</h2></td></tr></table>';
        		echo'<table border="1" width="98%" cellspacing="0" cellpadding="0" style="border-collapse: collapse" bordercolor="#000000">';
        		echo '<tr bgcolor="#C0C0C0">';
        		echo '<td width="10%">编号</td>
				<td width="15%">字段名称</td>
				<td width="15%">字段类型</td>
				<td width="10%">Null</td>
				<td width="10%">默认值</td>
				<td width="10%">主键</td>
				<td width="30%">说明</td>';
		        echo'</tr>';
        		$data = $this->model_table_field($val,false);
        		if ($data)
        		{
        			foreach ($data as $key=>$row)
        			{
        				echo '<tr>';
        				echo '<td>'.($key+1).'</td>';
        				echo '<td>'.$row['Field'].'</td>';
        				echo '<td>'.$row['Type'].'</td>';
        				echo '<td>'.$row['Null'].'</td>';
        				echo '<td>'.$row['Default'].'</td>';
        				echo '<td>'.($row['Key'] ? 'YES' : 'NO').'</td>';
        				echo '<td>'.$row['Comment'].'</td>';
        				echo '</tr>';
        			}
        		}
        		echo'</table>';
        	}
        }
        echo'</div></body>';
        echo'</html>';
    }

	
	
}

?>