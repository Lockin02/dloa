$(document).ready(function () {
	$("#summaryTable").yxeditgrid({
		objName : 'examine[summaryTable]',
		isAddOneRow : true,
		url : '?model=hr_permanent_linkcontent&action=listJson',
		param : {
			parentId : $("#parentId").val(),
			ownType : 1
		},
		type : 'view',
		colModel : [{
				display : '����Ҫ�����',
				name : 'workPoint',
				width:'35%'
			}, {
				display : '����ɹ�',
				name : 'outPoint',
				width:'40%'
			}, {
				display : '���ʱ��ڵ�',
				name : 'finishTime',
				width:'20%'
			}, {
				name : 'ownType',
				type : 'hidden',
				value : 1
			}
		]
	});

	$("#planTable").yxeditgrid({
		objName : 'examine[planTable]',
		isAddOneRow : true,
		url : '?model=hr_permanent_linkcontent&action=listJson',
		param : {
			parentId : $("#parentId").val(),
			ownType : 2
		},
		type : 'view',
		colModel : [{
				display : '����Ҫ�����',
				name : 'workPoint',
				width:'35%'
			}, {
				display : '����ɹ�������׼',
				name : 'outPoint',
				width:'40%'

			}, {
				display : '���ʱ��ڵ�',
				name : 'finishTime',
				width:'20%'
			}, {
				name : 'ownType',
				type : 'hidden',
				value : 2
			}
		]
	});
	$("#schemeTable").yxeditgrid({
		objName : 'examine[schemeTable]',
		url : '?model=hr_permanent_detail&action=listJson',
		type : 'view',
		param : {
			parentId : $("#parentId").val()
		},
		colModel : [{
				display : '������Ŀ',
				name : 'standard',
				width:'15%'
			}, {
				display : '���˷���',
				name : 'standarScore',
				tclass : 'txt',
				width:'8%'
			}, {
				display : '����Ȩ��',
				name : 'standardProportion',
				tclass : 'txt',
				width:'8%'
			}, {
				display : '��������',
				name : 'standardContent',
				tclass : 'txt',
				width:'20%',
				align:'left'
			}, {
				display : '����Ҫ��',
				name : 'standardPoint',
				tclass : 'txt',
				width:'20%',
				align:'left'
			}, {
				display : '����',
				name : 'selfScore',
				width:'3%'
			}, {
				display : '��ʦ����',
				name : 'otherScore',
				width:'3%'
			},  {
				display : '�쵼����',
				name : 'leaderScore',
				width:'3%'
			}, {
				display : '����˵��',
				name : 'comment',
				width:'18%'
			}
		]
	})

})