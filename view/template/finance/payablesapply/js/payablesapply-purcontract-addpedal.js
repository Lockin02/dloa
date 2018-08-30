
$(function() {

	// 单选客户
	$("#supplierName").yxcombogrid_supplier({
		hiddenId : 'supplierId',
		isShowButton : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(){
					$("#objCode").yxcombogrid_purchcontract('remove');

					$("#objCode").val('');
					$("#objId").val('');
					initPurcontract();
			  	}
			}
		}
	});
});

//初始化订单
function initPurcontract(){
	$("#objCode").yxcombogrid_purchcontract({
		hiddenId :  'objId',
		height : 250,
		width : 650,
		gridOptions : {
			isTitle : true,
			action : 'purDetailPageJson',
			param : { "csuppId" : $("#supplierId").val() , "sendUserId" : $("#sendUserId").val() ,'cannotpayed' : 1 , 'state' : 7 } ,
			event : {
				'row_check' : function(){

			  	}
			},colModel : [{
					display : 'id',
					name : 'id',
					hide : true,
					width:130
				},{
					display : '订单编号',
					name : 'hwapplyNumb',
					width:100
				},{
					display : '采购员',
					name : 'sendName',
					width:80
				},{
					display : '供应商名称',
					name : 'suppName',
					width:150
				},{
					display : '供应商Id',
					name : 'suppId',
					hide : true
				},{
					display : '单据金额',
					name : 'allMoney',
					process : function(v){
						return moneyFormat2(v);
					},
					width:80
				},{
					display : '已付金额',
					name : 'payed',
					process : function(v){
						return moneyFormat2(v);
					},
					width:80
				}, {
		            name: 'allMoneyCur',
		            display: '本位币金额',
		            sortable: true,
		            process: function (v) {
		                if (v >= 0) {
		                    return moneyFormat2(v);
		                } else {
		                    return "<span class='red'>" + moneyFormat2(v) + "</span>";
		                }
		            },
		            width: 80
		        }, {
		            name: 'currency',
		            display: '付款币种',
		            sortable: true,
		            width: 60
		        }, {
		            name: 'rate',
		            display: '汇率',
		            sortable: true,
		            width: 60
		        }, {
					display : '提交发票金额',
					name : 'handInvoiceMoney',
					process : function(v){
						return moneyFormat2(v);
					},
					width:80
				}
			]
		}
	});
}


//打开无源单类型单据
function openNoSourceType(){
//	showModalWin("?model=finance_payablesapply_payablesapply&action=toAdd");
	showModalWin("?model=finance_payablesapply_payablesapply&action=toAddDept&sourceType=YFRK-04");
}


//打开采购类型付款单据
function openPurcontract(){
	if($("#objId").val() == ""){
		alert("请选择对应采购订单");
		return false;
	}
	showModalWin("?model=finance_payablesapply_payablesapply&action=toAddforObjType&addType=push&objType=YFRK-01&objId=" + $("#objId").val());
}

function show_page(){
	parent.show_page();
	closeFun();
}
