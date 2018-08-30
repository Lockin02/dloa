$(function() {
	//初始化树形
	$.ajax({
		type : "POST",
		url : "?model=engineering_role_esmrole&action=checkParent",
		data : "",
		async : false
	});
	var thisHeight = document.documentElement.clientHeight - 40;
	$('#esmroleGrid').treegrid({
		url : '?model=engineering_role_esmrole&action=treeJson&projectId=' + $("#projectId").val(),
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
				field : 'memberName',
				title : '成员名称',
				width : 250
			},{
				field : 'activityName',
				title : '工作任务',
				width : 300
			},{
				field : 'jobDescription',
				title : '备注说明',
				width : 250
			//},{
			//	field : 'fixedRate',
			//	title : '固定投入比例',
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

//原页面刷新方法
function show_page(){
	reload();
}

//刷新
function reload(){
	$('#esmroleGrid').treegrid('reload');
}

//新增节点
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
			+ "&parentName=角色名称"
			+ "&projectId=" + $("#projectId").val()
			+ "&projectCode=" + $("#projectCode").val()
			+ "&projectName=" + $("#projectName").val()
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
	}
}

//编辑节点
function editRole(){
	var node = $('#esmroleGrid').treegrid('getSelected');
	if (node){
		showThickboxWin("?model=engineering_role_esmrole&action=toEdit&id=" + node.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
	}else{
		alert('请选择一个节点');
	}
}

//删除节点
function removeRole(){
	var node = $('#esmroleGrid').treegrid('getSelected');
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
			   		if(data == true){
						alert('删除成功');
						reload();
			   	    }else if(data == false){
						alert('删除失败');
						return false;
			   	    }else{
			   	    	data = eval("(" + data + ")");
			   	    	if(confirm("删除成功,你移除了"+data[0].count+"个已参与项目的成员,是否填写加入和离开日期？")){
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
		alert('请选择一个节点');
	}
}

//取消选中
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