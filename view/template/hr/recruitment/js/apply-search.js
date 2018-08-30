$(document).ready(function() {
	//initLevel();

	$("#deptName").yxselect_dept({
		hiddenId : 'deptId',
		event : {
			selectReturn : function(e,row){
				$("#positionName").val("");
				$("#positionId").val("");
				$("#positionName").yxcombogrid_position("remove");
				//职位选择
				$("#positionName").yxcombogrid_position({
					hiddenId : 'positionId',
					width:350,
					gridOptions : {
						param:{deptId:row.dept.id}
					},
					isShowButton : false
				});
				$("#positionName").yxcombogrid_position("show");
			}
		}
	});

	$("#resumeToName").yxselect_user({
		mode:'check'
	});

	$("#formManName").yxselect_user({
	});

	$("#recruitManName").yxselect_user({
	});

	//var deptId=$("#deptId").val();
	$("#positionName").yxcombogrid_position({
		hiddenId : 'positionId',
		width:350,
		gridOptions : {
			param:{deptId:$("#deptId").val()}
		},
		isShowButton : false
	});

	$("#formCode").yxcombogrid_interviewparent({
		nameCol:'formCode',
		isFocusoutCheck:false,
		gridOptions : {
			event:{
				'row_dblclick' : function(e, row, data) {
					$("#formCode").val(data.formCode);
				}
			},
			showcheckbox : false
		}
	});

	//非需求部门
	$("#noDeptName").yxselect_dept({
		hiddenId : 'noDeptId',
		mode : 'check'
	});
 });

 function toSupport(){
	var state="";
    $("input[name='apply[state][]']:checkbox").each(function(){
        if($(this).attr("checked")){
            state += $(this).val()+","
        }
    });
    state = state.substring(0,state.length-1);
    var ExaStatus="";
    $("input[name='apply[ExaStatus][]']:checkbox").each(function(){
        if($(this).attr("checked")){
            ExaStatus += $(this).val()+","
        }
    });
    ExaStatus = ExaStatus.substring(0,ExaStatus.length-1);
	var formCodeSearch = $.trim($("#formCode").val());
	var ExaStatusSearch = $.trim($("#ExaStatus").val());
	var stateSearch = $.trim($("#state").val());

	var formManNameSearch = $.trim($("#formManName").val());
	var resumeToNameSearch = $.trim($("#resumeToName").val());
	var deptNameSearch = $.trim($("#deptName").val());

	var workPlaceSearch = $.trim($("#workPlace").val());
	var postTypeSearch = $.trim($("#postType").val());
	var positionNameSearch = $.trim($("#positionName").val());

	var positionNoteSearch = $.trim($("#positionNote").val());
	var positionLevelSearch = $.trim($("#positionLevelHidden").val());
	var projectGroupSearch = $.trim($("#projectGroup").val());

	var isEmergencySearch = $.trim($("input:radio['apply['isEmergency']']:checked").val());
	var formDateBefSearch = $.trim($("#formDateBef").val());
	var formDateEndSearch = $.trim($("#formDateEnd").val());
	var hopeDateBefSearch = $.trim($("#hopeDateBef").val());
	var hopeDateEndSearch = $.trim($("#hopeDateEnd").val());

	var addTypeCodeSearch = $.trim($("#addTypeCode").val());
	var needNumSearch = $.trim($("#needNum").val());
	var recruitManNameSearch = $.trim($("#recruitManName").val());

	var applyReasonSearch = $.trim($("#applyReason").val());
	var workDutySearch = $.trim($("#workDuty").val());
	var jobRequireSearch = $.trim($("#jobRequire").val());

	var keyPointSearch = $.trim($("#keyPoint").val());
	var attentionMatterSearch = $.trim($("#attentionMatter").val());
	var leaderLoveSearch = $.trim($("#leaderLove").val());

	var noDeptName = $.trim($("#noDeptName").val());
	var positionRequirementSearch = $.trim($("#positionRequirement").val());
	var applyRemarkSearch = $.trim($("#applyRemark").val());

	//主列表对象获取
	var listGrid= parent.$("#applyGrid").data('yxgrid');

	//设置值以及传输列表参数
	setVal(listGrid,'formCode',formCodeSearch);
	setVal(listGrid,'ExaStatus',ExaStatusSearch);
	setVal(listGrid,'state',stateSearch);

	setVal(listGrid,'formManName',formManNameSearch);
	setVal(listGrid,'resumeToNameSearch',resumeToNameSearch);
	setVal(listGrid,'deptName',deptNameSearch);

	setVal(listGrid,'workPlaceSearch',workPlaceSearch);
	setVal(listGrid,'postTypeSearch',postTypeSearch);
	setVal(listGrid,'positionName',positionNameSearch);

	setVal(listGrid,'positionNoteSearch',positionNoteSearch);//职位备注无法多对多的搜索
	var tmp = positionLevelSearch.split(",");//级别无法多对多的搜索
	for (var i=0 ;i < tmp.length ;i++){
		setVal(listGrid,'positionLevelSearch',tmp[i]);
	}
	setVal(listGrid,'projectGroupSearch',projectGroupSearch);

	setVal(listGrid,'isEmergency',isEmergencySearch);
	setVal(listGrid,'formDateBefSearch',formDateBefSearch);
	setVal(listGrid,'formDateEndSearch',formDateEndSearch);
	setVal(listGrid,'DateBegin',hopeDateBefSearch);
	setVal(listGrid,'DateEnd',hopeDateEndSearch);

	setVal(listGrid,'addTypeCode',addTypeCodeSearch);
	setVal(listGrid,'needNum',needNumSearch);
	setVal(listGrid,'recruitManNameSearch',recruitManNameSearch);

	setVal(listGrid,'applyReasonSearch',applyReasonSearch);
	setVal(listGrid,'workDutySearch',workDutySearch);
	setVal(listGrid,'jobRequireSearch',jobRequireSearch);

	setVal(listGrid,'keyPoint',keyPointSearch);
	setVal(listGrid,'attentionMatter',attentionMatterSearch);
	setVal(listGrid,'leaderLove',leaderLoveSearch);

	setVal(listGrid,'positionRequirement',positionRequirementSearch);
	setVal(listGrid,'applyRemarkSearch',applyRemarkSearch);

	setVal(listGrid,'noDeptName',noDeptName);
	setVal(listGrid,'stateArr',state);
	setVal(listGrid,'ExaStatusArr',ExaStatus);

	//刷新列表
	listGrid.reload();
	closeFun();
}

function setVal(obj,thisKey,thisVal) {
	return obj.options.extParam[thisKey] = thisVal;
}

//清空
function toClear(){
	$(".toClear").val('');
}

