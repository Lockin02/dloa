var show_page = function(page) {
	$("#rentcarGrid").yxgrid("reload");
};

$(function() {
	$("#rentcarGrid").yxgrid({
		model : 'outsourcing_contract_rentcar',
        action:"pageJsonForAll",
		param : {
			'createId' : $("#createId").val()
		},
		title : '租车合同',
		bodyAlign : 'center',
		isDelAction : false,
		showcheckbox : false,
		isOpButton : false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
			name: 'createDate',
			display: '录入日期',
			sortable: true,
			width : 70
		},{
			name: 'orderCode',
			display: '鼎利合同编号',
			sortable: true,
			width : 130,
			process : function(v,row) {
				if (row.status == 4) {
					return "<a href='#' style='color:red' onclick='showModalWin(\"?model=outsourcing_contract_rentcar&action=viewTab&id=" + row.id + "\",1)'>" + v + "</a>";
				} else {
					return "<a href='#' onclick='showModalWin(\"?model=outsourcing_contract_rentcar&action=viewTab&id=" + row.id + "\",1)'>" + v + "</a>";
				}
			}
		},{
			name: 'contractNature',
			display: '合同性质',
			sortable: true,
			width : 75
		},{
			name: 'contractType',
			display: '合同类型',
			sortable: true,
			width : 75
		},{
			name: 'projectCode',
			display: '项目编号',
			sortable: true,
			width : 130,
			align : 'left'
		},{
			name: 'projectName',
			display: '项目名称',
			sortable: true,
			width : 130,
			align : 'left'
		},{
			name: 'orderName',
			display: '合同名称',
			sortable: true,
			width : 130,
			align : 'left'
		},{
			name: 'signCompany',
			display: '签约公司',
			sortable: true,
			width : 150,
			align : 'left'
		},{
			name: 'companyProvince',
			display: '公司省份',
			sortable: true,
			width : 70
		},{
			name: 'ownCompany',
			display: '归属公司',
			sortable: true,
			width : 80
		},{
			name: 'linkman',
			display: '联系人',
			sortable: true,
			hide : true,
			width : 60
		},{
			name: 'phone',
			display: '联系电话',
			sortable: true,
			hide : true,
			width : 85
		},{
			name: 'address',
			display: '联系地址',
			sortable: true,
			hide : true,
			width : 150,
			align : 'left'
		},{
			name: 'signDate',
			display: '签约日期',
			sortable: true,
			width : 70
		},{
//			name: 'payedMoney',
//			display: '已付金额',
//			sortable: true,
//			process : function(v) {
//				return moneyFormat2(v);
//			},
//			align : 'left'
//		},{
			name: 'orderMoney',
			display: '合同金额',
			sortable: true,
			process : function(v) {
				return moneyFormat2(v);
			},
			align : 'left'
		},{
            name: 'contractStartDate',
            display: '合同开始日期',
            sortable: true,
            width : 75
        },{
            name: 'contractEndDate',
            display: '合同结束日期',
            sortable: true,
            width : 75
        },{
            name: 'rentUnitPrice',
            display: '租赁费用(元/月/辆)',
            sortable: true,
            width : 100,
            process : function(v) {
                return moneyFormat2(v);
            },
            align : 'left'
        },{
            name: 'fuelCharge',
            display: '燃油费(元/公里)',
            sortable: true,
            width : 85,
            process : function(v) {
                return moneyFormat2(v);
            },
            align : 'left'
        }, {
            name: 'payApplyMoney',
            display: '申请付款',
            sortable: true,
            process: function (v, row) {
                return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
            },
            width: 80
        },{
            name: 'payedMoney',
            display: '已付金额',
            sortable: true,
            process: function (v, row) {
                return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
            },
            width: 80
        }, {
            name: 'invotherMoney',
            display: '已收发票',
            sortable: true,
            process: function (v, row) {
                return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
            },
            width: 80
        }, {
            name: 'confirmInvotherMoney',
            display: '财务确认发票',
            sortable: true,
            process: function (v, row) {
                return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
            },
            width: 80
        }, {
            name: 'needInvotherMoney',
            display: '欠票金额',
            sortable: true,
            process: function (v, row) {
                return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
            },
            width: 80
        },{
			name: 'returnMoney',
			display: '返款金额',
			sortable: true,
			process : function(v) {
				return moneyFormat2(v);
			},
			align : 'left'
		},{
			name: 'status',
			display: '合同状态',
			sortable: true,
			width : 70,
			process : function(v,row) {
				var str = '';
				switch (v) {
					case '0' : str = '未提交';break;
					case '1' : str = '审批中';break;
					case '2' : str = '执行中';break;
					case '3' : str = '已关闭';break;
					case '4' : str = '变更中';break;
					case '6' : str = '未确认';break;
				}
				return str;
			}
		},{
			name: 'ExaStatus',
			display: '审批状态',
			sortable: true,
			width : 70
		},{
			name: 'signedStatus',
			display: '合同签收',
			sortable: true,
			width : 70,
			process : function(v) {
				if (v == 0) {
					return '未签收';
				} else {
					return '已签收';
				}
			}
		},{
			name: 'objCode',
			display: '业务编号',
			sortable: true
		},{
			name: 'isNeedStamp',
			display: '是否需要盖章',
			sortable: true,
			width : 70,
			process : function(v) {
				if (v == 0) {
					return '否';
				} else {
					return '是';
				}
			}
		},{
			name: 'isStamp',
			display: '是否已盖章',
			sortable: true,
			width : 70,
			process : function(v) {
				if (v == 0) {
					return '否';
				} else {
					return '是';
				}
			}
		},{
			name: 'stampType',
			display: '盖章类型',
			sortable: true,
			width : 150,
			align : 'left'
		},{
		// 	name: 'rentalcarCode',
		// 	display: '租车申请Code',
		// 	sortable: true
		// },{
		// 	name: 'rentUnitPrice',
		// 	display: '租赁单价（元/月/辆）',
		// 	sortable: true
		// },{
		// 	name: 'oilPrice',
		// 	display: '油价',
		// 	sortable: true
		// },{
		// 	name: 'fuelCharge',
		// 	display: '燃油费单价',
		// 	sortable: true
		// },{
			name: 'createName',
			display: '申请人',
			sortable: true,
			width : 80
		},{
			name: 'updateTime',
			display: '更新时间',
			sortable: true,
			width : 120
		}],

		//扩张菜单
		buttonsEx : [{
			name : 'searchAdv',
			text : "高级搜索",
			icon : 'view',
			action : function(row) {
				showThickboxWin("?model=outsourcing_contract_rentcar&action=toSearchAdv"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
			}
		},
		// 	{
		// 	name : 'exportIn',
		// 	text : "导入",
		// 	icon : 'excel',
		// 	action : function(row) {
		// 		showThickboxWin("?model=outsourcing_contract_rentcar&action=toExcelIn"
		// 			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
		// 	}
		// },
			{
			name : 'exportOut',
			text : "导出",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=outsourcing_contract_rentcar&action=toExcelOut&isCreate=true"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
			}
		// },{
		// 	name : 'updatePro',
		// 	text : "关联工程项目",
		// 	icon : 'excel',
		// 	action : function(row) {
		// 		showThickboxWin("?model=outsourcing_contract_rentcar&action=toExcelPro"
		// 			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
		// 	}
		}],

		//扩展右键菜单
		menusEx : [{
			text : "提交审批",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row ,rows ,grid) {
				if(row) {
					$.ajax({
						type: "POST",
						url: "?model=outsourcing_vehicle_rentalcar&action=getOfficeInfoForId",
						data: {
							'projectId' : row.projectId
						},
						async: false,
						success: function(data) {
							if(data) {
								showThickboxWin('controller/outsourcing/contract/ewf_index.php?actTo=ewfSelect&billId='
									+ row.id + "&billArea=" + data
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
							} else {
								showThickboxWin('controller/outsourcing/contract/ewf_index.php?actTo=ewfSelect&billId='
									+ row.id
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
							}
						}
					});
				}
			}
		},{
			text : '申请付款',
			icon : 'add',
			showMenuFn : function(row) {
				// return false; //暂时关闭付款
				if(row.status == 3) {
					return false;
				}
				if (row.ExaStatus == "完成" && row.contractNatureCode == 'ZCHTXZ-01') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row ,rows ,grid) {
				if (row) {
					var data = '';
					$.ajax({
						type: "POST",
						url: "?model=outsourcing_contract_rentcar&action=canPayapply",
						data: { "id" : row.id},
						async: false,
						success: function(data) {
							data = data;
						}
					});
					if(data == 'hasBack') {
						alert('合同存在未处理完成的退款单，不能申请付款');
						return false;
					} else{ //如果可以继续申请
						showModalWin("?model=finance_payablesapply_payablesapply&action=toAddforObjType&objType=YFRK-06&objId=" + row.id ,1 ,row.id);
					}
				} else {
					alert("请选中一条数据");
				}
			}
		},{
			text : '录入发票',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.status == 3) {
					return false;
				}
				if (row.ExaStatus == "完成" && row.contractNatureCode == 'ZCHTXZ-01') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row ,rows ,grid) {
				if(row.orderMoney*1 <= accAdd(row.invotherMoney,row.returnMoney,2)*1) {
					alert('合同可录入发票额已满');
					return false;
				}
				showModalWin("?model=finance_invother_invother&action=toAddObj&objType=YFQTYD03&objId=" + row.id ,1 ,row.id);
			}
		},{
			name : 'stamp',
			text : '申请盖章',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.status == 3) {
					return false;
				}
				if (row.ExaStatus != "待提交") {
					if(row.isNeedStamp == '0') {
						return true;
					} else {
						if(row.isStamp == '0') {
							return false;
						} else{
							return true;
						}
					}
				} else {
					return false;
				}
			},
			action : function(row ,rows ,grid) {
				if (row) {
					showThickboxWin("?model=outsourcing_contract_rentcar&action=toStamp&id="
						+ row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=900");
				}
			}
		},{
			name : 'file',
			text : '上传附件',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.status == 3){
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=outsourcing_contract_rentcar&action=toUploadFile&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
			}
		},{
			name : 'change',
			text : '变更合同',
			icon : 'edit',
			showMenuFn : function(row) {
				if(row.status == 2 && row.ExaStatus == '完成') {
					return true;
				}
				return false;
			},
			action : function(row ,rows ,grid) {
				showModalWin("?model=outsourcing_contract_rentcar&action=toChange&id=" + row.id ,'1');
			}
		},{
			text : '申请退款',
			icon : 'delete',
			showMenuFn : function(row) {
				if(row.status == 3) {
					return false;
				}
				if (row.ExaStatus == "完成" && row.contractNatureCode == 'ZCHTXZ-01')
					return true;
				else
					return false;
			},
			action : function(row ,rows ,grid) {
				if (row) {
					$.ajax({
						type: "POST",
						url: "?model=outsourcing_contract_rentcar&action=canPayapplyBack",
						data: { "id" : row.id},
						async: false,
						success: function(data) {
							if(data == 'hasBack') {
								alert('合同存在未处理完成的付款申请，不能申请退款');
								return false;
							} else if (data*1 == '0') {
								alert('合同无已付款项，不能申请退款');
								return false;
							} else if (data*1 == -1) {
								alert('合同退款申请金额已满，不能继续申请');
								return false;
							} else{
								showModalWin("?model=finance_payablesapply_payablesapply&action=toAddforObjType&payFor=FKLX-03&objType=YFRK-06&objId=" + row.id ,1 ,row.id);
							}
						}
					});
				} else {
					alert("请选中一条数据");
				}
			}
		},{
			text : '关闭合同',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == "完成" && row.status == "2") {
					return true;
				}
				return false;
			},
			action: function(row) {
				if (window.confirm(("确定关闭吗？"))) {
					$.ajax({
						type : "POST",
						url : "?model=outsourcing_contract_rentcar&action=changeStatus",
						data : {
							"id" : row.id
						},
						success : function(msg) {
							if( msg == 1 ) {
								alert('关闭成功！');
								show_page();
							} else{
								alert('关闭失败！');
							}
						}
					});
				}
			}
		},{
			name : 'back',
			text : '打回',
			icon : 'delete',
			showMenuFn : function(row) {
				if(row.status == 6) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=outsourcing_contract_rentcar&action=toBack&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=250&width=860");
			}
		},{
			name : 'delete',
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.ExaStatus == '待提交' || row.ExaStatus == '打回') && row.status != 6) {
					return true;
				}
				return false;
			},
			action : function(row ,rows ,grid) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=outsourcing_contract_rentcar&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$("#rentcarGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row ,rows ,grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_contract_rentcar&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

		comboEx : [{
			text: "合同状态",
			key: 'status',
			data : [{
			// 	text : '未确认',
			// 	value : '6'
			// },{
				text : '待提交',
				value : '0'
			},{
				text : '审批中',
				value : '1'
			},{
				text : '执行中',
				value : '2'
			},{
				text : '已关闭',
				value : '3'
			},{
				text : '变更中',
				value : '4'
			}]
		},{
			text: "合同性质",
			key: 'contractNatureCode',
			datacode : 'ZCHTXZ'
		},{
			text: "合同类型",
			key: 'contractTypeCode',
			datacode : 'ZCHTLX'
		}],

		toAddConfig: {
			toAddFn : function(p, g) {
				showModalWin("?model=outsourcing_contract_rentcar&action=toAdd");
			}
		},
		toEditConfig: {
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			toEditFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					if (get['status'] == 6) { //项目经理提交过来的编辑不能修改租车申请单号、项目信息和签约公司
						showModalWin("?model=outsourcing_contract_rentcar&action=toEditByRentalcar&id=" + get[p.keyField],'1');
					} else {
						showModalWin("?model=outsourcing_contract_rentcar&action=toEdit&id=" + get[p.keyField],'1');
					}
				}
			}
		},
		toViewConfig: {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_contract_rentcar&action=viewTab&id=" + get[p.keyField],'1');
				}
			}
		},

		searchitems: [{
			display: "录入日期",
			name: 'createTimeSea'
		},{
			display: "鼎利合同编号",
			name: 'orderCode'
		},{
			display: "项目名称",
			name: 'projectName'
		},{
			display: "项目编号",
			name: 'projectCode'
		},{
			display: "合同名称",
			name: 'orderName'
		},{
			display: "签约公司",
			name: 'signCompany'
		},{
			display: "签约日期",
			name: 'signDateSea'
		}]
	});
});