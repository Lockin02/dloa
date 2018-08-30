var show_page = function(page) {
	$("#repaircheckGrid").yxgrid("reload");
};


/**
 * 查看报价申报单详细信息
 *
 * @param {}
 *            id
 */
function viewCheckDetail(applyDocId) {
	var skey = "";
	$.ajax({
				type : "POST",
				url : "?model=service_repair_repairapply&action=md5RowAjax",
				data : {
					"id" : applyDocId
				},
				async : false,
				success : function(data) {
					skey = data;
				}
			});
	showModalWin("?model=service_repair_repairapply&action=toView&id="
			+ applyDocId
			+ "&skey="
			+ skey
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
}


$(function() {
	$("#repaircheckGrid").yxgrid({
		model : 'service_repair_repaircheck',
		title : '检测维修请求',
		isEditAction : false,
		isAddAction : false,
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
					name : 'applyDocCode',
					display : '维修申请单编号',
					process : function(v, row) {
						return "<a href='#' onclick='viewCheckDetail("
								+ row.applyDocId
								+ ")' >"
								+ v
								+ " <img title='' src='js/jquery/images/grid/view.gif' align='absmiddle' /></a>";
					}
				}, {
					name : 'issuedUserCode',
					display : '下达人code',
					sortable : true,
					hide : true
				}, {
					name : 'issuedUserName',
					display : '下达人',
					sortable : true,
					hide : true
				}, {
					name : 'issuedTime',
					display : '下达时间',
					sortable : true,
					width : 150,
					hide : true
				},{
					name : 'repairDeptCode',
					display : '检测维修部门code',
					sortable : true,
					hide : true
				}, {
					name : 'repairDeptName',
					display : '检测维修部门',
					sortable : true
				}, {
					name : 'repairUserCode',
					display : '检测维修人员code',
					sortable : true,
					hide : true
				}, {
					name : 'repairUserName',
					display : '检测维修人员',
					sortable : true
				}, {
					name : 'productType',
					display : '物料类型',
					sortable : true,
					hide : true
				}, {
					name : 'productCode',
					display : '物料编号',
					sortable : true,
					hide : true
				}, {
					name : 'productName',
					display : '物料名称',
					sortable : true,
					width : 250
				}, {
					name : 'pattern',
					display : '规格型号',
					sortable : true,
					hide : true
				}, {
					name : 'unitName',
					display : '单位',
					sortable : true,
					hide : true
				}, {
					name : 'serilnoName',
					display : '序列号',
					sortable : true,
					hide : true
				}, {
					name : 'fittings',
					display : '配件信息',
					sortable : true,
					hide : true
				}, {
					name : 'troubleInfo',
					display : '故障现象',
					sortable : true
				}, {
					name : 'checkInfo',
					display : '检测处理方法',
					sortable : true
				}, {
					name : 'isAgree',
					display : '是否同意维修',
					sortable : true,
					width : 70,
					process : function(v) {
						if (v == '0') {
							return "是";
						} else if (v == '1') {
							return "否";
						} else if (v == '2') {
							return "未确认";
						} else {
							return "未确认";
						}
					}
				}, {
					name : 'docStatus',
					display : '单据状态',
					sortable : true,
					width : 50,
					process : function(v) {
						if (v == 'YJC') {
							return "已检测";
						} else if (v == 'YWX') {
							return "已维修";
						} else if (v == 'WJC') {
							return "未检测";
						} else if (v == 'DHCJ') {
							return "打回重检";
						} else {
							return "未检测";
						}
					}
				}, {
					name : 'finishTime',
					display : '维修完成时间',
					sortable : true

				}, {
					name : 'remark',
					display : '备注',
					sortable : true,
					hide : true
				}],
		toViewConfig : {
			action : 'toView',
			formWidth : 800,
			formHeight : 500
		},

		menusEx : [{
			text : '通知维修',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isAgree == "2" && row.docStatus == "YJC") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=service_repair_repaircheck&action=toIsagree&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
				}
			}
		},{
			text : '打回重检',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.isAgree == "2" && row.docStatus == "YJC") {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定打回重检吗?"))) {
					$.ajax({
						type : "POST",
						url : "?model=service_repair_repaircheck&action=ajaxStateBack",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('打回成功！');
								$("#repaircheckGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.docStatus == "WJC") {
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
						url : "?model=service_repair_repaircheck&action=ajaxdeletes",
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
		comboEx : [{
					text : '单据状态',
					key : 'docStatus',
					data : [{
								text : '未检测',
								value : 'WJC'
							}, {
								text : '已检测',
								value : 'YJC'
							}, {
								text : '已维修',
								value : 'YWX'
							}]
				}],
		searchitems : [{
					display : '单据编号',
					name : 'docCode'
				}, {
					display : '物料名称',
					name : 'productName'
				}]
	});
});