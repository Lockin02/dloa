var show_page = function (page) {
	$("#producetaskGrid").yxgrid("reload");
};

/**
 * 产品配置查看
 * @param thisVal
 */
function showGoodsConfig(thisVal) {

	url = "?model=goods_goods_properties&action=toChooseView" + "&cacheId=" + thisVal;

	var sheight = screen.height - 300;
	var swidth = screen.width - 400;
	var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth +
		"px;status:yes;scroll:yes;resizable:yes;center:yes";

	showModalDialog(url, '', winoption);
}

/**
 * LICENSE 查看方法
 *
 * @param thisVal
 */
function showLicense(thisVal) {
	url = "?model=yxlicense_license_tempKey&action=toViewRecord" + "&id=" + thisVal;

	var sheight = screen.height - 200;
	var swidth = screen.width - 400;
	var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth +
		"px;status:yes;scroll:yes;resizable:yes;center:yes";

	showModalDialog(url, '', winoption);
}

$(function () {
	//查看全部待执行生产任务权限
	var allTask = $("#allTask").val() == '1' ? true : false;
	//数据过滤条件
	var param = {};
	var comboEx = [{
		text: "任务类型",
		key: 'taskTypeCode',
		datacode: 'RWLX'
    }];
	if ($("#taskType").val() == 'finish') {
		if(allTask){
			param = {
					docStatusIn: '3,4'
				};
		}else{
			param = {
					recipientId: $("#userId").val(),
					docStatusIn: '3,4'
				};
		}
		var comboExArr = {
			text: '任务状态',
			key: 'docStatus',
			data: [{
				text: '关闭',
				value: '3'
			}, {
				text: '已完成',
				value: '4'
			}]
		};
		comboEx.push(comboExArr);
	} else {
		if(allTask){
			param = {
					docStatusIn: '0,1,2,4,5'
				};
		}else{
			param = {
					recipientId: $("#userId").val(),
					docStatusIn: '0,1,2,4,5'
				};
		}
		var comboExArr = {
			text: '任务状态',
			key: 'docStatusArr',
            value: '0,1,5',
			data: [{
				text: '未接收',
				value: '0'
			}, {
				text: '已接收',
				value: '1'
			}, {
				text: '已返工',
				value: '5'
			}, {
				text: '已制定计划',
				value: '2,4'
			}, {
				text: '未完成',
				value: '0,1,5'
			}]
		};
		comboEx.push(comboExArr);
	}

	$("#producetaskGrid").yxgrid({
		model: 'produce_task_producetask',
		param: param,
		title: '生产任务',
		isAddAction: false,
		isDelAction: false,
		isEditAction: false,
		isOpButton: false,
		showcheckbox: false,
		customCode: 'produce_task_producetask',

		// 列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name : 'isMeetProduction',
			display : '是否满足生产',
			sortable : false,
			width : 80,
			align : 'center',
			process : function(v) {
				if (v == '0') {
					return "<img src='images/icon/icon073.gif' title='未确认'/>";
				} else if (v == '1'){
					return "<img src='images/icon/green.gif' title='满足'/>";
				} else if (v == '2'){
					return "<img src='images/icon/red.gif' title='不满足'/>";
				} else if (v == '3'){
					return "<img src='images/icon/cicle_yellow.png' title='部分满足'/>";
				}
			}
		},  {
			name : 'docStatus',
			display : '是否返工',
			sortable : false,
			width : 80,
			align : 'center',
			process : function(v) {
				if (v == '5') {
					return "<img src='images/icon/red.gif' title='已返工'/>";
				} else {
					return "<img src='images/icon/icon073.gif' title='否'/>";
				}
			}
		}, {
			name : 'isOutPlan',
			display : '是否纳入发货计划',
			sortable : false,
			width : 100,
			align : 'center',
			process : function(v) {
				if (v == '1') {
					return "<img src='images/icon/green.gif' title='是'/>";
				} else {
					return "<img src='images/icon/icon073.gif' title='否'/>";
				}
			}
		}, {
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
		}, {
			name: 'docCode',
			display: '单据编号',
			sortable: true,
			width: 150,
			process: function (v, row) {
				if (row.docStatus == 0) {
					v = '<img title="新增的生产任务" src="images/new.gif">' + v;
				}
				return "<a href='#' onclick='showModalWin(\"?model=produce_task_producetask&action=toViewTab&id=" + row.id + "&relDocId=" + row.relDocId +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'customerName',
			display: '客户名称',
			sortable: true,
			width: 150
		}, {
			name: 'docStatus',
			display: '任务状态',
			sortable: true,
			width: '60',
			process: function (v, row) {
				switch (v) {
				case '0':
					return "未接收";
					break;
				case '1':
					return "已接收";
					break;
				case '5':
					return "已返工";
					break;
				case '2':
					return "已制定计划";
					break;
				case '3':
					return "关闭";
					break;
				case '4':
					return "已完成";
					break;
				default:
					return "--";
				}
			}
		}, {
			name: 'proType',
			display: '物料类型',
			sortable: true,
			width: 150
		}, {
			name: 'purpose',
			display: '用途',
			sortable: true
		}, {
			name: 'technology',
			display: '工艺',
			sortable: true
		}, {
			name: 'fileNo',
			display: '文件编号',
			sortable: true
		},{
			name: 'productionBatch',
			display: '生产批次',
			sortable: true
		}, {
			name: 'docUserName',
			display: '下单者',
			sortable: true
		}, {
			name: 'docDate',
			display: '下单日期',
			sortable: true
		}, {
			name: 'recipient',
			display: '接收人',
			sortable: true
		}, {
			name: 'recipientDate',
			display: '接收日期',
			sortable: true
		}, {
			name: 'saleUserName',
			display: '销售代表',
			sortable: true
		}, {
			name: 'remark',
			display: '备注',
			sortable: true,
			width: 250
		}],

		//扩展菜单
		buttonsEx: [{
			name: 'excelOut',
			text: "导出",
			icon: 'excel',
			action: function (row) {
				showThickboxWin("?model=produce_task_producetask&action=toExcelOut&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
			}
		}],

		//扩展右键菜单
		menusEx: [
//		{
//			text : '打印',
//			icon : 'view',
//			action : function(row) {
//				showModalWin('?model=produce_task_producetask&action=toView&id=' + row.id ,'1');
//			}
//		}
//		,{
//			text: '库存信息',
//			icon: 'view',
//			action: function (row) {
//				showModalWin("index1.php?model=produce_apply_produceapply&action=toStatisticsProduct&code=" + row.productCode, '1');
//			}
//		},
		{
			text: '接收任务',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.docStatus == '0') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (window.confirm("确认要接收?")) {
					$.ajax({
						type: "POST",
						url: "?model=produce_task_producetask&action=receiveTask",
						data: {
							id: row.id
						},
						success: function (msg) {
							if (msg == '0') {
								alert('接收成功！');
								show_page();
							} else {
								alert('接收失败！');
							}
						}
					});
				}
			}
		}, {
			text: '制定生产计划',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.docStatus == '1' || row.docStatus == '2' || row.docStatus == '5') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showModalWin('?model=produce_plan_produceplan&action=toAddByTask&taskId=' + row.id, '1');
			}
		}, {
			text: '关闭',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.docStatus == '0' || row.docStatus == '1' || row.docStatus == '2') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (window.confirm("确认要关闭?")) {
					$.ajax({
						type: "POST",
						url: "?model=produce_task_producetask&action=closedTask",
						data: {
							id: row.id
						},
						success: function (msg) {
							if (msg == '0') {
								alert('关闭成功！');
								show_page();
							} else {
								alert('关闭失败！');
							}
						}
					});
				}
			}
		}],

		//下拉过滤
		comboEx: comboEx,

		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_task_producetask&action=toViewTab&id=" + get[p.keyField] + "&relDocId=" + get.relDocId, '1');
				}
			}
		},

		searchitems: [{
			display: "单据编号",
			name: 'docCode'
		}, {
			display: "物料类型",
			name: 'proType'
		}, {
			display: "用途",
			name: 'purpose'
		}, {
			display: "工艺",
			name: 'technology'
		}, {
			display: "文件编号",
			name: 'fileNo'
		}, {
			display: "客户名称",
			name: 'customerName'
		}, {
			display: "合同编号",
			name: 'relDocCode'
		}, {
			display: "生产批次",
			name: 'productionBatch'
		}, {
			display: "下单者",
			name: 'docUserName'
		}, {
			display: "下单日期",
			name: 'docDate'
		}, {
			display: "接收人",
			name: 'recipient'
		}, {
			display: "接收日期",
			name: 'recipientDate'
		}, {
			display: "销售代表",
			name: 'saleUserName'
		}]
	});
});