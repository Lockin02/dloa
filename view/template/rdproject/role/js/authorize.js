$(document).ready(function() {
			// $.each($("input:checkbox"), function(i, obj) {
			// $(obj).attr("name", "perms[" + i + "]");
			// });
			$("input:checkbox").bind("click", function() {
						var permCode = $(this).attr("id");
						var checked = $(this).attr("checked");
						if (checked) {
							$(this).val(permCode);
							// ��ѡ��Ȩ�ޣ����Զ���ѡ��Ȩ��
							var i = permCode.lastIndexOf("_");
							var lastPermCode = permCode.substr(0, i);
							$("#" + lastPermCode).attr("checked", checked);
						} else {
							$(this).val('');
							
						}
						// ��ѡ����ȡ����Ȩ�ޣ����Զ���ѡ����ȡ��������Ȩ��
						 $("input:checkbox[id^="+permCode+"]").attr("checked", checked);

					});
			var permstr = $("#permstr").val();
			if (permstr != '') {
				$.each($("input:checkbox"), function(i, obj) {
							if (permstr.indexOf(obj.id) != -1) {
								$(obj).attr("checked", true);
							}
						});
			}

		});

/**
 * ����Ȩ����Ϣ
 */
function saveAuthorize() {
	var param = {
		roleId : $("#roleId").val()
	};
	var j = 0;
	$.each($("input:checkbox"), function(i, obj) {
				if (obj.checked == true) {
					param["perms[" + (j++) + "]"] = $(obj).attr("id");
				}
			});
	$.ajax({
				type : "POST",
				url : "?model=rdproject_role_rdrole&action=saveAuthorize",
				data : param,
				success : function(msg) {
					if (msg == 1) {
						alert("Ȩ�ޱ���ɹ���");
					} else {
						alert("Ȩ�ޱ���ʧ�ܣ�");
					}
				}
			});
}