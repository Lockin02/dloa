var colModel;
var parentColName;
var presentation; //��ʾ��ʽ
$(function () {
	$("#showHideBtn").toggle(
		function () {
			$("#recordDiv").show('slow' ,function () {
				$("#showHideBtn").attr("title" ,"���ز˵�");
				$("#showHideBtn img").attr("src" ,"images/icon/icon003.gif");
			});
		},
		function () {
			$("#recordDiv").hide('slow' ,function () {
				$("#showHideBtn").attr("title" ,"��ʾ�˵�");
				$("#showHideBtn img").attr("src" ,"images/icon/icon001.gif");
			});
		}
	);

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
    $("#unit option").each(function () {
        if ($(this).val() == $("#unit").attr("val")) {
            $(this).attr("selected" ,true);
        }
    });
	$("#presentation").trigger("change");

	reloadGrid(); //������ͼ
});

//���ر��
function reloadGrid() {
    //��λ��ʾ
    if($("#unit").val() == '2'){
        $("#unitView").html("(��λ����Ԫ)");
    }else{
        $("#unitView").html("(��λ��Ԫ)");
    }
	//������ݵ���Ч��
	if (!checkData()) {
		return false;
	}

//	gridRecord(); //�û���ѡ��¼����

	colModel = [];
	parentColName = []; //����ָ����������
	var keyName = []; //����
	var parentCode = []; //����������±�
	$(".checkItems").each(function () {
		if ($(this).attr("checked")) {
			parentColName.push($(this).attr('val'));
			keyName.push($(this).val());
		}
	});

	//��ʼ��
	var fixedThead = eval("(" + $("#fixedThead").text() + ")");

	for (var i = 0 ;i < fixedThead.length ;i++) {
		//���ÿ��
		if (parseInt(fixedThead[i].width) == fixedThead[i].width) {
			var width = parseInt(fixedThead[i].width);
		} else {
			var width = fixedThead[i].width ? fixedThead[i].width : '100px';
		}

		colModel.push({
			name : fixedThead[i].name,
			display : fixedThead[i].display,
			width : width,
            align: 'left'
		});
		parentCode.push(fixedThead[i].name);
		parentCode.push(fixedThead[i].code);
	}

	for (var i = 0 ;i < parentColName.length ;i++) {
		colModel.push({
			name : keyName[i] + 'tar',
			display : 'Ŀ��',
			width : '120px',
			parentCol : i,
			align : 'right',
			process : function (v) {
				if (!isNaN(v)) {
					return moneyFormat2(v); //���ǧ��λ
				} else  {
					if(!v)
						return "-";
					else
						return v;
				}
			}
		});
		colModel.push({
			name : keyName[i] + 'imp',
			display : 'ʵ��',
			width : '120px',
			parentCol : i,
			align : 'right',
			process : function (v) {
				if (!isNaN(v)) {
					return moneyFormat2(v); //���ǧ��λ
				} else  {
					if(!v)
						return "0.00";
					else
						return v;
				}
			}
		});

		//������ʾ
		if (presentation == 2) {
			var monthArr = getMonthArr($(startMonth).val() ,$(endMonth).val());
			for (var j = 0 ;j < monthArr.length ;j++) {
				colModel.push({
					name : keyName[i] + j,
					display : monthArr[j] + '��',
					width : '120px',
					parentCol : i,
					align : 'right',
					process : function (v) {
						if (!isNaN(v)) {
							return moneyFormat2(v); //���ǧ��λ
						} else  {
							return v;
						}
					}
				});
			}
		}
	}

	var colCode = []; //�����±�
	for (var i = 0 ;i < colModel.length ;i++) {
		colCode.push(colModel[i].name);
	}
	var colCodeStr = colCode.toString();
	var parentCodeStr = parentCode.toString() + ',' + keyName.toString();
	$("#productGrid").empty().yxeditgrid({
		url : $("#tableUrl").text(),
		param : {
			objCode : $("#objCode").val(),
			startMonth : $("#startMonth").val(),
			endMonth : $("#endMonth").val(),
			presentation : $("#presentation").val(),
            unit : $("#unit").val(),
			colCode : colCodeStr,
			parentCode : parentCodeStr
		},
		type : 'view',
		colModel : colModel
	});

	tableHead('productGrid' ,colModel);

    //��ȡ�����ڵ����汾��
    $.ajax({
        type : 'POST',
        url : '?model=contract_conproject_conproject&action=getMaxNum',
        data: {'endMonth': $("#endMonth").val()},
        async: false,
        success: function (data) {
            $("#version").html(" �汾�� V"+data);
        }
    });
}

