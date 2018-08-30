hookedArr = new Array();

$(function(){
	var ids = $("#ids").val();
	hookedArr = ids.split(",");
	for(var i = 0 ;i < hookedArr.length ; i++){
		$("#" + hookedArr[i]).attr("checked",true);
	}
})

//异步保存数据
function ajaxSave(){
	var thisDate = getDataFun();
	if(thisDate != false){
		$.ajax({
			type : "POST",
			url : "?model=finance_carriedforward_carriedforward&action=carryOutStock",
			data : {
				"data" : thisDate
			},
			success : function(msg) {
				msg = strTrim(msg);
				if (msg == 0) {
					alert("钩稽失败");
				}else if(msg != 'none'){
					alert('钩稽成功');
					hookedArr = msg.split(",");
					$("#ids").val(msg);
				}else{
					alert('钩稽成功');
					hookedArr = [];
					$("#ids").val('');
				}
			}
		});
	}else{
		alert('没有选中值');
		return false;
	}
}


//获取数据组成json数组
function getDataFun(){
	//已选择对象字符串
	dataStr = "";

	//未选择对象字符串
	unDataStr = "";

	//临时存储变量
	var tempKey = "";

	$(":checkbox[name='outStock']").each(function(i,n){
		tempKey = $(this).val();

		if($(this).attr("checked") == true){
			if(hookedArr.indexOf(tempKey) == -1){
				if(dataStr == ""){
					dataStr =  tempKey;
				}else{
					dataStr += "," + tempKey;
				}
			}
		}else{
			if(hookedArr.indexOf(tempKey) != -1){
				if(unDataStr == ""){
					unDataStr =  tempKey;
				}else{
					unDataStr += "," + tempKey;
				}
			}
		}
	});

	if(dataStr == "" && unDataStr == ""){
		return false;
	}else{
		dataJson = {
			"dataStr" : dataStr ,
			"unDataStr" : unDataStr,
			"customerId" : $("#customerId").val(),
			"thisYear" : $("#thisYear").val(),
			"thisMonth" : $("#thisMonth").val()
		};
		return dataJson;
	}
}

//打开查看销售出库
//param1 出库单id
//param2 加密参数
function viewOutStock(outstockId,skeyValue){
	var url = '?model=stock_outstock_stockout&action=toView&id='
				+ outstockId
				+ "&docType=CKSALES"
				+ "&skey="
				+ skeyValue
	showOpenWin(url,1);
}

//打印出库
function printOutstock(outstockId,skeyValue){
	var url = '?model=stock_outstock_stockout&action=toPrintForCarry&id='
		+ outstockId
		+ "&docType=CKSALES"
		+ "&skey="
		+ skeyValue;
	showOpenWin(url,1);
}