<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::get('/test', function () {
    return view('test3');
})->name('test');




Route::get('home',function(){})->name('home');

Route::get('about',function(){})->name('about');
//Route::get('institutions.index',function(){})->name('institutions.index');

//Route::get('events.index',function(){})->name('events.index');

Route::get('funding.index',function(){})->name('funding.index');

Route::post('newsletter.subscribe',function(){})->name('newsletter.subscribe');


Route::post('privacy',function(){})->name('privacy');
Route::post('terms',function(){})->name('terms');

Route::post('cookies',function(){})->name('cookies');
Route::post('support',function(){})->name('support');

//Route::post('events.show/{id}',function(){})->name('events.show');





// @foreach($slides as $index => $slide)
// <div class="slide-image {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}">
//     <img src="{{ $slide->image }}" alt="{{ $slide->title }}" class="w-full h-full object-cover">
// </div>
// @endforeach









// Help & Support (accessible to all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/help', [ 'index'])->name('help');
    Route::get('/help/faq', [ 'faq'])->name('help.faq');
    Route::get('/help/contact', [ 'contact'])->name('help.contact');
    Route::post('/help/contact', [ 'submitContact'])->name('help.contact.submit');
});

// Protected Routes (Authenticated Users)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
   // Route::get('/dashboard', [ 'index'])->name('dashboard');
    
    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ 'show'])->name('showm');
        Route::get('/edit', [ 'edit'])->name('edit');
        Route::patch('/update', [ 'update'])->name('update');
        Route::delete('/delete', [ 'destroy'])->name('destroy');
    });
    
    // Institution Management Routes
  //  Route::middleware('can:view-institutions')->group(function () {
       
        Route::prefix('institutions')->name('institutions.')->group(function () {
            Route::get('/', [InstitutionController::class, 'index'])->name('index');
            Route::get('/{institution}', [InstitutionController::class, 'show'])->name('show');
            
            Route::get('/create', [InstitutionController::class, 'createForm'])->name('create');
            Route::get('/pending-approval', [ InstitutionController::class,'pendingApproval'])->name('approval');


            // Management routes (Leaders and TRA Officers)
          //  Route::middleware('can:manage-institutions')->group(function () {
             
                Route::post('/', [ 'store'])->name('store');
                Route::get('/{institution}/edit', [ 'edit'])->name('edit');
                Route::patch('/{institution}', [ 'update'])->name('update');
                Route::delete('/{institution}', [ 'destroy'])->name('destroy');
                
                // Approval routes (TRA Officers only)


                
                Route::patch('/{institution}/approve', [ 'approve'])->name('approve');
                Route::patch('/{institution}/reject', [ 'reject'])->name('reject');
           // });
        });
 //   });
    
    // Member Management Routes
  //  Route::middleware('can:view-members')->group(function () {
       
     Route::prefix('members')->name('members.')->group(function () {
            Route::get('/', [ MemberController::class,'index'])->name('index');
            Route::get('/pending-approval', [ MemberController::class, 'pendingApproval'])->name('pending');
            Route::get('/leaders-supervisors', [MemberController::class, 'leadersAndSupervisors'])->name('leaders');

            Route::get('/create', [MemberController::class, 'create'])->name('create');



            Route::get('/{member}', [ 'show'])->name('show');
            
            // Management routes
            Route::middleware('can:manage-members')->group(function () {
                Route::post('/', [ 'store'])->name('store');
                Route::get('/{member}/edit', [ 'edit'])->name('edit');
                Route::patch('/{member}', [ 'update'])->name('update');
                Route::delete('/{member}', [ 'destroy'])->name('destroy');
                
                // Approval and leadership routes
                Route::patch('/{member}/approve', [ 'approve'])->name('approve');
                Route::patch('/{member}/reject', [ 'reject'])->name('reject');
                Route::patch('/{member}/promote', [ 'promote'])->name('promote');
                Route::patch('/{member}/demote', [ 'demote'])->name('demote');
                
                // Bulk operations
                Route::post('/bulk-approve', [ 'bulkApprove'])->name('bulk.approve');
                Route::post('/bulk-reject', [ 'bulkReject'])->name('bulk.reject');
                Route::post('/export', [ 'export'])->name('export');
            });
        });
   // });
    
    // Event Management Routes
   // Route::middleware('can:view-events')->group(function () {
        Route::prefix('events')->name('events.')->group(function () {
            Route::get('/', [EventController::class, 'index'])->name('index');

            Route::get('/create', [EventController::class, 'create'])->name('create');


            Route::get('/calendar', [EventController::class, 'calendar'])->name('calendar');

            Route::get('/registrations', [EventController::class, 'registrations'])->name('registrations');



            Route::get('/{event}', [ 'show'])->name('show');
            // Registration routes (all authenticated users)
            Route::post('/{event}/register', [ 'register'])->name('register');
            Route::delete('/{event}/unregister', [ 'unregister'])->name('unregister');
            
            // Management routes
            Route::middleware('can:manage-events')->group(function () {
                Route::post('/', [ 'store'])->name('store');
                Route::get('/{event}/edit', [ 'edit'])->name('edit');
                Route::patch('/{event}', [ 'update'])->name('update');
                Route::delete('/{event}', [ 'destroy'])->name('destroy');
                
                // Event management
                Route::patch('/{event}/publish', [ 'publish'])->name('publish');
                Route::patch('/{event}/cancel', [ 'cancel'])->name('cancel');
                Route::get('/{event}/attendees', [ 'attendees'])->name('attendees');
                Route::post('/{event}/mark-attendance', [ 'markAttendance'])->name('mark-attendance');
                Route::post('/{event}/upload-report', [ 'uploadReport'])->name('upload-report');
                
                // Export routes
                Route::get('/{event}/export-attendees', [ 'exportAttendees'])->name('export-attendees');
                Route::get('/export', [ 'export'])->name('export');
            });
        });
   // });
    
    // Budget Management Routes
    Route::middleware('can:view-budgets')->group(function () {
        Route::prefix('budgets')->name('budgets.')->group(function () {
            Route::get('/', [ 'index'])->name('index');
            Route::get('/yearly-plans', [ 'yearlyPlans'])->name('yearly');
            Route::get('/{budget}', [ 'show'])->name('show');
            
            // Creation and management routes
            Route::middleware('can:manage-budgets')->group(function () {
                Route::get('/create', [ 'create'])->name('create');
                Route::post('/', [ 'store'])->name('store');
                Route::get('/{budget}/edit', [ 'edit'])->name('edit');
                Route::patch('/{budget}', [ 'update'])->name('update');
                Route::delete('/{budget}', [ 'destroy'])->name('destroy');
                
                // Budget submission and revision
                Route::patch('/{budget}/submit', [ 'submit'])->name('submit');
                Route::patch('/{budget}/revise', [ 'revise'])->name('revise');
            });
            
            // Approval routes (TRA Officers only)
            Route::middleware('can:approve-budgets')->group(function () {
                Route::get('/pending-approval', [ 'pendingApproval'])->name('pending');
                Route::patch('/{budget}/approve', [ 'approve'])->name('approve');
                Route::patch('/{budget}/reject', [ 'reject'])->name('reject');
                Route::post('/{budget}/add-comment', [ 'addComment'])->name('add-comment');
                
                // Bulk approval operations
                Route::post('/bulk-approve', [ 'bulkApprove'])->name('bulk.approve');
                Route::post('/bulk-reject', [ 'bulkReject'])->name('bulk.reject');
            });
            
            // Export routes
            Route::get('/export/pdf', [ 'exportPdf'])->name('export.pdf');
            Route::get('/export/csv', [ 'exportCsv'])->name('export.csv');
        });
    });
    
    // Reports & Analytics Routes
    Route::middleware('can:view-reports')->group(function () {
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/dashboard', [ 'dashboard'])->name('dashboard');
            Route::get('/members', [ 'members'])->name('members');
            Route::get('/events', [ 'events'])->name('events');
            Route::get('/financial', [ 'financial'])->name('financial');
            Route::get('/my-activity', [ 'myActivity'])->name('my-activity');
            
            // Advanced reports (TRA Officers)
            Route::middleware('can:generate-reports')->group(function () {
                Route::get('/institutions-performance', [ 'institutionsPerformance'])->name('institutions-performance');
                Route::get('/budget-analysis', [ 'budgetAnalysis'])->name('budget-analysis');
                Route::get('/engagement-metrics', [ 'engagementMetrics'])->name('engagement-metrics');
                Route::get('/compliance-tracking', [ 'complianceTracking'])->name('compliance-tracking');
                
                // Export routes
                Route::post('/generate-custom', [ 'generateCustom'])->name('generate-custom');
                Route::get('/export/{type}', [ 'export'])->name('export');
            });
        });
    });
    
    // Certificate Routes
    Route::prefix('certificates')->name('certificates.')->group(function () {
        Route::get('/', [ 'index'])->name('index');
        Route::get('/my-certificates', [ 'myCertificates'])->name('my');
        Route::get('/{certificate}', [ 'show'])->name('show');
        Route::get('/{certificate}/download', [ 'download'])->name('download');
        Route::get('/{certificate}/verify', [ 'verify'])->name('verify');
        
        // Management routes (Leaders and TRA Officers)
        Route::middleware('can:manage-certificates')->group(function () {
            Route::post('/issue', [ 'issue'])->name('issue');
            Route::post('/bulk-issue', [ 'bulkIssue'])->name('bulk-issue');
            Route::patch('/{certificate}/revoke', [ 'revoke'])->name('revoke');
            Route::get('/templates/manage', [ 'manageTemplates'])->name('templates.manage');
            Route::post('/templates', [ 'createTemplate'])->name('templates.create');
        });
    });
    
    // Notification Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [ 'index'])->name('index');
        Route::patch('/{notification}/read', [ 'markAsRead'])->name('read');
        Route::patch('/mark-all-read', [ 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notification}', [ 'destroy'])->name('destroy');
        Route::delete('/clear-all', [ 'clearAll'])->name('clear-all');
        
        // Notification preferences
        Route::get('/settings', [ 'settings'])->name('settings');
        Route::patch('/settings', [ 'updateSettings'])->name('update-settings');
    });
    
    // Activity Feed Routes
    Route::prefix('activities')->name('activities.')->group(function () {
        Route::get('/', [ 'index'])->name('index');
        Route::get('/my-activities', [ 'myActivities'])->name('my');
    });
    
    // Settings Route
    Route::get('/settings', [ 'settings'])->name('settings');
    Route::patch('/settings', [ 'updateSettings'])->name('settings.update');
});

