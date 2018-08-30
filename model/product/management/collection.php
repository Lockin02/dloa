<?php
class model_product_management_collection extends model_base
{
	private $so_url;
	private $info_url;
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'product_competitors_patent_collection_data';
		$this->so_url = 'http://211.157.104.87:8080/sipo/zljs/hyjs-jieguo.jsp?flag3=1&sign=0';
		$this->info_url = 'http://211.157.104.87:8080/sipo/zljs/hyjs-yx-new.jsp';
	}
	/**
	 * 搜索结果列表
	 * 
	 * @param unknown_type $type
	 * @param unknown_type $keyword
	 * @param unknown_type $page
	 */
	function GetListData($type,$keyword,$page=1)
	{
		$parms = array('recshu'=>'20','selectbase'=>'0','Submit'=>'搜索');
		$type = $_GET['type'] ? $_GET['type'] : $_POST['type'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		$data = array();
		$data['total'] = 0;
		if ($type && $keyword)
		{
			$http = new includes_class_httpclient();
			$parms['select2'] = $type;
			$parms['textfield32']= $keyword;
			$parms['searchword']="$type='$keyword'";
			$parms['pg'] =$page;
			$http = new includes_class_httpclient();
			$html = $http->Post($this->so_url, $parms,'http://www.sipo.gov.cn/left_yw.html');
			//$html = file_get_contents('aa_data.txt');
			preg_match('/&nbsp;&nbsp;共有(\d*)条记录/is', $html,$count);
			$data['total'] = $count[1];
			preg_match_all('/<td width="158" class="dixian1" >(.*?)<a href="(.*?)"(.*?)">(.*?)<\/a>/is', $html, $arr);
			if ($arr[2])
			{
				$temp = array();
				foreach ($arr[2] as $key=>$val)
				{
					$tmp = parse_url($val);
					parse_str($tmp['query'],$row);
					$row['application_number'] = $arr[4][$key];
					if ($row['recid'])
					{
						$rs = $this->GetOneInfo("recid='".$row['recid']."'");
						if ($rs)
						{
							$row['status'] = 1;
						}else{
							$row['status'] = 0;
						}
					}
					$temp[] = un_iconv($row);
				}
			}
		}
		$data['rows'] = $temp ? $temp : array();
		return $data;
	}
	/**
	 * 详细详细
	 * 
	 * @param unknown_type $recid
	 */
	function GetInfo($recid,$leixin=null)
	{
		$data = array();
		$http = new includes_class_httpclient();
		$html = $http->Get($this->info_url,array('recid'=>$recid));
		preg_match('/<td align="left" class="zi_zw">(.*?)<\/td>/is', $html,$rs);
		$data['summary'] = $rs[1];
		preg_match_all('/<td(.*?)class="kuang(\d)">(.*?)<\/td>/', $html, $arr);
		if ($arr[3])
		{
			$data['leixin'] = $leixin;
			$data['application_number'] = strip_tags($arr[3][1]);
			$data['recid'] = $recid;
			$data['application_date'] = str_replace('.','-',$arr[3][2]);
			$data['title'] = $arr[3][4];
			$data['open_number'] = $arr[3][6];
			$data['open_date'] = str_replace('.','-',$arr[3][7]);
			$data['main_type_number'] = $arr[3][9];
			$data['times_type_number'] = $arr[3][13];
			$data['filing_no'] = $arr[3][11];
			$data['certification_date'] = str_replace('.','-',$arr[3][15]);
			$data['priority'] = $arr[3][17];
			$data['applicant'] = $arr[3][19];
			$data['address'] = $arr[3][21];
			$data['inventor'] = $arr[3][23];
			$data['international_application'] = $arr[3][25];
			$data['international_publication'] = $arr[3][27];
			$data['to_enter_the_national_date']= str_replace('.','-',$arr[3][29]);
			$data['agency'] = $arr[3][30];
			$data['agents'] = $arr[3][32];
			$data = array_map(array($this,'filter'),$data);
		}
		return $data;
	}
	/**
	 * 过滤空格符号
	 * 
	 * @param unknown_type $val
	 */
	function filter($val)
	{
		return trim(str_replace('&nbsp;','',$val));
	}
	/**
	 * 保存远程数据到本地
	 * 
	 * @param unknown_type $recid
	 */
	function SaveUrlData($recid,$leixin)
	{
		$data = $this->GetInfo($recid,$leixin);
		if ($data)
		{
			$data['update_date'] = date('Y-m-d H:i:s');
			$rs = $this->GetOneInfo("recid='$recid'");
			if ($rs)
			{
				return $this->Edit($data, $rs['id']);
			}else{
				$data['date'] = date('Y-m-d H:i:s');
				return $this->Add($data);
			}
		}
	}
	/**
	 * 复制单条数据
	 */
	function CopyOneData($recid)
	{
		$tmp = '';
		if (is_array($recid))
		{
			foreach ($recid as $val)
			{
				$tmp[] = "'$val'";
			} 
		}else if ($recid == 'all'){
			$tmp = '';
		}else{
			$tmp[] = "'$recid'";
		}
		$data = $this->GetDataList(($tmp ? "recid in(".implode(',', $tmp).")" : ''));
		if ($data)
		{
			$id_arr =array();
			foreach ($data as $rs)
			{
				$id_arr[] = $rs['id'];
			}
			if ($id_arr)
			{
				$this->update("id in(".(implode(',', $id_arr)).")", array('export_status'=>1));
				$this->query("
								update 
									product_competitors_patent as a,product_competitors_patent_collection_data as b 
								set
									a.leixin = b.leixin,
									a.recid = b.recid,
									a.title = b.title,
									a.application_number = b.application_number,
									a.application_date = b.application_date,
									a.open_number = b.open_number,
									a.open_date = b.open_date,
									a.main_type_number = b.main_type_number,
									a.times_type_number = b.times_type_number,
									a.filing_no = b.filing_no,
									a.certification_date = b.certification_date,
									a.priority = b.priority,
									a.applicant = b.applicant,
									a.address = b.address,
									a.inventor = b.inventor,
									a.international_application = b.international_application,
									a.international_publication = b.international_publication,
									a.to_enter_the_national_date = b.to_enter_the_national_date,
									a.agency = b.agency,
									a.agents = b.agents,
									a.summary = b.summary,
									a.date = now()
								where
									a.recid=b.recid
									and b.id in (".implode(',', $id_arr).")
				");
				$sql = "insert into product_competitors_patent(
																leixin,
																recid,
																title,
																application_number,
																application_date,
																open_number,
																open_date,
																main_type_number,
																times_type_number,
																filing_no,
																certification_date,
																priority,
																applicant,
																address,
																inventor,
																international_application,
																international_publication,
																to_enter_the_national_date,
																agency,
																agents,
																summary,
																date
															)";
					$sql .="select 								a.leixin,
																a.recid,
																a.title,
																a.application_number,
																a.application_date,
																a.open_number,
																a.open_date,
																a.main_type_number,
																a.times_type_number,
																a.filing_no,
																a.certification_date,
																a.priority,
																a.applicant,
																a.address,
																a.inventor,
																a.international_application,
																a.international_publication,
																a.to_enter_the_national_date,
																a.agency,
																a.agents,
																a.summary,
																now()
					from
						product_competitors_patent_collection_data as a
						left join product_competitors_patent as b on b.recid=a.recid
					where
						b.id is null
						and a.id in (".implode(',',$id_arr).")";
			$this->query($sql);							
			}																							
			return true;
		}
	}
}