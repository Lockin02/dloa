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
				display : '工作要点概述',
				name : 'workPoint',
				width:'35%'
			}, {
				display : '输出成果',
				name : 'outPoint',
				width:'40%'
			}, {
				display : '完成时间节点',
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
				display : '工作要点概述',
				name : 'workPoint',
				width:'35%'
			}, {
				display : '输出成果或检验标准',
				name : 'outPoint',
				width:'40%'

			}, {
				display : '完成时间节点',
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
				display : '考核项目',
				name : 'standard',
				width:'15%'
			}, {
				display : '考核分数',
				name : 'standarScore',
				tclass : 'txt',
				width:'8%'
			}, {
				display : '考核权重',
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
				width:'20%',
				align:'left'
			}, {
				display : '自评',
				name : 'selfScore',
				width:'3%'
			}, {
				display : '导师评分',
				name : 'otherScore',
				width:'3%'
			},  {
				display : '领导评分',
				name : 'leaderScore',
				width:'3%'
			}, {
				display : '其他说明',
				name : 'comment',
				width:'18%'
			}
		]
	})

})