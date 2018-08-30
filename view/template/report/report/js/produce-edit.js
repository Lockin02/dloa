$(function() {

	$("#stockinfo").yxeditgrid({
		objName : 'produce[stock]',
		url : '?model=report_report_produceinfo&action=listJson',
		param : {
			'produceId' : $("#id").val(),
			'proType' : "有库存"
		},
		isAddOneRow : false,
		tableClass : 'form_in_table',
		colModel : [
		{
			display : '库存情况',
			name : 'proType',
			tclass : 'txt',
			value : "有库存",
			type : 'hidden'
		},{
			display : '需求数量',
			name : 'needNum',
			tclass : 'txtshort'
		}, {
			display : '生成组装',
			name : 'proTime',
			tclass : 'txtshort'
		}, {
			display : '测试',
			name : 'testTime',
			tclass : 'txtshort'
		}, {
			display : '包装',
			name : 'packageTime',
			tclass : 'txtshort'
		}]

	});
	$("#outstockinfo").yxeditgrid({
		objName : 'produce[outstock]',
		url : '?model=report_report_produceinfo&action=listJson',
		param : {
			'produceId' : $("#id").val(),
			'proType' : "无库存"
		},
		isAddOneRow : false,
		tableClass : 'form_in_table',
		colModel : [
		{
			display : '库存情况',
			name : 'proType',
			tclass : 'txtshort',
			value : "无库存",
			type : 'hidden'
		}, {
			display : '物料盘点',
			name : 'needNum',
			tclass : 'txt'
		}, {
			display : '采购时间',
			name : 'proTime',
			tclass : 'txtshort'
		}, {
			display : '外加工时间',
			name : 'testTime',
			tclass : 'txtshort'
		}, {
			display : '入库检测',
			name : 'packageTime',
			tclass : 'txtshort'
		}]

	});
});