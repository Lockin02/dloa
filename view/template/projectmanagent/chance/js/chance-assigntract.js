$(function() {
    authorizeListEdit();
	// ��֯������Աѡ��
	$("#trackName").yxselect_user({
		hiddenId : 'trackId',
		mode : 'check',
		event : {
			"select" : function(obj, row) {
				authorizeList();
			}
		}
	});
});
/**
 * ��̬�����̻��Ŷӳ�ԱȨ�������б�
 */
function authorizeList() {
	var trackmanIds = $("#trackId").val();
	var chanceId = $("#chanceId").val();
	$.ajax({
		type : 'POST',
		url : '?model=projectmanagent_chance_chance&action=toSetauthorizeInfo',
		data : {
			trackmanIds : trackmanIds,
			chanceId : chanceId
		},
		async : false,
		success : function(data) {
//			//					    	var obj = eval("(" + data +")");
//			//					    	alert(data)
			$("#authorize").html(data);
//			self.parent.tb_remove();
//			parent.listNum();
		}
	});
}

function sub(){
   var trackId = $("#trackId").val();
   var prinvipalId = $("#prinvipalId").val();
   if(trackId != ''){
     trackIdArr = trackId.split(",");
   }
   for( i in trackIdArr){
      if(trackIdArr[i] == prinvipalId){
         alert("�Ŷӳ�Ա��������ڸ��̻������ˣ�������ѡ��");
         return false;
         break;
      }
   }
      return true;
}

function authorizeListEdit() {
	var chanceId = $("#chanceId").val();
	$.ajax({
		type : 'POST',
		url : '?model=projectmanagent_chance_chance&action=toSetauthorizeInfoEdit',
		data : {
			chanceId : chanceId
		},
		async : false,
		success : function(data) {
//			//					    	var obj = eval("(" + data +")");
//			//					    	alert(data)
			$("#authorize").html(data);
//			self.parent.tb_remove();
//			parent.listNum();
		}
	});
}
function fs_selectAll(value) {
            var ckelems = document.getElementById("authorize").getElementsByTagName("input");
            for (var i = 0; i < ckelems.length; i++) {
                if (ckelems[i].type == "checkbox") {
                    if (value == 1)
                        ckelems[i].checked = true;
                    else
                        ckelems[i].checked = false;
                }
            }
        }

/**
 * �̻��Ŷ�Ȩ��
 */
function authorize() {
	var temp = document.getElementById("authorize");
	var zk =  document.getElementById("zk");
	var sf =  document.getElementById("sf");
	if (temp.style.display == ''){
	   temp.style.display = "none";
	   zk.style.display = "";
	   sf.style.display = "none";
	}else if (temp.style.display == "none"){
	   temp.style.display = '';
	   zk.style.display = 'none';
	   sf.style.display = '';
	}
}