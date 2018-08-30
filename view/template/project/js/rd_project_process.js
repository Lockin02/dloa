/****************************** delete ******************************/
function del(index){
	var row = getGridRow('project_grid', index);
	$.messager.confirm('删除确认','您确定要删除该项目吗？',function(r){
    	if (r)
    	{
    		$.post(rootUrl + 'del',{id:row.id,rand:Math.random(0,9999)},function(data){
    			if (data == 1)
    			{
    				$.messager.show({
                        title: '提示',
                        msg: '删除成功！',
                        timeout: 3000,
                        showType: 'show'
                    });
    				reloadGrid('project_grid');
    			}else{
    				$.messager.alert('提示','删除失败，请与OA管理员联系！');
    			}
    		});
    	}
    });
}

/****************************** save data ******************************/
function save(){
	var id = $('#id').val();
	var name = $('#name').val();
	var number = $('#number').val();
	var ipo_id = $('#ipo_id').val();
	var zf_id = $('#zf_id').val();
	var status = $('#status').val();
	var manager_name = $('#manager_name').val();
	var assistant_name = $('#assistant_name').val();
	var dept_id = $('#dept_id').val();
	var priority = $('#priority').val();
	var begin_date = $('#begin_date').val();
	var end_date = $('#end_date').val();
	var product_id_str = $('#product_id_str').val() ? $('#product_id_str').val().join(",") : '';
	var description = $('#description').val();
	var msg = '';
	var project_type = $('#project_type').val();
	var extra = "";
	if(typeof($('#extra')).val() != "undefined"){
		extra = $('#extra').val();
	}
	var stage = $('#stage').val();
	var memberId = document.getElementsByName('memberId');
	var addtime = document.getElementsByName('addtime');
	var leavetime = document.getElementsByName('leavetime');
	var percent = document.getElementsByName('percent');
	var member_function = document.getElementsByName('member_function');
	var memberStr = "";
	var addTimeStr = "";
	var leaveTimeStr = "";
	var percentStr = "";
	var developer_name = "";
	var functionStr = "";
	for(var i = 0; i < memberId.length; i++){
		memberStr += memberId[i].value + "|";
		addTimeStr += addtime[i].value + "|";
		leaveTimeStr += leavetime[i].value + "|";
		percentStr += percent[i].value + "|";
		functionStr += member_function[i].value + "|";
		developer_name += memberId[i].value + ",";
	}
	developer_name = developer_name.substring(0,developer_name.length - 1);
	if (name == '') msg += '- 请填写项目名称！\r\n';
	if (begin_date == '') msg +=' - 请选择项目开始日期！\r\n';
	if (end_date == '') msg +=' - 请选择项目结束日期\r\n';
	if (msg !=''){
		$.messager.alert(msg);
	}else{
		$.post(rootUrl + 'save',
			{	id:id,
				name:name,
				number:number,
				ipo_id:ipo_id,
				zf_id:zf_id,
				status:status,
				manager_name:manager_name,
				assistant_name:assistant_name,
				dept_id:dept_id,
				priority:priority,
				developer_name:developer_name,
				begin_date:begin_date,
				end_date:end_date,
				product_id_str:product_id_str,
				description:description,
				rand:Math.random(),
				project_type:project_type,
				extra:extra,
				stage:stage,
				memberId:memberStr,
				addTime:addTimeStr,
				leaveTime:leaveTimeStr,
				percent:percentStr,
				member_function:functionStr
			},
			function(data){
				if (data == 1)
    			{
    				$.messager.show({
                        title: '提示',
                        msg: '操作成功！',
                        timeout: 3000,
                        showType: 'show'
                    });
    				closeDialog('add');
    				reloadGrid('project_grid');
    			}else{
    				$.messager.alert('提示','操作失败，请与OA管理员联系！');
    			}
			});
	}
}

/****************************** create ******************************/
function insert() {
	createAndEditOption();
	$('#id').val('');
	$('#name').val('');
	$('#number').val('');
	$('#ipo_id').attr('value','');
	$('#zf_id').attr('value','');
	$('#status').attr('value','0');
	$('#manager_name').val('');
	$('#assistant_name').val('');
	$('#dept_id').attr('value','');
	$('#priority').attr('value','3');
	$('#begin_date').val('');
	$('#end_date').val('');
	$('#developer_name').val('');
	$('#product_id_str').attr('value','');
	$('#description').val('');
	$('#project_type').val('');
	$('#member_info_table').html("");
	addExtraInput("");
	$('#extra').val('');
	removeAllMemberInfo();
	openDialog('add');
}

