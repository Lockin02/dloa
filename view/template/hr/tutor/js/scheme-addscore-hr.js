$(document).ready(function() {
	$("#schemeTable").yxeditgrid({
				objName : 'scheme[schemeinfo]',
				isAddAndDel : false,
				url : '?model=hr_tutor_schemeinfo&action=listjson',
				param : {
					'tutorassessId' : $("#id").val()
				},
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
						}, {
							display : '权重系数',
							name : 'coefficient',
							width : '5%',
							type : 'statictext',
							isSubmit : true
						}, {
							display : '优秀：9(含)-10',
							name : 'scaleA',
							type : 'statictext',
							width : '15%',
							isSubmit : true
						}, {
							display : '良好：7(含)-9',
							name : 'scaleB',
							type : 'statictext',
							width : '15%',
							isSubmit : true
						}, {
							display : '一般：5(含)-7',
							name : 'scaleC',
							type : 'statictext',
							width : '15%',
							isSubmit : true
						}, {
							display : '较差：3(含)-5',
							name : 'scaleD',
							type : 'statictext',
							width : '15%',
							isSubmit : true
						}, {
							display : '极差：0-2',
							name : 'scaleE',
							type : 'statictext',
							width : '15%',
							isSubmit : true
						}, {
							display : '导师自评',
							name : 'selfgraded',
							type : 'statictext',
							width : '1%',
							isSubmit : true,
							process : function(v) {
								if (v == "0.00") {
									return "";
								} else {
									return v;
								}
							}
						},{
							display : '自评说明',
							name : 'selfRemark',
							type : 'statictext',
							isSubmit : true
						}, {
							display : '部门负责人评分',
							name : 'assistantgraded',
							type : 'statictext',
							width : '1%',
							isSubmit : true,
							process : function(v) {
								if (v == "0.00") {
									return "";
								} else {
									return v;
								}
							}
						}, {
							display : '部门负责人评分说明',
							name : 'assistantRemark',
							type : 'statictext',
							isSubmit : true
						},{
							display : '直接上级评分',
							name : 'superiorgraded',
							type : 'statictext',
							isSubmit : true,
							width : '1%',
							process : function(v) {
								if (v == "0.00") {
									return "";
								} else {
									return v;
								}
							}
						},  {
							display : '上级评分说明',
							name : 'superiorRemark',
							type : 'statictext',
							isSubmit : true
						},{
							display : '新员工评分',
							name : 'staffgraded',
							type : 'statictext',
							isSubmit : true,
							width : '1%',
							process : function(v) {
								if (v == "0.00") {
									return "";
								} else {
									return v;
								}
							}
						},{
							display : '新员工评分说明',
							name : 'staffRemark',
							type : 'statictext',
							isSubmit : true
						}, {
							display : '人力资源部评分',
							name : 'hrgraded',
							tclass : 'txtmin',
							event : {
								blur : function() {
									var score = $(this).val();
									var num = /^(0|[1-9]\d*)$|^(0|[1-9]\d*)\.(\d+)$/;
									if (!num.test(score) && obj.value != "") {
										alert("请正确填写分数");
										$(this).val("");
									} else {
										if (score < 0 || score > 10) {
											alert("分数请填写 0~10 以内的数字");
											$(this).val("");
										}
									}
								}
							},
							validation : {
								required : true
							}
						},{
							display : '评分说明',
							name : 'hrRemark',
							tclass : 'txtshort'
						}]
							});
			validate({

					});
})

function sub() {
	// $("form").bind("submit", function() {
	if (confirm("完成考核后会计算并关闭考核评分，请确认各评分人是否已填写考核评分后再完成考核! 确定要完成考核吗?")) {
		document.getElementById('form1').action = "index1.php?model=hr_tutor_scheme&action=edit&act=con";
		$("#form1").submit();
		return true;
	}
	return false
	// });
}