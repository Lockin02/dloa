$().ready(function(){
	$.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess: function() {
            if ($("#overDate").val() < $("#beginDate").val()) {
            	alert('��ʼʱ�䲻�ܴ�����ֹʱ��');
                return false;
            } else {
                return true;
            }
        }
    });

    $("#beginDate").formValidator({
        onshow: "��ѡ��ƻ���ʼ����",
        onfocus: "��ѡ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
    }).compareValidator({
		desid : "overDate",
		operateor : "<=",
		onerror : "��ʼ���ڲ��ܴ��ڽ�������"
	}); //.defaultPassed();

    $("#overDate").formValidator({
        onshow: "��ѡ��ƻ���������",
        onfocus: "��ѡ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
    }).compareValidator({
		desid : "beginDate",
		operateor : ">=",
		onerror : "�������ڲ���С�ڿ�ʼ����"
	}); //.defaultPassed();

    function thisMonth(){
		 var d, s;

	    // ���� Date ����
	    d = new Date();
	    s = d.getFullYear() + "-";
	    s += ("0"+(d.getMonth()+1)).slice(-2) + "-01";

	    return s;
    }

    $("#beginDate").val(thisMonth());

    $("#overDate").val(formatDate(new Date()));
})