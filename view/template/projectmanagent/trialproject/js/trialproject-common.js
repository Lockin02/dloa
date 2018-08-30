/**
 * 返回项目编号串
 * @param projectCodes
 * @returns {*}
 */
function initProjectCode(projectCodes) {
    if (projectCodes == "") {
        return "";
    }
    var projectCodeArr = projectCodes.split(',');
    var htmlArr = [];
    for (var i = 0; i < projectCodeArr.length; i++) {
        htmlArr.push('<a href="javascript:void(0);" onclick="viewProjectByCode(\'' + projectCodeArr[i] + '\');">' + projectCodeArr[i] + '</a>');
    }
    return htmlArr.toString();
}

/**
 * 根据项目编号查看项目
 * @param projectCode
 */
function viewProjectByCode(projectCode) {
    $.ajax({
        url: '?model=projectmanagent_trialproject_trialproject&action=getESMProjectIdByCode',
        data: {projectCode: projectCode},
        type: 'POST',
        success: function (msg) {
            var id = msg;
            $.ajax({
                type: "POST",
                url: "?model=engineering_project_esmproject&action=md5RowAjax",
                data: {id: id},
                async: false,
                success: function (data) {
                    window.open("?model=engineering_project_esmproject&action=viewTab&id="
                        + id + "&skey=" + data
                    );
                }
            });
        }
    });
}