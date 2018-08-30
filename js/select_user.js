var user_id_list = '';
var user_name_list = '';
/**
 * 按ClassName获取元素
 * @param className
 * @param tagName
 * @return
 */
function getElementsByClassName(className,tagName)
{
	var ele=[],all=document.getElementsByTagName(tagName||"*");
	for(var i=0;i<all.length;i++)
	{
		if(all[i].className==className)
		{
			ele[ele.length]=all[i];
		}
	}
	return ele;
}
/**
 * 显示部门职位
 * @param dept_id
 * @return
 */
function show_dept_jobs(dept_id) {
	try {
		var obj = getElementsByClassName('dept_' + dept_id,'div');
		var dept_img = getElementsByClassName('dept_img_' + dept_id,'img');
		for (var i=0;i<obj.length;i++)
		{
			if (obj[i].style.display == 'none') {
				for(var j=0;j<dept_img.length;j++)
				{
					
					dept_img[j].src = 'images/work/sub.png';
				}
				obj[i].style.display = '';
			} else {
				for(var j=0;j<dept_img.length;j++)
				{
					dept_img[j].src = 'images/work/plus.png';
				}
				obj[i].style.display = 'none';
			}
		}
	} catch (e) {
		// TODO: handle exception
	}
}
/**
 * 显示职位员工
 * @param jobs_id
 * @return
 */
function show_jobs_user(jobs_id) {
	try {
		var obj = getElementsByClassName('jobs_' + jobs_id,'div');
		var jobs_img = getElementsByClassName('jobs_img_' + jobs_id,'img');
		for (var i=0;i<obj.length;i++)
		{
			if (obj[i].style.display == 'none') {
				for(var j=0;j<jobs_img.length;j++)
				{
					
					jobs_img[j].src = 'images/work/sub.png';
				}
				obj[i].style.display = '';
			} else {
				for(var j=0;j<jobs_img.length;j++)
				{
					jobs_img[j].src = 'images/work/plus.png';
				}
				obj[i].style.display = 'none';
			}
		}
	} catch (e) {
		// TODO: handle exception
	}
}
/**
 * 选择部门职位及员工
 * @param dept_id
 * @param checked
 * @return
 */
function select_dept_jobs(dept_id, checked) {
	try {
		var obj = getElementsByClassName('dept_' + dept_id,'div');
		var dept_img = getElementsByClassName('dept_img_' + dept_id,'img');
		for (var i=0;i<obj.length;i++)
		{
			if (checked == true) {
				obj[i].style.display = '';
				for(var j=0;j<dept_img.length;j++)
				{
					
					dept_img[j].src = 'images/work/sub.png';
				}
			}else{
				obj[i].style.display = 'none';
				for(var j=0;j<dept_img.length;j++)
				{
					
					dept_img[j].src = 'images/work/plus.png';
				}
			}
			var inputs = obj[i].getElementsByTagName('INPUT');
			for ( var k = 0; k < inputs.length; k++) {
				inputs[k].checked = checked;
			}
		}
		get_checked_user();
	} catch (e) {
		// TODO: handle exception
	}
}
/**
 * 选种职位所有员工
 * @param jobs_id
 * @param checked
 * @return
 */
function select_jobs_user(jobs_id, checked) {
	try {
		var obj = getElementsByClassName('jobs_' + jobs_id,'div');
		var jobs_img = getElementsByClassName('jobs_img_' + jobs_id,'img');
		for (var i=0;i<obj.length;i++)
		{
			if (checked == true) {
				obj[i].style.display = '';
				for(var j=0;j<jobs_img.length;j++)
				{
					
					jobs_img[j].src = 'images/work/sub.png';
				}
			}else{
				obj[i].style.display = 'none';
				for(var j=0;j<jobs_img.length;j++)
				{
					
					jobs_img[j].src = 'images/work/plus.png';
				}
			}
			var inputs = obj[i].getElementsByTagName('INPUT');
			for ( var k = 0; k < inputs.length; k++) {
				inputs[k].checked = checked;
			}
		}
		get_checked_user();
	} catch (e) {
		// TODO: handle exception
	}
}
/**
 * 获取选种用户
 * @return
 */
function get_checked_user() {
	user_id_list = '';
	user_name_list = '';
	var win = window.opener || parent;
	try {
		var obj;
		try {
			obj = document.getElementById('TB_ajaxContent');

		} catch (e) {
			obj = document;
		}
		inputs = obj.getElementsByTagName('INPUT');
		for ( var i = 0; i < inputs.length; i++) {
			if (inputs[i].name == 'user_id[]' && inputs[i].checked == true) {
				if (user_id_list == '') {
					user_id_list += inputs[i].value;
					user_name_list += inputs[i].title;
				} else {
					user_id_list += ',' + inputs[i].value;
					user_name_list += ',' + inputs[i].title;
				}
			}
		}
	} catch (e) {
		// TODO: handle exception
	}
	
	try {
		win.user_id_list = user_id_list;
	} catch (e) {
		// TODO: handle exception
	}
	
	try {
		win.user_name_list = user_name_list;
	} catch (e) {
		// TODO: handle exception
	}
}