$(document).ready(function() {

			/*
			 * validate({ "orderNum" : { required : true, custom : 'onlyNumber' }
			 * });
			 */
			$("#schemeDetailList").yxeditgrid({
						objName : 'tutorScheme[attrvals]',
						tableClass : 'form_in_table',
						url : '?model=hr_tutor_schemeDetail&action=listJson',
						param : {
							parentId : $("#id").val(),
							dir : 'ASC'
						},
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									type : 'hidden'
								}, {
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
						"supProportion" : {
							required : true,
							custom : ['percentage']
						}
					});

		})
//�ж�Ȩ��ϵ��֮���Ƿ����100
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