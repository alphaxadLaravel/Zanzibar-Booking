@extends('website.layouts.app')

@section('pages')
<section class="gallery">
    <div class="gmz-carousel-with-lightbox" data-count="33">
        @for ($i = 0; $i < 10; $i++) <a
            href="{{asset('https://www.zanzibarbookings.com/storage/2024/02/28/emarald-michamvi-34-1709067436.jpg')}}">
            <img src="{{asset('https://www.zanzibarbookings.com/storage/2024/02/28/emarald-michamvi-34-1709067436.jpg')}}"
                alt="home slider" />
            </a>
            @endfor
    </div>
</section>
<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="../index.html">Home</a></li>
            <li><span>Sampo Tour</span></li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-8 pb-5">
            <h4 class="post-title">
                <div class="add-wishlist-wrapper">
                    <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup" data-effect="mfp-zoom-in"></a>
                </div>
                Zanzibar Slave Route &amp; Heritage Tour
            </h4>
            <p class="location text-primary">
                <i class="fal fa-map-marker-alt"></i> Stone Town, Mbweni Ruins &amp; Mangapwani Slave cave
            </p>

            <div class="meta">
                <ul class="row gx-3 gy-2" style="list-style:none;padding:0;margin:0;">
                    <li class="col-6 col-md-4 mb-2">
                        <div class="d-flex flex-column">
                            <span class="label text-muted" style="font-size:13px;">Type</span>
                            <span class="value fw-semibold" style="font-size:15px;">Test Tour</span>
                        </div>
                    </li>
                    <li class="col-6 col-md-4 mb-2">
                        <div class="d-flex flex-column">
                            <span class="label text-muted" style="font-size:13px;">Period</span>
                            <span class="value fw-semibold" style="font-size:15px;">2 Days</span>
                        </div>
                    </li>
                    <li class="col-6 col-md-4 mb-2">
                        <div class="d-flex flex-column">
                            <span class="label text-muted" style="font-size:13px;">Maximu Group Size</span>
                            <span class="value fw-semibold" style="font-size:15px;">25 people</span>
                        </div>
                    </li>

                </ul>

            </div>

            <section class="description">
                <h4 class="section-title">Detail</h4>
                <div class="section-content">
                    <p>Step back in time and uncover Zanzibar’s rich past on this immersive full-day historical tour
                        that links Mbweni Ruins, Stone Town, and Mangapwani Slave cave for a “real Zanzibar history”
                        experience. Wander through the winding alleys of Stone Town, a UNESCO World Heritage Site, where
                        you’ll explore centuries-old architecture, bustling markets, and the moving Anglican Cathedral
                        built on the site of the old slave market.&nbsp;</p>
                    <p>Continue to the atmospheric Mbweni Ruins, once was home to a mission settlement established in
                        19th-century as missionary settlement and girls’ school by the Universities’ Mission to Central
                        Africa. It included a church, hospital, and a school built to house and educate freed slave
                        girls, offering them shelter, skills, and a new life. Today, its stone remains and quiet gardens
                        stand quietly amidst tropical gardens by the sea as a reminder of Zanzibar’s missionary history
                        and the island’s role in the abolition era.</p>
                    <p>The journey ends at Mangapwani, where the Slave Cave and Coral Cave reveal the island’s deeper
                        history of the slave trade and cultural heritage. . Zanzibar Slave Route &amp; Heritage is a
                        sobering and educational experience that aims to help you understand the impact of slavery on
                        Zanzibar's culture and society .But also corrections to human behaviour which left many scars of
                        sites to warn us never allow such horrific acts to be repeated again in human life. <span
                            style="color: rgb(81, 83, 101);">It is also a blending heritage, culture, and storytelling,
                            offering travelers a true understanding of Zanzibar’s historical legacy</span></p>
                </div>
            </section>

            <section class="feature">
                <h4 class="section-title mb-4">Itinerary</h4>
                <div class="section-content">
                    <div class="accordion" id="accordionItinerary">
                        <div class="card">
                            <div class="card-header" id="heading1">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left" type="button"
                                        data-toggle="collapse" data-target="#collapse1" aria-expanded="true"
                                        aria-controls="collapse1">
                                        <i class="fa fa-map-marker-alt mr-2"></i>
                                        Pick up from your hotel
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse1" class="collapse show" aria-labelledby="heading1"
                                data-parent="#accordionItinerary">
                                <div class="card-body">
                                    <strong>08:30</strong> &mdash; Pick-up your hotel &rarr; safety brief, water on
                                    board.
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="heading2">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button"
                                        data-toggle="collapse" data-target="#collapse2" aria-expanded="false"
                                        aria-controls="collapse2">
                                        <i class="fa fa-walking mr-2"></i>
                                        Stone Town Walking tour
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse2" class="collapse" aria-labelledby="heading2"
                                data-parent="#accordionItinerary">
                                <div class="card-body">
                                    <strong>08:45–11:30</strong> &mdash; Stone Town Walking History Tour. Visiting Old
                                    Fort, Forodhani, House of Wonders, Old Dispensary, Darajani Market. Enter Anglican
                                    Cathedral &amp; Old Slave Market Museum (guided).
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="heading3">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button"
                                        data-toggle="collapse" data-target="#collapse3" aria-expanded="false"
                                        aria-controls="collapse3">
                                        <i class="fa fa-monument mr-2"></i>
                                        Mbweni Ruins
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse3" class="collapse" aria-labelledby="heading3"
                                data-parent="#accordionItinerary">
                                <div class="card-body">
                                    <strong>11:30–12:15</strong> &mdash; Transfer to Mbweni Ruins (~15 min drive;
                                    exploration &amp; photos ~30–40 min).
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="heading4">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button"
                                        data-toggle="collapse" data-target="#collapse4" aria-expanded="false"
                                        aria-controls="collapse4">
                                        <i class="fa fa-utensils mr-2"></i>
                                        Stop for lunch and soft drinks
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse4" class="collapse" aria-labelledby="heading4"
                                data-parent="#accordionItinerary">
                                <div class="card-body">
                                    <strong>12:30–13:30</strong> &mdash; Lunch stop (Stone Town/Mbweni area—local
                                    Zanzibari cuisine).
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="heading5">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button"
                                        data-toggle="collapse" data-target="#collapse5" aria-expanded="false"
                                        aria-controls="collapse5">
                                        <i class="fa fa-car mr-2"></i>
                                        Transfer to Mangapwani Slave cave
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse5" class="collapse" aria-labelledby="heading5"
                                data-parent="#accordionItinerary">
                                <div class="card-body">
                                    <strong>13:30–14:15</strong> &mdash; Scenic coastal drive to Mangapwani (~25 km
                                    north).
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="heading6">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button"
                                        data-toggle="collapse" data-target="#collapse6" aria-expanded="false"
                                        aria-controls="collapse6">
                                        <i class="fa fa-mountain mr-2"></i>
                                        Mangapwani Slave Cave &amp; Coral Cave
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse6" class="collapse" aria-labelledby="heading6"
                                data-parent="#accordionItinerary">
                                <div class="card-body">
                                    <strong>14:15–16:00</strong> &mdash; Mangapwani Slave Cave &amp; Coral Cave (site
                                    guide included with entry in many cases). Time for quiet reflection and beach views.
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="heading7">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button"
                                        data-toggle="collapse" data-target="#collapse7" aria-expanded="false"
                                        aria-controls="collapse7">
                                        <i class="fa fa-route mr-2"></i>
                                        Mangapwani Slave cave to Your hotel
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse7" class="collapse" aria-labelledby="heading7"
                                data-parent="#accordionItinerary">
                                <div class="card-body">
                                    <strong>16:00–17:00</strong> &mdash; Return drive to Stone Town.
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="heading8">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button"
                                        data-toggle="collapse" data-target="#collapse8" aria-expanded="false"
                                        aria-controls="collapse8">
                                        <i class="fa fa-home mr-2"></i>
                                        Drop you off
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse8" class="collapse" aria-labelledby="heading8"
                                data-parent="#accordionItinerary">
                                <div class="card-body">
                                    <strong>17:00–17:30</strong> &mdash; Drop-off at hotel
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="inex my-3">
                <h4 class="section-title">What's Included & Not Included</h4>
                <div class="section-content">
                    <div class="row">
                        <!-- Includes -->
                        <div class="col-lg-6 include">
                            <h4 class="mb-3" style="font-size:1.1rem;font-weight:600;color:#2e8b57;">
                                <i class="fal fa-check-circle me-2"></i>Included
                            </h4>
                            <ul class="list-unstyled mb-0">
                                <li class="item d-flex align-items-baseline mb-2">
                                    <i class="fal fa-check text-success me-2"></i>
                                    <span>Professional English-speaking guide throughout tours</span>
                                </li>
                                <li class="item d-flex align-items-baseline mb-2">
                                    <i class="fal fa-check text-success me-2"></i>
                                    <span>Specialized bilingual guide</span>
                                </li>
                                <li class="item d-flex align-items-baseline mb-2">
                                    <i class="fal fa-check text-success me-2"></i>
                                    <span>Full day adventure trip</span>
                                </li>
                                <li class="item d-flex align-items-baseline mb-2">
                                    <i class="fal fa-check text-success me-2"></i>
                                    <span>Stone Town historical tour</span>
                                </li>
                                <li class="item d-flex align-items-baseline mb-2">
                                    <i class="fal fa-check text-success me-2"></i>
                                    <span>Pick up and drop off from your hotel</span>
                                </li>
                                <li class="item d-flex align-items-baseline mb-2">
                                    <i class="fal fa-check text-success me-2"></i>
                                    <span>Private transport</span>
                                </li>
                                <li class="item d-flex align-items-baseline mb-2">
                                    <i class="fal fa-check text-success me-2"></i>
                                    <span>Parking fees</span>
                                </li>
                                <li class="item d-flex align-items-baseline mb-2">
                                    <i class="fal fa-check text-success me-2"></i>
                                    <span>Entrance fees</span>
                                </li>
                                <li class="item d-flex align-items-baseline mb-2">
                                    <i class="fal fa-check text-success me-2"></i>
                                    <span>Public liability insurance</span>
                                </li>
                                <li class="item d-flex align-items-baseline mb-2">
                                    <i class="fal fa-check text-success me-2"></i>
                                    <span>Government taxes</span>
                                </li>
                                <li class="item d-flex align-items-baseline mb-2">
                                    <i class="fal fa-check text-success me-2"></i>
                                    <span>Snacks</span>
                                </li>
                                <li class="item d-flex align-items-baseline mb-2">
                                    <i class="fal fa-check text-success me-2"></i>
                                    <span>Superb Swahili dish</span>
                                </li>
                                <li class="item d-flex align-items-baseline mb-2">
                                    <i class="fal fa-check text-success me-2"></i>
                                    <span>Pure mineral drinking water</span>
                                </li>
                            </ul>
                        </div>
                        <!-- Excludes -->
                        <div class="col-lg-6 exclude">
                            <h4 class="mb-3" style="font-size:1.1rem;font-weight:600;color:#d9534f;">
                                <i class="fal fa-times-circle me-2"></i>Not Included
                            </h4>
                            <ul class="list-unstyled mb-0">
                                <li class="item d-flex align-items-baseline mb-2">
                                    <i class="fal fa-times text-danger me-2"></i>
                                    <span>Accommodation</span>
                                </li>
                                <li class="item d-flex align-items-baseline mb-2">
                                    <i class="fal fa-times text-danger me-2"></i>
                                    <span>Fuel surcharge</span>
                                </li>
                                <li class="item d-flex align-items-baseline mb-2">
                                    <i class="fal fa-times text-danger me-2"></i>
                                    <span>Bus fare</span>
                                </li>
                                <li class="item d-flex align-items-baseline mb-2">
                                    <i class="fal fa-times text-danger me-2"></i>
                                    <span>Tickets</span>
                                </li>
                                <li class="item d-flex align-items-baseline mb-2">
                                    <i class="fal fa-times text-danger me-2"></i>
                                    <span>Additional services</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>


            <section class="policy my-3">
                <h4 class="section-title">Policies</h4>
                <div class="section-content">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <strong>Free Cancellation:</strong> Customers can cancel this tour up to <strong>7
                                days</strong> before departure.
                        </li>
                        <li class="mb-2">
                            <strong>Full Refund:</strong> Cancellations made <strong>15 to 30 days</strong> before
                            departure are eligible for a <span class="text-success">100% refund</span>.
                        </li>
                        <li class="mb-2">
                            <strong>Partial Refund:</strong> Cancellations made <strong>10 to 14 days</strong> prior
                            will incur a <span class="text-warning">50% charge</span>.
                        </li>
                        <li class="mb-2">
                            <strong>Limited Refund:</strong> Cancellations made <strong>7 to 9 days</strong> prior are
                            eligible for a <span class="text-info">30% refund</span>.
                        </li>
                        <li class="mb-2">
                            <strong>No Refund:</strong> Cancellations made within <strong>6 days to 48 hours</strong>
                            before departure are <span class="text-danger">non-refundable</span>.
                        </li>
                    </ul>
                </div>
            </section>

            <section class="map">
                <h4 class="section-title">Tour On Map</h4>



                <div class="form-group">
                    <input type="hidden" id="address-input" name="address_address"
                        class="form-control map-input pac-target-input" placeholder="Enter a location"
                        autocomplete="off">
                    <input type="hidden" name="address_latitude" id="address-latitude" value="-6.162222">
                    <input type="hidden" name="address_longitude" id="address-longitude" value="39.192073">
                </div>
                <div id="address-map-container" style="width:100%;height:400px; ">
                    <div style="width: 100%; height: 100%; position: relative; overflow: hidden;" id="address-map">
                        <div
                            style="height: 100%; width: 100%; position: absolute; top: 0px; left: 0px; background-color: rgb(229, 227, 223);">
                            <div><button draggable="false" aria-label="Keyboard shortcuts" title="Keyboard shortcuts"
                                    type="button"
                                    style="background: none transparent; display: block; border: none; margin: 0px; padding: 0px; text-transform: none; appearance: none; position: absolute; cursor: pointer; user-select: none; z-index: 1000002; outline-offset: 3px; right: 0px; bottom: 0px; transform: translateX(100%);"></button>
                            </div>
                            <div tabindex="0" aria-label="Map" aria-roledescription="map" role="region"
                                aria-describedby="687A6B74-1124-4BA1-AF27-11E6F8A2519A"
                                style="position: absolute; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; left: 0px; top: 0px;">
                                <div id="687A6B74-1124-4BA1-AF27-11E6F8A2519A" style="display: none;"><span>To navigate
                                        the map with touch gestures double-tap and hold your finger on the map, then
                                        drag the map.</span>
                                    <div class="LGLeeN-keyboard-shortcuts-view">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td><kbd aria-label="Left arrow">←</kbd></td>
                                                    <td aria-label="Move left.">Move left</td>
                                                </tr>
                                                <tr>
                                                    <td><kbd aria-label="Right arrow">→</kbd></td>
                                                    <td aria-label="Move right.">Move right</td>
                                                </tr>
                                                <tr>
                                                    <td><kbd aria-label="Up arrow">↑</kbd></td>
                                                    <td aria-label="Move up.">Move up</td>
                                                </tr>
                                                <tr>
                                                    <td><kbd aria-label="Down arrow">↓</kbd></td>
                                                    <td aria-label="Move down.">Move down</td>
                                                </tr>
                                                <tr>
                                                    <td><kbd>+</kbd></td>
                                                    <td aria-label="Zoom in.">Zoom in</td>
                                                </tr>
                                                <tr>
                                                    <td><kbd>-</kbd></td>
                                                    <td aria-label="Zoom out.">Zoom out</td>
                                                </tr>
                                                <tr>
                                                    <td><kbd>Home</kbd></td>
                                                    <td aria-label="Jump left by 75%.">Jump left by 75%</td>
                                                </tr>
                                                <tr>
                                                    <td><kbd>End</kbd></td>
                                                    <td aria-label="Jump right by 75%.">Jump right by 75%</td>
                                                </tr>
                                                <tr>
                                                    <td><kbd>Page Up</kbd></td>
                                                    <td aria-label="Jump up by 75%.">Jump up by 75%</td>
                                                </tr>
                                                <tr>
                                                    <td><kbd>Page Down</kbd></td>
                                                    <td aria-label="Jump down by 75%.">Jump down by 75%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="gm-style"
                                style="position: absolute; z-index: 0; left: 0px; top: 0px; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px;">
                                <div
                                    style="position: absolute; z-index: 0; left: 0px; top: 0px; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; cursor: url(&quot;https://maps.gstatic.com/mapfiles/openhand_8_8.cur&quot;), default; touch-action: pan-x pan-y;">
                                    <div
                                        style="z-index: 1; position: absolute; left: 50%; top: 50%; width: 100%; will-change: transform; transform: translate(0px, 0px);">
                                        <div
                                            style="position: absolute; left: 0px; top: 0px; z-index: 100; width: 100%;">
                                            <div style="position: absolute; left: 0px; top: 0px; z-index: 0;">
                                                <div
                                                    style="position: absolute; z-index: 987; transform: matrix(1, 0, 0, 1, -214, -127);">
                                                    <div
                                                        style="position: absolute; left: 256px; top: 0px; width: 256px; height: 256px;">
                                                        <div style="width: 256px; height: 256px;"></div>
                                                    </div>
                                                    <div
                                                        style="position: absolute; left: 0px; top: 0px; width: 256px; height: 256px;">
                                                        <div style="width: 256px; height: 256px;"></div>
                                                    </div>
                                                    <div
                                                        style="position: absolute; left: 0px; top: -256px; width: 256px; height: 256px;">
                                                        <div style="width: 256px; height: 256px;"></div>
                                                    </div>
                                                    <div
                                                        style="position: absolute; left: 256px; top: -256px; width: 256px; height: 256px;">
                                                        <div style="width: 256px; height: 256px;"></div>
                                                    </div>
                                                    <div
                                                        style="position: absolute; left: 512px; top: -256px; width: 256px; height: 256px;">
                                                        <div style="width: 256px; height: 256px;"></div>
                                                    </div>
                                                    <div
                                                        style="position: absolute; left: 512px; top: 0px; width: 256px; height: 256px;">
                                                        <div style="width: 256px; height: 256px;"></div>
                                                    </div>
                                                    <div
                                                        style="position: absolute; left: 512px; top: 256px; width: 256px; height: 256px;">
                                                        <div style="width: 256px; height: 256px;"></div>
                                                    </div>
                                                    <div
                                                        style="position: absolute; left: 256px; top: 256px; width: 256px; height: 256px;">
                                                        <div style="width: 256px; height: 256px;"></div>
                                                    </div>
                                                    <div
                                                        style="position: absolute; left: 0px; top: 256px; width: 256px; height: 256px;">
                                                        <div style="width: 256px; height: 256px;"></div>
                                                    </div>
                                                    <div
                                                        style="position: absolute; left: -256px; top: 256px; width: 256px; height: 256px;">
                                                        <div style="width: 256px; height: 256px;"></div>
                                                    </div>
                                                    <div
                                                        style="position: absolute; left: -256px; top: 0px; width: 256px; height: 256px;">
                                                        <div style="width: 256px; height: 256px;"></div>
                                                    </div>
                                                    <div
                                                        style="position: absolute; left: -256px; top: -256px; width: 256px; height: 256px;">
                                                        <div style="width: 256px; height: 256px;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            style="position: absolute; left: 0px; top: 0px; z-index: 101; width: 100%;">
                                        </div>
                                        <div
                                            style="position: absolute; left: 0px; top: 0px; z-index: 102; width: 100%;">
                                        </div>
                                        <div
                                            style="position: absolute; left: 0px; top: 0px; z-index: 103; width: 100%;">
                                            <div style="position: absolute; left: 0px; top: 0px; z-index: -1;">
                                                <div
                                                    style="position: absolute; z-index: 987; transform: matrix(1, 0, 0, 1, -214, -127);">
                                                    <div
                                                        style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 256px; top: 0px;">
                                                    </div>
                                                    <div
                                                        style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 0px; top: 0px;">
                                                    </div>
                                                    <div
                                                        style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 0px; top: -256px;">
                                                    </div>
                                                    <div
                                                        style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 256px; top: -256px;">
                                                    </div>
                                                    <div
                                                        style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 512px; top: -256px;">
                                                    </div>
                                                    <div
                                                        style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 512px; top: 0px;">
                                                    </div>
                                                    <div
                                                        style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 512px; top: 256px;">
                                                    </div>
                                                    <div
                                                        style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 256px; top: 256px;">
                                                    </div>
                                                    <div
                                                        style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: 0px; top: 256px;">
                                                    </div>
                                                    <div
                                                        style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: -256px; top: 256px;">
                                                    </div>
                                                    <div
                                                        style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: -256px; top: 0px;">
                                                    </div>
                                                    <div
                                                        style="width: 256px; height: 256px; overflow: hidden; position: absolute; left: -256px; top: -256px;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                style="width: 26px; height: 37px; overflow: hidden; position: absolute; left: -13px; top: -37px; z-index: 0;">
                                                <img alt=""
                                                    src="https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi3_hdpi.png"
                                                    draggable="false"
                                                    style="position: absolute; left: 0px; top: 0px; width: 26px; height: 37px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;">
                                            </div>
                                        </div>
                                        <div style="position: absolute; left: 0px; top: 0px; z-index: 0;">
                                            <div
                                                style="position: absolute; z-index: 987; transform: matrix(1, 0, 0, 1, -214, -127);">
                                                <div
                                                    style="position: absolute; left: 0px; top: 0px; width: 256px; height: 256px; transition: opacity 200ms linear;">
                                                    <img draggable="false" alt="" role="presentation"
                                                        src="https://maps.googleapis.com/maps/vt?pb=!1m5!1m4!1i13!2i4987!3i4236!4i256!2m3!1e0!2sm!3i748507849!3m13!2sen-US!3sUS!5e18!12m5!1e68!2m2!1sset!2sRoadmap!4e2!12m3!1e37!2m1!1ssmartmaps!4e0!5m2!1e3!5f2!23i46991212!23i47054750!23i47083502&amp;key=AIzaSyC-3WQ5yGXTinm6UWcgPYX5gF0aBzyX7cA&amp;token=13440"
                                                        style="width: 256px; height: 256px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;">
                                                </div>
                                                <div
                                                    style="position: absolute; left: 256px; top: 0px; width: 256px; height: 256px; transition: opacity 200ms linear;">
                                                    <img draggable="false" alt="" role="presentation"
                                                        src="https://maps.googleapis.com/maps/vt?pb=!1m5!1m4!1i13!2i4988!3i4236!4i256!2m3!1e0!2sm!3i748507849!3m13!2sen-US!3sUS!5e18!12m5!1e68!2m2!1sset!2sRoadmap!4e2!12m3!1e37!2m1!1ssmartmaps!4e0!5m2!1e3!5f2!23i46991212!23i47054750!23i47083502&amp;key=AIzaSyC-3WQ5yGXTinm6UWcgPYX5gF0aBzyX7cA&amp;token=102223"
                                                        style="width: 256px; height: 256px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;">
                                                </div>
                                                <div
                                                    style="position: absolute; left: 0px; top: -256px; width: 256px; height: 256px; transition: opacity 200ms linear;">
                                                    <img draggable="false" alt="" role="presentation"
                                                        src="https://maps.googleapis.com/maps/vt?pb=!1m5!1m4!1i13!2i4987!3i4235!4i256!2m3!1e0!2sm!3i748507849!3m13!2sen-US!3sUS!5e18!12m5!1e68!2m2!1sset!2sRoadmap!4e2!12m3!1e37!2m1!1ssmartmaps!4e0!5m2!1e3!5f2!23i46991212!23i47054750!23i47083502&amp;key=AIzaSyC-3WQ5yGXTinm6UWcgPYX5gF0aBzyX7cA&amp;token=80750"
                                                        style="width: 256px; height: 256px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;">
                                                </div>
                                                <div
                                                    style="position: absolute; left: 256px; top: -256px; width: 256px; height: 256px; transition: opacity 200ms linear;">
                                                    <img draggable="false" alt="" role="presentation"
                                                        src="https://maps.googleapis.com/maps/vt?pb=!1m5!1m4!1i13!2i4988!3i4235!4i256!2m3!1e0!2sm!3i748507849!3m13!2sen-US!3sUS!5e18!12m5!1e68!2m2!1sset!2sRoadmap!4e2!12m3!1e37!2m1!1ssmartmaps!4e0!5m2!1e3!5f2!23i46991212!23i47054750!23i47083502&amp;key=AIzaSyC-3WQ5yGXTinm6UWcgPYX5gF0aBzyX7cA&amp;token=38462"
                                                        style="width: 256px; height: 256px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;">
                                                </div>
                                                <div
                                                    style="position: absolute; left: 512px; top: -256px; width: 256px; height: 256px; transition: opacity 200ms linear;">
                                                    <img draggable="false" alt="" role="presentation"
                                                        src="https://maps.googleapis.com/maps/vt?pb=!1m5!1m4!1i13!2i4989!3i4235!4i256!2m3!1e0!2sm!3i748508101!3m13!2sen-US!3sUS!5e18!12m5!1e68!2m2!1sset!2sRoadmap!4e2!12m3!1e37!2m1!1ssmartmaps!4e0!5m2!1e3!5f2!23i46991212!23i47054750!23i47083502&amp;key=AIzaSyC-3WQ5yGXTinm6UWcgPYX5gF0aBzyX7cA&amp;token=78403"
                                                        style="width: 256px; height: 256px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;">
                                                </div>
                                                <div
                                                    style="position: absolute; left: 0px; top: 256px; width: 256px; height: 256px; transition: opacity 200ms linear;">
                                                    <img draggable="false" alt="" role="presentation"
                                                        src="https://maps.googleapis.com/maps/vt?pb=!1m5!1m4!1i13!2i4987!3i4237!4i256!2m3!1e0!2sm!3i748507849!3m13!2sen-US!3sUS!5e18!12m5!1e68!2m2!1sset!2sRoadmap!4e2!12m3!1e37!2m1!1ssmartmaps!4e0!5m2!1e3!5f2!23i46991212!23i47054750!23i47083502&amp;key=AIzaSyC-3WQ5yGXTinm6UWcgPYX5gF0aBzyX7cA&amp;token=77201"
                                                        style="width: 256px; height: 256px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;">
                                                </div>
                                                <div
                                                    style="position: absolute; left: 512px; top: 0px; width: 256px; height: 256px; transition: opacity 200ms linear;">
                                                    <img draggable="false" alt="" role="presentation"
                                                        src="https://maps.googleapis.com/maps/vt?pb=!1m5!1m4!1i13!2i4989!3i4236!4i256!2m3!1e0!2sm!3i748508101!3m13!2sen-US!3sUS!5e18!12m5!1e68!2m2!1sset!2sRoadmap!4e2!12m3!1e37!2m1!1ssmartmaps!4e0!5m2!1e3!5f2!23i46991212!23i47054750!23i47083502&amp;key=AIzaSyC-3WQ5yGXTinm6UWcgPYX5gF0aBzyX7cA&amp;token=11093"
                                                        style="width: 256px; height: 256px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;">
                                                </div>
                                                <div
                                                    style="position: absolute; left: -256px; top: 256px; width: 256px; height: 256px; transition: opacity 200ms linear;">
                                                    <img draggable="false" alt="" role="presentation"
                                                        src="https://maps.googleapis.com/maps/vt?pb=!1m5!1m4!1i13!2i4986!3i4237!4i256!2m3!1e0!2sm!3i748507849!3m13!2sen-US!3sUS!5e18!12m5!1e68!2m2!1sset!2sRoadmap!4e2!12m3!1e37!2m1!1ssmartmaps!4e0!5m2!1e3!5f2!23i46991212!23i47054750!23i47083502&amp;key=AIzaSyC-3WQ5yGXTinm6UWcgPYX5gF0aBzyX7cA&amp;token=119489"
                                                        style="width: 256px; height: 256px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;">
                                                </div>
                                                <div
                                                    style="position: absolute; left: 512px; top: 256px; width: 256px; height: 256px; transition: opacity 200ms linear;">
                                                    <img draggable="false" alt="" role="presentation"
                                                        src="https://maps.googleapis.com/maps/vt?pb=!1m5!1m4!1i13!2i4989!3i4237!4i256!2m3!1e0!2sm!3i748507921!3m13!2sen-US!3sUS!5e18!12m5!1e68!2m2!1sset!2sRoadmap!4e2!12m3!1e37!2m1!1ssmartmaps!4e0!5m2!1e3!5f2!23i46991212!23i47054750!23i47083502&amp;key=AIzaSyC-3WQ5yGXTinm6UWcgPYX5gF0aBzyX7cA&amp;token=22089"
                                                        style="width: 256px; height: 256px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;">
                                                </div>
                                                <div
                                                    style="position: absolute; left: -256px; top: 0px; width: 256px; height: 256px; transition: opacity 200ms linear;">
                                                    <img draggable="false" alt="" role="presentation"
                                                        src="https://maps.googleapis.com/maps/vt?pb=!1m5!1m4!1i13!2i4986!3i4236!4i256!2m3!1e0!2sm!3i748507849!3m13!2sen-US!3sUS!5e18!12m5!1e68!2m2!1sset!2sRoadmap!4e2!12m3!1e37!2m1!1ssmartmaps!4e0!5m2!1e3!5f2!23i46991212!23i47054750!23i47083502&amp;key=AIzaSyC-3WQ5yGXTinm6UWcgPYX5gF0aBzyX7cA&amp;token=55728"
                                                        style="width: 256px; height: 256px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;">
                                                </div>
                                                <div
                                                    style="position: absolute; left: 256px; top: 256px; width: 256px; height: 256px; transition: opacity 200ms linear;">
                                                    <img draggable="false" alt="" role="presentation"
                                                        src="https://maps.googleapis.com/maps/vt?pb=!1m5!1m4!1i13!2i4988!3i4237!4i256!2m3!1e0!2sm!3i748507849!3m13!2sen-US!3sUS!5e18!12m5!1e68!2m2!1sset!2sRoadmap!4e2!12m3!1e37!2m1!1ssmartmaps!4e0!5m2!1e3!5f2!23i46991212!23i47054750!23i47083502&amp;key=AIzaSyC-3WQ5yGXTinm6UWcgPYX5gF0aBzyX7cA&amp;token=34913"
                                                        style="width: 256px; height: 256px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;">
                                                </div>
                                                <div
                                                    style="position: absolute; left: -256px; top: -256px; width: 256px; height: 256px; transition: opacity 200ms linear;">
                                                    <img draggable="false" alt="" role="presentation"
                                                        src="https://maps.googleapis.com/maps/vt?pb=!1m5!1m4!1i13!2i4986!3i4235!4i256!2m3!1e0!2sm!3i748507849!3m13!2sen-US!3sUS!5e18!12m5!1e68!2m2!1sset!2sRoadmap!4e2!12m3!1e37!2m1!1ssmartmaps!4e0!5m2!1e3!5f2!23i46991212!23i47054750!23i47083502&amp;key=AIzaSyC-3WQ5yGXTinm6UWcgPYX5gF0aBzyX7cA&amp;token=123038"
                                                        style="width: 256px; height: 256px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        style="z-index: 3; position: absolute; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; left: 0px; top: 0px; touch-action: pan-x pan-y;">
                                        <div
                                            style="z-index: 4; position: absolute; left: 50%; top: 50%; width: 100%; will-change: transform; transform: translate(0px, 0px);">
                                            <div
                                                style="position: absolute; left: 0px; top: 0px; z-index: 104; width: 100%;">
                                            </div>
                                            <div
                                                style="position: absolute; left: 0px; top: 0px; z-index: 105; width: 100%;">
                                            </div>
                                            <div
                                                style="position: absolute; left: 0px; top: 0px; z-index: 106; width: 100%;">
                                                <slot></slot><span id="81F6BCAA-5718-4E48-8668-6D35C981B713"
                                                    aria-live="polite"
                                                    style="position: absolute; width: 1px; height: 1px; margin: -1px; padding: 0px; overflow: hidden; clip-path: inset(100%); white-space: nowrap; border: 0px;"></span>
                                                <div title="" tabindex="-1"
                                                    style="width: 42px; height: 53px; overflow: hidden; position: absolute; cursor: pointer; touch-action: none; left: -21px; top: -45px; z-index: 0;">
                                                    <img alt="" src="https://maps.gstatic.com/mapfiles/transparent.png"
                                                        draggable="false"
                                                        style="width: 42px; height: 53px; user-select: none; border: 0px; padding: 0px; margin: 0px; max-width: none;">
                                                </div>
                                            </div>
                                            <div
                                                style="position: absolute; left: 0px; top: 0px; z-index: 107; width: 100%;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="gm-style-moc"
                                        style="z-index: 4; position: absolute; height: 100%; width: 100%; padding: 0px; border-width: 0px; margin: 0px; left: 0px; top: 0px; transition-property: opacity, display; transition-behavior: allow-discrete; opacity: 0; display: none;">
                                        <p class="gm-style-mot"></p>
                                    </div>
                                </div><iframe aria-hidden="true" frameborder="0" tabindex="-1"
                                    style="z-index: -1; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; border: none; opacity: 0;"></iframe>
                                <div
                                    style="pointer-events: none; width: 100%; height: 100%; box-sizing: border-box; position: absolute; z-index: 1000002; opacity: 0; border: 2px solid rgb(26, 115, 232);">
                                </div>
                                <div>
                                    <div class="gmnoprint gm-style-mtc-bbw" role="menubar"
                                        style="margin: 10px; z-index: 0; position: absolute; cursor: pointer; left: 0px; top: 0px;">
                                        <div class="gm-style-mtc" style="float: left; position: relative;"><button
                                                draggable="false" aria-label="Show street map" title="Show street map"
                                                type="button" role="menuitemradio" aria-checked="true"
                                                aria-haspopup="true"
                                                style="background: none padding-box rgb(255, 255, 255); display: table-cell; border: 0px; margin: 0px; padding: 0px 23px; text-transform: none; appearance: none; position: relative; cursor: pointer; user-select: none; overflow: hidden; text-align: center; height: 40px; vertical-align: middle; color: rgb(0, 0, 0); font-family: Roboto, Arial, sans-serif; font-size: 18px; box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px; min-width: 36px; font-weight: 500;"
                                                id="0A11DBE7-EE59-4A21-8063-A158156BBFE2">Map</button>
                                            <ul role="menu" aria-labelledby="0A11DBE7-EE59-4A21-8063-A158156BBFE2"
                                                style="background-color: rgb(255, 255, 255); list-style: none; padding: 2px; margin: 0px; z-index: -1; border-bottom-left-radius: 2px; border-bottom-right-radius: 2px; box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px; position: absolute; left: 0px; top: 40px; text-align: left; display: none;">
                                                <li tabindex="-1" role="menuitemcheckbox" aria-label="Terrain"
                                                    draggable="false" title="Show street map with terrain"
                                                    aria-checked="false" class="ssQIHO-checkbox-menu-item"
                                                    style="color: rgb(0, 0, 0); font-family: Roboto, Arial, sans-serif; user-select: none; font-size: 18px; background-color: rgb(255, 255, 255); padding: 7px 8px 7px 7px; direction: ltr; text-align: left; white-space: nowrap;">
                                                    <span><span
                                                            style="mask-image: url(&quot;data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M0%200h24v24H0z%22%20fill%3D%22none%22/%3E%3Cpath%20d%3D%22M19%203H5c-1.11%200-2%20.9-2%202v14c0%201.1.89%202%202%202h14c1.11%200%202-.9%202-2V5c0-1.1-.89-2-2-2zm-9%2014l-5-5%201.41-1.41L10%2014.17l7.59-7.59L19%208l-9%209z%22/%3E%3C/svg%3E&quot;); height: 1em; width: 1em; transform: translateY(0.15em); display: none;"></span><span
                                                            style="mask-image: url(&quot;data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M19%205v14H5V5h14m0-2H5c-1.1%200-2%20.9-2%202v14c0%201.1.9%202%202%202h14c1.1%200%202-.9%202-2V5c0-1.1-.9-2-2-2z%22/%3E%3Cpath%20d%3D%22M0%200h24v24H0z%22%20fill%3D%22none%22/%3E%3C/svg%3E&quot;); height: 1em; width: 1em; transform: translateY(0.15em);"></span></span><label
                                                        style="cursor: inherit;">Terrain</label>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="gm-style-mtc" style="float: left; position: relative;"><button
                                                draggable="false" aria-label="Show satellite imagery"
                                                title="Show satellite imagery" type="button" role="menuitemradio"
                                                aria-checked="false" aria-haspopup="true"
                                                style="background: none padding-box rgb(255, 255, 255); display: table-cell; border: 0px; margin: 0px; padding: 0px 23px; text-transform: none; appearance: none; position: relative; cursor: pointer; user-select: none; overflow: hidden; text-align: center; height: 40px; vertical-align: middle; color: rgb(86, 86, 86); font-family: Roboto, Arial, sans-serif; font-size: 18px; box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px; min-width: 66px;"
                                                id="463975CA-320E-4626-B338-9395E2858E97">Satellite</button>
                                            <ul role="menu" aria-labelledby="463975CA-320E-4626-B338-9395E2858E97"
                                                style="background-color: rgb(255, 255, 255); list-style: none; padding: 2px; margin: 0px; z-index: -1; border-bottom-left-radius: 2px; border-bottom-right-radius: 2px; box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px; position: absolute; right: 0px; top: 40px; text-align: left; display: none;">
                                                <li tabindex="-1" role="menuitemcheckbox" aria-label="Labels"
                                                    draggable="false" title="Show imagery with street names"
                                                    aria-checked="true" class="ssQIHO-checkbox-menu-item"
                                                    style="color: rgb(0, 0, 0); font-family: Roboto, Arial, sans-serif; user-select: none; font-size: 18px; background-color: rgb(255, 255, 255); padding: 7px 8px 7px 7px; direction: ltr; text-align: left; white-space: nowrap;">
                                                    <span><span
                                                            style="mask-image: url(&quot;data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M0%200h24v24H0z%22%20fill%3D%22none%22/%3E%3Cpath%20d%3D%22M19%203H5c-1.11%200-2%20.9-2%202v14c0%201.1.89%202%202%202h14c1.11%200%202-.9%202-2V5c0-1.1-.89-2-2-2zm-9%2014l-5-5%201.41-1.41L10%2014.17l7.59-7.59L19%208l-9%209z%22/%3E%3C/svg%3E&quot;); height: 1em; width: 1em; transform: translateY(0.15em);"></span><span
                                                            style="mask-image: url(&quot;data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M19%205v14H5V5h14m0-2H5c-1.1%200-2%20.9-2%202v14c0%201.1.9%202%202%202h14c1.1%200%202-.9%202-2V5c0-1.1-.9-2-2-2z%22/%3E%3Cpath%20d%3D%22M0%200h24v24H0z%22%20fill%3D%22none%22/%3E%3C/svg%3E&quot;); height: 1em; width: 1em; transform: translateY(0.15em); display: none;"></span></span><label
                                                        style="cursor: inherit;">Labels</label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div><button draggable="false" aria-label="Toggle fullscreen view"
                                        title="Toggle fullscreen view" type="button" aria-pressed="false"
                                        class="gm-control-active gm-fullscreen-control"
                                        style="background: none rgb(255, 255, 255); border: 0px; margin: 10px; padding: 0px; text-transform: none; appearance: none; position: absolute; cursor: pointer; user-select: none; border-radius: 2px; height: 40px; width: 40px; box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px; overflow: hidden; top: 0px; right: 0px;"><img
                                            src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2018%2018%22%3E%3Cpath%20fill%3D%22%23666%22%20d%3D%22M0%200v6h2V2h4V0H0zm16%200h-4v2h4v4h2V0h-2zm0%2016h-4v2h6v-6h-2v4zM2%2012H0v6h6v-2H2v-4z%22/%3E%3C/svg%3E"
                                            alt="" style="height: 18px; width: 18px;"><img
                                            src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2018%2018%22%3E%3Cpath%20fill%3D%22%23333%22%20d%3D%22M0%200v6h2V2h4V0H0zm16%200h-4v2h4v4h2V0h-2zm0%2016h-4v2h6v-6h-2v4zM2%2012H0v6h6v-2H2v-4z%22/%3E%3C/svg%3E"
                                            alt="" style="height: 18px; width: 18px;"><img
                                            src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2018%2018%22%3E%3Cpath%20fill%3D%22%23111%22%20d%3D%22M0%200v6h2V2h4V0H0zm16%200h-4v2h4v4h2V0h-2zm0%2016h-4v2h6v-6h-2v4zM2%2012H0v6h6v-2H2v-4z%22/%3E%3C/svg%3E"
                                            alt="" style="height: 18px; width: 18px;"></button></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div>
                                    <div class="gmnoprint gm-bundled-control gm-bundled-control-on-bottom"
                                        draggable="false" data-control-width="0" data-control-height="0"
                                        style="margin: 10px; user-select: none; position: absolute; display: none; bottom: 26px; left: 0px;">
                                        <div class="gmnoprint" data-control-width="40" data-control-height="40"
                                            style="display: none; position: absolute;">
                                            <div
                                                style="background-color: rgb(255, 255, 255); box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px; border-radius: 2px; width: 40px; height: 40px;">
                                                <button draggable="false" aria-label="Rotate map clockwise"
                                                    title="Rotate map clockwise" type="button" class="gm-control-active"
                                                    style="background: none; display: none; border: 0px; margin: 0px; padding: 0px; text-transform: none; appearance: none; position: relative; cursor: pointer; user-select: none; left: 0px; top: 0px; overflow: hidden; width: 40px; height: 40px;"><img
                                                        alt=""
                                                        src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20fill%3D%22none%22%20d%3D%22M0%200h24v24H0V0z%22/%3E%3Cpath%20fill%3D%22%23666%22%20d%3D%22M12.06%209.06l4-4-4-4-1.41%201.41%201.59%201.59h-.18c-2.3%200-4.6.88-6.35%202.64-3.52%203.51-3.52%209.21%200%2012.72%201.5%201.5%203.4%202.36%205.36%202.58v-2.02c-1.44-.21-2.84-.86-3.95-1.97-2.73-2.73-2.73-7.17%200-9.9%201.37-1.37%203.16-2.05%204.95-2.05h.17l-1.59%201.59%201.41%201.41zm8.94%203c-.19-1.74-.88-3.32-1.91-4.61l-1.43%201.43c.69.92%201.15%202%201.32%203.18H21zm-7.94%207.92V22c1.74-.19%203.32-.88%204.61-1.91l-1.43-1.43c-.91.68-2%201.15-3.18%201.32zm4.6-2.74l1.43%201.43c1.04-1.29%201.72-2.88%201.91-4.61h-2.02c-.17%201.18-.64%202.27-1.32%203.18z%22/%3E%3C/svg%3E"
                                                        style="width: 20px; height: 20px;"><img alt=""
                                                        src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20fill%3D%22none%22%20d%3D%22M0%200h24v24H0V0z%22/%3E%3Cpath%20fill%3D%22%23333%22%20d%3D%22M12.06%209.06l4-4-4-4-1.41%201.41%201.59%201.59h-.18c-2.3%200-4.6.88-6.35%202.64-3.52%203.51-3.52%209.21%200%2012.72%201.5%201.5%203.4%202.36%205.36%202.58v-2.02c-1.44-.21-2.84-.86-3.95-1.97-2.73-2.73-2.73-7.17%200-9.9%201.37-1.37%203.16-2.05%204.95-2.05h.17l-1.59%201.59%201.41%201.41zm8.94%203c-.19-1.74-.88-3.32-1.91-4.61l-1.43%201.43c.69.92%201.15%202%201.32%203.18H21zm-7.94%207.92V22c1.74-.19%203.32-.88%204.61-1.91l-1.43-1.43c-.91.68-2%201.15-3.18%201.32zm4.6-2.74l1.43%201.43c1.04-1.29%201.72-2.88%201.91-4.61h-2.02c-.17%201.18-.64%202.27-1.32%203.18z%22/%3E%3C/svg%3E"
                                                        style="width: 20px; height: 20px;"><img alt=""
                                                        src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20fill%3D%22none%22%20d%3D%22M0%200h24v24H0V0z%22/%3E%3Cpath%20fill%3D%22%23111%22%20d%3D%22M12.06%209.06l4-4-4-4-1.41%201.41%201.59%201.59h-.18c-2.3%200-4.6.88-6.35%202.64-3.52%203.51-3.52%209.21%200%2012.72%201.5%201.5%203.4%202.36%205.36%202.58v-2.02c-1.44-.21-2.84-.86-3.95-1.97-2.73-2.73-2.73-7.17%200-9.9%201.37-1.37%203.16-2.05%204.95-2.05h.17l-1.59%201.59%201.41%201.41zm8.94%203c-.19-1.74-.88-3.32-1.91-4.61l-1.43%201.43c.69.92%201.15%202%201.32%203.18H21zm-7.94%207.92V22c1.74-.19%203.32-.88%204.61-1.91l-1.43-1.43c-.91.68-2%201.15-3.18%201.32zm4.6-2.74l1.43%201.43c1.04-1.29%201.72-2.88%201.91-4.61h-2.02c-.17%201.18-.64%202.27-1.32%203.18z%22/%3E%3C/svg%3E"
                                                        style="width: 20px; height: 20px;"></button>
                                                <div
                                                    style="position: relative; overflow: hidden; width: 30px; height: 1px; margin: 0px 5px; background-color: rgb(230, 230, 230); display: none;">
                                                </div><button draggable="false" aria-label="Rotate map counterclockwise"
                                                    title="Rotate map counterclockwise" type="button"
                                                    class="gm-control-active"
                                                    style="background: none; display: none; border: 0px; margin: 0px; padding: 0px; text-transform: none; appearance: none; position: relative; cursor: pointer; user-select: none; left: 0px; top: 0px; overflow: hidden; width: 40px; height: 40px; transform: scaleX(-1);"><img
                                                        alt=""
                                                        src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20fill%3D%22none%22%20d%3D%22M0%200h24v24H0V0z%22/%3E%3Cpath%20fill%3D%22%23666%22%20d%3D%22M12.06%209.06l4-4-4-4-1.41%201.41%201.59%201.59h-.18c-2.3%200-4.6.88-6.35%202.64-3.52%203.51-3.52%209.21%200%2012.72%201.5%201.5%203.4%202.36%205.36%202.58v-2.02c-1.44-.21-2.84-.86-3.95-1.97-2.73-2.73-2.73-7.17%200-9.9%201.37-1.37%203.16-2.05%204.95-2.05h.17l-1.59%201.59%201.41%201.41zm8.94%203c-.19-1.74-.88-3.32-1.91-4.61l-1.43%201.43c.69.92%201.15%202%201.32%203.18H21zm-7.94%207.92V22c1.74-.19%203.32-.88%204.61-1.91l-1.43-1.43c-.91.68-2%201.15-3.18%201.32zm4.6-2.74l1.43%201.43c1.04-1.29%201.72-2.88%201.91-4.61h-2.02c-.17%201.18-.64%202.27-1.32%203.18z%22/%3E%3C/svg%3E"
                                                        style="width: 20px; height: 20px;"><img alt=""
                                                        src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20fill%3D%22none%22%20d%3D%22M0%200h24v24H0V0z%22/%3E%3Cpath%20fill%3D%22%23333%22%20d%3D%22M12.06%209.06l4-4-4-4-1.41%201.41%201.59%201.59h-.18c-2.3%200-4.6.88-6.35%202.64-3.52%203.51-3.52%209.21%200%2012.72%201.5%201.5%203.4%202.36%205.36%202.58v-2.02c-1.44-.21-2.84-.86-3.95-1.97-2.73-2.73-2.73-7.17%200-9.9%201.37-1.37%203.16-2.05%204.95-2.05h.17l-1.59%201.59%201.41%201.41zm8.94%203c-.19-1.74-.88-3.32-1.91-4.61l-1.43%201.43c.69.92%201.15%202%201.32%203.18H21zm-7.94%207.92V22c1.74-.19%203.32-.88%204.61-1.91l-1.43-1.43c-.91.68-2%201.15-3.18%201.32zm4.6-2.74l1.43%201.43c1.04-1.29%201.72-2.88%201.91-4.61h-2.02c-.17%201.18-.64%202.27-1.32%203.18z%22/%3E%3C/svg%3E"
                                                        style="width: 20px; height: 20px;"><img alt=""
                                                        src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20fill%3D%22none%22%20d%3D%22M0%200h24v24H0V0z%22/%3E%3Cpath%20fill%3D%22%23111%22%20d%3D%22M12.06%209.06l4-4-4-4-1.41%201.41%201.59%201.59h-.18c-2.3%200-4.6.88-6.35%202.64-3.52%203.51-3.52%209.21%200%2012.72%201.5%201.5%203.4%202.36%205.36%202.58v-2.02c-1.44-.21-2.84-.86-3.95-1.97-2.73-2.73-2.73-7.17%200-9.9%201.37-1.37%203.16-2.05%204.95-2.05h.17l-1.59%201.59%201.41%201.41zm8.94%203c-.19-1.74-.88-3.32-1.91-4.61l-1.43%201.43c.69.92%201.15%202%201.32%203.18H21zm-7.94%207.92V22c1.74-.19%203.32-.88%204.61-1.91l-1.43-1.43c-.91.68-2%201.15-3.18%201.32zm4.6-2.74l1.43%201.43c1.04-1.29%201.72-2.88%201.91-4.61h-2.02c-.17%201.18-.64%202.27-1.32%203.18z%22/%3E%3C/svg%3E"
                                                        style="width: 20px; height: 20px;"></button>
                                                <div
                                                    style="position: relative; overflow: hidden; width: 30px; height: 1px; margin: 0px 5px; background-color: rgb(230, 230, 230); display: none;">
                                                </div><button draggable="false" aria-label="Tilt map" title="Tilt map"
                                                    type="button" class="gm-tilt gm-control-active"
                                                    style="background: none; display: block; border: 0px; margin: 0px; padding: 0px; text-transform: none; appearance: none; position: relative; cursor: pointer; user-select: none; top: 0px; left: 0px; overflow: hidden; width: 40px; height: 40px;"><img
                                                        alt=""
                                                        src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2018%2016%22%3E%3Cpath%20fill%3D%22%23666%22%20d%3D%22M0%2016h8V9H0v7zm10%200h8V9h-8v7zM0%207h8V0H0v7zm10-7v7h8V0h-8z%22/%3E%3C/svg%3E"
                                                        style="width: 18px;"><img alt=""
                                                        src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2018%2016%22%3E%3Cpath%20fill%3D%22%23333%22%20d%3D%22M0%2016h8V9H0v7zm10%200h8V9h-8v7zM0%207h8V0H0v7zm10-7v7h8V0h-8z%22/%3E%3C/svg%3E"
                                                        style="width: 18px;"><img alt=""
                                                        src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2018%2016%22%3E%3Cpath%20fill%3D%22%23111%22%20d%3D%22M0%2016h8V9H0v7zm10%200h8V9h-8v7zM0%207h8V0H0v7zm10-7v7h8V0h-8z%22/%3E%3C/svg%3E"
                                                        style="width: 18px;"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="gmnoprint gm-bundled-control gm-bundled-control-on-bottom"
                                        draggable="false" data-control-width="40" data-control-height="112"
                                        style="margin: 10px; user-select: none; position: absolute; bottom: 126px; right: 40px;">
                                        <gmp-internal-camera-control data-control-width="40" data-control-height="40"
                                            draggable="false" class="gmnoprint"
                                            style="position: absolute; cursor: pointer; user-select: none; left: 0px; top: 0px;">
                                            <button draggable="false" aria-label="Map camera controls"
                                                title="Map camera controls" type="button" class="gm-control-active"
                                                aria-expanded="false"
                                                aria-controls="B444866B-BA5B-437B-9E51-6F6A135F5983"
                                                style="background: none 6px center / 28px no-repeat rgb(255, 255, 255); display: block; border: 0px; margin: 0px; padding: 0px; text-transform: none; appearance: none; position: relative; cursor: pointer; user-select: none; width: 40px; height: 40px; border-radius: 50%; box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px;"><img
                                                    src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M12%2019.175l2.125-2.125%201.425%201.4L12%2022l-3.55-3.55%201.425-1.4L12%2019.175zM4.825%2012l2.125%202.125-1.4%201.425L2%2012l3.55-3.55%201.4%201.425L4.825%2012zm14.35%200L17.05%209.875l1.4-1.425L22%2012l-3.55%203.55-1.4-1.425L19.175%2012zM12%204.825L9.875%206.95%208.45%205.55%2012%202l3.55%203.55-1.425%201.4L12%204.825z%22%20fill%3D%22%23666%22/%3E%3C/svg%3E"
                                                    alt="" style="height: 28px; width: 28px;"><img
                                                    src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M12%2019.175l2.125-2.125%201.425%201.4L12%2022l-3.55-3.55%201.425-1.4L12%2019.175zM4.825%2012l2.125%202.125-1.4%201.425L2%2012l3.55-3.55%201.4%201.425L4.825%2012zm14.35%200L17.05%209.875l1.4-1.425L22%2012l-3.55%203.55-1.4-1.425L19.175%2012zM12%204.825L9.875%206.95%208.45%205.55%2012%202l3.55%203.55-1.425%201.4L12%204.825z%22%20fill%3D%22%23666%22/%3E%3C/svg%3E"
                                                    alt="" style="height: 28px; width: 28px;"><img
                                                    src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M12%2019.175l2.125-2.125L15.55%2018.45%2012%2022%208.45%2018.45%209.875%2017.05%2012%2019.175zM4.825%2012l2.125%202.125L5.55%2015.55%202%2012%205.55%208.45%206.95%209.875%204.825%2012zM19.175%2012L17.05%209.875%2018.45%208.45%2022%2012%2018.45%2015.55%2017.05%2014.125%2019.175%2012zM12%204.825L9.875%206.95%208.45%205.55%2012%202%2015.55%205.55%2014.125%206.95%2012%204.825z%22%20fill%3D%22%231A73E8%22/%3E%3C/svg%3E"
                                                    alt="" style="height: 28px; width: 28px;"><img
                                                    src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M12%2019.175l2.125-2.125L15.55%2018.45%2012%2022%208.45%2018.45%209.875%2017.05%2012%2019.175zM4.825%2012l2.125%202.125L5.55%2015.55%202%2012%205.55%208.45%206.95%209.875%204.825%2012zM19.175%2012L17.05%209.875%2018.45%208.45%2022%2012%2018.45%2015.55%2017.05%2014.125%2019.175%2012zM12%204.825L9.875%206.95%208.45%205.55%2012%202%2015.55%205.55%2014.125%206.95%2012%204.825z%22%20fill%3D%22%23D1D1D1%22/%3E%3C/svg%3E"
                                                    alt="" style="height: 28px; width: 28px;"></button>
                                            <menu id="B444866B-BA5B-437B-9E51-6F6A135F5983"
                                                style="list-style: none; padding: 0px; display: none; position: absolute; z-index: 999999; margin: 10px; width: 140px; height: 140px; right: 100%; top: -60px;">
                                                <li><button draggable="false" aria-label="Move up" title="Move up"
                                                        type="button" class="gm-control-active"
                                                        style="background: none 6px center / 28px no-repeat rgb(255, 255, 255); display: block; border: 0px; margin: 0px; padding: 0px; text-transform: none; appearance: none; position: absolute; cursor: pointer; user-select: none; width: 40px; height: 40px; border-radius: 50%; box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px; top: 0px; left: 50%; transform: translateX(-50%);"><img
                                                            src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M12%2010.8l-4.6%204.6L6%2014l6-6%206%206-1.4%201.4-4.6-4.6z%22%20fill%3D%22%23666%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M12%2010.8l-4.6%204.6L6%2014l6-6%206%206L16.6%2015.4%2012%2010.8z%22%20fill%3D%22%23333%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M12%2010.8l-4.6%204.6L6%2014l6-6%206%206-1.4%201.4-4.6-4.6z%22%20fill%3D%22%23666%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M12%2010.8l-4.6%204.6L6%2014l6-6%206%206L16.6%2015.4%2012%2010.8z%22%20fill%3D%22%23D1D1D1%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"></button></li>
                                                <li><button draggable="false" aria-label="Move left" title="Move left"
                                                        type="button" class="gm-control-active"
                                                        style="background: none 6px center / 28px no-repeat rgb(255, 255, 255); display: block; border: 0px; margin: 0px; padding: 0px; text-transform: none; appearance: none; position: absolute; cursor: pointer; user-select: none; width: 40px; height: 40px; border-radius: 50%; box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px; bottom: 50%; left: 0px; transform: translateY(50%);"><img
                                                            src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M14%2018l-6-6%206-6%201.4%201.4-4.6%204.6%204.6%204.6L14%2018z%22%20fill%3D%22%23666%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M14%2018l-6-6%206-6L15.4%207.4%2010.8%2012%2015.4%2016.6%2014%2018z%22%20fill%3D%22%23333%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M14%2018l-6-6%206-6%201.4%201.4-4.6%204.6%204.6%204.6L14%2018z%22%20fill%3D%22%23666%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M14%2018l-6-6%206-6L15.4%207.4%2010.8%2012%2015.4%2016.6%2014%2018z%22%20fill%3D%22%23D1D1D1%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"></button></li>
                                                <li><button draggable="false" aria-label="Move right" title="Move right"
                                                        type="button" class="gm-control-active"
                                                        style="background: none 6px center / 28px no-repeat rgb(255, 255, 255); display: block; border: 0px; margin: 0px; padding: 0px; text-transform: none; appearance: none; position: absolute; cursor: pointer; user-select: none; width: 40px; height: 40px; border-radius: 50%; box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px; bottom: 50%; right: 0px; transform: translateY(50%);"><img
                                                            src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M12.6%2012L8%207.4%209.4%206l6%206-6%206L8%2016.6l4.6-4.6z%22%20fill%3D%22%23666%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M12.6%2012L8%207.4%209.4%206l6%206-6%206L8%2016.6%2012.6%2012z%22%20fill%3D%22%23333%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M12.6%2012L8%207.4%209.4%206l6%206-6%206L8%2016.6l4.6-4.6z%22%20fill%3D%22%23666%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M12.6%2012L8%207.4%209.4%206l6%206-6%206L8%2016.6%2012.6%2012z%22%20fill%3D%22%23D1D1D1%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"></button></li>
                                                <li><button draggable="false" aria-label="Move down" title="Move down"
                                                        type="button" class="gm-control-active"
                                                        style="background: none 6px center / 28px no-repeat rgb(255, 255, 255); display: block; border: 0px; margin: 0px; padding: 0px; text-transform: none; appearance: none; position: absolute; cursor: pointer; user-select: none; width: 40px; height: 40px; border-radius: 50%; box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px; bottom: 0px; left: 50%; transform: translateX(-50%);"><img
                                                            src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M12%2015.4l-6-6L7.4%208l4.6%204.6L16.6%208%2018%209.4l-6%206z%22%20fill%3D%22%23666%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M12%2015.4l-6-6L7.4%208l4.6%204.6L16.6%208%2018%209.4l-6%206z%22%20fill%3D%22%23333%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M12%2015.4l-6-6L7.4%208l4.6%204.6L16.6%208%2018%209.4l-6%206z%22%20fill%3D%22%23666%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20d%3D%22M12%2015.4l-6-6L7.4%208l4.6%204.6L16.6%208%2018%209.4l-6%206z%22%20fill%3D%22%23666%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"></button></li>
                                                <li><button draggable="false" aria-label="Zoom in" title="Zoom in"
                                                        type="button" class="gm-control-active"
                                                        style="background: none 6px center / 28px no-repeat rgb(255, 255, 255); display: block; border: 0px; margin: 0px; padding: 0px; text-transform: none; appearance: none; position: absolute; cursor: pointer; user-select: none; overflow: hidden; width: 40px; height: 40px; border-radius: 50%; box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px; top: 0px; right: 0px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%20-960%20960%20960%22%20fill%3D%22%23666%22%3E%3Cpath%20d%3D%22M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240z%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%20-960%20960%20960%22%20fill%3D%22%23333%22%3E%3Cpath%20d%3D%22M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240z%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%20-960%20960%20960%22%20fill%3D%22%23111%22%3E%3Cpath%20d%3D%22M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240z%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%20-960%20960%20960%22%20fill%3D%22%23d1d1d1%22%3E%3Cpath%20d%3D%22M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240z%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"></button></li>
                                                <li><button draggable="false" aria-label="Zoom out" title="Zoom out"
                                                        type="button" class="gm-control-active"
                                                        style="background: none 6px center / 28px no-repeat rgb(255, 255, 255); display: block; border: 0px; margin: 0px; padding: 0px; text-transform: none; appearance: none; position: absolute; cursor: pointer; user-select: none; overflow: hidden; width: 40px; height: 40px; border-radius: 50%; box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px; bottom: 0px; right: 0px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%20-960%20960%20960%22%20fill%3D%22%23666%22%3E%3Cpath%20d%3D%22M200-440v-80h560v80H200z%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%20-960%20960%20960%22%20fill%3D%22%23333%22%3E%3Cpath%20d%3D%22M200-440v-80h560v80H200z%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%20-960%20960%20960%22%20fill%3D%22%23111%22%3E%3Cpath%20d%3D%22M200-440v-80h560v80H200z%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"><img
                                                            src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%20-960%20960%20960%22%20fill%3D%22%23d1d1d1%22%3E%3Cpath%20d%3D%22M200-440v-80h560v80H200z%22/%3E%3C/svg%3E"
                                                            alt="" style="height: 28px; width: 28px;"></button></li>
                                            </menu>
                                        </gmp-internal-camera-control><button draggable="false"
                                            aria-label="Drag Pegman onto the map to open Street View"
                                            title="Drag Pegman onto the map to open Street View" type="button"
                                            class="gm-svpc" dir="ltr" data-control-width="40" data-control-height="40"
                                            style="background: rgb(255, 255, 255); border: 0px; margin: 0px; padding: 0px; text-transform: none; appearance: none; position: absolute; cursor: pointer; user-select: none; --pegman-scaleX: 1; box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px; border-radius: 2px; width: 40px; height: 40px; left: 0px; top: 72px;">
                                            <div
                                                style="position: absolute; left: 50%; top: 50%; transform: scaleX(var(--pegman-scaleX));">
                                                <img src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2023%2038%22%3E%3Cpath%20d%3D%22M16.6%2038.1h-5.5l-.2-2.9-.2%202.9h-5.5L5%2025.3l-.8%202a1.53%201.53%200%2001-1.9.9l-1.2-.4a1.58%201.58%200%2001-1-1.9v-.1c.3-.9%203.1-11.2%203.1-11.2a2.66%202.66%200%20012.3-2l.6-.5a6.93%206.93%200%20014.7-12%206.8%206.8%200%20014.9%202%207%207%200%20012%204.9%206.65%206.65%200%2001-2.2%205l.7.5a2.78%202.78%200%20012.4%202s2.9%2011.2%202.9%2011.3a1.53%201.53%200%2001-.9%201.9l-1.3.4a1.63%201.63%200%2001-1.9-.9l-.7-1.8-.1%2012.7zm-3.6-2h1.7L14.9%2020.3l1.9-.3%202.4%206.3.3-.1c-.2-.8-.8-3.2-2.8-10.9a.63.63%200%2000-.6-.5h-.6l-1.1-.9h-1.9l-.3-2a4.83%204.83%200%20003.5-4.7A4.78%204.78%200%200011%202.3H10.8a4.9%204.9%200%2000-1.4%209.6l-.3%202h-1.9l-1%20.9h-.6a.74.74%200%2000-.6.5c-2%207.5-2.7%2010-3%2010.9l.3.1L4.8%2020l1.9.3.2%2015.8h1.6l.6-8.4a1.52%201.52%200%20011.5-1.4%201.5%201.5%200%20011.5%201.4l.9%208.4zm-10.9-9.6zm17.5-.1z%22%20style%3D%22isolation%3Aisolate%22%20fill%3D%22%23333%22%20opacity%3D%22.7%22/%3E%3Cpath%20d%3D%22M5.9%2013.6l1.1-.9h7.8l1.2.9%22%20fill%3D%22%23ce592c%22/%3E%3Cellipse%20cx%3D%2210.9%22%20cy%3D%2213.1%22%20rx%3D%222.7%22%20ry%3D%22.3%22%20style%3D%22isolation%3Aisolate%22%20fill%3D%22%23ce592c%22%20opacity%3D%22.5%22/%3E%3Cpath%20d%3D%22M20.6%2026.1l-2.9-11.3a1.71%201.71%200%2000-1.6-1.2H5.699999999999999a1.69%201.69%200%2000-1.5%201.3l-3.1%2011.3a.61.61%200%2000.3.7l1.1.4a.61.61%200%2000.7-.3l2.7-6.7.2%2016.8h3.6l.6-9.3a.47.47%200%2001.44-.5h.06c.4%200%20.4.2.5.5l.6%209.3h3.6L15.7%2020.3l2.5%206.6a.52.52%200%2000.66.31l1.2-.4a.57.57%200%2000.5-.7z%22%20fill%3D%22%23fdbf2d%22/%3E%3Cpath%20d%3D%22M7%2013.6l3.9%206.7%203.9-6.7%22%20style%3D%22isolation%3Aisolate%22%20fill%3D%22%23cf572e%22%20opacity%3D%22.6%22/%3E%3Ccircle%20cx%3D%2210.9%22%20cy%3D%227%22%20r%3D%225.9%22%20fill%3D%22%23fdbf2d%22/%3E%3C/svg%3E"
                                                    alt="Street View Pegman Control"
                                                    style="height: 30px; width: 30px; position: absolute; transform: translate(-50%, -50%); pointer-events: none;"><img
                                                    src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2038%22%3E%3Cpath%20d%3D%22M22%2026.6l-2.9-11.3a2.78%202.78%200%2000-2.4-2l-.7-.5a6.82%206.82%200%20002.2-5%206.9%206.9%200%2000-13.8%200%207%207%200%20002.2%205.1l-.6.5a2.55%202.55%200%2000-2.3%202s-3%2011.1-3%2011.2v.1a1.58%201.58%200%20001%201.9l1.2.4a1.63%201.63%200%20001.9-.9l.8-2%20.2%2012.8h11.3l.2-12.6.7%201.8a1.54%201.54%200%20001.5%201%201.09%201.09%200%2000.5-.1l1.3-.4a1.85%201.85%200%2000.7-2zm-1.2.9l-1.2.4a.61.61%200%2001-.7-.3l-2.5-6.6-.2%2016.8h-9.4L6.6%2021l-2.7%206.7a.52.52%200%2001-.66.31l-1.1-.4a.52.52%200%2001-.31-.66l3.1-11.3a1.69%201.69%200%20011.5-1.3h.2l1-.9h2.3a5.9%205.9%200%20113.2%200h2.3l1.1.9h.2a1.71%201.71%200%20011.6%201.2l2.9%2011.3a.84.84%200%2001-.4.7z%22%20fill%3D%22%23333%22%20fill-opacity%3D%22.2%22/%3E%26quot%3B%3C/svg%3E"
                                                    alt="Pegman is on top of the Map"
                                                    style="height: 30px; width: 30px; position: absolute; transform: translate(-50%, -50%); pointer-events: none; display: none;"><img
                                                    alt="Street View Pegman Control"
                                                    src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2040%2050%22%3E%3Cpath%20d%3D%22M34-30.4l-2.9-11.3a2.78%202.78%200%2000-2.4-2l-.7-.5a6.82%206.82%200%20002.2-5%206.9%206.9%200%2000-13.8%200%207%207%200%20002.2%205.1l-.6.5a2.55%202.55%200%2000-2.3%202s-3%2011.1-3%2011.2v.1a1.58%201.58%200%20001%201.9l1.2.4a1.63%201.63%200%20001.9-.9l.8-2%20.2%2012.8h11.3l.2-12.6.7%201.8a1.54%201.54%200%20001.5%201%201.09%201.09%200%2000.5-.1l1.3-.4a1.85%201.85%200%2000.7-2zm-1.2.9l-1.2.4a.61.61%200%2001-.7-.3L28.4-36l-.2%2016.8h-9.4L18.6-36l-2.7%206.7a.52.52%200%2001-.66.31l-1.1-.4a.52.52%200%2001-.31-.66l3.1-11.3a1.69%201.69%200%20011.5-1.3h.2l1-.9h2.3a5.9%205.9%200%20113.2%200h2.3l1.1.9h.2a1.71%201.71%200%20011.6%201.2l2.9%2011.3a.84.84%200%2001-.4.7zM34%2029.6l-2.9-11.3a2.78%202.78%200%2000-2.4-2l-.7-.5a6.82%206.82%200%20002.2-5%206.9%206.9%200%2000-13.8%200%207%207%200%20002.2%205.1l-.6.5a2.55%202.55%200%2000-2.3%202s-3%2011.1-3%2011.2v.1a1.58%201.58%200%20001%201.9l1.2.4a1.63%201.63%200%20001.9-.9l.8-2%20.2%2012.8h11.3l.2-12.6.7%201.8a1.54%201.54%200%20001.5%201%201.09%201.09%200%2000.5-.1l1.3-.4a1.85%201.85%200%2000.7-2zm-1.2.9l-1.2.4a.61.61%200%2001-.7-.3L28.4%2024l-.2%2016.8h-9.4L18.6%2024l-2.7%206.7a.52.52%200%2001-.66.31l-1.1-.4a.52.52%200%2001-.31-.66l3.1-11.3a1.69%201.69%200%20011.5-1.3h.2l1-.9h2.3a5.9%205.9%200%20113.2%200h2.3l1.1.9h.2a1.71%201.71%200%20011.6%201.2l2.9%2011.3a.84.84%200%2001-.4.7z%22%20fill%3D%22%23333%22%20fill-opacity%3D%22.2%22/%3E%3Cpath%20d%3D%22M15.4%2038.8h-4a1.64%201.64%200%2001-1.4-1.1l-3.1-8a.9.9%200%2001-.5.1l-1.4.1a1.62%201.62%200%2001-1.6-1.4L2.3%2015.4l1.6-1.3a6.87%206.87%200%2001-3-4.6A7.14%207.14%200%20012%204a7.6%207.6%200%20014.7-3.1A7.14%207.14%200%200112.2%202a7.28%207.28%200%20012.3%209.6l2.1-.1.1%201c0%20.2.1.5.1.8a2.41%202.41%200%20011%201s1.9%203.2%202.8%204.9c.7%201.2%202.1%204.2%202.8%205.9a2.1%202.1%200%2001-.8%202.6l-.6.4a1.63%201.63%200%2001-1.5.2l-.6-.3a8.93%208.93%200%2000.5%201.3%207.91%207.91%200%20001.8%202.6l.6.3v4.6l-4.5-.1a7.32%207.32%200%2001-2.5-1.5l-.4%203.6zm-10-19.2l3.5%209.8%202.9%207.5h1.6V35l-1.9-9.4%203.1%205.4a8.24%208.24%200%20003.8%203.8h2.1v-1.4a14%2014%200%2001-2.2-3.1%2044.55%2044.55%200%2001-2.2-8l-1.3-6.3%203.2%205.6c.6%201.1%202.1%203.6%202.8%204.9l.6-.4c-.8-1.6-2.1-4.6-2.8-5.8-.9-1.7-2.8-4.9-2.8-4.9a.54.54%200%2000-.4-.3l-.7-.1-.1-.7a4.33%204.33%200%2000-.1-.5l-5.3.3%202.2-1.9a4.3%204.3%200%2000.9-1%205.17%205.17%200%2000.8-4%205.67%205.67%200%2000-2.2-3.4%205.09%205.09%200%2000-4-.8%205.67%205.67%200%2000-3.4%202.2%205.17%205.17%200%2000-.8%204%205.67%205.67%200%20002.2%203.4%203.13%203.13%200%20001%20.5l1.6.6-3.2%202.6%201%2011.5h.4l-.3-8.2z%22%20fill%3D%22%23333%22/%3E%3Cpath%20d%3D%22M3.35%2015.9l1.1%2012.5a.39.39%200%2000.36.42h.14l1.4-.1a.66.66%200%2000.5-.4l-.2-3.8-3.3-8.6z%22%20fill%3D%22%23fdbf2d%22/%3E%3Cpath%20d%3D%22M5.2%2028.8l1.1-.1a.66.66%200%2000.5-.4l-.2-3.8-1.2-3.1z%22%20fill%3D%22%23ce592b%22%20fill-opacity%3D%22.25%22/%3E%3Cpath%20d%3D%22M21.4%2035.7l-3.8-1.2-2.7-7.8L12%2015.5l3.4-2.9c.2%202.4%202.2%2014.1%203.7%2017.1%200%200%201.3%202.6%202.3%203.1v2.9m-8.4-8.1l-2-.3%202.5%2010.1.9.4v-2.9%22%20fill%3D%22%23e5892b%22/%3E%3Cpath%20d%3D%22M17.8%2025.4c-.4-1.5-.7-3.1-1.1-4.8-.1-.4-.1-.7-.2-1.1l-1.1-2-1.7-1.6s.9%205%202.4%207.1a19.12%2019.12%200%20001.7%202.4z%22%20style%3D%22isolation%3Aisolate%22%20fill%3D%22%23cf572e%22%20opacity%3D%22.6%22/%3E%3Cpath%20d%3D%22M14.4%2037.8h-3a.43.43%200%2001-.4-.4l-3-7.8-1.7-4.8-3-9%208.9-.4s2.9%2011.3%204.3%2014.4c1.9%204.1%203.1%204.7%205%205.8h-3.2s-4.1-1.2-5.9-7.7a.59.59%200%2000-.6-.4.62.62%200%2000-.3.7s.5%202.4.9%203.6a34.87%2034.87%200%20002%206z%22%20fill%3D%22%23fdbf2d%22/%3E%3Cpath%20d%3D%22M15.4%2012.7l-3.3%202.9-8.9.4%203.3-2.7%22%20fill%3D%22%23ce592b%22/%3E%3Cpath%20d%3D%22M9.1%2021.1l1.4-6.2-5.9.5%22%20style%3D%22isolation%3Aisolate%22%20fill%3D%22%23cf572e%22%20opacity%3D%22.6%22/%3E%3Cpath%20d%3D%22M12%2013.5a4.75%204.75%200%2001-2.6%201.1c-1.5.3-2.9.2-2.9%200s1.1-.6%202.7-1%22%20fill%3D%22%23bb3d19%22/%3E%3Ccircle%20cx%3D%227.92%22%20cy%3D%228.19%22%20r%3D%226.3%22%20fill%3D%22%23fdbf2d%22/%3E%3Cpath%20d%3D%22M4.7%2013.6a6.21%206.21%200%20008.4-1.9v-.1a8.89%208.89%200%2001-8.4%202z%22%20fill%3D%22%23ce592b%22%20fill-opacity%3D%22.25%22/%3E%3Cpath%20d%3D%22M21.2%2027.2l.6-.4a1.09%201.09%200%2000.4-1.3c-.7-1.5-2.1-4.6-2.8-5.8-.9-1.7-2.8-4.9-2.8-4.9a1.6%201.6%200%2000-2.17-.65l-.23.15a1.68%201.68%200%2000-.4%202.1s2.3%203.9%203.1%205.3c.6%201%202.1%203.7%202.9%205.1a.94.94%200%20001.24.49l.16-.09z%22%20fill%3D%22%23fdbf2d%22/%3E%3Cpath%20d%3D%22M19.4%2019.8c-.9-1.7-2.8-4.9-2.8-4.9a1.6%201.6%200%2000-2.17-.65l-.23.15-.3.3c1.1%201.5%202.9%203.8%203.9%205.4%201.1%201.8%202.9%205%203.8%206.7l.1-.1a1.09%201.09%200%2000.4-1.3%2057.67%2057.67%200%2000-2.7-5.6z%22%20fill%3D%22%23ce592b%22%20fill-opacity%3D%22.25%22/%3E%3C/svg%3E"
                                                    style="display: none; height: 40px; width: 40px; position: absolute; transform: translate(-60%, -45%); pointer-events: none;">
                                            </div>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <div
                                        style="margin: 0px 5px; z-index: 1000000; position: absolute; left: 0px; bottom: 0px;">
                                        <a target="_blank" rel="noopener"
                                            title="Open this area in Google Maps (opens a new window)"
                                            aria-label="Open this area in Google Maps (opens a new window)"
                                            href="https://maps.google.com/maps?ll=-6.162222,39.192073&amp;z=13&amp;t=m&amp;hl=en-US&amp;gl=US&amp;mapclient=apiv3"
                                            style="display: inline;">
                                            <div style="width: 66px; height: 26px;"><img alt="Google"
                                                    src="data:image/svg+xml,%3Csvg%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2069%2029%22%3E%3Cg%20opacity%3D%22.6%22%20fill%3D%22%23fff%22%20stroke%3D%22%23fff%22%20stroke-width%3D%221.5%22%3E%3Cpath%20d%3D%22M17.4706%207.33616L18.0118%206.79504%2017.4599%206.26493C16.0963%204.95519%2014.2582%203.94522%2011.7008%203.94522c-4.613699999999999%200-8.50262%203.7551699999999997-8.50262%208.395779999999998C3.19818%2016.9817%207.0871%2020.7368%2011.7008%2020.7368%2014.1712%2020.7368%2016.0773%2019.918%2017.574%2018.3689%2019.1435%2016.796%2019.5956%2014.6326%2019.5956%2012.957%2019.5956%2012.4338%2019.5516%2011.9316%2019.4661%2011.5041L19.3455%2010.9012H10.9508V14.4954H15.7809C15.6085%2015.092%2015.3488%2015.524%2015.0318%2015.8415%2014.403%2016.4629%2013.4495%2017.1509%2011.7008%2017.1509%209.04835%2017.1509%206.96482%2015.0197%206.96482%2012.341%206.96482%209.66239%209.04835%207.53119%2011.7008%207.53119%2013.137%207.53119%2014.176%208.09189%2014.9578%208.82348L15.4876%209.31922%2016.0006%208.80619%2017.4706%207.33616z%22/%3E%3Cpath%20d%3D%22M24.8656%2020.7286C27.9546%2020.7286%2030.4692%2018.3094%2030.4692%2015.0594%2030.4692%2011.7913%2027.953%209.39011%2024.8656%209.39011%2021.7783%209.39011%2019.2621%2011.7913%2019.2621%2015.0594c0%203.25%202.514499999999998%205.6692%205.6035%205.6692zM24.8656%2012.8282C25.8796%2012.8282%2026.8422%2013.6652%2026.8422%2015.0594%2026.8422%2016.4399%2025.8769%2017.2905%2024.8656%2017.2905%2023.8557%2017.2905%2022.8891%2016.4331%2022.8891%2015.0594%2022.8891%2013.672%2023.853%2012.8282%2024.8656%2012.8282z%22/%3E%3Cpath%20d%3D%22M35.7511%2017.2905v0H35.7469C34.737%2017.2905%2033.7703%2016.4331%2033.7703%2015.0594%2033.7703%2013.672%2034.7343%2012.8282%2035.7469%2012.8282%2036.7608%2012.8282%2037.7234%2013.6652%2037.7234%2015.0594%2037.7234%2016.4439%2036.7554%2017.2962%2035.7511%2017.2905zM35.7387%2020.7286C38.8277%2020.7286%2041.3422%2018.3094%2041.3422%2015.0594%2041.3422%2011.7913%2038.826%209.39011%2035.7387%209.39011%2032.6513%209.39011%2030.1351%2011.7913%2030.1351%2015.0594%2030.1351%2018.3102%2032.6587%2020.7286%2035.7387%2020.7286z%22/%3E%3Cpath%20d%3D%22M51.953%2010.4357V9.68573H48.3999V9.80826C47.8499%209.54648%2047.1977%209.38187%2046.4808%209.38187%2043.5971%209.38187%2041.0168%2011.8998%2041.0168%2015.0758%2041.0168%2017.2027%2042.1808%2019.0237%2043.8201%2019.9895L43.7543%2020.0168%2041.8737%2020.797%2041.1808%2021.0844%2041.4684%2021.7772C42.0912%2023.2776%2043.746%2025.1469%2046.5219%2025.1469%2047.9324%2025.1469%2049.3089%2024.7324%2050.3359%2023.7376%2051.3691%2022.7367%2051.953%2021.2411%2051.953%2019.2723v-8.8366zm-7.2194%209.9844L44.7334%2020.4196C45.2886%2020.6201%2045.878%2020.7286%2046.4808%2020.7286%2047.1616%2020.7286%2047.7866%2020.5819%2048.3218%2020.3395%2048.2342%2020.7286%2048.0801%2021.0105%2047.8966%2021.2077%2047.6154%2021.5099%2047.1764%2021.7088%2046.5219%2021.7088%2045.61%2021.7088%2045.0018%2021.0612%2044.7336%2020.4201zM46.6697%2012.8282C47.6419%2012.8282%2048.5477%2013.6765%2048.5477%2015.084%2048.5477%2016.4636%2047.6521%2017.2987%2046.6697%2017.2987%2045.6269%2017.2987%2044.6767%2016.4249%2044.6767%2015.084%2044.6767%2013.7086%2045.6362%2012.8282%2046.6697%2012.8282zM55.7387%205.22083v-.75H52.0788V20.4412H55.7387V5.220829999999999z%22/%3E%3Cpath%20d%3D%22M63.9128%2016.0614L63.2945%2015.6492%2062.8766%2016.2637C62.4204%2016.9346%2061.8664%2017.3069%2061.0741%2017.3069%2060.6435%2017.3069%2060.3146%2017.2088%2060.0544%2017.0447%2059.9844%2017.0006%2059.9161%2016.9496%2059.8498%2016.8911L65.5497%2014.5286%2066.2322%2014.2456%2065.9596%2013.5589%2065.7406%2013.0075C65.2878%2011.8%2063.8507%209.39832%2060.8278%209.39832%2057.8445%209.39832%2055.5034%2011.7619%2055.5034%2015.0676%2055.5034%2018.2151%2057.8256%2020.7369%2061.0659%2020.7369%2063.6702%2020.7369%2065.177%2019.1378%2065.7942%2018.2213L66.2152%2017.5963%2065.5882%2017.1783%2063.9128%2016.0614zM61.3461%2012.8511L59.4108%2013.6526C59.7903%2013.0783%2060.4215%2012.7954%2060.9017%2012.7954%2061.067%2012.7954%2061.2153%2012.8161%2061.3461%2012.8511z%22/%3E%3C/g%3E%3Cpath%20d%3D%22M11.7008%2019.9868C7.48776%2019.9868%203.94818%2016.554%203.94818%2012.341%203.94818%208.12803%207.48776%204.69522%2011.7008%204.69522%2014.0331%204.69522%2015.692%205.60681%2016.9403%206.80583L15.4703%208.27586C14.5751%207.43819%2013.3597%206.78119%2011.7008%206.78119%208.62108%206.78119%206.21482%209.26135%206.21482%2012.341%206.21482%2015.4207%208.62108%2017.9009%2011.7008%2017.9009%2013.6964%2017.9009%2014.8297%2017.0961%2015.5606%2016.3734%2016.1601%2015.7738%2016.5461%2014.9197%2016.6939%2013.7454h-4.9931V11.6512h7.0298C18.8045%2012.0207%2018.8456%2012.4724%2018.8456%2012.957%2018.8456%2014.5255%2018.4186%2016.4637%2017.0389%2017.8434%2015.692%2019.2395%2013.9838%2019.9868%2011.7008%2019.9868z%22%20fill%3D%22%234285F4%22/%3E%3Cpath%20d%3D%22M29.7192%2015.0594C29.7192%2017.8927%2027.5429%2019.9786%2024.8656%2019.9786%2022.1884%2019.9786%2020.0121%2017.8927%2020.0121%2015.0594%2020.0121%2012.2096%2022.1884%2010.1401%2024.8656%2010.1401%2027.5429%2010.1401%2029.7192%2012.2096%2029.7192%2015.0594zM27.5922%2015.0594C27.5922%2013.2855%2026.3274%2012.0782%2024.8656%2012.0782S22.1391%2013.2937%2022.1391%2015.0594C22.1391%2016.8086%2023.4038%2018.0405%2024.8656%2018.0405S27.5922%2016.8168%2027.5922%2015.0594z%22%20fill%3D%22%23E94235%22/%3E%3Cpath%20d%3D%22M40.5922%2015.0594C40.5922%2017.8927%2038.4159%2019.9786%2035.7387%2019.9786%2033.0696%2019.9786%2030.8851%2017.8927%2030.8851%2015.0594%2030.8851%2012.2096%2033.0614%2010.1401%2035.7387%2010.1401%2038.4159%2010.1401%2040.5922%2012.2096%2040.5922%2015.0594zM38.4734%2015.0594C38.4734%2013.2855%2037.2087%2012.0782%2035.7469%2012.0782%2034.2851%2012.0782%2033.0203%2013.2937%2033.0203%2015.0594%2033.0203%2016.8086%2034.2851%2018.0405%2035.7469%2018.0405%2037.2087%2018.0487%2038.4734%2016.8168%2038.4734%2015.0594z%22%20fill%3D%22%23FABB05%22/%3E%3Cpath%20d%3D%22M51.203%2010.4357v8.8366C51.203%2022.9105%2049.0595%2024.3969%2046.5219%2024.3969%2044.132%2024.3969%2042.7031%2022.7955%2042.161%2021.4897L44.0417%2020.7095C44.3784%2021.5143%2045.1997%2022.4588%2046.5219%2022.4588%2048.1479%2022.4588%2049.1499%2021.4487%2049.1499%2019.568V18.8617H49.0759C48.5914%2019.4612%2047.6552%2019.9786%2046.4808%2019.9786%2044.0171%2019.9786%2041.7668%2017.8352%2041.7668%2015.0758%2041.7668%2012.3%2044.0253%2010.1319%2046.4808%2010.1319%2047.6552%2010.1319%2048.5914%2010.6575%2049.0759%2011.2323H49.1499V10.4357H51.203zM49.2977%2015.084C49.2977%2013.3512%2048.1397%2012.0782%2046.6697%2012.0782%2045.175%2012.0782%2043.9267%2013.3429%2043.9267%2015.084%2043.9267%2016.8004%2045.175%2018.0487%2046.6697%2018.0487%2048.1397%2018.0487%2049.2977%2016.8004%2049.2977%2015.084z%22%20fill%3D%22%234285F4%22/%3E%3Cpath%20d%3D%22M54.9887%205.22083V19.6912H52.8288V5.220829999999999H54.9887z%22%20fill%3D%22%2334A853%22/%3E%3Cpath%20d%3D%22M63.4968%2016.6854L65.1722%2017.8023C64.6301%2018.6072%2063.3244%2019.9869%2061.0659%2019.9869%2058.2655%2019.9869%2056.2534%2017.827%2056.2534%2015.0676%2056.2534%2012.1439%2058.2901%2010.1483%2060.8278%2010.1483%2063.3818%2010.1483%2064.6301%2012.1768%2065.0408%2013.2773L65.2625%2013.8357%2058.6843%2016.5623C59.1853%2017.5478%2059.9737%2018.0569%2061.0741%2018.0569%2062.1746%2018.0569%2062.9384%2017.5067%2063.4968%2016.6854zM58.3312%2014.9115L62.7331%2013.0884C62.4867%2012.4724%2061.764%2012.0454%2060.9017%2012.0454%2059.8012%2012.0454%2058.2737%2013.0145%2058.3312%2014.9115z%22%20fill%3D%22%23E94235%22/%3E%3C/svg%3E"
                                                    draggable="false"
                                                    style="position: absolute; left: 0px; top: 0px; width: 66px; height: 26px; user-select: none; border: 0px; padding: 0px; margin: 0px;">
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div></div>
                                <div>
                                    <div style="display: inline-flex; position: absolute; right: 0px; bottom: 0px;">
                                        <div class="gmnoprint" style="z-index: 1000001;">
                                            <div draggable="false" class="gm-style-cc"
                                                style="user-select: none; position: relative; height: 14px; line-height: 14px;">
                                                <div
                                                    style="opacity: 0.7; width: 100%; height: 100%; position: absolute;">
                                                    <div style="width: 1px;"></div>
                                                    <div
                                                        style="background-color: rgb(245, 245, 245); width: auto; height: 100%; margin-left: 1px;">
                                                    </div>
                                                </div>
                                                <div
                                                    style="position: relative; padding-right: 6px; padding-left: 6px; box-sizing: border-box; font-family: Roboto, Arial, sans-serif; font-size: 10px; color: rgb(0, 0, 0); white-space: nowrap; direction: ltr; text-align: right; vertical-align: middle; display: inline-block;">
                                                    <button draggable="false" aria-label="Keyboard shortcuts"
                                                        title="Keyboard shortcuts" type="button"
                                                        style="background: none; display: inline-block; border: 0px; margin: 0px; padding: 0px; text-transform: none; appearance: none; position: relative; cursor: pointer; user-select: none; color: rgb(0, 0, 0); font-family: inherit; line-height: inherit;">Keyboard
                                                        shortcuts</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="gmnoprint" style="z-index: 1000001;">
                                            <div draggable="false" class="gm-style-cc"
                                                style="user-select: none; position: relative; height: 14px; line-height: 14px;">
                                                <div
                                                    style="opacity: 0.7; width: 100%; height: 100%; position: absolute;">
                                                    <div style="width: 1px;"></div>
                                                    <div
                                                        style="background-color: rgb(245, 245, 245); width: auto; height: 100%; margin-left: 1px;">
                                                    </div>
                                                </div>
                                                <div
                                                    style="position: relative; padding-right: 6px; padding-left: 6px; box-sizing: border-box; font-family: Roboto, Arial, sans-serif; font-size: 10px; color: rgb(0, 0, 0); white-space: nowrap; direction: ltr; text-align: right; vertical-align: middle; display: inline-block;">
                                                    <button draggable="false" aria-label="Map Data" title="Map Data"
                                                        type="button"
                                                        style="background: none; border: 0px; margin: 0px; padding: 0px; text-transform: none; appearance: none; position: relative; cursor: pointer; user-select: none; color: rgb(0, 0, 0); font-family: inherit; line-height: inherit; display: none;">Map
                                                        Data</button><span style="">Map data ©2025</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="gmnoscreen">
                                            <div
                                                style="font-family: Roboto, Arial, sans-serif; font-size: 11px; color: rgb(0, 0, 0); direction: ltr; text-align: right; background-color: rgb(245, 245, 245);">
                                                Map data ©2025</div>
                                        </div><button draggable="false" aria-label="Map Scale: 1 km per 53 pixels"
                                            title="Map Scale: 1 km per 53 pixels" type="button" class="gm-style-cc"
                                            aria-describedby="C3CC5840-5247-424F-9C90-61C398B87662"
                                            style="background: none; display: none; border: 0px; margin: 0px; padding: 0px; text-transform: none; appearance: none; position: relative; cursor: pointer; user-select: none; height: 14px; line-height: 14px;">
                                            <div style="opacity: 0.7; width: 100%; height: 100%; position: absolute;">
                                                <div style="width: 1px;"></div>
                                                <div
                                                    style="background-color: rgb(245, 245, 245); width: auto; height: 100%; margin-left: 1px;">
                                                </div>
                                            </div>
                                            <div
                                                style="position: relative; padding-right: 6px; padding-left: 6px; box-sizing: border-box; font-family: Roboto, Arial, sans-serif; font-size: 10px; color: rgb(0, 0, 0); white-space: nowrap; direction: ltr; text-align: right; vertical-align: middle; display: inline-block;">
                                                <span style="color: rgb(0, 0, 0);">1 km&nbsp;</span>
                                                <div
                                                    style="position: relative; display: inline-block; height: 8px; bottom: -1px; width: 57px;">
                                                    <div
                                                        style="width: 100%; height: 4px; position: absolute; left: 0px; top: 0px;">
                                                    </div>
                                                    <div style="width: 4px; height: 8px; left: 0px; top: 0px;"></div>
                                                    <div
                                                        style="width: 4px; height: 8px; position: absolute; right: 0px; bottom: 0px;">
                                                    </div>
                                                    <div
                                                        style="position: absolute; background-color: rgb(0, 0, 0); height: 2px; left: 1px; bottom: 1px; right: 1px;">
                                                    </div>
                                                    <div
                                                        style="position: absolute; width: 2px; height: 6px; left: 1px; top: 1px; background-color: rgb(0, 0, 0);">
                                                    </div>
                                                    <div
                                                        style="width: 2px; height: 6px; position: absolute; background-color: rgb(0, 0, 0); bottom: 1px; right: 1px;">
                                                    </div>
                                                </div>
                                            </div><span id="C3CC5840-5247-424F-9C90-61C398B87662"
                                                style="display: none;">Click to toggle between metric and imperial
                                                units</span>
                                        </button>
                                        <div class="gmnoprint gm-style-cc" draggable="false"
                                            style="z-index: 1000001; user-select: none; position: relative; height: 14px; line-height: 14px;">
                                            <div style="opacity: 0.7; width: 100%; height: 100%; position: absolute;">
                                                <div style="width: 1px;"></div>
                                                <div
                                                    style="background-color: rgb(245, 245, 245); width: auto; height: 100%; margin-left: 1px;">
                                                </div>
                                            </div>
                                            <div
                                                style="position: relative; padding-right: 6px; padding-left: 6px; box-sizing: border-box; font-family: Roboto, Arial, sans-serif; font-size: 10px; color: rgb(0, 0, 0); white-space: nowrap; direction: ltr; text-align: right; vertical-align: middle; display: inline-block;">
                                                <a aria-label="Terms (opens in new tab)"
                                                    href="https://www.google.com/intl/en-US_US/help/terms_maps.html"
                                                    target="_blank" rel="noopener"
                                                    style="text-decoration: none; cursor: pointer; color: rgb(0, 0, 0);">Terms</a>
                                            </div>
                                        </div>
                                        <div draggable="false" class="gm-style-cc"
                                            style="user-select: none; position: relative; height: 14px; line-height: 14px; display: none;">
                                            <div style="opacity: 0.7; width: 100%; height: 100%; position: absolute;">
                                                <div style="width: 1px;"></div>
                                                <div
                                                    style="background-color: rgb(245, 245, 245); width: auto; height: 100%; margin-left: 1px;">
                                                </div>
                                            </div>
                                            <div
                                                style="position: relative; padding-right: 6px; padding-left: 6px; box-sizing: border-box; font-family: Roboto, Arial, sans-serif; font-size: 10px; color: rgb(0, 0, 0); white-space: nowrap; direction: ltr; text-align: right; vertical-align: middle; display: inline-block;">
                                                <a target="_blank" rel="noopener"
                                                    title="Report errors in the road map or imagery to Google" dir="ltr"
                                                    href="https://www.google.com/maps/@-6.162222,39.192073,13z/data=!10m1!1e1!12b1?source=apiv3&amp;rapsrc=apiv3"
                                                    style="font-family: Roboto, Arial, sans-serif; font-size: 10px; text-decoration: none; position: relative; color: rgb(0, 0, 0);">Report
                                                    a map error</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <div class="reviews-section mt-4" id="review-section">
                <h3 class="comment-count mb-4">Reviews for this Hotel</h3>

                <!-- Sample Reviews -->
                <div class="reviews-list">
                    <!-- Review 1 -->
                    <div class="review-item d-flex mb-4 p-3"
                        style="background: #f8f9fa; border-radius: 12px; border: 1px solid #e9ecef;">
                        <div class="review-avatar me-3" style="flex-shrink: 0;">
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=60&h=60&fit=crop&crop=face"
                                alt="John Doe"
                                style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; display: block;">
                        </div>
                        <div class="review-content flex-grow-1">
                            <div class="review-header d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="reviewer-name mb-1"
                                        style="font-size: 16px; font-weight: 600; color: #333;">John Doe</h5>
                                    <div class="review-rating mb-1">
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                    </div>
                                </div>
                                <small class="text-muted">2 days ago</small>
                            </div>
                            <h6 class="review-title mb-2" style="font-size: 14px; font-weight: 500; color: #555;">
                                Amazing stay with beautiful views!</h6>
                            <p class="review-text mb-0" style="font-size: 14px; color: #666; line-height: 1.5;">
                                The hotel exceeded our expectations. The room was clean, comfortable, and had a stunning
                                view of the ocean.
                                The staff was incredibly friendly and helpful throughout our stay. The facilities were
                                top-notch,
                                especially the swimming pool and spa services. Highly recommended!
                            </p>
                        </div>
                    </div>

                    <!-- Review 2 -->
                    <div class="review-item d-flex mb-4 p-3"
                        style="background: #f8f9fa; border-radius: 12px; border: 1px solid #e9ecef;">
                        <div class="review-avatar me-3" style="flex-shrink: 0;">
                            <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=60&h=60&fit=crop&crop=face"
                                alt="Sarah Wilson"
                                style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; display: block;">
                        </div>
                        <div class="review-content flex-grow-1">
                            <div class="review-header d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="reviewer-name mb-1"
                                        style="font-size: 16px; font-weight: 600; color: #333;">Sarah Wilson</h5>
                                    <div class="review-rating mb-1">
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                    </div>
                                </div>
                                <small class="text-muted">1 week ago</small>
                            </div>
                            <h6 class="review-title mb-2" style="font-size: 14px; font-weight: 500; color: #555;">
                                Perfect location and excellent service</h6>
                            <p class="review-text mb-0" style="font-size: 14px; color: #666; line-height: 1.5;">
                                We had a wonderful time at this hotel. The location is perfect - right on the beach with
                                easy access to local attractions.
                                The concierge team helped us book amazing tours and the room service was prompt and
                                delicious.
                                The balcony view was breathtaking!
                            </p>
                        </div>
                    </div>

                    <!-- Review 3 -->
                    <div class="review-item d-flex mb-4 p-3"
                        style="background: #f8f9fa; border-radius: 12px; border: 1px solid #e9ecef;">
                        <div class="review-avatar me-3" style="flex-shrink: 0;">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=60&h=60&fit=crop&crop=face"
                                alt="Mike Johnson"
                                style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; display: block;">
                        </div>
                        <div class="review-content flex-grow-1">
                            <div class="review-header d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="reviewer-name mb-1"
                                        style="font-size: 16px; font-weight: 600; color: #333;">Mike Johnson</h5>
                                    <div class="review-rating mb-1">
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star-o text-muted"></i>
                                    </div>
                                </div>
                                <small class="text-muted">2 weeks ago</small>
                            </div>
                            <h6 class="review-title mb-2" style="font-size: 14px; font-weight: 500; color: #555;">Great
                                facilities and friendly staff</h6>
                            <p class="review-text mb-0" style="font-size: 14px; color: #666; line-height: 1.5;">
                                The hotel has excellent facilities including a great swimming pool and fitness center.
                                The staff was very accommodating and helped with all our requests. The only minor issue
                                was
                                the WiFi speed in our room, but overall it was a great experience.
                            </p>
                        </div>
                    </div>

                    <!-- Review 4 -->
                    <div class="review-item d-flex mb-4 p-3"
                        style="background: #f8f9fa; border-radius: 12px; border: 1px solid #e9ecef;">
                        <div class="review-avatar me-3" style="flex-shrink: 0;">
                            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=60&h=60&fit=crop&crop=face"
                                alt="Emma Davis"
                                style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; display: block;">
                        </div>
                        <div class="review-content flex-grow-1">
                            <div class="review-header d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h5 class="reviewer-name mb-1"
                                        style="font-size: 16px; font-weight: 600; color: #333;">Emma Davis</h5>
                                    <div class="review-rating mb-1">
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                        <i class="fa fa-star text-warning"></i>
                                    </div>
                                </div>
                                <small class="text-muted">3 weeks ago</small>
                            </div>
                            <h6 class="review-title mb-2" style="font-size: 14px; font-weight: 500; color: #555;">Luxury
                                experience with attention to detail</h6>
                            <p class="review-text mb-0" style="font-size: 14px; color: #666; line-height: 1.5;">
                                This hotel truly delivers a luxury experience. Every detail was perfect - from the
                                welcome drink
                                to the turn-down service. The spa treatments were exceptional and the restaurant served
                                delicious local cuisine. We'll definitely be back!
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="post-comment parent-form" id="gmz-comment-section">
                <div class="comment-form-wrapper">
                    <form action="https://www.zanzibarbookings.com/add-comment"
                        class="comment-form form-sm gmz-form-action form-add-post-comment" method="post"
                        data-reload-time="1000">
                        <h3 class="comment-title mb-4">Leave a Review</h3>
                        <p class="notice mb-4 text-muted">
                            Your email address will not be published. Required fields are marked *
                        </p>

                        <div class="gmz-loader">
                            <div class="loader-inner">
                                <div class="spinner-grow text-info align-self-center loader-lg"></div>
                            </div>
                        </div>

                        <input type="hidden" name="post_id" value="121" />
                        <input type="hidden" name="comment_id" value="0" />
                        <input type="hidden" name="comment_type" value="hotel" />

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="review-select-rate mb-3">
                                    <label class="form-label fw-semibold">Your rating *</label>
                                    <div class="fas-star mt-2">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </div>
                                    <input type="hidden" name="review_star" value="5" class="review_star" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comment-name" class="form-label fw-semibold">Your Name *</label>
                                    <input id="comment-name" type="text" name="comment_name"
                                        class="form-control gmz-validation" placeholder="Enter your full name"
                                        data-validation="required" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="comment-email" class="form-label fw-semibold">Your Email *</label>
                                    <input id="comment-email" type="email" name="comment_email"
                                        class="form-control gmz-validation" placeholder="Enter your email address"
                                        data-validation="required" />
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="comment-title" class="form-label fw-semibold">Review Title *</label>
                                    <input id="comment-title" type="text" name="comment_title"
                                        class="form-control gmz-validation" placeholder="Give your review a title"
                                        data-validation="required" />
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="comment-content" class="form-label fw-semibold">Your Review *</label>
                                    <textarea id="comment-content" name="comment_content"
                                        placeholder="Share your experience with this hotel..."
                                        class="form-control gmz-validation" data-validation="required"
                                        rows="5"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="gmz-message mt-3"></div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg text-uppercase fw-semibold">
                                Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="siderbar-single">

                {{-- Tour Booking Form --}}
                <div class="card mb-4 tour-booking-card rounded" style="box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                    <div class="card-header" style="background: #2e8b57; color: white; padding: 15px;">
                        <h5 class="mb-0" style="font-weight: 600;">
                            <i class="fas fa-map-marked-alt me-2"></i>Book This Tour
                        </h5>
                    </div>
                    
                    {{-- Pricing Display --}}
                    <div class="pricing-section" style="background: #f8f9fa; padding: 20px; border-bottom: 1px solid #e9ecef;">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="pricing-item">
                                    <h4 class="price-amount mb-1" style="color: #2e8b57; font-weight: 700; font-size: 1.6rem;">$85</h4>
                                    <p class="price-label mb-0" style="color: #666; font-size: 13px; font-weight: 500;">Adult Price</p>
                                    <small class="text-muted" style="font-size: 11px;">Per Person</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="pricing-item">
                                    <h4 class="price-amount mb-1" style="color: #2e8b57; font-weight: 700; font-size: 1.6rem;">$45</h4>
                                    <p class="price-label mb-0" style="color: #666; font-size: 13px; font-weight: 500;">Child Price</p>
                                    <small class="text-muted" style="font-size: 11px;">Under 12</small>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <small class="text-muted" style="font-size: 12px;">
                                <i class="fas fa-info-circle me-1"></i>
                                Includes transportation, guide & entrance fees
                            </small>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <form class="tour-booking-form" id="tour-booking-form">
                            <div class="gmz-loader">
                                <div class="loader-inner">
                                    <div class="spinner-grow text-info align-self-center loader-lg"></div>
                                </div>
                            </div>
                            
                            {{-- Form Section Title --}}
                            <div class="form-section-title mb-4">
                                <h6 class="mb-0" style="color: #333; font-weight: 600; font-size: 16px;">
                                    <i class="fas fa-users me-2" style="color: #2e8b57;"></i>Booking Details
                                </h6>
                                <hr style="margin: 8px 0 0 0; border-color: #e9ecef;">
                            </div>
                            
                            <div class="form">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="field-wrapper input">
                                            <label for="adults">NUMBER OF ADULTS</label>
                                            <i class="fal fa-user"></i>
                                            <select id="adults" name="adults" class="form-control gmz-validation"
                                                data-validation="required">
                                                <option value="">Adults</option>
                                                <option value="0">0 Adults</option>
                                                <option value="1">1 Adult</option>
                                                <option value="2">2 Adults</option>
                                                <option value="3">3 Adults</option>
                                                <option value="4">4 Adults</option>
                                                <option value="5">5 Adults</option>
                                                <option value="6">6 Adults</option>
                                                <option value="7">7 Adults</option>
                                                <option value="8">8 Adults</option>
                                                <option value="9">9 Adults</option>
                                                <option value="10">10 Adults</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="field-wrapper input">
                                            <label for="children">NUMBER OF CHILDREN</label>
                                            <i class="fal fa-child"></i>
                                            <select id="children" name="children" class="form-control gmz-validation">
                                                <option value="">Children</option>
                                                <option value="0">0 Children</option>
                                                <option value="1">1 Child</option>
                                                <option value="2">2 Children</option>
                                                <option value="3">3 Children</option>
                                                <option value="4">4 Children</option>
                                                <option value="5">5 Children</option>
                                                <option value="6">6 Children</option>
                                                <option value="7">7 Children</option>
                                                <option value="8">8 Children</option>
                                                <option value="9">9 Children</option>
                                                <option value="10">10 Children</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="field-wrapper input">
                                            <label for="tour_date">TOUR DATE</label>
                                            <i class="fal fa-calendar-alt"></i>
                                            <input id="tour_date" name="tour_date" type="date"
                                                class="form-control gmz-validation" data-validation="required" min="">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="field-wrapper input">
                                            <label for="tour_days">TOUR DURATION</label>
                                            <i class="fal fa-calendar-day"></i>
                                            <select id="tour_days" name="tour_days" class="form-control gmz-validation"
                                                data-validation="required">
                                                <option value="1" selected>1 Day</option>
                                                <option value="2">2 Days</option>
                                                <option value="3">3 Days</option>
                                                <option value="4">4 Days</option>
                                                <option value="5">5 Days</option>
                                                <option value="6">6 Days</option>
                                                <option value="7">7 Days</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="field-wrapper input">
                                            <label for="pickup_location">PICKUP LOCATION</label>
                                            <i class="fal fa-map-marker-alt"></i>
                                            <select id="pickup_location" name="pickup_location" class="form-control gmz-validation"
                                                data-validation="required">
                                                <option value="">Select pickup location</option>
                                                <option value="hotel">Hotel Pickup</option>
                                                <option value="airport">Airport Pickup</option>
                                                <option value="stone_town">Stone Town</option>
                                                <option value="nungwi">Nungwi</option>
                                                <option value="paje">Paje</option>
                                                <option value="jambiani">Jambiani</option>
                                                <option value="other">Other Location</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- Price Calculation Section --}}
                                <div class="price-section-title mb-3 mt-4">
                                    <h6 class="mb-0" style="color: #333; font-weight: 600; font-size: 16px;">
                                        <i class="fas fa-calculator me-2" style="color: #2e8b57;"></i>Price Calculation
                                    </h6>
                                    <hr style="margin: 8px 0 0 0; border-color: #e9ecef;">
                                </div>
                                
                                <div class="price-calculation my-3 p-3"
                                    style="background: #e8f5e8; border-radius: 8px; border-left: 4px solid #28a745;">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="calculation-item">
                                                <span class="label">Adults (×$85):</span>
                                                <span class="value" id="adults-calculation">0 × $85 = $0</span>
                                            </div>
                                            <div class="calculation-item">
                                                <span class="label">Children (×$45):</span>
                                                <span class="value" id="children-calculation">0 × $45 = $0</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="calculation-item">
                                                <span class="label">Tour Days:</span>
                                                <span class="value" id="tour-days-count">1</span>
                                            </div>
                                            <div class="calculation-item">
                                                <span class="label">Daily Total:</span>
                                                <span class="value" id="daily-total">$0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="margin: 10px 0;">
                                    <div class="total-price text-center">
                                        <h5 class="mb-0">
                                            <span class="label">Total Price: </span>
                                            <span class="value text-success" id="total-price"
                                                style="font-size: 1.5rem; font-weight: 700;">$0</span>
                                        </h5>
                                        <small class="text-muted">(Adults + Children) × Tour Days</small>
                                    </div>
                                </div>

                                <div class="gmz-message"></div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-success w-100" style="
                                        text-align: center;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        padding: 12px 24px;
                                        font-size: 16px;
                                        font-weight: 600;
                                        background: #2e8b57;
                                        border-color: #2e8b57;
                                    ">
                                        <i class="fal fa-map-marked-alt me-2"></i>
                                        Book Tour Now
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<section class="list-hotel list-hotel--grid py-40 bg-gray-100 mb-0 nearby">
    <div class="container">
        <h2 class="section-title mb-20">
            Other Featured Tours
        </h2>
        <div class="row">
            @for ($i = 0; $i < 3; $i++) <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="tour-item tour-item--grid" data-plugin="matchHeight">
                    <div class="tour-item__thumbnail position-relative">
                        <span class="tour-item__label position-absolute"
                            style="top: 12px; left: 12px; z-index: 2; background: #ff5722; color: #fff; padding: 4px 12px; border-radius: 6px; font-size: 14px;">Featured</span>
                        <a href="{{route('view-tour')}}" style="display:block;">
                            <img src="https://images.unsplash.com/photo-1523906834658-6e24ef2386f9?w=360&amp;h=240&amp;fit=crop&amp;crop=center"
                                alt="Zanzibar Slave Route &amp; Heritage Tour" loading="eager" width="360" height="240"
                                style="width:100%;height:220px;object-fit:cover;border-radius:12px;" />
                        </a>
                        <a class="tour-item__type" href="tour-searchfa11.html?tour_type=120"
                            style="position:absolute;left:12px;bottom:12px;z-index:2;background:#2e8b57;color:#fff;padding:4px 10px;border-radius:5px;font-size:13px;">
                            Historical tours
                        </a>
                        <div class="add-wishlist-wrapper" style="position:absolute;top:12px;right:12px;z-index:2;">
                            <a href="#gmz-login-popup" class="add-wishlist gmz-box-popup" data-effect="mfp-zoom-in">
                                <i class="fal fa-heart"></i>
                            </a>
                        </div>
                    </div>
                    <div class="tour-item__details" style="padding-top:18px;">
                        <h3 class="tour-item__title" style="font-size:1.25rem;font-weight:600;">
                            <a href="{{route('view-tour')}}" style="color:#222;text-decoration:none;">Zanzibar Slave
                                Route &amp; Heritage Tour</a>
                        </h3>
                        <div class="tour-item__meta" style="margin:18px 0 12px 0;">
                            <div class="i-meta d-flex align-items-center" style="font-size:15px;color:#888;">
                                <i class="fal fa-calendar-alt" style="margin-right:6px;"></i>
                                <span>1025 people</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center" style="margin-top:18px;">
                            <div class="tour-item__price">
                                <span class="_retail" style="color:#2e8b57;font-size:1.3rem;font-weight:600;">USD
                                    120.00</span>
                                <span class="_unit" style="color:#2e8b57;font-size:1rem;">/person</span>
                            </div>
                            <a class="btn btn-primary btn-sm tour-item__view-detail" href="{{route('view-tour')}}"
                                style="font-size:1rem;padding:8px 22px;border-radius:7px;">
                                View Detail
                            </a>
                        </div>
                    </div>
                </div>
        </div>
        @endfor


    </div>
    </div>
