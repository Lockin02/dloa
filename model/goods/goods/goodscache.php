<?php
/**
 * @author Show
 * @Date 2012年3月9日 星期五 15:50:47
 * @version 1.0
 * @description:产品配置缓存表 Model层
 */
class model_goods_goods_goodscache  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_goods_cache";
		$this->sql_map = "goods/goods/goodscacheSql.php";
		parent::__construct ();
	}

	/**
	 * 新增缓存记录
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

			//字符串类型过滤
			$object['goodsName'] = util_jsonUtil::iconvUTF2GB(stripslashes($object['goodsName']));

			$newId = parent::add_d($object);

			//配置缓存
			$goodsValueCacheArr = $this->changeJson_d($goodsValue);
			$goodsCacheHtml = $this->getPropertiesInfo_f($goodsValueCacheArr,$newId);
			$goodsCacheHtml = addslashes($goodsCacheHtml);
			//$this->update(array('id' => $newId),array('goodsCache' => stripslashes($goodsCacheHtml)));

			if($goodsCache){
				//文件操作部分
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
	 * 保存缓存记录
	 */
	function saveRecord_d($object){
		$rs = $this->get_d($object['id']);

		$filePath = UPLOADPATH . $this->tbl_name . '/' .$rs['fileName'];

		$goodsCache = $object['goodsCache'];
		try{
			$this->start_d();

			$goodsValue = util_jsonUtil::iconvUTF2GB(stripslashes($object['goodsValue']));

			//配置缓存
			$goodsValueCacheArr = $this->changeJson_d($goodsValue);
			$goodsCacheHtml = $this->getPropertiesInfo_f($goodsValueCacheArr,$object['id']);
			$goodsCacheHtml = mysql_real_escape_string($goodsCacheHtml);

			//字符串类型过滤
			$object['goodsValue'] = mysql_real_escape_string($goodsValue);

			//更新缓存值
			$this->update(array('id' => $object['id']),array('goodsValue' => $object['goodsValue'],'goodsCache' => $goodsCacheHtml));

			//文件操作部分
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
	 * 获取缓存字符串
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
	 * 解析json
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
		$obj['goodsValue']=str_replace('\"','"',$obj['goodsValue']);//发现没有这句会无法显示物料内容
		$obj['goodsValue']=str_replace('\n','</br>',$obj['goodsValue']);
		$newArr = (array)json_decode($obj['goodsValue']);

//		print_r($newArr);

		//构建新数组
//		$outArr = array();
//		foreach($newArr as $key => $val){
//			if(is_numeric($key)){
//				$outArr[$key] = $val;
//			}
//		}

		return $newArr;
	}

	/**
	 * 解析json
	 */
	function changeJson_d($goodsValue){
		$goodsValue = iconv('GBK','UTF-8',$goodsValue);
		$goodsValue=str_replace('\n','</br>',$goodsValue);
		$newArr = (array)json_decode(stripslashes($goodsValue));//

		return $newArr;
	}

	/******************* 页面渲染产品配置 ****************/

	/**
	 * 获取对应配置
	 */
	function getPropertiesInfo_f($object,$id){
//		print_r($object);

//		if(){
//
//		}
		//判断数组类型
		$tempObject = $object;
		$popValue = array_pop($tempObject);
		if(is_array($popValue)){
			//缓存数组
			$idArr = array();
			//初始信息缓存数组
			$initArr = array();
			foreach($object as $key => $val){

				//设置查询数组
				$idArr[$val[3]][] = $val[0];
				//设置渲染数组
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
	 * 渲染产品配置
	 */
	function showInnerProduct($rows,$object,$id){
		//实例化产品配置信息
		$propertiesItemDao = new model_goods_goods_propertiesitem();
		//实例化产品配置信息
		$propertiesDao = new model_goods_goods_properties();

		$str = "<tr id='goodsDetail_$id' t='showInnerProduct'><td></td><td class='innerTd' colspan='20'><div id='goodsDiv_$id' style='overflow-x:auto; border:0px solid;'><table class='form_in_table' style='text-align:left;'>";//嵌套表格
		$titleStr = "<tr class='main_tr_header1'>";//标题 th
		$infoStr = "<tr class='tr_inner'>";//内容 tr
		if(is_array($rows)){
//			print_r($object);
			//循环最高层，
			foreach($rows as $key => $val){
				//获取对应分项信息
				$idArr = array();
				//获取文本域id
				$textIdArr = array();
				$remakInfo = null;
				foreach($val as $thisK => $thisV){
					if(strpos($thisV,'i') !== false){
						$textId = substr($thisV,1,strlen($thisV));
						$rexrInfo = util_jsonUtil::iconvUTF2GB($object[$key][$thisV][1]);

						//缓存文本域选项
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

					//分享明细布局
					$mark = "";
					foreach($rs as $k => $v){
						if(!empty($object[$key][$v['id']][2])){
							$innerId = $object[$key][$v['id']][2];
							$licenseStr = " <a href='javascript:void(0)' onclick='showLicense($innerId)'>查看</a>";
						}else{
							$licenseStr = "";
						}
						//print_r($v);
						if(empty($mark)){
							$titleStr .=<<<EOT
								<th>$v[propertiesName]
EOT;
							//如果存在数量
							if(!empty($v['existNum'])){
								$thisNum = $object[$key][$v['id']][1];

								if($thisNum !== 0){
									$infoStr .=<<<EOT
										<td valign='top'>【$v[propertiesName]】$v[itemContent] 数量：$thisNum  $licenseStr<br/>
EOT;
								}
							}else{
								$infoStr .=<<<EOT
									<td valign='top'>【$v[propertiesName]】$v[itemContent]  $licenseStr<br/>
EOT;
							}
							$mark = $key;
						}else if($mark != $key){
							$titleStr .=<<<EOT
								</th><th>$v[propertiesName]
EOT;
							//如果存在数量
							if(!empty($v['existNum'])){
								$thisNum = $object[$key][$v['id']][1];
								if($thisNum !== 0){
									$infoStr .=<<<EOT
										<td valign='top'>【$v[propertiesName]】$v[itemContent] 数量：$thisNum  $licenseStr<br/>
EOT;
								}
							}else{
								$infoStr .=<<<EOT
									<td valign='top'>【$v[propertiesName]】$v[itemContent]  $licenseStr<br/>
EOT;
							}
							$mark = $key;
						}else{
							//如果存在数量
							if(!empty($v['existNum'])){
								$thisNum = $object[$key][$v['id']][1];
								if($thisNum != 0){
									$infoStr .=<<<EOT
										【$v[propertiesName]】 $v[itemContent] 数量：$thisNum  $licenseStr<br/>
EOT;
								}
							}else{
								$infoStr .=<<<EOT
									【$v[propertiesName]】 $v[itemContent]  $licenseStr<br/>
EOT;
							}
						}
					}
					if($remakInfo){
						$infoStr .=<<<EOT
							其他 : $remakInfo<br/>
EOT;
					}

					$titleStr .= "</th>";
					$infoStr .= "</td>";
				}elseif($remakInfo){
					//获取对应值id
					$parentId = $object[$key][$thisV][3];

					$parentArr = $propertiesDao->find(array('id' => $parentId),null,'propertiesName');
					$titleStr .=<<<EOT
						<th>$parentArr[propertiesName] :
EOT;
					$infoStr .=<<<EOT
						<td valign='top'>其他 : $remakInfo
EOT;
				}else{
					//循环显示自定义文本域
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
	 * 渲染产品配置
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
					//如果存在数量
					if(!empty($val['existNum'])){
						$thisNum = $newArr[$val['id']];

						if($thisNum !== 0){
							$infoStr .=<<<EOT
								<td>$val[itemContent] 数量：$thisNum<br/>
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
					//如果存在数量
					if(!empty($val['existNum'])){
						$thisNum = $newArr[$val['id']];
						if($thisNum !== 0){
							$infoStr .=<<<EOT
								<td>$val[itemContent] 数量：$thisNum<br/>
EOT;
						}
					}else{
						$infoStr .=<<<EOT
							<td>$val[itemContent]<br/>
EOT;
					}
					$mark = $val['propertiesName'];
				}else{
					//如果存在数量
					if(!empty($val['existNum'])){
						$thisNum = $newArr[$val['id']];
						if($thisNum !== 0){
							$infoStr .=<<<EOT
								$val[itemContent] 数量：$thisNum<br/>
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


	/******************* 页面渲染产品配置 ****************/

	/******************** 页面渲染产品配置新 ***************/
	/**
	 * 获取对应配置
	 */
	function showCache_d($object,$id){
//		print_r($object);

//		if(){
//
//		}
		//判断数组类型
		$tempObject = $object;
		$popValue = array_pop($tempObject);
		if(is_array($popValue)){
			//缓存数组
			$idArr = array();
			//初始信息缓存数组
			$initArr = array();
			foreach($object as $key => $val){

				//设置查询数组
				$idArr[$val[3]][] = $val[0];
				//设置渲染数组
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
	 * 渲染产品配置
	 */
	function showInnerProduct2($rows,$object,$id){
		//实例化产品配置信息
		$propertiesItemDao = new model_goods_goods_propertiesitem();
		//实例化产品配置信息
		$propertiesDao = new model_goods_goods_properties();

		$str = "<table style='text-align:left' class='form_in_table' width='100%'>";//嵌套表格
		$infoStr = "<tr class='tr_inner'><td>";//内容 tr
		if(is_array($rows)){
//			print_r($object);
			//循环最高层，
			foreach($rows as $key => $val){
				//获取对应分项信息
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

					//分享明细布局
					$mark = "";
					foreach($rs as $k => $v){
						if(!empty($object[$key][$v['id']][2])){
							$innerId = $object[$key][$v['id']][2];
							$licenseStr = " <a href='javascript:void(0)' onclick='showLicense($innerId)'>【加密信息】</a>";
						}else{
							$licenseStr = "";
						}
						//如果存在数量
						if(!empty($v['existNum'])){
							$thisNum = $object[$key][$v['id']][1];

							if($thisNum !== 0){
								$infoStr .=<<<EOT
									【$v[propertiesName]】 $v[itemContent] 数量：$thisNum  $licenseStr<br/>
EOT;
							}
						}else{
							$infoStr .=<<<EOT
								【$v[propertiesName]】 $v[itemContent]  $licenseStr<br/>
EOT;
						}
					}
					if($remakInfo){
						$infoStr .=<<<EOT
							其他 : $remakInfo<br/>
EOT;
					}
				}elseif($remakInfo){
					//获取对应值id
					$parentId = $object[$key][$thisV][3];

					$parentArr = $propertiesDao->find(array('id' => $parentId),null,'propertiesName');
					$infoStr .=<<<EOT
						其他 : $remakInfo
EOT;
				}else{
					$parentArr = $propertiesDao->find(array('id' => $textId),null,'propertiesName');
					$infoStr .=<<<EOT
						【备注】 ：$rexrInfo<br/>
EOT;
				}
			}

			$str .= $infoStr . "</td></tr><table>";
		}
		return $str;
	}


	/*********************** 渲染产品部分 变更 *****************************/
	/**
	 * 渲染产品配置变更情况
	 */
	function getCacheChange_d($object,$id,$beforeObj){
//		print_r($object);
		//判断数组类型
		$tempObject = $object;
		$popValue = array_pop($tempObject);

		$ids = implode(array_keys($object),',');

		//缓存数组
		$idArr = array();
		//初始信息缓存数组
		$initArr = array();
		foreach($object as $key => $val){

			//设置查询数组
			$idArr[$val[3]][] = $val[0];
			//设置渲染数组
			$initArr[$val[3]][$val[0]] = $val;
		}

		//初始化变更前数组
		//缓存数组
		$beforeIdArr = array();
		foreach($beforeObj as $key => $val){

			//设置查询数组
			$beforeIdArr[$val[3]][] = $val[0];
			//设置渲染数组
			$beforeArr[$val[3]][$val[0]] = $val;
		}

		return $this->showProductChange($idArr,$initArr,$id,$beforeIdArr,$beforeArr);
	}

	/**
	 * 渲染产品配置
	 * @param1 配置索引数组
	 * @param2 配置详细信息
	 * @param3 产品id
	 * @param4 变更前配置数组
	 */
	function showProductChange($rows,$object,$id,$beforeRows,$beforeArr){
		//实例化产品配置信息
		$propertiesItemDao = new model_goods_goods_propertiesitem();
		//实例化产品配置信息
		$propertiesDao = new model_goods_goods_properties();

		$str = "<tr id='goodsDetail_$id' t='showProductChange'><td></td><td class='innerTd' colspan='20'><table class='form_in_table' style='text-align:left;'>";//嵌套表格
		$titleStr = "<tr class='main_tr_header1'>";//标题 th
		$infoStr = "<tr class='tr_inner'>";//内容 tr
		if(is_array($rows)){
//			print_r($rows);
			//循环最高层，
			foreach($rows as $key => $val){
				//获取对应分项信息
				$idArr = array();
				//获取文本域id
				$textIdArr = array();
				$remakInfo = null;
				foreach($val as $thisK => $thisV){
					if(strpos($thisV,'i') !== false){
						$textId = substr($thisV,1,strlen($thisV));
						$rexrInfo = util_jsonUtil::iconvUTF2GB($object[$key][$thisV][1]);

						//缓存文本域选项
						$textIdArr[$textId] = util_jsonUtil::iconvUTF2GB($object[$key][$thisV][1]);
					}elseif(strpos($thisV,'t') === false){
						array_push($idArr,$thisV);
					}else{
						$remakInfo = util_jsonUtil::iconvUTF2GB($object[$key][$thisV][1]);
					}
				}

				//获取之前分项信息
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

					//当前配置信息
					$ids = implode($idArr,',');
					$rs = $propertiesItemDao->getItemProperty($ids);

					//之前配置信息
					$beforeIds = implode($beforeIdArr,',');
					$beforeRs = $propertiesItemDao->getItemProperty($beforeIds);
					$beforePro = array();
					if($beforeRs){
						foreach($beforeRs as $thisK => $thisV){
							$beforePro[$thisV['id']] = $thisV;
						}
					}

					//分享明细布局
					$mark = "";
					foreach($rs as $k => $v){

						//差异比较
						$thisTips = in_array($v['id'],$beforeRows[$key]);

						//如果当前项存在于之前的配置且数量不同，而判断为编辑
						if($thisTips && $object[$key][$v['id']][1] != $beforeArr[$key][$v['id']][1]){
							$spanClass = "blue";
							$spanTitle = " title='变更编辑的配置项' ";
							$numDiff = $beforeArr[$key][$v['id']][1] . " => ";
//							$beforeIcon = '<img title="变更新增的产品" src="images/changeedit.gif" />';
							$beforeIcon = '';
							unset($beforePro[$v['id']]);
						}elseif(!$thisTips){
							$spanClass = "red";
							$spanTitle = " title='新增的配置项' ";
							$numDiff = "";
//							$beforeIcon = '<img title="变更新增的产品" src="images/new.gif" />';
							$beforeIcon = '';
							unset($beforePro[$v['id']]);
						}else{
							$spanClass = "";
							$spanTitle = " title='无变动的配置项' ";
							$numDiff = "";
							$beforeIcon = '';
							unset($beforePro[$v['id']]);
						}

						//加载license部分
						if(!empty($object[$key][$v['id']][2])){
							$innerId = $object[$key][$v['id']][2];
							$licenseStr = " <a href='javascript:void(0)' onclick='showLicense($innerId)'>查看</a>";
						}else{
							$licenseStr = "";
						}
						//print_r($v);
						if(empty($mark)){
							$titleStr .=<<<EOT
								<th>$v[propertiesName]
EOT;
							//如果存在数量
							if(!empty($v['existNum'])){
								$thisNum = $object[$key][$v['id']][1];

								if($thisNum !== 0){
									$infoStr .=<<<EOT
										<td valign="top">$beforeIcon<span class="$spanClass" $spanTitle$spanTitle>【$v[propertiesName]】$v[itemContent] 数量：$numDiff $thisNum $licenseStr </span><br/>
EOT;
								}
							}else{
								$infoStr .=<<<EOT
									<td valign="top">$beforeIcon <span class="$spanClass" $spanTitle>【$v[propertiesName]】$v[itemContent]  $licenseStr</span><br/>
EOT;
							}
							$mark = $key;
						}else if($mark != $key){
							$titleStr .=<<<EOT
								</th><th>$v[propertiesName]
EOT;
							//如果存在数量
							if(!empty($v['existNum'])){
								$thisNum = $object[$key][$v['id']][1];
								if($thisNum !== 0){
									$infoStr .=<<<EOT
										<td valign="top">$beforeIcon <span class="$spanClass" $spanTitle>【$v[propertiesName]】$v[itemContent] 数量：$numDiff $thisNum $licenseStr</span><br/>
EOT;
								}
							}else{
								$infoStr .=<<<EOT
									<td valign="top">$beforeIcon <span class="$spanClass" $spanTitle>【$v[propertiesName]】$v[itemContent]  $licenseStr</span><br/>
EOT;
							}
							$mark = $key;
						}else{
							//如果存在数量
							if(!empty($v['existNum'])){
								$thisNum = $object[$key][$v['id']][1];
								if($thisNum != 0){
									$infoStr .=<<<EOT
										$beforeIcon <span class="$spanClass" $spanTitle>【$v[propertiesName]】 $v[itemContent] 数量：$numDiff $thisNum $licenseStr</span><br/>
EOT;
								}
							}else{
								$infoStr .=<<<EOT
									$beforeIcon <span class="$spanClass" $spanTitle>【$v[propertiesName]】 $v[itemContent]  $licenseStr</span><br/>
EOT;
							}
						}
					}


//						print_r($beforePro);
						//处理删除部分
						foreach($beforePro as $bKey => $bVal){

							//加载license部分
							if(!empty($beforeArr[$key][$bVal['id']][2])){
								$innerId = $beforeArr[$key][$bVal['id']][2];
								$licenseStr = " <a href='javascript:void(0)' onclick='showLicense($innerId)'>查看</a>";
							}else{
								$licenseStr = "";
							}

//							$beforeIcon = '<img title="变更删除的产品" src="images/changedelete.gif" />';

							//如果存在数量
							if(!empty($bVal['existNum'])){
								$thisNum = $beforeArr[$key][$bVal['id']][1];
								if($thisNum != 0){
									$infoStr .=<<<EOT
										$beforeIcon <span title="已删除的配置项" style="text-decoration: line-through;">【$bVal[propertiesName]】 $bVal[itemContent] 数量：$thisNum $licenseStr</span><br/>
EOT;
								}
							}else{
								$infoStr .=<<<EOT
									$beforeIcon <span title="已删除的配置项" style="text-decoration: line-through;">【$bVal[propertiesName]】 $bVal[itemContent]  $licenseStr</span><br/>
EOT;
							}
						}



					if($remakInfo){
						$infoStr .=<<<EOT
							其他 : $remakInfo<br/>
EOT;
					}

					$titleStr .= "</th>";
					$infoStr .= "</td>";
				}elseif($remakInfo){
					//获取对应值id
					$parentId = $object[$key][$thisV][3];

					$parentArr = $propertiesDao->find(array('id' => $parentId),null,'propertiesName');
					$titleStr .=<<<EOT
						<th>$parentArr[propertiesName] :
EOT;
					$infoStr .=<<<EOT
						<td valign="top">其他 : $remakInfo
EOT;
				}else{

					//循环显示自定义文本域
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

	/****************** 缓存更新功能 *******************/
	/**
	 * 缓存更新功能
	 */
	function updateCache_d(){
		$sql = "select id,goodsValue,goodsCache from $this->tbl_name where (goodsCache is null or goodsCache = '') and goodsValue <> '' and goodsValue <> '{}'";
		$rs = $this->_db->getArray($sql);

		foreach($rs as $key => $val){

			$goodsValue = util_jsonUtil::iconvUTF2GB(stripslashes($val['goodsValue']));

			//配置缓存
			$goodsValueCacheArr = $this->changeJson_d($goodsValue);
			$goodsCacheHtml = $this->getPropertiesInfo_f($goodsValueCacheArr,$val['id']);
			$goodsCacheHtml = addslashes($goodsCacheHtml);

			//更新缓存值
			$this->update(array('id' => $val['id']),array('goodsCache' => $goodsCacheHtml));
		}
		return true;
	}


	/**
	 * 显示
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


//保存海外推送的产品配置HTML
	function saveHWProHtml_d($obj){
		$obj['content'] = str_replace("contract_product_properties","goods_goods_properties",$obj['content']);
		$dir_name = UPLOADPATH . $this->tbl_name;
	    if(!file_exists($dir_name))//判断文件夹是否存在
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