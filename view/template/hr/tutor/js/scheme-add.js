$(document).ready(function() {
// �ύ��֤
	$("#form1").validationEngine({
	inlineValidation: false,
	success :  function(){
		   sub();
		   $("#form1").submit();// ������֤�����ύ���������Ҫ����������ΰ�ť�����ύ����bug
	},
	failure :false
	})
	// �ʼ�������
	if($("#ADDIDS").length!=0){
		$("#ADDNAMES").yxselect_user({
			mode : 'check',
			hiddenId : 'ADDIDS',
			formCode : 'tutor'
		});
	}
	// �ʼ�������
	if($("#TO_ID").length!=0){
		$("#TO_NAME").yxselect_user({
			mode : 'check',
			hiddenId : 'TO_ID',
			formCode : 'tutor'
		});
	}

	validate({
		"superiorName" : {
			required : true
		},
		"hrName" : {
			required : true
		},
		"assistantName" : {
			required : true
		},
		"tutorScheme" : {
			required : true
		}
	});

	// ��Ա��ֱ���ϼ�
	$("#superiorName").yxselect_user({
		hiddenId : 'superiorId'
	});

	// hr������
	$("#hrName").yxselect_user({
		hiddenId : 'hrId'
	});

	// hr�����˲�������
	$("#assistantName").yxselect_user({
		hiddenId : 'assistantId'
	});
		//��Ⱦģ��ѡ��
	$("#tutorScheme").yxcombogrid_tutorscheme({
		hiddenId :  'tutorSchemeId',
		isFocusoutCheck : true,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data){
					$("#tutProportion").val("");
					$("#deptProportion").val("");
					$("#hrProportion").val("");
					$("#stuProportion").val("");
					$("#trialplantemdetail").yxeditgrid('remove');
					$("#schemeTable").html('');
					initTemplate(data.id);
					$("#tutProportion").val(data.tutProportion);
					$("#deptProportion").val(data.deptProportion);
					$("#hrProportion").val(data.hrProportion);
					$("#stuProportion").val(data.stuProportion);
//					beforeTaskArr = [];
				}
			}
		}
	});
function initTemplate(schemeId){
	$("#schemeTable").yxeditgrid({
		objName : 'scheme[schemeinfo]',
		isAddAndDel : false,
		url : '?model=hr_tutor_schemeDetail&action=listJson',
		param : {
			'parentId' : schemeId
		},
		isAdd : false,
		event : {
			removeRow : function(t, rowNum, rowData) {
				check_all();
			}
		},
		colModel : [{
			display : '������Ŀ',
			name : 'appraisal',
			width : '15%',
			type : 'statictext',
			isSubmit : true
		},{
			display : 'Ȩ��ϵ��',
			name : 'coefficient',
			width : '5%',
			type : 'statictext',
			isSubmit : true
		},{
			display : '���㣺9(��)-10',
			name : 'scaleA',
			type : 'statictext',
			width : '15%',
			isSubmit : true
		},{
			display : '���ã�7(��)-9',
			name : 'scaleB',
			type : 'statictext',
			width : '15%',
			isSubmit : true
		},{
			display : 'һ�㣺5(��)-7',
			name : 'scaleC',
			type : 'statictext',
			width : '15%',
			isSubmit : true
		},{
			display : '�ϲ3(��)-5',
			name : 'scaleD',
			type : 'statictext',
			width : '15%',
			isSubmit : true
		},{
			display : '���0-2',
			name : 'scaleE',
			type : 'statictext',
			width : '15%',
			isSubmit : true
		}],
		sortname : "id",
		sortorder : "ASC"
	});
}
	})

function sub() {
	$("form").bind("submit", function() {
		// ��֤����Ȩ���Ƿ�Ϊ100%
		var tutProportion = $("#tutProportion").val();// ��ʦռ��
		var deptProportion = $("#deptProportion").val();// ����ռ��
		var hrProportion = $("#hrProportion").val();// hrռ��
		var stuProportion = $("#stuProportion").val();// ֱ���ϼ�ռ��
		var addArr = [tutProportion, deptProportion, hrProportion, stuProportion]
		var allNum = accAddMore(addArr);
		if (allNum != '100') {
			alert("�����������ռ��֮��Ϊ��" + allNum + "%�� �뽫ռ��֮�͵���Ϊ 100% ");
			return false;
		}
		return true;
	});
}