<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Copyright" content="Halim Teguh Saputro" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Halim Teguh Saputro" />
    <meta name="rating" content="general" />
    <meta name="language" content="English" />
    <meta name="application-name" content="Iswara" />
    <meta name="description"
        content="Iswara is a digital learning platform designed to help vocational students master programming skills through structured modules, Taxonomy Bloom evaluation, and gamification." />
    <meta name="keywords"
        content="Iswara, programming education, vocational learning, Bloom's Taxonomy, gamified learning, SKKNI, coding platform" />
    <meta name="twitter:description" content="Tailwind CSS Company Landing Page Template by Ranyeh." />
    <meta name="twitter:image" content="{{ asset('landingPage/assets/img/inazuma-cover.png') }}" />
    <meta content="Iswara | Structured Programming Education for Vocational Students" property="og:title" />
    <meta content="Iswara" property="og:site_name" />
    <meta
        content="Learn programming with Iswara — a platform built for vocational students with structured modules, objective assessments, and gamified learning experience."
        property="og:description" />
    <meta content="{{ asset('landingPage/assets/img/inazuma-cover.png') }}" property="og:image" />
    <meta content="https://ranyeh24.github.io/inazuma-tailwind" property="og:url" />
    <meta content="website" property="og:type" />

    <meta name="msapplication-TileColor" content="#696cff" />
    <meta name="msapplication-TileImage" content="{{ asset('landingPage/assets/favicon/mstile-144x144.png') }}" />
    <meta name="theme-color" content="#696cff" />

    <!-- Page title -->
    <title>Iswara | Structured Programming Education for Vocational Students</title>

    <!-- Canonical -->
    <link rel="canonical" href="https://ranyeh24.github.io/inazuma-tailwind" />

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ asset('landingPage/assets/favicon/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('landingPage/assets/favicon/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="194x194"
        href="{{ asset('landingPage/assets/favicon/favicon-194x194.png') }}" />
    <link rel="icon" type="image/png" sizes="192x192"
        href="{{ asset('landingPage/assets/favicon/android-chrome-192x192.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('landingPage/assets/favicon/favicon-16x16.png') }}" />
    <link rel="manifest" href="{{ asset('landingPage/assets/favicon/site.webmanifest.json') }}" />
    <link rel="mask-icon" href="{{ asset('landingPage/assets/favicon/safari-pinned-tab.svg') }}" color="#696cff" />

    <!-- CSS Plugins -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
    <link rel="stylesheet" href="https://cdn.lineicons.com/4.0/lineicons.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">



    <link rel="stylesheet" href="{{ asset('landingPage/assets/css/main.css') }}" />
</head>

<body>
    <!-- Page loading -->
    <div class="page-loading fixed top-0 bottom-0 left-0 right-0 z-[99999] flex items-center justify-center bg-primary-light-1 dark:bg-primary-dark-1 opacity-100 visible pointer-events-auto"
        role="status" aria-live="polite" aria-atomic="true" aria-label="Loading...">
        <div class="grid-loader">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    <!-- Navbar -->
    <header class="ic-navbar absolute left-0 top-0 z-40 flex w-full items-center bg-transparent" role="banner"
        aria-label="Navigation bar">
        <div class="container">
            <div class="ic-navbar-container relative -mx-5 flex items-center justify-between">
                <div class="w-60 lg:w-56 max-w-full px-5">
                    <a href="." class="ic-navbar-logo block w-full py-5 text-primary-color">
                        <svg width="182" height="82" viewBox="0 0 182 82" class="w-full fill-current" id="NavbarBrand"
                            data-name="NavbarBrand" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M4.8 62V16.688H13.952V62H4.8ZM32.254 62.448C30.6753 62.448 29.054 62.2773 27.39 61.936C25.726 61.5947 24.19 61.0187 22.782 60.208C21.374 59.3547 20.1793 58.224 19.198 56.816C18.2167 55.3653 17.5767 53.5733 17.278 51.44H26.366C26.622 52.208 27.07 52.8693 27.71 53.424C28.3927 53.9787 29.1607 54.384 30.014 54.64C30.91 54.896 31.806 55.024 32.702 55.024C33.2993 55.024 33.8967 54.9813 34.494 54.896C35.0913 54.8107 35.646 54.64 36.158 54.384C36.67 54.128 37.0753 53.808 37.374 53.424C37.6727 52.9973 37.822 52.464 37.822 51.824C37.822 51.1413 37.6087 50.608 37.182 50.224C36.7553 49.84 36.158 49.5413 35.39 49.328C34.622 49.072 33.7473 48.8373 32.766 48.624C31.0167 48.24 29.1393 47.8133 27.134 47.344C25.1287 46.8747 23.4007 46.192 21.95 45.296C21.3527 44.8693 20.8193 44.4 20.35 43.888C19.8807 43.376 19.4967 42.8 19.198 42.16C18.8993 41.52 18.6647 40.8587 18.494 40.176C18.3233 39.4507 18.238 38.6827 18.238 37.872C18.238 36.0373 18.622 34.48 19.39 33.2C20.2007 31.8773 21.2673 30.8107 22.59 30C23.9127 29.1893 25.3847 28.6133 27.006 28.272C28.6273 27.888 30.2913 27.696 31.998 27.696C34.302 27.696 36.4353 28.08 38.398 28.848C40.3607 29.5733 41.982 30.7253 43.262 32.304C44.542 33.84 45.3313 35.8453 45.63 38.32H37.054C36.8833 37.3813 36.3073 36.6347 35.326 36.08C34.3447 35.4827 33.1287 35.184 31.678 35.184C31.166 35.184 30.6113 35.2267 30.014 35.312C29.4593 35.3973 28.9473 35.5467 28.478 35.76C28.0087 35.9733 27.6247 36.272 27.326 36.656C27.0273 36.9973 26.878 37.4667 26.878 38.064C26.878 38.5333 27.006 38.96 27.262 39.344C27.5607 39.728 27.966 40.048 28.478 40.304C29.0327 40.56 29.694 40.7947 30.462 41.008C31.9127 41.3493 33.3633 41.6693 34.814 41.968C36.3073 42.224 37.63 42.5013 38.782 42.8C40.19 43.0987 41.47 43.5893 42.622 44.272C43.8167 44.9547 44.7767 45.8933 45.502 47.088C46.27 48.24 46.654 49.7547 46.654 51.632C46.654 53.7653 46.2273 55.536 45.374 56.944C44.5207 58.3093 43.3687 59.3973 41.918 60.208C40.51 61.0187 38.9527 61.5947 37.246 61.936C35.582 62.2773 33.918 62.448 32.254 62.448Z" />
                            <path
                                d="M103.208 62.448C101.672 62.448 100.179 62.256 98.728 61.872C97.32 61.4453 96.0613 60.8053 94.952 59.952C93.8427 59.0987 92.968 58.032 92.328 56.752C91.688 55.472 91.368 53.9787 91.368 52.272C91.368 49.968 91.8373 48.1333 92.776 46.768C93.7147 45.36 94.9733 44.2933 96.552 43.568C98.1307 42.8427 99.944 42.352 101.992 42.096C104.083 41.84 106.237 41.712 108.456 41.712H113.32C113.32 40.4747 113.085 39.3867 112.616 38.448C112.189 37.4667 111.528 36.72 110.632 36.208C109.736 35.6533 108.584 35.376 107.176 35.376C106.28 35.376 105.405 35.504 104.552 35.76C103.741 35.9733 103.059 36.2933 102.504 36.72C101.949 37.1467 101.587 37.7013 101.416 38.384H92.328C92.584 36.5067 93.1813 34.9067 94.12 33.584C95.0587 32.2187 96.232 31.1093 97.64 30.256C99.048 29.4027 100.584 28.784 102.248 28.4C103.955 27.9733 105.704 27.76 107.496 27.76C112.403 27.76 116.029 29.168 118.376 31.984C120.723 34.7573 121.896 38.704 121.896 43.824V62H113.832L113.704 57.648C112.637 59.1413 111.421 60.2293 110.056 60.912C108.733 61.5947 107.453 62.0213 106.216 62.192C104.979 62.3627 103.976 62.448 103.208 62.448ZM105.256 55.344C106.749 55.344 108.115 55.0453 109.352 54.448C110.589 53.8507 111.571 53.04 112.296 52.016C113.021 50.992 113.384 49.84 113.384 48.56V47.728H107.24C106.344 47.728 105.469 47.7707 104.616 47.856C103.805 47.9413 103.059 48.112 102.376 48.368C101.736 48.5813 101.224 48.944 100.84 49.456C100.456 49.968 100.264 50.6507 100.264 51.504C100.264 52.3573 100.477 53.0827 100.904 53.68C101.373 54.2347 101.992 54.6613 102.76 54.96C103.528 55.216 104.36 55.344 105.256 55.344ZM126.335 62V28.272H134.399L134.975 32.304C135.828 31.024 136.788 30.064 137.855 29.424C138.922 28.7413 140.031 28.2933 141.183 28.08C142.335 27.824 143.508 27.696 144.703 27.696C145.258 27.696 145.77 27.7173 146.239 27.76C146.708 27.76 147.05 27.76 147.263 27.76V36.4H145.151C143.146 36.4 141.396 36.7413 139.903 37.424C138.41 38.1067 137.258 39.1733 136.447 40.624C135.679 42.0747 135.295 43.9307 135.295 46.192V62H126.335ZM159.588 62.448C158.052 62.448 156.559 62.256 155.108 61.872C153.7 61.4453 152.441 60.8053 151.332 59.952C150.223 59.0987 149.348 58.032 148.708 56.752C148.068 55.472 147.748 53.9787 147.748 52.272C147.748 49.968 148.217 48.1333 149.156 46.768C150.095 45.36 151.353 44.2933 152.932 43.568C154.511 42.8427 156.324 42.352 158.372 42.096C160.463 41.84 162.617 41.712 164.836 41.712H169.7C169.7 40.4747 169.465 39.3867 168.996 38.448C168.569 37.4667 167.908 36.72 167.012 36.208C166.116 35.6533 164.964 35.376 163.556 35.376C162.66 35.376 161.785 35.504 160.932 35.76C160.121 35.9733 159.439 36.2933 158.884 36.72C158.329 37.1467 157.967 37.7013 157.796 38.384H148.708C148.964 36.5067 149.561 34.9067 150.5 33.584C151.439 32.2187 152.612 31.1093 154.02 30.256C155.428 29.4027 156.964 28.784 158.628 28.4C160.335 27.9733 162.084 27.76 163.876 27.76C168.783 27.76 172.409 29.168 174.756 31.984C177.103 34.7573 178.276 38.704 178.276 43.824V62H170.212L170.084 57.648C169.017 59.1413 167.801 60.2293 166.436 60.912C165.113 61.5947 163.833 62.0213 162.596 62.192C161.359 62.3627 160.356 62.448 159.588 62.448ZM161.636 55.344C163.129 55.344 164.495 55.0453 165.732 54.448C166.969 53.8507 167.951 53.04 168.676 52.016C169.401 50.992 169.764 49.84 169.764 48.56V47.728H163.62C162.724 47.728 161.849 47.7707 160.996 47.856C160.185 47.9413 159.439 48.112 158.756 48.368C158.116 48.5813 157.604 48.944 157.22 49.456C156.836 49.968 156.644 50.6507 156.644 51.504C156.644 52.3573 156.857 53.0827 157.284 53.68C157.753 54.2347 158.372 54.6613 159.14 54.96C159.908 55.216 160.74 55.344 161.636 55.344Z" />
                            <path
                                d="M63.3984 62.5469L42 24.4784C63.3984 19.4932 68.8618 35.8083 76.1463 44.8722C71.5935 22.6655 88.8943 21.3058 98 24.0252C85.7073 33.089 92.3357 49.8573 77.9675 63C76.1463 62.5469 70.2276 52.1234 70.2276 52.1234L63.3984 62.5469Z"
                                stroke-linejoin="round" id="NavbarBrandIcon" />
                        </svg>



                    </a>
                </div>
                <div class="flex w-full items-center justify-between px-5">
                    <div>
                        <button type="button"
                            class="ic-navbar-toggler absolute right-4 top-1/2 block -translate-y-1/2 rounded-md px-3 py-[6px] text-[22px]/none text-primary-color ring-primary focus:ring-2 lg:hidden"
                            data-web-toggle="navbar-collapse" data-web-target="navbarMenu" aria-expanded="false"
                            aria-label="Toggle navigation menu">
                            <i class="lni lni-menu"></i>
                        </button>

                        <nav id="navbarMenu"
                            class="ic-navbar-collapse absolute right-4 top-[80px] w-full max-w-[250px] rounded-lg hidden bg-primary-light-1 py-5 shadow-lg dark:bg-primary-dark-1 lg:static lg:block lg:w-full lg:max-w-full lg:bg-transparent lg:py-0 lg:shadow-none dark:lg:bg-transparent xl:px-6">
                            <ul class="block lg:flex" role="menu" aria-label="Navigation menu">
                                <li class="group relative">
                                    <a href="#home"
                                        class="ic-page-scroll mx-8 flex py-2 text-base font-medium text-body-light-12 group-hover:text-primary dark:text-body-dark-12 lg:mx-0 lg:inline-flex lg:px-0 lg:py-6 lg:text-primary-color lg:dark:text-primary-color lg:group-hover:text-primary-color lg:group-hover:opacity-70 active"
                                        role="menuitem">Home</a>
                                </li>

                                <li class="group relative">
                                    <a href="#services"
                                        class="ic-page-scroll mx-8 flex py-2 text-base font-medium text-body-light-12 group-hover:text-primary dark:text-body-dark-12 lg:mr-0 lg:inline-flex lg:px-0 lg:py-6 lg:text-primary-color lg:dark:text-primary-color lg:group-hover:text-primary-color lg:group-hover:opacity-70"
                                        role="menuitem">Services</a>
                                </li>

                                {{-- <li class="group relative">
                                    <a href="#portfolio"
                                        class="ic-page-scroll mx-8 flex py-2 text-base font-medium text-body-light-12 group-hover:text-primary dark:text-body-dark-12 lg:mr-0 lg:inline-flex lg:px-0 lg:py-6 lg:text-primary-color lg:dark:text-primary-color lg:group-hover:text-primary-color lg:group-hover:opacity-70"
                                        role="menuitem">Portfolio</a>
                                </li> --}}

                                {{-- <li class="group relative">
                                    <a href="#pricing"
                                        class="ic-page-scroll mx-8 flex py-2 text-base font-medium text-body-light-12 group-hover:text-primary dark:text-body-dark-12 lg:mr-0 lg:inline-flex lg:px-0 lg:py-6 lg:text-primary-color lg:dark:text-primary-color lg:group-hover:text-primary-color lg:group-hover:opacity-70"
                                        role="menuitem">Pricing</a>
                                </li> --}}

                                <li class="group relative">
                                    <a href="#team"
                                        class="ic-page-scroll mx-8 flex py-2 text-base font-medium text-body-light-12 group-hover:text-primary dark:text-body-dark-12 lg:mr-0 lg:inline-flex lg:px-0 lg:py-6 lg:text-primary-color lg:dark:text-primary-color lg:group-hover:text-primary-color lg:group-hover:opacity-70"
                                        role="menuitem">Team</a>
                                </li>

                                <li class="group relative">
                                    <a href="#contact"
                                        class="ic-page-scroll mx-8 flex py-2 text-base font-medium text-body-light-12 group-hover:text-primary dark:text-body-dark-12 lg:mr-0 lg:inline-flex lg:px-0 lg:py-6 lg:text-primary-color lg:dark:text-primary-color lg:group-hover:text-primary-color lg:group-hover:opacity-70"
                                        role="menuitem">Contact</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="flex items-center justify-end pr-[52px] lg:pr-0">
                        <button type="button" class="inline-flex items-center text-primary-color text-[24px]/none"
                            aria-label="Switch theme" data-web-trigger="web-theme"></button>
                        <div class="hidden sm:flex">
                            <a href="/login"
                                class="btn-navbar ml-5 px-6 py-3 rounded-md bg-primary-color bg-opacity-20 text-base font-medium text-primary-color hover:bg-opacity-100 hover:text-primary"
                                role="button">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    @yield('content')

    <!-- Footer -->
    <footer class="bg-primary-dark-2 text-white">
        <div class="container py-20 lg:py-[100px]">
            <div class="row">
                <div class="col-12 order-first lg:col-4">
                    <div class="w-full">
                        <a href="." class="inline-block mb-5">
                            <svg width="182" height="82" viewBox="0 0 182 82" id="FooterBrand" class="w-full fill-current
                                data-name="FooterBrand" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M4.8 62V16.688H13.952V62H4.8ZM32.254 62.448C30.6753 62.448 29.054 62.2773 27.39 61.936C25.726 61.5947 24.19 61.0187 22.782 60.208C21.374 59.3547 20.1793 58.224 19.198 56.816C18.2167 55.3653 17.5767 53.5733 17.278 51.44H26.366C26.622 52.208 27.07 52.8693 27.71 53.424C28.3927 53.9787 29.1607 54.384 30.014 54.64C30.91 54.896 31.806 55.024 32.702 55.024C33.2993 55.024 33.8967 54.9813 34.494 54.896C35.0913 54.8107 35.646 54.64 36.158 54.384C36.67 54.128 37.0753 53.808 37.374 53.424C37.6727 52.9973 37.822 52.464 37.822 51.824C37.822 51.1413 37.6087 50.608 37.182 50.224C36.7553 49.84 36.158 49.5413 35.39 49.328C34.622 49.072 33.7473 48.8373 32.766 48.624C31.0167 48.24 29.1393 47.8133 27.134 47.344C25.1287 46.8747 23.4007 46.192 21.95 45.296C21.3527 44.8693 20.8193 44.4 20.35 43.888C19.8807 43.376 19.4967 42.8 19.198 42.16C18.8993 41.52 18.6647 40.8587 18.494 40.176C18.3233 39.4507 18.238 38.6827 18.238 37.872C18.238 36.0373 18.622 34.48 19.39 33.2C20.2007 31.8773 21.2673 30.8107 22.59 30C23.9127 29.1893 25.3847 28.6133 27.006 28.272C28.6273 27.888 30.2913 27.696 31.998 27.696C34.302 27.696 36.4353 28.08 38.398 28.848C40.3607 29.5733 41.982 30.7253 43.262 32.304C44.542 33.84 45.3313 35.8453 45.63 38.32H37.054C36.8833 37.3813 36.3073 36.6347 35.326 36.08C34.3447 35.4827 33.1287 35.184 31.678 35.184C31.166 35.184 30.6113 35.2267 30.014 35.312C29.4593 35.3973 28.9473 35.5467 28.478 35.76C28.0087 35.9733 27.6247 36.272 27.326 36.656C27.0273 36.9973 26.878 37.4667 26.878 38.064C26.878 38.5333 27.006 38.96 27.262 39.344C27.5607 39.728 27.966 40.048 28.478 40.304C29.0327 40.56 29.694 40.7947 30.462 41.008C31.9127 41.3493 33.3633 41.6693 34.814 41.968C36.3073 42.224 37.63 42.5013 38.782 42.8C40.19 43.0987 41.47 43.5893 42.622 44.272C43.8167 44.9547 44.7767 45.8933 45.502 47.088C46.27 48.24 46.654 49.7547 46.654 51.632C46.654 53.7653 46.2273 55.536 45.374 56.944C44.5207 58.3093 43.3687 59.3973 41.918 60.208C40.51 61.0187 38.9527 61.5947 37.246 61.936C35.582 62.2773 33.918 62.448 32.254 62.448Z"
                                    fill="#eeeef0" />
                                <path
                                    d="M103.208 62.448C101.672 62.448 100.179 62.256 98.728 61.872C97.32 61.4453 96.0613 60.8053 94.952 59.952C93.8427 59.0987 92.968 58.032 92.328 56.752C91.688 55.472 91.368 53.9787 91.368 52.272C91.368 49.968 91.8373 48.1333 92.776 46.768C93.7147 45.36 94.9733 44.2933 96.552 43.568C98.1307 42.8427 99.944 42.352 101.992 42.096C104.083 41.84 106.237 41.712 108.456 41.712H113.32C113.32 40.4747 113.085 39.3867 112.616 38.448C112.189 37.4667 111.528 36.72 110.632 36.208C109.736 35.6533 108.584 35.376 107.176 35.376C106.28 35.376 105.405 35.504 104.552 35.76C103.741 35.9733 103.059 36.2933 102.504 36.72C101.949 37.1467 101.587 37.7013 101.416 38.384H92.328C92.584 36.5067 93.1813 34.9067 94.12 33.584C95.0587 32.2187 96.232 31.1093 97.64 30.256C99.048 29.4027 100.584 28.784 102.248 28.4C103.955 27.9733 105.704 27.76 107.496 27.76C112.403 27.76 116.029 29.168 118.376 31.984C120.723 34.7573 121.896 38.704 121.896 43.824V62H113.832L113.704 57.648C112.637 59.1413 111.421 60.2293 110.056 60.912C108.733 61.5947 107.453 62.0213 106.216 62.192C104.979 62.3627 103.976 62.448 103.208 62.448ZM105.256 55.344C106.749 55.344 108.115 55.0453 109.352 54.448C110.589 53.8507 111.571 53.04 112.296 52.016C113.021 50.992 113.384 49.84 113.384 48.56V47.728H107.24C106.344 47.728 105.469 47.7707 104.616 47.856C103.805 47.9413 103.059 48.112 102.376 48.368C101.736 48.5813 101.224 48.944 100.84 49.456C100.456 49.968 100.264 50.6507 100.264 51.504C100.264 52.3573 100.477 53.0827 100.904 53.68C101.373 54.2347 101.992 54.6613 102.76 54.96C103.528 55.216 104.36 55.344 105.256 55.344ZM126.335 62V28.272H134.399L134.975 32.304C135.828 31.024 136.788 30.064 137.855 29.424C138.922 28.7413 140.031 28.2933 141.183 28.08C142.335 27.824 143.508 27.696 144.703 27.696C145.258 27.696 145.77 27.7173 146.239 27.76C146.708 27.76 147.05 27.76 147.263 27.76V36.4H145.151C143.146 36.4 141.396 36.7413 139.903 37.424C138.41 38.1067 137.258 39.1733 136.447 40.624C135.679 42.0747 135.295 43.9307 135.295 46.192V62H126.335ZM159.588 62.448C158.052 62.448 156.559 62.256 155.108 61.872C153.7 61.4453 152.441 60.8053 151.332 59.952C150.223 59.0987 149.348 58.032 148.708 56.752C148.068 55.472 147.748 53.9787 147.748 52.272C147.748 49.968 148.217 48.1333 149.156 46.768C150.095 45.36 151.353 44.2933 152.932 43.568C154.511 42.8427 156.324 42.352 158.372 42.096C160.463 41.84 162.617 41.712 164.836 41.712H169.7C169.7 40.4747 169.465 39.3867 168.996 38.448C168.569 37.4667 167.908 36.72 167.012 36.208C166.116 35.6533 164.964 35.376 163.556 35.376C162.66 35.376 161.785 35.504 160.932 35.76C160.121 35.9733 159.439 36.2933 158.884 36.72C158.329 37.1467 157.967 37.7013 157.796 38.384H148.708C148.964 36.5067 149.561 34.9067 150.5 33.584C151.439 32.2187 152.612 31.1093 154.02 30.256C155.428 29.4027 156.964 28.784 158.628 28.4C160.335 27.9733 162.084 27.76 163.876 27.76C168.783 27.76 172.409 29.168 174.756 31.984C177.103 34.7573 178.276 38.704 178.276 43.824V62H170.212L170.084 57.648C169.017 59.1413 167.801 60.2293 166.436 60.912C165.113 61.5947 163.833 62.0213 162.596 62.192C161.359 62.3627 160.356 62.448 159.588 62.448ZM161.636 55.344C163.129 55.344 164.495 55.0453 165.732 54.448C166.969 53.8507 167.951 53.04 168.676 52.016C169.401 50.992 169.764 49.84 169.764 48.56V47.728H163.62C162.724 47.728 161.849 47.7707 160.996 47.856C160.185 47.9413 159.439 48.112 158.756 48.368C158.116 48.5813 157.604 48.944 157.22 49.456C156.836 49.968 156.644 50.6507 156.644 51.504C156.644 52.3573 156.857 53.0827 157.284 53.68C157.753 54.2347 158.372 54.6613 159.14 54.96C159.908 55.216 160.74 55.344 161.636 55.344Z"
                                    fill="#eeeef0" />
                                <path
                                    d="M63.3984 62.5469L42 24.4784C63.3984 19.4932 68.8618 35.8083 76.1463 44.8722C71.5935 22.6655 88.8943 21.3058 98 24.0252C85.7073 33.089 92.3357 49.8573 77.9675 63C76.1463 62.5469 70.2276 52.1234 70.2276 52.1234L63.3984 62.5469Z"
                                    fill="#696CFF" stroke-linejoin="round" />
                            </svg>

                            {{-- <svg id="FooterBrand" class="h-[40px]" data-name="FooterBrand"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 118.11">
                                <path
                                    d="M272.26,29.77h14.86V58q0,8.24,1.13,11.44a10,10,0,0,0,3.64,5,10.44,10.44,0,0,0,6.18,1.77,10.75,10.75,0,0,0,6.23-1.75,10.26,10.26,0,0,0,3.81-5.14q.92-2.52.91-10.82V29.77h14.7V54.59q0,15.33-2.42,21a23,23,0,0,1-8.72,10.58q-5.76,3.68-14.64,3.68-9.65,0-15.59-4.3a22.79,22.79,0,0,1-8.37-12q-1.73-5.32-1.72-19.37Z"
                                    style="fill: #eeeef0" />
                                <path
                                    d="M338.36,29.77h14.69V36.5a25.29,25.29,0,0,1,8.38-6.19,24.53,24.53,0,0,1,10.05-2A18.63,18.63,0,0,1,381.39,31a19.46,19.46,0,0,1,7.11,7.86A23.64,23.64,0,0,1,397.09,31a23.34,23.34,0,0,1,11.12-2.69,20.65,20.65,0,0,1,11,2.9A16.79,16.79,0,0,1,426,38.76q2.08,4.68,2.08,15.24V88.34h-14.8V58.62q0-9.94-2.48-13.48t-7.43-3.52a11.22,11.22,0,0,0-6.75,2.15,12.66,12.66,0,0,0-4.44,5.95q-1.45,3.8-1.46,12.19V88.34h-14.8V60q0-7.86-1.16-11.38a10.27,10.27,0,0,0-3.48-5.25,9.15,9.15,0,0,0-5.61-1.72A11,11,0,0,0,359,43.8a13,13,0,0,0-4.48,6.1q-1.49,3.93-1.49,12.38V88.34H338.36Z"
                                    style="fill: #eeeef0" />
                                <path
                                    d="M485.31,29.77H500V88.34H485.31V82.15A29,29,0,0,1,476.68,88a24.24,24.24,0,0,1-9.39,1.8q-11.34,0-19.62-8.8t-8.28-21.88q0-13.56,8-22.23a25.55,25.55,0,0,1,19.47-8.66,24.72,24.72,0,0,1,9.89,2,27.17,27.17,0,0,1,8.55,6ZM469.86,41.83a14.85,14.85,0,0,0-11.32,4.82A17.33,17.33,0,0,0,454,59a17.55,17.55,0,0,0,4.59,12.49,14.85,14.85,0,0,0,11.29,4.9,15.15,15.15,0,0,0,11.48-4.82Q486,66.76,486,59q0-7.65-4.56-12.38A15.31,15.31,0,0,0,469.86,41.83Z"
                                    style="fill: #eeeef0" />
                                <path
                                    d="M28,29.77h14.7v6a35.37,35.37,0,0,1,9-5.87,22.09,22.09,0,0,1,8.31-1.64,20.09,20.09,0,0,1,14.78,6.08Q80,39.51,80,49.64v38.7H65.45V62.69q0-10.48-.94-13.92a9.57,9.57,0,0,0-3.27-5.25,9.18,9.18,0,0,0-5.78-1.8,10.82,10.82,0,0,0-7.65,3A16,16,0,0,0,43.38,53q-.65,2.74-.64,11.88v23.5H28Z"
                                    style="fill: #eeeef0" />
                                <path
                                    d="M137.42,29.77h14.69V88.34H137.42V82.15A29,29,0,0,1,128.79,88a24.19,24.19,0,0,1-9.38,1.8q-11.34,0-19.63-8.8T91.5,59.16q0-13.56,8-22.23A25.53,25.53,0,0,1,119,28.27a24.69,24.69,0,0,1,9.89,2,27.17,27.17,0,0,1,8.55,6ZM122,41.83a14.87,14.87,0,0,0-11.32,4.82A17.37,17.37,0,0,0,106.15,59a17.54,17.54,0,0,0,4.58,12.49A14.85,14.85,0,0,0,122,76.39a15.12,15.12,0,0,0,11.48-4.82q4.56-4.81,4.56-12.62,0-7.65-4.56-12.38A15.3,15.3,0,0,0,122,41.83Z"
                                    style="fill: #eeeef0" />
                                <rect y="29.77" width="14.69" height="58.56" style="fill: #eeeef0" />
                                <polygon
                                    points="226.39 62.27 232.8 68.67 282.23 118.11 163.48 89.2 196.84 55.84 190.43 49.44 140.99 0 259.75 28.91 226.39 62.27"
                                    style="fill: #3d63dd" />
                            </svg> --}}
                        </a>

                        <p class="mb-8 text-body-dark-11">
                            We are an education-focused team dedicated to providing accessible and structured programming learning experiences for vocational students.
                        </p>

                        <div class="-mx-3 flex items-center">
                            <a href="javascript:void(0)"
                                class="px-3 text-body-dark-11 hover:text-primary text-[22px] leading-none">
                                <i class="lni lni-facebook-fill"></i>
                            </a>

                            <a href="javascript:void(0)"
                                class="px-3 text-body-dark-11 hover:text-primary text-[22px] leading-none">
                                <i class="lni lni-twitter-original"></i>
                            </a>

                            <a href="javascript:void(0)"
                                class="px-3 text-body-dark-11 hover:text-primary text-[22px] leading-none">
                                <i class="lni lni-instagram-original"></i>
                            </a>

                            <a href="javascript:void(0)"
                                class="px-3 text-body-dark-11 hover:text-primary text-[22px] leading-none">
                                <i class="lni lni-linkedin-original"></i>
                            </a>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-6 lg:col-2">
                    <div class="w-full">
                        <h4 class="mb-9 text-lg font-semibold text-inherit">Solutions</h4>
                        <ul>
                            <li>
                                <a href="javascript:void(0)"
                                    class="mb-3 inline-block text-body-dark-11 hover:text-primary">Marketing</a>
                            </li>
                            <li>
                                <a href="javascript:void(0)"
                                    class="mb-3 inline-block text-body-dark-11 hover:text-primary">Analytics</a>
                            </li>
                            <li>
                                <a href="javascript:void(0)"
                                    class="mb-3 inline-block text-body-dark-11 hover:text-primary">Commerce</a>
                            </li>
                            <li>
                                <a href="javascript:void(0)"
                                    class="mb-3 inline-block text-body-dark-11 hover:text-primary">Insights</a>
                            </li>
                        </ul>
                    </div>
                </div> --}}
                <div class="col-6 lg:col-2">
                    <div class="w-full">
                        <h4 class="mb-9 text-lg font-semibold text-inherit">Support</h4>
                        <ul>
                            {{-- <li>
                                <a href="javascript:void(0)"
                                    class="mb-3 inline-block text-body-dark-11 hover:text-primary">Pricing</a>
                            </li> --}}
                            {{-- <li>
                                <a href="javascript:void(0)"
                                    class="mb-3 inline-block text-body-dark-11 hover:text-primary">Documentation</a>
                            </li> --}}
                            <li>
                                <a href="javascript:void(0)"
                                    class="mb-3 inline-block text-body-dark-11 hover:text-primary">Guides</a>
                            </li>
                            {{-- <li>
                                <a href="javascript:void(0)"
                                    class="mb-3 inline-block text-body-dark-11 hover:text-primary">API Status</a>
                            </li> --}}
                        </ul>
                    </div>
                </div>
                <div class="col-12 -order-3 lg:col-4 lg:order-1">
                    <div class="w-full">
                        <h4 class="mb-9 text-lg font-semibold text-inherit">Subscribe</h4>

                        <p class="text-body-dark-11">
                            Subscribe to our newsletter for the latest updates
                        </p>

                        <form action="#" method="POST" target="_blank" class="mt-8 flex">
                            <input type="email" name="email"
                                class="inline-block flex-grow px-5 py-3 rounded-md rounded-e-none border border-solid border-alpha-dark text-inherit text-base focus:border-primary"
                                placeholder="Email address" required />

                            <button type="submit"
                                class="inline-block py-3 w-[50px] rounded-md rounded-s-none text-center text-lg/none bg-primary text-primary-color hover:bg-primary-light-10 dark:hover:bg-primary-dark-10 focus:bg-primary-light-10 dark:focus:bg-primary-dark-10">
                                <i class="lni lni-envelope"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full border-t border-solid border-alpha-dark"></div>
        <div class="container py-8">
            <div class="flex flex-wrap">
                <div class="w-full md:w-1/2">
                    <div class="my-1">
                        <div class="flex flex-wrap justify-center gap-x-3 md:justify-start">
                            <a href="javascript:void(0)" class="text-body-dark-11 hover:text-body-dark-12">Privacy
                                Policy</a>
                            <a href="javascript:void(0)" class="text-body-dark-11 hover:text-body-dark-12">Legal
                                Notice</a>
                            <a href="javascript:void(0)" class="text-body-dark-11 hover:text-body-dark-12">Terms of
                                Service</a>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/2">
                    <div class="my-1 flex justify-center md:justify-end">
                        <p class="text-body-dark-11">
                            &#169; 2025 Iswara Inc. All rights reserved. Theme by <a
                                href="https://themewagon.com/themes/inazuma/" target="_blank">ThemeWagon</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <button type="button"
        class="inline-flex w-12 h-12 rounded-md items-center justify-center text-lg/none bg-primary text-primary-color hover:bg-primary-light-10 dark:hover:bg-primary-dark-10 focus:bg-primary-light-10 dark:focus:bg-primary-dark-10 fixed bottom-[117px] right-[20px] hover:-translate-y-1 opacity-100 visible z-50 is-hided"
        data-web-trigger="scroll-top" aria-label="Scroll to top">
        <i class="lni lni-chevron-up"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    <script src="https://unpkg.com/scrollreveal@4.0.0/dist/scrollreveal.min.js"></script>

    <script src="{{ asset('landingPage/assets/js/main.js') }}"></script>
    <script>
        // Scroll Reveal
      const sr = ScrollReveal({
        origin: "bottom",
        distance: "16px",
        duration: 1000,
        reset: false,
      });

      sr.reveal(`.scroll-revealed`, {
        cleanup: true,
      });

      // GLightBox
      GLightbox({
        selector: ".video-popup",
        href: "https://www.youtube.com/watch?v=r44RKWyfcFw&fbclid=IwAR21beSJORalzmzokxDRcGfkZA1AtRTE__l5N4r09HcGS5Y6vOluyouM9EM",
        type: "video",
        source: "youtube",
        width: 900,
        autoplayVideos: true,
      });

      const myGallery3 = GLightbox({
        selector: ".portfolio-box",
        type: "image",
        width: 900,
      });

      // Testimonial
      const testimonialSwiper = new Swiper(".testimonial-carousel", {
        slidesPerView: 1,
        spaceBetween: 30,

        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },

        breakpoints: {
          640: {
            slidesPerView: 2,
            spaceBetween: 30,
          },
          1024: {
            slidesPerView: 3,
            spaceBetween: 30,
          },
          1280: {
            slidesPerView: 3,
            spaceBetween: 30,
          },
        },
      });
    </script>
</body>

</html>