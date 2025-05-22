    <!-- Portfolio section -->
    <section id="portfolio" class="section-area">
        <div class="container">
            <div class="scroll-revealed text-center max-w-[550px] mx-auto mb-12">
                <h6 class="mb-2 block text-lg font-semibold text-primary">
                    Portfolio
                </h6>
                <h2 class="mb-6">Our Recent Works</h2>
                <p>
                    There are many variations of passages of Lorem Ipsum available but
                    the majority have suffered alteration in some form.
                </p>
            </div>

            <nav class="scroll-revealed portfolio-menu mb-[3.750rem] text-center" aria-label="Portfolio filter">
                <button type="button"
                    class="font-semibold px-5 py-2 rounded-md text-body-light-11 dark:text-body-dark-11 hover:bg-primary hover:text-primary-color focus:bg-primary focus:text-primary-color active"
                    data-filter="all">
                    All Work
                </button>
                <button type="button"
                    class="font-semibold px-5 py-2 rounded-md text-body-light-11 dark:text-body-dark-11 hover:bg-primary hover:text-primary-color focus:bg-primary focus:text-primary-color"
                    data-filter="branding">
                    Branding
                </button>
                <button type="button"
                    class="font-semibold px-5 py-2 rounded-md text-body-light-11 dark:text-body-dark-11 hover:bg-primary hover:text-primary-color focus:bg-primary focus:text-primary-color"
                    data-filter="marketing">
                    Marketing
                </button>
                <button type="button"
                    class="font-semibold px-5 py-2 rounded-md text-body-light-11 dark:text-body-dark-11 hover:bg-primary hover:text-primary-color focus:bg-primary focus:text-primary-color"
                    data-filter="planning">
                    Planning
                </button>
                <button type="button"
                    class="font-semibold px-5 py-2 rounded-md text-body-light-11 dark:text-body-dark-11 hover:bg-primary hover:text-primary-color focus:bg-primary focus:text-primary-color"
                    data-filter="research">
                    Research
                </button>
            </nav>

            <div class="scroll-revealed portfolio-grid row">
                <div class="portfolio col-12 sm:col-6 lg:col-4">
                    <article class="group" data-filter="branding">
                        <div class="relative overflow-hidden w-full aspect-[4/3] rounded-xl">
                            <img src="{{ asset('landingPage/assets/img/portfolio/portfolio-1.jpg') }}"
                                alt="Graphics Design" class="w-full h-full object-cover" />
                            <div
                                class="absolute top-0 left-0 w-full aspect-[4/3] flex items-center justify-center bg-body-light-1/75 scale-[0.15] rounded-xl opacity-0 invisible group-hover:scale-95 group-hover:opacity-100 group-hover:visible">
                                <div class="flex flex-wrap gap-2 p-4">
                                    <div class="inline-block relative">
                                        <a href="{{ asset('landingPage/assets/img/portfolio/portfolio-1.jpg') }}"
                                            class="portfolio-box text-[1.75rem] text-primary-color bg-primary z-10 w-[60px] aspect-square rounded-lg text-center inline-flex items-center justify-center hover:bg-primary-light-10 hover:text-primary-color dark:hover:bg-primary-dark-10 dark:hover:text-primary-color focus:bg-primary-light-10 focus:text-primary-color dark:focus:bg-primary-dark-10 dark:focus:text-primary-color">
                                            <i class="lni lni-zoom-in"></i>
                                        </a>
                                    </div>
                                    <div class="portfolio-icon">
                                        <a href="javascript:void(0)"
                                            class="text-[1.75rem] text-primary-color bg-primary z-10 w-[60px] aspect-square rounded-lg text-center inline-flex items-center justify-center hover:bg-primary-light-10 hover:text-primary-color dark:hover:bg-primary-dark-10 dark:hover:text-primary-color focus:bg-primary-light-10 focus:text-primary-color dark:focus:bg-primary-dark-10 dark:focus:text-primary-color">
                                            <i class="lni lni-link"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-4">
                            <h4 class="mb-2">
                                <a href="javascript:void(0)" class="text-[1.5rem] leading-tight text-inherit">Graphics
                                    Design</a>
                            </h4>
                            <p>
                                Short description for the ones who look for something new.
                                Awesome!
                            </p>
                        </div>
                    </article>
                </div>

                <div class="portfolio col-12 sm:col-6 lg:col-4">
                    <article class="group" data-filter="research">
                        <div class="relative overflow-hidden w-full aspect-[4/3] rounded-xl">
                            <img src="{{ asset('landingPage/assets/img/portfolio/portfolio-2.jpg') }}"
                                alt="Web Development" class="w-full h-full object-cover" />
                            <div
                                class="absolute top-0 left-0 w-full aspect-[4/3] flex items-center justify-center bg-body-light-1/75 scale-[0.15] rounded-xl opacity-0 invisible group-hover:scale-95 group-hover:opacity-100 group-hover:visible">
                                <div class="flex flex-wrap gap-2 p-4">
                                    <div class="inline-block relative">
                                        <a href="{{ asset('landingPage/assets/img/portfolio/portfolio-2.jpg') }}"
                                            class="portfolio-box text-[1.75rem] text-primary-color bg-primary z-10 w-[60px] aspect-square rounded-lg text-center inline-flex items-center justify-center hover:bg-primary-light-10 hover:text-primary-color dark:hover:bg-primary-dark-10 dark:hover:text-primary-color focus:bg-primary-light-10 focus:text-primary-color dark:focus:bg-primary-dark-10 dark:focus:text-primary-color">
                                            <i class="lni lni-zoom-in"></i>
                                        </a>
                                    </div>
                                    <div class="portfolio-icon">
                                        <a href="javascript:void(0)"
                                            class="text-[1.75rem] text-primary-color bg-primary z-10 w-[60px] aspect-square rounded-lg text-center inline-flex items-center justify-center hover:bg-primary-light-10 hover:text-primary-color dark:hover:bg-primary-dark-10 dark:hover:text-primary-color focus:bg-primary-light-10 focus:text-primary-color dark:focus:bg-primary-dark-10 dark:focus:text-primary-color">
                                            <i class="lni lni-link"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-4">
                            <h4 class="mb-2">
                                <a href="javascript:void(0)" class="text-[1.5rem] leading-tight text-inherit">Web
                                    Development</a>
                            </h4>
                            <p>
                                Short description for the ones who look for something new.
                                Awesome!
                            </p>
                        </div>
                    </article>
                </div>

                <div class="portfolio col-12 sm:col-6 lg:col-4">
                    <article class="group" data-filter="marketing">
                        <div class="relative overflow-hidden w-full aspect-[4/3] rounded-xl">
                            <img src="{{ asset('landingPage/assets/img/portfolio/portfolio-3.jpg') }}"
                                alt="App Development" class="w-full h-full object-cover" />
                            <div
                                class="absolute top-0 left-0 w-full aspect-[4/3] flex items-center justify-center bg-body-light-1/75 scale-[0.15] rounded-xl opacity-0 invisible group-hover:scale-95 group-hover:opacity-100 group-hover:visible">
                                <div class="flex flex-wrap gap-2 p-4">
                                    <div class="inline-block relative">
                                        <a href="{{ asset('landingPage/assets/img/portfolio/portfolio-3.jpg') }}"
                                            class="portfolio-box text-[1.75rem] text-primary-color bg-primary z-10 w-[60px] aspect-square rounded-lg text-center inline-flex items-center justify-center hover:bg-primary-light-10 hover:text-primary-color dark:hover:bg-primary-dark-10 dark:hover:text-primary-color focus:bg-primary-light-10 focus:text-primary-color dark:focus:bg-primary-dark-10 dark:focus:text-primary-color">
                                            <i class="lni lni-zoom-in"></i>
                                        </a>
                                    </div>
                                    <div class="portfolio-icon">
                                        <a href="javascript:void(0)"
                                            class="text-[1.75rem] text-primary-color bg-primary z-10 w-[60px] aspect-square rounded-lg text-center inline-flex items-center justify-center hover:bg-primary-light-10 hover:text-primary-color dark:hover:bg-primary-dark-10 dark:hover:text-primary-color focus:bg-primary-light-10 focus:text-primary-color dark:focus:bg-primary-dark-10 dark:focus:text-primary-color">
                                            <i class="lni lni-link"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-4">
                            <h4 class="mb-2">
                                <a href="javascript:void(0)" class="text-[1.5rem] leading-tight text-inherit">App
                                    Development</a>
                            </h4>
                            <p>
                                Short description for the ones who look for something new.
                                Awesome!
                            </p>
                        </div>
                    </article>
                </div>

                <div class="portfolio col-12 sm:col-6 lg:col-4">
                    <article class="group" data-filter="planning">
                        <div class="relative overflow-hidden w-full aspect-[4/3] rounded-xl">
                            <img src="{{ asset('landingPage/assets/img/portfolio/portfolio-4.jpg') }}"
                                alt="Digital Marketing" class="w-full h-full object-cover" />
                            <div
                                class="absolute top-0 left-0 w-full aspect-[4/3] flex items-center justify-center bg-body-light-1/75 scale-[0.15] rounded-xl opacity-0 invisible group-hover:scale-95 group-hover:opacity-100 group-hover:visible">
                                <div class="flex flex-wrap gap-2 p-4">
                                    <div class="inline-block relative">
                                        <a href="{{ asset('landingPage/assets/img/portfolio/portfolio-4.jpg') }}"
                                            class="portfolio-box text-[1.75rem] text-primary-color bg-primary z-10 w-[60px] aspect-square rounded-lg text-center inline-flex items-center justify-center hover:bg-primary-light-10 hover:text-primary-color dark:hover:bg-primary-dark-10 dark:hover:text-primary-color focus:bg-primary-light-10 focus:text-primary-color dark:focus:bg-primary-dark-10 dark:focus:text-primary-color">
                                            <i class="lni lni-zoom-in"></i>
                                        </a>
                                    </div>
                                    <div class="portfolio-icon">
                                        <a href="javascript:void(0)"
                                            class="text-[1.75rem] text-primary-color bg-primary z-10 w-[60px] aspect-square rounded-lg text-center inline-flex items-center justify-center hover:bg-primary-light-10 hover:text-primary-color dark:hover:bg-primary-dark-10 dark:hover:text-primary-color focus:bg-primary-light-10 focus:text-primary-color dark:focus:bg-primary-dark-10 dark:focus:text-primary-color">
                                            <i class="lni lni-link"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-4">
                            <h4 class="mb-2">
                                <a href="javascript:void(0)" class="text-[1.5rem] leading-tight text-inherit">Digital
                                    Marketing</a>
                            </h4>
                            <p>
                                Short description for the ones who look for something new.
                                Awesome!
                            </p>
                        </div>
                    </article>
                </div>

                <div class="portfolio col-12 sm:col-6 lg:col-4">
                    <article class="group" data-filter="branding">
                        <div class="relative overflow-hidden w-full aspect-[4/3] rounded-xl">
                            <img src="{{ asset('landingPage/assets/img/portfolio/portfolio-5.jpg') }}"
                                alt="SEO Services" class="w-full h-full object-cover" />
                            <div
                                class="absolute top-0 left-0 w-full aspect-[4/3] flex items-center justify-center bg-body-light-1/75 scale-[0.15] rounded-xl opacity-0 invisible group-hover:scale-95 group-hover:opacity-100 group-hover:visible">
                                <div class="flex flex-wrap gap-2 p-4">
                                    <div class="inline-block relative">
                                        <a href="{{ asset('landingPage/assets/img/portfolio/portfolio-5.jpg') }}"
                                            class="portfolio-box text-[1.75rem] text-primary-color bg-primary z-10 w-[60px] aspect-square rounded-lg text-center inline-flex items-center justify-center hover:bg-primary-light-10 hover:text-primary-color dark:hover:bg-primary-dark-10 dark:hover:text-primary-color focus:bg-primary-light-10 focus:text-primary-color dark:focus:bg-primary-dark-10 dark:focus:text-primary-color">
                                            <i class="lni lni-zoom-in"></i>
                                        </a>
                                    </div>
                                    <div class="portfolio-icon">
                                        <a href="javascript:void(0)"
                                            class="text-[1.75rem] text-primary-color bg-primary z-10 w-[60px] aspect-square rounded-lg text-center inline-flex items-center justify-center hover:bg-primary-light-10 hover:text-primary-color dark:hover:bg-primary-dark-10 dark:hover:text-primary-color focus:bg-primary-light-10 focus:text-primary-color dark:focus:bg-primary-dark-10 dark:focus:text-primary-color">
                                            <i class="lni lni-link"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-4">
                            <h4 class="mb-2">
                                <a href="javascript:void(0)" class="text-[1.5rem] leading-tight text-inherit">SEO
                                    Services</a>
                            </h4>
                            <p>
                                Short description for the ones who look for something new.
                                Awesome!
                            </p>
                        </div>
                    </article>
                </div>

                <div class="portfolio col-12 sm:col-6 lg:col-4">
                    <article class="group" data-filter="marketing">
                        <div class="relative overflow-hidden w-full aspect-[4/3] rounded-xl">
                            <img src="{{ asset('landingPage/assets/img/portfolio/portfolio-6.jpg') }}"
                                alt="Product Design" class="w-full h-full object-cover" />
                            <div
                                class="absolute top-0 left-0 w-full aspect-[4/3] flex items-center justify-center bg-body-light-1/75 scale-[0.15] rounded-xl opacity-0 invisible group-hover:scale-95 group-hover:opacity-100 group-hover:visible">
                                <div class="flex flex-wrap gap-2 p-4">
                                    <div class="inline-block relative">
                                        <a href="{{ asset('landingPage/assets/img/portfolio/portfolio-6.jpg') }}"
                                            class="portfolio-box text-[1.75rem] text-primary-color bg-primary z-10 w-[60px] aspect-square rounded-lg text-center inline-flex items-center justify-center hover:bg-primary-light-10 hover:text-primary-color dark:hover:bg-primary-dark-10 dark:hover:text-primary-color focus:bg-primary-light-10 focus:text-primary-color dark:focus:bg-primary-dark-10 dark:focus:text-primary-color">
                                            <i class="lni lni-zoom-in"></i>
                                        </a>
                                    </div>
                                    <div class="portfolio-icon">
                                        <a href="javascript:void(0)"
                                            class="text-[1.75rem] text-primary-color bg-primary z-10 w-[60px] aspect-square rounded-lg text-center inline-flex items-center justify-center hover:bg-primary-light-10 hover:text-primary-color dark:hover:bg-primary-dark-10 dark:hover:text-primary-color focus:bg-primary-light-10 focus:text-primary-color dark:focus:bg-primary-dark-10 dark:focus:text-primary-color">
                                            <i class="lni lni-link"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-4">
                            <h4 class="mb-2">
                                <a href="javascript:void(0)" class="text-[1.5rem] leading-tight text-inherit">Product
                                    Design</a>
                            </h4>
                            <p>
                                Short description for the ones who look for something new.
                                Awesome!
                            </p>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>