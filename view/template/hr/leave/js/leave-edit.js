$(function () {

	//��ְԭ��ѡ��ƥ��
	var reason = $("#quitResonOld").val();
	if (reason.indexOf("��н�긣��������^nbsp") > -1) {
		$("input[name='leave[checkbox][0]']").attr("checked", true);
	}
	if (reason.indexOf("�Թ�˾������������չ�ռ䣩����^nbsp") > -1) {
		$("input[name='leave[checkbox][1]']").attr("checked", true);
	}
	if (reason.indexOf("����ҵ�Ļ�������^nbsp") > -1) {
		$("input[name='leave[checkbox][2]']").attr("checked", true);
	}
	if (reason.indexOf("�Թ���ʽ������^nbsp") > -1) {
		$("input[name='leave[checkbox][3]']").attr("checked", true);
	}
	if (reason.indexOf("�������ŶӵĹ����������Χ������^nbsp") > -1) {
		$("input[name='leave[checkbox][4]']").attr("checked", true);
	}
	if (reason.indexOf("��ͬ�²����⣨�����ദ��^nbsp") > -1) {
		$("input[name='leave[checkbox][5]']").attr("checked", true);
	}
	if (reason.indexOf("���ϼ��쵼������^nbsp") > -1) {
		$("input[name='leave[checkbox][6]']").attr("checked", true);
	}
	if (reason.indexOf("����������ʤ���ָ�λ^nbsp") > -1) {
		$("input[name='leave[checkbox][7]']").attr("checked", true);
	}
	if (reason.indexOf("�����ְҵ��չ�������Ȥ���ò���^nbsp") > -1) {
		$("input[name='leave[checkbox][8]']").attr("checked", true);
	}
	if (reason.indexOf("������������������^nbsp") > -1) {
		$("input[name='leave[checkbox][9]']").attr("checked", true);
	}
	if (reason.indexOf("�����������ܺܺõ�����^nbsp") > -1) {
		$("input[name='leave[checkbox][10]']").attr("checked", true);
	}
	if (reason.indexOf("��ͥ����Ե��^nbsp") > -1) {
		$("input[name='leave[checkbox][11]']").attr("checked", true);
	}
	if (reason.indexOf("����ԭ��^nbsp") > -1) {
		$("input[name='leave[checkbox][12]']").attr("checked", true);
	}
	if (reason.indexOf("����̫����^nbsp") > -1) {
		$("input[name='leave[checkbox][13]']").attr("checked", true);
	}
	if (reason.indexOf("����ѹ��̫��^nbsp") > -1) {
		$("input[name='leave[checkbox][14]']").attr("checked", true);
	}
	if (reason.indexOf("ѧϰ���޻�ҵ^nbsp") > -1) {
		$("input[name='leave[checkbox][15]']").attr("checked", true);
	}
	if (reason.indexOf("��ͬ������˾����^nbsp") > -1) {
		$("input[name='leave[checkbox][16]']").attr("checked", true);
	}
	if (reason.indexOf("����^nbsp") > -1) {
		$("input[name='leave[checkbox][17]']").attr("checked", true).trigger('change');
		$("#comOther").text(reason.substring((reason.indexOf("����^nbsp") + 7)));
	}

});

function sub() {
	var str = '';
	$("input[name^='leave[checkbox]']").each(function () {
		if ($(this).attr("checked")) {
			str += $(this).val() + ",";
			return false; //����ѭ��
		}
	});

	if (str == "") {
		alert("��ѡ����ְԭ��");
		return false;
	}

	if (!$("#comOther").hasClass('validate[required]')) {
		$("#comOther").val('');
	}

	if (!$("#projectManager").val()) {
		if (confirm("��Ŀ������ĿΪ�գ��Ƿ������")) {
			return true;
		} else {
			return false;
		}
	}
}

//ֱ���ύ
function toSubmit() {
	document.getElementById('form1').action = "?model=hr_leave_leave&action=staffEdit&actType=staff";
}