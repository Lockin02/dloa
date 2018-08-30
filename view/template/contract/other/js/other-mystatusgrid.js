var show_page = function(page) {
	$("#otherGrid").yxgrid("reload");
};

$(function() {
	$("#otherGrid").yxgrid({
		model : 'contract_other_other',
		action : 'myOtherListPageJson',
		title : '我的其他合同',
		isViewAction : false,
		isAddAction :false,
		customCode : 'otherGrid',
		param : { "status" : $("#status").val() },
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'createDate',
				display : '录入日期',
				width : 70
			}, {
				name : 'fundTypeName',
				display : '款项性质',
				sortable : true,
				width : 70,
				process : function(v,row){
					if(row.fundType == 'KXXZB'){
						return '<span style="color:blue">' + v  +'</span>';
					}else if( row.fundType == 'KXXZA'){
						return '<span style="color:green">' + v  +'</span>';
					}else{
						return v;
					}
				}
			}, {
				name : 'orderCode',
				display : '鼎利合同号',
				sortable : true,
				width : 130,
	            process : function(v,row){
					if(row.status == 4){
						return "<a href='#' style='color:red' title='变更中的合同' onclick='window.open(\"?model=contract_other_other&action=viewTab&id=" + row.id+ "&fundType=" + row.fundType + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					}else{
						if(row.ExaStatus == '待提交' || row.ExaStatus == '部门审批'){
							return "<a href='#' onclick='showModalWin(\"?model=contract_other_other&action=viewAlong&id=" + row.id + '&skey=' + row.skey_ + "\",1,"+ row.id +")'>" + v + "</a>";
						}else{
							return "<a href='#' onclick='showModalWin(\"?model=contract_other_other&action=viewTab&id=" + row.id + "&fundType=" + row.fundType + '&skey=' + row.skey_ + "\",1,"+ row.id +")'>" + v + "</a>";
						}
					}
	            }
			}, {
				name : 'orderName',
				display : '合同名称',
				sortable : true,
				width : 130
			}, {
				name : 'signCompanyName',
				display : '签约公司',
				sortable : true,
				width : 150
			}, {
				name : 'proName',
				display : '公司省份',
				sortable : true,
				width : 70
			}, {
				name : 'address',
				display : '联系地址',
				sortable : true,
				hide : true
			}, {
				name : 'phone',
				display : '联系电话',
				sortable : true,
				hide : true
			}, {
				name : 'linkman',
				display : '联系人',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'signDate',
				display : '签约日期',
				sortable : true,
				width : 80
			}, {
				name : 'orderMoney',
				display : '合同总金额',
				sortable : true,
				process : function(v) {
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'payApplyMoney',
				display : '申请付款',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZB' ){
						return '--';
					}else{
						if(v*1 == 0){
							return 0;
						}else{
							var thisTitle = '其中初始导入付款金额为: ' + moneyFormat2(row.initPayMoney) + ',后期付款申请金额为：' + moneyFormat2(row.countPayApplyMoney);
							return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
						}
					}
				},
				width : 80
			}, {
				name : 'payedMoney',
				display : '已付金额',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZB' ){
						return '--';
					}else{
						if(v*1 == 0){
							return 0;
						}else{
							var thisTitle = '其中初始导入付款金额为: ' + moneyFormat2(row.initPayMoney) + ',后期付款金额为：' + moneyFormat2(row.countPayMoney);
							return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
						}
					}
				},
				width : 80
			}, {
				name : 'returnMoney',
				display : '返款金额',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZB'){
						if(row.id == 'noId'){
							return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
						}
						return '--';
					}else{
						if(v*1 != 0){
							return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
						}else{
							return 0;
						}
					}
				},
				width : 80
			}, {
				name : 'invotherMoney',
				display : '已收发票',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZB'){
						return '--';
					}else{
						if(v*1 != 0){
							var thisTitle = '其中初始导入收票金额为: ' + moneyFormat2(row.initInvotherMoney) + ',后期收票金额为：' + moneyFormat2(row.countInvotherMoney);
							return "<span style='color:blue' title='" + thisTitle + "'>" + moneyFormat2(v) + "</span>";
						}else{
							return 0;
						}
					}
				},
				width : 80
			}, {
				name : 'applyInvoice',
				display : '申请开票',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZA' ){
						return '--';
					}else{
						if(v*1 == 0){
							return 0;
						}else{
							return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
						}
					}
				},
				width : 80
			}, {
				name : 'invoiceMoney',
				display : '已开发票',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZA'){
						return '--';
					}else{
						if(v*1 != 0){
							return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
						}else{
							return 0;
						}
					}
				},
				width : 80
			}, {
				name : 'incomeMoney',
				display : '收款金额',
				sortable : true,
				process : function(v,row) {
					if(row.fundType != 'KXXZA'){
						return '--';
					}else{
						if(v*1 != 0){
							return "<span style='color:green'>" + moneyFormat2(v) + "</span>";
						}else{
							return 0;
						}
					}
				},
				width : 80
			}, {
				name : 'principalName',
				display : '合同负责人',
				sortable : true,
				hide : true
			}, {
				name : 'deptName',
				display : '部门名称',
				sortable : true,
				hide : true
			}, {
				name : 'status',
				display : '状态',
				sortable : true,
				width : 60
				,
				process : function(v,row){
					if(row.id == 'noId') return false;
					if(v == 0){
						return "未提交";
					}else if(v == 1){
						return "审批中";
					}else if(v == 2){
						return "执行中";
					}else if(v == 3){
						return "已关闭";
					}else if(v == 4){
						return "变更中";
					}
				}
			},{
				name : 'ExaStatus',
				display : '审批状态',
				sortable : true,
				width : 60
			},{
	            name : 'signedStatus',
	            display : '合同签收',
	            sortable : true,
	            process : function(v,row){
					if(row.id == "noId"){
						return '';
					}
					if(v=="1"){
						return "已签收";
					}else{
						return "未签收";
					}
				},
	            width : 70
	        }, {
				name : 'objCode',
				display : '业务编号',
				sortable : true,
				width : 120
			},{
	            name : 'isNeedStamp',
	            display : '已申请盖章',
	            sortable : true,
	            width : 60,
	            process : function(v,row){
					if(v=="1"){
						return "是";
					}else{
						return "否";
					}
				}
	        },{
	            name : 'isStamp',
	            display : '是否已盖章',
	            sortable : true,
	            width : 60,
	            process : function(v,row){
					if(v == 1){
						return "是";
					}else{
						return "否";
					}
	            }
	        },{
	            name : 'stampType',
	            display : '盖章类型',
	            sortable : true,
	            width : 80
	        },{
	            name : 'createName',
	            display : '申请人',
	            sortable : true
	        },{
	            name : 'updateTime',
	            display : '更新时间',
	            sortable : true,
	            width : 130
	        }],
		toEditConfig : {
			formWidth : 1000,
			formHeight : 500,
			showMenuFn : function(row) {
				if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
					return true;
				}
				return false;
			},
			toEditFn : function(p, g) {
				var c = p.toEditConfig;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					showModalWin("?model="
							+ p.model
							+ "&action="
							+ c.action
							+ c.plusUrl
							+ "&id="
							+ rowData[p.keyField]
							+ keyUrl);
				} else {
					alert('请选择一行记录！');
				}
			}
		},
		// 扩展右键菜单
		menusEx : [{
			text : '查看合同',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=contract_other_other&action=viewTab&id="
							+ row.id
							+ "&fundType="
							+ row.fundType
							+ "&skey=" + row.skey_
							);
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			text : '提交审批',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.ajax({
						type : "POST",
						url : "?model=contract_otherpayapply_otherpayapply&action=getFeeDeptId",
						data : { "contractId" : row.id ,'contractType' : 'oa_sale_other' },
						success : function(data) {
							if(data != '0'){
								showThickboxWin('controller/contract/other/ewf_forpayapply.php?actTo=ewfSelect&billId='
									+ row.id
									+ "&flowMoney=" + row.orderMoney
									+ "&billDept=" + data
                                    + "&billCompany=" + row.businessBelong
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
							}else{
								if(row.fundType == 'KXXZB'){
									showThickboxWin('controller/contract/other/ewf_index.php?actTo=ewfSelect&billId='
										+ row.id
										+ "&flowMoney=" + row.orderMoney
										+ "&billDept=" + row.feeDeptId
                                        + "&billCompany=" + row.businessBelong
										+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
								}else{
									showThickboxWin('controller/contract/other/ewf_index.php?actTo=ewfSelect&billId='
										+ row.id
										+ "&flowMoney=" + row.orderMoney
                                        + "&billCompany=" + row.businessBelong
										+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
								}
							}
						}
					});
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			text : '申请开票',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.status == 3){
					return false;
				}
				if (row.ExaStatus == "完成" && row.fundType == 'KXXZA')
					return true;
				else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=finance_invoiceapply_invoiceapply&action=toAdd&invoiceapply[objId]="
							+ row.id
							+ "&invoiceapply[objCode]="
							+ row.orderCode
							+ "&invoiceapply[objType]=KPRK-09");
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			text : '申请付款',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.status == 3){
					return false;
				}
				if (row.ExaStatus == "完成" && row.fundType == 'KXXZB')
					return true;
				else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=finance_payablesapply_payablesapply&action=toAddforObjType&objType=YFRK-02&objId=" + row.id);
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			text : '录入发票',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.status == 3){
					return false;
				}
				if (row.ExaStatus == "完成" && row.fundType == 'KXXZB')
					return true;
				else
					return false;
			},
			action : function(row, rows, grid) {
				if(row.orderMoney*1 <= accAdd(row.invotherMoney,row.returnMoney,2)*1){
					alert('合同可录入发票额已满');
					return false;
				}
				showModalWin("?model=finance_invother_invother&action=toAddObj&objType=YFQTYD02&objId=" + row.id);
			}
		} ,{
			name : 'stamp',
			text : '申请盖章',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.status == 3){
					return false;
				}
				if (row.ExaStatus == "完成" && row.isStamp != "1")
					return true;
				else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if(row.isNeedStamp == '1'){
						alert('此合同已申请盖章,不能重复申请');
						return false;
					}
					showThickboxWin("?model=contract_other_other&action=toStamp&id="
						+ row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=750");
				} else {
					alert("请选中一条数据");
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
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=contract_other_other&action=toUploadFile&id="
					+ row.id
					+ "&skey=" + row.skey_
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
			}
		},{
			name : 'change',
			text : '变更合同',
			icon : 'edit',
			showMenuFn : function(row) {
				if(row.status == 2 && row.ExaStatus == '完成'){
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showOpenWin("?model=contract_other_other&action=toChange&id="
					+ row.id
					+ "&skey=" + row.skey_ );
			}
		}, {
			text : '关闭合同',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == "完成" && row.status == "2") {
					return true;
				}
				return false;
			},
			action: function(row){
				if (window.confirm(("确定关闭吗？"))) {
					$.ajax({
						type : "POST",
						url : "?model=contract_other_other&action=changeStatus",
						data : { "id" : row.id },
						success : function(msg) {
							if( msg == 1 ){
								alert('关闭成功！');
				                show_page();
							}else{
								alert('关闭失败！');
							}
						}
					});
				}
			}
		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.ExaStatus == "待提交" || row.ExaStatus == "打回")) {
					return true;
				}
				return false;
			},
			action : function(rowData, rows, rowIds, g) {
				g.options.toDelConfig.toDelFn(g.options, g);
			}
		}],
		searchitems : [{
			display : '负责人',
			name : 'principalName'
		}, {
			display : '签约公司',
			name : 'signCompanyName'
		}, {
			display : '合同名称',
			name : 'orderName'
		}, {
			display : '合同编号',
			name : 'orderCodeSearch'
		},{
			display : '业务编号',
			name : 'objCodeSearch'
		}],
		isDelAction : false,
		// 默认搜索字段名
		sortname : "c.createTime",
		// 默认搜索顺序 降序DESC 升序ASC
		sortorder : "DESC",
		// 审批状态数据过滤
		comboEx : [{
			text : "款项性质",
			key : 'fundType',
			datacode : 'KXXZ'
		}]
	});
});