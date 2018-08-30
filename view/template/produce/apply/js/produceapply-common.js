$(document).ready(function() {
	//Դ�������¼�
	$('#relDocTypeCode').change(function () {
		$("#relDocId").val('');
		if ($(this).val() == 'SCYDLX-01') {
			$("#projectName").val('').removeClass('txt').addClass('readOnlyTxtNormal').attr('readonly' ,true);
			//�з���Ŀ����
			$("#relDocCode").val('').attr('readonly' ,true).yxcombogrid_esmproject({
				hiddenId : 'relDocId',
				nameCol : 'projectCode',
				isFocusoutCheck : false,
				gridOptions : {
					isTitle : true,
					showcheckbox : false,
					param: {attribute: 'GCXMSS-05'},
					event : {
						row_dblclick : function(e ,row ,data) {
							$("#projectName").val(data.projectName);
							$("#relDocId").val(data.id);
						}
					}
				}
			});
		} else {
			$("#relDocCode").val('').attr('readonly' ,'');
			$("#projectName").val('').removeClass('readOnlyTxtNormal').addClass('txt').attr('readonly' ,'');
			$("#relDocCode").yxcombogrid_esmproject('remove');
		}
	});

	validate({});
});

//���ôӱ�������������
function setPlanEndDate(e) {
	if (e.value != '') {
		var planEndDateObj = $("#items").yxeditgrid("getCmpByCol" ,"planEndDate");
		planEndDateObj.each(function () {
			if (this.value == '') {
				$(this).val(e.value);
			}
		});
	}
}

/**
 * ��У��
 */
function checkForm() {
	var produceNumObj = $("#items").yxeditgrid("getCmpByCol" ,"produceNum");
	if (produceNumObj.length == 0) {
		alert("û�����ϣ�");
		return false;
	}
	for (var i = 0 ;i < produceNumObj.length ;i++) {
		if (produceNumObj[i].value <= 0) {
			alert("���������������0");
			return false;
		}
	}
	return true;
}