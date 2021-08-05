<?php

namespace SV\ReportImprovements\Entity;

use XF\Entity\Report;
use XF\Entity\User;

/**
 * @property Report Report
 */
interface IReportResolver
{
    public function canResolveLinkedReport(): bool;

    /**
     * @return User|null
     */
    public function getResolveUser();

    /**
     * @param bool   $resolveReport
     * @param bool   $alert
     * @param string $alertComment
     * @return Report|null
     */
    public function resolveReportFor(bool $resolveReport, bool $alert, string $alertComment);
}