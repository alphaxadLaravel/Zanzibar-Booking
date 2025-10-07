<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'page' => 'About Us',
                'content' => 'At Zanzibar Bookings, we believe every journey should be more than just a trip—it should be an unforgettable experience. Based in Zanzibar, we are a trusted local travel and tour company dedicated to creating tailor-made adventures that showcase the beauty, culture, and spirit of our islands.

Our passionate team of travel experts carefully designs each package to meet the needs of families, couples, groups, and solo travelers. From exploring the winding streets of Stone Town and the fragrant spice plantations, to sailing the turquoise waters on a traditional dhow, or relaxing on pristine beaches, every detail is thoughtfully arranged.

What sets us apart is our commitment to excellence, authenticity, and reliability. We don\'t just sell tours—we craft meaningful experiences backed by:

Personalized Service – Every traveler receives attentive care, from planning to departure.
Local Expertise – As a Zanzibar-based company, we know the hidden gems, cultural traditions, and best spots to make your trip truly unique.
Trusted Safety & Comfort – All our packages include insurance, professional guides, and safe transportation so you can travel with peace of mind.
Diverse Experiences – Whether you want luxury, adventure, culture, volunteering, or family fun, we offer flexible itineraries to match your travel style.

With Zanzibar Bookings, you are not just booking a trip—you are joining a family that values trust, transparency, and unforgettable memories. Many of our guests arrive as visitors but leave as friends, already planning their return.

"Discover Zanzibar with confidence. Travel with Zanzibar Bookings."',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'Become a Partner',
                'content' => 'At Zanzibar Bookings, we believe in building strong partnerships that create value for both travelers and service providers. Our mission is to connect visitors from around the world with the best hotels, lodges, tours, and experiences Zanzibar has to offer — and we\'d love for you to be part of this journey.

Why Partner With Us?
Global Reach – Gain access to a wide audience of international and local travelers actively seeking trusted services in Zanzibar.
Increased Bookings – Showcase your property, tours, or services on our platform and boost your sales with minimal commission rates.
Trusted Brand – Partner with a reputable, insured, and established travel company that travelers already know and trust.
Marketing Support – Benefit from our promotional campaigns, digital marketing, and social media visibility to attract more guests.
Seamless Process – Our team ensures smooth coordination, timely payments, and transparent communication.

Our Commitment to Partners
We are committed to fair collaboration, mutual growth, and long-term relationships. Your success is our success, and together we can offer travelers unforgettable experiences while strengthening Zanzibar\'s tourism industry.

Join us today and grow with Zanzibar Bookings.

For partnership inquiries, contact us at click here',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'Our Commitment',
                'content' => 'At Zanzibar Bookings, our promise is simple: we put travelers first. We are committed to delivering safe, seamless, and memorable experiences that reflect the true spirit of Zanzibar.

What We Stand For
Trust & Transparency – Clear pricing, honest communication, and no hidden surprises.
Quality & Care – We carefully select our partners, guides, and services to ensure comfort, safety, and value.
Authenticity – Our tours are designed to let you experience Zanzibar\'s culture, nature, and people in a genuine way.
Safety & Insurance – Every package comes with insurance coverage and professional support, so you can travel with peace of mind.
Sustainability – We respect local communities and the environment, supporting responsible tourism that benefits Zanzibar and its people.

Our Promise to You
When you travel with us, you are more than just a guest — you are part of our Zanzibar family. From your first inquiry until the moment you return home, we are here to listen, assist, and make sure every detail of your journey is taken care of.

"Your happiness, safety, and trust are the heart of everything we do."',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'Terms & Conditions',
                'content' => 'Welcome to Zanzibar Bookings. By making a booking with us, you agree to the following terms and conditions. These are designed to ensure clarity, transparency, and a smooth travel experience.

1. Bookings & Payments
A confirmed booking requires a deposit or full payment, depending on the package selected.
Payments can be made securely through our trusted payment partners.
Final balances must be cleared before the start of your trip unless otherwise agreed.

2. Cancellations & Refunds
Cancellations made in advance may qualify for a partial refund, depending on the service provider\'s policy.
Certain services (such as last-minute tours, flights, or hotel bookings) may be non-refundable.
Zanzibar Bookings will always do its best to minimize cancellation charges for our guests.

3. Changes to Itinerary
While we strive to deliver exactly what is booked, changes may occur due to weather, availability, or circumstances beyond our control.
If substitutions are needed, we will provide equal or better alternatives where possible.

4. Traveler Responsibilities
Travelers are responsible for ensuring they have valid passports, visas, travel documents, and insurance.
Please inform us in advance of any special needs, medical conditions, or dietary requirements.

5. Liability
Zanzibar Bookings works with trusted hotels, transport providers, and activity operators but is not liable for injuries, losses, delays, or damages beyond our direct control.
All our packages include insurance coverage for added safety and peace of mind.

6. Privacy & Data Protection
Your personal information is protected in line with our Privacy Policy. We only share details with service providers directly involved in your booking.

7. Acceptance of Terms
By confirming your booking with us, you acknowledge that you have read, understood, and accepted these Terms & Conditions.

Contact Us
For any questions, clarifications, or special requests, please contact us by click here',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page' => 'Privacy Policy',
                'content' => 'At Zanzibar Bookings, your trust is our priority. We respect your privacy and are committed to protecting any personal information you share with us when booking your travel experiences.

Information We Collect
When you use our website or services, we may collect:

Personal details (name, email, phone number, nationality) for booking purposes.
Payment details processed securely through trusted third-party providers.
Travel preferences to help us customize your experience.

How We Use Your Information
Your information is used only to:

Confirm and manage your bookings.
Provide customer support and personalized travel services.
Improve our website, products, and traveler experience.
Send important updates or offers (you can unsubscribe anytime).

How We Protect Your Information
We use secure systems and encryption to safeguard your data. We never sell, rent, or trade your personal information to third parties. Data is shared only with trusted service providers (such as hotels, airlines, or activity partners) necessary to complete your booking.

Your Rights
You have the right to:

Access and update your personal information.
Request deletion of your data after your booking is completed.
Opt out of marketing communications at any time.

Cookies & Online Tracking
Our website uses cookies to improve functionality and give you a smoother browsing experience. You can choose to disable cookies in your browser settings.

Contact Us
If you have any questions about our Privacy Policy or how your data is handled, please contact us by click here',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('pages')->insert($pages);
    }
}

