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

	$("#name").formValidator({
		onshow : "�����빩Ӧ������",
		onfocus : "��Ӧ����������2���ַ�,���50���ַ�",
		oncorrect : "������Ĺ�Ӧ�����ƿ���"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "��Ӧ���������߲����пշ���"
		},
		onerror : "����������Ʋ��Ϸ�,��ȷ��"
	});
	$("#plane").formValidator({
		empty : true,
		onshow : "�����������ϵ�绰������Ϊ��",
		onfocus : "��ʽ���磺0577-88888888",
		oncorrect : "лл��ĺ���",
		onempty : "�㲻������ϵ�绰����"
	}).regexValidator({
		regexp : "^[[0-9]{3}-|\[0-9]{4}-]?([0-9]{8}|[0-9]{7})?$",
		onerror : "���������ϵ�绰��ʽ����ȷ"
	});
	$("#email").formValidator({
		onshow : "����������",
		onfocus : "����6-100���ַ�,������ȷ�˲����뿪����",
		oncorrect : "��ϲ��,�������",
		forcevalid : true
	}).inputValidator({
		min : 6,
		max : 100,
		onerror : "����������䳤�ȷǷ�,��ȷ��"
	}).regexValidator({
		regexp : "^([\\w-.]+)@(([[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.)|(([\\w-]+.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(]?)$",
		onerror : "������������ʽ����ȷ"
	});
	$("#fax").formValidator({
		empty : true,
		onshow : "�����������ϵ�绰������Ϊ��Ŷ",
		onfocus : "��ʽ���磺0577-88888888",
		oncorrect : "лл��ĺ���",
		onempty : "����Ĳ�������ϵ�绰����"
	}).regexValidator({
		regexp : "^[[0-9]{3}-|\[0-9]{4}-]?([0-9]{8}|[0-9]{7})?$",
		onerror : "���������ϵ�绰��ʽ����ȷ"
	});
})