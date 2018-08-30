$(document).ready(function (){

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){
//			return true;
		}
	});


    $("#sendName").formValidator({
        onshow: "请选择起草人",
        onfocus: "请选择起草人",
        oncorrect: "OK"
    }).inputValidator({
        min: 1,
        onerror: "请选择起草人"
    });


    $("#supplierName").formValidator({
        onshow: "请选择供应商",
        onfocus: "请选择供应商",
        oncorrect: "OK"
    }).inputValidator({
        min: 1,
        onerror: "请选择供应商"
    });

    $("#dateHope").formValidator({
        onshow: "请选择预计到货日期",
        onfocus: "请选择预计到货日期",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请选择合法的日期,日期为空"
    });


    $("#dateFact").formValidator({
        onshow: "请选择期望完成日期",
        onfocus: "请选择期望完成日期",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请选择合法的日期,日期为空"
    });

    //对合同号进行唯一性验证
    $("#hwapplyNumb").formValidator({
        onshow: "请输入采购订单编号",
        onfocus: "请输入采购订单编号",
        oncorrect: "OK"
    }).inputValidator({
        min: 1,
        max: 100,
        empty:{
        	leftempty:false,
        	rightempty:false,
        	emptyerror:"合同号两边不能有空符号"
        }
    }).ajaxValidator({
    	type:"get",
    	url:"index1.php",
    	data : "model=purchase_contract_purchasecontract&action=ajaxContractNumb",
    	datatype : "json",
    	success : function(data){
    		if(data == "1"){
    			return true;
    		}else{
    			return false;
    		}
    	},
    	buttons : $("#saveButton"),
    	error : function(){
    		alert("服务器没有返回数据，可能服务器忙，请重试");
    	},
    	onerror : "该名称不可用，请更换",
    	onwait : "正在对合同编号进行唯一性验证，请稍候..."
    });


});