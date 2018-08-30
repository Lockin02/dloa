function ajax_check(num)
{
	var msg = '';
	switch (num)
	{
		case '0':
		msg = '对不起，你无权执行此操作！';
		break
		case '-1':
		msg = '对不起，你尚未登录！';
		break
		case '-2':
		msg = '请求MODEL名不能为空！';
		break
		case '-3':
		msg = '请求函数方法名不能为空！';
		break
		case '-4':
		msg = '请求的函数方法不存在！';
		break
		case '-5':
		msg = '您请求的MODEL类文件不存在！'
		break
		default:
		return true;
	}
	if (msg=='')
	{
		return true;
	}else{
		alert(msg);
		return false;
	}
}