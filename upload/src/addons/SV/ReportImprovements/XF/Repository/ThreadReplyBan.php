<?php

namespace SV\ReportImprovements\XF\Repository;

/**
 * Class ThreadReplyBan
 * 
 * Extends \XF\Repository\ThreadReplyBan
 *
 * @package SV\ReportImprovements\XF\Repository
 */
class ThreadReplyBan extends XFCP_ThreadReplyBan
{
    /**
     * @param \XF\Entity\ThreadReplyBan|\SV\ReportImprovements\XF\Entity\ThreadReplyBan $threadReplyBan
     * @param                           $type
     */
    public function logToReport(\XF\Entity\ThreadReplyBan $threadReplyBan, $type)
    {
        /** @var \SV\ReportImprovements\Service\WarningLog\Creator $warningLogCreator */
        $warningLogCreator = $this->app()->service('SV\ReportImprovements:WarningLog\Creator', $threadReplyBan, $type);
        if ($warningLogCreator->validate($errors))
        {
            $warningLogCreator->save();
        }
    }
}