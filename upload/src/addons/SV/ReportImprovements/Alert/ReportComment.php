<?php

namespace SV\ReportImprovements\Alert;

use XF\Alert\AbstractHandler;
use XF\Entity\UserAlert;
use XF\Mvc\Entity\Entity;

/**
 * Class ReportComment
 *
 * @package SV\ReportImprovements\Alert
 */
class ReportComment extends AbstractHandler
{
    /**
     * @param Entity|\SV\ReportImprovements\XF\Entity\ReportComment $entity
     * @param null   $error
     *
     * @return bool
     */
    public function canViewContent(Entity $entity, &$error = null)
    {
        $report = $entity->Report;
        if (!$report)
        {
            return false;
        }

        return $report->canView();
    }

    /**
     * @return array
     */
    public function getOptOutActions()
    {
        /** @var \SV\ReportImprovements\XF\Entity\User $visitor */
        $visitor = \XF::visitor();

        if (!$visitor->canViewReports())
        {
            return [];
        }

        return [
            'insert',
            'like'
        ];
    }

    /**
     * @return int
     */
    public function getOptOutDisplayOrder()
    {
        return 42000;
    }

    /**
     * @return array
     */
    public function getEntityWith()
    {
        return ['Report'];
    }
}