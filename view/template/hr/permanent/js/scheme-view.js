$(function () {
	$("#schemeTable").yxeditgrid({
		objName : 'scheme[standard]',
		delTagName : 'isDelTag',
//		type : 'view',
		isAddAndDel : false,
		url : '?model=hr_permanent_schemelist&action=listJson',
		param : {
			parentId : $("#parentId").val()
		},
		colModel : [{
				display : '������Ŀ',
				name : 'standard',
				type : 'statictext',
				width : 100
			}, {
				display : '���˷���',
				name : 'standarScore',
				width : 60,
				type : 'statictext'
			}, {
				display : '����Ȩ��',
				name : 'standardProportion',
				width : 60,
				type : 'statictext'
			}, {
				display : '��������',
				name : 'standardContent',
				width : 350,
				type : 'statictext',
				align:'left'
			}, {
				display : '����Ҫ��',
				name : 'standardPoint',
				type : 'textarea',
				tclass : 'textarea_editgrid_read',
				width : 400,
				align:'left',
				readonly:true
			}
		]
	})

})