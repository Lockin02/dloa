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
			display : '竞争对手',
			name : 'competitor',
			tclass : 'txt'
		}, {
			display : '竞争优势',
			name : 'superiority',
			tclass : 'txt'
		}, {
			display : '竞争劣势',
			name : 'disadvantaged',
			tclass : 'txt'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	});
});