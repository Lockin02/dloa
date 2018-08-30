// 取得当前时间
// <script type="text/javascript">
// $("#payTime").val(formatDate(new Date()));
// </script>

/** ****************合同 区域负责人 --- 合同归属区域************************************** */
$(function() {
	$("#areaName").yxcombogrid_area({
				hiddenId : 'areaCode',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#areaPrincipal").val(data.areaPrincipal);
							$("#areaCode").val(data.id);
							$("#areaPrincipalId_v").val(data.areaPrincipalId);
						}
					}
				}
			});

	$("#customerProvince").yxcombogrid_province({
				hiddenId : 'clustomerProvinceId',
				gridOptions : {
					showcheckbox : false
				}
			});
	$("#createSection").yxselect_dept({
				hiddenId : 'createSectionId',
				event:{
					select:function(e,returnValue){alert(returnValue.text)}
				}
			});
	$("#createName").yxselect_user({
				hiddenId : 'createNameId'
			});
	// 线索来源
	cluesSourceArr = getData('XSLY');
	addDataToSelect(cluesSourceArr, 'cluesSource');

	$("#provincecity").yxcombogrid_province({
				hiddenId : 'provinceId',
				gridOptions : {
					showcheckbox : false
				}
			});

	var html = '<tr id="linkTab_1">'
			+ '<td>1'
			+ '</td>'
			+ '<td>'
			+ '<input class="text" type="hidden" name="clues[linkman][1][linkmanId]" id="linkmanId1"/>'
			+ '<input class="text" type="hidden" name="clues[linkman][1][customerId]" id="customerId1"/>'
			+ '<input class="txt" type="text" name="clues[linkman][1][linkmanName]" id="linkmanName1" title="双击可以添加联系人" >'
			+ '</td>'
			+ '<td>'
			+ '<input class="txt" type="text" name="clues[linkman][1][mobileTel]" id="mobileTel1" onchange="tel(1);"/>'
			+ '</td>'
			+ '<td>'
			+ '<input class="txt" type="text" name="clues[linkman][1][email]" id="email1" onchange="Email(1);"/>'
			+ '</td>'
			+ '<td>'
			+ '<select class="" type="text" name="clues[linkman][1][roleCode]" id="roleCode1">'
			+ $("#role").val()
			+ '</select>'
			+ '</td>'
			+ '<td>'
			+ '<input class="" type="checkbox" name="clues[linkman][1][isKeyMan]" id="isKeyMan1"/>'
			+ '</td>'
			+ '<td>'
			+ '<img src="images/closeDiv.gif" onclick="mydel(this,\'mylink\')" title="删除行"/>'
			+ '</td>' + '</tr>';

	$("#mylink").html(html); // 加载列表

	/*
	 * $("#linkmanName1").yxcombogrid_linkman({ hiddenId : 'linkmanId1',
	 * gridOptions : { reload : true, showcheckbox : false,
	 * param:{'customerId':$("#customerId").val()}, event : { 'row_dblclick' :
	 * function(e, row, data) { $("#mobileTel1").val(data.mobile); } } } });
	 */
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		isShowButton : false,
		gridOptions : {
			showcheckbox : false,
			// param :{"contid":$('#contractId').val()},
			event : {
				'row_dblclick' : function(e, row, data) {
					var getGrid = function() {
						return $("#customerLinkman")
								.yxcombogrid_linkman("getGrid");
					}
					var getGridOptions = function() {
						return $("#customerLinkman")
								.yxcombogrid_linkman("getGridOptions");
					}
					if (getGrid().reload) {
						getGridOptions().param = {
							customerId : data.id
						};
						getGrid().reload();
					} else {
						getGridOptions().param = {
							customerId : data.id
						}
					}

					$("#customerType").val(data.TypeOne);
					$("#customerProvince").val(data.Prov);
					$("#customerId").val(data.id);
					for (var i = 1; i <= mycount; i++) {
						$("#linkmanName" + i).yxcombogrid_linkman("remove");
						// $("#linkmanName" +
						// i).yxcombogrid_linkman("showCombo");
						// $("#mylink").html("");
						// $("#mylink").html(html);
						$("#linkmanName" + i).yxcombogrid_linkman({
							hiddenId : 'linkmanId' + i,
							gridOptions : {
								reload : true,
								showcheckbox : false,
								param : {
									'customerId' : data.id
								},
								event : {
									'row_dblclick' : (function(num) {
										return function(e, row, data) {
											$("#customerId" + num)
													.val(data.customerId);
											$("#mobileTel" + num)
													.val(data.mobile);
											$("#email" + num).val(data.email);
										};
									})(i)
								}
							}
						});
					}
				}
			}
		}
	});
	// 客户类型
	customerTypeArr = getData('KHLX');
	addDataToSelect(customerTypeArr, 'customerType');
	// addDataToSelect(customerTypeArr, 'customerListTypeArr1');
	trackTypeArr = getData('GZLX');
	addDataToSelect(trackTypeArr, 'trackType');
	reloadLinkman(1);
});

