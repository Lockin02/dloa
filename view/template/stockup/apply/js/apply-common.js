
//��ȡ�̻���Ϣ
function getChanceInfo(thisType){
	var chanceCode = $("#chanceCode").val();
	var chanceName = $("#chanceName").val();
	if(chanceCode == "" && chanceName == ""){
		return false;
	}
	$.ajax({
	    type: "POST",
	    url: "?model=projectmanagent_chance_chance&action=ajaxChanceByCode",
	    data: {"chanceCode" : chanceCode , "chanceName" : chanceName},
	    async: false,
	    success: function(data){
	   		if(data){
				var dataArr = eval("(" + data + ")");
				if(dataArr.thisLength*1 > 1){
					alert('ϵͳ�д��ڡ�' + dataArr.thisLength + '��������Ϊ��' + chanceName + '�����̻�����ͨ���̻����ƥ���̻���Ϣ��');
				}else{
					$("#chanceCode").val(dataArr.chanceCode);
					$("#chanceId").val(dataArr.id);
					$("#chanceName").val(dataArr.chanceName);
				}
	   	    }else{
				alert('û�в�ѯ������̻���Ϣ');
				$("#chanceCode").val('');
				$("#chanceId").val('');
				$("#chanceName").val('');
	   	    }
		}
	});
}

//����������Ⱦ
function buildInputSet(thisId,thisName,thisType){
	//��Ⱦһ��ƥ�䰴ť
	var thisObj = $("#" + thisId);
	if(thisObj.attr('wchangeTag2') == 'true' || thisObj.attr('wchangeTag2') == true){
		return false;
	}
	var title = "����������" + thisName +"��ϵͳ�Զ�ƥ�������Ϣ";
	var $button = $("<span class='search-trigger' id='" + thisId + "Search' title='"+ title +"'>&nbsp;</span>");
	$button.click(function(){
		if($("#" + thisId).val() == ""){
			alert('������һ��' + thisName);
			return false;
		}
	});

	//�����հ�ť
	var $button2 = $("<span class='clear-trigger' title='����������'>&nbsp;</span>");
	$button2.click(function(){
				$("#chanceCode").val('');
				$("#chanceId").val('');
				$("#chanceName").val('');
	});
	thisObj.after($button2).width(thisObj.width() - $button2.width()).after($button).width(thisObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');
}