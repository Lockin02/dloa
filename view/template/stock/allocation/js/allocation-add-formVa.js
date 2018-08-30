$(document).ready(function() {
	$.formValidator.initConfig({
		formid: "form1",
		//autotip: true,
		onerror: function(msg) {
			//alert(msg);
		}
	});

	/** �������� * */
	$("#mainNo").formValidator({
		onshow: "�������������",
		onfocus: "��������2���ַ������50���ַ�",
		oncorrect: "OK"
	}).inputValidator({
		min: 2,
		max: 50,
		empty: {
			leftempty: false,
			rightempty: false,
			emptyerror: "�������߲����пշ���"
		},
		onerror: "������ĵ��Ų��Ϸ�������������"
	});

	/** ��֤�������� * */
	$("#outStockName").formValidator({
		onshow: "��ѡ���������",
		onfocus: "��ѡ���������",
		oncorrect: "OK"
	}).inputValidator({
		min: 2,
		max: 50,
		empty: {
			leftempty: false,
			rightempty: false,
			emptyerror: "ѡ���������߲���Ϊ��"
		},
		onerror: "δѡ���ѡ����ȷ,������ѡ��"
	});

	/** ��֤��Ʊ���� * */
	$("#inStockName").formValidator({
		onshow: "��ѡ���������",
		onfocus: "��ѡ���������",
		oncorrect: "OK"
	}).inputValidator({
		min: 2,
		max: 50,
		empty: {
			leftempty: false,
			rightempty: false,
			emptyerror: "ѡ���������߲���Ϊ��"
		},
		onerror: "δѡ���ѡ����ȷ,������ѡ��"
	});

	/** ��֤�������� * */
	$("#formDate").formValidator({
		onshow: "��ѡ�񵥾�����",
		onfocus: "��ѡ������",
		oncorrect: "����������ںϷ�"
	}).inputValidator({
		min: "1900-01-01",
		max: "2100-01-01",
		type: "date",
		onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
	});
});