$(function() {
	var treeObj = $('#esmMyJobsGrid');
	var thisHeight = document.documentElement.clientHeight - 25;
	var thisWidth = document.documentElement.clientWidth - 8;

	treeObj.treegrid({
		title : '�ҵĹ�����־',
		width : thisWidth,
		height : 300,
		nowrap : false,
		rownumbers : true,
		animate : true,
		collapsible : true,
		idField : 'id',
		treeField : 'executionDate',//��������
		fitColumns : false,//�����Ӧ
		pagination : false,//��ҳ
		showFooter : true,//��ʾͳ��
		columns : [[
			{
				title : '����',
				field : 'executionDate',
				width : 120
			},{
				field : 'proName',
				title : 'ʡ',
				width : 70
			},{
				field : 'cityName',
				title : '��',
				width : 70
			},{
				field : 'workStatus',
				title : '����״̬',
				width : 80
			},{
				field : 'projectName',
				title : '��Ŀ',
				width : 120
			},{
				field : 'activityName',
				title : '����',
				width : 80
			},{
				field : 'workloadDay',
				title : '�����',
				width : 80
			},{
				field : 'process',
				title : '����',
				width : 80
			},{
				field : 'problem',
				title : '����������',
				width : 150
			},{
				field : 'feeTask',
				title : '����',
				width : 80
			}
		]],
		onBeforeLoad : function(row,param) {//��̬��ֵȡֵ·��
			if(row){
				if(row.id * 1 == row.id){
					$(this).treegrid('options').url =  '?model=engineering_activity_esmactivity&action=treeJson&projectId=' + $("#projectId").val() + "&parentId=" + row.id;
				}else{
					$(this).treegrid('options').url =  '?model=engineering_activity_esmactivity&action=treeJson&projectId=' + $("#projectId").val() + "&parentId=" + $("#parentId").val();
				}
			}
		},
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

//�����ڵ�
function append(){

	var treeObj = $('#esmMyJobsGrid');

	var myData = [{
		executionDate: '2012-7-19',
		id : 1
	}];

	var node = treeObj;
	treeObj.treegrid('append', {
		parent: (node ? node.id : null),
		data: myData
	});
}

//�༭�ڵ�
function edit(){
	var node = $('#esmMyJobsGrid').treegrid('getSelected');
	if (node){
//		$('#esmroleGrid').treegrid('beginEdit',node.id);
		showThickboxWin("?model=engineering_role_esmrole&action=toEdit&id=" + node.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
	}else{
		alert('��ѡ��һ���ڵ�');
	}
}


//ɾ���ڵ�
function remove(){
	var node = $('#esmMyJobsGrid').treegrid('getSelected');
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