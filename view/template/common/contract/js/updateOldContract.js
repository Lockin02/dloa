function update(objType,contractType) {
	$("#msg").html("���ڸ�����,������������϶�,��ռ��һ����ʱ��...");
	$.ajax({
		url : '?model=common_contract_allsource&action=updateOldcontract&objType='
				+ objType+"&contractType="+contractType,
		success : function(data) {
			if (data == 1) {
				alert('���³ɹ�.');
				$("#msg").html('���³ɹ�.��رմ��ں�����ˢ�����ݲ鿴.');
			} else {
				alert('�������,����ʧ��.ʧ��ԭ��:' + data);
				$("#msg").html('����ʧ��.ʧ��ԭ��:' + data);
			}

		}
	});
}
