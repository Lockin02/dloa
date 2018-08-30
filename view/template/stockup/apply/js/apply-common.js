
//获取商机信息
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
					alert('系统中存在【' + dataArr.thisLength + '】条名称为【' + chanceName + '】的商机，请通过商机编号匹配商机信息！');
				}else{
					$("#chanceCode").val(dataArr.chanceCode);
					$("#chanceId").val(dataArr.id);
					$("#chanceName").val(dataArr.chanceName);
				}
	   	    }else{
				alert('没有查询到相关商机信息');
				$("#chanceCode").val('');
				$("#chanceId").val('');
				$("#chanceName").val('');
	   	    }
		}
	});
}

//构建填入渲染
function buildInputSet(thisId,thisName,thisType){
	//渲染一个匹配按钮
	var thisObj = $("#" + thisId);
	if(thisObj.attr('wchangeTag2') == 'true' || thisObj.attr('wchangeTag2') == true){
		return false;
	}
	var title = "输入完整的" + thisName +"，系统自动匹配相关信息";
	var $button = $("<span class='search-trigger' id='" + thisId + "Search' title='"+ title +"'>&nbsp;</span>");
	$button.click(function(){
		if($("#" + thisId).val() == ""){
			alert('请输入一个' + thisName);
			return false;
		}
	});

	//添加清空按钮
	var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
	$button2.click(function(){
				$("#chanceCode").val('');
				$("#chanceId").val('');
				$("#chanceName").val('');
	});
	thisObj.after($button2).width(thisObj.width() - $button2.width()).after($button).width(thisObj.width() - $button.width()).attr("wchangeTag2", true).attr('readonly',false).attr("class",'txt');
}