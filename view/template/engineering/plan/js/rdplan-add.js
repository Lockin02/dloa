$().ready(function(){
	$.formValidator.initConfig({
		formid: "form1",
	    //autotip: true,
	    onerror: function(msg) {
	        //alert(msg);
	    	return false;
	    },
	    onsuccess: function() {
	    	if ($("#planDateClose").val() < $("#planDateStart").val()) {
	        	alert('����ʱ�䲻�ܴ������ʱ��');
	            return false;
	        } else {
	            return true;
	        }
	    }
	});

	$("#planName").formValidator({
	    onshow: "����д�ƻ�����",
	    oncorrect: "OK"
	}).inputValidator({
		min :1,
		empty:{leftempty:false,rightempty:false,emptyerror:"���߲����пշ���"},
	    onerror: "����Ϊ��"
	}); //.defaultPassed();

	$("#planDateStart").formValidator({
	    onshow: "��ѡ��ƻ�����ʱ��",
	    onfocus: "��ѡ������",
	    oncorrect: "����������ںϷ�"
	}).inputValidator({
	    min: "1900-01-01",
	    max: "2100-01-01",
	    type: "date",
	    onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
	}); //.defaultPassed();

	$("#planDateClose").formValidator({
	    onshow: "��ѡ��ƻ����ʱ��",
	    onfocus: "��ѡ������",
	    oncorrect: "����������ںϷ�"
	}).inputValidator({
	    min: "1900-01-01",
	    max: "2100-01-01",
	    type: "date",
	    onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
    }).compareValidator({
		desid : "planDateStart",
		operateor : ">=",
		onerror : "�ƻ�������ڲ���С�ڼƻ���ʼ����"
	}); // .defaultPassed();
})