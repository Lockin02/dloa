$(document).ready(function() {
	$("#purchaseProductTable").yxeditgrid({
		objName:'scrap[item]',
		url:'?model=asset_disposal_scrapitem&action=listJson',
		type:'view',
		param:{allocateID:$("#allocateID").val()},
	colModel : [
		{
			display:'报废资产编码',
			name : 'assetCode'
		}, {
			display:'资产名称',
			name : 'assetName'
		},{
			display:'规格型号',
			name : 'spec'
		}, {
			display:'购置日期',
			name : 'buyDate'
		}, {
			display:'资产原值',
			name : 'origina',
       		 //列表格式化千分位
       		 process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display:'残值',
			name : 'salvage',
        	//列表格式化千分位
        	process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '净值',
			name : 'netValue',
			tclass : 'txtshort',
			readonly : true,
			type : 'money'
		}, {
			display:'已提折旧',
			name : 'depreciation',
       		 //列表格式化千分位
        	process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display:'备注',
			name : 'remark'
		}]
   })
});