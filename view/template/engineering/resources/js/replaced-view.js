$(document).ready(function() {
	$("#info").yxeditgrid({
		objName : 'replaced[info]',
		url : '?model=engineering_resources_replacedinfo&action=listJson',
		type:'view',
		param : {
			'replacedId' : $("#id").val()
		},
		colModel : [{
			display : '设备id',
			name : 'deviceId',
			type : 'hidden'
		}, {
			display : '设备名称',
			name : 'deviceName',
			width : 200,
			readonly : true
		},{
			display : '备注',
			name : 'remark',
			tclass: 'txtlong'
		}]
	})

});

