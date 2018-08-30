$(document).ready(function(){
	$("#preMilestoneName").yxcombogrid_milestone({
		hiddenId : 'preMilestoneId',
		gridOptions : {
			param  : {'projectId' : $("#projectId").val()}
		}
	});

	//对时间过滤
	if(actBeginDate=="0000-00-00"){
		$("#actBeginDate").val("");
	};
	if(actEndDate=="0000-00-00"){
		$("#actEndDate").val("");
	}

	/**
	 * 验证信息
	 */
	validate({
		"milestoneName" : {
			required : true,
			length : [0,100]
		},
		"versionNo" : {
			custom : ['onlyNumber']
		},
		"planBeginDate" : {
			required : true,
			custom : ['date']
		},
		"planEndDate" : {
			required : true,
			custom : ['date']
		},
		"actBeginDate" : {
			required : false,
			custom : ['date']
		},
		"actEndDate" : {
			required : false,
			custom : ['date']
		}
	});
});

//开始与结束完成差验证
function timeCheck($t){
	var s = plusDateInfo('planBeginDate','planEndDate');
	if(s <= 0) {
		alert("开始时间不能比完成时间晚！");
		$t.value = "";
		return false;
	}
}

//开始与结束关闭差验证
function actTimeCheck($t){
	var s = plusDateInfo('actBeginDate','actEndDate');
	if(s <= 0) {
		alert("开始时间不能比结束时间晚！");
		$t.value = "";
		return false;
	}
}