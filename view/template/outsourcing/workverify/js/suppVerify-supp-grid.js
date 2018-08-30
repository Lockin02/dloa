var show_page = function(page) {
	$("#suppVerifyGrid").yxgrid("reload");
};
$(function() {
	$("#suppVerifyGrid").yxgrid({
		model : 'outsourcing_workverify_suppVerify',
		isEditAction:false,
		isDelAction:false,
		showcheckbox:false,
		param:{'createId':$("#createId").val()},
		bodyAlign:'center',
		title : '工作量确认单',
		//列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'formCode',
					display : '单据编号',
					width : 150,
					sortable : true,
					process : function(v, row) {
						return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_workverify_suppVerify&action=toView&id="
								+ row.id + "\",1)'>" + v + "</a>";
					}
				},{
					name : 'status',
					display : '状态',
					width : 70,
					sortable : true,
					process : function(v) {
						if (v == "1") {
							return "提交审批";
						} else if (v == "3") {
							return "已确认";
						} else if (v == "4") {
							return "关闭";
						} else if (v == "5") {
							return "审批完成";
						} else {
							return "未提交";
						}
					}
				},{
					name : 'formDate',
					display : '单据时间',
					sortable : true
				},{
					name : 'workMonth',
					display : '工作量月份',
					width:80,
					sortable : true
				},{
					name : 'endDate',
					display : '周期结束日期',
					sortable : true
				},{
					name : 'remark',
					display : '备注',
					width : 450,
					align:'left',
					sortable : true
				}],

          //下拉过滤
			comboEx : [{
				text : '状态',
				key : 'status',
				data : [{
						text : '未提交',
						value : '0'
					},{
						text : '提交审批',
						value : '1'
					},{
						text : '审批完成',
						value : '5'
					},{
						text : '关闭',
						value : '4'
					}]
				}
			],

          toAddConfig : {
			formWidth : 1000,
			formHeight : 500,
			toAddFn : function(p, g) {
				showModalWin("?model=outsourcing_workverify_suppVerify&action=toAdd");
			}
		},


		toEditConfig : {
			formWidth : 1000,
			formHeight : 500,
			toEditFn : function(p, g) {
					var get = g.getSelectedRow().data('data');
				showModalWin("?model=outsourcing_workverify_suppVerify&action=toEdit&id=" + get[p.keyField]);
			}
		},
		toViewConfig : {
//			action : 'toView',
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showOpenWin("?model=outsourcing_workverify_suppVerify&action=toView&id=" + get[p.keyField],'1');
				}
			}
		},
					// 扩展右键菜单

		menusEx : [{
				text : '编辑',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == '0') {
						return true;
					}
					return false;
				},
				action : function(row) {
					showModalWin("?model=outsourcing_workverify_suppVerify&action=toEdit&id=" +row.id);

				}

			},{
				text: '外包结算',
				icon: 'add',
				showMenuFn: function(row) {
					if (row.status == '5') {
						return true;
					}
					return false;
				},
				action: function(row) {
					showModalWin("?model=outsourcing_account_basic&action=toSuppVerifyAdd&suppVerifyId=" + row.id);

				}
			},{
				text : '提交审批',
				icon : 'add',
				showMenuFn : function(row) {
					if (row.status == '0') {
						return true;
					}
					return false;
				},
				action : function(row) {
					if (window.confirm(("确定要提交?"))) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_workverify_suppVerify&action=changeStatus",
							data : {
								id : row.id,
								status:1
							},
							success : function(msg) {
								if (msg == 1) {
									alert('提交成功！');
									$("#suppVerifyGrid").yxgrid("reload");
								}
							}
						});
					}
				}

			},{
				text : '删除',
				icon : 'delete',
				showMenuFn : function(row) {
					if (row.status == '0') {
						return true;
					}
					return false;
				},
				action : function(row) {
					if (window.confirm(("确定要删除?"))) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_workverify_suppVerify&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('删除成功！');
									$("#suppVerifyGrid").yxgrid("reload");
								}
							}
						});
					}
				}

			}]
	});
});