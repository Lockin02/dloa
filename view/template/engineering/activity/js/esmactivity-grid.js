var show_page = function() {
	$("#esmactivityGrid").yxgrid("reload");

	var nodes = treeObj.getSelectedNodes();
	treeObj.reAsyncChildNodes(nodes[0],'refresh');

	//清空预算的路径，用于重新刷新
	self.parent.$("#iframe5").attr('src','');
};

var treeObj;

$(function() {
	var projectId = $("#projectId").val();

    //更新试用类的任务信息
	$.ajax({
		type : "POST",
		url : "?model=engineering_activity_esmactivity&action=updateTriActivity",
		data : {"projectId":projectId}
	});

	/******新树部分设置*********/

	//树基本设置
	var setting = {
		async : {
			enable : true,
			url : "?model=engineering_activity_esmactivity&action=getChildren&projectId=" + projectId,
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

		var budgetGrid = $("#esmactivityGrid").data('yxgrid');
		budgetGrid.options.param['parentId']=treeNode.id;

		budgetGrid.reload();
		$("#parentId").val(treeNode.id);
	}

	//项目范围
	$("#esmactivityGrid").yxgrid({
		model : 'engineering_activity_esmactivity',
		title : '项目任务',
		param : {
			"projectId" : $("#projectId").val()
		},
		showcheckbox : false,
		isDelAction : false,
		isAddAction : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'activityName',
				display : '任务名称',
				sortable : true,
				process : function(v,row){
					if(row.id == 'noId') return v;
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_activity_esmactivity&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
				},
				width : 130
			}, {
				name : 'workRate',
				display : '工作占比',
				sortable : true,
				process : function(v){
					return v + "%";
				},
				width : 60
			}, {
				name : 'parentId',
				display : '上级Id',
				sortable : true,
				hide : true
			}, {
				name : 'parentCode',
				display : '上级编码',
				sortable : true,
				hide : true
			}, {
				name : 'parentName',
				display : '上级名称',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '项目编号',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '项目名称',
				sortable : true,
				hide : true
			}, {
				name : 'planBeginDate',
				display : '预计开始',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				width : 80
			}, {
				name : 'planEndDate',
				display : '预计结束',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				width : 80
			}, {
				name : 'days',
				display : '预计工期',
				sortable : true,
				width : 60
			}, {
				name : 'actBeginDate',
				display : '实际开始',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				width : 80
			}, {
				name : 'actEndDate',
				display : '实际结束',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				width : 80
			}, {
				name : 'actDays',
				display : '实际工期',
				sortable : true,
				width : 70
			}, {
				name : 'workedDays',
				display : '已实施天数',
				sortable : true,
				width : 70
			}, {
				name : 'needDays',
				display : '预计还需',
				sortable : true,
				width : 60
			}, {
				name : 'process',
				display : '完成进度',
				sortable : true,
				process : function(v){
					return v + "%";
				},
				width : 60
			}, {
				name : 'workContent',
				display : '工作内容',
				sortable : true,
				width : 300,
				hide : true
			}],
		menusEx : [{
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					//验证时候已经被关联
					$.ajax({
						type : "POST",
						url : "?model=engineering_resources_esmresources&action=checkHasResourceInAct",
						data : {
							activityId : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('该活动已被资源计划关联，不能删除');
								return false;
							} else {
								if (window.confirm("删除节点会把其子节点一同删除.确认要删除?")) {
									$.ajax({
										type : "POST",
										url : "?model=engineering_activity_esmactivity&action=ajaxdeletes",
										data : {
											id : row.id,
											key : row['skey_']
										},
										success : function(msg) {
											if (msg == 1) {
												alert('删除成功!');
												show_page();
											} else {
												alert('删除失败!');
											}
										}
									});
								}
							}
						}
					});
				}
			}
		}],
		//新增
		toAddConfig : {
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			},
			formWidth : 1000,
			formHeight : 500,
			plusUrl : "&projectId=" + projectId,
			toAddFn : function(p, treeNode, treeId) {
				var c = p.toAddConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				showThickboxWin("?model="
					+ p.model
					+ "&action="
					+ c.action
					+ c.plusUrl
					+ "&parentId="
					+ $("#parentId").val(treeNode.id)
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
					+ h + "&width=" + w);
			}
		},
		toEditConfig : {
			formWidth : 1000,
			formHeight : 500,
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			},
			action : 'toEdit'
		},
		toViewConfig : {
			formWidth : 900,
			formHeight : 500,
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			},
			action : 'toView'
		},
		sortname : 'c.planBeginDate',
		sortorder : 'ASC'
	});
});