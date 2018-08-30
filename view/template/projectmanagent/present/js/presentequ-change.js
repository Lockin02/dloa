
// 物料确认修改
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
	if(confirm("一旦确认,变更物料数据将更新,是否确认变更?")){
		$("#form1").submit();
	}
}