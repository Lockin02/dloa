$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess: function() {
            if ($("#planDateClose").val() < $("#planDateStart").val()) {
	        	alert('����ʱ�䲻�ܴ������ʱ��');
	            return false;
	        } else if($('#officeId').val() == ""){
				alert('�������´�ѡ����ȷ��������ѡ��');
	            return false;
	        }else{
	            return true;
	        }
        }
    });

     /**��֤��Ŀ���� **/
     $("#projectName").formValidator({
         onshow:"��������Ŀ����",
         onfocus:"��Ŀ��������2���ַ������50���ַ�",
         oncorrect:"���������Ŀ������Ч"
     }).inputValidator({
         min:2,
         max:50,
         empty:{
            leftempty:false,
            rightempty:false,
            emptyerror:"��Ŀ�������߲����пշ���"
         },
         onerror:"����������Ʋ��Ϸ�������������"
     });

     /**��֤��Ŀ��� **/
     $("#projectCode").formValidator({
         onshow:"��������Ŀ���",
         onfocus:"��Ŀ�������2���ַ������50���ַ�",
         oncorrect:"���������Ŀ�����Ч"
     }).inputValidator({
         min:2,
         max:50,
         empty:{
            leftempty:false,
            rightempty:false,
            emptyerror:"��Ŀ������߲����пշ���"
         },
         onerror:"������ı�Ų��Ϸ�������������"
     });

     $("#officeName").formValidator({
         onshow:"��ѡ���������´�",
         onfocus:"��ѡ���������´�"
     }).inputValidator({
         min:2,
         max:50,
         empty:{
            leftempty:false,
            rightempty:false,
            emptyerror:"��Ŀ������߲����пշ���"
         },
         onerror:"�������´���Ч��������ѡ��"
     });

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
});