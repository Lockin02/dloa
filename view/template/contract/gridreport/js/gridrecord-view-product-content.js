$(function () {
	$("#presentation").change(function () {
		if ($(this).val() == 1) {
			presentation = 1;
			unbindCheckFun();
		} else {
			//清空选择(保留第一个选中)
			var firstCheck = true;
			$(".checkItems").each(function () {
				if ($(this).attr("checked")) {
					if (firstCheck) {
						firstCheck = false;
						return true; //跳出循环
					}
					$(this).attr("checked" ,false);
				}
			});
			presentation = 2;
			bindCheckFun(); //绑定改为单选的事件
		}
	});
	
	// 更改指标类型时重新加载
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

//保存用户勾选记录
function saveGridRecord() {
	//检查数据的有效性
	if (!checkData()) {
		return false;
	}

	var recordData = {};
	//获取指标名称
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
				alert('保存成功！');
			} else {
				alert('保存失败！');
			}
		}
	});
}

//检查数据的有效性
function checkData() {
	//检查时间区间的有效性
	if ($("#presentation").val() == 2) {
		if (!checkTimeInterval()) {
			return false;
		}
	}

	//检查呈现视图的有效性
	if (!checkView()) {
		return false;
	}

	return true;
}

//检查时间区间的有效性
function checkTimeInterval() {
	var startMonth = $("#startMonth").val();
	var endMonth = $("#endMonth").val();
	if (startMonth > endMonth || startMonth == '' || endMonth == '') {
		alert('时间区间有误！');
		return false;
	}
	return true;
}

/**视图的显示方式：根据用户勾选值来显示；
 * 如呈现方式选择为“分月”，由于受视图限制该情况下只允许勾选1种指标；而“累计”方式，不做数量限制
 */
function checkView() {
	var checkNum = 0;
	$(".checkItems").each(function () {
		if ($(this).attr('checked')) {
			checkNum++;
		}
	});

	if (checkNum == 0) {
		alert('至少选择一种指标统计！');
		return false;
	} else if ($("#presentation").val() == 2) {
		if (checkNum > 1) {
			alert('按月份呈现只能勾选一种指标！');
			return false;
		}
	}
	return true;
}

//改为单选
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

//恢复多选
function unbindCheckFun() {
	$(".checkItems").each(function () {
		$(this).unbind("change");
	});
}