function mydel(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
//		var myrows = mytable.rows.length;
//		for (i = 1; i < myrows; i++) {
//			mytable.rows[i].childNodes[0].innerHTML = i;
//		}
	}
}

//ʵ�����ڵ�����
$(function(){
//	$("#dateHope").bind("focusout",function(){
//		var dateHope=$(this).val();
//		$.each($(':input[class^="txtshort datehope"]'),function(i,n){
//			$(this).val(dateHope);
//		})
//	});
});