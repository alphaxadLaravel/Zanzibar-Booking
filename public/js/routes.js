// Dynamic route utility for Zanzibar Booking application
// Production: https://172.20.20.196/survey/ (deployed in survey folder)
// Local: http://127.0.0.1/ (local development)

// Detect if we're in production by checking the current URL
const isDevelopment = window.location.hostname === "127.0.0.1" || 
                     window.location.hostname === "localhost" || 
                     window.location.hostname.includes("127.0.0.1");

// Set the base path based on environment
const BASE_PATH = "";

// Get current base URL
const getBaseUrl = () => {
    // Always return the current URL to maintain the /survey/ path
    return window.location.href.split('?')[0]; // Remove query parameters if any
};

// Debug logging
console.log("Route Detection:", {
    hostname: window.location.hostname,
    isDevelopment: isDevelopment,
    BASE_PATH: BASE_PATH,
    currentUrl: window.location.href,
});

// Route helper functions - matching actual Laravel routes
export const routes = {
    // Main website routes
    index: () => `${getBaseUrl()}/`,
    search: () => `${getBaseUrl()}/search`,
    contactUs: () => `${getBaseUrl()}/contact-us`,
    blog: () => `${getBaseUrl()}/blog`,
    viewBlog: () => `${getBaseUrl()}/view/blog`,
    
    // Deal routes
    hotels: () => `${getBaseUrl()}/hotels`,
    apartments: () => `${getBaseUrl()}/apartments`,
    tours: () => `${getBaseUrl()}/tours`,
    cars: () => `${getBaseUrl()}/cars`,
    flights: () => `${getBaseUrl()}/flights`,
    
    // Booking routes
    confirmBooking: () => `${getBaseUrl()}/confirm-booking`,
    processBooking: () => `${getBaseUrl()}/process-booking`,
    
    // Admin routes
    dashboard: () => `${getBaseUrl()}/dashboard`,
    adminHotels: () => `${getBaseUrl()}/admin/hotels`,
    adminApartments: () => `${getBaseUrl()}/admin/apartments`,
    adminTours: () => `${getBaseUrl()}/admin/tours`,
    adminCars: () => `${getBaseUrl()}/admin/cars`,
    adminBookings: () => `${getBaseUrl()}/admin/bookings`,
    adminUsers: () => `${getBaseUrl()}/admin/users`,
    adminMedia: () => `${getBaseUrl()}/admin/media`,
    adminSettings: () => `${getBaseUrl()}/admin/settings/general`,
    adminProfile: () => `${getBaseUrl()}/admin/profile`,
    
    // Legacy functions that might be expected by existing JavaScript
    reports: () => getBaseUrl(),
    login: () => getBaseUrl(),
    logout: () => getBaseUrl(),
    searchSurveys: () => getBaseUrl(),
    preview: () => getBaseUrl(),
    saveForm: () => getBaseUrl(),
    create: () => getBaseUrl(),
    board: () => getBaseUrl(),
    edit: () => getBaseUrl(),
    attend: () => getBaseUrl(),
    initiateSurvey: () => getBaseUrl(),
    reportDetail: () => getBaseUrl(),
    approval: () => getBaseUrl(),

    // API routes - matching actual Laravel routes
    api: {
        // Deal reviews
        storeReview: (dealId) => `${getBaseUrl()}/deals/${dealId}/reviews`,
        
        // Legacy API functions that might be expected by existing JavaScript
        formData: () => getBaseUrl(),
        surveys: {
            stats: () => getBaseUrl(),
            results: () => getBaseUrl(),
            submit: () => getBaseUrl(),
            existingResponse: () => getBaseUrl(),
            completionStatus: () => getBaseUrl(),
            publish: () => getBaseUrl(),
            unpublish: () => getBaseUrl(),
            reports: () => getBaseUrl(),
            reportDetail: () => getBaseUrl(),
            downloadReport: () => getBaseUrl(),
            approve: () => getBaseUrl(),
            reject: () => getBaseUrl(),
            requestApproval: () => getBaseUrl(),
            deactivate: () => getBaseUrl(),
            reactivate: () => getBaseUrl(),
            forceAttendance: () => getBaseUrl(),
        },
        users: {
            list: () => getBaseUrl(),
            loginAs: () => getBaseUrl(),
            validateNida: () => getBaseUrl(),
            logout: () => getBaseUrl(),
            current: () => getBaseUrl(),
            testSession: () => getBaseUrl(),
            clearSession: () => getBaseUrl(),
        },
        
        // Admin API routes
        admin: {
            // Hotel management
            storeHotelRoom: (hotelId) => `${getBaseUrl()}/admin/hotels/${hotelId}/rooms/store`,
            updateHotelRoom: (hotelId, roomId) => `${getBaseUrl()}/admin/hotels/${hotelId}/rooms/${roomId}`,
            deleteHotelRoom: (hotelId, roomId) => `${getBaseUrl()}/admin/hotels/${hotelId}/rooms/${roomId}`,
            
            // Apartment management
            storeApartment: () => `${getBaseUrl()}/admin/apartments/store`,
            updateApartment: (id) => `${getBaseUrl()}/admin/apartments/${id}`,
            deleteApartment: (id) => `${getBaseUrl()}/admin/apartments/${id}`,
            
            // Tour management
            storeTour: () => `${getBaseUrl()}/admin/tours/store`,
            updateTour: (id) => `${getBaseUrl()}/admin/tours/${id}`,
            deleteTour: (id) => `${getBaseUrl()}/admin/tours/${id}`,
            
            // Car management
            storeCar: () => `${getBaseUrl()}/admin/cars/store`,
            updateCar: (id) => `${getBaseUrl()}/admin/cars/${id}`,
            deleteCar: (id) => `${getBaseUrl()}/admin/cars/${id}`,
            
            // Media management
            uploadMedia: () => `${getBaseUrl()}/admin/media/upload`,
            deleteMedia: (id) => `${getBaseUrl()}/admin/media/${id}`,
            
            // Settings
            updateGeneralSettings: () => `${getBaseUrl()}/admin/settings/general`,
            updateSecuritySettings: () => `${getBaseUrl()}/admin/settings/security`,
            
            // Profile
            updateProfile: () => `${getBaseUrl()}/admin/profile`,
        }
    },
};

