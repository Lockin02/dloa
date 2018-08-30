$(document).ready(function (){
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){
			return true;
		}
	});


    $("#sendTime").formValidator({
        onshow: "请选择下达日期",
        onfocus: "请选择下达日期",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请输入合法的日期,下达日期为空"
    });

    $("#dateHope").formValidator({
        onshow: "请选择期望完成日期",
        onfocus: "请选择日期，不能小于下达日期",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请输入合法的日期,期望完成日期不能为空"
    }).compareValidator({
		desid : "sendTime",
		operateor : ">=",
		onerror : "期望完成日期不能小于下达日期"
	});


    $("#contractName").formValidator({
        onshow: "请选择合同",
        onfocus: "请选择合同",
        oncorrect: "你选择了合同"
    }).inputValidator({
        min:1,
        onerror: "请选择合同"
    });

    $("#depName").formValidator({
    	onshow: "请选择部门",
    	onfocus: "请选择部门",
    	oncorrect: "您选择了部门"
    }).inputValidator({
    	min:1,
    	onerror:"请选择部门"
    });

    $("#userName").formValidator({
    	onshow : "请选择使用人",
    	onfocus:"请选择使用人",
    	oncorrect:"选择了使用人"
    }).inputValidator({
    	min:1,
    	onerror:"请选择使用人"
    });
        $("#sendName").formValidator({
    	onshow: "请选择下达人名称",
    	onfocus: "请选择下达人名称",
    	oncorrect: "您选择了下达人名称"
    }).inputValidator({
    	min:1,
    	onerror:"请选择下达人名称"
    });

})