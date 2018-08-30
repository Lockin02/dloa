$(function() {

			$("#logGrid").yxgrid_tracklog({
						param : {
							objType : $("#objType").val(),
							objId : $("#objId").val()
						}
					});
		});