var show_page = function(page) {
	$("#produceplanGrid").yxgrid("reload");
};

$(function() {
	//数据过滤条件
	var param = {};
	var comboEx = [{
		text : '优先级',
		key : 'urgentLevelCode',
		datacode : 'SCJHYXJ'
	}];
	if ($("#finish").val() == 'yes') {
		param = {
			docStatusIn : '6,8',
			isCancel : 0
		};
		var comboExArr = {
			text : '单据状态',
			key : 'docStatus',
			data : [{
				text: '已关闭',
				value: '6'
			}, {
				text: '已完成',
				value: '8'
			}]
		};
		comboEx.push(comboExArr);
	} else {
		param = {
			isCancel : 0
		};
		var comboExArr = {
			text: '单据状态',
			key: 'docStatus',
			data: [{
				text: '未领料',
				value: '1'
			}, {
				text: '正在审批',
				value: '2'
			}, {
				text: '正在领料',
				value: '3'
			}, {
				text: '部分领料',
				value: '4'
			}, {
				text: '领料完成',
				value: '5'
			}, {
				text: '质检中',
				value: '9'
			}, {
				text: '部分质检',
				value: '10'
			}, {
				text: '质检完成',
				value: '11'
			}, {
				text: '正在返工',
				value: '12'
			}, {
				text: '已关闭',
				value: '6'
			}, {
				text: '部分入库',
				value: '7'
			}, {
				text: '已完成',
				value: '8'
			}]
		};
		comboEx.push(comboExArr);
	}

	$("#produceplanGrid").yxgrid({
		model : 'produce_plan_produceplan',
		action : 'pageJsonFeedback',
		param : param,
		title : '生产计划',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
			name: 'relDocCode',
			display: '合同(源单)编号',
			sortable: true,
            process: function (v, row) {
            	if(row.relDocTypeCode == 'HTLX-XSHT' || row.relDocTypeCode == 'HTLX-FWHT' ||
            		row.relDocTypeCode == 'HTLX-ZLHT' || row.relDocTypeCode == 'HTLX-YFHT'){
                    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                    + row.relDocId
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + "<font color = '#4169E1'>"
                    + v
                    + "</font>"
                    + '</a>';
            	}
            	return v;
            }
		},{
			name: 'docCode',
			display: '单据编号',
			sortable: true,
			width : 140,
			process : function (v ,row) {
				if (row.docStatus == 0) {
					v = '<img title="新增的生产计划" src="images/new.gif">' + v;
				} else if (row.docStatus == 1) {
					if (row.planNum > row.stockNum) {
						var nowData = new Date();
						var dateArr = (row.planEndDate).split('-');
						var planEndDate = new Date(dateArr[0] ,parseInt(dateArr[1]) ,parseInt(dateArr[2]));
						if (nowData.getTime() > planEndDate.getTime()) {
							v = '<img title="超时的生产计划" src="images/icon/hred.gif">' + v;
						}
					}
				}
				//已进行质检申请但未反馈完整的进行提示
				if (row.feedbackState == 2) {
					v = v + '<img title="反馈不完整" src="images/w_vActionSignIn.gif">';
				}
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_produceplan&action=toViewTab&id=" + row.id + "\",1)'>" + v + "</a>";
			}
		},{
			name: 'docStatus',
			display: '单据状态',
			sortable: true,
			width : 60,
			align : 'center',
			process : function(v ,row) {
				switch (v) {
					case '1':
						return "未领料";
						break;
					case '2':
						return "正在审批";
						break;
					case '3':
						return "正在领料";
						break;
					case '4':
						return "部分领料";
						break;
					case '5':
						return "领料完成";
						break;
					case '6':
						return "已关闭";
						break;
					case '7':
						return "部分入库";
						break;
					case '8':
						return "已完成";
						break;
					case '9':
						return "质检中";
						break;
					case '10':
						return "部分质检";
						break;
					case '11':
						return "质检完成";
						break;
					case '12':
						return "正在返工";
						break;
					default:
						return "--";
				}
			}
		},{
			name: 'urgentLevel',
			display: '优先级',
			sortable: true,
			align : 'center'
		},{
			name: 'docDate',
			display: '单据日期',
			sortable: true,
			width : 80,
			align : 'center'
		},{
			name: 'proType',
			display: '物料类型',
			sortable: true,
			width : 100
		},{
			name: 'productName',
			display: '物料名称',
			sortable: true,
			width : 200
		},{
			name: 'productCode',
			display: '物料编码',
			sortable: true
		},{
			name : 'pattern',
			display : '规格型号',
			sortable: true
		},{
			name: 'planNum',
			display: '数量',
			sortable: true,
			width : 60
		},{
			name: 'qualityNum',
			display: '提交质检数量',
			sortable: true,
			width : 80
		},{
			name: 'qualifiedNum',
			display: '质检合格数量',
			sortable: true,
			width : 80
		},{
			name: 'stockNum',
			display: '入库数量',
			sortable: true,
			width : 60
		},{
			name: 'taskCode',
			display: '生产任务单号',
			sortable: true,
			width : 120
		},{
			name: 'applyDocCode',
			display: '生产申请单号',
			sortable: true
		},{
			name: 'customerName',
			display: '客户名称',
			sortable: true,
			width : 150
		},{
			name: 'productionBatch',
			display: '生产批次',
			sortable: true
		},{
			name: 'planStartDate',
			display: '计划开始时间',
			sortable: true,
			align : 'center'
		},{
			name: 'planEndDate',
			display: '计划结束时间',
			sortable: true,
			align : 'center'
		},{
			name: 'chargeUserName',
			display: '责任人',
			sortable: true,
			align : 'center'
		},{
			name: 'saleUserName',
			display: '销售代表',
			sortable: true,
			align : 'center'
		},{
			name: 'deliveryDate',
			display: '交货日期',
			sortable: true,
			align : 'center'
		},{
			name: 'remark',
			display: '备注',
			sortable: true,
			width : 350
		}],

		//扩展菜单
		buttonsEx : [{
			name : 'excelOut',
			text : "导出",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=produce_plan_produceplan&action=toExcelOut"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=800");
			}
		}],

		//扩展右键菜单
		menusEx : [{
			text : '打印',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=produce_plan_produceplan&action=toView&id=' + row.id ,'1');
			}
		},{
			text : '库存信息',
			icon : 'view',
			action : function(row) {
				showModalWin("index1.php?model=produce_apply_produceapply&action=toStatisticsProduct&code=" + row.productCode ,'1');
			}
		},{
			text : '导出',
			icon : 'excel',
			action : function(row) {
				if (row) {
					window.open("?model=produce_plan_produceplan&action=excelOutOne&id=" + row.id);
				}
			}
		},{
			text : '确定工序',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.docStatus == '0') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showModalWin('?model=produce_plan_produceplan&action=toSureProcess&id=' + row.id ,'1');
			}
		},{
			text : '变更',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.docStatus != '4' && row.docStatus != '5' && row.docStatus != '6') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showModalWin('?model=produce_plan_produceplan&action=toEdit&id=' + row.id ,'1');
			}
		},{
			text : '进度反馈',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.docStatus == '1') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showModalWin('?model=produce_plan_produceplan&action=toFeedback&id=' + row.id ,'1');
			}
		},{
			text : '关闭申请',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.docStatus == '0' || row.docStatus == '1')&&(row.ExaStatus!='部门审批'||row.ExaStatus!='完成')) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showThickboxWin('?model=produce_plan_produceplan&action=toClose&id=' + row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=750");
			}
		},{
			text : '取消',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.docStatus == '0' || row.docStatus == '1')&&(row.ExaStatus!='部门审批'||row.ExaStatus!='完成')) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if (confirm('取消后，任务单返回到生产任务里面等待，是否确定取消？')) {
					$.ajax({
						type : 'POST',
						url : '?model=produce_plan_produceplan&action=toCancel',
						data : {
							id : row.id
						},
						success : function(data) {
							if (data == 0) {
								alert('取消失败');
							} else {
								alert("已取消！");
								show_page();
							}
							return false;
						}
					});
				}
			}
		},{
			name: 'aduit',
			text: '关闭审批情况',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_produce_produceplan&pid=" + row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

		//下拉过滤
		comboEx : comboEx,

		toViewConfig: {
			toViewFn : function(p ,g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_produceplan&action=toViewTab&id=" + get[p.keyField] ,'1');
				}
			}
		},

		searchitems: [{
			name: 'docCode',
			display: '单据编号'
		},{
			name: 'docDate',
			display: '单据日期'
		},{
			name: 'proType',
			display: '物料类型'
		},{
			name: 'productName',
			display: '物料名称'
		},{
			name: 'productCode',
			display: '物料编码'
		},{
			name : 'pattern',
			display : '规格型号'
		},{
			name: 'taskCode',
			display: '生产任务单号'
		},{
			name: 'relDocCode',
			display: '合同编号'
		},{
			name: 'applyDocCode',
			display: '生产申请单号'
		},{
			name: 'customerName',
			display: '客户名称'
		},{
			name: 'productionBatch',
			display: '生产批次'
		},{
			name: 'planStartDate',
			display: '计划开始时间'
		},{
			name: 'planEndDate',
			display: '计划结束时间'
		},{
			name: 'chargeUserName',
			display: '责任人'
		},{
			name: 'urgentLevel',
			display: '优先级'
		},{
			name: 'saleUserName',
			display: '销售代表'
		},{
			name: 'deliveryDate',
			display: '交货日期'
		}]
	});
});