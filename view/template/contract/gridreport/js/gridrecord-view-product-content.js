$(function () {
	$("#presentation").change(function () {
		if ($(this).val() == 1) {
			presentation = 1;
			unbindCheckFun();
		} else {
			//���ѡ��(������һ��ѡ��)
			var firstCheck = true;
			$(".checkItems").each(function () {
				if ($(this).attr("checked")) {
					if (firstCheck) {
						firstCheck = false;
						return true; //����ѭ��
					}
					$(this).attr("checked" ,false);
				}
			});
			presentation = 2;
			bindCheckFun(); //�󶨸�Ϊ��ѡ���¼�
		}
	});
	
	// ����ָ������ʱ���¼���
	$("#objCode").change(function () {
		var url = window.location.href;
		var index = url.indexOf('&',url.indexOf('&')+1);
		if(index != -1){
			url = url.substr(0,index);
		}
		window.location.replace(url + '&objCode=' + $("#objCode").val());
	});

	$(".checkItems").each(function () {
		var objId = $(this).val() + 'Check';
		if ($("#" + objId).val() == 1) {
			$(this).attr('checked' ,true);
		}
	});
	$("#presentation option").each(function () {
		if ($(this).val() == $("#presentation").attr("val")) {
			$(this).attr("selected" ,true);
		}
	});
	$("#presentation").trigger("change");

    $("#unit option").each(function () {
        if ($(this).val() == $("#unit").attr("val")) {
            $(this).attr("selected" ,true);
        }
    });

});

//�����û���ѡ��¼
function saveGridRecord() {
	//������ݵ���Ч��
	if (!checkData()) {
		return false;
	}

	var recordData = {};
	//��ȡָ������
	$(".checkItems").each(function () {
		recordData[$(this).val()] = $(this).attr('checked') ? 1 : 0
	});

	recordData["startMonth"] = $("#startMonth").val();
	recordData["endMonth"] = $("#endMonth").val();
	recordData["presentation"] = $("#presentation").val();
    recordData["unit"] = $("#unit").val();
	recordData["objCode"] = $("#objCode").val();

	$.ajax({
		type : 'POST',
		url : '?model=contract_gridreport_gridrecord&action=saveRecord',
		data : recordData,
		success : function (msg) {
			if (msg == 1) {
				alert('����ɹ���');
			} else {
				alert('����ʧ�ܣ�');
			}
		}
	});
}

//������ݵ���Ч��
function checkData() {
	//���ʱ���������Ч��
	if ($("#presentation").val() == 2) {
		if (!checkTimeInterval()) {
			return false;
		}
	}

	//��������ͼ����Ч��
	if (!checkView()) {
		return false;
	}

	return true;
}

//���ʱ���������Ч��
function checkTimeInterval() {
	var startMonth = $("#startMonth").val();
	var endMonth = $("#endMonth").val();
	if (startMonth > endMonth || startMonth == '' || endMonth == '') {
		alert('ʱ����������');
		return false;
	}
	return true;
}

/**��ͼ����ʾ��ʽ�������û���ѡֵ����ʾ��
 * ����ַ�ʽѡ��Ϊ�����¡�����������ͼ���Ƹ������ֻ����ѡ1��ָ�ꣻ�����ۼơ���ʽ��������������
 */
function checkView() {
	var checkNum = 0;
	$(".checkItems").each(function () {
		if ($(this).attr('checked')) {
			checkNum++;
		}
	});

	if (checkNum == 0) {
		alert('����ѡ��һ��ָ��ͳ�ƣ�');
		return false;
	} else if ($("#presentation").val() == 2) {
		if (checkNum > 1) {
			alert('���·ݳ���ֻ�ܹ�ѡһ��ָ�꣡');
			return false;
		}
	}
	return true;
}

//��Ϊ��ѡ
function bindCheckFun() {
	$(".checkItems").each(function () {
		$(this).change(function () {
			var checkVal = $(this).val();
			if ($(this).attr("checked")) {
				$(".checkItems").each(function () {
					if ($(this).val() != checkVal) {
						$(this).attr("checked" ,false);
					}
				});
			}
		});
	});
}

//�ָ���ѡ
function unbindCheckFun() {
	$(".checkItems").each(function () {
		$(this).unbind("change");
	});
}