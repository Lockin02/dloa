$(function() {
	$("#taskTable").yxeditgrid({
		objName : 'task[taskItem]',
		delTagName : 'isDelTag',
		type : 'view',
		url : '?model=asset_purchase_task_taskItem&action=listJson',
		param : {
			parentId : $("#parentId").val()
		},
		colModel : [{
			display : '物料名称',
			name : 'productName',
			tclass : 'txtshort'
		}, {
			display : '规格',
			name : 'pattem',
			tclass : 'txtshort'
		}, {
			display : '单位',
			name : 'unitName',
			tclass : 'txtshort'
		}, {
			display : '供应商',
			name : 'supplierName',
			tclass : 'txtshort'
		}, {
			display : '采购数量',
			name : 'purchAmount',
			tclass : 'txtshort'
		}, {
			display : '任务数量',
			name : 'taskAmount',
			tclass : 'txtshort'
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			// 列表格式化千分位
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '金额',
			name : 'moneyAll',
			tclass : 'txtshort',
			// 列表格式化千分位
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '希望交货日期',
			name : 'dateHope',
			type : 'date',
			tclass : 'txtshort'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	})

	// 判断是否显示关闭按钮
	if ($("#showBtn").val() == 1) {
		$("#btn").hide();
		$("#hiddenF").hide();
	}
});