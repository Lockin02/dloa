var show_page = function(page) {
	$("#resourceGrid").yxgrid("reload");

	var nodes = treeObj.getSelectedNodes();
	treeObj.reAsyncChildNodes(nodes[0],'refresh');
};

var treeObj;

$(function() {
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_baseinfo_resource&action=checkParent",
	    data : "",
	    async: false
	});

	/******新树部分设置*********/

	//树基本设置
	var setting = {
		async : {
			enable : true,
			url : "?model=engineering_baseinfo_resource&action=getChildren",
			autoParam : ["id", "name=n"],
			otherParam : { 'rtParentType' : 1 }
		},
		callback : {
			onClick : clickFun,
			onAsyncSuccess : zTreeOnAsyncSuccess
		},
		view : {
			selectedMulti : false
		}
	};

	//加载树
	treeObj = $.fn.zTree.init($("#tree"), setting);

	//第一次加载的时候刷新根节点
	var firstAsy = true;
	// 加载成功后执行
	function zTreeOnAsyncSuccess() {
		if (firstAsy) {
			var treeObj = $.fn.zTree.getZTreeObj("tree");
			var nodes = treeObj.getNodes();
			if (nodes.length > 0) {
				treeObj.reAsyncChildNodes(nodes[0], "refresh");
			}
		}
		firstAsy = false;
	}

	//树的双击事件
	function clickFun(event, treeId, treeNode){

		var budgetGrid = $("#resourceGrid").data('yxgrid');
		budgetGrid.options.param['parentId']=treeNode.id;

		budgetGrid.reload();
		$("#parentId").val(treeNode.id);
	}

	$("#resourceGrid").yxgrid({
		model : 'engineering_baseinfo_resource',
		title : '资源目录',
		//列信息
		colModel : [
			{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'resourceCode',
				display : '资源编码',
				sortable : true
			}, {
				name : 'resourceName',
				display : '资源名称',
				sortable : true
			}, {
				name : 'parentId',
				display : '父节点id',
				sortable : true,
				hide : true
			}, {
				name : 'parentCode',
				display : '父节点编码',
				sortable : true,
				hide : true
			}, {
				name : 'parentName',
				display : '上级名称',
				sortable : true
			}, {
				name : 'isLeaf',
				display : '是否叶子节点',
				sortable : true,
				hide : true
			}, {
				name : 'price',
				display : '资源单价',
				sortable : true,
				process : function(v){
					if(v != 0.00){
						return v;
					}
				},
				width : 80
			}, {
				name : 'currencyUnit',
				display : '货币单位',
				sortable : true,
				width : 80
			}, {
				name : 'units',
				display : '计量单位',
				sortable : true,
				width : 80
			}, {
				name : 'budgetCode',
				display : '预算项目编码',
				sortable : true,
				hide : true
			}, {
				name : 'budgetName',
				display : '预算项目',
				sortable : true
			}, {
				name : 'orderNum',
				display : '排序号码',
				sortable : true,
				width : 80
			}, {
				name : 'status',
				display : '状态',
				sortable : true,
				process : function(v){
					if(v == 0){
						return '启用';
					}else{
						return '禁用';
					}
				},
				width : 80
			}, {
				name : 'remark',
				display : '备注',
				sortable : true,
				hide : true
			}
		],
		//新增
	 	toAddConfig : {
			toAddFn : function(p ,treeNode , treeId) {
				var c = p.toAddConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				showThickboxWin("?model="
					+ p.model
					+ "&action="
					+ c.action
					+ c.plusUrl
					+ "&parentId=" +$("#parentId").val(treeNode.id)
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
					+ h + "&width=" + w);
			}
		},
		menusEx : [{
			text : '禁用',
			icon : 'edit',
			showMenuFn:function(row){
				if(row.status == 0){
					return true;
				}
				return false;
			},
			action: function(row){
				if (window.confirm(("确定禁用吗？"))) {
					$.ajax({
						type : "POST",
						url : "?model=engineering_baseinfo_resource&action=changeStatus",
						data : { "id" : row.id , "status" : 1 },
						success : function(msg) {
									if( msg == 1 ){
										alert('禁用成功！');
						                $("#resourceGrid").yxgrid("reload");
									}else{
										alert('禁用失败！');
									}
								}
					});
				}
			}
		},
		{
			text : '启用',
			icon : 'edit',
			showMenuFn:function(row){
				if(row.status != 0){
					return true;
				}
				return false;
			},
			action: function(row){
				if (window.confirm(("确定启用吗？"))) {
					$.ajax({
						type : "POST",
						url : "?model=engineering_baseinfo_resource&action=changeStatus",
						data : { "id" : row.id , "status" : 0 },
						success : function(msg) {
									if( msg == 1 ){
										alert('启用成功！');
						                $("#resourceGrid").yxgrid("reload");
									}else{
										alert('启用失败！');
									}
								}
					});
				}
			}
		},{
			text : '删除',
			icon : 'delete',
			action : function(rowData, rows, rowIds, g) {
				$.ajax({
						type : "POST",
						url : "?model=engineering_baseinfo_resource&action=deleteCheck",
						data : { "rowData" : rowData },
						success : function(msg) {
									if( msg == 1 ){
										alert('对象已经被引用，不可以删除！');
									}else{
										g.options.toDelConfig.toDelFn(g.options,g);
						                $("#resourceGrid").yxgrid("reload");
									}
								}
					});
			}
		}],
		isDelAction : false,
		sortname : "resourceCode ASC,orderNum",
		sortorder : "ASC",
		searchitems : [{
				display : "资源编码",
				name : 'resourceCode'
			}, {
				display : "资源名称",
				name : 'resourceName'
			}]
	});
});