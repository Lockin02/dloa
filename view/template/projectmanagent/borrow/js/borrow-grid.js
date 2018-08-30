var show_page = function() {
	$("#borrowGrid").yxsubgrid("reload");
};
$(function() {
	buttonsArr = [
        {
            text : "重置",
            icon : 'delete',
			action : function(row) {
				var listGrid= $("#borrowGrid").data('yxsubgrid');
				listGrid.options.extParam = {};
				$("#borrowGrid tr").attr('style',"background-color: rgb(255, 255, 255)");
				listGrid.reload();
			}
		},{
			name : 'advancedsearch',
			text : "高级搜索",
			icon : 'search',
			action : function(row) {
				showThickboxWin("?model=projectmanagent_borrow_borrow&action=search&gridName=borrowGrid"
				        + "&gridType=yxsubgrid"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700")
			}
		}
    ];

	// 导出列表数据
	var exportExcel = {
		text: "导出",
		icon: 'excel',
		action: function (row) {
			var i = 1;
			var colId = "";
			var colName = "";
			$("#borrowGrid_hTable").children("thead").children("tr")
				.children("th").each(function () {
				if ($(this).css("display") != "none"
					&& $(this).attr("colId") != undefined && $(this).attr("colId") != 'test') {
					colName += $(this).children("div").html() + ",";
					colId += $(this).attr("colId") + ",";
					i++;
				}
			});
			window.open("?model=projectmanagent_borrow_borrow&action=exportExcel&colId="+colId+"&colName="+colName);
		}
	};

	// 检验是否有导出权限
	var exportLimit = $.ajax({
		url : "?model=projectmanagent_borrow_borrow&action=ajaxChkExportLimit",
		type : 'post',
		async : false
	}).responseText;
	if(exportLimit === '1'){
		buttonsArr.push(exportExcel);
	}

	$("#borrowGrid").yxsubgrid({
		model : 'projectmanagent_borrow_borrow',
		action : 'borrowGridJson',
		param : {
			"ExaStatus" : "完成",
			"limits" : "客户"
		},
		title : '借试用',
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isToolBar : false, // 是否显示工具栏
		showcheckbox : false,
        isOpButton : false,
		// 列信息
		colModel : [{
			display : 'initTip',
			name : 'initTip',
			sortable : true,
			hide : true
		}, {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'chanceId',
			display : '商机Id',
			sortable : true,
			hide : true
		}, {
			name : 'Code',
			display : '编号',
			sortable : true
		}, {
			name : 'Type',
			display : '类型',
			sortable : true,
			hide : true
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true
		}, {
			name : 'salesName',
			display : '销售负责人',
			sortable : true
		}, {
			name : 'beginTime',
			display : '开始日期',
			sortable : true
		}, {
			name : 'closeTime',
			display : '截止日期',
			sortable : true
		}, {
			name : 'scienceName',
			display : '技术负责人',
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 90,
			process: function (v,row) {
				if(row.lExaStatus != '变更审批中'){
					return v;
				}else{
					return '变更审批中';
				}
			}
		}, {
			name : 'checkFile',
			display : '验收文件',
			sortable : true,
			width : 90,
			process: function (v,row) {
				if(v == '有'){
					return v;
				}else{
					return '否';
				}
			}
		}, {
			name : 'DeliveryStatus',
			display : '发货状态',
			sortable : true,
			process : function(v) {
				if (v == 'WFH') {
					return "未发货";
				} else if (v == 'YFH') {
					return "已发货";
				} else if (v == 'BFFH') {
					return "部分发货";
				} else if (v == 'TZFH') {
					return "停止发货";
				}
			}
		}, {
			name : 'backStatus',
			display : '归还状态',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "未归还";
				} else if (v == '1') {
					return "已归还";
				} else if (v == '2') {
					return "部分归还";
				}
			}
		}, {
			name : 'ExaDT',
			display : '审批时间',
			sortable : true,
			hide : true,
			process: function (v,row){
				if(row['ExaStatus'] == "部门审批"){
					return '';
				}else{
					return v;
				}
			}
		}, {
			name : 'remark',
			display : '备注',
			sortable : true
		}, {
			name : 'objCode',
			display : '业务编号',
			width : 120
		}],
		comboEx : [{
			text: '归还状态',
			key: 'backStatu',
			data: [
				{
					text: '未归还',
					value: '0'
				},{
					text: '已归还',
					value: '1'
				},{
					text: '部分归还',
					value: '2'
				}
			]
		},{
			text : '审批状态',
			key : 'ExaStatus',
			value : '完成',
			data : [{
				text : '未审批',
				value : '未审批'
			}, {
				text : '变更审批中',
				value : '变更审批中'
			}, {
				text : '部门审批',
				value : '部门审批'
			}, {
				text : '物料确认',
				value : '物料确认'
			}, {
				text : '完成',
				value : '完成'
			}]
		}, {
			text : '发货状态',
			key : 'DeliveryStatus',
			data : [{
				text : '未发货',
				value : 'WFH'
			}, {
				text : '已发货',
				value : 'YFH'
			}, {
				text : '部分发货',
				value : 'BFFH'
			}]
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_borrow_borrowequ&action=listPageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'borrowId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称

			}],
			// 显示的列
			colModel : [{
				name : 'productNo',
				width : 100,
				display : '产品编号',
				process : function(v, row) {
					return v + "&nbsp;&nbsp;K3:" + row['productNoKS'];
				}
			}, {
				name : 'productName',
				width : 200,
				display : '产品名称',
				process : function(v, row) {
					return v + "&nbsp;&nbsp;K3:" + row['productNameKS'];
				}
			}, {
				name : 'productModel',
				width : 200,
				display : '物料版本/型号'
			}, {
				name : 'number',
				display : '申请数量',
				width : 80
			}, {
				name : 'executedNum',
				display : '已执行数量',
				width : 80
			}, {
                name : 'applyBackNum',
                display : '已申请归还数量'
            }, {
				name : 'backNum',
				display : '已归还数量',
				width : 80
			}]
		},
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '编号',
			name : 'Code'
		}, {
			display : '客户名称',
			name : 'customerName'
		}, {
			display : '业务编号',
			name : 'objCode'
		}, {
			display : '销售负责人',
			name : 'salesName'
		}, {
			display : '申请人',
			name : 'createNmae'
		}, {
			display : '申请日期',
			name : 'createTime'
		}, {
			display : 'K3物料名称',
			name : 'productNameKS'
		}, {
			display : '系统物料名称',
			name : 'productName'
		}, {
			display : 'K3物料编码',
			name : 'productNoKS'
		}, {
			display : '系统物料编码',
			name : 'productNo'
        }, {
            display: '序列号',
            name: 'serialName2'
		}],
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=projectmanagent_borrow_borrow&action=toViewTab&id="
							+ row.id + "&skey=" + row['skey_']);

				}
			}
		}
		, {
			text : '提交审核',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '未审批' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				// if (row) {
				// 	showThickboxWin('controller/projectmanagent/borrow/ewf_index.php?actTo=ewfSelect&billId='
				// 			+ row.id
				// 			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				// }
				if (window.confirm(("确定提交吗?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_borrow_borrow&action=ajaxSubForm",
						data : {
							id : row.id
						},
						success : function(msg) {
							if(msg != ""){
								alert(msg);
							}else{
								alert("提交失败。请重试!");
							}
							$("#MyBorrowGrid").yxsubgrid("reload");
						}
					});
				}
			}
		},{
            text : '归还物料',
            icon : 'add',
            showMenuFn : function(row) {
                return row.ExaStatus == '完成' && row.backStatus != '1' && $("#returnLimit").val() == "1";
            },
            action : function(row) {
                showOpenWin('?model=projectmanagent_borrowreturn_borrowreturn&action=toAdd&id=' + row.id);
            }
        }],
		buttonsEx : buttonsArr
	});
});