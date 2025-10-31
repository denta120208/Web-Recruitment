<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use App\Filament\Widgets\ApplicantStatsOverviewWidget;
use App\Filament\Pages\AdminDashboard;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Models\Admin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->authGuard('admin')
            ->authPasswordBroker('admins')
            ->colors([
                // Sesuaikan nuansa brand recruitment (hijau-metland)
                'primary' => Color::Emerald,
                'gray' => Color::Zinc,
            ])
            ->brandName('Metland Recruit Admin')
            ->favicon(asset('favicon.ico'))
            ->darkMode(true)
            // Hide theme toggle and force dark scheme + compact UI
            ->renderHook('panels::head.start', fn () => new \Illuminate\Support\HtmlString(
                '<style>
                    :root{color-scheme:dark}
                    .fi-theme-toggle,[data-theme-toggle]{display:none!important}
                    
                    /* Compact UI - Aggressive size reduction */
                    body { font-size: 13px !important; line-height: 1.4 !important; }
                    .fi-body { font-size: 13px !important; }
                    .fi-main { font-size: 13px !important; }
                    .fi-main-ctn { font-size: 13px !important; }
                    
                    /* Topbar */
                    .fi-topbar { height: 48px !important; min-height: 48px !important; padding: 0 12px !important; }
                    .fi-topbar-item { padding: 6px 8px !important; font-size: 13px !important; }
                    
                    /* Sidebar */
                    .fi-sidebar { width: 200px !important; }
                    .fi-sidebar-nav { font-size: 13px !important; }
                    .fi-sidebar-nav-item { padding: 8px 12px !important; font-size: 13px !important; min-height: 36px !important; }
                    .fi-sidebar-nav-icon { width: 18px !important; height: 18px !important; margin-right: 8px !important; }
                    .fi-sidebar-section-label { font-size: 11px !important; padding: 6px 12px !important; margin-top: 8px !important; }
                    
                    /* Page Header */
                    .fi-header { padding: 12px 16px !important; margin-bottom: 12px !important; }
                    .fi-header-heading { font-size: 18px !important; line-height: 1.3 !important; }
                    .fi-header-subheading { font-size: 13px !important; }
                    .fi-header-actions { gap: 8px !important; }
                    .fi-breadcrumbs { font-size: 12px !important; padding: 8px 0 !important; }
                    
                    /* Forms - Remove gaps between sections */
                    .fi-section { 
                        margin-bottom: 0 !important; 
                        margin-top: 0 !important; 
                        border-radius: 0 !important;
                    }
                    .fi-section:not(:first-child) { 
                        margin-top: 0 !important; 
                        border-top: 1px solid rgba(255,255,255,0.1) !important; 
                    }
                    .fi-section:first-child { border-radius: 8px 8px 0 0 !important; }
                    .fi-section:last-child { border-radius: 0 0 8px 8px !important; }
                    .fi-section-content-ctn { padding: 12px !important; }
                    .fi-section-header { 
                        padding: 8px 12px !important; 
                        margin-bottom: 0 !important; 
                    }
                    .fi-section-header-heading { font-size: 13px !important; font-weight: 600 !important; }
                    .fi-infolist { gap: 0 !important; }
                    .fi-infolist > div { margin: 0 !important; }
                    [class*="fi-section"] { margin: 0 !important; }
                    /* Reduce spacing in TextEntry fields */
                    .fi-infolist-entry { margin-bottom: 8px !important; }
                    .fi-infolist-entry:last-child { margin-bottom: 0 !important; }
                    .fi-infolist-entry-label { font-size: 12px !important; margin-bottom: 2px !important; }
                    .fi-infolist-entry-content { font-size: 13px !important; line-height: 1.4 !important; }
                    /* Reduce spacing in RepeatableEntry */
                    .fi-repeatable { gap: 8px !important; margin-bottom: 0 !important; }
                    .fi-repeatable-item { margin-bottom: 8px !important; padding: 8px !important; }
                    .fi-repeatable-item:last-child { margin-bottom: 0 !important; }
                    .fi-form-section { margin-bottom: 12px !important; }
                    .fi-input-wrp { margin-bottom: 10px !important; }
                    .fi-input { padding: 6px 10px !important; font-size: 13px !important; min-height: 36px !important; }
                    .fi-label { font-size: 12px !important; margin-bottom: 4px !important; font-weight: 500 !important; }
                    .fi-hint { font-size: 11px !important; margin-top: 4px !important; }
                    
                    /* Textarea */
                    .fi-ta { padding: 8px !important; font-size: 13px !important; min-height: 80px !important; }
                    
                    /* Buttons */
                    .fi-btn { padding: 6px 12px !important; font-size: 13px !important; min-height: 36px !important; line-height: 1.4 !important; }
                    .fi-btn-sm { padding: 4px 8px !important; font-size: 12px !important; min-height: 28px !important; }
                    
                    /* Tables - More compact */
                    .fi-table { font-size: 12px !important; }
                    .fi-table-container { font-size: 12px !important; }
                    .fi-table th { 
                        padding: 6px 8px !important; 
                        font-size: 11px !important; 
                        font-weight: 600 !important;
                        line-height: 1.3 !important;
                        height: auto !important;
                    }
                    .fi-table td { 
                        padding: 6px 8px !important; 
                        font-size: 12px !important;
                        line-height: 1.4 !important;
                        height: auto !important;
                    }
                    .fi-table tr { height: auto !important; min-height: 32px !important; }
                    .fi-table tbody tr { height: auto !important; }
                    .fi-table-cell { padding: 6px 8px !important; font-size: 12px !important; }
                    .fi-table-cell-content { font-size: 12px !important; line-height: 1.4 !important; }
                    
                    /* Filament table wrapper */
                    [data-table] { font-size: 12px !important; }
                    [data-table] th { padding: 6px 8px !important; font-size: 11px !important; }
                    [data-table] td { padding: 6px 8px !important; font-size: 12px !important; }
                    [data-table] tr { height: auto !important; }
                    
                    /* Table search and filters */
                    .fi-table-search-actions { padding: 8px !important; gap: 8px !important; }
                    .fi-table-search-field { font-size: 12px !important; padding: 6px 10px !important; min-height: 32px !important; }
                    
                    /* More table specific styles */
                    table { border-spacing: 0 !important; }
                    table thead th { padding: 6px 8px !important; font-size: 11px !important; }
                    table tbody td { padding: 6px 8px !important; font-size: 12px !important; }
                    table tbody tr { height: auto !important; line-height: 1.4 !important; }
                    
                    /* Filament specific table classes */
                    .fi-ta-table { font-size: 12px !important; }
                    .fi-ta-table th, .fi-ta-table td { padding: 6px 8px !important; font-size: 12px !important; }
                    .fi-ta-table tr { min-height: 32px !important; height: auto !important; }
                    
                    /* Table pagination */
                    .fi-pagination { padding: 8px !important; font-size: 12px !important; }
                    .fi-pagination-item { padding: 4px 8px !important; font-size: 12px !important; min-height: 28px !important; }
                    
                    /* Table bulk actions */
                    .fi-table-bulk-actions { padding: 8px !important; font-size: 12px !important; }
                    
                    /* Force smaller row height */
                    .fi-table tbody > tr > td { vertical-align: middle !important; padding-top: 6px !important; padding-bottom: 6px !important; }
                    .fi-table thead > tr > th { vertical-align: middle !important; padding-top: 6px !important; padding-bottom: 6px !important; }
                    
                    /* Table cell content */
                    .fi-table-cell { line-height: 1.4 !important; }
                    .fi-table-cell > * { font-size: 12px !important; line-height: 1.4 !important; }
                    
                    /* Table action buttons */
                    .fi-table-actions { gap: 4px !important; }
                    .fi-table-actions .fi-btn { padding: 4px 8px !important; font-size: 11px !important; min-height: 28px !important; }
                    
                    /* Table links and text */
                    .fi-table a, .fi-table-link { font-size: 12px !important; line-height: 1.4 !important; }
                    
                    /* Make all table elements compact */
                    [role="table"] { font-size: 12px !important; }
                    [role="row"] { min-height: 32px !important; height: auto !important; }
                    [role="cell"], [role="columnheader"] { padding: 6px 8px !important; font-size: 12px !important; }
                    
                    /* Generic table styles - catch all */
                    div[class*="table"] { font-size: 12px !important; }
                    div[class*="Table"] { font-size: 12px !important; }
                    .grid { font-size: 12px !important; }
                    
                    /* Badges */
                    .fi-badge { padding: 3px 8px !important; font-size: 11px !important; line-height: 1.3 !important; }
                    
                    /* Modals */
                    .fi-modal-window { max-width: 85% !important; }
                    .fi-modal-header { padding: 12px 16px !important; }
                    .fi-modal-heading { font-size: 16px !important; }
                    .fi-modal-content { padding: 16px !important; font-size: 13px !important; }
                    .fi-modal-footer { padding: 12px 16px !important; gap: 8px !important; }
                    
                    /* Actions */
                    .fi-actions { gap: 8px !important; }
                    .fi-action { padding: 6px 12px !important; font-size: 13px !important; min-height: 36px !important; }
                    
                    /* Page padding */
                    .fi-page { padding: 16px !important; }
                    .fi-page-header-actions { gap: 8px !important; }
                    
                    /* Form groups */
                    .fi-grouped-header { padding: 8px 12px !important; font-size: 13px !important; }
                    .fi-grouped-content { padding: 12px !important; }
                    
                    /* File upload */
                    .fi-fi-wrp { padding: 4px 8px !important; }
                    .fi-fi-wrp .fi-btn { padding: 4px 8px !important; font-size: 12px !important; }
                    
                    /* Footer */
                    .fi-fo { padding: 12px 16px !important; font-size: 13px !important; }
                    
                    /* Select dropdowns */
                    .fi-select-option { padding: 6px 12px !important; font-size: 13px !important; }
                    
                    /* Reduce all spacing */
                    * { 
                        --fi-spacing-1: 4px;
                        --fi-spacing-2: 8px;
                        --fi-spacing-3: 12px;
                        --fi-spacing-4: 16px;
                        --fi-spacing-5: 20px;
                    }
                </style>'
            ))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                AdminDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                ApplicantStatsOverviewWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
