$(function() {
	var thisHeight = document.documentElement.clientHeight - 40;
	var treeObj = $('#esmroleGrid');
	treeObj.treegrid({
		url : '?model=engineering_budget_esmbudget&action=treeJson',
		queryParams : {'projectId' : $("#projectId").val(),'budgetType' : 'budgetPerson'},
		title : '成员结构',
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
				title : '分组',
				field : 'resourceName',
				width : 150,
				formatter:function(v,row){
					if(row.isManager == '1'){
                		return '<span class="blue" title="项目经理">'+v+'</span>';
				 	}else{
				 		return v;
				 	}
				}
			},{
				field : 'budgetName',
				title : '级别',
				width : 100
			},{
				field : 'memberName',
				title : '成员名称',
				width : 100
			},{
				field : 'personLevel',
				title : '成员等级',
				width : 100
			},{
				field : 'budgetDay',
				title : '预计人天数',
				width : 100
			},{
				field : 'jobDescription',
				title : '具体指责及任务',
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

//原页面刷新方法
function show_page(){
	reload();
}

//刷新
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

//新增节点
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

//编辑节点
function editRole(){
	var node = $('#esmroleGrid').treegrid('getSelected');
	if (node){
		showThickboxWin("?model=engineering_budget_esmbudget&action=toEdit&id=" + node.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
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
			    url: "?model=engineering_budget_esmbudget&action=ajaxdeletes",
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

//取消选中
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