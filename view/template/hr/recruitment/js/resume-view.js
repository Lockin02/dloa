$(document).ready(function() {
	textareaHeightAuto($("#selfAssessment"));
});

$(window).resize(function() {
	textareaHeightAuto($("#selfAssessment"));
});

//控制多文本框的高度
function textareaHeightAuto($this) {
	var rows = $this.val().split("\n"); //获取每一个物理行的内容
	var rowsLength = rows.length; //手动换行的行数
	var rowsNum = rowsLength; //初始化等于没有自动换行的行数

	//加上自动换行的行数
	var tmp = 0;
	for (var i = 0; i < rowsLength; i++) {
		tmp = rows[i].length * 12 / parseInt($this.css("width"));
		if (tmp >= 1) {
			rowsNum += parseInt(tmp);
		}
	}
	$this.attr("rows" ,rowsNum);
}