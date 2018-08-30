
//步骤一，查询数据
function getInvpurchase(){
	$.ajax({
	    type: "POST",
	    url: "?model=finance_invpurchase_invpurchase&action=getRepeatArr",
	    async: false,
	    success: function(data){
	   		if(data == "1"){
				$("#shareBody").html('没有符合条件的数据');
	   	    }else{
	   	    	data = "<tr><td>序号</td><td>采购发票id</td><td>采购发票号</td><td>物料id</td><td>物料编码</td><td>数量</td>" +
	   	    			"<td>源单号</td><td>合同编号</td><td>操作</td></tr>" + data;
				$("#shareBody").html(data);
	   	    }
		}
	});
}


//获取总数
function getCount(){
	$.ajax({
	    type: "POST",
	    url: "?model=finance_invpurchase_invpurchase&action=getCount",
	    async: false,
	    success: function(data){
	   		if(data != "0"){
				$("#countNum").html('记录条数为：' + data);
	   	    }else{
				$("#countNum").html('没有符合条件的数据');
	   	    }
		}
	});
}

//页面刷新
function show_page(){
	getInvpurchase();
}

//更新其余信息
function updateOther(){
	var mark = false;
	//先验证，后更新
	$.ajax({
	    type: "POST",
	    url: "?model=finance_invpurchase_invpurchase&action=getCount",
	    async: false,
	    success: function(data){
	   		if(data != "0"){
				$("#updateInfo").html('数据未处理完毕!不能进行该操作');
	   	    }else{
				updateFun();
	   	    }
		}
	});
}

//实际更新的方法
function updateFun(){
	$.ajax({
	    type: "POST",
	    url: "?model=finance_invpurchase_invpurchase&action=updateOther",
	    async: false,
	    success: function(data){
	   		if(data == "1"){
	   			$("#updateInfo").html('更新完成');
	   	    }else{
	   	    	$("#updateInfo").html(data);
	   	    }
		}
	});
}