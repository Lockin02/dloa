$(document).ready(function () {

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
				width:'10%'
			},  {
				display : '���˷���',
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
				width:'25%',
				align:'left'
			}, {
				display : '����',
				name : 'selfScore',
				width:'3%'
			}, {
				display : '����˵��',
				name : 'comment',
				width:'18%'
			}
		]
	})

})