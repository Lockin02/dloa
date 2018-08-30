$(document).ready(function() {
// 提交验证
	$("#form1").validationEngine({
	inlineValidation: false,
	success :  function(){
		   sub();
		   $("#form1").submit();// 加上验证后再提交表单，解决需要连续点击两次按钮才能提交表单的bug
	},
	failure :false
	})
	// 邮件抄送人
	if($("#ADDIDS").length!=0){
		$("#ADDNAMES").yxselect_user({
			mode : 'check',
			hiddenId : 'ADDIDS',
			formCode : 'tutor'
		});
	}
	// 邮件发送人
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

	// 新员工直接上级
	$("#superiorName").yxselect_user({
		hiddenId : 'superiorId'
	});

	// hr评分人
	$("#hrName").yxselect_user({
		hiddenId : 'hrId'
	});

	// hr评分人部门助理
	$("#assistantName").yxselect_user({
		hiddenId : 'assistantId'
	});
		//渲染模板选择
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
			display : '考评项目',
			name : 'appraisal',
			width : '15%',
			type : 'statictext',
			isSubmit : true
		},{
			display : '权重系数',
			name : 'coefficient',
			width : '5%',
			type : 'statictext',
			isSubmit : true
		},{
			display : '优秀：9(含)-10',
			name : 'scaleA',
			type : 'statictext',
			width : '15%',
			isSubmit : true
		},{
			display : '良好：7(含)-9',
			name : 'scaleB',
			type : 'statictext',
			width : '15%',
			isSubmit : true
		},{
			display : '一般：5(含)-7',
			name : 'scaleC',
			type : 'statictext',
			width : '15%',
			isSubmit : true
		},{
			display : '较差：3(含)-5',
			name : 'scaleD',
			type : 'statictext',
			width : '15%',
			isSubmit : true
		},{
			display : '极差：0-2',
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
		// 验证评分权重是否为100%
		var tutProportion = $("#tutProportion").val();// 导师占比
		var deptProportion = $("#deptProportion").val();// 部门占比
		var hrProportion = $("#hrProportion").val();// hr占比
		var stuProportion = $("#stuProportion").val();// 直接上级占比
		var addArr = [tutProportion, deptProportion, hrProportion, stuProportion]
		var allNum = accAddMore(addArr);
		if (allNum != '100') {
			alert("您输入的评分占比之和为【" + allNum + "%】 请将占比之和调整为 100% ");
			return false;
		}
		return true;
	});
}