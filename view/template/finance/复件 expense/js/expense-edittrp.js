$(function() {
	//设置title
	initAmountTitle($("#feeRegular").val(),$("#feeSubsidy").val());

	//渲染同行人
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

	//渲染费用归属部门
//	initTrpDept();

	//实例化费用归属部门
	var detailType = $("#DetailTypeHidden").val();
	initDept(detailType);
});