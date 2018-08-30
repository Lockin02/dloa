$(function () {

	//离职原因选项匹配
	var reason = $("#quitResonOld").val();
	if (reason.indexOf("对薪酬福利不满意^nbsp") > -1) {
		$("input[name='leave[checkbox][0]']").attr("checked", true);
	}
	if (reason.indexOf("对公司晋升渠道（发展空间）不满^nbsp") > -1) {
		$("input[name='leave[checkbox][1]']").attr("checked", true);
	}
	if (reason.indexOf("对企业文化不满意^nbsp") > -1) {
		$("input[name='leave[checkbox][2]']").attr("checked", true);
	}
	if (reason.indexOf("对管理方式不满意^nbsp") > -1) {
		$("input[name='leave[checkbox][3]']").attr("checked", true);
	}
	if (reason.indexOf("对所在团队的工作能力或氛围不满意^nbsp") > -1) {
		$("input[name='leave[checkbox][4]']").attr("checked", true);
	}
	if (reason.indexOf("对同事不满意（不好相处）^nbsp") > -1) {
		$("input[name='leave[checkbox][5]']").attr("checked", true);
	}
	if (reason.indexOf("对上级领导不满意^nbsp") > -1) {
		$("input[name='leave[checkbox][6]']").attr("checked", true);
	}
	if (reason.indexOf("个人能力不胜任现岗位^nbsp") > -1) {
		$("input[name='leave[checkbox][7]']").attr("checked", true);
	}
	if (reason.indexOf("与个人职业发展方向或兴趣爱好不符^nbsp") > -1) {
		$("input[name='leave[checkbox][8]']").attr("checked", true);
	}
	if (reason.indexOf("个人能力发挥受限制^nbsp") > -1) {
		$("input[name='leave[checkbox][9]']").attr("checked", true);
	}
	if (reason.indexOf("自身能力不能很好地提升^nbsp") > -1) {
		$("input[name='leave[checkbox][10]']").attr("checked", true);
	}
	if (reason.indexOf("家庭环境缘故^nbsp") > -1) {
		$("input[name='leave[checkbox][11]']").attr("checked", true);
	}
	if (reason.indexOf("身体原因^nbsp") > -1) {
		$("input[name='leave[checkbox][12]']").attr("checked", true);
	}
	if (reason.indexOf("工作太辛苦^nbsp") > -1) {
		$("input[name='leave[checkbox][13]']").attr("checked", true);
	}
	if (reason.indexOf("工作压力太大^nbsp") > -1) {
		$("input[name='leave[checkbox][14]']").attr("checked", true);
	}
	if (reason.indexOf("学习进修或创业^nbsp") > -1) {
		$("input[name='leave[checkbox][15]']").attr("checked", true);
	}
	if (reason.indexOf("合同期满公司不续^nbsp") > -1) {
		$("input[name='leave[checkbox][16]']").attr("checked", true);
	}
	if (reason.indexOf("其它^nbsp") > -1) {
		$("input[name='leave[checkbox][17]']").attr("checked", true).trigger('change');
		$("#comOther").text(reason.substring((reason.indexOf("其它^nbsp") + 7)));
	}

});

function sub() {
	var str = '';
	$("input[name^='leave[checkbox]']").each(function () {
		if ($(this).attr("checked")) {
			str += $(this).val() + ",";
			return false; //跳出循环
		}
	});

	if (str == "") {
		alert("请选择离职原因！");
		return false;
	}

	if (!$("#comOther").hasClass('validate[required]')) {
		$("#comOther").val('');
	}

	if (!$("#projectManager").val()) {
		if (confirm("项目经理栏目为空，是否继续？")) {
			return true;
		} else {
			return false;
		}
	}
}

//直接提交
function toSubmit() {
	document.getElementById('form1').action = "?model=hr_leave_leave&action=staffEdit&actType=staff";
}