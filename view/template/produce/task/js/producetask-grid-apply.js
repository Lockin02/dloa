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
	$("#producetaskGrid").yxgrid({
		model : 'produce_task_producetask',
		title : '生产任务',
		isDelAction : false,
		isEditAction : false,
		isOpButton : false,
		showcheckbox : false,
		param : {
			applyDocId : $("#applyDocId").val()
		},

		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'relDocCode',
			display : '合同(源单)编号',
			sortable : true
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
					case '3' : return "关闭";break;
					case '4' : return "已完成";break;
					default : return "--";
				}
			}
		},{
			name : 'proType',
			display : '物料类型',
			sortable : true,
			width : 90
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

		//扩展右键菜单
		menusEx : [],

		//下拉过滤
		comboEx : [{
			text : '单据状态',
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
			},{
				text : '关闭',
				value : '3'
			},{
				text : '已完成',
				value : '4'
			}]
		}],

		toAddConfig: {
			toAddFn : function(p, g) {
				showModalWin("?model=produce_task_producetask&action=toAddByNeed&applyId=" + $("#applyDocId").val() ,'1');
			}
		},
		toViewConfig: {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_task_producetask&action=toView&id=" + get[p.keyField] ,'1');
				}
			}
		},

		searchitems : [{
			name : 'docCode',
			display : '单据编号'
		},{
			name : 'productCode',
			display : '物料编码'
		},{
			name : 'productName',
			display : '物料名称'
		},{
			name : 'purpose',
			display : '用途'
		},{
			name : 'technology',
			display : '工艺'
		},{
			name : 'fileNo',
			display : '文件编号'
		},{
			name : 'customerName',
			display : '客户名称'
		},{
			name : 'relDocCode',
			display : '合同编号'
		},{
			name : 'productionBatch',
			display : '生产批次'
		},{
			name : 'docUserName',
			display : '下单者'
		},{
			name : 'docDate',
			display : '下单日期'
		},{
			name : 'recipient',
			display : '接收人'
		},{
			name : 'recipientDate',
			display : '接收日期'
		},{
			name : 'saleUserName',
			display : '销售代表'
		}]
	});
});