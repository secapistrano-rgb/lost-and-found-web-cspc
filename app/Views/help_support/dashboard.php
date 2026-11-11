<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
CSPC Lost & Found - Help & Support
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Typography classes */
    .text-display {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-weight: 700;
        letter-spacing: -0.025em;
    }

    .text-subheading {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-weight: 500;
    }

    .text-caption {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-weight: 400;
    }

    .help-support-page {
        background-color: var(--cspc-light);
    }

    /* Glass card styling matching email management */
    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .glass-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    /* Statistics cards matching email management style */
    .stat-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.9));
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .stat-card.card-open {
        background: linear-gradient(135deg, rgba(255, 167, 38, 0.8), rgba(251, 140, 0, 0.9));
    }

    .stat-card.card-progress {
        background: linear-gradient(135deg, rgba(255, 107, 107, 0.8), rgba(238, 90, 36, 0.9));
    }

    /* Add gradient top border like email management */
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
    }

    .stat-card.card-open::before {
        background: linear-gradient(90deg, #ffa726, #fb8c00);
    }

    .stat-card.card-progress::before {
        background: linear-gradient(90deg, #ff6b6b, #ee5a24);
    }

    .stat-icon {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        opacity: 0.8;
    }

    .stat-count {
        font-size: 2rem;
        font-weight: 700;
        margin: 0.5rem 0;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.875rem;
        margin: 0.25rem 0 0 0;
        opacity: 0.9;
        font-weight: 500;
    }

    .action-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .action-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, #a8c8ec 0%, #d4a5d8 100%);
    }

    .recent-tickets-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .recent-tickets-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .recent-tickets-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, #a8c8ec 0%, #d4a5d8 100%);
    }

    .ticket-item {
        padding: 1rem;
        border-radius: 0.5rem;
        background: #f8f9fa;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    .ticket-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-open {
        background: #fff3cd;
        color: #856404;
    }

    .status-in_progress {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-resolved {
        background: #d4edda;
        color: #155724;
    }

    .btn-action {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, #4b5563, #374151);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
        color: white;
        text-decoration: none;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #374151;
        min-width: 150px;
    }

    .info-value {
        color: #6b7280;
        text-align: right;
        flex: 1;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mg-page mg-support">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mg-page-header glass-card backdrop-blur-md bg-white/90 border border-white/20 rounded-2xl shadow-xl mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between p-6 space-y-4 lg:space-y-0">
            <div class="space-y-3">
                <p class="mg-eyebrow">COMMUNITY SUPPORT</p>
                    <h1 class="text-display text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight bg-gradient-to-r from-gray-900 via-blue-900 to-purple-900 bg-clip-text text-transparent">Help & Support</h1>
                <p class="text-subheading text-base sm:text-lg text-gray-600 font-medium max-w-2xl">Track incoming requests and help students get back on track.</p>
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span class="text-caption text-xs">Live Updates</span>
                    </div>
                    <div class="text-caption text-xs">Last Updated: <?= date('M d, Y \a\t g:i A') ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="mg-stat-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="mg-stat-card bg-gradient-to-br from-blue-50/80 to-blue-100/80 backdrop-blur-xl rounded-xl shadow-lg border border-white/30 p-6 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 relative overflow-hidden animate-float" style="animation-delay: 0.5s;">
            <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 via-blue-300 to-transparent"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-700 mb-1">Total Tickets</p>
                    <p class="text-2xl font-bold text-blue-900"><?= $total_tickets ?? 0 ?></p>
                    <p class="text-xs text-blue-600">Support requests</p>
                </div>
                <div class="bg-white/40 backdrop-blur-sm rounded-xl p-3 shadow-inner">
                    <i class="bi bi-ticket text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="mg-stat-card bg-gradient-to-br from-orange-50/80 to-orange-100/80 backdrop-blur-xl rounded-xl shadow-lg border border-white/30 p-6 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 relative overflow-hidden animate-float" style="animation-delay: 0.7s;">
            <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-orange-400 via-orange-300 to-transparent"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-orange-700 mb-1">Open Tickets</p>
                    <p class="text-2xl font-bold text-orange-900"><?= $open_tickets ?? 0 ?></p>
                    <p class="text-xs text-orange-600">Awaiting response</p>
                </div>
                <div class="bg-white/40 backdrop-blur-sm rounded-xl p-3 shadow-inner">
                    <i class="bi bi-ticket-perforated text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="mg-stat-card bg-gradient-to-br from-red-50/80 to-red-100/80 backdrop-blur-xl rounded-xl shadow-lg border border-white/30 p-6 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 relative overflow-hidden animate-float" style="animation-delay: 1s;">
            <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-400 via-red-300 to-transparent"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-700 mb-1">In Progress</p>
                    <p class="text-2xl font-bold text-red-900"><?= $in_progress_tickets ?? 0 ?></p>
                    <p class="text-xs text-red-600">Being resolved</p>
                </div>
                <div class="bg-white/40 backdrop-blur-sm rounded-xl p-3 shadow-inner">
                    <i class="bi bi-gear text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tickets -->
    <div class="bg-white/70 backdrop-blur-xl rounded-xl shadow-lg border border-white/30 p-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-3 shadow-lg">
                        <i class="bi bi-clock-history text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Recent Tickets</h3>
                        <p class="text-sm text-gray-600">Latest support requests</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5 animate-pulse"></span>
                        Live
                    </span>
                </div>
            </div>

            <?php
            // Debug output for recent tickets
            echo "<!-- Debug: Recent tickets count: " . count($recent_tickets ?? []) . " -->";
            echo "<!-- Debug: Recent tickets empty check: " . (empty($recent_tickets) ? 'TRUE' : 'FALSE') . " -->";
            if (!empty($recent_tickets)) {
                echo "<!-- Debug: First ticket ID: " . ($recent_tickets[0]['id'] ?? 'NO_ID') . " -->";
            }
            ?>

            <?php if (!empty($recent_tickets)): ?>
                <div class="overflow-x-auto">
                    <div class="mg-table-container"><table class="mg-table w-full">
                        <thead>
                            <tr class="border-b border-gray-200/50">
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Ticket</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/30">
                            <?php foreach ($recent_tickets as $ticket): ?>
                                <tr class="hover:bg-white/50 transition-colors duration-200">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg p-2">
                                                <i class="bi bi-ticket-perforated text-blue-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900"><?= esc($ticket['subject']) ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center space-x-2">
                                            <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-full p-1">
                                                <i class="bi bi-person-circle text-gray-600 text-sm"></i>
                                            </div>
                                            <span class="text-sm text-gray-700"><?= esc($ticket['user_name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <?php
                                            $statusColors = [
                                                'open' => 'bg-blue-100 text-blue-800',
                                                'in_progress' => 'bg-yellow-100 text-yellow-800',
                                                'resolved' => 'bg-green-100 text-green-800',
                                                'closed' => 'bg-gray-100 text-gray-800'
                                            ];
                                            $statusColor = $statusColors[$ticket['status']] ?? 'bg-gray-100 text-gray-800';
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $statusColor ?>">
                                            <?= ucfirst(str_replace('_', ' ', $ticket['status'])) ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="text-sm text-gray-600"><?= date('M j, Y', strtotime($ticket['created_at'])) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table></div>
                </div>
                <div class="mt-6 flex justify-center">
                    <a href="<?= site_url('help-support/tickets') ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-medium rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="bi bi-arrow-right mr-2"></i>
                        View All Tickets
                    </a>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-full p-6 w-24 h-24 mx-auto mb-4 flex items-center justify-center">
                        <i class="bi bi-inbox text-gray-400 text-3xl"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">No recent tickets</h4>
                    <p class="text-gray-600">New support tickets will appear here</p>
                </div>
            <?php endif; ?>
        </div>
     </div>
 </div>

</div>
<?= $this->endSection() ?>
