<?php
/**
 * @author Show
 * @Date 2011年5月13日 星期五 11:19:40
 * @version 1.0
 * @description:license值暂用存储表 Model层
 */
class model_yxlicense_license_tempKey  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_license_tempKey";
		$this->sql_map = "yxlicense/license/tempKeySql.php";
		parent::__construct ();
   }

   /**
	 * 新增license记录
	 */
	function addRecord_d($object){
		$licenseStr = null;

		if($object['licenseStr']){
			$object['fileUploadPath'] = UPLOADPATH . $this->tbl_name . '/';
			$object['fileName'] = 'ls'.generatorSerial() . '.htm';

			$licenseStr = $object['licenseStr'];
			unset($object['licenseStr']);
		}
		try{
			$this->start_d();
			$object['thisVal'] = util_jsonUtil::iconvUTF2GB($object['thisVal']);
			$object['extVal'] = util_jsonUtil::iconvUTF2GB($object['extVal']);
			$object['rowVal'] = util_jsonUtil::iconvUTF2GB($object['rowVal']);
			$object['licenseType'] = util_jsonUtil::iconvUTF2GB($object['licenseType']);
			$newId = parent::add_d($object);

			if($licenseStr){
				//文件操作部分
				if(!is_dir($object['fileUploadPath'])){
					mkdir($object['fileUploadPath']);
				}
				$file = fopen($object['fileUploadPath'].$object['fileName'],'w');

				fwrite($file,util_jsonUtil::iconvUTF2GB(stripslashes($licenseStr)));

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
	 * 获取license记录
	 */
	function getRecord_d($id){
		$rs = $this->find( array ('HWTplId' => $id),null,'fileUploadPath,fileName,licenseType,thisVal,extVal,rowVal,templateId');
		if(empty($rs)){
			$rs = $this->find( array ('id' => $id),null,'fileUploadPath,fileName,licenseType,thisVal,extVal,rowVal,templateId');
		}
		if($rs){
			if(!empty($rs['fileName'])){
	            $fileLocal = UPLOADPATH . $this->tbl_name . '/' .$rs['fileName'];
	            $rs['licenseLocal'] =stripslashes ($fileLocal);
				if($file = fopen($fileLocal,'r')){
					$str = fread($file,filesize($fileLocal));
					fclose($file);
					$rs['licenseStr'] =stripslashes ($str);
				}else{
	                $rs = "Can't find file $fileLocal";
				}
			}else{
				$rs['extVal'] =stripslashes(stripslashes ($rs['extVal']));
				$rs['modalStr'] = $this->reHtml_d($rs['licenseType']);
			}
		}
		return $rs;
	}

	/**
	 * 根据license类型返回模板
	 * 供内部调用
	 */
	function reHtml_d($licenseType){
// 		$licenseType = util_jsonUtil::iconvUTF2GB($licenseType);
		switch($licenseType){
			case 'PIO' : $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-pioneer.htm'; break;
			case 'NAV' : $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-navigator.htm'; break;
			case 'Pioneer-Navigator' : $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-pioneernavigator.htm'; break;
			case 'FL' : $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-fleet.htm'; break;
			case 'FL2' : $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-fleet2.htm'; break;
			case 'SL' : $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-s5wlan.htm'; break;
			case 'RCU' : $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-rcu.htm'; break;
			case 'WT' : $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-walktour.htm'; break;
			case 'Walktour Pack-Ipad' : $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-walktourpad.htm'; break;
			case 'WISER' : $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-wiser.htm'; break;
			case $licenseType : $phpStr = UPLOADPATH . 'oa_license_baseinfo/'.$licenseType.".html"; break;
			default : $phpStr = null; break;
    	}
		if($phpStr){
			$fileSize = filesize($phpStr);
			if($file = fopen($phpStr ,'r')){
				$str = stripslashes(fread($file,$fileSize));
			}else{
				$str = util_jsonUtil::iconvGB2UTF('找不到文件');
			}
			fclose($file);
    	}else{
			$str = util_jsonUtil::iconvGB2UTF('不存在的类型');
    	}
		return $str;
	}

	/**
	 * 保存license记录
	 */
	function saveRecord_d($object){
//		$rs = $this->get_d($object['id']);

//		$licenseStr = $object['licenseStr'];
//		unset($object['licenseStr']);

//		$filePath = UPLOADPATH . $this->tbl_name . '/' .$rs['fileName'];
		try{
			$this->start_d();
			$object['licenseType'] = util_jsonUtil::iconvUTF2GB($object['licenseType']);
			$object['thisVal'] = util_jsonUtil::iconvUTF2GB($object['thisVal']);
			$object['rowVal'] = util_jsonUtil::iconvUTF2GB($object['rowVal']);
			parent::edit_d($object);

//			unlink($filePath);

			//文件操作部分
//			if(!is_dir($object['fileUploadPath'])){
//				mkdir($object['fileUploadPath']);
//			}
//			$file = fopen($filePath,'w');

//			fwrite($file,util_jsonUtil::iconvUTF2GB(stripslashes($licenseStr)));

//			fclose($file);

			$this->commit_d();
			return $object['id'];
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 删除license申请
	 */
	function delRecord_d($id){
		$rs = $this->find( array ('id' => $id),null,'fileUploadPath,fileName,licenseType');
		if($rs){
			$filePath = UPLOADPATH . $this->tbl_name . '/' .$rs['fileName'];
			unlink($filePath);
			$this->deletes_d($id);
			return true;
		}else{
			return false;
		}
	}

	/**
	 *  查看license
	 */
	function viewRecord_d($id){
		$rs = $this->get_d($id);
		if($rs){
            $fileLocal = UPLOADPATH . $this->tbl_name . '/' .$rs['fileName'];
			if($file = fopen($fileLocal,'r')){
				$str = fread($file,filesize($fileLocal));
				fclose($file);
				return $str;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	/**
	 * 批量更新license对象
	 */
	function updateLicenseBacth_d($obj,$mainObjCode,$recordcode ){
		$sql = " update ". $this->tbl_name ." c left join ".$obj['extType']." e on c.id = e.$recordcode set c.objId='" . $obj['objId']. "',c.objType = '" .$obj['objType'] ."',c.extType = '" . $obj['extType']."',c.extId = e.id where e.$mainObjCode = '".$obj['objId']."'";
		try{
			$this->query($sql);
		}catch(exception $e){
			throw $e;
		}
	}

	/**
	 * 获取当前默认license
	 */
	function getLicenseId_d(){
		$baseinfoDao = new model_yxlicense_license_baseinfo();
		$rs = $baseinfoDao->find(array('isDefault' => '1Y'),null,'id');
		return $rs['id'];
	}

    /**
     * 获取objId 为空的数据
     */
    function getRowsObjIdNull_d(){
        $this->searchArr['objIdIsNull'] = 1;
        $rs = $this->list_d('selectFileName');
    	return $rs;
    }

    /**
     * 清空缓存
     */
    function clearCache_d(){

        $rs = $this->getRowsObjIdNull_d();
        $filePath = UPLOADPATH . $this->tbl_name . '/';

        foreach($rs as $val){
            unlink($filePath.$val['fileName']);
        }
        if($this->_db->query('delete from '.$this->tbl_name.' where objId is null ')){
        	return true;
        }else{
        	return false;
        }
    }
    /**
     * 复制已有的license
     * by Liub 2011年8月23日10:45:53
     */
    function copyLicense($row){

           foreach ( $row['chanceequ'] as $k => $v){
               $linceseId[$k] =  $row['chanceequ'][$k]['license'];
           }

           foreach ($linceseId as $key => $val){
           	    $lid = $linceseId[$key];
           	    //获取原加密信息数字信息
           	    $rs = $this->get_d($lid);
           	    //创建新的加密信息数组用
                $rc = array();
           	    $filePath = UPLOADPATH . $this->tbl_name . '/' .$rs['fileName'];
           	    $rc['fileUploadPath'] = UPLOADPATH . $this->tbl_name . '/';
		        $rc['fileName'] = 'ls'.generatorSerial() . '.htm';
           	    //文件操作部分
				if(!is_dir($rc['fileUploadPath'])){
					mkdir($rc['fileUploadPath']);
				}
				//打开原加密文件
				$fileOld = fopen($filePath,'r');
				$str = fread($fileOld,filesize($filePath));
                //将原加密文件写入新创建的文件中
                $fileNew = fopen($rc['fileUploadPath'].$rc['fileName'],'w');
				fwrite($fileNew,$str);

                fclose($fileOld);
				fclose($fileNew);

                $rc['licenseType'] = $rs['licenseType'];
                $rc['thisVal'] = $rs['thisVal'];
                //保存数据，获取新的licenseid
               $newid = parent::add_d($rc);
               $row['chanceequ'][$key]['license'] = $newid;

           }
           return $row;
    }

    /**
     * 查找导出加密需要的地址
     */
	function licenseAdress($id){
		$fileUploadPath = UPLOADPATH . $this->tbl_name . '/';
		$lic = $this->find( array ('id' => $id),null,'fileName,licenseType,extId');
		$lic['Path'] = $fileUploadPath.$lic['fileName'];
		return $lic;
	}

	//根据licenseid获取选择内容
	function getLicenseVal($id){
		$rs = $this->find( array ('id' => $id),null,'thisVal');
		return $rs['thisVal'];
	}

	/**
	 * 获取license管理中已启用的名称
	 */
	function getLicense_d(){
		$licenseDao = new model_yxlicense_license_baseinfo();
		return $licenseDao->getLicense_d();
	}

	/**
	 * 通过ID获取license名称
	 */
	function getLicenseNames_d($id){
		$licenseDao = new model_yxlicense_license_baseinfo();
		return $licenseDao->getLicenseNames_d($id);
	}

	//根据ID获取海外产品加密配置模板
	function getHWTpl_d($id){
		$sql = "select * from oa_license_tempkey where HWTplId = '$id'";
		return $this->_db->get_one($sql);
	}

//保存海外推送的产品配置HTML
	function saveHWProHtml_d($obj){
		$dir_name = UPLOADPATH ."oa_license_tempKey";
	    if(!file_exists($dir_name))//判断文件夹是否存在
		   {
			   mkdir($dir_name,0777);
			   @chmod($dir_name,0777);
		   }
		$filename = UPLOADPATH . 'oa_license_tempKey/'.$obj['fileName'].'';
		if(file_put_contents($filename,$obj['content'])){
			return 1;
		}else{
			return 0;
		}
	}

    /**
     * 判断License是否被引用
     */
    function checkExists_d($licenseType){
        return $this->find(array('licenseType' => $licenseType),null,'id');
    }
}