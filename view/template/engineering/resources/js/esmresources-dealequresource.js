var show_page = function(page) {
	$("#esmresourcesGrid").yxgrid("reload");
};

$(function() {
	var projectId = $("#projectId").val();

	/******新树部分设置*********/
	//树基本设置
	var setting = {
		async : {
			enable : true,
			url : "?model=engineering_resources_esmresources&action=resourcesTree&projectId=" + projectId + "&resourceNature=GCXMZYXZ-02" ,
			autoParam : ["id"],
			otherParam : { 'rtParentType' : 1 }
		},
		callback : {
			onClick : clickFun,
			onAsyncSuccess : zTreeOnAsyncSuccess
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
			firstAsy = false;
		}
	}

	//树的双击事件
	function clickFun(event, treeId, treeNode){

		var budgetGrid = $("#esmresourcesGrid").data('yxgrid');
		if(treeNode.code == 'root'){
			budgetGrid.options.param['resourceCode']= '' ;
		}else{
			budgetGrid.options.param['resourceCode']=treeNode.code;
		}


		budgetGrid.reload();
	}

	//实例化列表
	$("#esmresourcesGrid").yxgrid({
		model : 'engineering_resources_esmresources',
		action : 'pageJsonOrg',
		title : '项目资源计划详细处理',
		param : {
			"projectId" : projectId,
			"resourceNature" : 'GCXMZYXZ-02'
		},
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		customCode : 'esmresourcesGridDeal',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'resourceId',
				display : '资源id',
				sortable : true,
				hide : true
			}, {
				name : 'resourceCode',
				display : '资源编码',
				sortable : true,
				hide : true
			}, {
				name : 'resourceName',
				display : '资源名称',
				sortable : true
			}, {
				name : 'resourceTypeId',
				display : '资源类型id',
				sortable : true,
				hide : true
			}, {
				name : 'resourceTypeName',
				display : '资源类型',
				sortable : true,
				hide : true
			},{
			    name : 'resourceNature',
			    display : '资源性质',
			    sortable : true,
			    datacode : 'GCXMZYXZ',
			    width : 70
			}, {
				name : 'activityId',
				display : '活动id',
				sortable : true,
				hide : true
			}, {
				name : 'activityName',
				display : '活动名称',
				sortable : true
			}, {
				name : 'number',
				display : '数量',
				sortable : true,
				width : 50
			}, {
				name : 'unit',
				display : '单位',
				sortable : true,
				width : 50
			}, {
				name : 'planBeginDate',
				display : '需求开始',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				width : 70
			}, {
				name : 'planEndDate',
				display : '需求结束',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				width : 70
			}, {
				name : 'beignTime',
				display : '开始使用',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				hide : true,
				width : 80
			}, {
				name : 'endTime',
				display : '结束使用',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				hide : true,
				width : 80
			}, {
				name : 'useDays',
				display : '天数',
				sortable : true,
				width : 50
			}, {
				name : 'projectId',
				display : '项目id',
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
				name : 'workContent',
				display : '工作内容',
				sortable : true,
            	width : 150
			}, {
				name : 'remark',
				display : '备注说明',
				sortable : true,
				hide : true
			}, {
				name : 'dealStatus',
				display : '处理状态',
				width : 70,
				datacode : 'GCZYCLZT'
			}, {
				name : 'dealManName',
				display : '处理人',
				width : 90
			}, {
				name : 'dealDate',
				display : '处理日期',
				width : 70
			}, {
				name : 'dealResult',
				display : '处理反馈',
				width : 150
			}
		],
		buttonsEx : [{
			name : 'deal',
			text : "处理",
			icon : 'edit',
			action : function(row, rows, idArr) {
				if (row) {
					idStr = idArr.toString();
					showThickboxWin("?model=engineering_resources_esmresources"
						+ "&action=toDeal&ids="
						+ idStr
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900"
						);
				} else {
					alert('请先选择记录');
				}
			}
		},{
			name : 'Add',
			text : "返回",
			icon : 'edit',
			action : function(row, rows, idArr) {
				location.href = "?model=engineering_project_esmproject&action=waitDealEquProject";
			}
		}],
		// 审批状态数据过滤
		comboEx : [{
				text : '处理状态',
				key : 'dealStatus',
				datacode : 'GCZYCLZT'
			}
		],
		searchitems : [{
				display : "资源名称",
				name : 'resourceNameSearch'
			}
		],
		sortname : 'c.resourceCode asc,c.planBeginDate',
		sortorder : 'ASC'
	});
});