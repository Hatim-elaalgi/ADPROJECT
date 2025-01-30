<html>
<head>
    <title>Home page</title>
    <link rel="stylesheet" href="{{asset('css/welcomeCSS/welcomeStyle.css')}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</head>
<body>
    <div class="banner">
        <div class="navbar">
            <img src="{{asset('images/welcomeIMG/logo.png')}}" class="logo">
            <ul>
                <li><a href="#">Data</a></li>
                <li><a href="#">Programming</a></li>
                <li><a href="#">Artificial Intelligence</a></li>
                <li><a href="#">Cyber security</a></li>

            </ul>
       <a href="{{route('Auth')}}"><button type="button" class=""><span></span>LOG IN OR SIGN UP</button></a> 
        </div>
        <div class="content">
            <h1>Dare To Be More</h1>
            <p>Your future,multiplied.Discover the power of Tech Horizons to unleash your career potentail.</p>
        </div>
    </div>


    <div class="section">
    <div class="section-text">
        <h2>Why Tech Horizons?</h2>
        <p>
            Welcome to Tech Horizons, your ultimate destination for exploring the dynamic world of technology! Discover the latest advancements in artificial intelligence, the Internet of Things, cybersecurity, and more. Our platform delivers insightful articles, expert analyses, and tailored content to match your interests and browsing habits.
        </p>
        <p>Whether you’re a tech enthusiast or a curious learner, Tech Horizons offers a seamless way to uncover trends, expand your knowledge, and stay ahead in today’s fast-paced digital landscape. Our platform empowers you to explore innovative ideas, embrace cutting-edge technologies, and connect with a vibrant community of like-minded individuals. Join us to explore, learn, and connect—together, let’s lead the way in innovation!</p>
    </div>
    <div class="section-image">
        <img src="images/welcomeIMG/btwn.webp" alt="Tech Horizons">
    </div>
</div>


<div class="themes-section">
    <div class="slider">
        <button class="prev" onclick="prevSlide()">&#8249;</button>
        <div class="slide" id="data">
            <h3>Data</h3>
            <p>In a world where data drives innovation, its role has never been more critical. At Tech Horizons, discover how data is transforming industries, optimizing decision-making, and shaping the future. Dive into topics like big data analytics, advanced visualization techniques, and responsible data management to unlock its limitless potential. Learn to navigate this information-rich landscape and harness the power of data to propel your projects and ideas forward.</p>
        </div>
        <div class="slide" id="programming">
            <h3>Programming</h3>
            <p>Programming is the engine behind modern technology, transforming ideas into functional solutions. At Tech Horizons, explore languages, frameworks, and tools that power everything from apps to large-scale systems. Whether you're a beginner or experienced developer, find tutorials and tips to enhance your skills.

                Programming is also about creativity and problem-solving. Learn to tackle challenges, optimize code, and stay updated on trends like AI-driven development. With Tech Horizons, unlock your potential and bring your projects to life.</p>
        </div>
        <div class="slide" id="artificial-intelligence">
            <h3>Artificial Intelligence</h3>
            <p>Artificial Intelligence (AI) is revolutionizing industries and reshaping the way we interact with technology. At Tech Horizons, explore key areas like machine learning, natural language processing, and robotics, along with their applications in healthcare, finance, and transportation. From automating tasks to enabling personalized experiences, AI is driving innovation and efficiency across the globe.

                AI also presents challenges, including ethical concerns like bias, transparency, and accountability. At Tech Horizons, we tackle these issues while providing insights to help developers, business leaders, and curious learners responsibly harness AI’s transformative potential. Stay ahead in this AI-powered</p>

        </div>
        <div class="slide" id="cyber-security">
            <h3>Cyber Security</h3>
            <p>Cybersecurity is the cornerstone of trust in our increasingly digital world, safeguarding data, systems, and networks from ever-evolving threats. At Tech Horizons, delve into how cybersecurity protects critical infrastructures, ensures privacy, and enables secure innovation. Discover essential topics like threat detection, ethical hacking, data encryption, and risk management to stay one step ahead of cybercriminals. Learn about emerging trends, such as AI-powered security tools and zero-trust architectures, to understand how the digital landscape is defended. Whether you're a beginner or a seasoned professional, explore ways to secure the digital future with confidence and resilience.</p>
        </div>
        <button class="next" onclick="nextSlide()">&#8250;</button>
    </div>
</div>

<div class="final">
    <div class="final-content">
        <h1>Tech Horizons: Empowering Innovation, Unlocking Opportunities</h1>
        <p>
            At Tech Horizons, we are committed to shaping the future by empowering individuals with knowledge and insights into cutting-edge technologies. 
            Our platform not only keeps users informed about the latest advancements but also fosters innovation by connecting enthusiasts, professionals, 
            and thought leaders. By highlighting emerging trends and providing in-depth analysis, Tech Horizons inspires curiosity, fuels creativity,
            and contributes to building a more tech-savvy and connected world. Together, we are driving progress and unlocking the potential of tomorrow’s 
            innovations.


        </p>
    </div>

    <div class="statistics">
        <div class="stat-card">
            <h2>205k+</h2>
            <p>Tech Horizons LEARNERS SINCE 2021</p>
        </div>
        <div class="stat-card">
            <h2>116,833</h2>
            <p>ARTICLE PUBLISHED</p>
        </div>
        <div class="stat-card">
            <h2>12,326</h2>
            <p>YOUNG ENTREPRENEURS SUPPORTED</p>
        </div>
        <div class="stat-card">
            <h2>65,542</h2>
            <p>FOUND WORK OPPORTUNITIES</p>
        </div>
    </div>
</div>


<footer>
    <div class="footer">
        <div class="footer-content">
            <div class="footer-column">
                <h3>Contact</h3>
                <ul>
                    <li>Email: <a href="mailto:contact@techhorizon.com">contact@techhorizon.com</a></li>
                    <li>Téléphone: <a href="tel:+1234567890">+123 456 7890</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Liens Utiles</h3>
                <ul>
                    <li><a href="#">Accueil</a></li>
                    <li><a href="#">À propos</a></li>
                    <li><a href="#">Politique de confidentialité</a></li>
                    <li><a href="#">Conditions d'utilisation</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Suivez-nous</h3>
                <div class="footer-social">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Tech Horizon. Tous droits réservés.</p>
        </div>
    </div>
</footer>

    
<script src="{{asset('js/welcome/script.js')}}"></script>
</body>
</html>