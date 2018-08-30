$(document).ready(function() {
	GongArr = getData('HRGZJB');
	addDataToSelect(GongArr, 'schemeTypeCode');
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
		param : {
			parentId : $("#parentId").val()
		},
		isAddOneRow : true,
		isAddAndDel : false,
		colModel : [{
			name : 'standardId',
			type : 'hidden'
		},{
			display : '������Ŀ',
			name : 'standard',
			readonly : 'readonly',
			type : 'statictext'
		}, {
			display : '���˷���',
			name : 'standarScore',
			type : 'statictext'
		},{
			display : '����Ȩ��',
			name : 'standardProportion',
			type : 'statictext'
		}, {
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
		},{
			display : '����',
			name : 'selfScore',
			readonly : 'readonly',
			type : 'statictext'
		}, {
			display : '��ʦ����',
			name : 'otherScore',
			readonly : 'readonly',
			type : 'statictext'
		},{
			display : '�쵼����',
			name : 'leaderScore',
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
			type : 'hidden',
			display : 'id',
			name : 'id'
		}]
	});
  })

function caculate(){
	var rowAmountVa = 0;
	var cmps = $("#schemeTable").yxeditgrid("getCmpByCol", "leaderScore");
	var portions = $("#schemeTable").yxeditgrid("getCmpByCol", "standarScore");
	var standardProportion = $("#schemeTable").yxeditgrid("getCmpByCol", "standardProportion");
	for(var i=0;i<cmps.length;i++){
		if(parseInt(cmps[i].value)>parseInt(portions[i].value)){
			alert("���ֲ��ܸ��ڿ��˷������ֵ");
			cmps[i].value='';
			$("#leaderScore").val("");
			return false;
			}
		else if(parseInt(cmps[i].value)<0||cmps[i].value=='-0'){
			alert("������������");
			cmps[i].value='';
			$("#leaderScore").val("");
			return false;
			}
		else{
			if(cmps[i].value.indexOf(".")!=-1){
				alert("����������")
				cmps[i].value='';
				$("#leaderScore").val("");
				return false;
			}
		}
		}
	for(var i=0;i<cmps.length;i++){
		var percent=accDiv(standardProportion[i].value, 10);
		var mark=accMul(cmps[i].value,percent);// ��ðٷֱȺ�ķ���
		rowAmountVa = accAdd(rowAmountVa, mark, 2);// �������
	}
	if(rowAmountVa>100){
		alert("�ܺͲ��ܳ���100��");return false;
		}
	$("#leaderScore").val(rowAmountVa);
	return true;
}
function submitToHr(){
	$("#status").val(5);
 	$("#submitInfo").val(1);
    $("#form1").submit();
}

