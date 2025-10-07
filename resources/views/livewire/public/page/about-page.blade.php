<div>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400&display=swap');

        :root {
            --primary-color: #8f4da7;
            --text-color: #171717;
            --background-color: #faf9fc;
            --card-background: #ffffff;
            --border-color: rgba(143, 77, 167, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.7;
            font-weight: 400;
            overflow-x: hidden;
        }

        .container {
          
            margin: 0 auto;
            padding: 0 1.2rem;
        }

        .text-primary {
            color: var(--primary-color);
        }

        .bg-primary {
            background-color: var(--primary-color);
        }

        /* Hero Section */
        .hero-section {
            padding: 4rem 1rem;
            text-align: center;
            background-color: #f8f0ff;
        }

        .hero-section h1 {
            font-size: 2.6rem;
            margin-bottom: 1rem;
            font-weight: 400;
        }

        .hero-section p {
            font-size: 1rem;
            max-width: 600px;
            margin: 0 auto;
            font-weight: 400;
        }

        /* CEO Section */
        /* .ceo-section {
            padding: 3rem 0;
        } */

        .ceo-content {
            background-color: var(--card-background);
            border-radius: 8px;
            padding: 1.8rem;
            border: 1px solid var(--border-color);
        }

        .ceo-content h2 {
            font-size: 1.8rem;
            margin-bottom: 0.8rem;
            font-weight: 400;
        }

        .ceo-content h3 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            font-weight: 400;
        }

        .ceo-content .quote {
            font-style: italic;
            padding: 1rem 1.2rem;
            background-color: #f8f0ff;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-weight: 400;
        }

        .ceo-tags {
            display: flex;
            gap: 0.7rem;
            flex-wrap: wrap;
            margin-top: 1.2rem;
        }

        .ceo-tag {
            background-color: #f8f0ff;
            color: var(--primary-color);
            padding: 0.3rem 0.9rem;
            border-radius: 16px;
            font-size: 0.8rem;
            font-weight: 400;
        }

        /* Story Section */
        .story-section {
            padding: 3rem 0;
        }

        .story-content {
            background-color: var(--card-background);
            border-radius: 8px;
            padding: 1.8rem;
            border: 1px solid var(--border-color);
        }

        .story-content h2 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            font-weight: 400;
        }

        .story-content p {
            font-size: 0.9rem;
            margin-bottom: 1rem;
            font-weight: 400;
        }

        .mission-vision {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.2rem;
            margin-top: 1.2rem;
        }

        .mission-card,
        .vision-card {
            padding: 1.2rem;
            border-radius: 6px;
            border-left: 2px solid var(--primary-color);
        }

        .mission-card h3,
        .vision-card h3 {
            font-size: 1.1rem;
            margin-bottom: 0.7rem;
            font-weight: 400;
        }

        .mission-icon,
        .vision-icon {
            width: 1.8rem;
            height: 1.8rem;
            background-color: #f8f0ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.7rem;
        }

        /* Values Section */
        .values-section {
            padding: 3rem 0;
            text-align: center;
        }

        .values-section h2 {
            font-size: 1.8rem;
            margin-bottom: 0.8rem;
            font-weight: 400;
        }

        .values-section>p {
            font-size: 0.9rem;
            max-width: 500px;
            margin: 0 auto 1.5rem;
            font-weight: 400;
        }

        .values-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.2rem;
        }

        .value-card {
            background-color: var(--card-background);
            padding: 1.5rem;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            transition: transform 0.2s ease;
        }

        .value-card:hover {
            transform: translateY(-4px);
        }

        .value-icon {
            width: 2rem;
            height: 2rem;
            margin: 0 auto 0.8rem;
            border-radius: 50%;
            background-color: #f8f0ff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .value-card h3 {
            font-size: 1rem;
            margin-bottom: 0.7rem;
            font-weight: 400;
        }

        /* CTA Section */
        .cta-section {
            background-color: var(--primary-color);
            color: #fff;
            padding: 3rem 1rem;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 1.8rem;
            margin-bottom: 0.8rem;
            font-weight: 400;
        }

        .cta-section p {
            font-size: 0.9rem;
            max-width: 500px;
            margin: 0 auto 1.2rem;
            font-weight: 400;
        }

        .cta-button {
            background-color: #fff;
            color: var(--primary-color);
            padding: 0.7rem 1.8rem;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 400;
            display: inline-block;
            transition: background-color 0.2s ease;
        }

        .cta-button:hover {
            background-color: #f8f0ff;
        }

        /* Responsive Design */
        @media (min-width: 768px) {
            .hero-section {
                padding: 5rem 1rem;
            }

            .hero-section h1 {
                font-size: 3rem;
            }

            .ceo-section,
            .story-section,
            .values-section {
                padding: 2rem 0;
            }

            .mission-vision {
                grid-template-columns: 1fr 1fr;
            }

            .values-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .ceo-content h2,
            .story-content h2,
            .values-section h2,
            .cta-section h2 {
                font-size: 2.2rem;
            }
        }

        @media (min-width: 1024px) {
            .hero-section h1 {
                font-size: 3.4rem;
            }

            .container {
                padding: 0 1.5rem;
            }
        }
    </style>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>About <span class="text-primary">TEXTIO</span></h1>
            <p>We believe fashion is more than just clothing - it's a way to express yourself.</p>
        </div>
    </section>

    <!-- CEO Section -->
    <section class="ceo-section">
        <div class="container">
            <div class="ceo-content">
                <h2>Meet Our Visionary Leader</h2>
                <h3 class="text-primary">Textio</h3>
                <p class="quote">
                    "Fashion is the armor to survive the reality of everyday life. At TEXTIO, we don't just create
                    clothes; we craft confidence, personality, and self-expression."
                </p>
                <p>
                    Under Textio's visionary leadership, TEXTIO has transformed from a custom t-shirt printing
                    startup into a complete fashion brand that celebrates individuality. His passion for accessible
                    fashion and belief in clothing as a form of self-expression drives our company's innovation and
                    commitment to quality.
                </p>
                <div class="ceo-tags">
                    <span class="ceo-tag">Visionary</span>
                    <span class="ceo-tag">Innovator</span>
                    <span class="ceo-tag">Leader</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Story Section -->
    <section class="story-section">
        <div class="container">
            <div class="story-content">
                <h2 class="text-primary">Our Story</h2>
                <p>
                    At <span class="text-primary">TEXTIO</span>, we believe fashion is more than just clothing -
                    it's a way to express yourself. In an era where uniqueness is paramount, we create apparel
                    that helps you showcase your style and feel confident every day.
                </p>
                <p>
                    We began our journey in 2024 to revolutionize the custom t-shirt printing industry, and it
                    has now evolved into a complete fashion brand. Today, we proudly offer a wide range of
                    clothing, including t-shirts, sweatshirts, and hoodies—all designed with quality, comfort,
                    and style.
                </p>
                <div class="mission-vision">
                    <div class="mission-card">
                        <div class="mission-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3>Our Mission</h3>
                        <p>To make stylish, customizable, and high-quality fashion accessible to everyone.</p>
                    </div>
                    <div class="vision-card">
                        <div class="vision-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3>Our Vision</h3>
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
            <h2>Our Values</h2>
            <p>The principles that guide everything we do at Textio</p>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3>Self-Expression</h3>
                    <p>We believe clothing is a powerful medium for expressing individuality and personality.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                    <h3>Quality</h3>
                    <p>We never compromise on the quality of our materials, craftsmanship, or designs.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3>Community</h3>
                    <p>We foster a community where people can share their unique styles and inspire others.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="container">
            <h2>Join the Textio Community</h2>
            <p>Be part of a fashion movement that celebrates individuality and self-expression</p>
            <a href="/products" class="cta-button">Explore Our Collections</a>
        </div>
    </section>

</div>
