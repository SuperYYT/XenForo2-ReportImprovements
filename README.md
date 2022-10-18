# Report Improvements 举报改进

XenForo举报系统的大量改进。

Note; 当报告被发送到论坛，警告<->举报链接无法创建!

- 通过减少储存 XenForo N+1 查询行为来提高举报中心的性能
- 基于权限访问举报中心:
 - 为用户组（全局/内容）设置默认权限，用于具有警告或编辑基本配置文件权限的用户组。
 - 新权限：
   - 查看举报中心
   - 评论公开举报
   - 评论已关闭举报
   - 更新举报状态
   - 分配举报
   - 查看举报者用户名
- 向评论/举报过举报的版主发送提醒。
  - 只有在未查看上一个提醒时才会发送提醒。
  - 举报提醒链接到较长的举报的实际评论。
  - 举报提醒包括举报的标题。
- 如果报告被分配给版主，将通知版主
- 将警告链接到举报。
  - Visible from the warning itself, and when issuing warnings against content.
- Link reply bans to reports
  - Log reply bans into report system
  - Optional Issue a reply-ban on issuing a warning (default disabled)
  - Allow reply-banning through a closed threads for moderators
- Link Reports to Warnings.
  - Logs changes to Warnings (add/edit/delete), and associates them with a report.
- Automatically create a report for a warning.
- When issuing a Warning, option to resolve any linked report.
- Optional ability to log warnings into reports when they expire. This does not disrupt who the report was assigned to, and does not re-open the report.
- Report Comment Reactions.
- Resolved Report Alerts are logged into Report Comments (as an explicit field).
- Search report comments
- Optional ability to search report comments by associated warning points, and warned user. (Requires Enhanced Search Improvements add-on)
- Reverse order of report comments (default disabled)
- Optional auto-reject/resolve sufficiently old reports (default disabled)
- Show content date when viewing a report
- Show forum for post reports in report list
- Permission-based ability to join a reported conversation