function reloadCombo() {
	// alert( $("#customerLinkman").yxcombogrid('grid').param );
	$("#customerLinkman").yxcombogrid('grid').reload;
}
// 客户联系人
function reloadLinkman(i) {
	// if($("#customerId").val()==""){
	// alert("请选择客户!");
	// $("#linkmanName1").yxcombogrid_linkman("remove");
	// $("#customerName").focus();
	// }else{
	$("#linkmanName" + i).yxcombogrid_linkman("remove");
	$("#linkmanName" + i).yxcombogrid_linkman("showCombo");
	$("#linkmanName" + i).yxcombogrid_linkman({
				gridOptions : {
					reload : true,
					showcheckbox : false,
					param : {
						'customerId' : $("#customerId").val()
					},
					event : {
						'row_dblclick' : function(i) {
							return function(e, row, data) {
								// alert( "linkman" + mycount );
								$("#customerId" + i).val(data.customerId);
								$("#linkmanId" + i).val(data.id);
								$("#mobileTel" + i).val(data.mobile);
								$("#email" + i).val(data.email);
							};
						}(i)
					}
				}
			});
	// $("#linkmanName"+i).yxcombogrid('grid').reload;
	// }

}

/** *****************隐藏计划******************************* */
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}
/** ********************删除动态表单************************ */
function mydel(obj, mytable) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo - 1);
		var myrows = mytable.rows.length;
		for (i = 0; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i + 1;
		}
	}
}
var mycount = 1;
// ************************客户联系人*************************
function link_add(mypay, countNum) {
	var customerId = $("#customerId").val();
	// if(customerId==""){
	// alert("请选择客户!");
	// $("#customerName").focus();
	// return false;
	// }

	mycount = document.getElementById(countNum).value * 1 + 1;

	var mypay = document.getElementById(mypay);
	i = mypay.rows.length;

	j = i + 1;
	oTR = mypay.insertRow([i]);
	oTR.id = "linkTab_" + i;
	// oTR.id = "linkDetail" + mycount;
	oTL0 = oTR.insertCell([0]);
	oTL0.innerHTML = j;
	oTL1 = oTR.insertCell([1]);
	oTL1.innerHTML = "<input class='txt' type='hidden' name='clues[linkman]["
			+ mycount + "][linkmanId]' id='linkmanId" + mycount
			+ "'/><input class='txt' type='hidden' name='clues[linkman]["
			+ mycount + "][customerId]' id='customerId" + mycount + "'/>"
			+ "<input class='txt' type='text' name='clues[linkman][" + mycount
			+ "][linkmanName]' id='linkmanName" + mycount
			+ "' title='双击可以添加联系人' />";

	/**
	 * 客户联系人
	 */
	$("#linkmanName" + mycount).yxcombogrid_linkman({
				gridOptions : {
					reload : true,
					showcheckbox : false,
					param : {
						'customerId' : $("#customerId").val()
					},
					event : {
						'row_dblclick' : function(mycount) {
							return function(e, row, data) {
								// alert( "linkman" + mycount );
								$("#customerId" + mycount).val(data.customerId);
								$("#linkmanId" + mycount).val(data.id);
								$("#mobileTel" + mycount).val(data.mobile);
								$("#email" + mycount).val(data.email);
							};
						}(mycount)
					}
				}
			});

	oTL2 = oTR.insertCell([2]);
	oTL2.innerHTML = "<input class='txt' type='text' name='clues[linkman]["
			+ mycount + "][mobileTel]' id='mobileTel" + mycount
			+ "'onchange='tel(" + mycount + ")'/>";
	oTL3 = oTR.insertCell([3]);
	oTL3.innerHTML = "<input class='txt' type='text' name='clues[linkman]["
			+ mycount + "][email]' id='email" + mycount + "'onchange='Email("
			+ mycount + ")'/>";
	oTL4 = oTR.insertCell([4]);
	oTL4.innerHTML = "<select class=''  name='clues[linkman][" + mycount
			+ "][roleCode]' id='roleCode" + mycount + "'>" + $("#role").val()
			+ "</select>";
	oTL5 = oTR.insertCell([5]);
	oTL5.innerHTML = "<input class='' type='checkbox' name='clues[linkman]["
			+ mycount + "][isKeyMan]' id='isKeyMan" + mycount + "'/>";
	oTL6 = oTR.insertCell([6]);
	oTL6.innerHTML = "<img src='images/closeDiv.gif' onclick='mydel(this,\""
			+ mypay.id + "\")' title='删除行'>";
	document.getElementById(countNum).value = document.getElementById(countNum).value
			* 1 + 1;
}

// function test(){
// var test = $("#customerNeed").val();
// test=(test+'').replace(/^0+\./g,'0.');
// test.match(/^0+[1-9]+/)?test=test.replace(/^0+/g,''):test;
// alert (test);
//
//
// }