</section>

<style>
    /* Fix modal z-index to appear above header */
    .white-popup.gmz-popup-form {
        z-index: 2147483647 !important;
    }

    .mfp-bg {
        z-index: 2147483646 !important;
    }

    .mfp-wrap {
        z-index: 2147483646 !important;
    }

    .mfp-container {
        z-index: 2147483646 !important;
    }

    /* Ensure modal content is properly positioned */
    .mfp-content {
        z-index: 2147483647 !important;
    }

    /* Fix for Magnific Popup modal positioning */
    .mfp-ready .mfp-bg {
        opacity: 0.8;
    }

    .mfp-ready .mfp-wrap {
        opacity: 1;
    }

    /* Ensure modal is centered and visible */
    .white-popup {
        position: relative;
        background: white;
        padding: 0;
        width: auto;
        max-width: 500px;
        margin: 0 auto;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .popup-inner {
        padding: 15px;
    }

    .popup-title {
        margin-bottom: 15px;
        font-size: 20px;
        font-weight: 600;
        color: #333;
        text-align: center;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
    }

    .calculation-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
        font-size: 13px;
    }

    .calculation-item .label {
        color: #666;
        font-weight: 500;
    }

    .calculation-item .value {
        color: #333;
        font-weight: 600;
    }

    .price-calculation {
        transition: all 0.3s ease;
    }

    .price-calculation:hover {
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15);
    }

    .tour-booking-form .field-wrapper {
        position: relative;
        margin-bottom: 15px;
    }

    .tour-booking-form .field-wrapper input,
    .tour-booking-form .field-wrapper select,
    .tour-booking-form .field-wrapper textarea {
        padding: 8px 12px 8px 35px;
        border: 1px solid #ddd;
        border-radius: 6px;
        transition: border-color 0.3s ease;
        font-size: 14px;
        width: 100%;
    }

    .tour-booking-form .field-wrapper input:focus,
    .tour-booking-form .field-wrapper select:focus,
    .tour-booking-form .field-wrapper textarea:focus {
        border-color: #2e8b57;
        box-shadow: 0 0 0 0.2rem rgba(46, 139, 87, 0.25);
        outline: none;
    }

    .tour-booking-form label {
        font-size: 11px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .tour-booking-form .field-wrapper i {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
        z-index: 2;
        font-size: 12px;
    }

    .tour-booking-card {
        border: none;
        overflow: hidden;
    }

    .tour-booking-card .card-header {
        border-bottom: none;
    }

    .tour-booking-card .btn-success:hover {
        background: #228b22;
        border-color: #228b22;
    }

    .tour-booking-form .gmz-loader {
        display: none;
        text-align: center;
        padding: 20px;
    }

    .tour-booking-form .gmz-message {
        margin-top: 15px;
        padding: 10px;
        border-radius: 4px;
        display: none;
    }

    .tour-booking-form .gmz-message.success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .tour-booking-form .gmz-message.error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Fix modal z-index issues
    function fixModalZIndex() {
        // Ensure modals are above header
        const modals = document.querySelectorAll('.white-popup.gmz-popup-form');
        modals.forEach(modal => {
            modal.style.zIndex = '2147483647';
        });
        
        // Fix Magnific Popup z-index
        if (typeof $.magnificPopup !== 'undefined') {
            $.magnificPopup.instance = null;
        }
    }
    
    // Call fix on load
    fixModalZIndex();
    
    // Re-apply fix when modals are opened
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('gmz-box-popup')) {
            setTimeout(fixModalZIndex, 100);
        }
    });
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tour_date').min = today;
    
    // Tour prices
    const adultPrice = 85;
    const childPrice = 45;
    
    // Price calculation function
    function calculateTourPrice() {
        const adults = parseInt(document.getElementById('adults').value) || 0;
        const children = parseInt(document.getElementById('children').value) || 0;
        const tourDays = parseInt(document.getElementById('tour_days').value) || 1;
        
        if (adults > 0 || children > 0) {
            const adultTotal = adults * adultPrice;
            const childTotal = children * childPrice;
            const dailyTotal = adultTotal + childTotal;
            const total = dailyTotal * tourDays;
            
            // Update display
            document.getElementById('tour-days-count').textContent = tourDays;
            document.getElementById('adults-calculation').textContent = `${adults} × $${adultPrice} = $${adultTotal}`;
            document.getElementById('children-calculation').textContent = `${children} × $${childPrice} = $${childTotal}`;
            document.getElementById('daily-total').textContent = `$${dailyTotal}`;
            document.getElementById('total-price').textContent = `$${total}`;
        } else {
            resetTourPriceDisplay();
        }
    }
    
    // Reset price display
    function resetTourPriceDisplay() {
        const tourDays = parseInt(document.getElementById('tour_days').value) || 1;
        document.getElementById('tour-days-count').textContent = tourDays;
        document.getElementById('adults-calculation').textContent = '0 × $85 = $0';
        document.getElementById('children-calculation').textContent = '0 × $45 = $0';
        document.getElementById('daily-total').textContent = '$0';
        document.getElementById('total-price').textContent = '$0';
    }
    
    // Event listeners for price calculation
    document.getElementById('adults').addEventListener('change', calculateTourPrice);
    document.getElementById('children').addEventListener('change', calculateTourPrice);
    document.getElementById('tour_days').addEventListener('change', calculateTourPrice);
    
    // Form submission
    document.getElementById('tour-booking-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = {
            adults: document.getElementById('adults').value,
            children: document.getElementById('children').value,
            tour_date: document.getElementById('tour_date').value,
            tour_days: document.getElementById('tour_days').value,
            pickup_location: document.getElementById('pickup_location').value,
            total_price: document.getElementById('total-price').textContent
        };
        
        // Show loading
        const loader = document.querySelector('.gmz-loader');
        loader.style.display = 'block';
        
        // Simulate booking process (replace with actual API call)
        setTimeout(() => {
            loader.style.display = 'none';
            alert('Tour booking confirmed! You will receive a confirmation email shortly with pickup details.');
            
            // Reset form
            document.getElementById('tour-booking-form').reset();
            resetTourPriceDisplay();
        }, 2000);
    });
});
</script>

@endsection