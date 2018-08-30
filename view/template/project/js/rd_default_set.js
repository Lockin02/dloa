function getStatus(value){
	var status;
	if(value == 0){
		status = '������';
	}else if(value == 1){
		status = 'δ��ʼ';
	}else if(value == 2){
		status = '�����';
	}else if(value == 3){
		status = '��ȡ��';
	}else if(value == 4){
		status = 'δ���';
	}else if(value == 5){
		status = '�����';
	}else if(value == 6){
		status = '�ѹر�';
	}else{
		status = "";
	}
	return status;
}

function getPriority(value){
	var priority;
	if(value == 1){
		priority = '��';
	}else if(value == 2){
		priority = '��';
	}else{
		priority = "��";
	}
	
	return priority;
}

function addExtraInput(id){
	var str = "";
	if(id == '4' ){
		str += "<label>��ͬ��ţ�</label>";
	}else if(id == '6'){
		str += "<label>�̻���ţ�</label>";
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
		str += "��ͬ��ţ�";
	}else if(id == '6'){
		str += "�̻���ţ�";
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
		stage = "�о��׶�";
	}else if(value == 2){
		stage = "�����׶�";
	}else{
		stage = '';
	}
	return stage;
}

function searchOption(){
	projectOptionForSearch = '<option value="" selected >��ѡ��</option>' + projectOption;
	$('#s_project_type').html(projectOptionForSearch);
	deptOptionForSearch = '<option value="">��ѡ����</option>' + deptOption;
	$('#s_dept').html(deptOptionForSearch);
	ipoOptionForSearch = '<option value="" selected >��ѡ��</option>' + ipoOption;
	$('#s_project_ipo_type').html(ipoOptionForSearch);
	zfOptionForSearch = '<option value="" selected >��ѡ��</option>' + zfOption;
	$('#s_zf_type').html(zfOptionForSearch);
}

function createAndEditOption(){
	aprojectOptionForCreateAndEdit = '<option value="" selected >��ѡ��</option>' + projectOption;
	$('#project_type').html(aprojectOptionForCreateAndEdit);
	deptOptionForCreateAndEdit = '<option value="">��ѡ����</option>' + deptOption;
	$('#dept_id').html(deptOptionForCreateAndEdit);
	ipoOptionForCreateAndEdit = '<option value="" selected >��ѡ��</option>' + ipoOption;
	$('#ipo_id').html(ipoOptionForCreateAndEdit);
	zfOptionForCreateAndEdit = '<option value=""></option>' + zfOption;
	$('#zf_id').html(zfOptionForCreateAndEdit);
	productOptionForCreateAndEdit = '<option value=""></option>' + productOption;
	$('#product_id_str').html(productOptionForCreateAndEdit);
}