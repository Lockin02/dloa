function update(objType,contractType) {
	$("#msg").html("正在更新中,如果单据数量较多,会占用一定的时间...");
	$.ajax({
		url : '?model=common_contract_allsource&action=updateOldcontract&objType='
				+ objType+"&contractType="+contractType,
		success : function(data) {
			if (data == 1) {
				alert('更新成功.');
				$("#msg").html('更新成功.请关闭窗口后重新刷新数据查看.');
			} else {
				alert('程序出错,更新失败.失败原因:' + data);
				$("#msg").html('更新失败.失败原因:' + data);
			}

		}
	});
}
