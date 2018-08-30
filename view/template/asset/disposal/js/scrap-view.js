$(document).ready(function() {
	$("#purchaseProductTable").yxeditgrid({
		objName:'scrap[item]',
		url:'?model=asset_disposal_scrapitem&action=listJson',
		type:'view',
		param:{allocateID:$("#allocateID").val()},
	colModel : [
		{
			display:'�����ʲ�����',
			name : 'assetCode'
		}, {
			display:'�ʲ�����',
			name : 'assetName'
		},{
			display:'����ͺ�',
			name : 'spec'
		}, {
			display:'��������',
			name : 'buyDate'
		}, {
			display:'�ʲ�ԭֵ',
			name : 'origina',
       		 //�б��ʽ��ǧ��λ
       		 process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display:'��ֵ',
			name : 'salvage',
        	//�б��ʽ��ǧ��λ
        	process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '��ֵ',
			name : 'netValue',
			tclass : 'txtshort',
			readonly : true,
			type : 'money'
		}, {
			display:'�����۾�',
			name : 'depreciation',
       		 //�б��ʽ��ǧ��λ
        	process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display:'��ע',
			name : 'remark'
		}]
   })
});