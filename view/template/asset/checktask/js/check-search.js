
 $(function() {

				$("#taskNo").yxcombogrid_checktask({
				hiddenId : 'taskId',
				width : 600,
				isShowButton : false,
				gridOptions : {
					isShowButton : true,
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
                            $("#deptId").val(data.deptId);
							$("#dept").val(data.deptName);
						}
					}
				}
			});


	});

