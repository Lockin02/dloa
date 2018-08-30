var show_page = function(page) {
	$("#serviceContractAllGrid").yxgrid("reload");
};
$(function() {
	$("#serviceContractAllGrid").yxgrid({
		//如果传入url，则用传入的url，否则使用model及action自动组装
		model : 'engineering_serviceContract_serviceContract',
		title : '服务合同信息',
		action : 'serviceContactInfoJson',
		isToolBar : false,
		showcheckbox : false,
		customCode : 'serlistAll',
		//					param : {
		//								//'states' : '2'
		//							},
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'ExaDT',
					display : '建立时间',
					sortable : true,
					width : 80
				}, {
					name : 'orderCode',
					display : '鼎利合同号',
					sortable : true,
					width : 180
				}, {
					name : 'orderTempCode',
					display : '临时合同号',
					sortable : true,
					width : 180
				}, {
					name : 'cusName',
					display : '客户名称',
					sortable : true,
					width : 100
				}, {
					name : 'orderName',
					display : '合同名称',
					sortable : true,
					width : 150
				}, {
					name : 'orderTempMoney',
					display : '预计合同金额',
					sortable : true,
					width : 80,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'orderMoney',
					display : '签约合同金额',
					sortable : true,
					width : 80,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true,
					width : 60
				}, {
					name : 'sign',
					display : '是否签约',
					sortable : true,
					width : 70
				}, {
					name : 'areaName',
					display : '归属区域',
					sortable : true,
					width : 60
				}, {
					name : 'orderPrincipal',
					display : '合同负责人',
					sortable : true,
					width : 80
				}, {
					name : 'state',
					display : '合同状态',
					sortable : true,
					process : function(v) {
						if (v == '0') {
							return "未提交";
						} else if (v == '1') {
							return "审批中";
						} else if (v == '2') {
							return "执行中";
						} else if (v == '3') {
							return "已关闭";
						} else if (v == '4') {
							return "已完成";
						} else if (v == '5') {
							return "已合并";
						} else if (v == '5') {
							return "已拆分";
						}
					},
					width : 60
				}, {
					name : 'objCode',
					display : '业务编号',
					width : 150
				}],
		//扩展按钮
		buttonsEx : [],
		//扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					//									showThickboxWin("?model=engineering_serviceContract_serviceContract&action=init"
					//										+ "&id="
					//										+ row.id
					//										+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
					//										+ 400 + "&width=" + 700);
					showOpenWin('?model=engineering_serviceContract_serviceContract&action=init&perm=view&id='
							+ row.id + "&skey=" + row['skey_'])
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			text : '导出',
			icon : 'add',
			action : function(row) {
				window
						.open('?model=contract_common_allcontract&action=importCont&id='
								+ row.id
								+ '&type=oa_sale_service'
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		},{
			text : '附件上传',
			icon : 'add',
			action: function(row){
				     showThickboxWin ('?model=projectmanagent_order_order&action=toUploadFile&id='
				                      + row.id
				                      +'&type=oa_sale_service'
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
			}
		},{
			text : '完成合同',
			icon : 'edit',
			showMenuFn : function (row){
				   if(row.state == 2){
				       return true;
				   }
				       return false;
				},
			action: function(row){
				if (window.confirm(("确定要把合同状态改为 “完成” 状态吗？"))) {
	                 	$.ajax({
							type : "POST",
							url : "?model=projectmanagent_order_order&action=completeOrder&id=" + row.id + "&type=oa_sale_service",
							success : function(msg) {
	                                $("#serviceContractAllGrid").yxgrid("reload");
							}
						});
	                 }
				}
		},{
			text : '执行合同',
			icon : 'edit',
			showMenuFn : function (row){
			   if(row.state == 4){
			       return true;
			   }
			       return false;
			},
			action: function(row){
				if (window.confirm(("确定要把合同状态改为 “执行中” 状态吗？"))) {
	                 	$.ajax({
							type : "POST",
							url : "?model=projectmanagent_order_order&action=exeOrder&id=" + row.id + "&type=oa_sale_service",
							success : function(msg) {
								   if(msg == '0'){
                                       alert("合同发货已完成，请选择变更流程");
                                       $("#serviceContractAllGrid").yxgrid("reload");
								   }else{
								       $("#serviceContractAllGrid").yxgrid("reload");
								   }

							}
						});
	                 }
				}
		}],

		comboEx : [{
			text : '合同状态',
			key : 'state',
			data : [ {
				text : '未提交',
				value : '0'
			},{
				text : '审批中',
				value : '1'
			},{
				text : '执行中',
				value : '2'
			},{
				text : '已完成',
				value : '4'
			},{
				text : '已关闭',
				value : '3'
			}  ]
		}],
		buttonsEx : [{
			name : 'export',
			text : "导出合同",
			icon : 'excel',
			action : function(row) {
				var type = "oa_sale_service"
				var state = $("#state").val();
				var ExaStatus = $("#ExaStatus").val();
				var i = 1;
				var colId = "";
				var colName = "";
				$("#orderInfoGrid_hTable").children("thead").children("tr")
						.children("th").each(function() {
							if ($(this).css("display") != "none"
									&& $(this).attr("colId") != undefined) {
								colName += $(this).children("div").html() + ",";
								colId += $(this).attr("colId") + ",";
								i++;
							}
						})
				window
						.open("?model=engineering_serviceContract_serviceContract&action=exportServiceExcel"
								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
			}
		}],
		//快速搜索
		searchitems : [{
			display : '合同名称',
			name : 'orderName'
		}, {
			display : '合同编号',
			name : 'orderCodeOrTempSearch'
		},{
			display : '业务编号',
			name : 'objCode'
		}],
		// title : '客户信息',
		//业务对象名称
		//						boName : '供应商联系人',
		sortname : "createTime",
		//显示查看按钮
		isViewAction : false
			//						isAddAction : true,
			//						isEditAction : false
	});

});