// System Administration Routes (TRA Officers Only)
Route::middleware(['auth', 'can:system-admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [ 'users'])->name('index');
        Route::get('/create', [ 'createUser'])->name('create');
        Route::post('/', [ 'storeUser'])->name('store');
        Route::get('/{user}', [ 'showUser'])->name('show');
        Route::get('/{user}/edit', [ 'editUser'])->name('edit');
        Route::patch('/{user}', [ 'updateUser'])->name('update');
        Route::delete('/{user}', [ 'destroyUser'])->name('destroy');
        Route::patch('/{user}/activate', [ 'activateUser'])->name('activate');
        Route::patch('/{user}/deactivate', [ 'deactivateUser'])->name('deactivate');
        Route::post('/{user}/impersonate', [ 'impersonateUser'])->name('impersonate');
    });
    
    // Roles & Permissions Management
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [ 'roles'])->name('index');
        Route::get('/create', [ 'createRole'])->name('create');
        Route::post('/', [ 'storeRole'])->name('store');
        Route::get('/{role}/edit', [ 'editRole'])->name('edit');
        Route::patch('/{role}', [ 'updateRole'])->name('update');
        Route::delete('/{role}', [ 'destroyRole'])->name('destroy');
        
        // Permission management
        Route::get('/permissions', [ 'permissions'])->name('permissions');
        Route::post('/permissions', [ 'createPermission'])->name('permissions.create');
        Route::patch('/permissions/{permission}', [ 'updatePermission'])->name('permissions.update');
        Route::delete('/permissions/{permission}', [ 'destroyPermission'])->name('permissions.destroy');
    });
    
    // System Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [ 'settings'])->name('index');
        Route::patch('/', [ 'updateSettings'])->name('update');
        Route::get('/email-templates', [ 'emailTemplates'])->name('email-templates');
        Route::patch('/email-templates/{template}', [ 'updateEmailTemplate'])->name('email-templates.update');
        Route::get('/system-maintenance', [ 'systemMaintenance'])->name('maintenance');
        Route::post('/clear-cache', [ 'clearCache'])->name('clear-cache');
        Route::post('/backup-database', [ 'backupDatabase'])->name('backup-database');
    });
    
    // Audit Logs
    Route::prefix('audit')->name('audit.')->group(function () {
        Route::get('/', [ 'auditLogs'])->name('index');
        Route::get('/{log}', [ 'showAuditLog'])->name('show');
        Route::get('/export/csv', [ 'exportAuditLogs'])->name('export');
        Route::delete('/clear-old', [ 'clearOldLogs'])->name('clear-old');
    });
    
    // System Statistics
    Route::get('/statistics', [ 'systemStatistics'])->name('statistics');
    Route::get('/health-check', [ 'healthCheck'])->name('health-check');
});

