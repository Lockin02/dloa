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
			if (confirm("��д������Ϊ "+ workloadDay +" ?")) {
				return true;
			} else {
				return false;
			}
        }
    });

    $("#taskName").formValidator({
        onshow: "ѡ������",
        oncorrect: "OK"
    }).inputValidator({
    	min :1,
    	empty:{leftempty:false,rightempty:false,emptyerror:"���߲����пշ���"},
        onerror: "����Ϊ��"
    }); //.defaultPassed();

    $("#workloadDay").formValidator({
    	onshow:"�����빤����",
    	oncorrect:"OK"
	}).inputValidator({
		min:0.1,
		max:24,
		type:"value",
		onerrormin:"�������ֵ������ڵ���0.1",
		onerror:"������0.1��24֮�䣬����������"
    }).regexValidator({
    	regexp:"^[0-9]{1,3}([.]{1}[0-9]{1,2})?$",
    	onerror:"������ĵ绰�����ʽ����ȷ"
	});//.defaultPassed();

	$("#wlplanEndDate").formValidator({
        onshow: "��ѡ��Ԥ���������",
        onfocus: "��ѡ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
    }); //.defaultPassed();

    $("#rdeffortRate").formValidator({
    	onshow:"����������ǰ�����",
    	oncorrect:"OK"
	}).inputValidator({
		min:0.1,
		max:100,
		type:"value",
		onerrormin:"�������ֵ������ڵ���0.1",
		onerror:"������0.1��100֮�䣬����������"
	});//.defaultPassed();
})

/**
 * ���������
 * @param {���칤����} workloadDay
 * @param {���ƹ�����} appraiseWorkload
 * @param {�����} rdeffortRate
 */
//function myDiv(workloadDay,areadyput,appraiseWorkload,rdeffortRate){//����Ͷ�빤����,��Ͷ�빤����,���ƹ�����,�����
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

function residualWorkload(workloadDay,appraiseWorkload,putWorkload,workloadSurplus){//����Ͷ�빤����,���ƹ�����,��Ͷ�빤����,ʣ�๤����
	if($("#"+appraiseWorkload).val() != "" && $("#"+workloadDay).val() != ""){
		//�ж�������Ͷ�빤�����͹��ƹ�����
		if($("#"+putWorkload).val() == "") vputWorkload = 0;
		else vputWorkload = $("#"+putWorkload ).val();
		//�ж��Ƿ������Ͷ�빤����
		var rs =parseFloat( $("#"+appraiseWorkload).val()) - parseFloat( $("#"+workloadDay).val()) - vputWorkload;
		$("#"+workloadSurplus).val(rs);

	}
}

function editWorkload(workloadDay,newWorkloadDay,appraiseWorkload,putWorkload,workloadSurplus){//ʵ�ʵ���Ͷ�빤����,ԭ����Ͷ�빤����,���ƹ�����,��Ͷ�빤����,ʣ�๤����
	if($("#"+appraiseWorkload).val() != "" && $("#"+workloadDay).val() != ""){
		//�ж��Ƿ���ڵ���Ͷ�빤�����͹��ƹ�����
		var rs =parseFloat( $("#"+appraiseWorkload).val()) - parseFloat( $("#"+putWorkload).val()) - parseFloat( $("#"+newWorkloadDay).val()) + parseFloat( $("#"+workloadDay).val());
		$("#"+workloadSurplus).val(rs);

	}
}