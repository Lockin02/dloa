//增加一行
function pay_add(mypay, countNum) {
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mypay = document.getElementById(mypay);
	i = mypay.rows.length;
	oTR = mypay.insertRow([i]);
	//oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "30px";
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input type='text' class='txt' name='temporary[Bank][" + mycount
			+ "][bankName]' />";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input type='text' name='temporary[Bank][" + mycount
			+ "][accountNum]' />";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<textarea   class='txt_txtarea_input'  name='temporary[Bank]["
			+ mycount
			+ "][remark]'> </textarea>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mypay.id + "\")' title='删除行'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;

	createFormatOnClick('PayMoney'+mycount);
}

//动态添加供应商联系人
function linkman_add(mylinkman,countNum){
	mycount = document.getElementById(countNum).value * 1 + 1;
	var mylinkman = document.getElementById(mylinkman);
	i = mylinkman.rows.length;
	oTR = mylinkman.insertRow([i]);
	//oTR.className = "TableData";
	oTR.align = "center";
	oTR.height = "30px";
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = i;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input type='text'class='txtshort' name='temporary[supplinkman][" + mycount
			+ "][name]' />";
	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input type='text' name='temporary[supplinkman][" + mycount
			+ "][[plane]'  onblur='checkPlan(this)'/>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input type='text' name='temporary[supplinkman][" + mycount
			+ "][mobile1]'  onblur='checkMobile1(this)'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<input type='text' name='temporary[supplinkman][" + mycount
			+ "][email]'  onblur='checkEmail(this)'/>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input type='text' name='temporary[supplinkman][" + mycount
			+ "][fax]'  onblur='checkPlan(this)' />";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<textarea name='temporary[supplinkman]["
			+ mycount
			+ "][remark]'> </textarea>";
	oTL7 = oTR.insertCell([7]);
	oTL7.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mylinkman.id + "\")' title='删除行'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;

}

//删除一行
function mydel(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 1; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i;
		}
	}
}
//缩放功能
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}

//对固定电话的验证
function checkPlan(obj){
	// 验证电话号码手机号码，包含153，159号段
	if (obj.value != "") {
		var p1 = /^(([0\+]\d{2,3}-)?(0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/;
		var me = false;
		if (p1.test(obj.value))
			me = true;
		if (!me) {
			alert('对不起，您输入的电话号码有错误。区号和电话号码之间请用-分割');
			obj.value="";
			return false;
		}
	}
	return true;
}

//对联系电话的验证
function checkMobile1(obj){
	if (obj.value != "") {
		var reg0 = /^13\d{5,9}$/;
		var reg1 = /^153\d{4,8}$/;
		var reg2 = /^159\d{4,8}$/;
		var reg3 = /^0\d{10,11}$/;
		var reg4 = /^150\d{4,8}$/;
		var reg5 = /^158\d{4,8}$/;
		var reg6 = /^15\d{5,9}$/;
		var my = false;
		if (reg0.test(obj.value))
			my = true;
		if (reg1.test(obj.value))
			my = true;
		if (reg2.test(obj.value))
			my = true;
		if (reg3.test(obj.value))
			my = true;
		if (reg4.test(obj.value))
			my = true;
		if (reg5.test(obj.value))
			my = true;
		if (reg6.test(obj.value))
			my = true;
		if (!my) {
			alert('对不起，您输入的手机号码有错误。');
			obj.value="";
			return false;
		}
		return true;
	}
}

//对email的验证
function checkEmail(obj){
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (filter.test(obj.value))
		return true;
	else {
		alert('您的电子邮件格式不正确');
		obj.value="";
		return false;
	}
}