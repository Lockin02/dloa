$(function() {
	//�ӱ�ֿ���Ⱦ
	temp = $('#rowNum').val();
	for(var i=1;i<=temp;i++){
	$("#stockName"+ i).yxcombogrid_stockinfo({
				hiddenId : 'stockId'+i,
				nameCol : 'stockName',
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
	//����ֿ���Ⱦ
	$("#stockName").yxcombogrid_stockinfo({
		hiddenId : 'stockId',
		nameCol : 'stockName',
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

//
////�ͻ���ͻ���ϵ������
//$(function() {
//	$("#customerName").yxcombogrid_customer({
//		hiddenId : 'customerId',
//		gridOptions : {
//			showcheckbox : false,
//			// param :{"contid":$('#contractId').val()},
//			event : {
//				'row_dblclick' : function(e, row, data) {
//					$("#customerId").val(data.id);
//				}
//			}
//		}
//	});
//});

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
function issuedFun(){
	document.getElementById('form1').action="?model=stock_outplan_outplan&action=add&issued=true&msg=�´�ɹ�";
}

function checkThis( obj ){
	if( $('#number'+obj).val()*1 > $('#contRemain' + obj).val()*1 ){
		alert( '�˴η�������������ͬʣ�����������������룡' );
		$('#number' + obj).val('');
		$('#number' + obj+ '_v').val('');
	}
}

$(function(){
	changeDate();
});
