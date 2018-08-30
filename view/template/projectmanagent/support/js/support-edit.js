//下载项
$(function() {

	// 支持身份
	$("#signSubjectName").yxcombogrid_branch({
		hiddenId : 'signSubject',
		width : 300,
		gridOptions : {
			showcheckbox : false
		}
	});
    //建议交流人员
	$("#exchangeName").yxselect_user({
						hiddenId : 'exchangeId'
//						isGetDept:[true,"depId","depName"]
					});
})

$(function() {
	/**
	 * 验证信息
	 */
	validate({
		"signSubjectName" : {
			required : true
		},
		"recentExDate" : {
			required : true
		},
		"exDate" : {
			required : true
		},
		"exchangeName" : {
			required : true
		},
		"linkman" : {
			required : true
		},
		"contact" : {
			required : true
		},
		"AClocation" : {
			required : true
		}
	});
});
 //开始时间与结束时间差验证
function timeCheck($t){
	var s = plusDateInfo('beginDate','closeDate');
	if(s < 0) {
		alert("开始时间不能比结束时间晚！");
		$t.value = "";
		return false;
	}
}