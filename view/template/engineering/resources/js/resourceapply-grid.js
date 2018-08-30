var show_page = function(page) {
	$("#resourceapplyGrid").yxgrid("reload");
};
$(function() {
	$("#resourceapplyGrid").yxgrid({
		model : 'engineering_resources_resourceapply',
		title : '项目设备申请表',
		param : {confirmStatusArr : '3,4,5,7'},
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		isOpButton : false,
		showcheckbox : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'status',
				display : '待确认',
				sortable : true,
				align : 'center',
				process : function(v) {
					switch(v){
						case '3' : return '<img src="images/icon/cicle_yellow.png" title="待确认"/>';break;
						default : return '';break;
					}
				},
				width : 50
			}, {
				name : 'formNo',
				display : '申请单编号',
				sortable : true,
				width : 120,
				process : function(v, row) {
					return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_resourceapply&action=toView&id="
							+ row.id + '&skey=' + row.skey_ + "\",1,700,1100,"+row.id+")'>" + v + "</a>";
				}
			}, {
				name : 'applyUser',
				display : '申请人',
				sortable : true,
				width : 70
			}, {
				name : 'applyUserId',
				display : '申请人id',
				sortable : true,
				hide : true
			}, {
				name : 'deptName',
				display : '申请人部门',
				sortable : true,
				width : 70
			}, {
				name : 'applyDate',
				display : '申请日期',
				sortable : true,
				width : 70
			}, {
				name : 'applyTypeName',
				display : '申请类型',
				sortable : true,
				width : 70
			}, {
				name : 'getTypeName',
				display : '领用方式',
				sortable : true,
				width : 70
			}, {
				name : 'place',
				display : '设备使用地',
				sortable : true,
				width : 70
			}, {
				name : 'deptName',
				display : '所属部门',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '项目编号',
				sortable : true,
				width : 120
			}, {
				name : 'projectName',
				display : '项目名称',
				sortable : true,
				width : 120
			}, {
				name : 'managerName',
				display : '项目经理',
				sortable : true,
				width : 80
			}, {
				name : 'managerId',
				display : '项目经理id',
				sortable : true,
				hide : true
			}, {
				name : 'remark',
				display : '备注信息',
				sortable : true,
				width : 130,
				hide : true
			}, {
				name : 'ExaStatus',
				display : '审批状态',
				sortable : true,
				width : 75
			}, {
				name : 'ExaDT',
				display : '审批日期',
				sortable : true,
				width : 80
			}, {
				name : 'createName',
				display : '创建人',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true,
				hide : true
			}, {
				name : 'updateName',
				display : '修改人',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '修改时间',
				sortable : true,
				hide : true
			}, {
				name : 'confirmStatus',
				display : '单据状态',
				sortable : true,
				width : 80,
				process : function(v) {
					switch(v){
						case '3' : return '等待发货';break;
						case '4' : return '发货中';break;
						case '7' : return '撤回待确认';break;
						case '5' : return '完成';break;
					}
				}
			}, {
				name : 'status',
				display : '下达状态',
				sortable : true,
				width : 80,
				process : function(v) {
					switch(v){
						case '0' : return '未下达';break;
						case '1' : return '部分下达';break;
						case '2' : return '已下达';break;
						case '3' : return '待确认';break;
					}
				}
			}],
		toViewConfig : {
			toViewFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin("?model=engineering_resources_resourceapply&action=toView&id="
						+ row[p.keyField],1,700,1100,row.id);
			}
		},
		// 扩展右键菜单
		menusEx : [{
			text : '下达发货任务',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status != '2' && row.ExaStatus == '完成' ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=engineering_resources_task&action=toAdd&id="
						+ + row.id + "&skey="+ row['skey_'] ,1,700,1100,row.id);
				}
			}
//		},{
//			text : '确认新设备',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.status != '2' && row.ExaStatus == '完成' ) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if (row) {
//					showOpenWin("?model=engineering_resources_resourceapply&action=toConfirmDetail&id="
//						+ + row.id + "&skey="+ row['skey_'] ,1,600,1100,row.id);
//				}
//			}
		},{
			text : '发货修改确认',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '3') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=engineering_resources_resourceapply&action=toConfirmTaskNum&id="
						+ + row.id + "&skey="+ row['skey_'] ,1,700,1200,row.id);
				}
			}
		},{
			text : '撤回确认',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.confirmStatus == '7') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=engineering_resources_resourceapply&action=toConfirmBack&id="
						+ + row.id + "&skey="+ row['skey_'] ,1,700,1200,row.id);
				}
			}
		}],
		comboEx : [{
			text : '下达状态',
			key : 'statusArr',
			value : '0,1,3',
			data : [{
				text : '未下达',
				value : '0'
			}, {
				text : '部分下达',
				value : '1'
			}, {
				text : '已下达',
				value : '2'
			}, {
				text : '待确认',
				value : '3'
			}, {
				text : '未完成',
				value : '0,1,3'
			}]
		},{
			text : '单据状态',
			key : 'confirmStatus',
			data : [{
					text : '等待发货',
					value : '3'
				},{
					text : '发货中',
					value : '4'
				},{
					text : '撤回待确认',
					value : '7'
				},{
					text : '完成',
					value : '5'
				}]
			},{
		     text:'审核状态',
		     key:'ExaStatus',
		     type : 'workFlow',
		     value : "完成"
		}],
		searchitems : [{
			display : "申请单号",
			name : 'formNoSch'
		},{
			display : "项目编号",
			name : 'projectCodeSch'
		},{
			display : "项目名称",
			name : 'projectNameSch'
		}]
	});
});