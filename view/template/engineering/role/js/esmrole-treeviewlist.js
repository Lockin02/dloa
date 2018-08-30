$(function() {
	//初始化树形
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
		title : '项目角色',
		width : '98%',
		height : thisHeight,
		nowrap : false,
		rownumbers : true,
		idField : 'id',
		treeField : 'roleName',//树级索引
		fitColumns : false,//宽度适应
		pagination : false,//分页
		showFooter : false,//显示统计
		columns : [[
			{
				title : '角色名称',
				field : 'roleName',
				width : 250,
				formatter:function(v,row){
					if(row.isManager == '1'){
                		return '<span class="blue" title="项目经理">'+v+'</span>';
				 	}else{
				 		return v;
				 	}
				}
			},{
				field : 'activityName',
				title : '工作任务',
				width : 300
			},{
				field : 'memberName',
				title : '成员名称',
				width : 200

			},{
				field : 'jobDescription',
				title : '备注说明',
				width : 250
			}
		]]
	});
});