/****************************** view ******************************/
function show_info(index){
	var row = getGridRow('project_grid', index);
	for(k in row){
		if (k=='product_id_str'){
			$('#_product_id_str').html('');
			if (row[k]){
				arr = row[k].split(',');
				var product_list ='';
				for (var i=0;i<arr.length;i++){
					product_list += $('#'+k+' option[value='+arr[i]+']').text()+'<br />';
				}
				$('#_'+k).html(product_list);
			}
		}else if (k == 'status'){
			var status = getStatus(row[k]);
			$('#_'+k).html(status);
		}else if (k == 'priority'){
			var priority = getPriority(row[k]);
			$('#_'+k).html(priority);
		}else if(k == 'stage'){
			var stage = stageStatus(row[k]);
			$('#_'+ k).html(stage);
		}else if(k == 'zf_name'){
			arr = row[k].split(',');
			var zf_list = '';
			for (var i=0;i<arr.length;i++){
				zf_list += arr[i]+'<br />';
			}
			$('#_'+k).html(zf_list);
		}else{
			$('#_'+k).html(row[k] ? row[k] : '&nbsp;');
		}
	}
	
	showExtraInfo(row['project_type'], row['extra']);
	$.post(rootUrl + 'view_member_table',{id:row['id']},function(data){
		$('#_member_info_table').html(data);
	});
	openDialog('show_info');
}

/****************************** edit ******************************/
function edit(index){
	var row = getGridRow('project_grid', index);
	createAndEditOption();
	$('#product_id_str').find('option').attr('selected','');
	$('#zf_id').find('option').attr('selected','');
	for(k in row){
		if (k=='product_id_str'){
			$('#' + k + ' option').removeAttr('selected', false);
			if (row[k]){
				arr = row[k].split(',');
				for (var i=0;i<arr.length;i++){
					$('#'+k+' option[value='+arr[i]+']').attr('selected','selected');
				}
			}
		}else if (k == 'zf_id'){
			$('#' + k + ' option').removeAttr('selected', false);
			if (row[k]){
				var arr = row[k].split(',');
				for(var i = 0; i < arr.length; i++){
					$('#'+k+' option[value='+arr[i]+']').attr('selected','selected'); 
				}
			}
		}else{
			$('#'+k).val('');
			$('#'+k).val(row[k]);
		}
	}
	addExtraInput(row['project_type']);
	if(typeof($('#extra')).val() != "undefined"){
		$('#extra').val(row['extra']);
	}
	$.post(rootUrl + 'member_table',{id:row['id']},function(data){
		$('#member_info_table').html(data);
	});
	removeAllMemberInfo();
	$.post(rootUrl + 'get_dev_options',{id:row['id']},function(data){
		$('#selected_user_list').append(data);
	});
	editTitle('add', '编辑');
	openDialog('add');
}

/****************************** rule ******************************/
function encode_rule(){
	openDialog('encode_rule');
}

/****************************** search ******************************/
function getSearch(){
	var keyword = $('#keyword').val();
	var projectType = $('#s_project_type').val();
	var ipoType = $('#s_project_ipo_type').val();
	var zfType = $('#s_zf_type').val();
	var projectStatus = $('#s_project_status').val();
	var stage = $('#s_project_stage').val();
	var dept = $('#s_dept').val();
	$('#project_grid').datagrid({
        url: rootUrl + 'list_data&keyword=' + keyword + '&rand=' + Math.random(0, 999) + '&projectType=' + projectType + '&ipoType=' + ipoType + '&zfType=' + zfType + '&projectStatus=' + projectStatus + '&stage=' + stage + '&dept=' + dept
    });
	button = showButton('project_grid', admin, canImport, canExport);
	setButton('project_grid', button);
}

/****************************** load project grid ******************************/
function process_project_grid(){
	createGrid('project_grid', rootUrl + 'list_data', rootUrl + 'load_product');
	button = showButton('project_grid', admin, canImport, canExport);
	setButton('project_grid', button);
}

/****************************** import ******************************/
function import_file(){
	$('#upfile').val('');
	openDialog('import_div');
}

function importResult(msg){
	if (msg == -1){
		$.messager.show({
			title : '提示',
			msg : '操作失败，请与OA管理员联系！',
			timeout : 3000,
			showType : 'show'
		});
		$('#tt').datagrid('reload');
	}else if (msg == 1){
		$.messager.show({
			title : '提示',
			msg : '操作成功！',
			timeout : 3000,
			showType : 'show'
		});
		
		reloadGrid('project_grid');
		closeDialog('import_div');
	}
}

function uploadMethod(){
	var importForm = document.import_form;
	importForm.action = rootUrl + "import";
	importForm.submit();
}

/****************************** export ******************************/
function export_file(){
	var keyword = $('#keyword').val();
	var projectType = $('#s_project_type').val();
	var ipoType = $('#s_project_ipo_type').val();
	var zfType = $('#s_zf_type').val();
	var projectStatus = $('#s_project_status').val();
	var stage = $('#s_project_stage').val();
	var dept = $('#s_dept').val();
	$('#export_keyword').val(keyword);
	$('#export_projectType').val(projectType);
	$('#export_ipoType').val(ipoType);
	$('#export_zfType').val(zfType);
	$('#export_projectStatus').val(projectStatus);
	$('#export_rand').val(Math.random(0, 999));
	$('#export_dept').val(dept);
	$('#export_stage').val(stage);
	var exportForm = document.getElementById('export_frm');
	exportForm.action = rootUrl + "export"; 
	exportForm.submit();
}
