<?php

/**
 * Interface iesmfieldrecord
 */
interface iesmfieldrecord
{
    function init_d($esmFieldRecordDao, $category);

    function feeUpdate_d($esmFieldRecordDao, $category, $year, $month, $projectId);

    function feeList_d($esmFieldRecordDao, $category, $year, $month, $sourceParam);
}