var show_page = function(page) {
	$("#budgetGrid").yxgrid("reload");

	var nodes = treeObj.getSelectedNodes();
	treeObj.reAsyncChildNodes(nodes[0],'refresh');
};

//树对象缓存
var treeObj;

$(function() {

	$.ajax({
	    type: "POST",
	    url: "?model=engineering_baseinfo_budget&action=checkParent",
	    data : "",
	    async: false
	});

	/******新树部分设置*********/

	//树基本设置
	var setting = {
		async : {
			enable : true,
			url : "?model=engineering_baseinfo_budget&action=getChildren",
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

		var budgetGrid = $("#budgetGrid").data('yxgrid');
		budgetGrid.options.param['parentId']=treeNode.id;

		budgetGrid.reload();
		$("#parentId").val(treeNode.id);
	}


    $("#budgetGrid").yxgrid({
        model : 'engineering_baseinfo_budget',
        title : '预算项目',
        //列信息
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        }         ,{
            name : 'budgetCode',
            display : '预算编码',
            sortable : true
        }         ,{
            name : 'budgetName',
            display : '预算名称',
            sortable : true
        }         ,{
            name : 'parentId',
            display : '父节点id',
            sortable : true,
            hide:true
        }         ,{
            name : 'parentCode',
            display : '父节点编码',
            sortable : true,
            hide:true
        }         ,{
            name : 'parentName',
            display : '上级名称',
            sortable : true
        }         ,{
            name : 'currencyUnit',
            display : '货币单位',
            sortable : true
        }         ,{
            name : 'subjectName',
            display : '科目名称',
            sortable : true
        }         ,{
            name : 'subjectCode',
            display : '科目编码',
            sortable : true,
            hide:true
        }         ,{
            name : 'budgetType',
            display : '费用类型',
            datacode : 'FYLX',
            sortable : true
        }         ,{
            name : 'orderNum',
            display : '排序顺序号',
            sortable : true
        }         ,{
            name : 'isLeaf',
            display : '是否叶子节点',
            sortable : true,
            hide:true
        }         ,{
            name : 'remark',
            display : '备注',
			hide:true,
            sortable : true
        }         ,{
            name : 'status',
            display : '状态',
            sortable : true,
            process : function(v){
				 if (v == 0) {
                     return "启用";
                 }else if(v == 1){
                     return "禁用";
                 }else{
                     return "数据出错";
                 }
            }
        }],
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
						url : "?model=engineering_baseinfo_budget&action=changeStatus",
						data : { "id" : row.id , "status" : 1 },
						success : function(msg) {
							if( msg == 1 ){
								alert('禁用成功！');
				                $("#budgetGrid").yxgrid("reload");
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
						url : "?model=engineering_baseinfo_budget&action=changeStatus",
						data : { "id" : row.id , "status" : 0 },
						success : function(msg) {
							if( msg == 1 ){
								alert('启用成功！');
				                $("#budgetGrid").yxgrid("reload");
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
						url : "?model=engineering_baseinfo_budget&action=deleteCheck",
						data : { "rowData" : rowData },
						success : function(msg) {
									if( msg == 1 ){
										alert('对象已经被引用，不可以删除！');
									}else{
										g.options.toDelConfig.toDelFn(g.options,g);
						                $("#budgetGrid").yxgrid("reload");
									}
								}
					});
			}
		}],
		sortorder : "ASC",
		sortname : "budgetCode",
		isDelAction : false,
		searchitems : [{
				display : "预算编码",
				name : 'budgetCode'
			}, {
				display : "预算名称",
				name : 'budgetName'
			}]
    });
});