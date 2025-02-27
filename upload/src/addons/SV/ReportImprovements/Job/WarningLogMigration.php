<?php

namespace SV\ReportImprovements\Job;

use SV\ReportImprovements\Globals;
use XF\Job\AbstractRebuildJob;

/**
 * Class WarningLogMigration
 *
 * @package SV\ReportImprovements\Job
 */
class WarningLogMigration extends AbstractRebuildJob
{
    /**
     * @param int $start
     * @param int $batch
     * @return array
     */
    protected function getNextIds($start, $batch)
    {
        $db = $this->app->db();

        return $db->fetchAllColumn($db->limit(
            '
				SELECT warning_id
				FROM xf_warning
				WHERE warning_id > ?
				  AND NOT exists (SELECT warning_id FROM xf_sv_warning_log WHERE xf_sv_warning_log.warning_id = xf_warning.warning_id)
				ORDER BY warning_id
			', $batch
        ), [$start]);
    }

    /**
     * @param int $id
     * @throws \Exception
     */
    protected function rebuildById($id)
    {
        /** @var \SV\ReportImprovements\XF\Entity\Warning $warning */
        $warning = $this->app->em()->find('XF:Warning', $id, ['User', 'WarnedBy']);
        if ($warning !== null)
        {
            // On a detached thread, just pretend the post no longer exists
            $content = $warning->Content ?? null;
            if ($content instanceof \XF\Entity\Post && $content->Thread === null)
            {
                $warning->setContent(null);
            }
            Globals::$expiringFromCron = false;
            $user = $warning->WarnedBy;
            if (!$user)
            {
                $expireUserId = (int)(\XF::options()->svReportImpro_ExpireUserId ?? 1);
                $user = \XF::app()->find('XF:User', $expireUserId);
                if (!$user)
                {
                    $user = \XF::app()->find('XF:User', 1);
                }
                if (!$user && $warning->User)
                {
                    $user = $warning->User;
                }
            }

            \XF::asVisitor($user, function () use ($warning) {
                $time = \XF::$time;
                \XF::$time = $warning->warning_date;
                try
                {
                    /** @var \SV\ReportImprovements\Service\WarningLog\Creator $warningLogCreator */
                    $warningLogCreator = \XF::app()->service('SV\ReportImprovements:WarningLog\Creator', $warning, 'new');
                    $warningLogCreator->setAutoResolve(true, false, '');
                    $warningLogCreator->setCanReopenReport(false);
                    $warningLogCreator->setAutoResolveNewReports(true);
                    if ($warningLogCreator->validate($errors))
                    {
                        $warningLogCreator->save();
                    }
                }
                catch (\Exception $e)
                {
                    // setupReportEntityContent can throw if the child content is detached from the parent
                    // but there is really no sane way to detect this ahead of time
                    if (\stripos($e->getMessage(), 'Attempt to read property') !== false)
                    {
                        \XF::logException($e);

                        return;
                    }
                    throw $e;
                }
                finally
                {
                    \XF::$time = $time;
                }
            });
        }
    }

    /**
     * @return null
     */
    protected function getStatusType()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getStatusMessage()
    {
        $actionPhrase = \XF::phrase('svReportImprov_migrating');

        return sprintf('%s... (%s)', $actionPhrase, $this->data['start']);
    }
}