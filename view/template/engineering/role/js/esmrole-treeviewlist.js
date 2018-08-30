$(function() {
	//��ʼ������
	$.ajax({
		type : "POST",
		url : "?model=engineering_role_esmrole&action=checkParent",
		data : "",
		async : false
	});

	var thisHeight = document.documentElement.clientHeight - 10;
	$('#esmroleGrid').treegrid({
		url : '?model=engineering_role_esmrole&action=treeJson',
		queryParams : {'projectId' : $("#projectId").val()},
		title : '��Ŀ��ɫ',
		width : '98%',
		height : thisHeight,
		nowrap : false,
		rownumbers : true,
		idField : 'id',
		treeField : 'roleName',//��������
		fitColumns : false,//�����Ӧ
		pagination : false,//��ҳ
		showFooter : false,//��ʾͳ��
		columns : [[
			{
				title : '��ɫ����',
				field : 'roleName',
				width : 250,
				formatter:function(v,row){
					if(row.isManager == '1'){
                		return '<span class="blue" title="��Ŀ����">'+v+'</span>';
				 	}else{
				 		return v;
				 	}
				}
			},{
				field : 'activityName',
				title : '��������',
				width : 300
			},{
				field : 'memberName',
				title : '��Ա����',
				width : 200

			},{
				field : 'jobDescription',
				title : '��ע˵��',
				width : 250
			}
		]]
	});
});