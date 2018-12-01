<?php

namespace SV\ReportImprovements\XF\Entity;

use SV\ReportImprovements\Globals;
use XF\Mvc\Entity\Structure;

/**
 * Class User
 *
 * Extends \XF\Entity\User
 *
 * @package SV\ReportImprovements\XF\Entity
 */
class User extends XFCP_User
{
    /**
     * @param null $error
     *
     * @return bool
     */
    public function canViewReports(/** @noinspection PhpUnusedParameterInspection */
        &$error = null)
    {
        $visitor = \XF::visitor();

        if (!$visitor->user_id)
        {
            return false;
        }

        return $visitor->hasPermission('general', 'viewReports');
    }

    /**
     * @param null $error
     *
     * @return bool
     */
    public function canViewConversationMessageReport(/** @noinspection PhpUnusedParameterInspection */
        &$error = null)
    {
        $visitor = \XF::visitor();

        if (!$visitor->user_id)
        {
            return false;
        }

        return $visitor->hasPermission('general', 'viewReportConversation');
    }

    /**
     * @param null $error
     *
     * @return bool
     */
    public function canViewProfilePostCommentReport(&$error = null)
    {
        return $this->canViewProfilePostReport($error);
    }

    /**
     * @param null $error
     *
     * @return bool
     */
    public function canViewProfilePostReport(/** @noinspection PhpUnusedParameterInspection */
        &$error = null)
    {
        $visitor = \XF::visitor();

        if (!$visitor->user_id)
        {
            return false;
        }

        return $visitor->hasPermission('general', 'viewReportProfilePost');
    }

    /**
     * @param null $error
     *
     * @return bool
     */
    public function canViewUserReport(/** @noinspection PhpUnusedParameterInspection */
        &$error = null)
    {
        $visitor = \XF::visitor();

        if (!$visitor->user_id)
        {
            return false;
        }

        return $visitor->hasPermission('general', 'viewReportUser');
    }

    /**
     * @param int  $nodeId
     * @param null $error
     * @return bool
     */
    public function canViewPostReport($nodeId,/** @noinspection PhpUnusedParameterInspection */
        &$error = null)
    {
        if (!$this->hasNodePermission($nodeId, 'viewReportPost'))
        {
            return false;
        }

        if (\XF::options()->sv_moderators_respect_view_node)
        {
            /** @var \XF\Entity\Forum $forum */
            $forum = \XF::app()->find('XF:Forum', $nodeId);
            if ($forum && !$forum->canView())
            {
                return false;
            }
        }

        return true;
    }

    /**
     * @param null $error
     *
     * @return bool
     */
    public function canViewReporter(/** @noinspection PhpUnusedParameterInspection */
        &$error = null)
    {
        if (!$this->user_id)
        {
            return false;
        }

        return $this->hasPermission('general', 'viewReporterUsername');
    }
}