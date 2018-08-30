$(document).ready(function() {
	var levelid = $('#levelId').val();
	$("#name").yxcombotree({
		hiddenId : 'rangeId',// ���ؿؼ�id
		treeOptions : {
			checkable : true,// ��ѡ
			url : "?model=engineering_assessment_assIndex&action=getChildren",// ��ȡ����url
			event : {
				node_change : addLevelRow
			}
		}
	});

	/*
	 * ����ָ����
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
	 * ɾ��ָ����
	 */
	function mydel(id) {
		var tdEl = document.getElementById(id);
		var itemtable = document.getElementById("itemtable");
		var rowNo = tdEl.parentNode.parentNode.rowIndex;
		// �������к�
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
		alert("Ȩ���ܺͱ������100,��ǰ�ܺ�Ϊ: " + temp );
		return false;
	}else{
		return true;
	}
}
