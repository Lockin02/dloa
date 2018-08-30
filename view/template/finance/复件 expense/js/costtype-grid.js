$(function() {
	var treeObj = $('#costTypeGrid');
	var thisHeight = document.documentElement.clientHeight - 40;

	treeObj.treegrid({
		title : ' 新费用类型',
		url : '?model=finance_expense_costtype&action=treeJson',
		width : '98%',
		height : thisHeight,
		nowrap : false,
		rownumbers : true,
		animate : false,
		collapsible : true,
		idField : 'id',
		treeField : 'CostTypeName',//树级索引
		fitColumns : false,//宽度适应
		pagination : false,//分页
		showFooter : false,//显示统计
		columns : [[
			{
				title : '费用类型名称',
				field : 'CostTypeName',
				width : 280
			},{
				field : 'k3Code',
				title : 'K3类型编码',
				width : 80
			},{
				field : 'k3Name',
				title : 'K3类型名称',
				width : 100
			},{
				field : 'invoiceTypeName',
				title : '默认发票类型',
				width : 80
			},{
				field : 'showDays',
				title : '显示天数',
				align : 'center',
				formatter:function(v,row){
					if(v == '1'){
                		return '√';
				 	}else{
				 		return '';
				 	}
				},
				width : 60
			},{
				field : 'isReplace',
				title : '允许替票',
				width : 60,
				align : 'center',
				formatter:function(v,row){
					if(v == '1'){
                		return '√';
				 	}else{
				 		return '';
				 	}
				}
			},{
				field : 'isEqu',
				title : '录入物料清单',
				width : 80,
				align : 'center',
				formatter:function(v,row){
					if(v == '1'){
                		return '√';
				 	}else{
				 		return '';
				 	}
				}
			},{
				field : 'isSubsidy',
				title : '是否补贴',
				width : 60,
				align : 'center',
				formatter:function(v,row){
					if(v == '1'){
                		return '√';
				 	}else{
				 		return '';
				 	}
				}
			},{
				field : 'isClose',
				title : '是否关闭',
				width : 60,
				align : 'center',
				formatter:function(v,row){
					if(v == '1'){
                		return '√';
				 	}else{
				 		return '';
				 	}
				}
			},{
				field : 'orderNum',
				title : '排序号',
				width : 50
			},{
				field : 'esmCountName',
				title : '工程统计别称',
				width : 80
			},{
				field : 'Remark',
				title : '备注信息',
				width : 120
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
	var node = $('#costTypeGrid').treegrid('getSelected');
	if (node){
		$('#costTypeGrid').treegrid('reload', node._parentId);
	} else {
		$('#costTypeGrid').treegrid('reload');
	}
}

//新增节点
function append(){
	if($("#isNewHidden").val() == "0"){
		alert('暂不支持在此列表添加费用类型');
	}else{
		var node = $('#costTypeGrid').treegrid('getSelected');
		if(node){
			showThickboxWin("?model=finance_expense_costtype&action=toAdd&ParentCostTypeID=" + node.id + "&ParentCostType=" + node.CostTypeName + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
		}else{
			showThickboxWin("?model=finance_expense_costtype&action=toAdd&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
		}
	}
}

//编辑节点
function edit(){
	var node = $('#costTypeGrid').treegrid('getSelected');
	if (node){
		showThickboxWin("?model=finance_expense_costtype&action=toEdit&id=" + node.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
	}else{
		alert('请选择一个节点');
	}
}


//删除节点
function remove(){
	var node = $('#costTypeGrid').treegrid('getSelected');
	if (node){
		if(node.isNew == "1"){
			if(confirm('确认要删除？')){
				//异步删除节点
				$.ajax({
				    type: "POST",
				    url: "?model=finance_expense_costtype&action=ajaxdeletes",
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
			alert('暂不能删除旧报销类型');
		}
	}else{
		alert('请选择一个节点');
	}
}