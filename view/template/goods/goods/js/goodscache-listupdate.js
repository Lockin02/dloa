$(document).ready(function() {

});

//���²�Ʒ��������
function updateFun(){
	$.ajax({
	    type: "POST",
	    url: "?model=goods_goods_goodscache&action=updateCache",
	    async: false,
	    success: function(data){
			if(data){
				alert('���³ɹ�');
			}else{
				alert('����ʧ��');
			}
		}
	});
}