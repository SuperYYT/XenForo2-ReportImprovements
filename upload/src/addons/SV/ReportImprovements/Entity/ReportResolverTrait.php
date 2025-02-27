<?php

namespace SV\ReportImprovements\Entity;

use SV\ReportImprovements\Behavior\ReportResolver;
use SV\ReportImprovements\XF\Entity\Report;
use SV\ReportImprovements\XF\Entity\User;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

trait ReportResolverTrait
{
    public function canResolveLinkedReport(): bool
    {
        /** @var Report|null $report */
        $report = $this->getRelation('Report');
        if ($report === null)
        {
            /** @var User $visitor */
            $visitor = \XF::visitor();

            return $visitor->canViewReports();
        }

        return $report->canView() && $report->canUpdate($error);
    }

    /**
     * @param bool   $resolveReport
     * @param bool   $alert
     * @param string $alertComment
     * @return \XF\Entity\Report|null
     */
    public function resolveReportFor(bool $resolveReport, bool $alert, string $alertComment)
    {
        /** @var ReportResolver $behavior */
        $behavior = $this->getBehavior('SV\ReportImprovements:ReportResolver');
        return $behavior->resolveReportFor($resolveReport, $alert, $alertComment);
    }

    public static function addReportResolverStructureElements(Structure $structure, array $reportRelationConditions, array $behaviourOptions)
    {
        $structure->relations['Report'] = [
            'entity'     => 'XF:Report',
            'type'       => Entity::TO_ONE,
            'conditions' => $reportRelationConditions,
        ];

        $structure->options['svCanReopenReport'] = true;
        $structure->options['svResolveReport'] = false;
        $structure->options['svResolveReportAlert'] = false;
        $structure->options['svResolveReportAlertComment'] = '';

        $structure->options['svLogWarningChanges'] = true;
        $structure->options['svPublicBanner'] = null;
        $structure->behaviors['SV\ReportImprovements:ReportResolver'] = $behaviourOptions;
    }
}