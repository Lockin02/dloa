$(document).ready(function () {
	$('#itemInfo').yxeditgrid({
		objName: 'produceapply[items]',
		url: '?model=produce_apply_produceapplyitem&action=listJson',
		param: {
			state: 0,
			mainId: $('#id').val(),
			dir: 'ASC'
		},
		isAddAndDel: false,
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			name: 'state',
			display: '�Ƿ���',
			type: 'checkbox'
		}, {
			display: '��������',
			name: 'proType',
			type: 'statictext'
		}, {
			display: '��������',
			name: 'productName',
			type: 'statictext'
		}, {
			display: '���ϱ���',
			name: 'productCode',
			type: 'statictext'
		}, {
			display: '����ͺ�',
			name: 'pattern',
			type: 'statictext'
		}, {
			display: '��λ����',
			name: 'unitName',
			type: 'statictext'
		}, {
			name: 'produceNum',
			display: '��������',
			type: 'statictext'
		}]
	});

	validate({
		"backReason": {
			required: true
		}
	});
});

// ����У��
function checkData() {
	var rs = false;
	$('input[id^=itemInfo_cmp_state]').each(function () {
		if ($(this).attr('checked')) {
			rs = true;
			return false; // �˳�ѭ��
		}
	});

	if (!rs) {
		alert('����ѡ��һ����¼���д�أ�');
	}

	return rs;
}