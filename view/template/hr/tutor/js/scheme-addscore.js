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
			display : '������Ŀ',
			name : 'appraisal',
			width : '130px',
			type : 'statictext',
			isSubmit : true
		},{
			display : 'Ȩ��ϵ��',
			name : 'coefficient',
			width : '80px',
			type : 'statictext',
			isSubmit : true
		},{
			display : '���㣺9(��)-10',
			name : 'scaleA',
			type : 'statictext',
			isSubmit : true
		},{
			display : '���ã�7(��)-9',
			name : 'scaleB',
			type : 'statictext',
			isSubmit : true
		},{
			display : 'һ�㣺5(��)-7',
			name : 'scaleC',
			type : 'statictext',
			isSubmit : true
		},{
			display : '�ϲ3(��)-5',
			name : 'scaleD',
			type : 'statictext',
			isSubmit : true
		},{
			display : '���0-2',
			name : 'scaleE',
			type : 'statictext',
			isSubmit : true
		},{
			display : '��ʦ������',
			name : 'selfgraded',
			width : '50px',
			readonly : true,
			tclass : 'rimless_textB'
		},{
			display : 'ֱ���ϼ�����',
			name : 'superiorgraded',
			width : '50px',
			readonly : falg1,
			tclass : 'rimless_textB'
		},{
			display : '������Դ������',
			name : 'hrgraded',
			width : '50px',
			readonly : falg2,
			tclass : 'rimless_textB'
		},{
			display : '������������',
			name : 'assistantgraded',
			width : '50px',
			readonly : falg3,
			tclass : 'rimless_textB'
		},{
			display : '��Ա��������(�ο�)',
			name : 'staffgraded',
			width : '50px',
			readonly : true,
			tclass : 'rimless_textB'
		}]
	});
})