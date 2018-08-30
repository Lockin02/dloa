// 用于新增/修改后回调刷新表格

var show_page = function(page) {
	$("#rentGrid").yxgrid('reload');
};

$(function() {

	$("#rentGrid").yxgrid({

		model : 'asset_daily_rent',
		title : '固定资产租赁信息',
		showcheckbox : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '表单编号',
			name : 'billNo',
			sortable : true,
			width : 120
		}, {
			display : '申请出租部门id',
			name : 'deptId',
			sortable : true,
			hide : true

		}, {
			display : '申请出租部门',
			name : 'deptName',
			sortable : true
		}, {
			display : '承租单位id',
			name : 'lesseeid',
			sortable : true,
			hide : true
		}, {
			display : '承租单位',
			name : 'lesseeName',
			sortable : true

		}, {
			display : '承租负责人',
			name : 'lessee',
			sortable : true
		}, {
			display : '租赁合同编号',
			name : 'contractNo',
			sortable : true,
			hide : true
		}, {
			display : '租赁数量',
			name : 'rentNum',
			sortable : true,
			hide : true
		},

		{
			display : '租赁总额',
			name : 'rentAmount',
			sortable : true
		}, {
			display : '租赁原因',
			name : 'reason',
			sortable : true
		}, {
			display : '申请人id',
			name : 'applicatId',
			sortable : true,
			hide : true
		}, {
			display : '申请人',
			name : 'applicat',
			sortable : true
		}, {
			display : '申请日期',
			name : 'applicatDate',
			sortable : true
		}, {
			display : '租赁日期',
			name : 'beginDate',
			sortable : true
		}, {
			display : '回收日期',
			name : 'endDate',
			sortable : true
		}, {
			display : '备注',
			name : 'remark',
			sortable : true,
			hide : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true
		}, {
			name : 'ExaDT',
			display : '审批时间',
			sortable : true
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true,
			hide : true
		}, {
			name : 'createId',
			display : '创建人id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '创建日期',
			sortable : true,
			hide : true
		}, {
			name : 'updateName',
			display : '录入人',
			sortable : true,
			hide : true
		}, {
			name : 'updateId',
			display : '修改人id',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '修改日期',
			sortable : true,
			hide : true
		}],
		//						toDelConfig : {
		//								showMenuFn : function(row) {
		//										if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
		//											return true;
		//												}
		//									return false;
		//									}
		//							},
		isDelAction : false,
		isViewAction : false,
		isEditAction : false,
		toAddConfig : {
			formWidth : 1000,
			/**
			 * 新增表单默认高度
			 */
			formHeight : 450
		},

		menusEx : [{
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定删除吗？"))) {

					$.ajax({
						type : "GET",
						url : "?model=asset_daily_rent&action=deletes&id="
								+ row.id,
						success : function(msg) {
							$("#rentGrid").yxgrid("reload");
						}
					});
				}
			}
		}, {
			name : 'view',
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_daily_rent&action=init&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=1000");
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			name : 'edit',
			text : '修改',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
					return true;
				} else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_daily_rent&action=init&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&&width=1000");
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'sumbit',
			text : '提交审批',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {

					showThickboxWin("controller/asset/daily/ewf_index_rent.php?actTo=ewfSelect&billId="
							+ row.id
							+ "&examCode=oa_asset_rent&formName=资产租赁审批"
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=780");

				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成'
						|| row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_rent&pid="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=700&width=900");
				}
			}
		}

		//					            ,{
		//									text : '签收',
		//									icon : 'edit',
		//									showMenuFn : function(row) {
		//										if (row.ExaStatus == "完成" && row.isSign=="未签收" ) {
		//											return true;
		//										}else {
		//										    return false;
		//										}
		//
		//									},
		//									action: function(row){
		//										if (window.confirm(("确定签收吗？"))) {
		//
		//							                 	$.ajax({
		//													type : "POST",
		//													url : "?model=asset_daily_allocation&action=sign&id="+ row.id,
		//													success : function(msg) {
		//															if( msg == 1 ){
		//																alert('签收成功！');
		//							                               	 $("#allocationGrid").yxgrid("reload");
		//															}else{
		//																alert('签收失败！');
		//															}
		//													}
		//												});
		//							                 }
		//										}
		//
		//							}
		],

		// 快速搜索
		searchitems : [{
			display : '表单编号',
			name : 'billNo'
		}, {
			display : '申请出租部门',
			name : 'deptName'
		}, {
			display : '承租单位',
			name : 'lesseeName'
		}],
		// 默认搜索字段名

		sortname : "id",
		// 默认搜索顺序

		sortorder : "DESC"

	});

});