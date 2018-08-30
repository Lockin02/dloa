// 表单收缩
function hideList(listId) {
	var temp = document.getElementById(listId);
	var tempH = document.getElementById(listId + "H");
	if (temp.style.display == '') {
		temp.style.display = "none";
		tempH.style.display = "";
	} else if (temp.style.display == "none") {
		temp.style.display = '';
		tempH.style.display = 'none';
	}
}

$(function() {
	// 退货物料
	$("#backequinfo").yxeditgrid({
		objName : 'exchange[backequ]',
		url:'?model=projectmanagent_exchange_exchangebackequ&action=listJson',
    	type:'view',
    	param:{
        	'exchangeId' : $("#exchangeId").val(),
			'isDel' : '0'
        },
		tableClass : 'form_in_table',
		isAddOneRow:false,
		isAdd : false,
		colModel : [{
			display : '物料编号',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 200
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 400
		}, {
			display : '型号/版本',
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 400
		}, {
			display : '退货数量',
			name : 'number',
			tclass : 'txtshort',
			width : 100
		}, {
			display : '执行数量',
			name : 'executedNum',
			tclass : 'txtshort',
			width : 100
		}]
	});
	// 换货产品
	$("#productInfo").yxeditgrid({
		objName : 'exchange[product]',
		url:'?model=projectmanagent_exchange_exchangeproduct&action=listJson',
    	type:'view',
    	param:{
        	'exchangeId' : $("#exchangeId").val(),
			'isDel' : '0'
        },
		tableClass : 'form_in_table',
		isAddOneRow:false,
		isAdd : false,
		colModel : [{
			display : '产品名称',
			name : 'conProductName',
			tclass : 'txt',
			process : function(v, row) {
							 	return  '<a title=点击查看发货清单 href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=toViewEqu&id='
									+ row.id
									+ '&contractId='
									+ $("#contractId").val()
									+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'+"<font color = '#4169E1'>" + v +"</font>"+'</a>';
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
			display : '数量',
			name : 'number',
			tclass : 'txtshort'
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			process : function(v){
				return moneyFormat2(v);
			},
			type : 'money'
		}, {
			display : '金额',
			name : 'money',
			tclass : 'txtshort',
			process : function(v){
				return moneyFormat2(v);
			},
			type : 'money'
		}, {
			display : '加密配置Id',
			name : 'license',
			type : 'hidden'
		},{
			name : 'licenseButton',
			display : '加密配置',
			process : function(v,row){
				if(row.license != ""){
					return "<a href='#' onclick='showLicense(\""+ row.license + "\")'>查看</a>";
				}
			}
		}, {
			display : '产品配置Id',
			name : 'deploy',
			type : 'hidden'
		},{
			name : 'deployButton',
			display : '产品配置',
			process : function(v,row){
				if(row.deploy != ""){
					return "<a href='#' onclick='showGoods(\""+ row.deploy + "\",\""+ row.conProductName + "\")'>查看</a>";
				}
			}
		}],
		event : {
			'reloadData' : function(e){
				initCacheInfo();
			}
		}
	});
	// 换货物料
	$("#equinfo").yxeditgrid({
		objName : 'return[equ]',
		url:'?model=projectmanagent_exchange_exchangeequ&action=listJson',
    	type:'view',
    	param:{
        	'exchangeId' : $("#exchangeId").val(),
			'isDel' : '0'
        },
		tableClass : 'form_in_table',
		isAddOneRow:false,
		isAdd : false,
		colModel : [{
			display : '物料编号',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 200
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 400
		}, {
			display : '型号/版本',
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 400
		}, {
			display : '换货数量',
			name : 'number',
			tclass : 'txtshort',
			width : 100
		}, {
			display : '执行数量',
			name : 'executedNum',
			tclass : 'txtshort',
			width : 100
		}]
	});
});

// 详细物料成本
function equCoseView() {
	showThickboxWin('?model=contract_contract_contract&action=equCoseView&contractId='
			+ $("#cid").val()
			+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900');
}
