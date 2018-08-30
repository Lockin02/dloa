$(function() {
	var treeObj = $('#esmMyJobsGrid');
	var thisHeight = document.documentElement.clientHeight - 25;
	var thisWidth = document.documentElement.clientWidth - 8;

	treeObj.treegrid({
		title : '我的工作日志',
		width : thisWidth,
		height : 300,
		nowrap : false,
		rownumbers : true,
		animate : true,
		collapsible : true,
		idField : 'id',
		treeField : 'executionDate',//树级索引
		fitColumns : false,//宽度适应
		pagination : false,//分页
		showFooter : true,//显示统计
		columns : [[
			{
				title : '日期',
				field : 'executionDate',
				width : 120
			},{
				field : 'proName',
				title : '省',
				width : 70
			},{
				field : 'cityName',
				title : '市',
				width : 70
			},{
				field : 'workStatus',
				title : '工作状态',
				width : 80
			},{
				field : 'projectName',
				title : '项目',
				width : 120
			},{
				field : 'activityName',
				title : '任务',
				width : 80
			},{
				field : 'workloadDay',
				title : '完成量',
				width : 80
			},{
				field : 'process',
				title : '进度',
				width : 80
			},{
				field : 'problem',
				title : '完成情况描述',
				width : 150
			},{
				field : 'feeTask',
				title : '费用',
				width : 80
			}
		]],
		onBeforeLoad : function(row,param) {//动态设值取值路径
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

//新增节点
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

//编辑节点
function edit(){
	var node = $('#esmMyJobsGrid').treegrid('getSelected');
	if (node){
//		$('#esmroleGrid').treegrid('beginEdit',node.id);
		showThickboxWin("?model=engineering_role_esmrole&action=toEdit&id=" + node.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
	}else{
		alert('请选择一个节点');
	}
}


//删除节点
function remove(){
	var node = $('#esmMyJobsGrid').treegrid('getSelected');
	if (node){
		//判断是否项目经理，是的话不允许删除
		if(node.isManager == 1){
			alert('项目经理不允许进行删除');
			return false;
		}
		if(confirm('确认要删除？')){
			//异步删除节点
			$.ajax({
			    type: "POST",
			    url: "?model=engineering_role_esmrole&action=ajaxdeletes",
			    data: {
			    	id : node.id
			    },
			    async: false,
			    success: function(data){
			   		if(data == "1"){
						alert('删除成功');
						reload();
			   	    }else{
						alert('删除失败');
						return false;
			   	    }
				}
			});
		}
	}else{
		alert('请选择一个节点');
	}
}