$(document).ready(function (){

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){
			return true;
		}
	});


    $("#arrivalDate").formValidator({
        onshow: "请选择收料日期",
        onfocus: "请选择收料日期",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请选择收料日期"
    });


//    $("#purchaseCode").formValidator({
//    	onshow: "请选择订单号",
//    	onfocus: "请选择订单号",
//    	oncorrect: "OK"
//    }).inputValidator({
//    	min:1,
//    	onerror:"请选择订单号"
//    });


    $("#supplierName").formValidator({
    	onshow: "请选择供应商",
    	onfocus: "请选择供应商",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"请选择供应商"
    });




    $("#stockName").formValidator({
    	onshow: "请选择收料仓库名称 ",
    	onfocus: "请选择收料仓库名称 ",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"请选择收料仓库名称 "
    });

     $("#purchManName").formValidator({
    	onshow: "请选择采购员",
    	onfocus: "请选择采购员",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"请选择采购员"
    });


})