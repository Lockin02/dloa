<?php
/**
 * @author Show
 * @Date 2012��3��9�� ������ 15:50:47
 * @version 1.0
 * @description:��Ʒ���û���� Model��
 */
class model_goods_goods_goodscache  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_goods_cache";
		$this->sql_map = "goods/goods/goodscacheSql.php";
		parent::__construct ();
	}

	/**
	 * ���������¼
	 */
	function addRecord_d($object){
		$goodsCache = null;

		if($object['goodsCache']){
			$object['filePath'] = UPLOADPATH . $this->tbl_name . '/';
			$object['fileName'] = 'ls'.generatorSerial() . '.htm';

			$goodsCache = $object['goodsCache'];
			unset($object['goodsCache']);
		}

		try{
			$this->start_d();

			$goodsValue = util_jsonUtil::iconvUTF2GB(stripslashes($object['goodsValue']));
			$object['goodsValue'] = mysql_real_escape_string($goodsValue);

			//�ַ������͹���
			$object['goodsName'] = util_jsonUtil::iconvUTF2GB(stripslashes($object['goodsName']));

			$newId = parent::add_d($object);

			//���û���
			$goodsValueCacheArr = $this->changeJson_d($goodsValue);
			$goodsCacheHtml = $this->getPropertiesInfo_f($goodsValueCacheArr,$newId);
			$goodsCacheHtml = addslashes($goodsCacheHtml);
			//$this->update(array('id' => $newId),array('goodsCache' => stripslashes($goodsCacheHtml)));

			if($goodsCache){
				//�ļ���������
				if(!is_dir($object['filePath'])){
					mkdir($object['filePath']);
				}
				$file = fopen($object['filePath'].$object['fileName'],'w');

				fwrite($file,util_jsonUtil::iconvUTF2GB(stripslashes($goodsCache)));

				fclose($file);

			}

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���滺���¼
	 */
	function saveRecord_d($object){
		$rs = $this->get_d($object['id']);

		$filePath = UPLOADPATH . $this->tbl_name . '/' .$rs['fileName'];

		$goodsCache = $object['goodsCache'];
		try{
			$this->start_d();

			$goodsValue = util_jsonUtil::iconvUTF2GB(stripslashes($object['goodsValue']));

			//���û���
			$goodsValueCacheArr = $this->changeJson_d($goodsValue);
			$goodsCacheHtml = $this->getPropertiesInfo_f($goodsValueCacheArr,$object['id']);
			$goodsCacheHtml = mysql_real_escape_string($goodsCacheHtml);

			//�ַ������͹���
			$object['goodsValue'] = mysql_real_escape_string($goodsValue);

			//���»���ֵ
			$this->update(array('id' => $object['id']),array('goodsValue' => $object['goodsValue'],'goodsCache' => $goodsCacheHtml));

			//�ļ���������
			if(!is_dir($object['filePath'])){
				mkdir($object['filePath']);
			}
			$file = fopen($filePath,'w');

			fwrite($file,util_jsonUtil::iconvUTF2GB(stripslashes($goodsCache)));

			fclose($file);

			$this->commit_d();
			return $object['id'];
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��ȡ�����ַ���
	 */
	function getGoodsCache_d($id){
		$rs = $this->find( array ('id' => $id),null,'filePath,fileName');
		if($rs){
            $fileLocal = UPLOADPATH . $this->tbl_name . '/' .$rs['fileName'];
			if($file = fopen($fileLocal,'r')){
				$str = fread($file,filesize($fileLocal));
				fclose($file);
				$str =stripslashes ($str);
			}else{
                $rs = "Can't find file $fileLocal";
			}
		}
		return $str;
	}

	/**
	 * ����json
	 */
	function changeToProduct_d($id){
		$obj = $this->find( array ('id' => $id),null,'id,goodsValue');

		$obj['goodsValue'] = iconv('GBK','UTF-8',$obj['goodsValue']);

//		$obj['goodsValue'] = str_replace('{','',$obj['goodsValue']);
//		$obj['goodsValue'] = str_replace('}','',$obj['goodsValue']);
//		$obj['goodsValue'] = str_replace('[','',$obj['goodsValue']);
//		$obj['goodsValue'] = str_replace(']','',$obj['goodsValue']);

		//$obj['goodsValue'] = '{' .  $obj['goodsValue'] . '}';

//		echo $obj['goodsValue'];

//		$obj['goodsValue']=mysql_real_escape_string($obj['goodsValue']);
		$obj['goodsValue']=str_replace('\"','"',$obj['goodsValue']);//����û�������޷���ʾ��������
		$obj['goodsValue']=str_replace('\n','</br>',$obj['goodsValue']);
		$newArr = (array)json_decode($obj['goodsValue']);

//		print_r($newArr);

		//����������
//		$outArr = array();
//		foreach($newArr as $key => $val){
//			if(is_numeric($key)){
//				$outArr[$key] = $val;
//			}
//		}

		return $newArr;
	}

	/**
	 * ����json
	 */
	function changeJson_d($goodsValue){
		$goodsValue = iconv('GBK','UTF-8',$goodsValue);
		$goodsValue=str_replace('\n','</br>',$goodsValue);
		$newArr = (array)json_decode(stripslashes($goodsValue));//

		return $newArr;
	}

	/******************* ҳ����Ⱦ��Ʒ���� ****************/

	/**
	 * ��ȡ��Ӧ����
	 */
	function getPropertiesInfo_f($object,$id){
//		print_r($object);

//		if(){
//
//		}
		//�ж���������
		$tempObject = $object;
		$popValue = array_pop($tempObject);
		if(is_array($popValue)){
			//��������
			$idArr = array();
			//��ʼ��Ϣ��������
			$initArr = array();
			foreach($object as $key => $val){

				//���ò�ѯ����
				$idArr[$val[3]][] = $val[0];
				//������Ⱦ����
				$initArr[$val[3]][$val[0]] = $val;
			}

			return $this->showInnerProduct($idArr,$initArr,$id);
		}else{
			$ids = implode(array_keys($object),',');

			if(empty($ids)){
				return "";
			}else{
				$propertiesItemDao = new model_goods_goods_propertiesitem();
				$rs = $propertiesItemDao->getItemProperty($ids);

				return $this->showProductInfo($rs,$object,$id);
			}
		}
	}

	/**
	 * ��Ⱦ��Ʒ����
	 */
	function showInnerProduct($rows,$object,$id){
		//ʵ������Ʒ������Ϣ
		$propertiesItemDao = new model_goods_goods_propertiesitem();
		//ʵ������Ʒ������Ϣ
		$propertiesDao = new model_goods_goods_properties();

		$str = "<tr id='goodsDetail_$id' t='showInnerProduct'><td></td><td class='innerTd' colspan='20'><div id='goodsDiv_$id' style='overflow-x:auto; border:0px solid;'><table class='form_in_table' style='text-align:left;'>";//Ƕ�ױ��
		$titleStr = "<tr class='main_tr_header1'>";//���� th
		$infoStr = "<tr class='tr_inner'>";//���� tr
		if(is_array($rows)){
//			print_r($object);
			//ѭ����߲㣬
			foreach($rows as $key => $val){
				//��ȡ��Ӧ������Ϣ
				$idArr = array();
				//��ȡ�ı���id
				$textIdArr = array();
				$remakInfo = null;
				foreach($val as $thisK => $thisV){
					if(strpos($thisV,'i') !== false){
						$textId = substr($thisV,1,strlen($thisV));
						$rexrInfo = util_jsonUtil::iconvUTF2GB($object[$key][$thisV][1]);

						//�����ı���ѡ��
						$textIdArr[$textId] = util_jsonUtil::iconvUTF2GB($object[$key][$thisV][1]);
					}elseif(strpos($thisV,'t') === false){
						array_push($idArr,$thisV);
					}else{
						$remakInfo = util_jsonUtil::iconvUTF2GB($object[$key][$thisV][1]);
					}
				}
				if(!empty($idArr)){

					$ids = implode($idArr,',');
					$rs = $propertiesItemDao->getItemProperty($ids);

					//������ϸ����
					$mark = "";
					foreach($rs as $k => $v){
						if(!empty($object[$key][$v['id']][2])){
							$innerId = $object[$key][$v['id']][2];
							$licenseStr = " <a href='javascript:void(0)' onclick='showLicense($innerId)'>�鿴</a>";
						}else{
							$licenseStr = "";
						}
						//print_r($v);
						if(empty($mark)){
							$titleStr .=<<<EOT
								<th>$v[propertiesName]
EOT;
							//�����������
							if(!empty($v['existNum'])){
								$thisNum = $object[$key][$v['id']][1];

								if($thisNum !== 0){
									$infoStr .=<<<EOT
										<td valign='top'>��$v[propertiesName]��$v[itemContent] ������$thisNum  $licenseStr<br/>
EOT;
								}
							}else{
								$infoStr .=<<<EOT
									<td valign='top'>��$v[propertiesName]��$v[itemContent]  $licenseStr<br/>
EOT;
							}
							$mark = $key;
						}else if($mark != $key){
							$titleStr .=<<<EOT
								</th><th>$v[propertiesName]
EOT;
							//�����������
							if(!empty($v['existNum'])){
								$thisNum = $object[$key][$v['id']][1];
								if($thisNum !== 0){
									$infoStr .=<<<EOT
										<td valign='top'>��$v[propertiesName]��$v[itemContent] ������$thisNum  $licenseStr<br/>
EOT;
								}
							}else{
								$infoStr .=<<<EOT
									<td valign='top'>��$v[propertiesName]��$v[itemContent]  $licenseStr<br/>
EOT;
							}
							$mark = $key;
						}else{
							//�����������
							if(!empty($v['existNum'])){
								$thisNum = $object[$key][$v['id']][1];
								if($thisNum != 0){
									$infoStr .=<<<EOT
										��$v[propertiesName]�� $v[itemContent] ������$thisNum  $licenseStr<br/>
EOT;
								}
							}else{
								$infoStr .=<<<EOT
									��$v[propertiesName]�� $v[itemContent]  $licenseStr<br/>
EOT;
							}
						}
					}
					if($remakInfo){
						$infoStr .=<<<EOT
							���� : $remakInfo<br/>
EOT;
					}

					$titleStr .= "</th>";
					$infoStr .= "</td>";
				}elseif($remakInfo){
					//��ȡ��Ӧֵid
					$parentId = $object[$key][$thisV][3];

					$parentArr = $propertiesDao->find(array('id' => $parentId),null,'propertiesName');
					$titleStr .=<<<EOT
						<th>$parentArr[propertiesName] :
EOT;
					$infoStr .=<<<EOT
						<td valign='top'>���� : $remakInfo
EOT;
				}else{
					//ѭ����ʾ�Զ����ı���
					foreach($textIdArr as $textKey => $textVal){
						$parentArr = $propertiesDao->find(array('id' => $textKey),null,'propertiesName');
						$titleStr .=<<<EOT
							<th>$parentArr[propertiesName]
EOT;
						$infoStr .=<<<EOT
							<td valign='top'>$textVal<br/>
EOT;
					}
				}
			}
			$str .= $titleStr . $infoStr . "</td></tr></table></div><script>$('#goodsDiv_$id').width(document.documentElement.clientWidth - 50);</script></td></tr>";
		}
		return $str;
	}

	/**
	 * ��Ⱦ��Ʒ����
	 */
	function showProductInfo($rows,$object,$id){
		$newArr = array();
		foreach($object as $key => $val){
			$newArr[$key] = $val;
		}
		$str = "<tr id='goodsDetail_$id' t='showProductInfo'><td></td><td class='innerTd' colspan='20'><table class='form_in_table'>";
		if(is_array($rows)){
			$mark = "";
			$titleStr = "<tr class='main_tr_header1'>";
			$infoStr = "<tr class='tr_inner'>";
			foreach($rows as $key => $val){
				if(empty($mark)){
					$titleStr .=<<<EOT
						<th>$val[propertiesName]
EOT;
					//�����������
					if(!empty($val['existNum'])){
						$thisNum = $newArr[$val['id']];

						if($thisNum !== 0){
							$infoStr .=<<<EOT
								<td>$val[itemContent] ������$thisNum<br/>
EOT;
						}
					}else{
						$infoStr .=<<<EOT
							<td>$val[itemContent]<br/>
EOT;
					}
					$mark = $val['propertiesName'];
				}else if($mark != $val['propertiesName']){
					$titleStr .=<<<EOT
						</th><th>$val[propertiesName]
EOT;
					//�����������
					if(!empty($val['existNum'])){
						$thisNum = $newArr[$val['id']];
						if($thisNum !== 0){
							$infoStr .=<<<EOT
								<td>$val[itemContent] ������$thisNum<br/>
EOT;
						}
					}else{
						$infoStr .=<<<EOT
							<td>$val[itemContent]<br/>
EOT;
					}
					$mark = $val['propertiesName'];
				}else{
					//�����������
					if(!empty($val['existNum'])){
						$thisNum = $newArr[$val['id']];
						if($thisNum !== 0){
							$infoStr .=<<<EOT
								$val[itemContent] ������$thisNum<br/>
EOT;
						}
					}else{
						$infoStr .=<<<EOT
							$val[itemContent]<br/>
EOT;
					}
				}
			}
			$titleStr .= "</th></tr>";
			$infoStr .= "</td></tr>";

			$str .= $titleStr . $infoStr . "</td></tr></table>";
		}
		return $str;
	}


	/******************* ҳ����Ⱦ��Ʒ���� ****************/

	/******************** ҳ����Ⱦ��Ʒ������ ***************/
	/**
	 * ��ȡ��Ӧ����
	 */
	function showCache_d($object,$id){
//		print_r($object);

//		if(){
//
//		}
		//�ж���������
		$tempObject = $object;
		$popValue = array_pop($tempObject);
		if(is_array($popValue)){
			//��������
			$idArr = array();
			//��ʼ��Ϣ��������
			$initArr = array();
			foreach($object as $key => $val){

				//���ò�ѯ����
				$idArr[$val[3]][] = $val[0];
				//������Ⱦ����
				$initArr[$val[3]][$val[0]] = $val;
			}

			return $this->showInnerProduct2($idArr,$initArr,$id);
		}else{
			$ids = implode(array_keys($object),',');

			if(empty($ids)){
				return "";
			}else{
				$propertiesItemDao = new model_goods_goods_propertiesitem();
				$rs = $propertiesItemDao->getItemProperty($ids);

				return $this->showProductInfo($rs,$object,$id);
			}
		}
	}

	/**
	 * ��Ⱦ��Ʒ����
	 */
	function showInnerProduct2($rows,$object,$id){
		//ʵ������Ʒ������Ϣ
		$propertiesItemDao = new model_goods_goods_propertiesitem();
		//ʵ������Ʒ������Ϣ
		$propertiesDao = new model_goods_goods_properties();

		$str = "<table style='text-align:left' class='form_in_table' width='100%'>";//Ƕ�ױ��
		$infoStr = "<tr class='tr_inner'><td>";//���� tr
		if(is_array($rows)){
//			print_r($object);
			//ѭ����߲㣬
			foreach($rows as $key => $val){
				//��ȡ��Ӧ������Ϣ
				$idArr = array();
				$remakInfo = null;
				foreach($val as $thisK => $thisV){
					if(strpos($thisV,'i') !== false){
						$textId = substr($thisV,1,strlen($thisV));
						$rexrInfo = util_jsonUtil::iconvUTF2GB($object[$key][$thisV][1]);
					}elseif(strpos($thisV,'t') === false){
						array_push($idArr,$thisV);
					}else{
						$remakInfo = util_jsonUtil::iconvUTF2GB($object[$key][$thisV][1]);
					}
				}
				if(!empty($idArr)){

					$ids = implode($idArr,',');
					$rs = $propertiesItemDao->getItemProperty($ids);

					//������ϸ����
					$mark = "";
					foreach($rs as $k => $v){
						if(!empty($object[$key][$v['id']][2])){
							$innerId = $object[$key][$v['id']][2];
							$licenseStr = " <a href='javascript:void(0)' onclick='showLicense($innerId)'>��������Ϣ��</a>";
						}else{
							$licenseStr = "";
						}
						//�����������
						if(!empty($v['existNum'])){
							$thisNum = $object[$key][$v['id']][1];

							if($thisNum !== 0){
								$infoStr .=<<<EOT
									��$v[propertiesName]�� $v[itemContent] ������$thisNum  $licenseStr<br/>
EOT;
							}
						}else{
							$infoStr .=<<<EOT
								��$v[propertiesName]�� $v[itemContent]  $licenseStr<br/>
EOT;
						}
					}
					if($remakInfo){
						$infoStr .=<<<EOT
							���� : $remakInfo<br/>
EOT;
					}
				}elseif($remakInfo){
					//��ȡ��Ӧֵid
					$parentId = $object[$key][$thisV][3];

					$parentArr = $propertiesDao->find(array('id' => $parentId),null,'propertiesName');
					$infoStr .=<<<EOT
						���� : $remakInfo
EOT;
				}else{
					$parentArr = $propertiesDao->find(array('id' => $textId),null,'propertiesName');
					$infoStr .=<<<EOT
						����ע�� ��$rexrInfo<br/>
EOT;
				}
			}

			$str .= $infoStr . "</td></tr><table>";
		}
		return $str;
	}


	/*********************** ��Ⱦ��Ʒ���� ��� *****************************/
	/**
	 * ��Ⱦ��Ʒ���ñ�����
	 */
	function getCacheChange_d($object,$id,$beforeObj){
//		print_r($object);
		//�ж���������
		$tempObject = $object;
		$popValue = array_pop($tempObject);

		$ids = implode(array_keys($object),',');

		//��������
		$idArr = array();
		//��ʼ��Ϣ��������
		$initArr = array();
		foreach($object as $key => $val){

			//���ò�ѯ����
			$idArr[$val[3]][] = $val[0];
			//������Ⱦ����
			$initArr[$val[3]][$val[0]] = $val;
		}

		//��ʼ�����ǰ����
		//��������
		$beforeIdArr = array();
		foreach($beforeObj as $key => $val){

			//���ò�ѯ����
			$beforeIdArr[$val[3]][] = $val[0];
			//������Ⱦ����
			$beforeArr[$val[3]][$val[0]] = $val;
		}

		return $this->showProductChange($idArr,$initArr,$id,$beforeIdArr,$beforeArr);
	}

	/**
	 * ��Ⱦ��Ʒ����
	 * @param1 ������������
	 * @param2 ������ϸ��Ϣ
	 * @param3 ��Ʒid
	 * @param4 ���ǰ��������
	 */
	function showProductChange($rows,$object,$id,$beforeRows,$beforeArr){
		//ʵ������Ʒ������Ϣ
		$propertiesItemDao = new model_goods_goods_propertiesitem();
		//ʵ������Ʒ������Ϣ
		$propertiesDao = new model_goods_goods_properties();

		$str = "<tr id='goodsDetail_$id' t='showProductChange'><td></td><td class='innerTd' colspan='20'><table class='form_in_table' style='text-align:left;'>";//Ƕ�ױ��
		$titleStr = "<tr class='main_tr_header1'>";//���� th
		$infoStr = "<tr class='tr_inner'>";//���� tr
		if(is_array($rows)){
//			print_r($rows);
			//ѭ����߲㣬
			foreach($rows as $key => $val){
				//��ȡ��Ӧ������Ϣ
				$idArr = array();
				//��ȡ�ı���id
				$textIdArr = array();
				$remakInfo = null;
				foreach($val as $thisK => $thisV){
					if(strpos($thisV,'i') !== false){
						$textId = substr($thisV,1,strlen($thisV));
						$rexrInfo = util_jsonUtil::iconvUTF2GB($object[$key][$thisV][1]);

						//�����ı���ѡ��
						$textIdArr[$textId] = util_jsonUtil::iconvUTF2GB($object[$key][$thisV][1]);
					}elseif(strpos($thisV,'t') === false){
						array_push($idArr,$thisV);
					}else{
						$remakInfo = util_jsonUtil::iconvUTF2GB($object[$key][$thisV][1]);
					}
				}

				//��ȡ֮ǰ������Ϣ
				$beforeIdArr = array();
				foreach($beforeRows[$key] as $thisK => $thisV){
					if(strpos($thisV,'i') !== false){

					}elseif(strpos($thisV,'t') === false){
						array_push($beforeIdArr,$thisV);
					}
				}

//				print_r($object[$key]);
//				print_r($beforeIdArr[$key]);

				if(!empty($idArr)){

					//��ǰ������Ϣ
					$ids = implode($idArr,',');
					$rs = $propertiesItemDao->getItemProperty($ids);

					//֮ǰ������Ϣ
					$beforeIds = implode($beforeIdArr,',');
					$beforeRs = $propertiesItemDao->getItemProperty($beforeIds);
					$beforePro = array();
					if($beforeRs){
						foreach($beforeRs as $thisK => $thisV){
							$beforePro[$thisV['id']] = $thisV;
						}
					}

					//������ϸ����
					$mark = "";
					foreach($rs as $k => $v){

						//����Ƚ�
						$thisTips = in_array($v['id'],$beforeRows[$key]);

						//�����ǰ�������֮ǰ��������������ͬ�����ж�Ϊ�༭
						if($thisTips && $object[$key][$v['id']][1] != $beforeArr[$key][$v['id']][1]){
							$spanClass = "blue";
							$spanTitle = " title='����༭��������' ";
							$numDiff = $beforeArr[$key][$v['id']][1] . " => ";
//							$beforeIcon = '<img title="��������Ĳ�Ʒ" src="images/changeedit.gif" />';
							$beforeIcon = '';
							unset($beforePro[$v['id']]);
						}elseif(!$thisTips){
							$spanClass = "red";
							$spanTitle = " title='������������' ";
							$numDiff = "";
//							$beforeIcon = '<img title="��������Ĳ�Ʒ" src="images/new.gif" />';
							$beforeIcon = '';
							unset($beforePro[$v['id']]);
						}else{
							$spanClass = "";
							$spanTitle = " title='�ޱ䶯��������' ";
							$numDiff = "";
							$beforeIcon = '';
							unset($beforePro[$v['id']]);
						}

						//����license����
						if(!empty($object[$key][$v['id']][2])){
							$innerId = $object[$key][$v['id']][2];
							$licenseStr = " <a href='javascript:void(0)' onclick='showLicense($innerId)'>�鿴</a>";
						}else{
							$licenseStr = "";
						}
						//print_r($v);
						if(empty($mark)){
							$titleStr .=<<<EOT
								<th>$v[propertiesName]
EOT;
							//�����������
							if(!empty($v['existNum'])){
								$thisNum = $object[$key][$v['id']][1];

								if($thisNum !== 0){
									$infoStr .=<<<EOT
										<td valign="top">$beforeIcon<span class="$spanClass" $spanTitle$spanTitle>��$v[propertiesName]��$v[itemContent] ������$numDiff $thisNum $licenseStr </span><br/>
EOT;
								}
							}else{
								$infoStr .=<<<EOT
									<td valign="top">$beforeIcon <span class="$spanClass" $spanTitle>��$v[propertiesName]��$v[itemContent]  $licenseStr</span><br/>
EOT;
							}
							$mark = $key;
						}else if($mark != $key){
							$titleStr .=<<<EOT
								</th><th>$v[propertiesName]
EOT;
							//�����������
							if(!empty($v['existNum'])){
								$thisNum = $object[$key][$v['id']][1];
								if($thisNum !== 0){
									$infoStr .=<<<EOT
										<td valign="top">$beforeIcon <span class="$spanClass" $spanTitle>��$v[propertiesName]��$v[itemContent] ������$numDiff $thisNum $licenseStr</span><br/>
EOT;
								}
							}else{
								$infoStr .=<<<EOT
									<td valign="top">$beforeIcon <span class="$spanClass" $spanTitle>��$v[propertiesName]��$v[itemContent]  $licenseStr</span><br/>
EOT;
							}
							$mark = $key;
						}else{
							//�����������
							if(!empty($v['existNum'])){
								$thisNum = $object[$key][$v['id']][1];
								if($thisNum != 0){
									$infoStr .=<<<EOT
										$beforeIcon <span class="$spanClass" $spanTitle>��$v[propertiesName]�� $v[itemContent] ������$numDiff $thisNum $licenseStr</span><br/>
EOT;
								}
							}else{
								$infoStr .=<<<EOT
									$beforeIcon <span class="$spanClass" $spanTitle>��$v[propertiesName]�� $v[itemContent]  $licenseStr</span><br/>
EOT;
							}
						}
					}


//						print_r($beforePro);
						//����ɾ������
						foreach($beforePro as $bKey => $bVal){

							//����license����
							if(!empty($beforeArr[$key][$bVal['id']][2])){
								$innerId = $beforeArr[$key][$bVal['id']][2];
								$licenseStr = " <a href='javascript:void(0)' onclick='showLicense($innerId)'>�鿴</a>";
							}else{
								$licenseStr = "";
							}

//							$beforeIcon = '<img title="���ɾ���Ĳ�Ʒ" src="images/changedelete.gif" />';

							//�����������
							if(!empty($bVal['existNum'])){
								$thisNum = $beforeArr[$key][$bVal['id']][1];
								if($thisNum != 0){
									$infoStr .=<<<EOT
										$beforeIcon <span title="��ɾ����������" style="text-decoration: line-through;">��$bVal[propertiesName]�� $bVal[itemContent] ������$thisNum $licenseStr</span><br/>
EOT;
								}
							}else{
								$infoStr .=<<<EOT
									$beforeIcon <span title="��ɾ����������" style="text-decoration: line-through;">��$bVal[propertiesName]�� $bVal[itemContent]  $licenseStr</span><br/>
EOT;
							}
						}



					if($remakInfo){
						$infoStr .=<<<EOT
							���� : $remakInfo<br/>
EOT;
					}

					$titleStr .= "</th>";
					$infoStr .= "</td>";
				}elseif($remakInfo){
					//��ȡ��Ӧֵid
					$parentId = $object[$key][$thisV][3];

					$parentArr = $propertiesDao->find(array('id' => $parentId),null,'propertiesName');
					$titleStr .=<<<EOT
						<th>$parentArr[propertiesName] :
EOT;
					$infoStr .=<<<EOT
						<td valign="top">���� : $remakInfo
EOT;
				}else{

					//ѭ����ʾ�Զ����ı���
					foreach($textIdArr as $textKey => $textVal){
						$parentArr = $propertiesDao->find(array('id' => $textKey),null,'propertiesName');
						$titleStr .=<<<EOT
							<th>$parentArr[propertiesName]
EOT;
						$infoStr .=<<<EOT
							<td valign="top">$textVal<br/>
EOT;
					}
				}
			}

			$str .= $titleStr . $infoStr . "</td></tr></table>";
		}
		return $str;
	}

	/****************** ������¹��� *******************/
	/**
	 * ������¹���
	 */
	function updateCache_d(){
		$sql = "select id,goodsValue,goodsCache from $this->tbl_name where (goodsCache is null or goodsCache = '') and goodsValue <> '' and goodsValue <> '{}'";
		$rs = $this->_db->getArray($sql);

		foreach($rs as $key => $val){

			$goodsValue = util_jsonUtil::iconvUTF2GB(stripslashes($val['goodsValue']));

			//���û���
			$goodsValueCacheArr = $this->changeJson_d($goodsValue);
			$goodsCacheHtml = $this->getPropertiesInfo_f($goodsValueCacheArr,$val['id']);
			$goodsCacheHtml = addslashes($goodsCacheHtml);

			//���»���ֵ
			$this->update(array('id' => $val['id']),array('goodsCache' => $goodsCacheHtml));
		}
		return true;
	}


	/**
	 * ��ʾ
	 */
	function showDeploy($id){
		$deployShow = '';
		if( $id!=0 || $id!='' ){
			$goodObj = $this -> find(array('id' => $id),null,'goodsCache');
			if($goodObj['goodsCache']){
				$deployShow=$goodObj['goodsCache'];
			}else{
				$obj = $this -> changeToProduct_d($id);
				$deployShow = $this -> getPropertiesInfo_f($obj,$id);
			}
		}
		return $deployShow;
	}


//���溣�����͵Ĳ�Ʒ����HTML
	function saveHWProHtml_d($obj){
		$obj['content'] = str_replace("contract_product_properties","goods_goods_properties",$obj['content']);
		$dir_name = UPLOADPATH . $this->tbl_name;
	    if(!file_exists($dir_name))//�ж��ļ����Ƿ����
		   {
			   mkdir($dir_name,0777);
			   @chmod($dir_name,0777);
		   }
		$filename = UPLOADPATH . $this->tbl_name . '/'.$obj['fileName'].'';
		if(file_put_contents($filename,$obj['content'])){
			return 1;
		}else{
			return 0;
		}
	}
}

?>