$(function() {
			var mode = $("#mode").val();
			// 获取数据
			function getTreeData(searchVal) {
				var param = {
					url : "?model=deptuser_dept_dept&action=alltree",
					type : 'POST',
					async : false
				}
				if (searchVal!="") {
					param.data = {
						deptName : searchVal
					};
				}
				var data = $.ajax(param).responseText;
				data = eval("(" + data + ")");
				return data;
			}

			$("#tree").yxtree({
				height : 360,
				url : "?model=deptuser_dept_dept&action=tree",
				// data : getTreeData(),
				param : ['id', 'Depart_x', 'Dflag'],
				nameCol : "DEPT_NAME",
				event : {
					node_click : function(e, treeId, node) {
						// 如果是单选，清除所有选中的数据
						if (mode == 'single') {
							$("#selectedDept").empty();
						}
						var $selected = $("#selectedDept").find('option[value='
								+ node.id + ']');
						if ($selected.size() == 0) {
							$option = $("<option value='" + node.id + "'>"
									+ node.DEPT_NAME + "</option>");
							$("#selectedDept").append($option);
						}

					}
				}
			});
			var searchFn=function() {
						var searchVal = $('#searchVal').val();
						if (searchVal == '') {
							$("#tree").yxtree('reload');
						} else {
							$("#tree").yxtree('reloadData',
									getTreeData(searchVal));
						}
					}
			$("#searchVal").bind('keyup',function(e){
				if(e.keyCode==13){//回车
					searchFn();
				}
			});
			// 搜索事件
			$("#searchButton").bind('click', searchFn);
			// 清除搜索事件
			$("#clearButton").bind('click', function() {
						$("#searchVal").val('');
						//$("#tree").yxtree('reloadData', getTreeData(''));
						$("#tree").yxtree('reload');
					});
			// 清除选中机构
			$("#clearSelectedButton").bind('click', function() {
						$("#selectedDept").empty();
					});

			$("#selectedDept").bind('dblclick', function() {
						$('#selectedDept option:selected').remove();
					});

			// 确定按钮事件
			$("#confirmButton").bind('click', function() {
						var s = $('#selectedDept option').size();
						var valArr = [];
						$('#selectedDept option').each(function() {
									valArr.push($(this).val());
								});
						var textArr = [];
						$('#selectedDept option').each(function() {
									textArr.push($(this).text());
								});
						window.returnValue = {
							text : textArr.toString(),
							val : valArr.toString()
						};
						window.close();
					});

			// 如果有选择值，遍历树，把选中的节点放置到右边选中面板上
			var deptVal = $("#deptVal").val();
			if (deptVal != "") {
				var deptArr = deptVal.split(",");
				for (var i = 0; i < deptArr.length; i++) {
					var v = deptArr[i];
					if (v) {
						//ajax获取部门名称
						$.ajax({
							type:'POST',
							url:"?model=deptuser_dept_dept&action=getDeptName",
							async:false,
							data:"deptId="+v,
							success:function(data){
									$option = $("<option value='" + v + "'>"
											+ data + "</option>");
									$("#selectedDept").append($option);
							}
						});
					}
				}
			}
		});