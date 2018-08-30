$(document).ready(function () {
	$("#summaryTable").yxeditgrid({
		objName : 'examine[summaryTable]',
		isAddOneRow : true,
		url : '?model=hr_permanent_linkcontent&action=listJson',
		type : 'view',
		param : {
			parentId : $("#parentId").val(),
			ownType : 1
		},
		colModel : [{
			display : '工作要点概述',
			name : 'workPoint',
			validation : {
				required : true
			}
		}, {
			display : '输出成果',
			name : 'outPoint',
			validation : {
				required : true
			}
		}, {
			display : '完成时间节点',
			name : 'finishTime',
			event : {
				focus : function() {
					WdatePicker();
				}
			},
			validation : {
				required : true
			}
		}, {
			type : 'hidden',
			display : 'id',
			name : 'id'
		}]
	});

	$("#planTable").yxeditgrid({
		objName : 'examine[planTable]',
		url : '?model=hr_permanent_linkcontent&action=listJson',
		type : 'view',
		param : {
			parentId : $("#parentId").val(),
			ownType : 2
		},
		isAddOneRow : true,
		colModel : [{
			display : '工作要点概述',
			name : 'workPoint',
			validation : {
				required : true
			}
		}, {
			display : '输出成果或检验标准',
			name : 'outPoint',
			validation : {
				required : true
			}
		}, {
			display : '完成时间节点',
			name : 'finishTime',
			event : {
				focus : function() {
					WdatePicker();
				}
			},
			validation : {
				required : true
			}
		}]
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
				name : 'standard'
			},
			{
				display : '考核分数',
				name : 'standarScore',
				width: '50',
				type : 'statictext'
			},
			{
			display : '考核权重',
			name : 'standardProportion',
			type : 'statictext'
		}, {
				display : '考核要点',
				name : 'standardPoint',
				tclass : 'txt',
				align:'left'
			}, {
				display : '考核内容',
				name : 'standardContent',
				tclass : 'txt',
				align:'left'
			}, {
				display : '自评',
				width: '50',
				name : 'selfScore'
			}, {
				display : '导师评分',
				width: '50',
				name : 'otherScore'
			},  {
				display : '领导评分',
				width: '50',
				name : 'leaderScore'
			},{
				display : '其他说明',
				name : 'comment'
			}
		]
	})

})


//function submitinfo(){
//	//alert($('input:radio[name="examine[isAgree]"]:checked').val());
//	if($('input:radio[name="examine[isAgree]"]:checked').val()){
//		return true;
//	}else{
//		alert("请填写是否同意");
//		return false;
//	}
//
//}
$(function (){
	if ($("#isAgree").val()!='0'){
		$("#ok").hide();
	}else{
		$("#seav").hide();
	}
});