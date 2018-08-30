var show_page = function(page) {
	$("#repairquoteGrid").yxsubgrid("reload");
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
	$("#repairquoteGrid").yxsubgrid({
		model : 'service_repair_repairquote',
		title : '维修报价申报单',
		isEditAction : false,
		isAddAction : false,
		isViewAction : false,
		isDelAction : false,
		showcheckbox : false,
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
					name : 'chargeUserCode',
					display : '上报人code',
					sortable : true,
					hide : true
				}, {
					name : 'chargeUserName',
					display : '上报人名称',
					sortable : true
				}, {
					name : 'docDate',
					display : '上报时间',
					sortable : true,
					width : 150
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true
				}, {
					name : 'ExaDT',
					display : '审批时间',
					sortable : true
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
//		toViewConfig : {
//			toViewFn : function(p, g) {
//				action : showModalWin("?model=service_repair_repairquote&action=toView&id="
//						+ g.getSelectedRow().data('data')['id']
//						+ "&skey="
//						+ g.getSelectedRow().data('data')['skey_'])
//			}
//		},
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row.ExaStatus == "完成" || row.ExaStatus == "打回") {
					if (row) {
						showModalWin("?model=service_repair_repairquote&action=viewTab&id="
								+ row.id
								+ "&skey="
								+ row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
					}
				} else {
					if (row) {
						showModalWin("?model=service_repair_repairquote&action=toView&id="
								+ row.id
								+ "&skey="
								+ row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
					}
				}
			}
		},{
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == "待提交" || row.ExaStatus == "打回")
					return true;
				else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=service_repair_repairquote&action=toEdit&id="
							+ row.id + "&skey=" + row['skey_']);
				}
			}
		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == "待提交") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if (window.confirm(("确定删除吗？"))) {
					$.ajax({
						type : "POST",
						data : {
							id : row.id
						},
						url : "?model=service_repair_repairquote&action=ajaxdeletes",
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
					var maxCost=0;
					$.ajax({
						type : "POST",
						async : false,
						data : {
							id : row.id
						},
						url : "?model=service_repair_repairquote&action=getItemMaxMoney",
						success : function(result) {
							maxCost=result;
						}
					});
											showThickboxWin("controller/service/repair/ewf_index.php?actTo=ewfSelect&billId="
													+ row.id
													+ "&flowMoney="
													+ maxCost
													+ "&examCode=oa_service_repair_quote&formName=维修报价审批"
													+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=780");

				} else {
					alert("请选中一条数据");
				}
			}
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=service_repair_applyItem&action=pageJson',
			param : [{
						paramId : 'quoteId',
						colId : 'id'
					}],
			colModel : [{
						name : 'productCode',
						display : '物料编号',
						sortable : true
					}, {
						name : 'productName',
						display : '物料名称',
						sortable : true,
						width : 250
					}, {
						name : 'applyCode',
						display : '维修申请单编号',
						process : function(v, row) {
							return "<a href='#' onclick='viewApplyDetail("
									+ row.mainId
									+ ")' >"
									+ v
									+ " <img title='' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
						}
					}, {
						name : 'pattern',
						display : '规格型号',
						sortable : true
					}, {
						name : 'unitName',
						display : '单位',
						sortable : true,
						width : 50
					}, {
						name : 'serilnoName',
						display : '序列号',
						sortable : true
					}]
		},
		searchitems : [{
					display : '单据编号',
					name : 'docCode'
				}, {
					display : '上报人名称',
					name : 'chargeUserName'
				}],
		comboEx : [{
					text : '审批状态',
					key : 'ExaStatus',
					data : [{
								text : '待提交',
								value : '待提交'
							}, {
								text : '部门审批',
								value : '部门审批'
							}, {
								text : '完成',
								value : '完成'
							}, {
								text : '打回',
								value : '打回'
							}]
				}]
	});
});