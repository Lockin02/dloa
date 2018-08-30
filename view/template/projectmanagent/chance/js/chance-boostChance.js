$(function(){
	 var status = $("#status").val();
   if(status != '3'){
     	  //商机阶段
	    $('select[name="chance[chanceStage]"] option').each(function() {
	        var chanceStageA = $("#chanceStageA").val();
			if( $(this).val() == chanceStageA){
			  return false;
			}
			$(this).remove()
		});

	 //判断推进信息是否可以推进
	 var winRateDone = $("#winRateDone").val();
	 if(winRateDone == 'done'){
	    $('select[name="chance[winRate]"] option').each(function() {
	        var winRateA = $("#winRateA").val();
			if( $(this).val() != winRateA){
			  $(this).remove()
			}
		});
	 }
	 var chanceStageDone = $("#chanceStageDone").val();
	 if(chanceStageDone == 'done'){
	    $('select[name="chance[chanceStage]"] option').each(function() {
	        var chanceStageA = $("#chanceStageA").val();
			if( $(this).val() != chanceStageA){
			  $(this).remove()
			}
		});
	 }
    }

            /**
			 * 验证信息
			 */
			validate({});
});

   function chanceInfo(){
      var chanceId = $("#chanceId").val();
      showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id=" + chanceId
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
   }

   function sub(){
      var winRate = $("#winRate").val();
      var oldwinRate = $("#oldwinRate").val();
      var chanceStage = $("#chanceStage").val();
      var oldchanceStage = $("#oldchanceStage").val();
      if((winRate == oldwinRate) && (chanceStage == oldchanceStage)){
         alert("无推进信息，请选择推进信息");
         return false;
      }
      if((chanceStage == "SJJD06" || chanceStage == "SJJD07") && (winRate != "0")){
         alert("阶段六或阶段七时请将 赢率 调整为 “0%”");
         return false;
      }
      if((winRate == "0") && (chanceStage != "SJJD06" && chanceStage != "SJJD07")){
         alert("赢率为0% 时请将商机阶段调整为“阶段六”或“阶段七”");
         return false;
      }
      var goodsTable = document.getElementById("productList");
	  var tr = goodsTable.getElementsByTagName("tr");
	  var isProFlag = $("#isProFlag").val();
	  if (isProFlag == "0" && tr.length == '0') {
		 alert("当商机为阶段一以上时，产品不能为空，请选择产品");
		  return false;
	  }
         return true;
   }
function changeWinRate(obj){
	var val = obj.value;
    if(val == '80' || val == '100'){
       alert("特别提示 ： 商机推进到 80%或100% 后 赢率将无法更改！");
    }
}

function changeChanceStage(){
	var chanceStage = $("#chanceStage").val();
  if(chanceStage == "SJJD06" || chanceStage == "SJJD07"){
     	$('select[name="chance[winRate]"] option').each(function() {
			if( $(this).val() == "0" ){
				$(this).attr("selected","selected'");
			}
		});
		$("#closeInfo").show();
		$('#closeRegard').addClass("validate[required]");
  }else{
        $("#closeInfo").hide();
		$('#closeRegard').removeClass("validate[required]");
  }
  //判断商机是否有产品
   var isPro = $.ajax({
		type : 'POST',
		url : '?model=projectmanagent_chance_chance&action=ajaxFindChanceGoods',
		data : {
			chanceId : $("#chanceId").val()
		},
		async : false,
		success : function(data) {
		}
	}).responseText;
   if(isPro == '0' && chanceStage != 'SJJD01'){
       $("#isProFlag").val("0");
       alert("商机阶段一以上时产品必填，请选择产品");
       $("#proList").show();
   }else{
       $("#isProFlag").val("1");
       $("#proList").hide();
   }
}

// 选产品
function chooseProduct() {
	// url = "?model=projectmanagent_chance_chance&action=chanceProduct";
	// showModalDialog(url, '',"dialogWidth:1000px;dialogHeight:600px;");

	showThickboxWin("?model=projectmanagent_chance_chance&action=chanceProduct"
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
}
// 删除产品
function delectPro(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.rowIndex;
		$(obj).parent().remove();
		listNum();
		countSum();
		// $(obj).parent().hide();
	}
}
// 处理序号
function listNum() {
	var goodsTable = document.getElementById("productList");
	var rows = goodsTable.rows.length;
	for (var i = 0; i < rows; i++) {
		$("#productList tr:eq(" + i + ") td:eq(0)").text(i + 1);
	}
}

