var show_page = function(page) {
	$("#producetaskGrid").yxgrid("reload");
};
/**
 * 产品配置查看
 *
 * @param thisVal
 * @param goodsName
 */
function showGoodsConfig(thisVal) {

	url = "?model=goods_goods_properties&action=toChooseView" + "&cacheId="
			+ thisVal;
	// + "&goodsName=" + goodsName;

	var sheight = screen.height - 300;
	var swidth = screen.width - 400;
	var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth
			+ "px;status:yes;scroll:yes;resizable:yes;center:yes";

	showModalDialog(url, '', winoption);
}

/**
 * LICENSE 查看方法
 *
 * @param thisVal
 */
function showLicense(thisVal) {
	url = "?model=yxlicense_license_tempKey&action=toViewRecord" + "&id="
			+ thisVal;

	var sheight = screen.height - 200;
	var swidth = screen.width - 400;
	var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth
			+ "px;status:yes;scroll:yes;resizable:yes;center:yes";

	showModalDialog(url, '', winoption);
}

$(function() {
	//数据过滤条件
	var param = {};
	var comboEx = [];
	if ($("#taskType").val() == 'finish') {
		param = {
			docStatusIn : '4'
		};
	} else {
		param = {
			docStatusIn : '0,1,2,3'
		};
		var comboExArr = {
			text : '任务状态',
			key : 'docStatus',
			data : [{
				text : '未接收',
				value : '0'
			},{
				text : '已接收',
				value : '1'
			},{
				text : '已制定计划',
				value : '2'
			}]
		};
		comboEx.push(comboExArr);
	}

	$("#producetaskGrid").yxgrid({
		model : 'produce_task_producetask',
		param : param,
		title : '生产任务',
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		isOpButton : false,
		showcheckbox : false,

		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'docCode',
			display : '单据编号',
			sortable : true,
			width : 120,
			process : function (v ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_task_producetask&action=toView&id=" + row.id + "\",1)'>" + v + "</a>";
			}
		},{
			name : 'docStatus',
			display : '任务状态',
			sortable : true,
			width : '60',
			process : function(v ,row) {
				switch (v) {
					case '0' : return "未接收";break;
					case '1' : return "已接收";break;
					case '2' : return "已制定计划";break;
					case '3' : return "执行中";break;
					case '4' : return "已完成";break;
					default : return "--";
				}
			}
		},{
			name : 'productCode',
			display : '物料编码',
			sortable : true,
			width : 90
		},{
			name : 'productName',
			display : '物料名称',
			sortable : true,
			width : 150
		},{
			name : 'purpose',
			display : '用途',
			sortable : true
		},{
			name : 'technology',
			display : '工艺',
			sortable : true
		},{
			name : 'fileNo',
			display : '文件编号',
			sortable : true
		},{
			name : 'customerName',
			display : '客户名称',
			sortable : true,
			width : 150
		},{
			name : 'relDocCode',
			display : '合同编号',
			sortable : true
		},{
			name : 'productionBatch',
			display : '生产批次',
			sortable : true
		},{
			name : 'docUserName',
			display : '下单者',
			sortable : true
		},{
			name : 'docDate',
			display : '下单日期',
			sortable : true
		},{
			name : 'recipient',
			display : '接收人',
			sortable : true
		},{
			name : 'recipientDate',
			display : '接收日期',
			sortable : true
		},{
			name : 'saleUserName',
			display : '销售代表',
			sortable : true
		},{
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 250
		}],
	buttonsEx : [{
			name : 'add',
			text : "交货计划报表",
			icon : 'search',
			action : function(row) {
				showModalWin('?model=produce_apply_produceapply&action=toSendplanReport',1);
			}
		},{
			name : 'add',
			text : "生产计划报表",
			icon : 'search',
			action : function(row) {
				showModalWin('?model=produce_plan_produceplan&action=toProduceplanReport',1);
			}
		}],

		//扩展右键菜单
		menusEx : [{
			text : '接收任务',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.docStatus == '0') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if (window.confirm("确认要接收?")) {
					$.ajax({
						type : "POST",
						url : "?model=produce_task_producetask&action=receiveTask",
						data : {
							id : row.id
						},
						success : function(msg) {
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
		},{
			text : '制定生产计划',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.docStatus != '0') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showModalWin('?model=produce_plan_produceplan&action=toAddByTask&taskId=' + row.id ,'1');
			}
		}],

		//下拉过滤
		comboEx : comboEx,

		toViewConfig: {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_task_producetask&action=toViewTab&id=" + get[p.keyField] ,'1');
				}
			}
		},

		searchitems : [{
			display : "单据编号",
			name : 'docCode'
		},{
			display : "物料名称",
			name : 'productName'
		},{
			display : "物料编码",
			name : 'productCode'
		},{
			display : "用途",
			name : 'purpose'
		},{
			display : "工艺",
			name : 'technology'
		},{
			display : "文件编号",
			name : 'fileNo'
		},{
			display : "客户名称",
			name : 'customerName'
		},{
			display : "合同编号",
			name : 'relDocCode'
		},{
			display : "生产批次",
			name : 'productionBatch'
		},{
			display : "下单者",
			name : 'docUserName'
		},{
			display : "下单日期",
			name : 'docDate'
		},{
			display : "接收人",
			name : 'recipient'
		},{
			display : "接收日期",
			name : 'recipientDate'
		},{
			display : "销售代表",
			name : 'saleUserName'
		}]
	});
});