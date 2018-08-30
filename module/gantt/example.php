<?php

require_once "BURAK_Gantt.class.php";

$g = new BURAK_Gantt();
// set grid type
$g->setGrid(1);
// set Gantt colors
$g->setColor("group","000000");
$g->setColor("progress","660000");
// add holidays
$g->addHoliday("2006-12-09");
// add groups
$g->addGroup("G1","Group 1");
$g->addGroup("G2","Group 2","G1");
$g->addGroup("G3","Group 3","G2");
$g->addGroup("G4","Group 3","G3");
// add tasks
// 任务ID ，开始日期，结束日期，任务完成率，任务名称,所属计划
$g->addTask("A","2006-12-02","2006-12-05",100,"Task A","G1");
$g->addTask("B","2006-12-06","2006-12-08",100,"Task B","G1");
$g->addTask("C","2006-12-09","2006-12-12",100,"Task C","G2");
$g->addTask("D","2006-12-13","2006-12-14",100,"Task D","G2");
$g->addTask("E","2006-12-15","2007-01-22",1,"Task E","G1");
$g->addTask("F","2006-12-13","2006-12-19",30,"Task F");
$g->addTask("G","2006-12-11","2006-12-19",40,"Task G");
// add milestones
$g->addMilestone("M1","2006-12-15","Milestone 1","G2");
$g->addMilestone("M2","2007-01-23","Milestone 2");
// add relations
// add end-to-start relations
$g->addRelation("A","B","ES");
$g->addRelation("B","C","ES");
$g->addRelation("C","D","ES");
$g->addRelation("M1","E","ES");
$g->addRelation("D","M1","ES");
$g->addRelation("E","M2","ES");
// add start-to-start relation
$g->addRelation("D","F","SS");
// add end-to-end relation
$g->addRelation("F","G","EE");
// ouptput Gantt image
$g->outputGantt();

?>