//���������·ݷ��ذ������·�����
function getMonthArr(startMonth ,endMonth) {
	var startMonthObj = new Date(startMonth.substr(0 ,4) ,startMonth.substr(5 ,2));
	var endMonthObj = new Date(endMonth.substr(0 ,4) ,endMonth.substr(5 ,2));
	var yearStart = startMonthObj.getFullYear();
	var yearEnd = endMonthObj.getFullYear();
	var monthStart = startMonthObj.getMonth();
	var monthEnd = endMonthObj.getMonth();
	var monthArr = [];
	if (yearStart < yearEnd) {
		var isFirst = true;
		var tmpYear = yearStart;
		var tmpMonth = monthStart;
		for (var i = tmpYear ;i <= yearEnd ;i++) {
			if (isFirst) { //����
				for (var j = tmpMonth ;j <= 12 ;j++) {
					monthArr.push(j);
				}
				isFirst = false;
			} else if (i != yearEnd) { //�м���
				for (var j = 1 ;j <= 12 ;j++) {
					monthArr.push(j);
				}
			} else { //ĩ��
				for (var j = 1 ;j <= monthEnd ;j++) {
					monthArr.push(j);
				}
			}
		}
	} else {
		for (var i = monthStart ;i <= monthEnd ;i++) {
			monthArr.push(i);
		}
	}

	return monthArr;
}

//����Excel���
function excelOut() {
	var colCode = []; //�����±�
	var parentCode = []; //����������±�
	var keyName = []; //ѡ�и��ϱ��������±�

	//��ʼ��
	var fixedThead = eval("(" + $("#fixedThead").text() + ")");
	for (var i = 0 ;i < fixedThead.length ;i++) {
		parentCode.push(fixedThead[i].name);
		parentCode.push(fixedThead[i].code);
	}
	$(".checkItems").each(function () {
		if ($(this).attr("checked")) {
			keyName.push($(this).val());
		}
	});
	var parentCodeStr = parentCode.toString() + ',' + keyName.toString();

	for (var i = 0 ;i < colModel.length ;i++) {
		colCode.push(colModel[i].name);
	}
	var colCodeStr = colCode.toString();

	var colCodeStrHtml = '<input type="hidden" name=colCode value="' + colCodeStr + '"/>';
	var parentCodeStrHtml = '<input type="hidden" name=parentCode value="' + parentCodeStr + '"/>';
	var colModelHtml = '<input type="hidden" name=colModel value=\'' + JSON.stringify(colModel) + '\'/>';
	var parentColNameHtml = '<input type="hidden" name=parentColName value="' + parentColName + '"/>';
	var objCodeHtml = '<input type="hidden" name=objCode value="' + $("#objCode").val() + '"/>';
	var startMonthHtml = '<input type="hidden" name=startMonth value="' + $("#startMonth").val() + '"/>'; //��ʼ�·�
	var endMonthHtml = '<input type="hidden" name=endMonth value="' + $("#endMonth").val() + '"/>'; //�����·�
	var presentationHtml = '<input type="hidden" name=presentation value="' + $("#presentation").val() + '"/>'; //��ʾ��ʽ
	$("#form1").append(colCodeStrHtml)
			.append(parentCodeStrHtml)
			.append(colModelHtml)
			.append(parentColNameHtml)
			.append(objCodeHtml)
			.append(startMonthHtml)
			.append(endMonthHtml)
			.append(presentationHtml)
			.submit();
}

//�����û���ѡ��¼
function gridRecord() {
	var recordData = {};
	//��ȡָ������
	$(".checkItems").each(function () {
		recordData[$(this).val()] = $(this).attr('checked') ? 1 : 0
	});

	recordData["startMonth"] = $("#startMonth").val();
	recordData["endMonth"] = $("#endMonth").val();
	recordData["presentation"] = $("#presentation").val();
	recordData["objCode"] = $("#objCode").val();

	$.ajax({
		type : 'POST',
		url : '?model=contract_gridreport_gridrecord&action=saveRecord',
		data : recordData
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