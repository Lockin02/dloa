$().ready(function(){
	$("#executionDate").val(formatDate(new Date()));
	$.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        	return false;
        },
        onsuccess: function() {
    		workloadDay = $('#workloadDay').val();
			if (confirm("填写工作量为 "+ workloadDay +" ?")) {
				return true;
			} else {
				return false;
			}
        }
    });

    $("#taskName").formValidator({
        onshow: "选择任务",
        oncorrect: "OK"
    }).inputValidator({
    	min :1,
    	empty:{leftempty:false,rightempty:false,emptyerror:"两边不能有空符号"},
        onerror: "不能为空"
    }); //.defaultPassed();

    $("#workloadDay").formValidator({
    	onshow:"请输入工作量",
    	oncorrect:"OK"
	}).inputValidator({
		min:0.1,
		max:24,
		type:"value",
		onerrormin:"你输入的值必须大于等于0.1",
		onerror:"必须在0.1至24之间，请重新输入"
    }).regexValidator({
    	regexp:"^[0-9]{1,3}([.]{1}[0-9]{1,2})?$",
    	onerror:"你输入的电话号码格式不正确"
	});//.defaultPassed();

	$("#wlplanEndDate").formValidator({
        onshow: "请选择预计完成日期",
        onfocus: "请选择日期",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
    }); //.defaultPassed();

    $("#rdeffortRate").formValidator({
    	onshow:"请输入任务当前完成率",
    	oncorrect:"OK"
	}).inputValidator({
		min:0.1,
		max:100,
		type:"value",
		onerrormin:"你输入的值必须大于等于0.1",
		onerror:"必须在0.1至100之间，请重新输入"
	});//.defaultPassed();
})

/**
 * 计算完成率
 * @param {当天工作量} workloadDay
 * @param {估计工作量} appraiseWorkload
 * @param {完成率} rdeffortRate
 */
//function myDiv(workloadDay,areadyput,appraiseWorkload,rdeffortRate){//当天投入工作量,已投入工作量,估计工作量,完成率
//	if($("#"+workloadDay).val()!=""&&$("#"+appraiseWorkload).val()!=""){
//		if($("#"+areadyput).val()=="") vareadyput = 0;
//		else vareadyput = $("#"+areadyput).val();
//		var allput = parseFloat($("#"+workloadDay).val()) + parseFloat(vareadyput) ;
//		var appraiseWorkloadValue = parseFloat($("#"+appraiseWorkload).val());
//		var divresult = NewFloatDiv(allput,appraiseWorkloadValue) - NewFloatDiv(vareadyput,appraiseWorkloadValue);
//		var outputRd = divresult*100;
//		$("#"+rdeffortRate).val(Math.round(outputRd*100)/100);
//	}
//
//}

function residualWorkload(workloadDay,appraiseWorkload,putWorkload,workloadSurplus){//当天投入工作量,估计工作量,已投入工作量,剩余工作量
	if($("#"+appraiseWorkload).val() != "" && $("#"+workloadDay).val() != ""){
		//判断有误当天投入工作量和估计工作量
		if($("#"+putWorkload).val() == "") vputWorkload = 0;
		else vputWorkload = $("#"+putWorkload ).val();
		//判断是否存在已投入工作量
		var rs =parseFloat( $("#"+appraiseWorkload).val()) - parseFloat( $("#"+workloadDay).val()) - vputWorkload;
		$("#"+workloadSurplus).val(rs);

	}
}

function editWorkload(workloadDay,newWorkloadDay,appraiseWorkload,putWorkload,workloadSurplus){//实际当天投入工作量,原当天投入工作量,估计工作量,已投入工作量,剩余工作量
	if($("#"+appraiseWorkload).val() != "" && $("#"+workloadDay).val() != ""){
		//判断是否存在当天投入工作量和估计工作量
		var rs =parseFloat( $("#"+appraiseWorkload).val()) - parseFloat( $("#"+putWorkload).val()) - parseFloat( $("#"+newWorkloadDay).val()) + parseFloat( $("#"+workloadDay).val());
		$("#"+workloadSurplus).val(rs);

	}
}