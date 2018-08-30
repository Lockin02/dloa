

//设置最大最小值
function setLimitVal(thisVal){
	var upperLimitObj = $("#upperLimit");
	var lowerLimitObj = $("#lowerLimit");

	//从表对象
	var thisGrid = $("#innerTable");
	var thisMax , thisMin ;
	var cmps = thisGrid.yxeditgrid("getCmpByCol", "score");
	cmps.each(function(i,n) {
		if(thisMax){
			if(this.value*1 > thisMax) thisMax = this.value
		}else{
			thisMax = this.value;
		}

		if(thisMin){
			if(this.value*1 < thisMin) thisMin = this.value
		}else{
			thisMin = this.value;
		}
	});

	upperLimitObj.val(thisMax);
	lowerLimitObj.val(thisMin);
}