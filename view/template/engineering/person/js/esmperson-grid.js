var show_page = function(page) {
	$("#esmpersonGrid").yxgrid("reload");
};
$(function() {
	//设值树域的高度
	var thisHeight = document.documentElement.clientHeight - 40;
	$("#tree").height(thisHeight);

	var projectId = $("#projectId").val();

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
		var budgetGrid = $("#esmpersonGrid").data('yxgrid');
		budgetGrid.options.param['activityId']=treeNode.id;
		budgetGrid.options.param['lft']=treeNode.lft;
		budgetGrid.options.param['rgt']=treeNode.rgt;

		budgetGrid.reload();
		$("#activityId").val(treeNode.id);
		$("#activityName").val(treeNode.name);
		$("#lft").val(treeNode.lft);
		$("#rgt").val(treeNode.rgt);
	}

	$("#esmpersonGrid").yxgrid({
		model : 'engineering_person_esmperson',
		title : '人力预算',
		param : {
			"projectId" : projectId
		},
		noCheckIdValue : 'noId',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '项目编号',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '所属项目',
				sortable : true,
				hide : true
			}, {
				name : 'personLevel',
				display : '人员等级',
				sortable : true,
				process : function(v,row){
					if(row.id == 'noId') return v;
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_person_esmperson&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "</a>";
				}
			}, {
				name : 'number',
				display : '数量',
				sortable : true
			}, {
				name : 'planBeginDate',
				display : '加入项目',
				sortable : true
			}, {
				name : 'planEndDate',
				display : '离开项目',
				sortable : true
			}, {
				name : 'days',
				display : '天数',
				sortable : true
			}, {
				name : 'personDays',
				display : '人工天数(天)',
				sortable : true
			}, {
				name : 'personCostDays',
				display : '人力成本(天)',
				sortable : true
			}, {
				name : 'activityName',
				display : '所属任务',
				sortable : true
			}, {
				name : 'personCost',
				display : '人力成本金额(元)',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}],

		toAddConfig : {
			formWidth : 950,
			formHeight : 400,
			plusUrl : "&projectId=" + projectId,
			toAddFn : function(p, treeNode, treeId) {
				var activityId = $("#activityId").val();
				if(activityId == -1){
					alert('请先选择一个项目任务');
					return false;
				}
				var c = p.toAddConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				showThickboxWin("?model="
						+ p.model
						+ "&action="
						+ c.action
						+ c.plusUrl
						+ "&activityId="
						+ activityId
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ h + "&width=" + w);
			}
		},
		toEditConfig : {
			formWidth : 950,
			formHeight : 400,
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
			formHeight : 400,
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			},
			action : 'toView'
		},
		toDelConfig : {
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			}
		},
		buttonsEx : [{
				text : "批量新增",
				icon : 'add',
				name : 'batchAdd',
				action : function() {
					showThickboxWin("?model=engineering_person_esmperson&action=toAddBatch&projectId="
						+ projectId
						+ "&activityId="
						+ $("#activityId").val()
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=1000");
				}
			},{
				name : 'Add',
				text : "复制",
				icon : 'add',
				action : function(row, rows, idArr) {
					if (row) {
						idStr = idArr.toString();
						showThickboxWin("?model=engineering_person_esmperson"
							+ "&action=toCopy&ids="
							+ idStr
							+ "&projectId=" + projectId
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900"
							);
					} else {
						alert('请先选择记录');
					}
				}
			}],
		sortname : 'planBeginDate',
		sortorder : 'ASC',
		searchitems : {
			display : "人员级别",
			name : 'personLevel'
		}
	});
});