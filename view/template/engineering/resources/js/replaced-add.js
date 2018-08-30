$(document).ready(function() {
	$("#info").yxeditgrid({
		objName : 'replaced[info]',
		colModel : [{
			display : '设备id',
			name : 'deviceId',
			type : 'hidden'
		}, {
			display : '设备名称',
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
			display : '备注',
			name : 'remark',
			tclass: 'txtlong'
		}]
	})


	// 资源目录下拉combogrid
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
	 * 验证信息
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
				alert('设备已存在');
				unRepeat = false;
	   	    }
		}
	});
	return false;

}