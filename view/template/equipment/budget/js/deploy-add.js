$(document).ready(function() {

   $("#info").yxeditgrid({
		objName : 'deploy[info]',
		tableClass : 'form_in_table',
		colModel : [{
					display : '��ϸ����',
					name : 'info',
					tclass : 'txt',
					width : 400
				}, {
					display : '����',
					name : 'price',
					tclass : 'txt',
					type : 'money',
					width : 100
				}]

   })
})