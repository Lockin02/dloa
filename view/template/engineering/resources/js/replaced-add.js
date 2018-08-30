$(document).ready(function() {
	$("#info").yxeditgrid({
		objName : 'replaced[info]',
		colModel : [{
			display : '�豸id',
			name : 'deviceId',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'deviceName',
			width : 200,
			validation : {
				required : true
			},
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_esmdevice({
					hiddenId : 'info_cmp_deviceId' + rowNum,
					width : 600,
					checkNum : false,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum,'deviceId').val(rowData.id);
								}
							})(rowNum)
						}
					}
				});
			}
		},{
			display : '��ע',
			name : 'remark',
			tclass: 'txtlong'
		}]
	})


	// ��ԴĿ¼����combogrid
	$("#deviceName").yxcombogrid_esmdevice({
		hiddenId : 'deviceId',
		width : 600,
		height : 300,
		isFocusoutCheck : false,
		checkNum : false,
		gridOptions : {
			showcheckbox : false
		}
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"deviceName" : {
			required : true
		}
	});
});

function sub(){
	var unRepeat = true;
   $.ajax({
	    type: "POST",
	    url: "?model=engineering_resources_replaced&action=checkIsRepeat",
	    data: {"deviceId" : $("#deviceId").val()},
	    async: false,
	    success: function(data){
	    	if(data == "1"){
				alert('�豸�Ѵ���');
				unRepeat = false;
	   	    }
		}
	});
	return false;

}