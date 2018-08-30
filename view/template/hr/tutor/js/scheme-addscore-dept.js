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
							width : '80px',
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
							display : '��ʦ����',
							name : 'selfgraded',
							type : 'statictext',
							isSubmit : true,
							process : function(v) {
								if (v == "0.00") {
									return "";
								} else {
									return v;
								}
							}
						},{
							display : '����˵��',
							name : 'selfRemark',
							type : 'statictext',
							isSubmit : true
						}, {
							display : '������Դ������',
							name : 'hrgraded',
							type : 'statictext',
							isSubmit : true,
							process : function(v) {
								if (v == "0.00") {
									return "";
								} else {
									return v;
								}
							}
						}, {
							display : '������Դ������˵��',
							name : 'hrRemark',
							type : 'statictext',
							isSubmit : true
						},{
							display : 'ֱ���ϼ�����',
							name : 'superiorgraded',
							type : 'statictext',
							isSubmit : true,
							process : function(v) {
								if (v == "0.00") {
									return "";
								} else {
									return v;
								}
							}
						},   {
							display : '�ϼ�����˵��',
							name : 'superiorRemark',
							type : 'statictext',
							isSubmit : true
						},{
							display : '��Ա������',
							name : 'staffgraded',
							type : 'statictext',
							isSubmit : true,
							process : function(v) {
								if (v == "0.00") {
									return "";
								} else {
									return v;
								}
							}
						},{
							display : '��Ա������˵��',
							name : 'staffRemark',
							type : 'statictext',
							isSubmit : true
						}, {
							display : '���Ÿ���������',
							name : 'assistantgraded',
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
							name : 'assistantRemark',
							tclass : 'txtshort'
						}]
			});
			validate({

					});

})

function sub() {
	// $("form").bind("submit", function() {
	if (confirm("��ɿ��˺����㲢�رտ������֣���ȷ�ϸ��������Ƿ�����д�������ֺ�����ɿ���! ȷ��Ҫ��ɿ�����?")) {
		document.getElementById('form1').action = "index1.php?model=hr_tutor_scheme&action=edit&act=con";
		$("#form1").submit();
		return true;
	}
	return false
	// });
}