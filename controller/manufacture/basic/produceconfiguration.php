<?php

class controller_manufacture_basic_produceconfiguration extends controller_base_action {

	function __construct() {

		$this->objName = "produceconfiguration";
		$this->objPath = "manufacture_basic";
		parent::__construct ();
	}

	function c_page() {

		$this->assign('createName' ,$_SESSION['USERNAME']);
		$this->assign('createId' ,$_SESSION ['USER_ID']);
		$this->assign('createTime' ,date("Y-m-d"));
		$this->view ( 'list' );
	}

	 /**
     * 获取树结构列表
     */
    function c_load_produceconfiguration_tree() {
        $datas = $this->service->loadTree();
        header('Content-type:application/json');
        exit(json_encode(un_iconv($datas)));
    }

	function c_configuration(){
		if(isset($_POST['id']) && !empty($_POST['id'])){
			$configuration = $this->service->get_configurationInfo($_POST['id']);
			header('Content-type:application/json');
        	exit(json_encode(un_iconv($configuration)));
		}
	}


    function c_toView(){
    	if(isset($_POST['id']) && !empty($_POST['id'])){
    		$datas['configuration'] = $this->service->get_configurationInfo($_POST['id']);
    		if(!empty($datas['configuration'])){
    			$datas['classify'] =  $this->service->get_classify($datas['configuration']['classifyId']);
    			$datas['process'] =  $this->service->get_process($datas['configuration']['processId']);
    			$datas['template'] =  $this->service->get_template('son',$datas['configuration']['templateId']);
    		}
    		header('Content-type:application/json');
        	exit(json_encode(un_iconv($datas)));
    	}else{
    		echo false;
    	}

    }

    function c_toEdit(){
    	if(isset($_POST['id']) && !empty($_POST['id'])){
    		$datas['configuration'] = $this->service->get_configurationInfo($_POST['id']);
			$datas['classify'] =  $this->service->get_classify($datas['configuration']['classifyId']);
			$process =  $this->service->get_process();
			if(!empty($process)){
				$datas['process'] = "<select class='txt' id='inp_processId' >";
				foreach($process as $val){
					if($val['id'] == $datas['configuration']['processId']){
						$datas['process'] .= "<option value=".$val['id']." selected='selected'>".$val['templateName']."</option>";
					} else {
						$datas['process'] .= "<option value=".$val['id'].">".$val['templateName']."</option>";
					}
				}
				$datas['process'] .= "</select>";
			}

			$parent =  $this->service->get_parent('',$datas['configuration']['parentId']);
			if(!empty($parent)){
				$datas['parent'] = "<select class='txt' id='inp_parentId'>";
				$datas['parent'] .= "<option></option>";
				foreach($parent as $val){
					if($val['id'] == $datas['configuration']['parentId']){
						$datas['parent'] .= "<option value=".$val['id']." selected='selected'>".$val['produceName']."</option>";
					} else if($_POST['id'] != $val['id']){
						$datas['parent'] .= "<option value=".$val['id'].">".$val['produceName']."</option>";
					}
				}
				$datas['parent'] .= "</select>";
			}
			$datas['template'] =  $this->service->get_template('son',$datas['configuration']['templateId']);
    		header('Content-type:application/json');
        	exit(json_encode(un_iconv($datas)));
    	}else{
    		echo false;
    	}
    }

    function c_edit(){
    	if(!empty($_POST)){
    		$data =  $this->service->update($_POST);
    		header('Content-type:application/json');
        	exit(json_encode(un_iconv($data)));
    	}
    	echo false;
    }

	function c_delete(){
		if(!empty($_POST['id'])){
    		$data =  $this->service->delete($_POST['id']);
    		header('Content-type:application/json');
        	exit(json_encode(un_iconv($data)));
    	}
	}

	function c_toAdd(){

		$classify =  $this->service->get_classify();
		if(!empty($classify)){
			$datas['classify'] = "<select class='txt' id='inp_classifyId'>";
			foreach($classify as $val){
				if($val['id'] == $datas['configuration']['classifyId']){
					$datas['classify'] .= "<option value=".$val['id']." selected='selected' >".$val['classifyName']."</option>";
				}else{
					$datas['classify'] .= "<option value=".$val['id'].">".$val['classifyName']."</option>";
				}
			}
			$datas['classify'] .= "</select>";
		} else {
			$datas['classify'] = "<select class='txt' id='inp_classifyId'>".
								"<option></option>" .
								"</select>";
		}


		$process =  $this->service->get_process();
		if(!empty($process)){
			$datas['process'] = "<select class='txt' id='inp_processId' >";
			foreach($process as $val){
				if($val['id'] == $datas['configuration']['processId']){
					$datas['process'] .= "<option value=".$val['id']." selected='selected'>".$val['templateName']."</option>";
				} else {
					$datas['process'] .= "<option value=".$val['id'].">".$val['templateName']."</option>";
				}
			}
			$datas['process'] .= "</select>";
		} else {
			$datas['process'] = "<select class='txt' id='inp_classifyId'>".
							"<option></option>" .
							"</select>";
		}

		$parent =  $this->service->get_parent();
		if(!empty($parent)){
			$datas['parent'] = "<select class='txt' id='inp_parentId'>";
			$datas['parent'] .= "<option></option>";
			foreach($parent as $val){
				if($val['id'] == $datas['configuration']['parentId']){
					$datas['parent'] .= "<option value=".$val['id']." selected='selected'>".$val['produceName']."</option>";
				} else {
					$datas['parent'] .= "<option value=".$val['id'].">".$val['produceName']."</option>";
				}
			}
			$datas['parent'] .= "</select>";
		}else{
			$datas['parent'] = "<select class='txt' id='inp_classifyId'>".
							"<option></option>" .
							"</select>";
		}

		header('Content-type:application/json');
    	exit(json_encode(un_iconv($datas)));

    }

	function c_add(){
    	if(!empty($_POST)){
    		$data =  $this->service->add($_POST);
    		header('Content-type:application/json');
        	exit(json_encode(un_iconv($data)));
    	}
    	echo false;
    }

    function c_classifyTemplate(){
    	if(!empty($_POST['id'])){
    		$template = $this->service->get_template('parent',$_POST['id'] );
			if(!empty($template)){
				$S_template = "<select class='txt' id='inp_classifyId'>";
				foreach($template as $val){
					if(isset($_POST['val']) &&  $val['id'] == $_POST['val']){
						$S_template .= "<option value=".$val['id']." selected='selected' >".$val['templateName']."</option>";
					}else{
						$S_template .= "<option value=".$val['id'].">".$val['templateName']."</option>";
					}
				}
				$S_template .= "</select>";
			} else {
				$S_template = "<select class='txt' id='inp_classifyId'>".
									"<option></option>" .
									"</select>";
			}

			header('Content-type:application/json');
			exit(json_encode(un_iconv($S_template)));
    	}
    	echo false;
    }

}
?>