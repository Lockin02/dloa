var show_page = function(page) {
	$("#esmchangeGrid").yxgrid("reload");
};

$(function() {
	$("#esmchangeGrid").yxgrid({
		model : 'engineering_change_esmchange',
		title : '项目变更申请单',
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		isOpButton : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '项目编号',
				sortable : true
			}, {
				name : 'projectName',
				display : '项目名称',
				sortable : true
			}, {
				name : 'newBudgetAll',
				display : '总预算(新)',
				sortable : true,
				process : function(v,row){
					if(v != row.orgBudgetAll){
						return "<span class='red'>" + moneyFormat2(v) + "</span>";
					}
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'orgBudgetAll',
				display : '总预算(旧)',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'newBudgetField',
				display : '现场预算(新)',
				sortable : true,
				process : function(v,row){
					if(v != row.orgBudgetField){
						return "<span class='red'>" + moneyFormat2(v) + "</span>";
					}
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'orgBudgetField',
				display : '现场预算(旧)',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'newBudgetPerson',
				display : '人力预算(新)',
				sortable : true,
				process : function(v,row){
					if(v != row.orgBudgetPerson){
						return "<span class='red'>" + moneyFormat2(v) + "</span>";
					}
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'orgBudgetPerson',
				display : '人力预算(旧)',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'newBudgetEqu',
				display : '设备预算(新)',
				sortable : true,
				process : function(v,row){
					if(v != row.orgBudgetEqu){
						return "<span class='red'>" + moneyFormat2(v) + "</span>";
					}
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'orgBudgetEqu',
				display : '设备预算(旧)',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'newBudgetOutsourcing',
				display : '外包预算(新)',
				sortable : true,
				process : function(v,row){
					if(v != row.orgBudgetOutsourcing){
						return "<span class='red'>" + moneyFormat2(v) + "</span>";
					}
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'orgBudgetOutsourcing',
				display : '外包预算(旧)',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 70
			}, {
				name : 'actEndDate',
				display : '项目结束日期',
				sortable : true,
				width : 80
			}, {
				name : 'changeTimes',
				display : '变更次数',
				sortable : true,
				hide : true,
				width : 80
			}, {
				name : 'applyName',
				display : '变更申请人',
				sortable : true
			}, {
				name : 'applyDate',
				display : '申请日期',
				sortable : true,
				width : 70
			}, {
				name : 'ExaStatus',
				display : '审批状态',
				sortable : true,
				width : 60
			}, {
				name : 'ExaDT',
				display : '审批日期',
				sortable : true,
				width : 80,
				hide : true
			}
		],
		toViewConfig : {
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=engineering_change_esmchange&action=toView&id=" + rowData[p.keyField],1 );
			}
		},
		// 扩展右键菜单
		menusEx : [{
//			text : '提交审批',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.ExaStatus == "待提交") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				$.ajax({
//				    type: "POST",
//				    url: "?model=engineering_project_esmproject&action=getRangeId",
//				    data: {'projectId' : row.projectId },
//				    async: false,
//				    success: function(data){
//				   		if(data != ''){
//							showThickboxWin('controller/engineering/change/ewf_index.php?actTo=ewfSelect&billId='
//								+ row.id + "&billArea=" + data
//								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
//						}else{
//							showThickboxWin('controller/engineering/change/ewf_index.php?actTo=ewfSelect&billId='
//								+ row.id
//								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
//						}
//					}
//				});
//			}
//		}, {
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if ((row.ExaStatus != "待提交")) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_esm_change_baseinfo&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600");
				}
			}
		}],
		// 审批状态数据过滤
		comboEx : [{
			text: "审批状态",
			key: 'ExaStatus',
			type : 'workFlow'
		}],
		searchitems : [{
				display : "项目编号",
				name : 'projectCode'
			}, {
				display : "项目名称",
				name : 'projectName'
			}, {
				display : "变更申请人",
				name : 'applyName'
			}]
	});
});