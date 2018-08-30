// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#serviceContractForESM").yxgrid("reload");
};
$(function() {
	$("#serviceContractForESM").yxgrid({
		model : 'engineering_serviceContract_serviceContract',
		action : 'pageJsonForESM',
		customCode : 'serviceContractForESM',
		title : '项目合同列表',
		param : {'ExaStatus' : '完成'},
		isToolBar : false,
		showcheckbox : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			},{
				name : 'tablename',
				display : '合同类型',
				sortable : true,
				process : function(v) {
					if (v == 'oa_sale_order') {
						return "销售合同";
					} else if (v == 'oa_sale_service') {
						return "服务合同";
					}
				},
				width : 80
			},{
				name : 'ExaDT',
				display : '建立时间',
				sortable : true,
				width : 80
			}, {
				name : 'orderCode',
				display : '鼎利合同号',
				sortable : true,
				width : 160
			}, {
				name : 'orderTempCode',
				display : '临时合同号',
				sortable : true,
				width : 160
			}, {
				name : 'customerName',
				display : '客户名称',
				sortable : true,
				width : 150
			}, {
				name : 'orderName',
				display : '合同名称',
				sortable : true,
				width : 150,
				hide : true
			}, {
				name : 'orderProvince',
				display : '省份',
				sortable : true
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
				name : 'sign',
				display : '是否签约',
				sortable : true,
				width : 70,
				hide : true
			}, {
				name : 'areaName',
				display : '归属区域',
				sortable : true,
				width : 60,
				hide : true
			}, {
				name : 'prinvipalName',
				display : '合同负责人',
				sortable : true,
				width : 100
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
				width : 60,
				hide : true
			}, {
				name : 'ExaStatus',
				display : '审批状态',
				sortable : true,
				width : 60
			}, {
				name : 'dealStatus',
				display : '处理状态',
				sortable : true,
				width : 60,
				process : function(v){
					if(v == 0){
						return '未处理';
					}else{
						return '已处理';
					}
				}
			}, {
				name : 'contractProcess',
				display : '合同进度',
				sortable : true,
				width : 60,
				process : function(v){
					return v + " %";
				}
			}, {
				name : 'objCode',
				display : '业务编号',
				sortable : true,
				width : 110
			}
		],
		//扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if(row.tablename == 'oa_sale_order'){
					showOpenWin('?model=projectmanagent_order_order&action=init&perm=view&id='
						+ row.orgid)
				}else{
					showOpenWin('?model=engineering_serviceContract_serviceContract&action=init&perm=view&id='
						+ row.orgid)
				}
			}

		}, {
			text : '发布项目章程',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.dealStatus == 0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if(row.objCode == ""){
						alert('此合同没有业务编号，请先联系管理员更新业务编号');
						return false;
					}
					showThickboxWin("?model=engineering_charter_esmcharter&action=toAddInContract&contractId="
						+ row.orgid
						+ "&contractType=" + row.tablename
						+ "&objCode=" + row.objCode
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000");
				}
			}
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
			}, {
				display : '业务编号',
				name : 'objCode'
			}
		],
		// 审批状态数据过滤
		comboEx : [{
			text : '处理状态',
			key: 'dealStatus',
			value : '0',
			data : [{
				text : '未处理',
				value : '0'
			}, {
				text : '已处理',
				value : '1'
			}]
		}],
		sortname : "createTime",
		//显示查看按钮
		isViewAction : false
	});

});