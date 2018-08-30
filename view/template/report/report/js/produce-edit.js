$(function() {

	$("#stockinfo").yxeditgrid({
		objName : 'produce[stock]',
		url : '?model=report_report_produceinfo&action=listJson',
		param : {
			'produceId' : $("#id").val(),
			'proType' : "�п��"
		},
		isAddOneRow : false,
		tableClass : 'form_in_table',
		colModel : [
		{
			display : '������',
			name : 'proType',
			tclass : 'txt',
			value : "�п��",
			type : 'hidden'
		},{
			display : '��������',
			name : 'needNum',
			tclass : 'txtshort'
		}, {
			display : '������װ',
			name : 'proTime',
			tclass : 'txtshort'
		}, {
			display : '����',
			name : 'testTime',
			tclass : 'txtshort'
		}, {
			display : '��װ',
			name : 'packageTime',
			tclass : 'txtshort'
		}]

	});
	$("#outstockinfo").yxeditgrid({
		objName : 'produce[outstock]',
		url : '?model=report_report_produceinfo&action=listJson',
		param : {
			'produceId' : $("#id").val(),
			'proType' : "�޿��"
		},
		isAddOneRow : false,
		tableClass : 'form_in_table',
		colModel : [
		{
			display : '������',
			name : 'proType',
			tclass : 'txtshort',
			value : "�޿��",
			type : 'hidden'
		}, {
			display : '�����̵�',
			name : 'needNum',
			tclass : 'txt'
		}, {
			display : '�ɹ�ʱ��',
			name : 'proTime',
			tclass : 'txtshort'
		}, {
			display : '��ӹ�ʱ��',
			name : 'testTime',
			tclass : 'txtshort'
		}, {
			display : '�����',
			name : 'packageTime',
			tclass : 'txtshort'
		}]

	});
});