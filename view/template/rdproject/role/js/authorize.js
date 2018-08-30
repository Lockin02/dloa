$(document).ready(function() {
			// $.each($("input:checkbox"), function(i, obj) {
			// $(obj).attr("name", "perms[" + i + "]");
			// });
			$("input:checkbox").bind("click", function() {
						var permCode = $(this).attr("id");
						var checked = $(this).attr("checked");
						if (checked) {
							$(this).val(permCode);
							// 勾选子权限，则自动勾选父权限
							var i = permCode.lastIndexOf("_");
							var lastPermCode = permCode.substr(0, i);
							$("#" + lastPermCode).attr("checked", checked);
						} else {
							$(this).val('');
							
						}
						// 勾选或者取消父权限，则自动勾选或者取消所有子权限
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
 * 保存权限信息
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
						alert("权限保存成功！");
					} else {
						alert("权限保存失败！");
					}
				}
			});
}