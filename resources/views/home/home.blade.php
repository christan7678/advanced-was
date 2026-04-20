@extends('layouts.app')

@section('content')
    @php
        $upcomingEventsLink = $canBrowseEvents ? route('events.index', ['period' => 'upcoming']) : route('login');
        $pastEventsLink = $canBrowseEvents ? route('events.index', ['period' => 'past']) : route('login');
    @endphp

    <div class="home-page" data-home-live-url="{{ route('home.live') }}">
        <div id="homeHeroSection">
            @include('home.partials.hero', [
                'heroEvents' => $heroEvents,
                'canBrowseEvents' => $canBrowseEvents,
            ])
        </div>

        <div id="homeArtistsSection">
            @include('home.partials.artists', [
                'popularArtists' => $popularArtists,
                'canBrowseEvents' => $canBrowseEvents,
            ])
        </div>

        <div id="homeFeaturedSection">
            @include('home.partials.featured', [
                'featuredEvents' => $featuredEvents,
                'canBrowseEvents' => $canBrowseEvents,
            ])
        </div>

        <div id="homeCategoriesSection">
            @include('home.partials.categories', [
                'categories' => $categories,
                'canBrowseEvents' => $canBrowseEvents,
            ])
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const homePage = document.querySelector('.home-page[data-home-live-url]');

            if (!homePage) {
                return;
            }

            let sliderIntervalId = null;
            let liveRefreshId = null;

            function clearSliderInterval() {
                if (sliderIntervalId) {
                    window.clearInterval(sliderIntervalId);
                    sliderIntervalId = null;
                }
            }

            function replaceSection(id, html) {
                const section = document.getElementById(id);

                if (section && typeof html === 'string') {
                    section.innerHTML = html;
                }
            }

            function initializeHeroSlider() {
                clearSliderInterval();

                const slider = document.querySelector('#homeHeroSection .hero-slider');

                if (!slider) {
                    return;
                }

                const slides = Array.from(slider.querySelectorAll('.hero-slide'));
                const dots = Array.from(slider.querySelectorAll('.hero-dot'));
                const prevBtn = slider.querySelector('#heroPrev');
                const nextBtn = slider.querySelector('#heroNext');

                if (!slides.length) {
                    return;
                }

                let current = slides.findIndex((slide) => slide.classList.contains('active'));

                if (current < 0) {
                    current = 0;
                }

                const hasMultipleSlides = slides.length > 1;

                function updateControls() {
                    if (prevBtn) {
                        prevBtn.style.display = hasMultipleSlides && current > 0 ? 'flex' : 'none';
                    }

                    if (nextBtn) {
                        nextBtn.style.display = hasMultipleSlides && current < slides.length - 1 ? 'flex' : 'none';
                    }
                }

                function showSlide(index) {
                    slides.forEach((slide, slideIndex) => {
                        slide.classList.toggle('active', slideIndex === index);
                    });

                    dots.forEach((dot, dotIndex) => {
                        dot.classList.toggle('active', dotIndex === index);
                    });

                    current = index;
                    updateControls();
                }

                function restartAutoSlide() {
                    clearSliderInterval();

                    if (!hasMultipleSlides) {
                        return;
                    }

                    sliderIntervalId = window.setInterval(function () {
                        showSlide(current < slides.length - 1 ? current + 1 : 0);
                    }, 5000);
                }

                if (prevBtn) {
                    prevBtn.addEventListener('click', function () {
                        showSlide(current > 0 ? current - 1 : slides.length - 1);
                        restartAutoSlide();
                    });
                }

                if (nextBtn) {
                    nextBtn.addEventListener('click', function () {
                        showSlide(current < slides.length - 1 ? current + 1 : 0);
                        restartAutoSlide();
                    });
                }

                dots.forEach((dot, index) => {
                    dot.addEventListener('click', function () {
                        showSlide(index);
                        restartAutoSlide();
                    });
                });

                showSlide(current);
                restartAutoSlide();
            }

            async function refreshLiveSections() {
                if (document.hidden) {
                    return;
                }

                try {
                    const response = await fetch(homePage.dataset.homeLiveUrl, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        return;
                    }

                    const payload = await response.json();

                    replaceSection('homeHeroSection', payload.hero);
                    replaceSection('homeArtistsSection', payload.artists);
                    replaceSection('homeFeaturedSection', payload.featured);
                    replaceSection('homeCategoriesSection', payload.categories);

                    initializeHeroSlider();
                } catch (error) {
                    console.error('Unable to refresh homepage sections.', error);
                }
            }

            initializeHeroSlider();
            liveRefreshId = window.setInterval(refreshLiveSections, 30000);

            window.addEventListener('beforeunload', function () {
                clearSliderInterval();

                if (liveRefreshId) {
                    window.clearInterval(liveRefreshId);
                }
            });
        });
    </script>
@endsection
