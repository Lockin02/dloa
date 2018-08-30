//仓库combogrid
$(function(){
	//主表仓库渲染
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
					var itemscount = $('#itemscount').val();
					for(var i=0;i<itemscount;i++){
						if(!$('#stockName' + i).val()){
							$('#stockId' + i).val(data.id);
							$('#stockCode' + i).val(data.stockCode);
							$('#stockName' + i).val(data.stockName);

							reloadItemInventory(i);
						}
					}
				}
			}
		}
	});
});

$(document).ready(function(){
	//人员选择组件--经办人
    $("#dealUserName").yxselect_user({
		hiddenId : 'dealUserId'
	});
	//单据日期
	$("#docDate").val(formatDate(new Date()));
});