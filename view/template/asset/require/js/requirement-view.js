$(document).ready(function() {
	$("#itemTable").yxeditgrid({
		url : '?model=asset_require_requireitem&action=listByRequireJson',
		objName : 'requirement[items]',
		type:'view',
		param : {
			mainId : $("#id").val()
		},
		isAddOneRow : true,
		colModel : [{
			display : 'id',
			name : 'id',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '物料编号',
			name : 'productCode',
			tclass : 'txt'
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'txt'
		}, {
			display : '物料金额',
			name : 'productPrice',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '物料品牌',
			name : 'brand',
			tclass : 'txt'
		}, {
			display : '规格型号',
			name : 'spec',
			tclass : 'txt'
		}, {
			display : '设备名称',
			name : 'name',
			tclass : 'txt'
		}, {
			display : '设备描述',
			name : 'description',
			validation : {
				required : true
			},
			tclass : 'txt'
		}, {
			display : '数量',
			name : 'number',
			validation : {
				required : true ,
				custom : ['onlyNumber']
			},
			tclass : 'txtshort'
		}, {
			display : '预计金额',
			name : 'expectAmount',
			tclass : 'txtshort',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '预计交货日期',
			name : 'dateHope',
			type:'date',
			validation : {
				required : true
			},
			tclass : 'txtshort'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}, {
			display : '询价金额',
			name : 'inquiryAmount',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '行政意见',
			name : 'suggestion',
			type : 'textarea'
		}]
	})
})
