$(function() {
	//$("#account").val(moneyFormat2($("#account").val()));
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"equipId" : {
			required : true
		},
		"equipName" : {
			required : true
		},
		"regDate" : {
			required : true

		},
		"spec" : {
			required : true
		},
		"num" : {
			custom : ['onlyNumber']
		},
		"unit" : {
			required : true
		},
		"account" : {
			custom : ['money']
		}
	});

	$('#equipCode').yxcombogrid_asset({
		hiddenId : 'equipId',
		nameCol : 'assetCode',
		gridOptions : {
			param : {
				'useStatusCode' : 'SYZT-XZ',
				//				'agencyCode' : agency,
				'isDel' : '0',
				'idle' : '0',
				'isScrap' : '0'
			},
			event : {
				row_dblclick : function(e, row, rowData) {
					$('#equipName').val(rowData.assetName)
					$('#regDate').val(rowData.wirteDate)
					$('#spec').val(rowData.spec)
					$('#unit').val(rowData.unit)
					$('#num').val(1)
					$('#place').val(rowData.place)
				}
			}
		}
	});
});
