$(function() {
	var thisHeight = document.documentElement.clientHeight - 40;
	var treeObj = $('#esmroleGrid');
	treeObj.treegrid({
		url : '?model=engineering_budget_esmbudget&action=treeJson',
		queryParams : {'projectId' : $("#projectId").val(),'budgetType' : 'budgetPerson'},
		title : '��Ա�ṹ',
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
				title : '����',
				field : 'resourceName',
				width : 150,
				formatter:function(v,row){
					if(row.isManager == '1'){
                		return '<span class="blue" title="��Ŀ����">'+v+'</span>';
				 	}else{
				 		return v;
				 	}
				}
			},{
				field : 'budgetName',
				title : '����',
				width : 100
			},{
				field : 'memberName',
				title : '��Ա����',
				width : 100
			},{
				field : 'personLevel',
				title : '��Ա�ȼ�',
				width : 100
			},{
				field : 'budgetDay',
				title : 'Ԥ��������',
				width : 100
			},{
				field : 'jobDescription',
				title : '����ָ������',
				width : 400
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
	var responseText = $.ajax({
		url : 'index1.php?model=engineering_budget_esmbudget&action=treeJson',
		type : "POST",
		data : {'projectId' : $("#projectId").val()},
		async : false
	}).responseText;
	var o = eval("(" + responseText + ")");
	return o.collection;
	$('#esmroleGrid').treegrid('reload');
}

//�����ڵ�
function toAdd(){
	var node = $('#esmroleGrid').treegrid('getSelected');
	if(node){
		showOpenWin("?model=engineering_budget_esmbudget&action=toAddPerson&_parentId="
			+ node.id
			+ "&projectId=" + $("#projectId").val()
			+ "&projectCode=" + $("#projectCode").val()
			+ "&projectName=" + $("#projectName").val() ,1,500 ,900,'toAddPerson');
	}else{
		showOpenWin("?model=engineering_budget_esmbudget&action=toAddPerson&_parentId=-1"
			+ "&projectId=" + $("#projectId").val()
			+ "&projectCode=" + $("#projectCode").val()
			+ "&projectName=" + $("#projectName").val() ,1,500 ,900,'toAddPerson');
	}
}

//�༭�ڵ�
function editRole(){
	var node = $('#esmroleGrid').treegrid('getSelected');
	if (node){
		showThickboxWin("?model=engineering_budget_esmbudget&action=toEdit&id=" + node.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
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
			    url: "?model=engineering_budget_esmbudget&action=ajaxdeletes",
			    data: {
			    	id : node.id
			    },
			    async: false,
			    success: function(data){
			   		if(data == "1"){
						alert('ɾ���ɹ�');
						reload();
			   	    }else{
						alert('ɾ��ʧ��');
						return false;
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
	var node = $('#esmroleGrid').treegrid('unselectAll');
}

function importExcel() {
	var node = $('#esmroleGrid').treegrid('getSelected');
	showThickboxWin("?model=engineering_budget_esmbudget&action=toEportExcelIn"
		+ "&projectId=" + $("#projectId").val()
		+ "&projectCode=" + $("#projectCode").val()
		+ "&projectName=" + $("#projectName").val()
		+ "&placeValuesBeforeTB_iframe=true&modal=false&height=400&width=600")

}