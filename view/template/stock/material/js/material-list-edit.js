$(function() {
	var treeObj = $('#materialGrid');
	var thisHeight = document.documentElement.clientHeight - 30;

	var productCode=$("#productCode").val();
	var productName=$("#productName").val();

	treeObj.treegrid({
		title : '物料【'+productCode+'-'+productName+'】BOM清单',
		width : '98%',
		height : thisHeight,
		nowrap : false,
		rownumbers : true,
		animate : true,
		collapsible : true,
		idField : 'id',
		treeField : 'productCode',//树级索引
		fitColumns : false,//宽度适应
		pagination : false,//分页
		showFooter : true,//显示统计
		columns : [[
			{
				title : '物料编码',
				field : 'productCode',
				width : 210,
				formatter : function(v,row) {
					if(row.id == 'noId') return v;
					if((row.rgt - row.lft) == 1){
						return v ;
					}else{
						return v ;
					}
				}
			},{
				field : 'productName',
				title : '物料名称',
				width : 250
			},{
				field : 'pattern',
				title : '规格型号',
				width : 250
			},{
				field : 'unitName',
				title : '单位',
				width : 60
			},{
				field : 'materialNum',
				title : '数量',
				width : 80
			}
		]],
		onBeforeLoad : function(row,param) {//动态设值取值路径
			if(row){
				if(row.id * 1 == row.id){
					$(this).treegrid('options').url =  '?model=stock_material_material&action=treeJson&parentProductID=' + $("#parentProductID").val() + "&parentId=" + row.id;
				}else{
					$(this).treegrid('options').url =  '?model=stock_material_material&action=treeJson&parentProductID=' + $("#parentProductID").val() + "&parentId=" + $("#parentId").val();
				}
			}else{
				$(this).treegrid('options').url =  '?model=stock_material_material&action=treeJson&parentProductID=' + $("#parentProductID").val() + "&parentId=" + $("#parentId").val();
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

//原页面刷新方法
function show_page(){
	reload();
}

//刷新
function reload(){
	$('#materialGrid').treegrid('reload');
}

//新增任务
function add(){
	var node = $('#materialGrid').treegrid('getSelected');
	if (node){
		//如果选中节点没有子节点，则提示
		if((node.rgt - node.lft) == 1){
				//暂时取消任务上下级任务
				showThickboxWin("?model=stock_material_material&action=toAdd&parentId="
					+ node.id + "&parentProductID=" + $("#parentProductID").val()
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=620&width=1000");
		}else{
			//暂时取消任务上下级任务
			showThickboxWin("?model=stock_material_material&action=toAdd&parentId="
				+ node.id + "&parentProductID=" + $("#parentProductID").val()
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=620&width=1000");
		}
	}else{
		showThickboxWin("?model=stock_material_material&action=toAdd&parentId=-1"
			+ "&parentProductID=" + $("#parentProductID").val()
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=620&width=1000");
	}
}

//编辑节点
function edit(){
	var node = $('#materialGrid').treegrid('getSelected');
	if (node){
		if((node.rgt - node.lft) == 1){
			showThickboxWin("?model=stock_material_material&action=toEdit&id="
				+ node.id  + "&skey=" + node.skey_
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=620&width=1000");
		}else{
			showThickboxWin("?model=stock_material_material&action=toEdit&id="
				+ node.id  + "&skey=" + node.skey_
				+ "&parentId=" + node._parentId
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
		}
	}else{
		alert('请选择一个节点');
	}
}

//删除任务
function removeActivity(){
	var node = $('#materialGrid').treegrid('getSelected');
	if (node){
		//判断提示信息
		if((node.rgt - node.lft) == 1){
			var alertText = '确认要删除？';
		}else{
			var alertText = '删除此物料，会将下级物料一并删除，确认要执行此操作吗？';
		}
		if(confirm(alertText)){
			//异步删除节点
			$.ajax({
			    type: "POST",
			    url: "?model=stock_material_material&action=ajaxdeletes",
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
	var node = $('#materialGrid').treegrid('unselectAll');
}