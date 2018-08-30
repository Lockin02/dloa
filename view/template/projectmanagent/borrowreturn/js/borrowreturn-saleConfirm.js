$(function() {
	//若存在修改原因，则显示
	if($('#editReason').html() != ''){
		$('#editReason').parents('tr:first').show();
	}
	//若存在打回原因，则显示
	if($('#backReason').html() != ''){
		$('#backReason').parents('tr:first').show();
	}
	// 产品清单
	$("#productInfo").yxeditgrid({
		url:'?model=projectmanagent_borrowreturn_borrowreturnequ&action=listJson',
		tableClass : 'form_in_table',
		type:'view',
		title : '物料清单',
		param:{
        	'returnId' : $("#id").val()
        },
        event : {
			reloadData : function(event, g) {
				g.getCmpByCol('serialId').each(function(i){
					if($(this).val() != ""){
						$.post(
							"?model=stock_serialno_serialno&action=checkTemp",
							{"ids" : $(this).val()},
							function(rs){
								if(rs && rs != ""){
									var serialNameObj = g.getCmpByRowAndCol(i,'serialName');
									var showArr = serialNameObj.val().split(',');
									var tempArr = eval("(" + rs + ")");
									var temp3Arr = [];
									for(var k = 0;k < tempArr.length;k++){
										temp3Arr.push(tempArr[k].sequence);
									}
									var formalArr = []; //正常归还序列号
									var temp2Arr = [];
									for(var j = 0;j< showArr.length;j++){
										if(jQuery.inArray(showArr[j],temp3Arr) == -1){
											formalArr.push(showArr[j]);
										}else{
											temp2Arr.push("<span class='red'>" + showArr[j] + "</span>");
										}
									}
                                    var showStr = formalArr.toString();
									if(showStr !="" && temp2Arr.length > 0){
										showStr+= ','+temp2Arr.toString();
									}else{
										showStr+= temp2Arr.toString();
									}
									serialNameObj.html(showStr);
								}
							}
						);
					}
				});
			}
		},
		colModel : [{
			display : '物料编号',
			name : 'productNo',
			width : 80
		},{
			display : '物料名称',
			name : 'productName'
		}, {
			display : '申请<br/>归还数量',
			name : 'number',
			width : 60
		}, {
			display : '申请<br/>质检数量',
			name : 'qualityNum',
			width : 60
		}, {
			display : '合格数量',
			name : 'qPassNum',
			width : 60
		}, {
			display : '不合格数量',
			name : 'qBackNum',
			width : 60
		}, {
			display : '已下达<br/>归还数量',
			name : 'disposeNumber',
			width : 60
		}, {
			display : '已下达<br/>出库数量',
			name : 'outNum',
			width : 60
		}, {
			display : '已下达<br/>赔偿数量',
			name : 'compensateNum',
			width : 60
		}, {
			name : 'serialId',
			display : 'serialId',
			type : 'hidden'
		}, {
			name : 'serialName',
			display : '序列号'
		}],
		isAddOneRow:false,
		isAdd : false
	});
});