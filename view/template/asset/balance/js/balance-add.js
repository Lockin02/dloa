$(function() {
	/* 选择资产编号 */
	$("#assetName").yxcombogrid_asset({
		nameCol : 'assetName',
		hiddenId : 'assetId',
		gridOptions : {
			param : {
				"isDel" : "0",
				"isTemp" : "0",
				"isDeprf" : "0"
			},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#assetCode").val(data.assetCode);
					$("#origina").val(data.origina);
					$("#buyDepr").val(data.buyDepr);
					$("#beginTime").val(data.beginTime);
					$("#estimateDay").val(data.estimateDay);
					$("#alreadyDay").val(data.alreadyDay);
					$("#depreciation").val(data.depreciation);
					$("#salvage").val(data.salvage);
					$("#netValue").val(data.netValue);
					$("#deprName").val(data.deprName);
					$("#wirteDate").val(data.wirteDate);
//					$("#isDel").val(data.isDel);
					$("#assetName").val(data.assetName);
					// alert($("#deprName").val());
					// 计算本月折旧率
					// if ($("#deprName").val() == '平均年限法') {
					// 资产共折旧额
					var allDepr = data.origina - data.salvage;
					// 月折旧额
					var m = allDepr / data.estimateDay;
					// 月折旧率
					var rate = m / data.origina;
					// 剩余折旧额
					var remain = allDepr - m - data.depreciation;

					$("#deprRate").val(rate*100);
					$("#deprShould").val(m);

					//判断剩余折旧额为负数时，把剩余折旧额赋值为0，本期计提折旧为能让剩余折旧额为0的值
					if(remain<0){
						$("#deprRemain").val('0');
						$("#depr").val(allDepr - data.depreciation);
					}else{
						$("#deprRemain").val(remain);
						$("#depr").val(m);
					}

					$("#initDepr").val(data.depreciation);
					$("#period").val(data.alreadyDay * 1 + 1);
					// 获取当前折旧年份
					var myDate = new Date();
					var y = myDate.getYear();
					$("#years").val(y);
					// }

					// 清理的资产不能折旧
//					var isD = $("#isDel").val();

					//折旧完的资产不能折旧
					var sal = $("#salvage").val()*1;
					var net = $("#netValue").val()*1;

					// 控制资产入账时间的当月，不能折旧
					var year = new Date().getYear();
					var month = new Date().getMonth() + 1;
					var wirteDate = $("#wirteDate").val();
					var y = wirteDate.substr(0, 4);
					var m = wirteDate.substr(5, 2);
					if (year == y && month == m) {
						alert("资产入账时间为" + wirteDate + ",资产暂时不能折旧！");
						$("#deprBtn").attr("disabled", "disabled");
					}else if(net <= sal){
						alert("该资产已经折旧完！");
						$("#deprBtn").attr("disabled", "disabled");
					}
//					else if(isD == "1"){
//						alert("该资产已被清理！");
//						$("#deprBtn").attr("disabled", "disabled");
//					}
					else {
						// alert("资产可以折旧！");
						$("#deprBtn").removeAttr("disabled");
					}

				}
			}
		}
	});
});

// 跳转到列表页面
function toList() {
//	if ($("#assetCode").val() == "" || $("#assetName").val() == "") {
//		alert("请填好查询条件！");
//		return false;
//	}
	location = '?model=asset_balance_balance&action=page' + '&assetId='
			+ $("#assetId").val();
	return true;
}

function reload(){
	$("#deprBtn").removeAttr("disabled");
}

