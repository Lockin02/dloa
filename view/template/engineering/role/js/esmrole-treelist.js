$(function() {
	//��ʼ������
	$.ajax({
		type : "POST",
		url : "?model=engineering_role_esmrole&action=checkParent",
		data : "",
		async : false
	});
	var thisHeight = document.documentElement.clientHeight - 40;
	$('#esmroleGrid').treegrid({
		url : '?model=engineering_role_esmrole&action=treeJson&projectId=' + $("#projectId").val(),
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
				field : 'memberName',
				title : '��Ա����',
				width : 250
			},{
				field : 'activityName',
				title : '��������',
				width : 300
			},{
				field : 'jobDescription',
				title : '��ע˵��',
				width : 250
			//},{
			//	field : 'fixedRate',
			//	title : '�̶�Ͷ�����',
			//	width : 100
			}
		]],
		onContextMenu : function(e, row) {
			e.preventDefault();
			$(this).treegrid('unselectAll');
			$(this).treegrid('select', row.id);
			$('#menuDiv').menu('show', {
				left : e.pageX,
				top : e.pageY
			});
		}
	});
});

//ԭҳ��ˢ�·���
function show_page(){
	reload();
}

//ˢ��
function reload(){
	$('#esmroleGrid').treegrid('reload');
}

//�����ڵ�
function appendRole(){
	var node = $('#esmroleGrid').treegrid('getSelected');
	if(node){
		showThickboxWin("?model=engineering_role_esmrole&action=toAdd&parentId="
			+ node.id
			+ "&parentName=" + node.roleName
			+ "&projectId=" + $("#projectId").val()
			+ "&projectCode=" + $("#projectCode").val()
			+ "&projectName=" + $("#projectName").val()
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
	}else{
		showThickboxWin("?model=engineering_role_esmrole&action=toAdd&parentId=-1"
			+ "&parentName=��ɫ����"
			+ "&projectId=" + $("#projectId").val()
			+ "&projectCode=" + $("#projectCode").val()
			+ "&projectName=" + $("#projectName").val()
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
	}
}

//�༭�ڵ�
function editRole(){
	var node = $('#esmroleGrid').treegrid('getSelected');
	if (node){
		showThickboxWin("?model=engineering_role_esmrole&action=toEdit&id=" + node.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
	}else{
		alert('��ѡ��һ���ڵ�');
	}
}

//ɾ���ڵ�
function removeRole(){
	var node = $('#esmroleGrid').treegrid('getSelected');
	if (node){
		//�ж��Ƿ���Ŀ�����ǵĻ�������ɾ��
		if(node.isManager == 1){
			alert('��Ŀ�����������ɾ��');
			return false;
		}
		if(confirm('ȷ��Ҫɾ����')){
			//�첽ɾ���ڵ�
			$.ajax({
			    type: "POST",
			    url: "?model=engineering_role_esmrole&action=ajaxdeletes",
			    data: {
			    	id : node.id
			    },
			    async: false,
			    success: function(data){
			   		if(data == true){
						alert('ɾ���ɹ�');
						reload();
			   	    }else if(data == false){
						alert('ɾ��ʧ��');
						return false;
			   	    }else{
			   	    	data = eval("(" + data + ")");
			   	    	if(confirm("ɾ���ɹ�,���Ƴ���"+data[0].count+"���Ѳ�����Ŀ�ĳ�Ա,�Ƿ���д������뿪���ڣ�")){
							showThickboxWin("?model=engineering_member_esmentry&action=toLeaveList&ids="+data[0].ids
							+"&placeValuesBefore&TB_iframe=true&modal=false&height=452&width=838");
							}else{
								reload();
							}
			   	    }
				}
			});
		}
	}else{
		alert('��ѡ��һ���ڵ�');
	}
}

//ȡ��ѡ��
function cancelSelect(){
	$('#esmroleGrid').treegrid('unselectAll');
}

function importExcel() {
	$('#esmroleGrid').treegrid('getSelected');
	showThickboxWin("?model=engineering_role_esmrole&action=toEportExcelIn"
		+ "&projectId=" + $("#projectId").val()
		+ "&projectCode=" + $("#projectCode").val()
		+ "&projectName=" + $("#projectName").val()
		+ "&placeValuesBeforeTB_iframe=true&modal=false&height=400&width=600");
}