<?php
/*
 * ������Ӧ��model�㷽��
 */
class model_supplierManage_assess_norm extends model_base {
/**
 * @desription ���캯��
 * @param tags
 * @date 2010-11-10 ����04:39:47
 */
	function __construct () {
		$this->tbl_name = "oa_supp_asses_norm";
		$this->sql_map = "supplierManage/assess/normSql.php";
		parent :: __construct();
	}

	/**
	 * @desription �鿴����ָ��
	 * @param tags
	 * @date 2010-11-12 ����05:28:53
	 */
	function sasReadList_d ($normArr) {
		$str = "";
		$i = 0;
		if($normArr){
			foreach( $normArr as $key => $val ){
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$i++;
				$str .=<<<EOT
	            <tr class="$classCss">
                    <td>
                       	$i
                    </td>
                    <td>
                        $val[normName]
                    </td>
                    <td>
                        $val[assesCriteria]
                    </td>
                    <td>
                        $val[weight] %
                    </td>
                    <td>
                        $val[normTotal]
                    </td>
                </tr>
EOT;
			}
		}else {
			$str = '<tr><td colspan="50">������ؼ�¼</td></tr>';
		}
		return $str;
	}

	/**
	 * @desription ��ʾ�б�
	 * @param tags
	 * @date 2010-11-15 ����03:49:55
	 */
	function sasNormList_s ($normArr) {
		$str = "";
		$i = 0;
		if($normArr){
			foreach( $normArr as $key => $val ){
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$i++;
				$str .=<<<EOT
	            <tr class="$classCss">
                    <td>
                       	$i
                    </td>
                    <td>
                        $val[normName]
                    </td>
                    <td>
                        $val[assesCriteria]
                    </td>
                    <td>
                        $val[weight]
                    </td>
                    <td>
                        $val[normTotal] ����
                    </td>
                    <td class="main_td_align_left">
                        &nbsp;<input type="text" class="txtshort scoreVal" name="obj[normId][$val[id]][score]" value="$val[score]" >��
                        <input type="hidden" class="normTotal" value="$val[normTotal]" />
                        <input type="hidden" name="obj[normId][$val[id]][perId] " value="$val[perId]" />
                    </td>
                </tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * @desription ��ʾ�б�
	 * @param tags
	 * @date 2010-11-17 ����10:27:17
	 */
	function getNormPeo_s ( $arr ) {
		$top="";
		$list = "";
		foreach( $arr as $key => $val ){
			if( $key==0 ){
				foreach( $val as $keyc => $valc ){
					if( $keyc ==0 ) $width="width='20%'";
					else $width="";
					$top .= "<th $width> ".$valc['val']." </th>";
				}
			}else{
				$trV ="";
				foreach( $val as $keyv => $valv ){
					$trV .= "<td> $valv[val] </td>";
				}
				$list .= "<tr>$trV</tr>";
			}
		}

		$str =<<<EOT
                    <thead>
                        <tr class="main_tr_header">
                            $top
                        </tr>
                    </thead>
                    <tbody>
						$list
                    </tbody>
EOT;
		return $str;
	}

	/**
	 * @desription ���ָ��
	 * @param tags
	 * @date 2010-11-12 ����02:24:13
	 */
	function sasAdd_d ( $arr ) {
		try {
			$this->start_d ();
			$newId = $this->add_d($arr,true);
			$suppDao = new model_supplierManage_assess_suppassess();
			$suppArr = $suppDao->getAllByAssesId ( $arr['assesId'] );
			if( is_array($suppArr) ){
				$suppSql = "insert into oa_supp_asses_suppnorm(suppId,suppName,assesId,normId,normName) values  ";
				foreach($suppArr as $suppKey => $suppVal ){
					$suppSql .= "('".$suppVal['id']."','".$suppVal['suppName']."','".$arr['assesId']."','".$newId ."','".$arr['normName']."'),";
				}
				$this->query( substr( $suppSql,0,-1 ) );
			}
			$peopleDao = new model_supplierManage_assess_sapeople();
			$peopleDao->sapAdd($arr,$newId);
			$this->commit_d ();
			return $newId;
		} catch ( Exception $e ) {
			echo "<pre>";
			print_r($e);
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * @desription ͨ��ID��ȡ����
	 * @param tags
	 * @date 2010-11-19 ����10:09:33
	 */
	function sasArrById_d ( $id ) {
		$this->resetParam();
		$this->searchArr = array( "id"=>$id );
		$arr = $this->list_d();
		$datDao = new model_system_datadict_datadict();
		$asseserName = $asseserId = "";
		$peoArr = $this->findSql(" select asseserId,asseserName,peopleId from oa_supp_asses_normpeo where normId='".$id."' ");
		foreach( $peoArr as $peoKey => $peoVal ){
			$asseserName .= $peoVal['asseserName'].",";
			$asseserId .= $peoVal['asseserId'].",";
		}
		$arr[0]['normCodeName'] = $datDao->dataDictArr[ $arr[0]['normCode'] ];
		$arr[0]['asseserName'] = $asseserName;
		$arr[0]['asseserId'] = $asseserId;
		//echo "<pre>";
		//print_r($arr);
		return $arr;
	}

	/**
	 * @desription ͨ������Id��ȡ��������
	 * @param tags
	 * @date 2010-11-12 ����02:31:52
	 */
	function getAllByAssesId_d ( $assesId ) {
		$this->resetParam();
		$this->searchArr = array( "assesId"=>$assesId );
		$arr = $this->list_d();
		$datDao = new model_system_datadict_datadict();
		foreach( $arr as $key => $val ){
			$arr[$key]['normCodeName'] = $datDao->dataDictArr[ $arr[$key]['normCode'] ];
		}
		return $arr;
	}

	/**
	 * @desription ͨ������Id��ȡ��������
	 * @param tags
	 * @date 2010-11-12 ����02:31:52
	 */
	function getPage_d ( ) {
		$arr = $this->page_d ();
		$datDao = new model_system_datadict_datadict();
		foreach( $arr as $key => $val ){
			$arr[$key]['normCodeName'] = $datDao->dataDictArr[ $arr[$key]['normCode'] ];
		}
		return $arr;
	}

	/**
	 * @desription ���ݷ���Id �û���ȡ����
	 * @param tags
	 * @date 2010-11-16 ����03:46:16
	 */
	function getAllByAssesUId_d ($assesId) {
		$this->resetParam();
		$this->searchArr = array(
			"assesId"=>$assesId,
			"asseserId" => $_SESSION['USER_ID']
		);
		$arr = $this->list_d("select_user");
		//$datDao = new model_system_datadict_datadict();
		//$arr['0']['normCodeName'] = $datDao->dataDictArr[ $arr['0']['normCode'] ];
		return $arr;
	}

	/**
	 * @desription ���ݷ���ID��ȡ�б�
	 * @param tags
	 * @date 2010-11-15 ����03:48:23
	 */
	function getNorm ( $assesId ,$suppId) {
		$arr = $this->getAllByAssesUId_d($assesId);
		foreach( $arr as $key => $val ){
		$arrPer = $this->_db->getArray( " select * from oa_supp_asses_per where userId='".$_SESSION['USER_ID']."' and assesId='$assesId' and normId='".$val['id']."' and suppId='$suppId' " );
			$arr[$key]['perId'] = $arrPer['0']['id'];
			$arr[$key]['score'] = $arrPer['0']['score'] ;
		}
		return $this->sasNormList_s( $arr );
	}

	/**
	 * @desription ��ȡ�������
	 * @param tags
	 * @date 2010-11-17 ����09:40:50
	 */
	function getNormPeo_d ( $assId,$suppPjId ) {
		$normArr = $this->getAllByAssesId_d( $assId );
		$perArr = $this->findSql(" select * from  oa_supp_asses_per where assesId='".$assId."' and suppId='".$suppPjId."' ");
		$peoDao = new model_supplierManage_assess_sapeople();
		$peoArr = $peoDao->peoGetAllByAssesId_d($assId);
		$arr = array();
		$arr['0']['0'] = array("val"=>"������\ָ��" );
		foreach( $peoArr as $peoKey => $peoVal ){
			foreach( $normArr as $normKey => $normVal ){
				$arr[$peoKey+1]['0']['val']=$peoVal['asseserName'];
				$arr[$peoKey+1][$normKey+1]['val']="";
				$arr['0'][$normKey+1]['val'] = $normVal['normName'];
				foreach( $perArr as $perKey => $perVal ){
					if( $perVal['normId']==$normVal['id']  &&  $perVal['peopleId']==$peoVal['id'] ){
						$arr[$peoKey+1][$normKey+1]['val'] = $perVal['score'];
					}
				}
			}
		}
		$arr[$peoKey+2]['0'] = array( "val"=>"ƽ��" );
		foreach( $normArr as $normKey => $normVal ){
			$score = 0;
			$i = 0;
			foreach( $perArr as $perKey => $perVal ){
				if( $perVal['normId']==$normVal['id'] && isset( $perVal['score'] ) && $perVal['score']!="" ){
					$score += $perVal['score'];
					++$i;
				}
			}
			if( $i!=0 ){
				$arr[$peoKey+2][$normKey+1] = array( "val"=>$score/$i );
			}else{
				$arr[$peoKey+2][$normKey+1] = array( "val"=>0 );
			}
		}
//		echo "<pre>";
//		print_r($arr);
		return $arr;
	}

	/**
	 * @desription ɾ������
	 * @param tags
	 * @date 2010-11-17 ����02:53:37
	 */
	function deletes_d ( $id ) {
		try {
			$arr = $this->findSql("select peopleId,assesId from oa_supp_asses_normpeo where normId='$id' group by peopleId");
			$delPeoStr = "";
			foreach( $arr as $key =>$val ){
				$count = $this->findSql( " select count(*) from oa_supp_asses_normpeo where assesId='".$val['assesId']."' and normId !='$id' and peopleId='".$val['peopleId']."' " );
				if($count==0){
					$delPeoStr .= $val['peopleId'].",";
				}
			}
			if($delPeoStr!=""){
				$peoDao = new model_supplierManage_assess_sapeople();
				$peoDao->deletes_d( substr( $delPeoStr,0,-1 ) );
			}
			$this->deletes($id);
			return true;
		} catch (Exception $e) {
			throw $e;
			return false;
		}
	}

}
?>
