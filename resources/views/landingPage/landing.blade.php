@extends('layout.front')

@section('content')

<main class="main relative">
    @include('landingPage.section.heroSection')

    @include('landingPage.section.aboutSection')
    
    @include('landingPage.section.servicesSection')

    {{-- @include('landingPage.section.introVideoSection') --}}

    {{-- @include('landingPage.section.portofolioSection') --}}

    {{-- @include('landingPage.section.pricingSection') --}}

    @include('landingPage.section.ctaSection')
    
    @include('landingPage.section.teamSection')

    {{-- @include('landingPage.section.testimonialsSection') --}}

    @include('landingPage.section.faqSection')

    {{-- @include('landingPage.section.blogSection') --}}

    {{-- @include('landingPage.section.clientsSection') --}}

    @include('landingPage.section.contactSection')








</main>

@endsection