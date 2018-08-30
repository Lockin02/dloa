$(document).ready(function(){
	/**
	 * 验证信息
	 */
	validate({
		"resourceCode" : {
			required : true,
			length : [0,100]
		},
		"resourceName" : {
			required : true,
			length : [0,100]
		},
		"number" : {
			custom : ['onlyNumber']
		},
		"useDays" : {
			required : true,
			custom : ['onlyNumber']
		}
	});

	// 资源目录下拉combogrid
	$("#resourceName").yxcombogrid_esmdevice({
		hiddenId : 'resourceId',
        valueCol: 'virtualId',
		width : 600,
		height : 300,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#resourceTypeName").val(data.deviceType);
					$("#resourceTypeId").val(data.typeid);
					$("#unit").val(data.unit);
					setMoney("price",data.discount);
					//计算设备金额
					calResource();
				}
			}
		}
	});
});