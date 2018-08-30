/**
 * Ϊ����Firefox ��дJS toFixed 
 * @param s
 * @return
 */
Number.prototype.toFixed = function(s)
{
    return (parseInt(this * Math.pow( 10, s ) + 0.5)/ Math.pow( 10, s )).toString();
}
/**
 * �ύ��ǰ����
 * @param types
 * @return
 */
function submit_check(types) {
	if (types == 'add') {
		var inputs = $('input[class=my_fraction]').get();
		var textareas = $('textarea[class=my_remark]').get();
		for ( var i = 0; i < inputs.length; i++) {
			if (inputs[i].value == '') {
				alert('���������Ϊ�գ�');
				inputs[i].focus('');
				return false;
			} else if (Number(inputs[i].value) >= 8.5) {
				if (textareas[i].value == '') {
					alert('�����ִﵽ8.5�����ϣ�����д����˵����');
					textareas[i].focus();
					return false;
				}
			}
		}
		$('input[type=submit]').eq(0).attr('disabled', true);
		$('input[type=submit]').eq(0).val('�����ύ����...');
	} else if (types == 'assess') {
		var inputs = $('input[class=assess_fraction]').get();
		for (var i=0;i<inputs.length;i++)
		{
			if (inputs[i].value== '') {
				alert('ÿ��˷���������д��');
				inputs[i].focus();
				return false;
			}
		}
		
		var texts = $('textarea[name=assess_opinion]').eq(0);
		if(texts.val() == '')
		{
			alert('����д�����������');
			texts.focus();
			return false;
		}
		$('input[type=submit]').eq(0).attr('disabled', true);
		$('input[type=submit]').eq(0).val('�����ύ����...');
	} else if (types == 'audit') {
		var inputs = $('input[class=audit_fraction]').get();
		for (var i=0;i<inputs.length;i++)
		{
			if (inputs[i].value == '') {
				alert('ÿ����˷���������д��');
				inputs[i].focus();
				return false;
			}
		}
		$('input[type=submit]').eq(0).attr('disabled', true);
		$('input[type=submit]').eq(0).val('�����ύ����...');
	} else if (types == 'opinion') {
			var texts = $('textarea').get();
			for (var i=0;i<texts.length;i++)
			{
				if (texts[i].name.indexOf('opinion')!=-1)
				{
					if (texts[i].value == '')
					{
						alert('������ݲ���Ϊ�գ�');
						return false;
					}
				}
			}
			$('input[type=submit]').eq(0).attr('disabled', true);
			$('input[type=submit]').eq(0).val('�����ύ����...');
	} else if (types == 'evaluate') {
		var inputs = $('input[class=evaluate_fraction]').get();
		var texts = $('textarea[class=evaluate_remark]').get();
		var eva_num = 0;
		for ( var i = 0; i < inputs.length; i++) {
			if (inputs[i].value!='') eva_num++;
			/*if (inputs[i].value == '') {
				alert('ÿ���������������д��');
				inputs[i].focus();
				return false;
			}

			if (texts[i].value == '') {
				alert('ÿ��������������Ϊ�գ�');
				texts[i].focus();
				return false;
			}*/
		}
		if (eva_num == 0)
		{
			alert('����Ҫ����һ��ſ����ύ��');
			return false;
		}
		$('input[type=submit]').eq(0).attr('disabled', true);
		$('input[type=submit]').eq(0).val('�����ύ����...');
	}
}
/**
 * ɾ������
 * 
 * @return
 */
function del_file(id) {
	var inputs = $('#file_list input[type=checkbox]:checked').get();
	if (inputs.length < 1) {
		alert('����Ҫѡ��һ�������ſ���ִ�д˲�����');
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
 * ͳ�Ƹ������
 * @param obj
 * @param name
 * @return
 */
function set_count(obj,name) {
	if (typeof obj !== "undefined") {
		if (obj.value && Number(obj.value) < 0 || Number(obj.value) > 10) {
			alert("����������0~10֮�䣬��������룡");
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