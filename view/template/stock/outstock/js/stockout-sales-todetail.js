$(function() {
			// �ͻ�
			$("#customerName").yxcombogrid_customer({
				hiddenId : 'customerId',
				height : 400,
				isShowButton : false,
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							var getGrid = function() {
								return $("#objCode")
										.yxcombogrid_order("getGrid");
							}
							var getGridOptions = function() {
								return $("#objCode")
										.yxcombogrid_order("getGridOptions");
							}
							if (getGrid().reload) {
								getGridOptions().param = {
									"customerId" : data.id,
									"ExaStatus" : "���"
								};
								getGrid().reload();
							} else {
								getGridOptions().param = {
									"customerId" : data.id,
									"ExaStatus" : "���"
								}
							}
						}
					}
				}
			});

			// ��ͬ���
			$("#objCode").yxcombogrid_allorder({
						hiddenId : 'objId',
						nameCol : 'orderCode',
						searchName : 'orderCodeOrTempSearch',
						height : 400,
						width : 700,
						gridOptions : {
							param : {
								"ExaStatus" : "���"
							},
							showcheckbox : false,
							event : {
								'row_dblclick' : function(e, row, data) {
									if (data.orderCode == "") {
										$("#objCode").val(data.orderTempCode);
									}
									// if(data.orderCode == ""){
									// $("#objCode").yxcombogrid_order('setText',data.orderTempCode);
									// }
								}
							}
						}
			});
			
			// �ƶ�����
			$("#productCode").yxcombogrid_product({
				hiddenId : 'productId',
				height : 400,
				width : 700,
				gridOptions : {
					showcheckbox : false
				}
			});
			
			// ���ϲ���
	        $("#deptName").yxselect_dept({
	            hiddenId: 'deptCode'
	        });
		});

function checkform() {

	var beginYearVal = $("#beginYear").val() * 1; // ��ʼ��

	if (beginYearVal != "") {
		if (isNaN(beginYearVal)) {
			alert('��ʼ��ݲ�������');
			return false;
		}
		if (beginYearVal > 2100 || beginYearVal < 1980) {
			alert('�����������1980 �� 2100 ');
			return false;
		}
	}

	var beginMonthVal = $("#beginMonth").val() * 1; // ��ʼ��

	if (beginMonthVal != "") {
		if (isNaN(beginMonthVal)) {
			alert('��ʼ�·ݲ�������');
			return false;
		}
		if (beginMonthVal > 12 || beginMonthVal < 1) {
			alert('�������·���1 �� 12 ');
			return false;
		}
	}

	var endYearVal = $("#endYear").val() * 1; // ������

	if (endYearVal != "") {
		if (isNaN(endYearVal)) {
			alert('������ݲ�������');
			return false;
		}
		if (endYearVal > 2100 || endYearVal < 1980) {
			alert('�����������1980 �� 2100 ');
			return false;
		}
	}

	var endMonthVal = $("#endMonth").val() * 1; // ������

	if (endMonthVal != "") {
		if (isNaN(endMonthVal)) {
			alert('�����·ݲ�������');
			return false;
		}
		if (endMonthVal > 12 || endMonthVal < 1) {
			alert('�������·���1 �� 12 ');
			return false;
		}
	}

	if ($("#customerName").val() != "") {
		if ($("#customerId").val() == "") {
			alert("��ͨ���������Կͻ�����ѡ��");
			return false;
		}
	}

	if ($("#productCode").val() != "") {
		if ($("#productId").val() == "") {
			alert("��ͨ�������������Ͻ���ѡ��");
			return false;
		}
	}
	return true;
}