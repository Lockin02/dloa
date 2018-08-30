$(document).ready(function() {
	$("#leaderName").yxselect_user({
				hiddenId : 'leaderId'
	});
	GongArr = getData('HRGZJB');
	addDataToSelect(GongArr, 'schemeTypeCode');
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
			name : 'ownType',
			type : 'hidden',
			value : 1
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
		}, {
			name : 'ownType',
			type : 'hidden',
			value : 2
		}, {
			type : 'hidden',
			display : 'id',
			name : 'id'
		}]
	});
	$("#schemeTable").yxeditgrid({
		objName : 'examine[schemeTable]',
		url : '?model=hr_permanent_detail&action=listJson',
		param : {
			parentId : $("#parentId").val()
		},
		isAddOneRow : true,
		isAddAndDel : false,
		colModel : [{
			type : 'hidden',
			name : 'id',
			display : 'id'
		}, {
			name : 'standardId',
			type : 'hidden'
		}, {
			display : '������Ŀ',
			name : 'standard',
			readonly : 'readonly',
			type : 'statictext'
		},{
			display : '���˷���',
			name : 'standarScore',
			width:'7%',
			readonly : 'readonly',
			type : 'statictext'
		}, {
			display : '���˷���',
			name : 'standarScore',
			type : 'hidden'
		}, {
			display : '����Ȩ��',
			name : 'standardProportion',
			width:'7%',
			readonly : 'readonly',
			type : 'statictext'
		}, {
			display : '����Ȩ��',
			name : 'standardProportion',
			type : 'hidden'
		},{
			display : '��������',
			name : 'standardContent',
			readonly : 'readonly',
			type : 'statictext',
				align:'left'
		},  {
			display : '����Ҫ��',
			name : 'standardPoint',
			readonly : 'readonly',
			type : 'statictext',
				align:'left'
		}, {
			display : '����',
			name : 'selfScore',
			readonly : 'readonly',
			type : 'statictext'
		}, {
			display : '����[*]',
			name : 'otherScore',
			validation : {
				custom : ['onlyNumber']
			},
			tclass : 'txtshort',
			event : {
				blur : function() {
					caculate();
				}
			}
		}, {
			display : '��ע',
			name : 'comment'
		}, {
			name : 'status',
			type : 'hidden',
			value : 2
		}]
	});
	validate({
				"leaderName" : {
					required : true
				}
	});
     })
 function submitinfo(){
 	if(caculate()){
 		$("#status").val(3);
 		$("#submitInfo").val(1);
        $("#form1").submit();
 	}

 }



function caculate() {
	var rowAmountVa = 0;
	var cmps = $("#schemeTable").yxeditgrid("getCmpByCol", "otherScore");
	var portions = $("#schemeTable").yxeditgrid("getCmpByCol", "standarScore");
	var standardProportion = $("#schemeTable").yxeditgrid("getCmpByCol", "standardProportion");
	for(var i=0;i<cmps.length;i++){
	if(parseInt(cmps[i].value)>parseInt(portions[i].value)){
		alert("���ֲ��ܸ��ڿ��˷������ֵ");
		cmps[i].value='';
		$("#otherScore").val("");
		return false;
		}
	else if(parseInt(cmps[i].value)<0||cmps[i].value=='-0'){
		alert("������������");
		cmps[i].value='';
		$("#otherScore").val("");
		return false;
		}
	else{
		if(cmps[i].value.indexOf(".")!=-1){
			alert("����������")
			cmps[i].value='';
			$("#otherScore").val("");
			return false;
		}
	}
	}
// cmps.each(function() {
// rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
// });
	for(var i=0;i<cmps.length;i++){
		var percent=accDiv(standardProportion[i].value, 10);
		var mark=accMul(cmps[i].value,percent);// ��ðٷֱȺ�ķ���
		rowAmountVa = accAdd(rowAmountVa, mark, 2);// �������
	}
	if( rowAmountVa > 100 ){
		alert("�ܺͲ��ܳ���100��");return false;
	}
	$("#totalScore").val(rowAmountVa);
	return true;
}