$(document).ready(function() {
	var falg1=true;
	var falg2=true;
	var falg3=true;
	if($("#userId").val()==$("#superiorId").val())
		falg1=false;
	if($("#userId").val()==$("#hrId").val())
		falg2=false;
	if($("#userId").val()==$("#assistantId").val())
		falg3=false;

//	$(":input").find("readonly:true").first().change(function(){
//		alert(111);
//	})

	$("#schemeTable").yxeditgrid({
		objName : 'scheme[schemeinfo]',
		isAddAndDel : false,
		url : '?model=hr_tutor_schemeinfo&action=schemeinfo&id='+$("#id").val(),
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
		},{
			display : '权重系数',
			name : 'coefficient',
			width : '80px',
			type : 'statictext',
			isSubmit : true
		},{
			display : '优秀：9(含)-10',
			name : 'scaleA',
			type : 'statictext',
			isSubmit : true
		},{
			display : '良好：7(含)-9',
			name : 'scaleB',
			type : 'statictext',
			isSubmit : true
		},{
			display : '一般：5(含)-7',
			name : 'scaleC',
			type : 'statictext',
			isSubmit : true
		},{
			display : '较差：3(含)-5',
			name : 'scaleD',
			type : 'statictext',
			isSubmit : true
		},{
			display : '极差：0-2',
			name : 'scaleE',
			type : 'statictext',
			isSubmit : true
		},{
			display : '导师自评分',
			name : 'selfgraded',
			width : '50px',
			readonly : true,
			tclass : 'rimless_textB'
		},{
			display : '直接上级评分',
			name : 'superiorgraded',
			width : '50px',
			readonly : falg1,
			tclass : 'rimless_textB'
		},{
			display : '人力资源部评分',
			name : 'hrgraded',
			width : '50px',
			readonly : falg2,
			tclass : 'rimless_textB'
		},{
			display : '部门助理评分',
			name : 'assistantgraded',
			width : '50px',
			readonly : falg3,
			tclass : 'rimless_textB'
		},{
			display : '新员工的评分(参考)',
			name : 'staffgraded',
			width : '50px',
			readonly : true,
			tclass : 'rimless_textB'
		}]
	});
})