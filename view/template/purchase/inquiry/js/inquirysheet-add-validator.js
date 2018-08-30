$(document).ready(function (){

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){
//			return true;
		}
	});


    $("#deptName").formValidator({
    	onshow: "请选择部门",
    	onfocus: "请选择部门",
    	oncorrect: "您选择了部门"
    }).inputValidator({
    	min:1,
    	onerror:"请选择部门"
    });

     $("#purcherName").formValidator({
    	onshow: "请选择采购员名称",
    	onfocus: "请选择采购员名称",
    	oncorrect: "您选择了采购员名称"
    }).inputValidator({
    	min:1,
    	onerror:"请选择采购员名称"
    });


    $("#inquiryBgDate").formValidator({
        onshow: "请选择询价日期",
        onfocus: "请选择日期",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请输入合法的日期,询价日期为空"
    });

    $("#inquiryEndDate").formValidator({
        onshow: "请选择报价截止日期",
        onfocus: "请选择日期，不能小于询价日期",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请输入合法的日期,报价截止日期不能为空"
    }).compareValidator({
		desid : "inquiryBgDate",
		operateor : ">=",
		onerror : "报价截止日期不能小于询价日期"
	});


    $("#effectiveDate").formValidator({
        onshow: "请选择生效日期",
        onfocus: "请选择日期",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请输入合法的日期,生效日期为空"
    });

    $("#expiryDate").formValidator({
        onshow: "请选择失效日期",
        onfocus: "请选择日期，不能小于生效日期",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请输入合法的日期,失效日期不能为空"
    }).compareValidator({
		desid : "effectiveDate",
		operateor : ">=",
		onerror : "失效日期不能小于生效日期"
	});
})