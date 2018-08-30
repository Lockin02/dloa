$(document).ready(function () {
    //使用人渲染
    $("#outUser").yxselect_user({
        hiddenId: 'outUserId',
        isGetDept : [true, "outUseOrgId", "outUseOrg"],
        event : {
            select : function() {
            	$("#showArea").empty();
            },
            clearReturn : function(){
            	$("#showArea").empty();
            }
        }
    });
    $("#inUser").yxselect_user({
        hiddenId: 'inUserId',
        isGetDept : [true, "inUseOrgId", "inUseOrg"]
    });
    //所属人渲染
    $("#outBelongMan").yxselect_user({
        hiddenId: 'outBelongManId',
        isGetDept : [true, "outOrgId", "outOrg"],
        event : {
            select : function() {
            	$("#showArea").empty();
            },
            clearReturn : function(){
            	$("#showArea").empty();
            }
        }
    });
    $("#inBelongMan").yxselect_user({
        hiddenId: 'inBelongManId',
        isGetDept : [true, "inOrgId", "inOrg"]
    });
	//行政区域渲染
	$("#outAgencyName").yxcombogrid_agency({
		hiddenId : 'outAgencyCode',
		event : {
			'clear' : function() {
				$("#showArea").empty();
			}
		},
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#showArea").empty();
				}
			}
		}
	});
	$("#inAgencyName").yxcombogrid_agency({
		hiddenId : 'inAgencyCode'
	});
	
    //绑定一下验证
    $("form").submit(function(){
    	if($("#inUser").val() == "" && $("#inBelongMan").val() == "" && $("#inAgencyName").val() == ""){
    		alert('请至少选择一种更新类型并初始化转出转入信息');
    		return false;
    	}
    	if($("#outUser").val() == "" && $("#inUser").val() != ""){
    		alert('转入使用人不为空，必须填写转出使用人信息');
    		return false;
    	}
    	if($("#outBelongMan").val() == "" && $("#inBelongMan").val() != ""){
    		alert('转入所属人不为空，必须填写转出所属人信息');
    		return false;
    	}
    	if($("#outAgencyName").val() == "" && $("#inAgencyName").val() != ""){
    		alert('转入行政区域不为空，必须填写转出行政区域信息');
    		return false;
    	}
        var checkedNum = $("input[name^='assetcard[idArr]']:checked").length;
        if(checkedNum*1 == 0){
            alert('请先选择要更新的卡片');
            return false;
        }
        return confirm('确认更新选择的卡片吗?');
    });
});

//选择要更新的类型
function selectType(type){
	$("#"+type).toggle();
	if($("#"+type).css('display') == 'none'){
		$("#"+type).find("input").val("");
	}
}
//初始化要更新的内容
function init(){
	//参数判断
	var userId = $("#outUserId").val();
	var belongManId = $("#outBelongManId").val();
	var agencyCode = $("#outAgencyCode").val();

	var paramObj = {};
	if(userId)
		paramObj.userId = userId;
	if(belongManId)
		paramObj.belongManId = belongManId;
	if(agencyCode)
		paramObj.agencyCode = agencyCode;

	if(userId == '' && belongManId == '' && agencyCode == ''){
		alert('请至少选择一种更新类型并初始化转出转入信息');
	}else{
	  $.ajax({
	      url : '?model=asset_assetcard_assetcard&action=getUpdateData',
	      data : paramObj,
	      type : 'POST',
	      success : function(data){
	          $("#showArea").empty().append(data);
	      }
	  });
	}
}
//全选
function checkAll(){
    if($("#checkboxAll").attr('checked') == false){
        $("input[id^='check-']").attr('checked',false);
        $("#num").html(0);
    }else{
        $("input[id^='check-']").attr('checked',true);
        $("#num").html($("#allNum").html());
    }
}

//单选
function checkThis(id){
    var num = $("#num").html()*1;
    if($("#check-"+id).attr('checked') == false){
        $("#num").html(num - 1);
    }else{
        $("#num").html(num + 1);
    }
}

//查看单据
function viewForm(id){
    showModalWin("?model=asset_assetcard_assetcard&action=init&perm=view&id=" + id,1,id);
}