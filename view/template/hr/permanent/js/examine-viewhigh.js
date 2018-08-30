$(document).ready(function () {

	$("#schemeTable").yxeditgrid({
		objName : 'examine[schemeTable]',
		url : '?model=hr_permanent_detail&action=listJson',
		type : 'view',
		param : {
			parentId : $("#parentId").val()
		},
		colModel : [{
				display : '考核项目',
				name : 'standard',
				width:'10%'
			},  {
				display : '考核分数',
				name : 'standardProportion',
				tclass : 'txt',
				width:'8%'
			}, {
				display : '考核内容',
				name : 'standardContent',
				tclass : 'txt',
				width:'20%',
				align:'left'
			}, {
				display : '考核要点',
				name : 'standardPoint',
				tclass : 'txt',
				width:'25%',
				align:'left'
			}, {
				display : '自评',
				name : 'selfScore',
				width:'3%'
			}, {
				display : '其他说明',
				name : 'comment',
				width:'18%'
			}
		]
	})

})