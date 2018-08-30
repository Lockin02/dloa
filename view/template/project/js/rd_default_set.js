function getStatus(value){
	var status;
	if(value == 0){
		status = '进行中';
	}else if(value == 1){
		status = '未开始';
	}else if(value == 2){
		status = '已完成';
	}else if(value == 3){
		status = '已取消';
	}else if(value == 4){
		status = '未审核';
	}else if(value == 5){
		status = '已审核';
	}else if(value == 6){
		status = '已关闭';
	}else{
		status = "";
	}
	return status;
}

function getPriority(value){
	var priority;
	if(value == 1){
		priority = '低';
	}else if(value == 2){
		priority = '中';
	}else{
		priority = "高";
	}
	
	return priority;
}

function addExtraInput(id){
	var str = "";
	if(id == '4' ){
		str += "<label>合同编号：</label>";
	}else if(id == '6'){
		str += "<label>商机编号：</label>";
	}
	if(str != ''){
		str += "<span>";
		str += "<input type='input' id='extra' name='extra' value='' />";
		str += "</span>";
		
	}
	$('#showExtra').html(str);
}

function showExtraInfo(id, value){
	var str = '';
	if(id == '4' ){
		str += "合同编号：";
	}else if(id == '6'){
		str += "商机编号：";
	}
	
	if(str != ''){
		$('#_show_extra_label').html(str);
		$('#_extra').html(value);
		$('#_show_extra_label').show();
		$('#_extra').show();
	}else{
		$('#_show_extra_label').hide();
		$('#_extra').show();
		$('#_show_extra_label').html("");
		$('#_extra').html("");
	}
}

function stageStatus(value){
	var stage;
	if(value == 1){
		stage = "研究阶段";
	}else if(value == 2){
		stage = "开发阶段";
	}else{
		stage = '';
	}
	return stage;
}

function searchOption(){
	projectOptionForSearch = '<option value="" selected >请选择</option>' + projectOption;
	$('#s_project_type').html(projectOptionForSearch);
	deptOptionForSearch = '<option value="">请选择部门</option>' + deptOption;
	$('#s_dept').html(deptOptionForSearch);
	ipoOptionForSearch = '<option value="" selected >请选择</option>' + ipoOption;
	$('#s_project_ipo_type').html(ipoOptionForSearch);
	zfOptionForSearch = '<option value="" selected >请选择</option>' + zfOption;
	$('#s_zf_type').html(zfOptionForSearch);
}

function createAndEditOption(){
	aprojectOptionForCreateAndEdit = '<option value="" selected >请选择</option>' + projectOption;
	$('#project_type').html(aprojectOptionForCreateAndEdit);
	deptOptionForCreateAndEdit = '<option value="">请选择部门</option>' + deptOption;
	$('#dept_id').html(deptOptionForCreateAndEdit);
	ipoOptionForCreateAndEdit = '<option value="" selected >请选择</option>' + ipoOption;
	$('#ipo_id').html(ipoOptionForCreateAndEdit);
	zfOptionForCreateAndEdit = '<option value=""></option>' + zfOption;
	$('#zf_id').html(zfOptionForCreateAndEdit);
	productOptionForCreateAndEdit = '<option value=""></option>' + productOption;
	$('#product_id_str').html(productOptionForCreateAndEdit);
}