<?php

namespace SV\ReportImprovements\XF\Entity;

use XF\Mvc\Entity\Structure;

/**
 * Class ReportComment
 *
 * Extends \XF\Entity\ReportComment
 *
 * @package SV\ReportImprovements\XF\Entity
 *
 * COLUMNS
 * @property int likes
 * @property array like_users
 *
 * RELATIONS
 * @property \XF\Entity\LikedContent[] Likes
 */
class ReportComment extends XFCP_ReportComment
{
    /**
     * @param Structure $structure
     *
     * @return Structure
     */
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->behaviors['XF:Likeable'] = [];
        $structure->relations['Likes'] = [
            'entity' => 'XF:LikedContent',
            'type' => self::TO_MANY,
            'conditions' => [
                ['content_type', '=', 'report_comment'],
                ['content_id', '=', '$report_comment_id']
            ],
            'key' => 'like_user_id',
            'order' => 'like_date'
        ];

        return $structure;
    }

    /**
     * @param null $error
     *
     * @return bool
     */
    public function canLike(&$error = null)
    {
        $visitor = \XF::visitor();
        if (!$visitor->user_id)
        {
            return false;
        }

        if ($this->Report->isClosed())
        {
            return false;
        }

        if ($this->user_id === $visitor->user_id)
        {
            $error = \XF::phraseDeferred('liking_own_content_cheating');
            return false;
        }

        return $visitor->hasPermission('general', 'reportLike');
    }
}