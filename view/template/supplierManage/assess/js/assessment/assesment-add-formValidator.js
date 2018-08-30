$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess: function() {
            if (confirm("您输入成功，确定提交吗？")) {
                return true;
            } else {
                return false;
            }
        }
    });

    $("#assesName").formValidator({
        onshow: "请输入评估方案名称",
        onfocus: "评估方案名称至少5个字符,最多50个字符",
        oncorrect: "您输入的评估方案名称可用"
    }).inputValidator({
        min: 5,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "评估方案名称两边不能有空符号"
        },
        onerror: "你输入的名称不合法,请确认"
    });

    $("#assesType").formValidator({
        onshow: "请选择评估类型",
        onfocus: "评估类型必须选择",
        oncorrect: "谢谢你的配合"
    }).inputValidator({
        min: 1,
        onerror: "你是不是忘记选择评估类型了!"
    });

    $("#manageName").formValidator({
        onshow: "请选择责任人",
        onfocus: "不可输入，请选择",
        oncorrect: "您选择的负责人有效"
    }).inputValidator({
        min: 1,
        onerror: "请选择负责人"
    });

    $("#beginDate").formValidator({
        onshow: "请选择起始日期",
        onfocus: "请选择日期",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请输入合法的日期,例:2000-01-01"
    }); //.defaultPassed();

    $("#endDate").formValidator({
        onshow: "请选择计划截止日期",
        onfocus: "请选择日期，不能小于起始日期哦",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "请输入合法的日期,例:2000-01-01"
    }).compareValidator({
		desid : "beginDate",
		operateor : ">=",
		onerror : "截止日期不能小于起始日期"
	});

});