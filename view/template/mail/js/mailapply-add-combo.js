
/** ********************�ʼĵ�λ�������************************ */
$(function() {
	// ��ѡ�ͻ�
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
//					alert(data.Prov);
				}
			}
		}
	})
});