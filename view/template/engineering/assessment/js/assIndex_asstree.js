$(document).ready(function() {
	var levelid = $('#levelId').val();
	$("#name").yxcombotree({
		hiddenId : 'rangeId',// 隐藏控件id
		treeOptions : {
			checkable : true,// 多选
			url : "?model=engineering_assessment_assIndex&action=getChildren",// 获取数据url
			event : {
				node_change : addLevelRow
			}
		}
	});

	/*
	 * 增加指标项
	 */
	function addLevelRow(event, treeId, treeNode) {
		if (treeNode.checked) {
			mycount = document.getElementById("coutNumb").value * 1;
			mycount++;
			var itemtable = document.getElementById("itemtable");
			i = itemtable.rows.length;
			oTR = itemtable.insertRow([i]);
			oTR.align = "center";
			oTL2 = oTR.insertCell([0]);
//			oTL2.class = "form_text_left";
			oTL2.innerHTML = '<input type="hidden"  name="assIndex[' + mycount
					+ '][levelId]" value="' + levelid + '" />'
					+ '<input type="hidden" id="' + treeNode.id + '" value="'
					+ treeNode.id + '" name="assIndex[' + mycount
					+ '][indexId]">' + '<input type="hidden"  value="'
					+ treeNode.name + '" name="assIndex[' + mycount
					+ '][name]" readonly>' + treeNode.name;
			oTL3 = oTR.insertCell([1]);
//			oTL3.class = "form_text_right";
			oTL3.innerHTML = '<input type="text"  value="" class="txt" name="assIndex['
					+ mycount + '][weight]" id="weight'
					+ mycount + '"/>';
			document.getElementById("coutNumb").value = document
					.getElementById("coutNumb").value
					* 1 + 1;
		} else {
			mydel(treeNode.id);
		}
	}
	/*
	 * 删除指标项
	 */
	function mydel(id) {
		var tdEl = document.getElementById(id);
		var itemtable = document.getElementById("itemtable");
		var rowNo = tdEl.parentNode.parentNode.rowIndex;
		// 减少序列号
		// document.getElementById("coutNumb").value=document.getElementById("coutNumb").value-1;
		itemtable.deleteRow(rowNo);
	}

});

function checkform(){
	var temp = 0;
	$.each($("input[id^='weight']"), function(i, n) {
		temp = accAdd(temp , $(this).val()*1);
	});
	if(temp > 100 || temp < 100){
		alert("权重总和必须等于100,当前总和为: " + temp );
		return false;
	}else{
		return true;
	}
}
