$(function(){
	$("#competitorList").yxeditgrid({
		objName : 'chance[competitor]',
		isAddOneRow : false,
		url : '?model=projectmanagent_chance_competitor&action=listJson',
		param : {
			'chanceId' : $("#chanceId").val()
		},
		tableClass : 'form_in_table',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '��������',
			name : 'competitor',
			tclass : 'txt'
		}, {
			display : '��������',
			name : 'superiority',
			tclass : 'txt'
		}, {
			display : '��������',
			name : 'disadvantaged',
			tclass : 'txt'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	});
});