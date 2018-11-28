<?php

namespace SV\ReportImprovements\XF\Report;

use SV\ReportImprovements\Report\ContentInterface;
use XF\Entity\Report;

/**
 * Class User
 *
 * Extends \XF\Report\User
 *
 * @package SV\ReportImprovements\XF\Report
 */
class User extends XFCP_User
{
    /**
     * @param Report $report
     *
     * @return bool
     */
    public function canView(Report $report)
    {
        /** @var \SV\ReportImprovements\XF\Entity\User $visitor */
        $visitor = \XF::visitor();

        return $visitor->canViewUserReport();
    }

    /**
     * @param Report $report
     *
     * @return \XF\Phrase
     */
    public function getContentTitle(Report $report)
    {
        if ((\XF::$versionId > 2001170 && \XF::$versionId < 2010000) || \XF::$versionId > 2010032)
        {
            return parent::getContentTitle($report);
        }

        // patch an XF2.0.12 bug
        $content = $report->content_info;

        if (isset($content['username']))
        {
            $name = $content['username'];
        }
        else if (isset($content['user']['username']))
        {
            $name = $content['user']['username'];
        }
        else
        {
            $name = \XF::phrase('unknown_user');
        }

        return \XF::phrase('member_x', [
            'username' => $name
        ]);
    }
}