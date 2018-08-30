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
							width : '130px',
							type : 'statictext',
							isSubmit : true
						}, {
							display : '权重系数',
							name : 'coefficient',
							width : '60px',
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
							display : '员工评分',
							name : 'staffgraded',
							type : 'hidden'
						},  {
							display : '员工评分说明',
							name : 'staffRemark',
							type : 'hidden'
						},{
							display : 'HR评分',
							name : 'hrgraded',
							type : 'hidden'
						},{
							display : 'HR评分说明',
							name : 'hrRemark',
							type : 'hidden'
						},  {
							display : '直接上级评分',
							name : 'superiorgraded',
							type : 'hidden'
						},  {
							display : '直接上级评分说明',
							name : 'superiorRemark',
							type : 'hidden'
						},{
							display : '部门助理评分',
							name : 'assistantgraded',
							type : 'hidden'
						},{
							display : 'assistantRemark',
							name : 'assistantgraded',
							type : 'hidden'
						}, {
							display : '自评',
							name : 'selfgraded',
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
							display : '自评说明',
							name : 'selfRemark',
							tclass : 'txtshort'
						}]
			});
			validate({

					});
})