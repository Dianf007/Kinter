@extends('layouts.admin.app')
@section('title', 'Dashboard')

@push('styles')
<style>
    .welcome-section {
        margin-bottom: 24px;
    }
    .welcome-section h2 {
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--admin-text);
        margin-bottom: 4px;
    }
    .welcome-section p {
        color: var(--admin-text-light);
        font-size: 0.95rem;
    }

    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    
    .metric-card {
        background: var(--admin-card-bg);
        border: 1px solid var(--admin-border);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px var(--admin-shadow);
    }
    
    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px var(--admin-shadow);
    }
    
    .metric-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    
    .metric-icon.primary {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
        color: var(--admin-primary);
    }
    
    .metric-icon.success {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
        color: var(--admin-success);
    }
    
    .metric-icon.warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1));
        color: var(--admin-warning);
    }
    
    .metric-icon.danger {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
        color: var(--admin-danger);
    }
    
    .metric-content h3 {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
        color: var(--admin-text);
    }
    
    .metric-content p {
        margin: 0;
        font-size: 0.875rem;
        color: var(--admin-text-light);
    }
    
    .metric-badge {
        font-size: 0.75rem;
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 600;
        margin-top: 4px;
        display: inline-block;
    }
    
    .metric-badge.up {
        background: rgba(16, 185, 129, 0.1);
        color: var(--admin-success);
    }
    
    .metric-badge.down {
        background: rgba(239, 68, 68, 0.1);
        color: var(--admin-danger);
    }

    .dashboard-row {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 1024px) {
        .dashboard-row {
            grid-template-columns: 1fr;
        }
    }

    .activity-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .activity-table thead th {
        background: var(--admin-bg);
        padding: 12px 16px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--admin-text-light);
        border-bottom: 1px solid var(--admin-border);
        text-align: left;
    }
    
    .activity-table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--admin-border);
        color: var(--admin-text);
        font-size: 0.875rem;
    }
    
    .activity-table tbody tr:hover {
        background: var(--admin-bg);
    }

    .school-avatar {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: white;
        font-size: 0.875rem;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .badge.active {
        background: rgba(16, 185, 129, 0.1);
        color: var(--admin-success);
    }

    .badge.pending {
        background: rgba(245, 158, 11, 0.1);
        color: var(--admin-warning);
    }

    .badge.done {
        background: rgba(59, 130, 246, 0.1);
        color: var(--admin-info);
    }

    .quick-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .quick-action {
        padding: 16px;
        border-radius: 12px;
        background: var(--admin-bg);
        border: 1px solid var(--admin-border);
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: var(--admin-text);
        transition: all 0.2s ease;
    }

    .quick-action:hover {
        background: var(--admin-card-bg);
        border-color: var(--admin-primary);
        transform: translateX(4px);
        color: var(--admin-primary);
    }

    .quick-action-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .quick-action-icon.primary {
        background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
        color: white;
    }

    .quick-action-icon.success {
        background: var(--admin-success);
        color: white;
    }

    .quick-action-icon.info {
        background: var(--admin-info);
        color: white;
    }

    .quick-action-content h4 {
        font-size: 0.875rem;
        font-weight: 600;
        margin: 0;
    }

    .quick-action-content p {
        font-size: 0.75rem;
        margin: 0;
        color: var(--admin-text-light);
    }

    .notifications-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .notification-item {
        padding: 12px;
        border-radius: 8px;
        background: var(--admin-bg);
        border-left: 3px solid var(--admin-primary);
        font-size: 0.875rem;
    }

    .notification-item strong {
        color: var(--admin-text);
        display: block;
        margin-bottom: 2px;
    }

    .notification-item small {
        color: var(--admin-text-light);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .section-header h3 {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
        color: var(--admin-text);
    }

    .view-all-link {
        font-size: 0.875rem;
        color: var(--admin-primary);
        text-decoration: none;
        font-weight: 500;
    }

    .view-all-link:hover {
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<div style="padding: 0;">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <h2>Welcome back, John!</h2>
        <p>Here's what's happening with your schools today.</p>
    </div>

    <!-- Metrics Cards -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-icon primary">
                <i class="fas fa-school"></i>
            </div>
            <div class="metric-content">
                <h3>12</h3>
                <p>Total Schools</p>
                <span class="metric-badge up">↑ 12%</span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon success">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="metric-content">
                <h3>24,567</h3>
                <p>Total Students</p>
                <span class="metric-badge up">↑ 8.3%</span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon warning">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="metric-content">
                <h3>1,847</h3>
                <p>Total Teachers</p>
                <span class="metric-badge up">↑ 5.2%</span>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon danger">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="metric-content">
                <h3>342</h3>
                <p>Active Courses</p>
                <span class="metric-badge down">↓ 2.1%</span>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="dashboard-row">
        <!-- Recent Schools Activity -->
        <div class="admin-card">
            <div class="admin-card__body" style="padding: 20px;">
                <div class="section-header">
                    <h3>Recent Schools Activity</h3>
                    <a href="#" class="view-all-link">View All</a>
                </div>
                <div style="overflow-x: auto;">
                    <table class="activity-table">
                        <thead>
                            <tr>
                                <th>SCHOOL</th>
                                <th>STUDENTS</th>
                                <th>STATUS</th>
                                <th>LAST ACTIVE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="school-avatar" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">GH</div>
                                        <div>
                                            <div style="font-weight: 600;">Greenwood High</div>
                                            <div style="font-size: 0.75rem; color: var(--admin-text-light);">greenwood.edu</div>
                                        </div>
                                    </div>
                                </td>
                                <td><strong>2,847</strong></td>
                                <td><span class="badge active">Active</span></td>
                                <td><span style="color: var(--admin-text-light);">2 min ago</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="school-avatar" style="background: linear-gradient(135deg, #ec4899, #f43f5e);">MA</div>
                                        <div>
                                            <div style="font-weight: 600;">Maple Academy</div>
                                            <div style="font-size: 0.75rem; color: var(--admin-text-light);">maple.edu</div>
                                        </div>
                                    </div>
                                </td>
                                <td><strong>1,923</strong></td>
                                <td><span class="badge active">Active</span></td>
                                <td><span style="color: var(--admin-text-light);">15 min ago</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="school-avatar" style="background: linear-gradient(135deg, #f59e0b, #d97706);">SC</div>
                                        <div>
                                            <div style="font-weight: 600;">Sunnydale College</div>
                                            <div style="font-size: 0.75rem; color: var(--admin-text-light);">sunnydale.edu</div>
                                        </div>
                                    </div>
                                </td>
                                <td><strong>3,421</strong></td>
                                <td><span class="badge pending">Pending</span></td>
                                <td><span style="color: var(--admin-text-light);">1 hour ago</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="school-avatar" style="background: linear-gradient(135deg, #10b981, #059669);">RV</div>
                                        <div>
                                            <div style="font-weight: 600;">Riverside Valley</div>
                                            <div style="font-size: 0.75rem; color: var(--admin-text-light);">riverside.edu</div>
                                        </div>
                                    </div>
                                </td>
                                <td><strong>2,156</strong></td>
                                <td><span class="badge active">Active</span></td>
                                <td><span style="color: var(--admin-text-light);">3 hours ago</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="school-avatar" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">SL</div>
                                        <div>
                                            <div style="font-weight: 600;">Springfield Learning</div>
                                            <div style="font-size: 0.75rem; color: var(--admin-text-light);">springfield.edu</div>
                                        </div>
                                    </div>
                                </td>
                                <td><strong>1,678</strong></td>
                                <td><span class="badge done">Done</span></td>
                                <td><span style="color: var(--admin-text-light);">5 hours ago</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar: Quick Actions + Notifications -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <!-- Quick Actions -->
            <div class="admin-card">
                <div class="admin-card__body" style="padding: 20px;">
                    <div class="section-header">
                        <h3>Quick Actions</h3>
                    </div>
                    <div class="quick-actions">
                        <a href="{{ route('admin.schedules.create') }}" class="quick-action">
                            <div class="quick-action-icon primary">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="quick-action-content">
                                <h4>Invite Admin</h4>
                                <p>Add schedule for classes</p>
                            </div>
                        </a>
                        <a href="{{ route('admin.classrooms.index') }}" class="quick-action">
                            <div class="quick-action-icon success">
                                <i class="fas fa-file-download"></i>
                            </div>
                            <div class="quick-action-content">
                                <h4>Export Report</h4>
                                <p>Download latest data</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div class="admin-card">
                <div class="admin-card__body" style="padding: 20px;">
                    <div class="section-header">
                        <h3>Notifications</h3>
                        <span class="badge" style="background: var(--admin-danger); color: white;">3</span>
                    </div>
                    <div class="notifications-list">
                        <div class="notification-item">
                            <strong>🎉 New school registration</strong>
                            <div><small>Greenwood High just completed onboarding.</small></div>
                            <small style="display: block; margin-top: 4px;">2 hours ago</small>
                        </div>
                        <div class="notification-item" style="border-color: var(--admin-success);">
                            <strong>✅ Payment received</strong>
                            <div><small>Monthly subscription renewed.</small></div>
                            <small style="display: block; margin-top: 4px;">Yesterday - 4:32 PM</small>
                        </div>
                        <div class="notification-item" style="border-color: var(--admin-warning);">
                            <strong>⚙️ System maintenance</strong>
                            <div><small>Scheduled for tonight at 12:00 AM.</small></div>
                            <small style="display: block; margin-top: 4px;">3 hours ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
