$(function() {
	var isOnlyCurDept = $("#isOnlyCurDept").val() == "true" ? 1 : 0;
	var deptIds = $("#deptIds").val();
	var tabType = 2;// tab���� 2Ϊ���� 3Ϊ��ɫ ��searchType��Ӧ
	var mode = $("#mode").val();
	var formCode = $("#formCode").val();

	// ��Աѡ����� ��Ա��� update on 2012-6-6 by kuangzw ��ʱ��֧�ֶ�ѡ
	var userNo = $("#userNo").val();
	// ��Աѡ���Ƿ����ְλ��Ϣ
	var isNeedJob = $("#isNeedJob").val();
	// �Ƿ���ʾ��ְ��Ա
	var isShowLeft = $("#isShowLeft").val();

	// ������ѡ����
	if (formCode) {
		$select = $("<select  class='select'><option value=''>��ѡ������...</option></select>");
		$select.change(function() {
					if ($(this).val()) {
						if (mode == 'single') {
							$("#selectedUser").empty();
						}
						var $selected = $("#selectedUser").find('option[value='
								+ $(this).val() + ']');
						if ($selected.size() == 0) {
							var $selected = $(this).find("option:selected");
							var text = $selected.text();
							$option = $("<option value='" + $(this).val()
									+ "'>" + text + "</option>");
							$("#selectedUser").append($option);
							$("#deptId").val($selected.attr("deptId")); // ��ȡ����ID�벿������
							$("#deptName").val($selected.attr("deptName"));
							// ��ȡ��Աְλid update on 2012-6-11 by kuangzw
							$("#jobId").val($selected.attr("jobId")); // ��ȡ��ԱְλId
						}
					}
				});
		// $("#searchTd").append("������Աѡ��");
		$("#searchTd").append($select);
		$.ajax({
			type : 'POST',
			dataType : 'json',
			data : {
				formCode : formCode
			},
			url : "?model=deptuser_user_userselect&action=getCurUserModelSelect",
			success : function(data) {
				for (var i = 0; i < data.length; i++) {
					var d = data[i];
					$option = $("<option deptName='" + d.deptName
							+ "' deptId='" + d.deptId + "' value='"
							+ d.selectUserId + "' jobId='" + d.jobId + "'>"
							+ d.selectUserName + "</option>");
					$select.append($option);
				}
			}
		})
	}
	// ��ֻ��ѡ��ǰ��¼�����ڲ���ʱ�����ء���ɫ��Tab add by suxc 2011-08-18
	if (isOnlyCurDept) {
		$("#jobsTab").hide();
	}

	// ��ȡ���� searchType 1:��Ա 2:����
	function getDeptTreeData(searchType, searchVal) {
		var param = {
			url : "?model=deptuser_user_user&action=deptusertree&isOnlyCurDept="
					+ isOnlyCurDept
					+ "&deptIds="
					+ deptIds
					+ "&isShowLeft="
					+ isShowLeft,
			type : 'POST',
			async : false
		}
		if (searchVal) {
			param.data = {};
			if (searchType == 1) {// ��Ա
				param.data.userName = searchVal;
			} else if (searchType == 2) {// ����
				param.data.deptName = searchVal;
			}

		}
		var data = $.ajax(param).responseText;
		data = eval("(" + data + ")");
		return data;
	}

	// ��ȡ��ɫ����
	function getJobsTreeData(searchType, searchVal) {
		var param = {
			url : "?model=deptuser_user_user&action=jobsusertree&isShowLeft="
					+ isShowLeft,
			type : 'POST',
			async : false
		}
		if (searchVal) {
			param.data = {};
			if (searchType == 1) {// ��Ա
				param.data.userName = searchVal;
			} else {// ��ɫ
				param.data.jobsName = searchVal;
			}

		}
		var data = $.ajax(param).responseText;
		data = eval("(" + data + ")");
		return data;
	}
	var lastSelectNode;
	$("#deptTree").yxtree({
		height : 360,
		// data : getDeptTreeData(),
		url : "?model=deptuser_user_user&action=deptusertree&isOnlyCurDept="
				+ isOnlyCurDept + "&deptIds=" + deptIds + "&isShowLeft"
				+ isShowLeft,
		param : ['id', 'Depart_x', 'Dflag'],
		nameCol : "name",
		event : {
			node_click : function(e, treeId, node) {
				// ����ǵ�ѡ���������ѡ�е�����
				if (mode == 'single' && node.type == 'user') {
					$("#selectedUser").empty();
					$("#deptId").val(node.DEPT_ID); // ��ȡ����ID�벿������
					$("#deptName").val(node.DEPT_NAME);
					// ��ȡ��Աְλid update on 2012-6-11 by kuangzw
					$("#jobId").val(node.jobs_id); // ��ȡ��ԱְλId
					lastSelectNode = node;
				}
				var $selected = $("#selectedUser").find('option[value='
						+ node.id + ']');
				if ($selected.size() == 0 && node.type == 'user') {
					$option = $("<option value='" + node.id + "'>"
							+ node.USER_NAME + "</option>");
					$("#selectedUser").append($option);
				}

			},
			node_dblclick : function(e, treeId, node) {
				if (mode == 'single' && node.type == 'user') {
					$("#confirmButton").click();
				}
			}
		}
	});
	// ��ֹ�ص�
	var offset = $("#deptTree").offset();
	var showOffset = {
		top : offset.top,
		left : offset.left
	};
	var hideOffset = {
		top : 1000,
		left : 1000
	};
	var isFistClickDept = true;// ��һ�ε������tab��ʶ
	var isFistClickJobs = true;// ��һ�ε����ɫtab
	$("#jobsTree").offset(hideOffset);
	$("#jobsTree").hide();
	// �����֯����tab
	$("#deptTab").bind('click', function() {
				if (!isFistClickDept && !isFistClickJobs) {
					tabType = 2;
					$("#deptTree").show();
					$("#deptTree").offset(showOffset);
					$("#jobsTree").offset(hideOffset);
					$("#jobsTree").hide();
				} else {
					isFistClickDept = false;
				}
			});

	$("#jobsTab").bind('click', function() {
		tabType = 3;
		$("#jobsTree").show();
		$("#jobsTree").offset(showOffset);
		$("#deptTree").offset(hideOffset);
		$("#deptTree").hide();

		$("#deptTree").offset();
		// ����ǵ�һ�ε������Ⱦ��ɫ��
		if (isFistClickJobs) {
			isFistClickJobs = false;
			$("#jobsTree").yxtree({
				height : 360,
				url : '?model=deptuser_user_user&action=jobsusertree&isShowLeft'
						+ isShowLeft,
				param : ['id'],
				nameCol : "name",
				event : {
					node_click : function(e, treeId, node) {
						// ����ǵ�ѡ���������ѡ�е�����
						if (mode == 'single' && node.type == 'user') {
							$("#selectedUser").empty();
							$("#deptId").val(node.DEPT_ID); // ��ȡ����ID�벿������
							$("#deptName").val(node.DEPT_NAME);
							// ��ȡ��Աְλid update on 2012-6-11 by kuangzw
							$("#jobId").val(node.jobs_id); // ��ȡ��ԱְλId
							lastSelectNode = node;
						}
						var $selected = $("#selectedUser").find('option[value='
								+ node.id + ']');
						if ($selected.size() == 0 && node.type == 'user') {
							$option = $("<option value='" + node.id + "'>"
									+ node.USER_NAME + "</option>");
							$("#selectedUser").append($option);
						}

					},
					node_dblclick : function(e, treeId, node) {
						if (mode == 'single' && node.type == 'user') {
							$("#confirmButton").click();
						}
					}
				}
			});

		}
	});

	var searchFn = function() {
		var searchVal = $('#searchVal').val();
		var searchType = $('#searchType').val();
		if (searchVal != "") {
			if (searchType == 1) {
				if (tabType == 2) {
					$("#deptTree").yxtree('reloadData',
							getDeptTreeData(searchType, searchVal));
				} else {
					$("#jobsTree").yxtree('reloadData',
							getJobsTreeData(searchType, searchVal));
				}
			} else if (searchType == 2) {// ��������
				if (searchType != tabType) {
					// ���
					$("#deptTab").trigger('click');
				}
				$("#deptTree").yxtree('reloadData',
						getDeptTreeData(searchType, searchVal));
			} else if (searchType == 3) {
				if (searchType != tabType) {// ������ɫ
					// ���
					$("#jobsTab").trigger('click');
				}
				$("#jobsTree").yxtree('reloadData',
						getJobsTreeData(searchType, searchVal));
			}

		}
	}

	$("#searchVal").bind('keyup', function(e) {
				if (e.keyCode == 13) {// �س�
					searchFn();
				}
			});
	// �����¼�
	$("#searchButton").bind('click', searchFn);
	// ��������¼�
	$("#clearButton").bind('click', function() {
				$("#searchVal").val('');
				$("#deptTree").yxtree('reloadData', getDeptTreeData());
				$("#jobsTree").yxtree('reloadData', getJobsTreeData());
			});
	// ���ѡ����Ա
	$("#clearSelectedButton").bind('click', function() {
				$("#selectedUser").empty();
			});

	$("#selectedUser").bind('dblclick', function() {
				$('#selectedUser option:selected').remove();
			});

	// ȷ����ť�¼�
	$("#confirmButton").bind('click', function() {
		var s = $('#selectedUser option').size();
		var valArr = [];
		$('#selectedUser option').each(function() {
					valArr.push($(this).val());
				});
		var textArr = [];
		$('#selectedUser option').each(function() {
					textArr.push($(this).text());
				});

		// ��Աѡ����� ��Ա��� update on 2012-6-6 by kuangzw
		var uesrNoValue = "";
		if (userNo && mode == 'single') {
			$.ajax({
						url : '?model=common_otherdatas&action=getUserNo',
						type : 'POST',
						async : false,
						data : {
							userAccount : valArr.toString()
						},
						success : function(data) {
							uesrNoValue = data;
						}
					});
		}

		// ��Աѡ����� ��Ա��� update on 2012-6-6 by kuangzw
		var jobId = $("#jobId").val();
		var jobName = "";
		if (isNeedJob && mode == 'single') {
			$.ajax({
						url : '?model=common_otherdatas&action=getJobName',
						type : 'POST',
						async : false,
						data : {
							jobId : jobId
						},
						success : function(data) {
							jobName = data;
						}
					});
		}
		var companyCode;
		var companyName;
		if (lastSelectNode) {
			companyCode = lastSelectNode.Company;
			companyName = lastSelectNode.companyName;
		}
		window.returnValue = {
			text : textArr.toString(),
			val : valArr.toString(),
			deptId : $("#deptId").val(),
			deptName : $("#deptName").val(),
			// ��Աѡ����� ��Ա��� update on 2012-6-6 by kuangzw
			userNo : uesrNoValue,
			// ��Աѡ����� ְλ��Ϣ update on 2012-6-11 by kuangzw
			jobId : jobId,
			jobName : jobName,
			companyCode : companyCode,
			companyName : companyName
		};

		// window.returnValue='112aa';
		// ����ѡ����
		if (formCode) {
			$.ajax({
				url : '?model=deptuser_user_userselect&action=saveSelectedUser',
				type : 'POST',
				data : {
					selectedUserIds : valArr.toString(),
					selectedUserNames : textArr.toString(),
					formCode : formCode
				}
			});
		}
		window.close();
	});

	// �����ѡ��ֵ������������ѡ�еĽڵ���õ��ұ�ѡ�������
	var userVal = $("#userVal").val();
	if (userVal != "") {
		var userArr = userVal.split(",");
		for (var i = 0; i < userArr.length; i++) {
			var v = userArr[i];
			if (v) {
				$.ajax({
							type : 'POST',
							url : "?model=deptuser_user_user&action=getUserName",
							async : false,
							data : "userId=" + v,
							success : function(data) {
								$option = $("<option value='" + v + "'>" + data
										+ "</option>");
								$("#selectedUser").append($option);
							}
						});
			}
		}
	}
});