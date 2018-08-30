
$thisInitCode = 'objCode';
$thisInitId = 'objId';
$thisInitName = 'objName';
$thisInitType = 'objType';

/**
 * 公用明细表对象初始化方法
 * 在初始化的同时会除去关联关系
 * @param {} mycount
 */
function initGrid(thisVal,mycount){

	var thisObj = $("#" + $thisInitType + mycount);

	if(thisObj.val() != "" && $("#supplierId").val() == ""){
		alert('请先选择供应商');
		thisObj.val("");
		return false;
	}

	var objCode = $("#objCode" + mycount );
	//清楚下拉表格
	objCode.yxcombogrid_purchcontract('remove');
	objCode.val("");

	$("#" + $thisInitId + mycount ).val("");

	$("#purchaseMoney" + mycount ).val("");
	$("#payed" + mycount ).val("");
	$("#handInvoiceMoney" + mycount ).val("");

	if(thisObj.val() != 'YFRK-01'){
		alert('非采购订单业务请在对应业务中申请付款');
		thisObj.val("");
	}
	//当类型为销售合同时,初始化销售合同grid
	initGridNoClear(mycount);
}

//初始化grid,不会取消关联关系
function initGridNoClear(mycount){
	var thisObj = $("#objType" + mycount);
	//当类型为销售合同时,初始化销售合同grid
	if(thisObj.val() == 'YFRK-01' ){
		if($("#owner").val() == ""){
			$("#objCode"+ mycount).yxcombogrid_purchcontract({
				hiddenId :  'objId'+ mycount,
				height : 250,
				width : 700,
				gridOptions : {
					action : 'purDetailPageJson',
					showcheckbox : false,
					param : { 'csuppId' : $('#supplierId').val(), 'cannotpayed' : 1 , 'state' : 7 } ,
					event : {
						'row_dblclick' : function(mycount){
							return function(e, row, data) {
								$("#oldMoney" + mycount).val(data.canApply);
								$("#money" + mycount).val(data.canApply);
								$("#money" + mycount + "_v").val(moneyFormat2(data.canApply));
								$("#purchaseMoney" + mycount).val(data.allMoney);
								$("#purchaseMoney" + mycount + "_v").val(moneyFormat2(data.allMoney));
								$("#payed" + mycount).val(data.payed);
								$("#payed" + mycount + "_v").val(moneyFormat2(data.payed));
								$("#handInvoiceMoney" + mycount).val(data.handInvoiceMoney);
								$("#handInvoiceMoney" + mycount + "_v").val(moneyFormat2(data.handInvoiceMoney));
								$("#money" + mycount + "_v").focus();
							};
					  	}(mycount)
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
						},{
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
		}else{
			if($("#salesmanId").val() == ""){
				var thisMan = $("#createId").val();
			}else{
				var thisMan = $("#salesmanId").val();
			}
			$("#objCode"+ mycount).yxcombogrid_purchcontract({
				hiddenId :  'objId'+ mycount,
				height : 250,
				width : 700,
				gridOptions : {
					action : 'purDetailPageJson',
					showcheckbox : false,
					param : { 'csuppId' : $('#supplierId').val() , 'createId':thisMan , 'cannotpayed' : 1, 'state' : 7},
					event : {
						'row_dblclick' : function(mycount){
							return function(e, row, data) {
								$("#oldMoney" + mycount).val(data.canApply);
								$("#money" + mycount).val(data.canApply);
								$("#money" + mycount + "_v").val(moneyFormat2(data.canApply));
								$("#purchaseMoney" + mycount).val(data.allMoney);
								$("#purchaseMoney" + mycount + "_v").val(moneyFormat2(data.allMoney));
								$("#payed" + mycount).val(data.payed);
								$("#payed" + mycount + "_v").val(moneyFormat2(data.payed));
								$("#handInvoiceMoney" + mycount).val(data.handInvoiceMoney);
								$("#handInvoiceMoney" + mycount + "_v").val(moneyFormat2(data.handInvoiceMoney));
								$("#money" + mycount + "_v").focus();
							};
					  	}(mycount)
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
						},{
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
	}
}

//编辑时初始化列表gird
$(function(){
	var invnumber = $('#coutNumb').val();
	for(var i = 1;i <= invnumber;i++){
		initGridNoClear(i);
	}
})

