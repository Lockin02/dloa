$().ready(function(){
	$.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        },
        onsuccess: function() {
        }
    });

    $("#memberName").formValidator({
        onshow: "������1~20֮��,�����ַ�10����Ӣ���ַ�20����",
        onfocus: "������1~20֮��,�����ַ�10����Ӣ���ַ�20����",
        oncorrect: "OK"
    }).inputValidator({
        min: 1,
		max: 20,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "������߲����пշ���"
        },
        onerror: "����Ϊ��"
    }); //.defaultPassed();

//    $("#phone").formValidator({
//    	empty:true,
//    	onshow:"����Ϊ��",
//    	onfocus:"��������ȷ�ĵ绰����",
//    	oncorrect:"OK",
//    	onempty:"����Ϊ��"
//    }).regexValidator({
//    	regexp:"^[[0-9]{3}-|\[0-9]{4}-]?([0-9]{8}|[0-9]{7})?$",
//    	onerror:"������ĵ绰�����ʽ����ȷ"
//	});
//
//    $("#mobile").formValidator({
//    	empty:true,
//    	onshow:"����Ϊ��",
//    	onfocus:"��������ȷ���ֻ�����",
//    	oncorrect:"OK",
//    	onempty:"����Ϊ��"
//    }).inputValidator({
//    	min:11
//    	,max:11,
//    	onerror:"�ֻ����������11λ��,��ȷ��"
//    }).regexValidator({
//    	regexp:"mobile",
//    	datatype:"enum",
//    	onerror:"��������ֻ������ʽ����ȷ"
//	});
//
//    $("#email").formValidator({
//    	empty:true,
//    	onshow:"��Ϊ��",
//    	onfocus:"��������ȷ��Email��ַ",
//    	oncorrect:"OK",
//    	onempty:"����Ϊ��"
//	}).inputValidator({
//		min:6,
//		max:100,
//		onerror:"����������䳤�ȷǷ�,��ȷ��"
//	}).regexValidator({
//		regexp:"^([\\w-.]+)@(([[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.)|(([\\w-]+.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(]?)$",
//		onerror:"������������ʽ����ȷ"
//	});
})