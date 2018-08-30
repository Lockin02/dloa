/** 资产出售信息列表
 *  @linzx
 * */
var show_page = function(page) {
	$("#datadictList").yxsubgrid("reload");
};
$(function() {
	$("#datadictList").yxsubgrid({
		model : 'asset_disposal_sell',
		title : '资产出售',
		isToolBar : true,
		showcheckbox : false,
		//isViewAction : false,
		//isEditAction : false,
		//isAddAction : false,
		isDelAction : false,

		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '出售单编号',
			name : 'billNo',
			sortable : true,
			width : 130
		}, {
			display : '出售申请人',
			name : 'seller',
			sortable : true
		}, {
			display : '出售申请人部门',
			name : 'deptName',
			sortable : true
		}, {
			display : '出售申请日期',
			name : 'sellDate',
			sortable : true
		}, {
			display : '出售总数量',
			name : 'sellNum',
			sortable : true
		}, {
			display : '出售总金额',
			name : 'sellAmount',
			sortable : true,
			//列表格式化千分位
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '出售日期',
			name : 'donationDate',
			sortable : true
		}, {
			display : '审批状态',
			name : 'ExaStatus',
			sortable : true
		}, {
			display : '审批时间',
			name : 'ExaDT',
			sortable : true
		}, {
			display : '备注',
			name : 'remark',
			sortable : true
		}],
		// 列表页加上显示从表
		subGridOptions : {
			url : '?model=asset_disposal_sellitem&action=pageJson',
			param : [{
				paramId : 'sellID',
				colId : 'id'
			}],
			colModel : [{
				display : '卡片编号',
				name : 'assetCode',
				width : 250
			}, {
				display : '资产名称',
				name : 'assetName'
			}, {
				display : '购置日期',
				name : 'buyDate',
				type : 'date',
				tclass : 'txtshort'
			}, {
				display : '附属设备',
				name : 'equip',
				type : 'statictext',
				process : function(e, data) {
					return '<a href="#" onclick="javascript:window.open(\'?model=asset_assetcard_equip&action=toPage&showBtn=0&assetCode='
							+ data.assetCode + '\')">详细</a>'
				}
			}, {
				display : '已经使用期间数',
				name : 'alreadyDay',
				tclass : 'txtshort'
			}, {
				display : '售出部门',
				name : 'deptName',
				tclass : 'txtshort'
			}, {
				display : '已折旧金额',
				name : 'depreciation',
				tclass : 'txtshort',
				//列表格式化千分位
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
				display : '残余价值',
				name : 'salvage',
				tclass : 'txtshort',
				//列表格式化千分位
				process : function(v) {
					return moneyFormat2(v);
				}
			}]
		},
		toAddConfig : {
			formWidth : 1050,
			formHeight : 400
		},
		toEditConfig : {
			formWidth : 1050,
			formHeight : 400,
			showMenuFn : function(row) {
				if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
					return true;
				}
				return false;
			}
		},
		toViewConfig : {
			formWidth : 1000,
			formHeight : 500
		},

		// 扩展右键菜单
		menusEx : [{
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
					showThickboxWin('controller/asset/disposal/ewf_index_sell.php?actTo=ewfSelect&billId='
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			name : 'cancel',
			text : '撤消审批',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.ajax({
						type : "POST",
						url : "?model=common_workflow_workflow&action=isAudited",
						data : {
							billId : row.id,
							examCode : 'oa_asset_sell'
						},
						success : function(msg) {
							if (msg == '1') {
								alert('单据已经存在审批信息，不能撤销审批！');
						    	show_page();
								return false;
							}else{
								if(confirm('确定要撤消审批吗？')){
									$.ajax({
									    type: "GET",
									    url: "controller/asset/disposal/ewf_index_sell.php?actTo=delWork&billId=",
									    data: {"billId" : row.id },
									    async: false,
									    success: function(data){
									    	alert(data)
									    	show_page();
										}
									});
								}
							}
						}
					});
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if ((row.ExaStatus == "完成" || row.ExaStatus == "打回" || row.ExaStatus == "部门审批")) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_sell&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		}, {
			name : 'clear',
			text : '清理卡片',
			icon : 'add',
			showMenuFn : function(row) {
				if ((row.ExaStatus == "完成")) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					//平铺
					location = "?model=asset_assetcard_clean&action=toCleanSell&billNo="
							+ row.billNo + "&sellID=" + row.id;
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
						url : "?model=asset_disposal_sell&action=deletes&id="
								+ row.id,
						success : function(msg) {
							$("#datadictList").yxsubgrid("reload");
						}
					});
				}
			}
		}],

		searchitems : [{
			display : '出售单编号',
			name : 'billNo'
		}, {
			display : '出售申请人',
			name : 'seller'
		}, {
			display : '出售申请人部门',
			name : 'deptName'
		}],
		comboEx : [{
			text : '审批状态',
			key : 'ExaStatus',
			data : [{
				text : '部门审批',
				value : '部门审批'
			}, {
				text : '待提交',
				value : '待提交'
			}, {
				text : '完成',
				value : '完成'
			}, {
				text : '打回',
				value : '打回'
			}]
		}],
		// 默认搜索字段名
		sortname : "id",
		// 默认搜索顺序 降序
		sortorder : "DESC"

	});
});
