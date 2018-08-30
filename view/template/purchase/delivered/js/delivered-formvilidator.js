$(document).ready(function (){

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){
			return true;
		}
	});


    $("#returnDate").formValidator({
        onshow: "请选择退料日期",
        onfocus: "请选择退料日期",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请选择退料日期"
    });


//    $("#sourceCode").formValidator({
//    	onshow: "请选择源单编号",
//    	onfocus: "请选择源单编号",
//    	oncorrect: "OK"
//    }).inputValidator({
//    	min:1,
//    	onerror:"请选择源单编号"
//    });



     $("#purchManName").formValidator({
    	onshow: "请选择采购员",
    	onfocus: "请选择采购员",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"请选择采购员"
    });
     $("#supplierName").formValidator({
    	onshow: "请选择供应商",
    	onfocus: "请选择供应商",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"请选择供应商"
    });
     $("#stockName").formValidator({
    	onshow: "请选择退料仓库",
    	onfocus: "请选择退料仓库",
    	oncorrect: "OK"
    }).inputValidator({
    	min:1,
    	onerror:"请选择退料仓库"
    });


})