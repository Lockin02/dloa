var show_page = function(page) {
	$("#epersonGrid").yxgrid("reload");

	var nodes = treeObj.getSelectedNodes();
	treeObj.reAsyncChildNodes(nodes[0],'refresh');
};

//树对象缓存
var treeObj;

$(function() {
	//检查是否存在根节点，没有则新增
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_baseinfo_eperson&action=checkParent",
	    data : "",
	    async: false
	});

	/******新树部分设置*********/

	//树基本设置
	var setting = {
		async : {
			enable : true,
			url : "?model=engineering_baseinfo_eperson&action=getChildren",
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


	//表格部分处理
	$("#epersonGrid").yxgrid({
		model : 'engineering_baseinfo_eperson',
		title : '人力预算项目',
		isDelAction : false,
		//列信息
		colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        }, {
            name : 'personLevel',
            display : '人员等级',
            sortable : true,
            process : function(v,row){
                return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_baseinfo_eperson&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
            },
            width : 80
        }, {
            name : 'parentName',
            display : '上级名称',
            sortable : true,
        hide : true
        }, {
            name : 'isLeaf',
            display : '是否叶子节点',
            sortable : true,
            hide : true
        }, {
            name : 'price',
            display : '单价',
            sortable : true,
            width : 80
        }, {
            name : 'number',
            display : '数量',
            sortable : true,
            hide : true
        }, {
            name : 'money',
            display : '金额',
            sortable : true,
            hide : true
        }, {
            name : 'unit',
            display : '单位',
            sortable : true,
            width : 70
        }, {
            name : 'coefficient',
            display : '计量系数',
            sortable : true,
            width : 70
        }, {
            name : 'orderNum',
            display : '排序号',
            sortable : true,
            width : 70
        }, {
            name : 'customPrice',
            display : '自定义单价',
            sortable : true,
            width : 70,
            process : function(v){
                return v == "1" ? "<span class='blue'>是</span>" : "否";
            }
        }, {
            name : 'status',
            display : '状态',
            sortable : true,
            width : 70,
            process : function(v){
                 if (v == 0) {
                     return "启用";
                 }else if(v == 1){
                     return "禁用";
                 }else{
                     return "数据出错";
                 }
            }
        }, {
            name : 'remark',
            display : '备注',
            sortable : true,
            width : 150
        },{
            name : 'nonFamilyShort',
            display : '非家庭和办事处城市参加项目(短期)',
            sortable : true,
            width : 200
        },{
            name : 'nonFamilyLong',
            display : '非家庭和办事处城市参加项目(长期)',
            sortable : true,
            width : 200
        },{
            name : 'adminProject',
            display : '归属行政中心或办事处所在城市参与项目',
            sortable : true,
            width : 300
        },{
            name : 'familyProject',
            display : '家庭所在城市参与项目',
            sortable : true,
            width : 150
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
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
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
						url : "?model=engineering_baseinfo_eperson&action=changeStatus",
						data : { "id" : row.id , "status" : 1 },
						success : function(msg) {
							if( msg == 1 ){
								alert('禁用成功！');
				                $("#epersonGrid").yxgrid("reload");
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
						url : "?model=engineering_baseinfo_eperson&action=changeStatus",
						data : { "id" : row.id , "status" : 0 },
						success : function(msg) {
							if( msg == 1 ){
								alert('启用成功！');
				                $("#epersonGrid").yxgrid("reload");
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
						url : "?model=engineering_baseinfo_eperson&action=deleteCheck",
						data : { "rowData" : rowData },
						success : function(msg) {
									if( msg == 1 ){
										alert('对象已经被引用，不可以删除！');
									}else{
										g.options.toDelConfig.toDelFn(g.options,g);
						                $("#epersonGrid").yxgrid("reload");
									}
								}
					});
			}
		}],
		searchitems : {
			display : "搜索字段",
			name : 'XXX'
		},
		sortorder : "ASC",
		sortname : "orderNum"
	});
});