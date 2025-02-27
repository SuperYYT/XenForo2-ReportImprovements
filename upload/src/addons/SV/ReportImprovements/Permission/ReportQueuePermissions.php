<?php

namespace SV\ReportImprovements\Permission;

use SV\ReportCentreEssentials\Entity\ReportQueue as ReportQueueEntity;
use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\Entity\Entity;

class ReportQueuePermissions extends \XF\Permission\FlatContentPermissions
{
    protected $privatePermissionGroupId = 'general';
    protected $privatePermissionId = 'viewReports';

    protected function getContentType(): string
    {
        return 'report_queue';
    }

    public function getAnalysisTypeTitle(): \XF\Phrase
    {
        return \XF::phrase('svReportImprovements_report_queue_permissions');
    }

    public function getContentList(): AbstractCollection
    {
        /** @var \SV\ReportImprovements\Repository\ReportQueue $entryRepo */
        $entryRepo = $this->builder->em()->getRepository('SV\ReportImprovements:ReportQueue');
        return $entryRepo->getReportQueueList();
    }

    public function getContentTitle(Entity $entity): string
    {
        /** @var ReportQueueEntity $entity */
        return $entity->queue_name;
    }

    public function rebuildCombination(\XF\Entity\PermissionCombination $combination, array $basePerms)
    {
        // being notified on bulk permission changes is surprisingly challenging

        /** @var \SV\ReportImprovements\XF\Repository\Report $repo */
        $repo = \XF::repository('XF:Report');
        if (\is_callable([$repo, 'deferResetNonModeratorsWhoCanHandleReportCache']))
        {
            $repo->deferResetNonModeratorsWhoCanHandleReportCache();
        }

        parent::rebuildCombination($combination, $basePerms);
    }

    public function isValidPermission(\XF\Entity\Permission $permission): bool
    {
        return $permission->permission_group_id === 'report_queue' ||
               ($permission->permission_group_id === $this->privatePermissionGroupId && $permission->permission_id === $this->privatePermissionId);
    }

    protected function getFinalPerms($contentId, array $calculated, array &$childPerms): array
    {
        if (!isset($calculated['report_queue']))
        {
            $calculated['report_queue'] = [];
        }

        $final = $this->builder->finalizePermissionValues($calculated['report_queue']);
        if ($this->privatePermissionGroupId !== 'report_queue')
        {
            $final = $final + $this->builder->finalizePermissionValues($calculated[$this->privatePermissionGroupId]);
        }

        if (empty($final[$this->privatePermissionId]))
        {
            $childPerms[$this->privatePermissionGroupId][$this->privatePermissionId] = 'deny';
            $final = [];
        }

        return $final;
    }

    protected function getFinalAnalysisPerms($contentId, array $calculated, array &$childPerms): array
    {
        $final = $this->builder->finalizePermissionValues($calculated);

        if (empty($final[$this->privatePermissionGroupId][$this->privatePermissionId]))
        {
            $childPerms[$this->privatePermissionGroupId][$this->privatePermissionId] = 'deny';
        }

        return $final;
    }
}