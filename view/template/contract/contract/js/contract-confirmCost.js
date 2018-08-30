$(function() {
	 var isApp = $("#isApp").val();
//��Ʒ�嵥
	$("#productInfo").yxeditgrid({
		objName : 'contract[product]',
		url : '?model=contract_contract_product&action=listJsonCost&isApp='+isApp,
		type : 'view',
		tableClass : 'form_in_table',
		param : {
			'contractId' : $("#contractId").val(),
			'dir' : 'ASC',
			'prinvipalId' : $("#prinvipalId").val(),
			'createId' : $("#createId").val(),
			'areaPrincipalId' : $("#areaPrincipalId").val(),
			//			'isTemp' : '0',
			'isDel' : '0',
			'proTypeIdNot' : '11'
		},
		colModel : [{
			name : 'newProLineName',
			display : '��Ʒ��',
			sortable : true,
			width : 100
		},{
			name : 'exeDeptName',
			display : 'ִ������',
			sortable : true,
			width : 100
		},{
			name : 'goodsTypeId',
			display : '��Ʒ����',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == "11") {
					return "�������Ʒ";
				} else if (v == "17") {
					return "�������Ʒ";
				} else if (v == "18") {
					return "�з����Ʒ";
				} else {
					return "--";
				}
			}
		}, {
			display : '��Ʒ����',
			name : 'conProductName',
			tclass : 'txt',
			process : function(v, row) {
				return '<a title=����鿴�����嵥 href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=toViewEqu&id='
						+ row.id
						+ '&contractId='
						+ $("#contractId").val()
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			display : '��ƷId',
			name : 'conProductId',
			tclass : 'txt',
			type : 'hidden'
		}, {
			display : '��Ʒ����',
			name : 'conProductDes',
			tclass : 'txt'
		}, {
			display : '��������Id',
			name : 'license',
			type : 'hidden'
		}, {
			name : 'licenseButton',
			display : '��������',
			type : 'hidden',
			process : function(v, row) {
				if (row.license != "") {
					return "<a href='javascript:void(0)' onclick='showLicense(\""
							+ row.license + "\")'>��������</a>";
				}
			}
		}, {
			display : '��Ʒ����Id',
			name : 'deploy',
			type : 'hidden'
		}, {
			name : 'deployButton',
			display : '��Ʒ����',
			process : function(v, row) {
				if (row.deploy != "") {
					return "<a href='javascript:void(0)' onclick='showGoods(\""
							+ row.deploy
							+ "\",\""
							+ row.conProductName
							+ "\")'>��Ʒ����</a>";
				}
			}
		}]
//		event : {
//			'reloadData' : function(e) {
//				initCacheInfo();
//			}
//		}
	});
	var isdeff = $("#isdeff").val();
	if(isdeff =='1'){
	    $("#confirmMoney_v").attr("readonly",true);
 	    $("#confirmMoney_v").attr('class',"readOnlyTxtNormal");
 	     $("#subBtn").hide();
 	     $("#diffInfo").show();

	}else if(isdeff == '2'){
	    $("#confirmMoney_v").attr("readonly",true);
 	    $("#confirmMoney_v").attr('class',"readOnlyTxtNormal");
 	    $("#deff2").show();
	}

});




function contractview(contractId){
   showModalWin('?model=contract_contract_contract&action=showView&id='
						+ contractId
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
}
