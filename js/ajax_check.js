function ajax_check(num)
{
	var msg = '';
	switch (num)
	{
		case '0':
		msg = '�Բ�������Ȩִ�д˲�����';
		break
		case '-1':
		msg = '�Բ�������δ��¼��';
		break
		case '-2':
		msg = '����MODEL������Ϊ�գ�';
		break
		case '-3':
		msg = '����������������Ϊ�գ�';
		break
		case '-4':
		msg = '����ĺ������������ڣ�';
		break
		case '-5':
		msg = '�������MODEL���ļ������ڣ�'
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