$(document).ready(function() {

	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg) {
		},
		onsuccess : function() {
			if (confirm("������ɹ�,ȷ���ύ��?")) {
				return true;
			} else {
				return false;
			}

		}
	});
//	$("#mobile1").formValidator({
//		onshow : "����������ֻ�����",
//		onfocus : "������13��15��ͷŶ",
//		oncorrect : "лл��ĺ���������ֻ�������ȷ"
//	}).regexValidator({
//		regexp : "mobile",
//		datatype : "enum",
//		onerror : "�ֻ������ʽ����ȷ"
//	});
    $("#busiCode").formValidator({
        onshow: "������ҵ����",
        onfocus: "ҵ��������5���ַ�,���50���ַ�",
        oncorrect: "�������ҵ���ſ���"
    }).inputValidator({
        min: 5,
        max: 50,
        empty: {
            leftempty: false,
            rightempty: false,
            emptyerror: "ҵ�������߲����пշ���"
        },
        onerror: "�������ҵ���ŷǷ�,��ȷ��"
    });
	$("#name").formValidator({
		onshow : "��������ϵ������",
		onfocus : "��ϵ�˳�����2���ַ�,���50���ַ�",
		oncorrect : "OK"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "��ϵ���������߲����пշ���"
		},
		onerror : "��ϵ��Ϊ��,������"
	});
//	$("#plane").formValidator({
//		empty : false,
//		onshow : "�����������ϵ�绰��������Ϊ��",
//		onfocus : "��ʽ���磺0577-88888888"
////		oncorrect : "лл��ĺ���"
////		onempty : "�㲻������ϵ�绰����"
//	}).regexValidator({
//		regexp : "^[[0-9]{3}-|\[0-9]{4}-]?([0-9]{8}|[0-9]{7})?$",
//		onerror : "���������ϵ�绰��ʽ����ȷ��Ϊ��"
//	});
//	$("#email").formValidator({
//		onshow : "����������",
//		onfocus : "����6-100���ַ�,������ȷ�˲����뿪����",
//		oncorrect : "��ϲ��,�������"
////		forcevalid : true
//	}).inputValidator({
//		min : 6,
//		max : 100,
//		onerror : "����������䳤�ȷǷ�,��ȷ��"
//	}).regexValidator({
//		regexp : "^([\\w-.]+)@(([[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.)|(([\\w-]+.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(]?)$",
//		onerror : "������������ʽ����ȷ"
//	});
//	$("#fax").formValidator({
//		empty : false,
//		onshow : "�����������ϵ�绰��������Ϊ��Ŷ",
//		onfocus : "��ʽ���磺0577-88888888"
////		oncorrect : "лл��ĺ���",
////		onempty : "����Ĳ�������ϵ�绰����"
//	}).regexValidator({
//		regexp : "^[[0-9]{3}-|\[0-9]{4}-]?([0-9]{8}|[0-9]{7})?$",
//		onerror : "���������ϵ�绰��ʽ����ȷ"
//	});

	    $("#manageUserName").formValidator({
        onshow: "��ѡ������",
        onfocus: "�������룬��ѡ��",
        oncorrect: "��ѡ��ĸ�������Ч"
    }).inputValidator({
        min: 1,
        onerror: "��ѡ������"
    });
})