$(document).ready(function (){
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg){
		},
		onsuccess: function(){
				$("input[type='submit']").attr("disabled","disabled");
				$("#closeBtn").attr("disabled","disabled");
		}
	});


    $("#sendTime").formValidator({
        onshow: "请选择任务下达日期",
        onfocus: "请选择任务下达日期",
        oncorrect: "已选择任务下达日期"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请选择任务下达日期"
    });

    $("#dateHope").formValidator({
        onshow: "请选择期望完成日期",
        onfocus: "请选择日期，不能小于任务下达日期",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请选择期望完成日期"
    }).compareValidator({
		desid : "sendTime",
		operateor : ">=",
		onerror : "期望完成日期不能小于任务下达日期"
	});


    $("#sendUserId").formValidator({
        onshow: "请选择负责人",
        onfocus: "请选择负责人",
        oncorrect: "OK"
    }).inputValidator({
        min:1,
        onerror: "请选择负责人"
    });

    $("#depName").formValidator({
    	onshow: "请选择部门",
    	onfocus: "请选择部门",
    	oncorrect: "OK"
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