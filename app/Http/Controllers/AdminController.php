<?php

namespace App\Http\Controllers;

use App\Mail\PartnerRegistration;
use App\Mail\PartnerAccepted;
use App\Mail\PartnerRejected;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Deal;
use App\Models\Booking;
use App\Models\Tours;
use App\Models\Car;
use App\Models\Pages;
use App\Models\Payment;
use App\Models\User;
use App\Models\Role;
use App\Models\ContactMessage;
use App\Models\System;
use App\Models\SiteVisit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Hashids\Hashids;

class AdminController extends Controller
{
    /**
     * Create Hashids instance
     */
    private function getHashids()
    {
        return new Hashids('MchungajiZanzibarBookings', 10);
    }

    // Dashboard
    public function dashboard()
    {
        $authUser = Auth::user();
        $roleName = optional($authUser->role)->name;
        $isPartner = $roleName === 'Partner';

        if ($isPartner && $authUser) {
            $stats = [
                'activities_count' => Deal::where('type', 'activity')->where('user_id', $authUser->id)->count(),
                'packages_count' => Deal::where('type', 'package')->where('user_id', $authUser->id)->count(),
                'hotels_count' => Deal::where('type', 'hotel')->where('user_id', $authUser->id)->count(),
                'apartments_count' => Deal::where('type', 'apartment')->where('user_id', $authUser->id)->count(),
                'bookings_count' => Booking::where('user_id', $authUser->id)->count(),
                'cars_count' => Deal::where('type', 'car')->where('user_id', $authUser->id)->count(),
                'blog_posts_count' => Blog::where('user_id', $authUser->id)->count(),
                'site_visits_count' => 0,
                'total_revenue' => Payment::where('user_id', $authUser->id)->completed()->sum('amount'),
            ];

            $recentDeals = Deal::with('category')
                ->where('user_id', $authUser->id)
                ->latest()
                ->take(10)
                ->get();
        } else {
            $stats = [
                'activities_count' => Deal::where('type', 'activity')->count(),
                'packages_count' => Deal::where('type', 'package')->count(),
                'hotels_count' => Deal::where('type', 'hotel')->count(),
                'apartments_count' => Deal::where('type', 'apartment')->count(),
                'bookings_count' => Booking::count(),
                'cars_count' => Deal::where('type', 'car')->count(),
                'blog_posts_count' => Blog::count(),
                'site_visits_count' => $this->resolveSiteVisitsCount(),
                'total_revenue' => Payment::completed()->sum('amount'),
            ];

            $recentDeals = Deal::with('category')
                ->latest()
                ->take(10)
                ->get();
        }

        return view('admin.pages.dashboard', compact('stats', 'recentDeals', 'isPartner'));
    }

    private function resolveSiteVisitsCount(): int
    {
        // Count unique visitors from site_visits table
        if (Schema::hasTable('site_visits')) {
            return SiteVisit::count();
        }

        // Fallback to old method if table doesn't exist yet
        if (Schema::hasTable('flight_searches')) {
            return DB::table('flight_searches')->count();
        }

        if (Schema::hasTable('bookings')) {
            return Booking::distinct('user_id')->count('user_id');
        }

        return 0;
    }

