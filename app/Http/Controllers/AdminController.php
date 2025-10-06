<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Deal;
use App\Models\Booking;
use App\Models\Tours;
use App\Models\Car;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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
        // Real counts from database
        $stats = [
            'tours_count' => Deal::where('type', 'tour')->count(),
            'hotels_count' => Deal::where('type', 'hotel')->count(),
            'apartments_count' => Deal::where('type', 'apartment')->count(),
            'bookings_count' => Booking::count(),
            'cars_count' => Deal::where('type', 'car')->count(),
            'blog_posts_count' => Blog::count(),
            'site_visits_count' => 2847, // Keep static for now - would need analytics implementation
            'total_revenue' => 45250.75, // Keep static for now - would need payment calculation
        ];

        return view('admin.pages.dashboard', compact('stats'));
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
            'cover_photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // Image required
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
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
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
        return view('admin.pages.users.index');
    }

    public function createUser()
    {
        return view('admin.pages.users.create');
    }

    public function storeUser(Request $request)
    {
        // Add user creation logic here
        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    public function editUser($id)
    {
        return view('admin.pages.users.edit', compact('id'));
    }

    public function updateUser(Request $request, $id)
    {
        // Add user update logic here
        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    public function deleteUser($id)
    {
        // Add user deletion logic here
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

    public function userRoles()
    {
        return view('admin.pages.users.roles');
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
}