// Export individual route functions for convenience
export const {
    index,
    search,
    contactUs,
    blog,
    hotels,
    apartments,
    tours,
    cars,
    flights,
    confirmBooking,
    processBooking,
    dashboard,
    adminHotels,
    adminApartments,
    adminTours,
    adminCars,
    adminBookings,
    adminUsers,
    adminMedia,
    adminSettings,
    adminProfile,
    // Legacy exports for backward compatibility
    reports,
    login,
    logout,
    searchSurveys,
    preview,
    saveForm,
    create,
    board,
    edit,
    attend,
    initiateSurvey,
    reportDetail,
    approval,
} = routes;

export const api = routes.api;

// Debug logging - always log in browser console
console.log("Route configuration:", {
    isDevelopment,
    BASE_PATH,
    currentUrl: window.location.href,
    hostname: window.location.hostname,
    origin: window.location.origin,
    baseUrl: getBaseUrl(),
});

// Helper function to test routes
export const testRoutes = () => {
    console.log("Testing Zanzibar Booking routes:");
    console.log("Index:", routes.index());
    console.log("Hotels:", routes.hotels());
    console.log("Tours:", routes.tours());
    console.log("Dashboard:", routes.dashboard());
    console.log("Admin Hotels:", routes.adminHotels());
    console.log("API Store Review:", routes.api.storeReview(123));
    console.log("Current URL:", window.location.href);
};

// Make testRoutes available globally for debugging
if (typeof window !== 'undefined') {
    window.testRoutes = testRoutes;
}
