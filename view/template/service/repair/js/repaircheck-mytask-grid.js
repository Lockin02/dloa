var show_page = function(page) {
	$("#repaircheckGrid").yxgrid("reload");
};
$(function() {
	$("#repaircheckGrid").yxgrid({
		model : 'service_repair_repaircheck',
		title : '个人检测维修任务',
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		showcheckbox : false,
		param : {
			'repairUserCode' :	$("#repairUserCode").val()//根据维修人员过滤
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
					sortable : true,
					hide : true
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
					width : 120
				}, {
					name : 'serilnoName',
					display : '序列号',
					sortable : true,
					width : 200
				}, {
					name : 'prov',
					display : '省份',
					sortable : true,
					width : 60
				}, {
					name : 'customerName',
					display : '客户名称',
					sortable : true,
					width : 200
				}, {
					name : 'contactUserName',
					display : '客户联系人',
					sortable : true,
					width : 80
				}, {
					name : 'telephone',
					display : '联系电话',
					sortable : true
				}, {
					name : 'isGurantee',
					display : '是否过保',
					sortable : true,
					width : 80,
					process : function(v) {
						if (v == '0') {
							return "是";
						} else if (v == '1') {
							return "否";
						}
					}
				}, {
					name : 'applyDocCode',
					display : '维修申请单编号',
					sortable : true,
					hide : true
				}, {
					name : 'repairDeptCode',
					display : '检测维修部门code',
					sortable : true,
					hide : true
				}, {
					name : 'repairDeptName',
					display : '检测维修部门',
					sortable : true,
					hide : true
				}, {
					name : 'repairUserCode',
					display : '检测维修人员code',
					sortable : true,
					hide : true
				}, {
					name : 'repairUserName',
					display : '检测维修人员',
					sortable : true,
					hide : true
				}, {
					name : 'productType',
					display : '物料分类',
					sortable : true,
					hide : true
				}, {
					name : 'productCode',
					display : '物料编号',
					sortable : true,
					hide : true,
					width : 200
				}, {
					name : 'productName',
					display : '物料名称',
					sortable : true,
					hide : true,
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
					name : 'fittings',
					display : '配件信息',
					sortable : true,
					hide : true
				}, {
					name : 'troubleType',
					display : '故障类型',
					sortable : true,
					width : 100
				}, {
					name : 'troubleInfo',
					display : '反馈故障',
					sortable : true,
					width : 200
				}, {
					name : 'isAgree',
					display : '是否同意维修',
					sortable : true,
					width : 80,
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
					width : 60,
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
					name : 'checkInfo',
					display : '检测处理方法',
					sortable : true,
					width : 150

				}, {
					name : 'finishTime',
					display : '维修完成时间',
					sortable : true

				}, {
					name : 'remark',
					display : '备注',
					sortable : true,
					width : 150
				}],
		toViewConfig : {
			action : 'toView',
			formWidth : 800,
			formHeight : 500
		},

		menusEx : [{
			text : '检测反馈',
			icon : 'business',
			showMenuFn : function(row) {
				if (row.docStatus == "WJC" || row.docStatus == 'DHCJ') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=service_repair_repaircheck&action=toFeedback&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
				}
			}
		}, {
			text : '维修完成',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.docStatus == "YJC" && row.isAgree == "0") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=service_repair_repaircheck&action=toConfirm&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
				}
			}
		}, {
			text : '修改处理方法',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isAgree != '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row){
					showThickboxWin("?model=service_repair_repaircheck&action=toEditCheckInfo&id="
						+ row.id
						+ '&skey=' + row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
				}
			}
		}, {
			name : 'view',
			text : "操作日志",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
                    + row.id
                    + "&tableName=oa_service_repair_check"
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}],
		comboEx : [{
					text : '单据状态',
					key : 'docStatus',
					value : 'WJC',
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
					display : '省份',
					name : 'prov'
				}, {
					display : '客户名称',
					name : 'customerName'
				}, {
					display : '物料名称',
					name : 'productName'
				}, {
					display : '序列号',
					name : 'serilnoNameSer'
				}, {
					display : '故障类型',
					name : 'troubleType'
				}]
	});
});