// API Routes for AJAX calls
Route::middleware(['auth', 'api'])->prefix('api')->name('api.')->group(function () {
    
    // Search endpoints
    Route::get('/search/members', [ 'apiSearch'])->name('search.members');
    Route::get('/search/events', [ 'apiSearch'])->name('search.events');
    Route::get('/search/institutions', [ 'apiSearch'])->name('search.institutions');
    Route::get('/search/global', [ 'globalSearch'])->name('search.global');
    
    // Dashboard data endpoints
    Route::get('/dashboard/stats', [ 'getStats'])->name('dashboard.stats');
    Route::get('/dashboard/activities', [ 'getActivities'])->name('dashboard.activities');
    Route::get('/dashboard/notifications', [ 'getNotifications'])->name('dashboard.notifications');
    
    // Real-time updates
    Route::get('/events/upcoming', [ 'getUpcoming'])->name('events.upcoming');
    Route::get('/budgets/pending-count', [ 'getPendingCount'])->name('budgets.pending-count');
    Route::get('/members/recent', [ 'getRecent'])->name('members.recent');
});

// Public routes (no authentication required)
Route::prefix('public')->name('public.')->group(function () {
    Route::get('/events', [ 'publicEvents'])->name('events');
    Route::get('/events/{event}', [ 'publicShow'])->name('events.show');
  //  Route::get('/institutions', [ 'publicList'])->name('institutions');
    Route::get('/certificates/verify/{code}', [ 'publicVerify'])->name('certificates.verify');
});


Route::get('/privacy-policy', function () {
    return view('dashboard');
})->name('verification.notice');

// Fallback route
Route::fallback(function () {
    return view('errors.404');
});


