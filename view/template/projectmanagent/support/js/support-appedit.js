$(document).ready(function() {
	//���齻����Ա
	$("#exchangeName").yxselect_user({
		hiddenId : 'exchangeId',
		event : {
			select : function(e, row) {
			  setinfo();
			}
		}
			//						isGetDept:[true,"depId","depName"]
	});

//	$(window.parent.document.getElementById("sub")).bind("click", function() { // �����ύʱ���ж�
//				if ($(window.parent.document.getElementById("appendHtml"))
//						.html() == "") {
//					alert("����ȷѡ���齻����Ա��");
//					return false;
//				}
//			});
	function setinfo() {
		var exchangeName = $("#exchangeName").val(); // ְλ����
		var exchangeId = $("#exchangeId").val(); // ְλID
		var objId = $("#id").val();
		var appendHtml = ' <input type="hidden" id="exchangeName" name="objInfo[exchangeName]" value="'
				+ exchangeName
				+ '"/>'
				+ ' <input type="hidden" id="exchangeId" name="objInfo[exchangeId]" value="'
				+ exchangeId
				+ '"/>'
				+ '<input type="hidden" id="parentid" name="objInfo[id]" value="'
				+ objId + '"/>';
		if ($(window.parent.document.getElementById("appendHtml")).html() != "") { // ����ѡ����Ȱ�ǰһ��׷�ӵ��������
			$(window.parent.document.getElementById("appendHtml")).html("");
		}
		$(window.parent.document.getElementById("appendHtml"))
				.append(appendHtml);
	}

})
