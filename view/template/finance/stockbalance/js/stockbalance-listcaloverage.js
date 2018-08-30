//异步保存数据
function ajaxSave(){
	var thisDate = getDataFun();
	if(thisDate != false){
		$.ajax({
			type : "POST",
			url : "?model=finance_stockbalance_stockbalance&action=overageCal",
			data : {
				"data" : thisDate
			},
			success : function(msg) {
				if (msg == 1) {
					alert('核算成功！');
				}else{
					alert("核算失败! ");
				}
			}
		});
	}else{
		alert('没有修改任何值');
		return false;
	}
}


//获取数据组成json数组
function getDataFun(){
	var tempProductId = '';   //临时产品id
	var tempSubPrice = 0;     //临时总金额
	var tempprice = 0;  //单价
	var tempOldSubPrice = 0;  //原始金额

	var j = 0;

	var dataJson = {};

	//行数
	var countNum = $("#countNum").val();

	//循环够高json数组
	for(var i = 1 ; i <= countNum ; i++){
		//修改后的金额
		tempSubPrice = $('#subPrice' + i).val() * 1;
		//修改前的金额
		tempOldSubPrice = $('#oldSubPrice' + i).val() * 1;
		//如果不相等,则存入json数组内
		if( tempSubPrice != tempOldSubPrice ){
			j ++ ;
			tempProductId = $('#productId' + i).val();
			tempprice = $('#price' + i).val();
			dataJson[i] = {
				"productId" : tempProductId ,
				"subPrice" : tempSubPrice ,
				"price" : tempprice
			};
		}
	}

	if(j != 0){
		return dataJson;
	}else{
		return false;
	}
}