
$().ready(function() {
	//项目初始化
    $("#projectNames").yxcombogrid_rdProject({
		hiddenId :  'projectIds',
		height : 300,
		gridOptions : {
			isTitle : true,
			param : { "managerId" : $("#userId").val() },
			event : {
				'after_row_check' : function(e, row, data){
					initGrid();
				}
			}
		}
	});

	//日期初始化
	function thisMonth(){
		 var d, s;

	    // 创建 Date 对象。
	    d = new Date();
	    s = d.getFullYear() + "-";
	    s += ("0"+(d.getMonth()+1)).slice(-2) + "-01";

	    return s;
    }

    $("#beginDate").val(thisMonth());
    $("#overDate").val(formatDate(new Date()));

	//表单验证
	$.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            alert(msg);
        },
        onsuccess: function() {
            if ($("#overDate").val() < $("#beginDate").val()) {
            	alert('起始时间不能大于终止时间');
                return false;
            } else {
                return true;
            }
        }
    });

    $("#beginDate").formValidator({
        onshow: "请选择计划开始日期",
        onfocus: "请选择日期",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
    }); //.defaultPassed();

    $("#overDate").formValidator({
        onshow: "请选择计划结束日期",
        onfocus: "请选择日期",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
    }); //.defaultPassed();

    $("#projectNames").formValidator({
        onshow: "请选择项目",
        onfocus: "请选择项目",
        oncorrect: "OK"
    }).inputValidator({
		min: 2,
		max: 300,
		empty: {
			leftempty: false,
			rightempty: false,
			emptyerror: "请选择项目"
		},
		onerror: "请选择项目"
    }); //.defaultPassed();

	//如果项目成员不为空，则渲染人员下拉表格
	$("#memberNames").val("");
	$("#memberIds").val("");
	$("#projectNames").val("");
	$("#projectIds").val("");
})

//初始化表格
function initGrid(){
	var projectIds = $("#projectIds").val();
	$("#memberNames").yxcombogrid_rdmember('remove');
	$("#memberNames").val('');
	$("#memberIds").val('');

	if(projectIds ==""){
		return false;
	}

	//项目成员初始化
    $("#memberNames").yxcombogrid_rdmember({
		hiddenId :  'memberIds',
		gridOptions : {
			param : { "isInternal" : 1 ,"projectIds" : projectIds },
			colModel : [{
					display : 'id',
					name : 'id',
					hide : true
				}, {
					display : '项目名称',
					name : 'projectName',
					width : 180,
					hide : true
				}, {
					display : '项目编号',
					name : 'projectCode',
					width : 130,
					hide : true
				}, {
					display : '成员名称',
					name : 'memberName'
				}, {
					display : '成员名称id',
					name : 'memberId',
					hide : true
				}, {
					display : '描述信息',
					name : 'description',
					width : 150,
					hide : true
				}
			]
		}
	});
}