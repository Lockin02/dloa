/**
 * ��Ƹ�ƻ���Ϣ����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_plan', {
		options : {
			hiddenId : 'id',
			nameCol : 'formCode',
			// isFocusoutCheck:false,
			gridOptions : {
				showcheckbox : false,
				model : 'hr_recruitplan_plan',
				action : 'pageJson',
				param : {},
				pageSize : 10,
				// ����Ϣ
				colModel : [ {
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'formCode',
					display : '���ݱ��',
					width:130,
					sortable : true
				},{
					name : 'stateC',
					display : '����״̬',
					sortable : true,
					hide : true
				},{
					name : 'deptName',
					display : '������',
					sortable : true
				},{
					name : 'postTypeName',
					display : 'ְλ����',
					sortable : true
				},{
					name : 'positionName',
					display : '����ְλ',
					sortable : true
				},{
					name : 'developPositionName',
					display : '�з�ְλ',
					sortable : true,
					hide : true
				},{
					name : 'positionLevel',
					display : '����',
					sortable : true,
					hide : false
				},{
					name : 'needNum',
					display : '��������',
					sortable : true,
					hide : false
				},{
					name : 'hopeDate',
					display : 'ϣ������ʱ��',
					sortable : true,
					hide : false
				},{
					name : 'workPlace',
					display : '�����ص�',
					sortable : true,
					hide : false
				},{
					name : 'applyReason',
					display : '����ԭ��',
					sortable : true,
					width : 300,
					hide : false
				},{
					name : 'formManName',
					display : '�����',
					width : 70,
					sortable : true
				},{
					name : 'formDate',
					display : '���ʱ��',
					width : 70,
					sortable : true
				}],
				// ��������
				searchitems : [{
					display : "������",
					name : 'deptName'
				},{
					display : "����ְλ",
					name : 'positionName'
				},{
					display : '�����',
					name : 'formManName'
				},{
					display : '���ʱ��',
					name : 'formDate'
				}],

				sortname : "id",
				// Ĭ������˳��
				sortorder : "desc"
			}
		}
	});
})(jQuery);