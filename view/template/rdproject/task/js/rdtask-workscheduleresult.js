//单击查看相关日志i
function readWorklog(taskId,executionDate,createId){
//	showOpenWin('?model=rdproject_worklog_rdworklog&action=worklogListByTask&taskId=' + taskId + "&worklogDate=" + worklogDate);
	showThickboxWin("?model=rdproject_worklog_rdworklog"
		+ "&action=viewWorkLogByIdAndDate"
		+ "&taskId=" + taskId
		+ "&executionDate=" + executionDate
		+ "&createId=" + createId
		+ "&placeValuesBefore&TB_iframe=true&modal=false&height=550"
		+ "&width=900");
}