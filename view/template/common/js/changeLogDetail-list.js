var show_page = function(page) {
	$("#changeLogGrid").yxgrid_changeLog("reload");
};
$(function() {
			var param = {
				logObj : $('#logObj').val(),
				objId : $('#objId').val(),
				detailType : $('#detailType').val(),
				detailId : $('#detailId').val(),
				isLast : true
			};
			var isTemp = $('#isTemp').val();
			if (isTemp) {
				param.isTemp = isTemp;
			}
			$("#changeLogGrid").yxgrid_changeLogDetail({
						param : param
					});

		});