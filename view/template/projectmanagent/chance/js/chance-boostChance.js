$(function(){
	 var status = $("#status").val();
   if(status != '3'){
     	  //�̻��׶�
	    $('select[name="chance[chanceStage]"] option').each(function() {
	        var chanceStageA = $("#chanceStageA").val();
			if( $(this).val() == chanceStageA){
			  return false;
			}
			$(this).remove()
		});

	 //�ж��ƽ���Ϣ�Ƿ�����ƽ�
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
			 * ��֤��Ϣ
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
         alert("���ƽ���Ϣ����ѡ���ƽ���Ϣ");
         return false;
      }
      if((chanceStage == "SJJD06" || chanceStage == "SJJD07") && (winRate != "0")){
         alert("�׶�����׶���ʱ�뽫 Ӯ�� ����Ϊ ��0%��");
         return false;
      }
      if((winRate == "0") && (chanceStage != "SJJD06" && chanceStage != "SJJD07")){
         alert("Ӯ��Ϊ0% ʱ�뽫�̻��׶ε���Ϊ���׶������򡰽׶��ߡ�");
         return false;
      }
      var goodsTable = document.getElementById("productList");
	  var tr = goodsTable.getElementsByTagName("tr");
	  var isProFlag = $("#isProFlag").val();
	  if (isProFlag == "0" && tr.length == '0') {
		 alert("���̻�Ϊ�׶�һ����ʱ����Ʒ����Ϊ�գ���ѡ���Ʒ");
		  return false;
	  }
         return true;
   }
function changeWinRate(obj){
	var val = obj.value;
    if(val == '80' || val == '100'){
       alert("�ر���ʾ �� �̻��ƽ��� 80%��100% �� Ӯ�ʽ��޷����ģ�");
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
  //�ж��̻��Ƿ��в�Ʒ
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
       alert("�̻��׶�һ����ʱ��Ʒ�����ѡ���Ʒ");
       $("#proList").show();
   }else{
       $("#isProFlag").val("1");
       $("#proList").hide();
   }
}

// ѡ��Ʒ
function chooseProduct() {
	// url = "?model=projectmanagent_chance_chance&action=chanceProduct";
	// showModalDialog(url, '',"dialogWidth:1000px;dialogHeight:600px;");

	showThickboxWin("?model=projectmanagent_chance_chance&action=chanceProduct"
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
}
// ɾ����Ʒ
function delectPro(obj) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.rowIndex;
		$(obj).parent().remove();
		listNum();
		countSum();
		// $(obj).parent().hide();
	}
}
// �������
function listNum() {
	var goodsTable = document.getElementById("productList");
	var rows = goodsTable.rows.length;
	for (var i = 0; i < rows; i++) {
		$("#productList tr:eq(" + i + ") td:eq(0)").text(i + 1);
	}
}

