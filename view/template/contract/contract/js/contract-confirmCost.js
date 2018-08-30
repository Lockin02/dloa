$(function() {
	 var isApp = $("#isApp").val();
//产品清单
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
			display : '产品线',
			sortable : true,
			width : 100
		},{
			name : 'exeDeptName',
			display : '执行区域',
			sortable : true,
			width : 100
		},{
			name : 'goodsTypeId',
			display : '产品类型',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == "11") {
					return "销售类产品";
				} else if (v == "17") {
					return "服务类产品";
				} else if (v == "18") {
					return "研发类产品";
				} else {
					return "--";
				}
			}
		}, {
			display : '产品名称',
			name : 'conProductName',
			tclass : 'txt',
			process : function(v, row) {
				return '<a title=点击查看发货清单 href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=toViewEqu&id='
						+ row.id
						+ '&contractId='
						+ $("#contractId").val()
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			display : '产品Id',
			name : 'conProductId',
			tclass : 'txt',
			type : 'hidden'
		}, {
			display : '产品描述',
			name : 'conProductDes',
			tclass : 'txt'
		}, {
			display : '加密配置Id',
			name : 'license',
			type : 'hidden'
		}, {
			name : 'licenseButton',
			display : '加密配置',
			type : 'hidden',
			process : function(v, row) {
				if (row.license != "") {
					return "<a href='javascript:void(0)' onclick='showLicense(\""
							+ row.license + "\")'>加密配置</a>";
				}
			}
		}, {
			display : '产品配置Id',
			name : 'deploy',
			type : 'hidden'
		}, {
			name : 'deployButton',
			display : '产品配置',
			process : function(v, row) {
				if (row.deploy != "") {
					return "<a href='javascript:void(0)' onclick='showGoods(\""
							+ row.deploy
							+ "\",\""
							+ row.conProductName
							+ "\")'>产品配置</a>";
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
