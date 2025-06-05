<!-- About section -->
<section id="about" class="section-area">
    <div class="container">
        <div class="grid grid-cols-1 gap-14 lg:grid-cols-2">
            <div class="w-full">
                <figure class="scroll-revealed max-w-[480px] mx-auto">
                    <img src="{{ asset('landingPage/assets/img/about-img.jpg') }}" alt="About image"
                        class="rounded-xl" />
                </figure>
            </div>

            <div class="w-full">
                <div class="scroll-revealed">
                    <h6 class="mb-2 block text-lg font-semibold text-primary">
                        About Us
                    </h6>
                    <h2 class="mb-6">
                        Empowering Vocational Students Through Structured Programming Education
                    </h2>
                </div>

                <div class="tabs scroll-revealed">
                    <nav class="tabs-nav flex flex-wrap gap-4 my-8" role="tablist" aria-label="About us tabs">
                        <button type="button"
                            class="tabs-link inline-block py-2 px-4 rounded-md text-body-light-12 dark:text-body-dark-12 bg-body-light-12/10 dark:bg-body-dark-12/10 text-inherit font-medium hover:bg-primary hover:text-primary-color focus:bg-primary focus:text-primary-color"
                            data-web-toggle="tabs" data-web-target="tabs-panel-profile" id="tabs-list-profile"
                            role="tab" aria-controls="tabs-panel-profile">
                            Our Profile
                        </button>

                        <button type="button"
                            class="tabs-link inline-block py-2 px-4 rounded-md text-body-light-12 dark:text-body-dark-12 bg-body-light-12/10 dark:bg-body-dark-12/10 text-inherit font-medium hover:bg-primary hover:text-primary-color focus:bg-primary focus:text-primary-color"
                            data-web-toggle="tabs" data-web-target="tabs-panel-vision" id="tabs-list-vision"
                            role="tab" aria-controls="tabs-panel-vision">
                            Our Vision
                        </button>

                    </nav>

                    <div class="tabs-content mt-4" id="tabs-panel-profile" tabindex="-1" role="tabpanel"
                        aria-labelledby="tabs-list-profile">
                        <p>
                            We are an education-focused team dedicated to providing accessible and structured
                            programming learning experiences for vocational students.
                        </p>
                        <p>
                            Our platform integrates national competency standards (SKKNI), measurable learning
                            outcomes through Bloom’s Taxonomy, and gamified elements to foster student motivation
                            and engagement.
                        </p>
                    </div>

                    <div class="tabs-content mt-4" id="tabs-panel-vision" tabindex="-1" role="tabpanel"
                        aria-labelledby="tabs-list-vision">
                        <p>
                            To become a trusted digital learning platform that equips vocational students with
                            relevant programming skills aligned with industry demands.
                        </p>
                        <p>
                            We aim to bridge the gap between education and employment by combining technology,
                            interactive content, and standardized evaluations for impactful learning.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>