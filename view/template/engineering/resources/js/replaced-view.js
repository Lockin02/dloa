$(document).ready(function() {
	$("#info").yxeditgrid({
		objName : 'replaced[info]',
		url : '?model=engineering_resources_replacedinfo&action=listJson',
		type:'view',
		param : {
			'replacedId' : $("#id").val()
		},
		colModel : [{
			display : '�豸id',
			name : 'deviceId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'deviceName',
			width : 200,
			readonly : true
		},{
			display : '��ע',
			name : 'remark',
			tclass: 'txtlong'
		}]
	})

});

