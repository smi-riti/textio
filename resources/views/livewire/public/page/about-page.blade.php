<div>


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap');

        :root {
            --text-color: #171717;
            --accent-color: #8f4da7;
            --background-color: #f9f6fb;
            --card-background: #ffffff;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background-color);
            margin: 0;
            color: var(--text-color);
            line-height: 1.6;
        }

       
       
        .text-accent {
            color: var(--accent-color);
        }

        .bg-accent {
            background-color: var(--accent-color);
        }

        /* Hero Section */
        .hero-section {
            background-color: #f3e8ff;

            text-align: center;
        }

        .hero-section h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .hero-section p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        /* CEO Section */
        .ceo-section {}

        .ceo-section .content {
            background-color: var(--card-background);
            border-radius: 8px;
            padding: 3rem;
            border: 1px solid rgba(143, 77, 167, 0.1);
        }

        .ceo-section h2 {
            font-size: 2.2rem;
            margin-bottom: 1rem;
        }

        .ceo-section h3 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .ceo-section p.quote {
            font-style: italic;
            border-left: 4px solid var(--accent-color);
            padding-left: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .ceo-section .tags {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .ceo-section .tag {
            background-color: #f3e8ff;
            color: var(--accent-color);
            padding: 0.5rem 1.2rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }



        .content-section .content {
            background-color: var(--card-background);
            border-radius: 8px;
            padding: 3rem;
            border: 1px solid rgba(143, 77, 167, 0.1);
        }

        .content-section h2 {
            font-size: 2.2rem;
            margin-bottom: 1.5rem;
        }

        .content-section p {
            font-size: 1.05rem;
            margin-bottom: 1.5rem;
        }

        .content-section .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-top: 2.5rem;
        }

        @media (min-width: 768px) {
            .content-section .grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .content-section .card {
            background-color: var(--card-background);
            padding: 2rem;
            border-radius: 8px;
            border: 1px solid rgba(143, 77, 167, 0.1);
            transition: transform 0.3s ease;
        }

        .content-section .card:hover {
            transform: translateY(-5px);
        }

        .content-section .card h3 {
            font-size: 1.4rem;
            margin-bottom: 1rem;
        }

        .content-section .icon {
            width: 3rem;
            height: 3rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            background-color: #f3e8ff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Values Section */
        .values-section {
            text-align: center;
        }

        .values-section h2 {
            font-size: 2.2rem;
            margin-bottom: 1rem;
        }

        .values-section p {
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto 3rem;
        }

        .values-section .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        @media (min-width: 768px) {
            .values-section .grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .values-section .card {
            background-color: var(--card-background);
            padding: 2.5rem 2rem;
            border-radius: 8px;
            border: 1px solid rgba(143, 77, 167, 0.1);
            transition: transform 0.3s ease;
        }

        .values-section .card:hover {
            transform: translateY(-5px);
        }

        .values-section .card h3 {
            font-size: 1.3rem;
            margin-bottom: 1rem;
        }

        .values-section .icon {
            width: 3.5rem;
            height: 3.5rem;
            margin: 0 auto 1.5rem;
            border-radius: 8px;
            background-color: #f3e8ff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* CTA Section */
        .cta-section {
            background-color: var(--accent-color);
            color: #fff;
            padding: 5rem 1rem;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 2.2rem;
            margin-bottom: 1.5rem;
        }

        .cta-section p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-section a {
            background-color: #fff;
            color: var(--accent-color);
            padding: 0.9rem 2.2rem;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .cta-section a:hover {
            background-color: #f3e8ff;
        }

        @media (min-width: 768px) {
            .hero-section {
                padding: 7rem 1rem;
            }

            .hero-section h1 {
                font-size: 3.5rem;
            }

            .ceo-section,
            .content-section,
            .values-section {
                padding: 6rem 0;
            }

            .ceo-section h2,
            .content-section h2,
            .values-section h2,
            .cta-section h2 {
                font-size: 2.8rem;
            }
        }
    </style>
    </head>

    <body>
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <h1 class="heading-font">About <span class="text-accent">TEXTIO</span></h1>
                <p>We believe fashion is more than just clothing - it's a way to express yourself.</p>
            </div>
        </section>

        <!-- CEO Section -->
        <section class="ceo-section">
            <div class="container">
                <div class="content">
                    <h2 class="heading-font">Meet Our Visionary Leader</h2>
                    <h3 class="text-accent">Priyanshu Bhattacharya</h3>
                    <p class="quote">
                        "Fashion is the armor to survive the reality of everyday life. At TEXTIO, we don't just create
                        clothes; we craft confidence, personality, and self-expression."
                    </p>
                    <p>
                        Under Priyanshu's visionary leadership, TEXTIO has transformed from a custom t-shirt printing
                        startup into a complete fashion brand that celebrates individuality. His passion for accessible
                        fashion and belief in clothing as a form of self-expression drives our company's innovation and
                        commitment to quality.
                    </p>
                    <div class="tags">
                        <span class="tag">Visionary</span>
                        <span class="tag">Innovator</span>
                        <span class="tag">Leader</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Content Section -->
        <section class="content-section">
            <div class="container">
                <div class="content">
                    <div>
                        <h2 class="text-accent heading-font">Our Story</h2>
                        <p>
                            At <span class="text-accent">TEXTIO</span>, we believe fashion is more than just clothing -
                            it's a way to express yourself. In an era where uniqueness is paramount, we create apparel
                            that helps you showcase your style and feel confident every day.
                        </p>
                        <p>
                            We began our journey in 2024 to revolutionize the custom t-shirt printing industry, and it
                            has now evolved into a complete fashion brand. Today, we proudly offer a wide range of
                            clothing, including t-shirts, sweatshirts, and hoodies—all designed with quality, comfort,
                            and style.
                        </p>
                    </div>
                    <div class="grid">
                        <div class="card">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 class="heading-font">Our Mission</h3>
                            <p>To make stylish, customizable, and high-quality fashion accessible to everyone.</p>
                        </div>
                        <div class="card">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h3 class="heading-font">Our Vision</h3>
                            <p>To keep fashion personal, affordable, and sustainable so everyone can express themselves
                                through clothing they love to wear.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Values Section -->
        <section class="values-section">
            <div class="container">
                <h2 class="heading-font">Our Values</h2>
                <p>The principles that guide everything we do at Textio</p>
                <div class="grid">
                    <div class="card">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-accent" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="heading-font">Self-Expression</h3>
                        <p>We believe clothing is a powerful medium for expressing individuality and personality.</p>
                    </div>
                    <div class="card">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-accent" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <h3 class="heading-font">Quality</h3>
                        <p>We never compromise on the quality of our materials, craftsmanship, or designs.</p>
                    </div>
                    <div class="card">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-accent" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="heading-font">Community</h3>
                        <p>We foster a community where people can share their unique styles and inspire others.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="cta-section">
            <div class="container">
                <h2 class="heading-font">Join the Textio Community</h2>
                <p>Be part of a fashion movement that celebrates individuality and self-expression</p>
                <a href="/products">Explore Our Collections</a>
            </div>
        </section>



</div>
