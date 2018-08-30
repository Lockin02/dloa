
$(function() {
	//从表仓库渲染
	temp = $('#rowNum').val();
	for(var i=1;i<=temp;i++){
	$("#stockName"+ i).yxcombogrid_stockinfo({
				hiddenId : 'stockId'+i,
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(i){
						return function(e, row, data) {
							$('#stockId' + i).val(data.id);
							$('#stockCode' + i).val(data.stockCode);
							$('#stockName' + i).val(data.stockName);
						}
					}(i)
				}
			}
		});
	}

});
$(function(){
	//主表仓库渲染
	$("#stockName").yxcombogrid_stockinfo({
		hiddenId : 'stockId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$('#stockId').val(data.id);
					$('#stockCode').val(data.stockCode);
					$('#stockName').val(data.stockName);
					temp = $('#rowNum').val();
					for(var i=1;i<=temp;i++){
						if($('#stockName' + i).val() == ""){
							$('#stockId' + i).val(data.id);
							$('#stockCode' + i).val(data.stockCode);
							$('#stockName' + i).val(data.stockName);
						}
					}
				}
			}
		}
	});
});

$(function(){
	$("#shipPlanDate").val(formatDate(new Date()));
	if( $('#pageAction').val() != 'feedback' ){
		$('tr.Feedback').hide();
	}
})


function changeDate(){
		$.ajax({
			type : 'POST',
			url : '?model=stock_outplan_outplan&action=week',
			data : {
				date : $('#shipPlanDate').val()
			},
			success : function(data) {
				$('#week').val(data)
			}
		});
}


function checkThis( obj ){
	if( $('#number'+obj).val()*1 > $('#contRemain' + obj).val()*1 ){
		alert( '此次发货数量超过合同剩余数量！请重新输入！' );
		$('#number' + obj).val('');
		$('#number' + obj+ '_v').val('');
	}
}
function issuedFun(){
	document.getElementById('form1').action="?model=stock_outplan_outplan&action=edit&issued=true&msg=下达成功";
}