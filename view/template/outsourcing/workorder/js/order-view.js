$(document).ready(function() {
$("#itemTable").yxeditgrid({		
		objName : 'order[orderequ]',
		url : "?model=outsourcing_workorder_orderequ&action=listJson",
		param : {
			parentId : $("#ID").val(),
			dir : 'ASC'
		},
		type : 'view',
		colModel : [{
			display : 'ʩ����Ա',
			name : 'personName',
			tclass : 'txtshort'
		}, {
			display : 'ʩ����ԱID',
			name : 'personId',
			tclass : 'txt',
			type : 'hidden'
		}, {
			display : 'suppID',
			name : 'parentId',
			tclass : 'txt',
			type : 'hidden'
		}, {
			display : '���֤����',
			name : 'IdCard',
			tclass : 'txt',
			type :��'readonly'
		}, {
			display : '�ֻ�',
			name : 'phone',
			tclass : 'txt'
		}, {
			display : '����',
			name : 'email',
			tclass : 'txt'
		}, {
			display : '��ĿԤ�ƿ�ʼʱ��',
			name : 'exceptStart',
			tclass : 'txtshort',
			type : 'date'
		}, {
			display : '��ĿԤ�ƽ���ʱ��',
			name : 'exceptEnd',
			tclass : 'txtshort',
			type : 'date'
		}, {
			display : '����(Ԫ)',
			name : 'price',
			tclass : 'txtshort'
		}, {
			display : '���㷽ʽ',
			name : 'payWay',
			tclass : 'txt'
		}, {
			display : '����˵��',
			name : 'payExplain',
			tclass : 'txt'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	});

 })