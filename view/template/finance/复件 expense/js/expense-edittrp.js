$(function() {
	//����title
	initAmountTitle($("#feeRegular").val(),$("#feeSubsidy").val());

	//��Ⱦͬ����
	$("#memberNames").yxselect_user({
		hiddenId : 'memberIds',
		formCode : 'expenseMember',
		mode : 'check',
		event : {
			"select" : function(obj, row) {
				if(row != undefined){
					if(row.val != ''){
						var memberArr = row.val.split(',');
						$("#memberNumber").val(memberArr.length);
					}else{
						$("#memberNumber").val('');
					}
				}
			},
			"clearReturn" : function(){
				$("#memberNumber").val('');
			}
		}
	});

	//��Ⱦ���ù�������
//	initTrpDept();

	//ʵ�������ù�������
	var detailType = $("#DetailTypeHidden").val();
	initDept(detailType);
});