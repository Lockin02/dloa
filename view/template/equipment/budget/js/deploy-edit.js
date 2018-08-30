$(document).ready(function() {

   $("#info").yxeditgrid({
		objName : 'deploy[info]',
		tableClass : 'form_in_table',
		url : '?model=equipment_budget_deploy&action=listJson',
		param : {
			'id' : $("#deployId").val()
		},
		isAddAndDel : false,
		colModel : [{
					display : 'œÍœ∏√Ë ˆ',
					name : 'info',
					tclass : 'txt',
					width : 400
				}, {
					display : 'µ•º€',
					name : 'price',
					tclass : 'txt',
					type : 'money',
					width : 100
				}]

   })
})