@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v={{ time() }}">
@endsection

@section('content')

<div class="home-page">

    <section class="hero-slider">
        <div class="hero-slides" id="heroSlides">

            <div class="hero-slide active">
                <div class="hero-overlay"></div>
                <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?w=1600&h=700&fit=crop" alt="Music Festival 2026">

                <div class="hero-content">
                    <div class="hero-badge">Featured Event</div>
                    <h1 class="hero-title">Music Festival 2026</h1>
                    <p class="hero-subtitle">Coldplay · Wed, 29 Apr · Kuala Lumpur</p>

                    <div class="hero-actions">
                        <a href="{{ route('events.show', 1) }}" class="hero-primary-btn">Find Tickets</a>
                        <a href="{{ route('events.index', 1) }}" class="hero-secondary-btn">Browse Events</a>
                    </div>
                </div>
            </div>

            <div class="hero-slide">
                <div class="hero-overlay"></div>
                <img src="https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=1600&h=700&fit=crop" alt="Tech Conference 2026">

                <div class="hero-content">
                    <div class="hero-badge">Featured Event</div>
                    <h1 class="hero-title">Tech Conference 2026</h1>
                    <p class="hero-subtitle">Future Lab · Sun, 4 May · Selangor</p>

                    <div class="hero-actions">
                        <a href="{{ route('events.show', 2) }}" class="hero-primary-btn">Find Tickets</a>
                        <a href="{{ route('events.index') }}" class="hero-secondary-btn">Browse Events</a>
                    </div>
                </div>
            </div>

            <div class="hero-slide">
                <div class="hero-overlay"></div>
                <img src="https://images.unsplash.com/photo-1515169067868-5387ec356754?w=1600&h=700&fit=crop" alt="Food Carnival">

                <div class="hero-content">
                    <div class="hero-badge">Featured Event</div>
                    <h1 class="hero-title">Food Carnival</h1>
                    <p class="hero-subtitle">Street Food Fair · Fri, 2 May · Johor Bahru</p>

                    <div class="hero-actions">
                        <a href="{{ route('events.show', 3) }}" class="hero-primary-btn">Find Tickets</a>
                        <a href="{{ route('events.index') }}" class="hero-secondary-btn">Browse Events</a>
                    </div>
                </div>
            </div>

        </div>

        <button class="hero-arrow hero-arrow-left" id="heroPrev" type="button">
            <span>&#10094;</span>
        </button>

        <button class="hero-arrow hero-arrow-right" id="heroNext" type="button">
            <span>&#10095;</span>
        </button>

        <div class="hero-dots" id="heroDots">
            <button class="hero-dot active" type="button" data-slide="0"></button>
            <button class="hero-dot" type="button" data-slide="1"></button>
            <button class="hero-dot" type="button" data-slide="2"></button>
        </div>
    </section>

    <section class="home-section artist-section">

        <div class="section-header">
            <h2 class="section-title">Popular Artists</h2>
            <a href="{{ route('events.index') }}" class="section-link">See all →</a>
        </div>

        <div class="artist-list">

            <div class="artist-card">
                <img>Photo</img>
                <div class="artist-name">Coldplay</div>
                <div class="artist-meta">12 events</div>
            </div>

            <div class="artist-card">
                <img>Photo</img>
                <div class="artist-name">BTS</div>
                <div class="artist-meta">8 events</div>
            </div>

            <div class="artist-card">
                <img>Photo</img>               
                <div class="artist-name">Apink</div>
                <div class="artist-meta">6 events</div>
            </div>

            <div class="artist-card">
                <img>Photo</img>
                <div class="artist-name">Bruno Mars</div>
                <div class="artist-meta">5 events</div>
            </div>

        </div>

    </section>

    <section class="home-section">
        <div class="section-header">
            <h2 class="section-title">Featured events</h2>
            <a href="{{ route('events.index') }}" class="section-link">See all →</a>
        </div>

        <div class="featured-grid">
            <div class="featured-card">
                <div class="featured-card-top top-blue"></div>
                <div class="featured-card-body">
                    <div class="featured-tag">Concert</div>
                    <div class="featured-name">Music Festival 2026</div>
                    <div class="featured-meta">Coldplay · Wed 29 Apr · Kuala Lumpur</div>

                    <div class="featured-footer">
                        <div class="featured-price">RM288–RM888</div>
                        <a href="{{ route('events.show', 1) }}" class="featured-btn">Find Tickets</a>
                    </div>
                </div>
            </div>

            <div class="featured-card">
                <div class="featured-card-top top-purple"></div>
                <div class="featured-card-body">
                    <div class="featured-tag">Conference</div>
                    <div class="featured-name">Tech Conference 2026</div>
                    <div class="featured-meta">Future Lab · Sun 4 May · Selangor</div>

                    <div class="featured-footer">
                        <div class="featured-price">RM90–RM150</div>
                        <a href="{{ route('events.show', 2) }}" class="featured-btn">Find Tickets</a>
                    </div>
                </div>
            </div>

            <div class="featured-card">
                <div class="featured-card-top top-green"></div>
                <div class="featured-card-body">
                    <div class="featured-tag">Food & Drink</div>
                    <div class="featured-name">Food Carnival</div>
                    <div class="featured-meta">Street Food Fair · Fri 2 May · Johor Bahru</div>

                    <div class="featured-footer">
                        <div class="featured-price">RM30–RM80</div>
                        <a href="{{ route('events.show', 3) }}" class="featured-btn">Find Tickets</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.hero-dot');
        const prevBtn = document.getElementById('heroPrev');
        const nextBtn = document.getElementById('heroNext');

        let current = 0;
        let autoSlide;

        function updateArrows() {
            if (current === 0) {
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'flex';
            } else if (current === slides.length - 1) {
                nextBtn.style.display = 'none';
                prevBtn.style.display = 'flex';
            } else {
                prevBtn.style.display = 'flex';
                nextBtn.style.display = 'flex';
            }
        }

        //change slide
        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });

            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });

            current = index;
            updateArrows();
        }

        //move to next slide
        function nextSlide() {
            if (current < slides.length - 1) {
                showSlide(current + 1);
            }
        }

        function prevSlide() {
            if (current > 0) {
                showSlide(current - 1);
            }
        }

        function startAutoSlide() {
            autoSlide = setInterval(() => {
                if (current < slides.length - 1) {
                    nextSlide();
                } else {
                    showSlide(0); // reset to first
                }
            }, 4000);
        }

        function resetAutoSlide() {
            clearInterval(autoSlide);
            startAutoSlide();
        }

        nextBtn.addEventListener('click', () => {
            nextSlide();
            resetAutoSlide();
        });

        prevBtn.addEventListener('click', () => {
            prevSlide();
            resetAutoSlide();
        });

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                showSlide(index);
                resetAutoSlide();
            });
        });

        showSlide(0);
        startAutoSlide();
    });
</script>

@endsection
