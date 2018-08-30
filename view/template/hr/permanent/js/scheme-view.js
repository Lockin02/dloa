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
				display : '考核项目',
				name : 'standard',
				type : 'statictext',
				width : 100
			}, {
				display : '考核分数',
				name : 'standarScore',
				width : 60,
				type : 'statictext'
			}, {
				display : '考核权重',
				name : 'standardProportion',
				width : 60,
				type : 'statictext'
			}, {
				display : '考核内容',
				name : 'standardContent',
				width : 350,
				type : 'statictext',
				align:'left'
			}, {
				display : '考核要点',
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