$(function(){
	//主表仓库渲染
	$("#stockName").yxcombogrid_stock({
		hiddenId : 'stockId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					temp = $('#invnumber').val();
					for(var i=1;i<=temp;i++){
						if($('#dstockName' + i).val() == ""){
							$('#dstockId' + i).val(data.id);
							$('#dstockName' + i).val(data.stockName);
						}
					}
				}
			}
		}
	});
});

$(function() {
	//从表仓库渲染
	temp = $('#invnumber').val();
	for(var i=1;i<=temp;i++){
	$("#dstockName"+i).yxcombogrid_stock({
			hiddenId : 'dstockId'+i,
			gridOptions : {
				showcheckbox : false
			}
		});
	}

});

$(function() {
	//产品渲染
	temp = $('#invnumber').val();
	for(var i=1;i<=temp;i++){
	$("#productName"+i).yxcombogrid_product({
				hiddenId : 'productId'+i,
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(i){
						return function(e, row, data) {
								$("#productNo"+i).val(data.sequence);
								$("#productModel"+i).val(data.pattern);
							}
						}(i)
					}
				}
			});
	}
});

