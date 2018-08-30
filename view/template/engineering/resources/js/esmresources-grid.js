var show_page = function(page) {
	$("#esmresourcesGrid").yxgrid("reload");

	//刷新tab
	reloadTab('项目概况');
};

//重新刷新tab
function reloadTab(thisVal){
	var tt = window.parent.$("#tt");
	var tb=tt.tabs('getTab',thisVal);
	if(tb!= null){
		tb.panel('options').headerCls = tb.panel('options').thisUrl;
	}
}

$(function() {
	//设值树域的高度
	var thisHeight = document.documentElement.clientHeight - 40;

	var projectId = $("#projectId").val();

	//实例化列表
	$("#esmresourcesGrid").yxgrid({
		model : 'engineering_resources_esmresources',
		action : 'managePageJson',
		title : '项目设备预算',
		param : {
			"projectId" : projectId
		},
		noCheckIdValue : 'noId',
		isDelAction : false,
		isAddAction : false,
		isOpButton : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'resourceTypeId',
				display : '设备类型id',
				sortable : true,
				hide : true
			}, {
				name : 'resourceTypeName',
				display : '设备类型',
				sortable : true
			},{
				name : 'resourceId',
				display : '设备id',
				sortable : true,
				hide : true
			}, {
				name : 'resourceCode',
				display : '设备编码',
				sortable : true,
				hide : true
			}, {
				name : 'resourceName',
				display : '设备名称',
				sortable : true,
				process : function(v,row){
					if(row.id == 'noId') return v;
					if(row.thisType == "0"){
						return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_resources_esmresources&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "</a>";
					}else{
						return "<a href='javascript:void(0)' style='color:red;' title='变更中的预算' onclick='showThickboxWin(\"?model=engineering_resources_esmresources&action=toViewChange&id=" + row.uid + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "</a>";
					}
				},
				width : 160
			}, {
				name : 'number',
				display : '数量',
				sortable : true,
				width : 60
			}, {
				name : 'unit',
				display : '单位',
				sortable : true,
				width : 60,
				hide : true
			}, {
				name : 'planBeginDate',
				display : '领用日期',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				width : 80
			}, {
				name : 'planEndDate',
				display : '归还日期',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				width : 80
			}, {
				name : 'beignTime',
				display : '开始使用时间',
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
				display : '结束使用时间',
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
				display : '使用天数',
				sortable : true,
				width : 70
			}, {
				name : 'price',
				display : '单设备折旧',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'amount',
				display : '设备成本',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
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
				name : 'activityId',
				display : '任务id',
				sortable : true,
				hide : true
			}, {
				name : 'activityName',
				display : '所属任务',
				sortable : true,
				hide : true
			}, {
				name : 'workContent',
				display : '工作内容',
				sortable : true,
            	width : 250,
				hide : true
			}, {
				name : 'remark',
				display : '备注说明',
				sortable : true,
				hide : true
			}, {
				name : 'applyNo',
				display : '申请单号',
				sortable : true,
				width : 120
			}, {
				name : 'status',
				display : '单据状态',
				sortable : true,
				width : 75,
				process : function(v){
					switch(v){
						case '0' : return '未处理';
						case '1' : return '处理中';
						case '2' : return '已处理';
						case '3' : return '已完成';
						default : return v;
					}
				},
				hide : true
			}, {
				name : 'ExaStatus',
				display : '审批状态',
				sortable : true,
				width : 75
			}, {
				name : 'sendNum',
				display : '已发出数量',
				sortable : true,
				width : 80
			}, {
				name : 'receviceNum',
				display : '确认接收数量',
				sortable : true,
				width : 80
			}
		],
		toAddConfig : {
			formWidth : 950,
			formHeight : 500,
			plusUrl : "&projectId=" + projectId,
			toAddFn : function(p, treeNode, treeId) {
				var canChange = true;
				//判断项目是否可以进行变更
				$.ajax({
				    type: "POST",
				    url: "?model=engineering_change_esmchange&action=hasChangeInfo",
				    data: {
				    	"projectId" : $("#projectId").val()
				    },
				    async: false,
				    success: function(data){
				   		if(data*1 == -1){
							canChange = false;
				   	    }
					}
				});

				//如果不可变更
				if(canChange == false){
					alert('项目变更审批中，请等待审批完成后再进行变更操作！');
					return false;
				}

				var activityId = $("#activityId").val();
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
						+ "&lft="
						+ $("#lft").val()
						+ "&rgt="
						+ $("#rgt").val()
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ h + "&width=" + w);
			}
		},
		toEditConfig : {
			showMenuFn : function(row) {
				return true;
			},
			formWidth : 950,
			formHeight : 500,
			toEditFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				var canChange = true;
				//判断项目是否可以进行变更
				$.ajax({
				    type: "POST",
				    url: "?model=engineering_change_esmchange&action=hasChangeInfo",
				    data: {
				    	"projectId" : $("#projectId").val()
				    },
				    async: false,
				    success: function(data){
				   		if(data*1 == -1){
							canChange = false;
				   	    }
					}
				});

				//如果不可变更
				if(canChange == false){
					alert('项目变更审批中，请等待审批完成后再进行变更操作！');
					return false;
				}
				if(row.thisType == "0"){
					return showThickboxWin("?model=engineering_resources_esmresources&action=toEdit&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=950");
				}else{
					return showThickboxWin("?model=engineering_resources_esmresources&action=toEditChange&id=" + row.uid + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=950");
				}
			}
		},
		toViewConfig : {
			showMenuFn : function(row) {
				return true;
			},
			formWidth : 900,
			formHeight : 400,
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				if(row.thisType == "0"){
					return showThickboxWin("?model=engineering_resources_esmresources&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=950");
				}else{
					return showThickboxWin("?model=engineering_resources_esmresources&action=toViewChange&id=" + row.uid + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=950");
				}
			}
		},
		// 扩展右键菜单
		menusEx : [{
			text : '删除',
			icon : 'delete',
			action : function(row) {
				if (row) {
					var canChange = true;
					var projectId = $("#projectId").val();
					//判断项目是否可以进行变更
					$.ajax({
					    type: "POST",
					    url: "?model=engineering_change_esmchange&action=hasChangeInfo",
					    data: {
					    	"projectId" : projectId
					    },
					    async: false,
					    success: function(data){
					   		if(data*1 == -1){
								canChange = false;
					   	    }
						}
					});

					//如果不可变更
					if(canChange == false){
						alert('项目变更审批中，请等待审批完成后再进行变更操作！');
						return false;
					}
					if(row.thisType == "0"){
						var id = row.id;
						var changeId = '';
					}else{
						var id = '';
						var changeId = row.uid;
					}
					if (window.confirm(("确定要删除?"))) {
						$.ajax({
							type : "POST",
							url : "?model=engineering_resources_esmresources&action=ajaxdeletes",
							data : {
								"id" : id,
								"changeId" : changeId,
				    			"projectId" : projectId
							},
							success : function(msg) {
								if (msg == 1) {
									alert('删除成功！');
									show_page();
								} else {
									alert("删除失败! ");
								}
							}
						});
					}
				}
			}
		}],
		buttonsEx : [{
			name : 'Add',
			text : "批量新增",
			icon : 'add',
			action : function(row, rows, idArr) {
				var canChange = true;
				//判断项目是否可以进行变更
				$.ajax({
				    type: "POST",
				    url: "?model=engineering_change_esmchange&action=hasChangeInfo",
				    data: {
				    	"projectId" : $("#projectId").val()
				    },
				    async: false,
				    success: function(data){
				   		if(data*1 == -1){
							canChange = false;
				   	    }
					}
				});

				//如果不可变更
				if(canChange == false){
					alert('项目变更审批中，请等待审批完成后再进行变更操作！');
					return false;
				}

				showThickboxWin("?model=engineering_resources_esmresources&action=toBatchAdd&projectId="
						+ projectId
						+ "&activityId="
						+ $("#activityId").val()
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=1000");
			}
		},{
			name : 'exportIn',
			text : "导入",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=engineering_resources_esmresources&action=toEportExcelIn&projectId="
						+ $("#projectId").val()
						+ "&activityId="
						+ $("#activityId").val()
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		},{
			text : "删除预算",
			icon : 'delete',
			name : 'batchAdd',
			action : function(row,rows,idArr ) {
				if(row){
					var canChange = true;
					//判断项目是否可以进行变更
					$.ajax({
					    type: "POST",
					    url: "?model=engineering_change_esmchange&action=hasChangeInfo",
					    data: {
					    	"projectId" : $("#projectId").val()
					    },
					    async: false,
					    success: function(data){
					   		if(data*1 == -1){
								canChange = false;
					   	    }
						}
					});

					//如果不可变更
					if(canChange == false){
						alert('项目变更审批中，请等待审批完成后再进行变更操作！');
						return false;
					}
					if(confirm('确认删除选中的预算项么？')){
						var idArr = [];//正常id
						var changeIdArr = []; //变更的id
						for(var i = 0;i < rows.length ; i++){
							if(rows[i].thisType == "0"){
								idArr.push(rows[i].id);
							}else{
								changeIdArr.push(rows[i].uid);
							}
						}
						$.ajax({
							type : "POST",
							url : "?model=engineering_resources_esmresources&action=ajaxdeletes",
							data : {
								"id" : idArr.toString() ,
								"changeId" : changeIdArr.toString(),
								"projectId" : projectId
							},
							success : function(msg) {
								if (msg == 1) {
									alert('删除成功！');
									show_page(1);
								}else{
									alert('删除失败!');
								}
							}
						});
					}
				}else{
					alert('请先选择至少一条记录');
				}
			}
		}],
		searchitems : [{
				display : "设备名称",
				name : 'resourceNameSearch'
			}
		],
		sortorder : 'ASC'
	});
});