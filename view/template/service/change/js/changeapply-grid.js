var show_page = function(page) {
	$("#changeapplyGrid").yxsubgrid("reload");
};
/**
 * 查看维修申请单具体信息
 *
 * @param {}
 *            id
 */
function viewApplyDetail(mainId) {
	var skey = "";
	$.ajax({
				type : "POST",
				url : "?model=service_repair_repairapply&action=md5RowAjax",
				data : {
					"id" : mainId
				},
				async : false,
				success : function(data) {
					skey = data;
				}
			});
	showModalWin("?model=service_repair_repairapply&action=toView&id="
			+ mainId
			+ "&skey="
			+ skey
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
}
$(function() {
	$("#changeapplyGrid").yxsubgrid({
		model : 'service_change_changeapply',
		title : '设备更换申请单',
		isDelAction : false,
		isEditAction : false,
		isViewAction : false,
		showcheckbox : false,
		param : {
			applyUserCode : $("#userId").val()
		},
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'docCode',
					display : '单据编号',
					sortable : true
				}, {
					name : 'rObjCode',
					display : '源单业务编号',
					sortable : true,
					hide : true
				}, {
					name : 'relDocCode',
					display : '源单编号',
					sortable : true,
					process : function(v, row) {
						if (row.relDocType == 'WXSQD') {
							return "<a href='#' onclick='viewApplyDetail("
									+ row.relDocId
									+ ")' >"
									+ v
									+ " <img title='' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
						} else {
							return v;
						}

					}
				}, {
					name : 'relDocName',
					display : '源单名称',
					sortable : true,
					hide : true
				}, {
					name : 'relDocType',
					display : '源单类型',
					sortable : true,
					process : function(v) {
						if (v == 'WXSQD') {
							return "维修申请单";
						} else {
							return "";
						}
					}
				}, {
					name : 'customerName',
					display : '客户名称',
					sortable : true,
					width : 200
				}, {
					name : 'adress',
					display : '客户地址',
					sortable : true,
					hide : true
				}, {
					name : 'applyUserName',
					display : '申请人名称',
					sortable : true
				}, {
					name : 'applyUserCode',
					display : '申请人账号',
					sortable : true,
					hide : true
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true
				}, {
					name : 'ExaDT',
					display : '审批时间',
					sortable : true,
					hide : true
				}, {
					name : 'remark',
					display : '备注',
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
					display : '修改人',
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

		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row.ExaStatus == "完成" || row.ExaStatus == "打回") {
					if (row) {
						showModalWin("?model=service_change_changeapply&action=viewTab&id="
								+ row.id
								+ "&skey="
								+ row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
					}
				} else {
					if (row) {
						showModalWin("?model=service_change_changeapply&action=toView&id="
								+ row.id
								+ "&skey="
								+ row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
					}
				}
			}
		}, {
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=service_change_changeapply&action=toEdit&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
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
					showThickboxWin('controller/service/change/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600');

				} else {
					alert("请选中一条数据");
				}
			}
		}, {
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
						type : "POST",
						data : {
							id : row.id
						},
						url : "?model=service_change_changeapply&action=ajaxdeletes",
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('删除成功！');
							} else {
								alert('删除失败，该对象可能已经被引用!');
							}
						}
					});
				}
			}
		}],

		subGridOptions : {
			url : '?model=service_change_changeitem&action=pageJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'productCode',
						display : '物料编号'
					}, {
						name : 'productName',
						display : '物料名称',
						width : 250
					}, {
						name : 'pattern',
						display : '规格型号'
					}, {
						name : 'unitName',
						display : '单位'
					}, {
						name : 'serilnoName',
						display : '序列号'
					}, {
						name : 'remark',
						display : '变更原因',
						width : 200
					}]
		},
		toAddConfig : {
			toAddFn : function(p) {
				action : showModalWin("?model=service_change_changeapply&action=toAdd")
			}
		},

		toEditConfig : {
			formWidth : '1100px',
			formHeight : 600,
			action : 'toEdit'
		},
		toViewConfig : {
			formWidth : '1100px',
			formHeight : 600,
			action : 'toView'
		},
		comboEx : [{
					text : '审批状态',
					key : 'ExaStatus',
					data : [{
								text : '待提交',
								value : '待提交'
							}, {
								text : '打回',
								value : '打回'
							}, {
								text : '部门审批',
								value : '部门审批'
							}, {
								text : '完成',
								value : '完成'
							}]
				}],
		searchitems : [{
					display : '单据编号',
					name : 'docCode'
				}, {
					display : '源单编号',
					name : 'relDocCode'
				}, {
					display : '源单类型',
					name : 'relDocType'
				}, {
					display : '物料编号',
					name : 'productCode'
				}, {
					display : '物料名称',
					name : 'productName'
				}]
	});
});