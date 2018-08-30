$(function() {
			var mode = $("#mode").val();
			// ��ȡ����
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
						// ����ǵ�ѡ���������ѡ�е�����
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
				if(e.keyCode==13){//�س�
					searchFn();
				}
			});
			// �����¼�
			$("#searchButton").bind('click', searchFn);
			// ��������¼�
			$("#clearButton").bind('click', function() {
						$("#searchVal").val('');
						//$("#tree").yxtree('reloadData', getTreeData(''));
						$("#tree").yxtree('reload');
					});
			// ���ѡ�л���
			$("#clearSelectedButton").bind('click', function() {
						$("#selectedDept").empty();
					});

			$("#selectedDept").bind('dblclick', function() {
						$('#selectedDept option:selected').remove();
					});

			// ȷ����ť�¼�
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

			// �����ѡ��ֵ������������ѡ�еĽڵ���õ��ұ�ѡ�������
			var deptVal = $("#deptVal").val();
			if (deptVal != "") {
				var deptArr = deptVal.split(",");
				for (var i = 0; i < deptArr.length; i++) {
					var v = deptArr[i];
					if (v) {
						//ajax��ȡ��������
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