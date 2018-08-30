$(document).ready(function(){
	$("#preMilestoneName").yxcombogrid_milestonechange({
		hiddenId : 'changePreId',
		gridOptions : {
			param  : {
				'projectId' : $("#projectId").val(),
				'changeId' : $("#changeId").val()
			},
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#changePreId").val(data.milestoneId)
				}
			}
		}
	});
	/**
	 * 验证信息
	 */
	validate({
		"milestoneName" : {
			required : true,
			length : [0,100]
		},
		"planBeginDate" : {
			required : true,
			custom : ['date']
		},
		"planEndDate" : {
			required : true,
			custom : ['date']
		}
	});
})

//开始与结束完成差验证
function timeCheck($t){
	var s = plusDateInfo('planBeginDate','planEndDate');
	if(s <= 0) {
		alert("计划开始时间不能比接话完成时间晚！");
		$t.value = "";
		return false;
	}
}

//实际开始和实际结束比较
function actTimeCheck($t){
	var s = plusDateInfo('actBeginDate','actEndDate');
	if(s <= 0) {
		alert("实际开始日期不能比实际结束日期晚！");
		$t.value = "";
		return false;
	}
}