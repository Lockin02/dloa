$(function() {
	var projectId = $("#projectId").val();

    //更新试用类的任务信息
    $.ajax({
        type : "POST",
        url : "?model=engineering_activity_esmactivity&action=updateTriActivity",
        data : {"projectId":projectId}
    });

	/******新树部分设置*********/
	//设值树域的高度
	var thisHeight = document.documentElement.clientHeight - 40;
	$("#tree").height(thisHeight);

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

	$("#esmactivityGrid").yxgrid({
		model : 'engineering_activity_esmactivity',
		title : '项目任务',
		param : {
			"projectId" : $("#projectId").val()
		},
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
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
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_activity_esmactivity&action=toViewPEdit&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "</a>";
				},
				width : 130
			}, {
				name : 'workRate',
				display : '工作占比',
				sortable : true,
				process : function(v){
					return v + "%";
				},
				width : 70
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
				width : 70
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
				width : 70
			}, {
				name : 'process',
				display : '完成进度',
				sortable : true,
				width : 70,
				process : function(v){
					return v + "%";
				}
			}, {
				name : 'workContent',
				display : '工作内容',
				sortable : true,
				width : 300,
				hide : true
			}],
		toViewConfig : {
			formWidth : 900,
			formHeight : 400,
			showMenuFn : function(row) {
				if (row.id != "noId") {
					return true;
				}
				return false;
			},
			action : 'toView'
		},
		sortname : 'c.planBeginDate',
		sortorder : 'ASC'
	});
});