$(function (){
$("#linkmanListInfo").yxeditgrid({
	objName : 'contract[linkman]',
	url:'?model=contract_contract_linkman&action=listJsonLimit',
	type:'view',
	param:{
		'contractId':$("#contractId").val(),
		'prinvipalId':$("#prinvipalId").val(),
		'createId':$("#createId").val(),
		'areaPrincipalId':$("#areaPrincipalId").val(),
			'isTemp' : '1',
			'isDel' : '0'
	},
	tableClass : 'form_in_table',
	colModel : [{
		display : '客户联系人',
		name : 'linkmanName',
		tclass : 'txt'
	}, {
		display : '联系人ID',
		name : 'linkmanId',
		type : 'hidden'
	}, {
		display : '电话',
		name : 'telephone',
		tclass : 'txt'
	}, {
		display : 'QQ',
		name : 'QQ',
		tclass : 'txt'
	}, {
		display : '邮箱',
		name : 'Email',
		tclass : 'txt'
	}, {
		display : '备注',
		name : 'remark',
		tclass : 'txt'
	}]
});

  //产品清单
 $("#productInfo").yxeditgrid({
		objName : 'contract[product]',
		url:'?model=contract_contract_product&action=listJsonLimit',
    	type:'view',
    	tableClass : 'form_in_table',
    	param:{
        	'contractId' : $("#contractId").val(),
        	'dir' : 'ASC',
			'prinvipalId':$("#prinvipalId").val(),
			'createId':$("#createId").val(),
			'areaPrincipalId':$("#areaPrincipalId").val(),
			'isTemp' : '1',
			'isDel' : '0'
        },
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
			}
		}, {
			display : '金额',
			name : 'money',
			tclass : 'txtshort',
			process : function(v){
				return moneyFormat2(v);
			}
//		}, {
//			display : '保修期',
//			name : 'warrantyPeriod',
//			tclass : 'txtshort'
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
 //发货清单
 $("#equInfo").yxeditgrid({
		objName : 'contract[equ]',
		url:'?model=contract_contract_equ&action=listJson',
    	type:'view',
    	tableClass : 'form_in_table',
    	param:{
    	    'contractId':$("#contractId").val(),
			'isTemp' : '1',
			'isDel' : '0'
			},
		colModel : [{
			display : '物料编号',
			name : 'productCode',
			tclass : 'txt'
		},{
			display : '物料名称',
			name : 'productName',
			tclass : 'txt'
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txtshort'
		}, {
			display : '单价',
			name : 'price',
			type : 'hidden',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '金额',
			name : 'money',
			type : 'hidden',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '保修期',
			name : 'warrantyPeriod',
			tclass : 'txtshort'
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
		}]
	});
//开票计划
	$("#invoiceListInfo").yxeditgrid({
		objName : 'contract[invoice]',
		url:'?model=contract_contract_invoice&action=listJson',
        type:'view',
        tableClass : 'form_in_table',
    	param:{
    	  contractId:$("#contractId").val(),
			'isTemp' : '1',
			'isDel' : '0'
    	  },
		colModel : [{
			display : '开票金额',
			name : 'money',
			tclass : 'txt'
		}, {
			display : '软件金额',
			name : 'softMoney',
			tclass : 'txt'
		}, {
			display : '开票类型',
			name : 'iType',
			type : 'select',
			datacode : 'FPLX'
		}, {
			display : '开票日期',
			name : 'invDT',
			type : 'date'
		}, {
			display : '开票内容',
			name : 'remark',
			tclass : 'txt'
		}]
	});

//收款计划
	$("#incomeListInfo").yxeditgrid({
		objName : 'contract[income]',
		url:'?model=contract_contract_receiptplan&action=listJson',
    	type:'view',
    	tableClass : 'form_in_table',
    	param:{
    	   contractId:$("#contractId").val(),
			'isTemp' : '1',
			'isDel' : '0'

    	   },
		colModel : [{
			display : '收款金额',
			name : 'money',
			tclass : 'txt'
		}, {
			display : '收款日期',
			name : 'payDT',
			type : 'date'
		}, {
			display : '收款方式',
			name : 'pType',
			tclass : 'txt'
		}, {
			display : '收款条件',
			name : 'collectionTerms',
			tclass : 'txtlong'
		}]
	});

//培训计划
	$("#trainListInfo").yxeditgrid({
		objName : 'contract[train]',
		url:'?model=contract_contract_trainingplan&action=listJson',
    	type:'view',
    	tableClass : 'form_in_table',
    	param:{contractId:$("#contractId").val(),
			'isTemp' : '1',
			'isDel' : '0'},
		colModel : [{
			display : '培训开始日期',
			name : 'beginDT',
			type : 'date'
		}, {
			display : '培训结束日期',
			name : 'endDT',
			type : 'date'
		}, {
			display : '参与人数',
			name : 'traNum',
			tclass : 'txtshort'
		}, {
			display : '培训地点',
			name : 'adress',
			tclass : 'txtshort'
		}, {
			display : '培训内容',
			name : 'content',
			tclass : 'txtshort'
		}, {
			display : '培训工程师要求',
			name : 'trainer',
			tclass : 'txtshort'
		}]
	});

});
	//表单收缩
  function hideList(listId){
	        var temp = document.getElementById(listId);
			var tempH = document.getElementById(listId+"H");
				if (temp.style.display == ''){
					temp.style.display = "none";
					tempH.style.display = "";
				} else if (temp.style.display == "none"){
					temp.style.display = '';
					tempH.style.display = 'none';
				}
      }
   $(function() {
			var currency = $("#currency").html();
			if (currency != '人民币' && currency != '') {
				document.getElementById("currencyRate").style.display = "";
			}
		});