$(function() {
	//�������޸�ԭ������ʾ
	if($('#editReason').html() != ''){
		$('#editReason').parents('tr:first').show();
	}
	//�����ڴ��ԭ������ʾ
	if($('#backReason').html() != ''){
		$('#backReason').parents('tr:first').show();
	}
	// ��Ʒ�嵥
	$("#productInfo").yxeditgrid({
		url:'?model=projectmanagent_borrowreturn_borrowreturnequ&action=listJson',
		tableClass : 'form_in_table',
		type:'view',
		title : '�����嵥',
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
									var formalArr = []; //�����黹���к�
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
			display : '���ϱ��',
			name : 'productNo',
			width : 80
		},{
			display : '��������',
			name : 'productName'
		}, {
			display : '����<br/>�黹����',
			name : 'number',
			width : 60
		}, {
			display : '����<br/>�ʼ�����',
			name : 'qualityNum',
			width : 60
		}, {
			display : '�ϸ�����',
			name : 'qPassNum',
			width : 60
		}, {
			display : '���ϸ�����',
			name : 'qBackNum',
			width : 60
		}, {
			display : '���´�<br/>�黹����',
			name : 'disposeNumber',
			width : 60
		}, {
			display : '���´�<br/>��������',
			name : 'outNum',
			width : 60
		}, {
			display : '���´�<br/>�⳥����',
			name : 'compensateNum',
			width : 60
		}, {
			name : 'serialId',
			display : 'serialId',
			type : 'hidden'
		}, {
			name : 'serialName',
			display : '���к�'
		}],
		isAddOneRow:false,
		isAdd : false
	});
});