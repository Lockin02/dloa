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
							display : '������Ŀ',
							name : 'appraisal',
							width : '130px',
							type : 'statictext',
							isSubmit : true
						}, {
							display : 'Ȩ��ϵ��',
							name : 'coefficient',
							width : '60px',
							type : 'statictext',
							isSubmit : true
						}, {
							display : '���㣺9(��)-10',
							name : 'scaleA',
							type : 'statictext',
							width : '15%',
							isSubmit : true
						}, {
							display : '���ã�7(��)-9',
							name : 'scaleB',
							type : 'statictext',
							width : '15%',
							isSubmit : true
						}, {
							display : 'һ�㣺5(��)-7',
							name : 'scaleC',
							type : 'statictext',
							width : '15%',
							isSubmit : true
						}, {
							display : '�ϲ3(��)-5',
							name : 'scaleD',
							type : 'statictext',
							width : '15%',
							isSubmit : true
						}, {
							display : '���0-2',
							name : 'scaleE',
							type : 'statictext',
							width : '15%',
							isSubmit : true
						}, {
							display : 'Ա������',
							name : 'staffgraded',
							type : 'hidden'
						},  {
							display : 'Ա������˵��',
							name : 'staffRemark',
							type : 'hidden'
						},{
							display : 'HR����',
							name : 'hrgraded',
							type : 'hidden'
						},{
							display : 'HR����˵��',
							name : 'hrRemark',
							type : 'hidden'
						},  {
							display : 'ֱ���ϼ�����',
							name : 'superiorgraded',
							type : 'hidden'
						},  {
							display : 'ֱ���ϼ�����˵��',
							name : 'superiorRemark',
							type : 'hidden'
						},{
							display : '������������',
							name : 'assistantgraded',
							type : 'hidden'
						},{
							display : 'assistantRemark',
							name : 'assistantgraded',
							type : 'hidden'
						}, {
							display : '����',
							name : 'selfgraded',
							tclass : 'txtmin',
							event : {
								blur : function() {
									var score = $(this).val();
									var num = /^(0|[1-9]\d*)$|^(0|[1-9]\d*)\.(\d+)$/;
									if (!num.test(score) && obj.value != "") {
										alert("����ȷ��д����");
										$(this).val("");
									} else {
										if (score < 0 || score > 10) {
											alert("��������д 0~10 ���ڵ�����");
											$(this).val("");
										}
									}
								}
							},
							validation : {
								required : true

							}
						},{
							display : '����˵��',
							name : 'selfRemark',
							tclass : 'txtshort'
						}]
			});
			validate({

					});
})