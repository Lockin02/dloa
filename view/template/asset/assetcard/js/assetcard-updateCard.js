$(document).ready(function () {
    //ʹ������Ⱦ
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
    //��������Ⱦ
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
	//����������Ⱦ
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
	
    //��һ����֤
    $("form").submit(function(){
    	if($("#inUser").val() == "" && $("#inBelongMan").val() == "" && $("#inAgencyName").val() == ""){
    		alert('������ѡ��һ�ָ������Ͳ���ʼ��ת��ת����Ϣ');
    		return false;
    	}
    	if($("#outUser").val() == "" && $("#inUser").val() != ""){
    		alert('ת��ʹ���˲�Ϊ�գ�������дת��ʹ������Ϣ');
    		return false;
    	}
    	if($("#outBelongMan").val() == "" && $("#inBelongMan").val() != ""){
    		alert('ת�������˲�Ϊ�գ�������дת����������Ϣ');
    		return false;
    	}
    	if($("#outAgencyName").val() == "" && $("#inAgencyName").val() != ""){
    		alert('ת����������Ϊ�գ�������дת������������Ϣ');
    		return false;
    	}
        var checkedNum = $("input[name^='assetcard[idArr]']:checked").length;
        if(checkedNum*1 == 0){
            alert('����ѡ��Ҫ���µĿ�Ƭ');
            return false;
        }
        return confirm('ȷ�ϸ���ѡ��Ŀ�Ƭ��?');
    });
});

//ѡ��Ҫ���µ�����
function selectType(type){
	$("#"+type).toggle();
	if($("#"+type).css('display') == 'none'){
		$("#"+type).find("input").val("");
	}
}
//��ʼ��Ҫ���µ�����
function init(){
	//�����ж�
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
		alert('������ѡ��һ�ָ������Ͳ���ʼ��ת��ת����Ϣ');
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
//ȫѡ
function checkAll(){
    if($("#checkboxAll").attr('checked') == false){
        $("input[id^='check-']").attr('checked',false);
        $("#num").html(0);
    }else{
        $("input[id^='check-']").attr('checked',true);
        $("#num").html($("#allNum").html());
    }
}

//��ѡ
function checkThis(id){
    var num = $("#num").html()*1;
    if($("#check-"+id).attr('checked') == false){
        $("#num").html(num - 1);
    }else{
        $("#num").html(num + 1);
    }
}

//�鿴����
function viewForm(id){
    showModalWin("?model=asset_assetcard_assetcard&action=init&perm=view&id=" + id,1,id);
}