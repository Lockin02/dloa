$(function() {
		//申请人下拉
		$("#applyUserName").yxselect_user({
				hiddenId : 'applyUserCode'

				});
		});

	/**
	 *设备更换带出设备信息
	 */
		function reloadChangeCombo() {
				$("#relDocCode").yxcombogrid_reduce({
						hiddenId : 'relDocId',
						nameCol : 'relDocCode',
						width : 600,
						isShowButton : false,
						event : {
							'clearValue' : function() {
								$("#customerName").val("");
								$("#adress").val("");
								$("#applyUserName").val("");
							}
						},
						gridOptions : {
							isShowButton : true,
							showcheckbox : false,
							event : {
								'row_dblclick' : function(e, row, data) {
									$("#relDocCode").val(data.docCode);
									$("#customerName").val(data.customerName);
									$("#adress").val(data.adress);
									$("#applyUserName").val(data.applyUserName);

									$.post("?model=service_repair_repairapply&action=getItemStrAtChange",
											{
												changeapplyId : data.id
											}, function(result) {

												if (result != "") {

													$("#itembody").html("");
													$("#itembody").append(result);
													$("#itemscount").val($("#itembody tr").size());
												} else {
													alert("该发货单中没有物料信息，请重新选择！")
												}

											});

								 }

							}
						}
				});
		}