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
			display : '����Ҫ�����',
			name : 'workPoint',
			validation : {
				required : true
			}
		}, {
			display : '����ɹ�',
			name : 'outPoint',
			validation : {
				required : true
			}
		}, {
			display : '���ʱ��ڵ�',
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
			display : '����Ҫ�����',
			name : 'workPoint',
			validation : {
				required : true
			}
		}, {
			display : '����ɹ�������׼',
			name : 'outPoint',
			validation : {
				required : true
			}
		}, {
			display : '���ʱ��ڵ�',
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
				display : '������Ŀ',
				name : 'standard'
			},
			{
				display : '���˷���',
				name : 'standarScore',
				width: '50',
				type : 'statictext'
			},
			{
			display : '����Ȩ��',
			name : 'standardProportion',
			type : 'statictext'
		}, {
				display : '����Ҫ��',
				name : 'standardPoint',
				tclass : 'txt',
				align:'left'
			}, {
				display : '��������',
				name : 'standardContent',
				tclass : 'txt',
				align:'left'
			}, {
				display : '����',
				width: '50',
				name : 'selfScore'
			}, {
				display : '��ʦ����',
				width: '50',
				name : 'otherScore'
			},  {
				display : '�쵼����',
				width: '50',
				name : 'leaderScore'
			},{
				display : '����˵��',
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
//		alert("����д�Ƿ�ͬ��");
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