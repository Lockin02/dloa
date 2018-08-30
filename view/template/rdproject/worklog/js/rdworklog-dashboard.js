$().ready(function(){
	$.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess: function() {
            if ($("#overDate").val() < $("#beginDate").val()) {
            	alert('起始时间不能大于终止时间');
                return false;
            } else {
                return true;
            }
        }
    });

    $("#beginDate").formValidator({
        onshow: "请选择计划开始日期",
        onfocus: "请选择日期",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
    }).compareValidator({
		desid : "overDate",
		operateor : "<=",
		onerror : "开始日期不能大于结束日期"
	}); //.defaultPassed();

    $("#overDate").formValidator({
        onshow: "请选择计划结束日期",
        onfocus: "请选择日期",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
    }).compareValidator({
		desid : "beginDate",
		operateor : ">=",
		onerror : "结束日期不能小于开始日期"
	}); //.defaultPassed();

    function thisMonth(){
		 var d, s;

	    // 创建 Date 对象。
	    d = new Date();
	    s = d.getFullYear() + "-";
	    s += ("0"+(d.getMonth()+1)).slice(-2) + "-01";

	    return s;
    }

    $("#beginDate").val(thisMonth());

    $("#overDate").val(formatDate(new Date()));
})