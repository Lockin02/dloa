
// ����ȷ���޸�
$(function() {
	equConfig.type = "change";
	var rowNum = $('#rowNum').val();
	for (var i = 1; i <= rowNum; i++) {
		getGoodsProducts(i);
	}

	var newEquRowNum = rowNum * 1 + 1;
	getNoGoodsProducts(newEquRowNum);
});

function confirmSubmit(){
	if(confirm("һ��ȷ��,����������ݽ�����,�Ƿ�ȷ�ϱ��?")){
		$("#form1").submit();
	}
}