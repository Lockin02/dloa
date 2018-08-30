$(document).ready(function() {
	//建议交流人员
	$("#exchangeName").yxselect_user({
		hiddenId : 'exchangeId',
		event : {
			select : function(e, row) {
			  setinfo();
			}
		}
			//						isGetDept:[true,"depId","depName"]
	});

//	$(window.parent.document.getElementById("sub")).bind("click", function() { // 审批提交时，判断
//				if ($(window.parent.document.getElementById("appendHtml"))
//						.html() == "") {
//					alert("请正确选择建议交流人员。");
//					return false;
//				}
//			});
	function setinfo() {
		var exchangeName = $("#exchangeName").val(); // 职位名称
		var exchangeId = $("#exchangeId").val(); // 职位ID
		var objId = $("#id").val();
		var appendHtml = ' <input type="hidden" id="exchangeName" name="objInfo[exchangeName]" value="'
				+ exchangeName
				+ '"/>'
				+ ' <input type="hidden" id="exchangeId" name="objInfo[exchangeId]" value="'
				+ exchangeId
				+ '"/>'
				+ '<input type="hidden" id="parentid" name="objInfo[id]" value="'
				+ objId + '"/>';
		if ($(window.parent.document.getElementById("appendHtml")).html() != "") { // 重新选择刚先把前一次追加的内容清空
			$(window.parent.document.getElementById("appendHtml")).html("");
		}
		$(window.parent.document.getElementById("appendHtml"))
				.append(appendHtml);
	}

})