    public function approvePartner(Request $request, User $user)
    {
        $currentRole = optional(Auth::user()->role)->name;
        if (!in_array($currentRole, ['Super Admin', 'Admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $partnerRole = Role::where('name', 'Partner')->firstOrFail();

        $user->role_id = $partnerRole->id;
        $user->status = 1;
        $user->save();

        try {
            Mail::to($user->email)->send(new PartnerAccepted($user));
            Log::info('Partner approved via email link', ['user_id' => $user->id, 'approved_by' => Auth::id()]);
        } catch (\Throwable $th) {
            Log::error('Failed sending partner approval email', [
                'user_id' => $user->id,
                'error' => $th->getMessage()
            ]);
        }

        return redirect()->route('admin.users')->with('success', 'Partner approved successfully.');
    }

    public function approvePartnerRequest($id)
    {
        $user = User::findOrFail($id);
        return $this->approvePartner(new Request(), $user);
    }

    public function rejectPartnerRequest($id)
    {
        $user = User::findOrFail($id);
        return $this->rejectPartner(new Request(), $user);
    }

    public function rejectPartner(Request $request, User $user)
    {
        $currentRole = optional(Auth::user()->role)->name;
        if (!in_array($currentRole, ['Super Admin', 'Admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $userRole = Role::where('name', 'User')->firstOrFail();

        $user->role_id = $userRole->id;
        $user->status = 1;
        $user->save();

        try {
            Mail::to($user->email)->send(new PartnerRejected($user));
            Log::info('Partner request rejected', ['user_id' => $user->id, 'rejected_by' => Auth::id()]);
        } catch (\Throwable $th) {
            Log::error('Failed sending partner rejection email', [
                'user_id' => $user->id,
                'error' => $th->getMessage()
            ]);
        }

        return redirect()->route('admin.users')->with('info', 'Partner request rejected and user reverted.');
    }

   

    // Apartments Management
    public function apartments()
    {
        return view('admin.pages.apartments.index');
    }

    // Blog Management
    public function blog()
    {
        $blogs = Blog::with(['user', 'category'])->orderBy('created_at', 'desc')->get();
        return view('admin.pages.blog.index', compact('blogs'));
    }

    public function createBlog()
    {
        // Filter categories by type and active status using scopes
        $categories = Category::active()->byType('blog')->get();
        return view('admin.pages.blog.create', compact('categories'));
    }

    public function storeBlog(Request $request)
    {
        // Debug: Log request data
        Log::info('Blog creation request:', $request->all());
        
        // Enhanced validation rules
        $request->validate([
            'title' => 'required|string|max:255',
            'preview_text' => 'nullable|string|max:500',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,published',
            'cover_photo' => 'required|image' // Image required
        ]);

        try {
            DB::beginTransaction();

            // Prepare data for blog creation
            $data = $request->only(['title', 'preview_text', 'description', 'category_id']);
            $data['user_id'] = 1;
            $data['status'] = $request->status === 'published' ? 1 : 0;

            // Handle cover photo upload
            $coverPhotoPath = null;
            if ($request->hasFile('cover_photo')) {
                $coverPhoto = $request->file('cover_photo');
                $filename = time() . '_' . $coverPhoto->getClientOriginalName();
                $coverPhotoPath = $coverPhoto->storeAs('blog/covers', $filename, 'public');
                $data['cover_photo'] = $coverPhotoPath;
            }

            // Create the blog post
            $blog = Blog::create($data);
            
            DB::commit();
            
            Log::info('Blog created successfully:', ['blog_id' => $blog->id, 'title' => $blog->title]);
            return redirect()->route('admin.blog')->with('success', 'Blog post created successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Clean up uploaded file if blog creation failed
            if (isset($coverPhotoPath) && Storage::disk('public')->exists($coverPhotoPath)) {
                Storage::disk('public')->delete($coverPhotoPath);
            }
            
            Log::error('Blog creation failed:', [
                'error' => $e->getMessage(), 
                'data' => $data ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to create blog post: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function editBlog($id)
    {
        // Find the blog post with relationships
        $blog = Blog::with(['category', 'user'])->find($id);
        
        if (!$blog) {
            return redirect()->route('admin.blog')->with('error', 'Blog post not found');
        }
        
        // Filter categories by type and active status using scopes
        $categories = Category::active()->byType('blog')->get();
        
        return view('admin.pages.blog.create', compact('blog', 'categories'));
    }

    public function updateBlog(Request $request, $id)
    {
        // Find the blog post
        $blog = Blog::find($id);
        
        if (!$blog) {
            return redirect()->route('admin.blog')->with('error', 'Blog post not found');
        }
        
        // Debug: Log request data
        Log::info('Blog update request:', $request->all());
        
        // Enhanced validation rules
        $request->validate([
            'title' => 'required|string|max:255',
            'preview_text' => 'nullable|string|max:500',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,published',
            // Image optional on update
            'cover_photo' => 'nullable|image'
        ]);

        try {
            DB::beginTransaction();

            // Prepare data for blog update
            $data = $request->only(['title', 'preview_text', 'description', 'category_id']);
            $data['status'] = $request->status === 'published' ? 1 : 0;

            // Handle cover photo upload (optional on update)
            if ($request->hasFile('cover_photo')) {
                // Delete old cover if exists
                if ($blog->cover_photo && Storage::disk('public')->exists($blog->cover_photo)) {
                    Storage::disk('public')->delete($blog->cover_photo);
                }
                $coverPhoto = $request->file('cover_photo');
                $filename = time() . '_' . $coverPhoto->getClientOriginalName();
                $coverPhotoPath = $coverPhoto->storeAs('blog/covers', $filename, 'public');
                $data['cover_photo'] = $coverPhotoPath;
            }

            // Update the blog post
            $blog->update($data);
            
            DB::commit();
            
            Log::info('Blog updated successfully:', ['blog_id' => $blog->id, 'title' => $blog->title]);
            return redirect()->route('admin.blog')->with('success', 'Blog post updated successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Clean up uploaded file if blog update failed
            if (isset($coverPhotoPath) && Storage::disk('public')->exists($coverPhotoPath)) {
                Storage::disk('public')->delete($coverPhotoPath);
            }
            
            Log::error('Blog update failed:', [
                'error' => $e->getMessage(), 
                'blog_id' => $id,
                'data' => $data ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to update blog post: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function deleteBlog($id)
    {
        try {
            DB::beginTransaction();

            $blog = Blog::find($id);

            if (!$blog) {
                return redirect()->route('admin.blog')->with('error', 'Blog post not found');
            }

            // Delete cover photo from storage if it exists
            if ($blog->cover_photo && Storage::disk('public')->exists($blog->cover_photo)) {
                Storage::disk('public')->delete($blog->cover_photo);
            }

            $blog->delete();

            DB::commit();

            return redirect()->route('admin.blog')->with('success', 'Blog post deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Blog deletion failed:', [
                'error' => $e->getMessage(),
                'blog_id' => $id,
            ]);
            return redirect()->route('admin.blog')->with('error', 'Failed to delete blog post.');
        }
    }

    // Bookings Management
    public function bookings()
    {
        $bookings = Booking::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        $hashids = $this->getHashids();
        
        return view('admin.pages.bookings.index', compact('bookings', 'hashids'));
    }

    public function viewBooking($id)
    {
        $hashids = $this->getHashids();
        $decodedIds = $hashids->decode($id);
        
        if (empty($decodedIds)) {
            return redirect()->route('admin.bookings')->with('error', 'Invalid booking ID.');
        }
        
        $bookingId = $decodedIds[0];
        $booking = Booking::with('user')->findOrFail($bookingId);
        
        // Get deal information for booking items
        $bookingItems = $booking->getBookingItems();
        $dealIds = collect($bookingItems)->pluck('deal_id')->filter()->unique();
        $deals = Deal::with(['photos', 'category'])->whereIn('id', $dealIds)->get()->keyBy('id');
        
        return view('admin.pages.bookings.view', compact('booking', 'hashids', 'deals'));
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed,paid',
            'notes' => 'nullable|string|max:500'
        ]);

        $hashids = $this->getHashids();
        $decodedIds = $hashids->decode($id);
        
        if (empty($decodedIds)) {
            return redirect()->route('admin.bookings')->with('error', 'Invalid booking ID.');
        }
        
        $bookingId = $decodedIds[0];
        $booking = Booking::findOrFail($bookingId);
        
        $booking->update([
            'status' => $request->status,
            'additional_notes' => $request->notes ?? $booking->additional_notes
        ]);

        return redirect()->route('admin.bookings.view', $id)->with('success', 'Booking status updated successfully!');
    }

    // Users Management
    public function users()
    {
        $users = User::with('role')
            ->where('role_id', '!=', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $hashids = $this->getHashids();
        $baseQuery = User::query()->where('role_id', '!=', 1);
        $userStats = [
            'total' => (clone $baseQuery)->count(),
            'active' => (clone $baseQuery)->where('status', 1)->count(),
            'inactive' => (clone $baseQuery)->where('status', 0)->count(),
            'verified' => (clone $baseQuery)->whereNotNull('email_verified_at')->count(),
            'suspended' => (clone $baseQuery)->where('is_suspended', true)->count(),
        ];
        return view('admin.pages.users', compact('users', 'hashids', 'userStats'));
    }

    public function createUser(Request $request)
    {
        $this->ensureAdminOrSuperAdmin();
        $roles = Role::where('name', '!=', 'Super Admin')->orderBy('name')->get();
        $prefillPartner = $request->boolean('partner');

        return view('admin.pages.users.create', compact('roles', 'prefillPartner'));
    }

    public function storeUser(Request $request)
    {
        $this->ensureAdminOrSuperAdmin();

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|boolean',
        ]);

        $role = Role::findOrFail($request->role_id);
        if ($role->name === 'Super Admin') {
            return redirect()->back()->withInput()->with('error', 'Cannot assign Super Admin from this form.');
        }

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'status' => (int) $request->status,
            'is_suspended' => false,
            'email_verified_at' => now(),
        ]);

        if ($role->name === 'Partner') {
            try {
                if ((int) $user->status === 0) {
                    Mail::to($user->email)->send(new PartnerRegistration($user));
                } else {
                    Mail::to($user->email)->send(new PartnerAccepted($user));
                }
            } catch (\Throwable $e) {
                Log::warning('Partner welcome email failed on admin create', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }
        }

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    /**
     * Partners only (active Partner role). Admins can suspend / assign deals.
     */
    public function partners()
    {
        $this->ensureAdminOrSuperAdmin();

        $partnerRole = Role::where('name', 'Partner')->firstOrFail();

        $partners = User::with('role')
            ->where('role_id', $partnerRole->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $hashids = $this->getHashids();

        return view('admin.pages.partners', compact('partners', 'hashids'));
    }

    public function suspendUser(Request $request, string $id)
    {
        $this->ensureAdminOrSuperAdmin();

        $hashids = $this->getHashids();
        $decodedIds = $hashids->decode($id);
        if (empty($decodedIds)) {
            return redirect()->route('admin.users')->with('error', 'Invalid user ID.');
        }

        $user = User::findOrFail($decodedIds[0]);
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot suspend your own account.');
        }

        $user->is_suspended = true;
        $user->save();

        return redirect()->back()->with('success', 'User has been suspended and cannot log in.');
    }

    public function unsuspendUser(Request $request, string $id)
    {
        $this->ensureAdminOrSuperAdmin();

        $hashids = $this->getHashids();
        $decodedIds = $hashids->decode($id);
        if (empty($decodedIds)) {
            return redirect()->route('admin.users')->with('error', 'Invalid user ID.');
        }

        $user = User::findOrFail($decodedIds[0]);
        $user->is_suspended = false;
        $user->save();

        return redirect()->back()->with('success', 'Suspension removed. User can log in again.');
    }

    public function assignDealsToPartnerForm(string $id)
    {
        $this->ensureAdminOrSuperAdmin();

        $hashids = $this->getHashids();
        $decodedIds = $hashids->decode($id);
        if (empty($decodedIds)) {
            return redirect()->route('admin.partners')->with('error', 'Invalid partner ID.');
        }

        $partner = User::with('role')->findOrFail($decodedIds[0]);
        if (optional($partner->role)->name !== 'Partner') {
            return redirect()->route('admin.partners')->with('error', 'Selected user is not a partner.');
        }

        $deals = Deal::with(['user', 'category'])
            ->orderBy('type')
            ->orderBy('title')
            ->get();

        return view('admin.pages.partners.assign_deals', compact('partner', 'deals', 'hashids'));
    }

    public function assignDealsToPartner(Request $request, string $id)
    {
        $this->ensureAdminOrSuperAdmin();

        $request->validate([
            'deal_ids' => 'nullable|array',
            'deal_ids.*' => 'integer|exists:deals,id',
        ]);

        $hashids = $this->getHashids();
        $decodedIds = $hashids->decode($id);
        if (empty($decodedIds)) {
            return redirect()->route('admin.partners')->with('error', 'Invalid partner ID.');
        }

        $partner = User::with('role')->findOrFail($decodedIds[0]);
        if (optional($partner->role)->name !== 'Partner') {
            return redirect()->route('admin.partners')->with('error', 'Selected user is not a partner.');
        }

        $dealIds = $request->input('deal_ids', []);
        if (count($dealIds) === 0) {
            return redirect()->back()->with('info', 'No deals selected.');
        }

        Deal::whereIn('id', $dealIds)->update(['user_id' => $partner->id]);

        return redirect()->back()->with('success', count($dealIds) . ' deal(s) assigned to ' . $partner->full_name . '. They will appear in the partner dashboard.');
    }

    protected function ensureAdminOrSuperAdmin(): void
    {
        $role = optional(Auth::user()->role)->name;
        if (!in_array($role, ['Super Admin', 'Admin'], true)) {
            abort(403, 'Unauthorized.');
        }
    }

    public function editUser($id)
    {
        $hashids = $this->getHashids();
        $decodedIds = $hashids->decode($id);
        
        if (empty($decodedIds)) {
            return redirect()->route('admin.users')->with('error', 'Invalid user ID.');
        }
        
        $userId = $decodedIds[0];
        $user = User::with('role')->findOrFail($userId);
        
        // Get all roles except Super Admin
        $roles = Role::where('name', '!=', 'Super Admin')->get();
        
        return view('admin.pages.users.edit', compact('user', 'roles', 'hashids'));
    }

    public function showUser($id)
    {
        $hashids = $this->getHashids();
        $decodedIds = $hashids->decode($id);

        if (empty($decodedIds)) {
            return redirect()->route('admin.users')->with('error', 'Invalid user ID.');
        }

        $userId = $decodedIds[0];
        $user = User::with('role')->findOrFail($userId);
        $hashids = $this->getHashids();

        return view('admin.pages.users.view', compact('user', 'hashids'));
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|boolean',
            'role_id' => 'required|exists:roles,id',
        ]);

        $hashids = $this->getHashids();
        $decodedIds = $hashids->decode($id);
        
        if (empty($decodedIds)) {
            return redirect()->route('admin.users')->with('error', 'Invalid user ID.');
        }
        
        $userId = $decodedIds[0];
        $user = User::findOrFail($userId);
        
        // Check if role is being changed to Partner
        $oldRoleId = $user->role_id;
        $oldStatus = $user->status;

        $user->update(array_merge(
            $request->only(['firstname', 'lastname', 'email', 'phone', 'status', 'role_id']),
            ['is_suspended' => $request->boolean('is_suspended')]
        ));
        
        // Send email if user is promoted to Partner role and status is active
        $partnerRole = Role::where('name', 'Partner')->first();
        if ($partnerRole && $request->role_id == $partnerRole->id) {
            try {
                $statusBecameActive = (int) $oldStatus !== 1 && (int) $request->status === 1;
                $roleJustChangedToPartner = $oldRoleId != $partnerRole->id;

                if ($roleJustChangedToPartner && (int) $request->status === 0) {
                    // New partner set to pending - acknowledge receipt
                    Mail::to($user->email)->send(new PartnerRegistration($user));
                    Log::info('Partner registration acknowledgement sent', ['user_id' => $user->id]);
                }

                if ($statusBecameActive || ($roleJustChangedToPartner && (int) $request->status === 1)) {
                    Mail::to($user->email)->send(new PartnerAccepted($user));
                    Log::info('Partner acceptance email sent', ['user_id' => $user->id]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send partner email', ['error' => $e->getMessage(), 'user_id' => $user->id]);
            }
        }
        
        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    public function deleteUser($id)
    {
        $hashids = $this->getHashids();
        $decodedIds = $hashids->decode($id);
        
        if (empty($decodedIds)) {
            return redirect()->route('admin.users')->with('error', 'Invalid user ID.');
        }
        
        $userId = $decodedIds[0];
        $user = User::findOrFail($userId);
        
        // Don't allow deleting the current user
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')->with('error', 'You cannot delete your own account.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }


    // Payments Management
    public function payments()
    {
        return view('admin.pages.payments.index');
    }

    public function viewPayment($id)
    {
        return view('admin.pages.payments.view', compact('id'));
    }

    // Media Management
    public function media()
    {
        return view('admin.pages.media.index');
    }

    public function uploadMedia(Request $request)
    {
        // Add media upload logic here
        return redirect()->route('admin.media')->with('success', 'Media uploaded successfully!');
    }

    public function deleteMedia($id)
    {
        // Add media deletion logic here
        return redirect()->route('admin.media')->with('success', 'Media deleted successfully!');
    }

    // Settings Management
    public function generalSettings()
    {
        return view('admin.pages.settings.general');
    }

    public function updateGeneralSettings(Request $request)
    {
        // Add general settings update logic here
        return redirect()->route('admin.settings.general')->with('success', 'General settings updated successfully!');
    }

    public function securitySettings()
    {
        return view('admin.pages.settings.security');
    }

    public function updateSecuritySettings(Request $request)
    {
        // Add security settings update logic here
        return redirect()->route('admin.settings.security')->with('success', 'Security settings updated successfully!');
    }

    // Profile Management
    public function profile()
    {
        return view('admin.pages.profile.view');
    }

    public function editProfile()
    {
        return view('admin.pages.profile.edit');
    }

    public function updateProfile(Request $request)
    {
        // Add profile update logic here
        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully!');
    }

    // My Bookings
    public function myBookings()
    {
        return view('admin.pages.my-bookings.index');
    }

    // Convert slug to page name
    private function slugToPageName($slug)
    {
        $slugMap = [
            'about-us' => 'About Us',
            'become-a-partner' => 'Become a Partner',
            'our-commitment' => 'Our Commitment',
            'terms-conditions' => 'Terms & Conditions',
            'privacy-policy' => 'Privacy Policy',
        ];

        return $slugMap[$slug] ?? null;
    }

    // manageContent
    public function manageContent($slug)
    {
        $pageName = $this->slugToPageName($slug);
        
        if (!$pageName) {
            abort(404, 'Page not found');
        }

        $page = Pages::where('page', $pageName)->first();
        
        if (!$page) {
            abort(404, 'Page content not found');
        }

        return view('admin.pages.page_content', compact('page', 'slug'));
    }

    public function updateContent(Request $request, $slug)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $pageName = $this->slugToPageName($slug);
        
        if (!$pageName) {
            abort(404, 'Page not found');
        }

        $page = Pages::where('page', $pageName)->first();
        
        if (!$page) {
            abort(404, 'Page content not found');
        }

        $page->content = $request->content;
        $page->save();

        return redirect()->route('admin.manage.content', $slug)->with('success', 'Content updated successfully!');
    }

    // Contact Messages
    public function contactMessages()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(20);
        $hashids = $this->getHashids();
        return view('admin.pages.contact_messages', compact('messages', 'hashids'));
    }

    public function viewContactMessage($id)
    {
        $hashids = $this->getHashids();
        
        // Decode the hashed ID
        $decodedIds = $hashids->decode($id);
        if (empty($decodedIds)) {
            abort(404, 'Message not found');
        }
        $messageId = $decodedIds[0];
        
        $message = ContactMessage::findOrFail($messageId);
        
        // Mark as read if it's new
        if ($message->status === 'new') {
            $message->status = 'read';
            $message->save();
        }

        $hashedId = $id;
        return view('admin.pages.view_contact_message', compact('message', 'hashedId'));
    }

    public function updateMessageStatus(Request $request, $id)
    {
        $hashids = $this->getHashids();
        
        // Decode the hashed ID
        $decodedIds = $hashids->decode($id);
        if (empty($decodedIds)) {
            abort(404, 'Message not found');
        }
        $messageId = $decodedIds[0];
        
        $message = ContactMessage::findOrFail($messageId);
        $message->status = $request->status;
        $message->save();

        return redirect()->back()->with('success', 'Message status updated successfully!');
    }

    public function deleteContactMessage($id)
    {
        $hashids = $this->getHashids();
        
        // Decode the hashed ID
        $decodedIds = $hashids->decode($id);
        if (empty($decodedIds)) {
            abort(404, 'Message not found');
        }
        $messageId = $decodedIds[0];
        
        $message = ContactMessage::findOrFail($messageId);
        $message->delete();

        return redirect()->route('admin.contact.messages')->with('success', 'Message deleted successfully!');
    }

    // System Settings
    public function systemSettings()
    {
        $settings = System::first();
        
        // If no settings exist, create default
        if (!$settings) {
            $settings = System::create([
                'email' => 'info@zanzibarbookings.com',
                'phone' => '+255 774 378835',
                'address' => 'Zanzibar, Tanzania',
            ]);
        }

        return view('admin.pages.system_settings', compact('settings'));
    }

    public function updateSystemSettings(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'secondary_phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'about_text' => 'nullable|string',
            'whatsapp_url' => 'nullable|url|max:500',
            'facebook_url' => 'nullable|url|max:500',
            'instagram_url' => 'nullable|url|max:500',
            'tripadvisor_url' => 'nullable|url|max:500',
            'youtube_url' => 'nullable|url|max:500',
            'header_photo' => 'nullable|image',
            'video_file' => 'nullable',
        ]);

        $settings = System::first();
        
        if (!$settings) {
            $settings = new System();
        }

        // Handle header photo upload
        if ($request->hasFile('header_photo')) {
            // Delete old photo if exists
            if ($settings->header_photo && Storage::disk('public')->exists($settings->header_photo)) {
                Storage::disk('public')->delete($settings->header_photo);
            }
            
            $headerPhoto = $request->file('header_photo');
            $headerPhotoName = time() . '_header.' . $headerPhoto->getClientOriginalExtension();
            $headerPhotoPath = $headerPhoto->storeAs('system', $headerPhotoName, 'public');
            $settings->header_photo = $headerPhotoPath;
        }

        // Handle video file upload
        if ($request->hasFile('video_file')) {
            // Delete old video if exists
            if ($settings->video_file && Storage::disk('public')->exists($settings->video_file)) {
                Storage::disk('public')->delete($settings->video_file);
            }
            
            $videoFile = $request->file('video_file');
            $videoFileName = time() . '_video.' . $videoFile->getClientOriginalExtension();
            $videoFilePath = $videoFile->storeAs('system', $videoFileName, 'public');
            $settings->video_file = $videoFilePath;
        }

        // Update other fields
        $settings->fill($request->except(['header_photo', 'video_file']));
        $settings->save();

        return redirect()->back()->with('success', 'System settings updated successfully!');
    }

    public function homeSeoSettings()
    {
        $settings = System::first();
        if (!$settings) {
            $settings = new System();
        }
        return view('admin.pages.home_seo', compact('settings'));
    }

    public function updateHomeSeoSettings(Request $request)
    {
        $request->validate([
            'home_seo_title' => 'nullable|string|max:60',
            'home_seo_description' => 'nullable|string|max:160',
            'home_seo_keywords' => 'nullable|string',
            'home_seo_image' => 'nullable|image',
        ]);

        $settings = System::first();
        if (!$settings) {
            $settings = new System();
        }

        if ($request->hasFile('home_seo_image')) {
            if ($settings->home_seo_image && Storage::disk('public')->exists($settings->home_seo_image)) {
                Storage::disk('public')->delete($settings->home_seo_image);
            }
            $settings->home_seo_image = $request->file('home_seo_image')->store('system/seo', 'public');
        }

        $settings->home_seo_title = $request->home_seo_title;
        $settings->home_seo_description = $request->home_seo_description;
        $settings->home_seo_keywords = $request->home_seo_keywords;
        $settings->save();

        return redirect()->back()->with('success', 'Home page SEO settings updated successfully!');
    }
}
