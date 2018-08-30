// 取得当前时间
// <script type="text/javascript">
// $("#payTime").val(formatDate(new Date()));
// </script>

/** ****************合同 区域负责人 --- 合同归属区域************************************** */
var mycount = 1;
$(function() {
			$("#areaName").yxcombogrid_area({
						hiddenId : 'areaCode',
						gridOptions : {
							showcheckbox : false,
							event : {
								'row_dblclick' : function(e, row, data) {
									$("#areaPrincipal").val(data.areaPrincipal);
									$("#areaCode").val(data.id);
									$("#areaPrincipalId_v")
											.val(data.areaPrincipalId);
								}
							}
						}
					});
			// 编辑页面时加载联系人下拉列表
			mycount = $('#linkNum').val();
			for (var i = 1; i <= mycount; i++) {
				$("#linkmanName" + i).yxcombogrid_linkman({
							hiddenId : 'linkmanId' + i,
							gridOptions : {
								reload : true,
								showcheckbox : false,
								param : {
									'customerId' : $("#customerId").val()
								},
								event : {
									'row_dblclick' : function(i) {
										return function(e, row, data) {
											$("#customerId" + i)
													.val(data.customerId);
											$("#mobileTel" + i)
													.val(data.mobile);
										}
									}(i)
								}
							}
						});
			}

			$("#customerProvince").yxcombogrid_province({
						hiddenId : 'clustomerProvinceId',
						gridOptions : {
							showcheckbox : false
						}
					});
			// 组织机构人员选择
			$("#trackman").yxselect_user({
						hiddenId : 'trackmanId',

						mode : 'check'
					});

			$("#provincecity").yxcombogrid_province({
						hiddenId : 'provinceId',
						gridOptions : {
							showcheckbox : false
						}
					});

			$("#customerName").yxcombogrid_customer({
				hiddenId : 'customerId',
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

							// $("#linkmanName1").yxcombogrid_linkman("showCombo");
							// $("#mylink").html("");
							// $("#mylink").html(html);
							for (var i = 1; i <= mycount; i++) {
								$("#linkmanName" + i)
										.yxcombogrid_linkman("remove");
								$("#linkmanName" + i).yxcombogrid_linkman({
									hiddenId : 'linkmanId' + i,
									gridOptions : {
										reload : true,
										showcheckbox : false,
										param : {
											'customerId' : $("#customerId")
													.val()
										},
										event : {
											'row_dblclick' : function(i) {
												return function(e, row, data) {
													$("#customerId" + i)
															.val(data.customerId);
													$("#mobileTel" + i)
															.val(data.mobile);
												}
											}(i)
										}
									}
								});
							}
							// $("#customerLinkman").yxcombogrid('grid').param={}
							// $("#customerLinkman").yxcombogrid('grid').reload;
						}
					}
				}
			});
			// customerId = $("#customerId").val()
			// $("#customerId").val(customerId)
			$("#customerLinkman").yxcombogrid_linkman({
						hiddenId : 'customerLinkmanId',
						gridOptions : {
							reload : true,
							showcheckbox : false,
							// param : param,
							event : {
								'row_dblclick' : function(e, row, data) {
									// alert( $('#customerId').val() );
									// unset($('#customerId'));
									$("#customerName").val(data.customerName);
									$("#customerId").val(data.customerId);
									$("#customerTel").val(data.mobile);
									$("#customerEmail").val(data.email);
								}
							}
						}
					});

			trackTypeArr = getData('GZLX');
			addDataToSelect(trackTypeArr, 'trackType');

		});

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
// ************************客户联系人*************************
function link_add(mypay, countNum) {
	var customerId = $("#customerId").val();
	if (customerId == "") {
		alert("请选择客户!");
		$("#customerName").focus();
		return false;
	}

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
