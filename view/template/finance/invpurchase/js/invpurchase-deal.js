var stockArr = [];

$(function(){
	var stockIds = $("#stockIds").val();
	stockArr = stockIds.split(",");
});


//验证id是否在数组中
function checkValue(thisObj){
	if(stockArr.inArrayDeal(thisObj.value) == false){
		alert('请在下方选择明细id录入');
		thisObj.value = "";
	}
}

Array.prototype.inArrayDeal = function (value)
// Returns true if the passed value is found in the
// array.  Returns false if it is not.
{
    var i;
    for (i=0; i < this.length; i++) {
        // Matches identical (===), not just similar (==).
        if (this[i] === value) {
            return true;
        }
    }
    return false;
};