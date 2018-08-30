$(function(){
	countAll();
});

//主表查看外购入库单
function toLoca(){
	var sourcetType = $("#sourceType").val();
	if(sourcetType == 'CGFPYD-02'){
		sourcetType = 'RKPURCHASE';
	}
	url = '?model=stock_instock_stockin&action=viewByDocCode'
			+ '&docType=' + sourcetType
			+ '&docCode=' + $("#menuNo").val()
	;
	showModalWin(url,1);
}


//获取选择单据去向
function toSource(objId,objType){
	switch(objType){
		case 'CGFPYD-01': toPur(objId);break;
		case 'CGFPYD-02': toStockIn(objId);break;
		default : alert('未配置的对象类型');
	}
}

//进入外购入库单
function toStockIn(objId){
    var skey = "";
    $.ajax({
	    type: "POST",
	    url: "?model=stock_instock_stockin&action=md5RowAjax",
	    data: {"id" : objId},
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	var url = "index1.php?model=stock_instock_stockin&action=toView&id="+objId+"&docType=RKPURCHASE&skey=" + skey;
	showModalWin(url,1);
}

//进入采购订单
function toPur(objId){
	var skey = "";
    $.ajax({
	    type: "POST",
	    url: "?model=purchase_contract_purchasecontract&action=md5RowAjax",
	    data: {"id" : objId},
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	var url = '?model=purchase_contract_purchasecontract&action=toTabRead'
			+ '&id=' + objId
			+ '&skey=' + skey
	;
	showModalWin(url,1);
}




function countAll(){
	//物料清单记录数
	var $invnumber = $('#invnumber').val();
	//当前行总金额
	var  thisAmount = 0;
	//当前行税金
	var thisAssessment = 0;
	//当前行金额(不含税)
	var thisAllCount = 0;

	//表单内总金额(不含税)
	var allAmount = 0;
	//表单含税总金额
	var allCountAll = 0;
	//表单内总税额
	var allAssessment = 0;
	//表单内总数量
	var allNumber = 0;

	for(var i = 1;i <= $invnumber;i++){
		//判断金额是否存在
		thisAmount = $('#amount' + i).val() * 1;

		thisNumber = $("#number"+i).val()*1;

		thisAssessment = $("#assessment"+i).val()*1;

		thisAllCount = $("#allCount"+i).val()*1;

		allAssessment = accAdd(allAssessment,thisAssessment,4);

		allCountAll = accAdd(allCountAll,thisAllCount,4);

		allAmount = accAdd(allAmount,thisAmount,4);

		allNumber = accAdd(allNumber,thisNumber);
	}

	//单据不含税总金额
	$('#amountAll').html(moneyFormat2(allAmount));

	//单据含税总金额
	$('#allCountAll').html(moneyFormat2(allCountAll));

	//单据税额
	$('#assessmentAll').html(moneyFormat2(allAssessment));

	//数量
	$('#numberAll').html(moneyFormat2(allNumber,0));

	//
	if(allCountAll < 0){

		view_formAssessment = $('#view_formAssessment').html();
		if(view_formAssessment*1 != 0){
			view_formAssessment = '-' + view_formAssessment;
			$('#view_formAssessment').html(view_formAssessment);
		}


		view_amount = "-" + $('#view_amount').html();
		$('#view_amount').html(view_amount);

		view_formCount = "-" +  $('#view_formCount').html();
		$('#view_formCount').html(view_formCount);
	}
}