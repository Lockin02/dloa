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
									display : '考评项目',
									name : 'appraisal',
									type : 'txt',
									width : '8%',
									validation : {
										required : true
									}
								}, {
									display : '权重系数',
									name : 'coefficient',
									type : 'txt',
									width : '50px',
									validation : {
										required : true,
										custom : ['percentage']
									}
								}, {
									display : '考评尺度（优秀:9(含)-10）',
									name : 'scaleA',
									type : 'textarea',
									cols : '20',
									rows : '3',
									validation : {
										required : true
									}
								}, {
									display : '考评尺度（良好:7(含)-9）',
									name : 'scaleB',
									type : 'textarea',
									cols : '20',
									rows : '3',
									validation : {
										required : true
									}
								}, {
									display : '考评尺度（一般:5(含)-7）',
									name : 'scaleC',
									type : 'textarea',
									cols : '20',
									rows : '3',
									validation : {
										required : true
									}
								}, {
									display : '考评尺度（较差:3(含)-5）',
									name : 'scaleD',
									type : 'textarea',
									cols : '20',
									rows : '3',
									validation : {
										required : true
									}
								}, {
									display : '考评尺度（极差:0-2）',
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
//判断权重系数之和是否等于100
function check() {
	var g = $("#schemeDetailList").data("yxeditgrid");
	var $coefficient = g.getCmpByCol("coefficient");
	var coefficientSum = 0;//权重系数之和
	var scoreSum = 0;//评分权重比之和
	$coefficient.each(function() {
				coefficientSum += this.value * 1;// 乘以1可以将字符串转换为int
			})
	scoreSum = $("#tutProportion").val()*1+$("#deptProportion").val()*1+$("#hrProportion").val()*1+$("#stuProportion").val()*1;
	if(scoreSum!=100){
		alert("评分权重占比之和不等于100!");
	}else if (coefficientSum != 100) {
		alert("权重系数之和不等于100！");
	} else {
		$("#form1").submit();
	}
}