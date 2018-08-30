$(document).ready(function() {
	$("#referenceSchemeName").yxcombogrid_tutorscheme({
		hiddenId :  'schemeId',
		isFocusoutCheck : true,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data){
					$("#tutProportion").val("");
					$("#deptProportion").val("");
					$("#hrProportion").val("");
					$("#stuProportion").val("");
					$("#schemeDetailList").yxeditgrid('remove');
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
		$("#schemeDetailList").yxeditgrid({
								objName : 'tutorScheme[attrvals]',
								tableClass : 'form_in_table',
								url : '?model=hr_tutor_schemeDetail&action=listJson',
								param : {
									parentId :schemeId,
									dir : 'ASC'
								},
								dir : 'ASC',
								realDel : true,
								colModel : [ {
											display : '������Ŀ',
											name : 'appraisal',
											type : 'txt',
											width : '8%',
											validation : {
												required : true
											}
										}, {
											display : 'Ȩ��ϵ��',
											name : 'coefficient',
											type : 'txt',
											width : '50px',
											validation : {
												required : true,
												custom : ['percentage']
											}
										}, {
											display : '�����߶ȣ�����:9(��)-10��',
											name : 'scaleA',
											type : 'textarea',
											cols : '20',
											rows : '3',
											validation : {
												required : true
											}
										}, {
											display : '�����߶ȣ�����:7(��)-9��',
											name : 'scaleB',
											type : 'textarea',
											cols : '20',
											rows : '3',
											validation : {
												required : true
											}
										}, {
											display : '�����߶ȣ�һ��:5(��)-7��',
											name : 'scaleC',
											type : 'textarea',
											cols : '20',
											rows : '3',
											validation : {
												required : true
											}
										}, {
											display : '�����߶ȣ��ϲ�:3(��)-5��',
											name : 'scaleD',
											type : 'textarea',
											cols : '20',
											rows : '3',
											validation : {
												required : true
											}
										}, {
											display : '�����߶ȣ�����:0-2��',
											name : 'scaleE',
											type : 'textarea',
											cols : '20',
											rows : '3',
											validation : {
												required : true
											}
										}]
							});
}

			$("#schemeDetailList").yxeditgrid({
						objName : 'tutorScheme[attrvals]',
						tableClass : 'form_in_table',
						url : '?model=hr_tutor_schemeDetail&action=listJson',
						param : {
							parentId : $("#id").val(),
							dir : 'ASC'
						},
						dir : 'ASC',
						realDel : true,
						colModel : [ {
									display : '������Ŀ',
									name : 'appraisal',
									type : 'txt',
									width : '8%',
									validation : {
										required : true
									}
								}, {
									display : 'Ȩ��ϵ��',
									name : 'coefficient',
									type : 'txt',
									width : '50px',
									validation : {
										required : true,
										custom : ['percentage']
									}
								}, {
									display : '�����߶ȣ�����:9(��)-10��',
									name : 'scaleA',
									type : 'textarea',
									cols : '20',
									rows : '3',
									validation : {
										required : true
									}
								}, {
									display : '�����߶ȣ�����:7(��)-9��',
									name : 'scaleB',
									type : 'textarea',
									cols : '20',
									rows : '3',
									validation : {
										required : true
									}
								}, {
									display : '�����߶ȣ�һ��:5(��)-7��',
									name : 'scaleC',
									type : 'textarea',
									cols : '20',
									rows : '3',
									validation : {
										required : true
									}
								}, {
									display : '�����߶ȣ��ϲ�:3(��)-5��',
									name : 'scaleD',
									type : 'textarea',
									cols : '20',
									rows : '3',
									validation : {
										required : true
									}
								}, {
									display : '�����߶ȣ�����:0-2��',
									name : 'scaleE',
									type : 'textarea',
									cols : '20',
									rows : '3',
									validation : {
										required : true
									}
								}]
					});
			validate({
						"schemeName" : {
							required : true
						},
						"tutProportion" : {
							required : true,
							custom : ['percentage']
						},
						"hrProportion" : {
							required : true,
							custom : ['percentage']
						},
						"stuProportion" : {
							required : true,
							custom : ['percentage']
						}
					});

		})
// �ж�Ȩ��ϵ��֮���Ƿ����100
function check() {
	var g = $("#schemeDetailList").data("yxeditgrid");
	var $coefficient = g.getCmpByCol("coefficient");
	var coefficientSum = 0;//Ȩ��ϵ��֮��
	var scoreSum = 0;//����Ȩ�ر�֮��
	$coefficient.each(function() {
				coefficientSum += this.value * 1;// ����1���Խ��ַ���ת��Ϊint
			})
	scoreSum = $("#tutProportion").val()*1+$("#deptProportion").val()*1+$("#hrProportion").val()*1+$("#stuProportion").val()*1;
	if(scoreSum!=100){
		alert("����Ȩ��ռ��֮�Ͳ�����100!");
	}else if (coefficientSum != 100) {
		alert("Ȩ��ϵ��֮�Ͳ�����100��");
	} else {
		$("#form1").submit();
	}
}