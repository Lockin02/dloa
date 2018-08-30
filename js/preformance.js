/**
 * 为兼容Firefox 重写JS toFixed 
 * @param s
 * @return
 */
Number.prototype.toFixed = function(s)
{
    return (parseInt(this * Math.pow( 10, s ) + 0.5)/ Math.pow( 10, s )).toString();
}
/**
 * 提交表单前检验
 * @param types
 * @return
 */
function submit_check(types) {
	if (types == 'add') {
		var inputs = $('input[class=my_fraction]').get();
		var textareas = $('textarea[class=my_remark]').get();
		for ( var i = 0; i < inputs.length; i++) {
			if (inputs[i].value == '') {
				alert('自评分项不能为空！');
				inputs[i].focus('');
				return false;
			} else if (Number(inputs[i].value) >= 8.5) {
				if (textareas[i].value == '') {
					alert('自评分达到8.5分以上，请填写自评说明！');
					textareas[i].focus();
					return false;
				}
			}
		}
		$('input[type=submit]').eq(0).attr('disabled', true);
		$('input[type=submit]').eq(0).val('正在提交数据...');
	} else if (types == 'assess') {
		var inputs = $('input[class=assess_fraction]').get();
		for (var i=0;i<inputs.length;i++)
		{
			if (inputs[i].value== '') {
				alert('每项考核分数必须填写！');
				inputs[i].focus();
				return false;
			}
		}
		
		var texts = $('textarea[name=assess_opinion]').eq(0);
		if(texts.val() == '')
		{
			alert('请填写考核人意见！');
			texts.focus();
			return false;
		}
		$('input[type=submit]').eq(0).attr('disabled', true);
		$('input[type=submit]').eq(0).val('正在提交数据...');
	} else if (types == 'audit') {
		var inputs = $('input[class=audit_fraction]').get();
		for (var i=0;i<inputs.length;i++)
		{
			if (inputs[i].value == '') {
				alert('每项审核分数必须填写！');
				inputs[i].focus();
				return false;
			}
		}
		$('input[type=submit]').eq(0).attr('disabled', true);
		$('input[type=submit]').eq(0).val('正在提交数据...');
	} else if (types == 'opinion') {
			var texts = $('textarea').get();
			for (var i=0;i<texts.length;i++)
			{
				if (texts[i].name.indexOf('opinion')!=-1)
				{
					if (texts[i].value == '')
					{
						alert('意见内容不能为空！');
						return false;
					}
				}
			}
			$('input[type=submit]').eq(0).attr('disabled', true);
			$('input[type=submit]').eq(0).val('正在提交数据...');
	} else if (types == 'evaluate') {
		var inputs = $('input[class=evaluate_fraction]').get();
		var texts = $('textarea[class=evaluate_remark]').get();
		var eva_num = 0;
		for ( var i = 0; i < inputs.length; i++) {
			if (inputs[i].value!='') eva_num++;
			/*if (inputs[i].value == '') {
				alert('每项评介分数必须填写！');
				inputs[i].focus();
				return false;
			}

			if (texts[i].value == '') {
				alert('每项评价描述不能为空！');
				texts[i].focus();
				return false;
			}*/
		}
		if (eva_num == 0)
		{
			alert('至少要评价一项才可以提交。');
			return false;
		}
		$('input[type=submit]').eq(0).attr('disabled', true);
		$('input[type=submit]').eq(0).val('正在提交数据...');
	}
}
/**
 * 删除附件
 * 
 * @return
 */
function del_file(id) {
	var inputs = $('#file_list input[type=checkbox]:checked').get();
	if (inputs.length < 1) {
		alert('至少要选择一个附件才可以执行此操作！');
		return false;
	} else {
		var file_name = '';
		for ( var i = 0; i < inputs.length; i++) {
			file_name += inputs[i].value + ',';
		}
		$
				.post(
						'?model=administration_appraisal_performance_list&action=del_file&id=' + id,
						{
							file_name : file_name
						}, function(data) {

							$('#file_list').html(data);

						});
	}
}
/**
 * 统计各项分数
 * @param obj
 * @param name
 * @return
 */
function set_count(obj,name) {
	if (typeof obj !== "undefined") {
		if (obj.value && Number(obj.value) < 0 || Number(obj.value) > 10) {
			alert("分数必须是0~10之间，请从新输入！");
			obj.value = "";
			obj.focus();
			return false;
		}
	}
	var cls = document.getElementsByTagName("span");
	var j = 0, count_percentage = 0, percentage = [];
	for ( var i = 0; i < cls.length; i++) {
		if (cls[i].className == "percentage") {
			percentage[j] = Number(cls[i].innerHTML.replace(/%/g, ""));
			count_percentage = (count_percentage + percentage[j]);
			j++;
		}
	}
	var options = $("."+name).get();
	var count = 0;
	var average = 0;
	for ( var i = 0; i < options.length; i++) {
		if (options[i].value != "") {
			var temp = ((percentage[i]) / 100) * Number(options[i].value);
			count = (count + Number(temp.toString().replace('000000000001','')));
		}
	}
	;
	average = (10 * count);
	average = average.toFixed(2).toString();
	count = count.toFixed(3).toString();
	document.getElementById("show_count_"+name).innerHTML = count;//.substring(0, count.indexOf(".") + 5);
	document.getElementById("count_"+name).value = count;
	if (name != 'my_fraction' && name!='evaluate_fraction')
	{
		document.getElementById("show_average_"+name).innerHTML = average;
		document.getElementById("average_"+name+"_value").value = average;
	}
}