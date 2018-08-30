$(function(){
	var type = $("#type").val();
	var id = $("#id").val();
	if(($('#contractId').val()==0||$('#contractId').val()=='')&&(type=='allocation'||type=='backAllocation')){
		alert('���β���Ϊ���������ĵ��ݣ��޹�����¼��')
		closeFun();
	}else{
		switch (type) {
			case 'outstock' : outstockItem(id,type);
			break;
			case 'backStock' : backStockItem(id,type);
			break;
			case 'allocation' : allocationItem(id,type);
			break;
			case 'backAllocation' : allocationItem(id,type);
			break;
			case 'instock' : instockItem(id,type);
			break;
		}
	}
});

function outstockItem(id,type){
	$("#itemTable").yxeditgrid("remove");
	$("#itemTable").yxeditgrid({
		url : '?model=stock_serialno_serialno&action=toViewDetailJson',
		type: 'view',
		param : {
			id : id,
			type : type
		},
		isAddOneRow : false,
		colModel : [{
			display : '��������',
			name : 'auditDate',
			tclass : 'txt'
		}, {
			display : '���ݱ��',
			name : 'docCode',
			process : function(e,data){
					return '<a href="#" onclick="javascript:window.open(\'?model=stock_outstock_stockout&action=toView&id='
						+ data.mainId
						+ "&docType="
						+ data.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">'+e+'</a>';
			},
			tclass : 'txtshort'
		}, {
			display : '������',
			name : 'createName',
			tclass : 'txtshort'
		}, {
			display : '��ͬ��',
			name : 'contractCode',
			process : function(e,data){
					return '<a href="#" onclick="javascript:window.open(\'?model=contract_contract_contract&action=toViewShipInfoTab&id='
						+ data.contractId
						+ "&linkId="
						+ data.linkId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">'+e+'</a>';
			},
			tclass : 'txt'
		}],
		isAddOneRow : false
	});
}
/** ���ڲ�ļ��� */
function plusDateInfo2(startDate, endDate) {

	if (startDate != "" && endDate != "") {
		var startYear = startDate.substring(0, startDate.indexOf('-'));
		var startMonth = startDate.substring(5, startDate.lastIndexOf('-'));
		var startDay = startDate.substring(startDate.length, startDate
						.lastIndexOf('-')
						+ 1);

		var endYear = endDate.substring(0, endDate.indexOf('-'));
		var endMonth = endDate.substring(5, endDate.lastIndexOf('-'));
		var endDay = endDate.substring(endDate.length, endDate.lastIndexOf('-')
						+ 1);

		var dayNum = ((Date.parse(endMonth + '/' + endDay + '/' + endYear) - Date
				.parse(startMonth + '/' + startDay + '/' + startYear)) / 86400000);
		return dayNum + 1;
	}
}
function allocationItem(id,type){
	$("#itemTable").yxeditgrid("remove");
	$("#itemTable").yxeditgrid({
		url : '?model=stock_serialno_serialno&action=toViewDetailJson',
		type: 'view',
		param : {
			id : id,
			type : type
		},
		isAddOneRow : false,
		colModel : [{
			display : '������������',
			name : 'beginTime',
			tclass : 'txt'
		}, {
			display : '�黹����',
			name : 'closeTime',
			tclass : 'txt'
		}, {
			display : 'ʹ������',
			name : 'date',
			process : function(e,data){
				return plusDateInfo2(data.beginTime,data.closeTime);
			},
			tclass : 'txt'
		}, {
			display : '�ͻ�����',
			name : 'customerName',
			tclass : 'txt'
		}, {
			display : '���ñ��',
			name : 'contractCode',
			process : function(e,data){
					return '<a href="#" onclick="javascript:window.open(\'?model=projectmanagent_borrow_borrow&action=init&perm=view&id='
						+ data.contractId
						+ "&type="
						+ data.type
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">'+e+'</a>';
			},
			tclass : 'txtshort'
		}, {
			display : '������',
			name : 'applyName',
			tclass : 'txtshort'
		}],
		isAddOneRow : false
	});
}

function instockItem(id,type){
	$("#itemTable").yxeditgrid("remove");
	$("#itemTable").yxeditgrid({
		url : '?model=stock_serialno_serialno&action=toViewDetailJson',
		type: 'view',
		param : {
			id : id,
			type : type
		},
		isAddOneRow : false,
		colModel : [{
			display : '��������',
			name : 'auditDate',
			tclass : 'txt'
		}, {
			display : '���ݱ��',
			name : 'docCode',
			process : function(e,data){
					return '<a href="#" onclick="javascript:window.open(\'?model=stock_instock_stockin&action=toView&id='
						+ data.mainId
						+ "&docType="
						+ data.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">'+e+'</a>';
			},
			tclass : 'txtshort'
		}, {
			display : '������',
			name : 'createName',
			tclass : 'txtshort'
//		}, {
//			display : '��ͬ��',
//			name : 'contractCode',
//			process : function(e,data){
//					return '<a href="#" onclick="javascript:window.open(\'?model=contract_contract_contract&action=toViewShipInfoTab&id='
//						+ data.contractId
//						+ "&linkId="
//						+ data.linkId
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">'+e+'</a>';
//			},
//			tclass : 'txt'
		}],
		isAddOneRow : false
	});
}