<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CodeSprint – Coding Practice & Quiz Platform</title>


  <link rel="stylesheet" href="styles.css">
</head>
<body>
<div id="landing" class="page active">
  <!-- Removed glow and grid elements -->

  <nav class="main-nav">
    <div class="logo">Code<span>Sprint</span></div>
    <div class="nav-links">
      <a href="about.php">About</a>
      <a href="login.php">Sign In</a>
      <a class="btn-nav" href="register.php">Get Started →</a>
    </div>
  </nav>

  <section class="hero">
    <div class="hero-badge">
      <div class="badge-dot"></div>
      Practice · Assess · Improve
    </div>
    <h1>Code Smarter,<br><span style="color:var(--accent)">Sprint Further</span></h1>
    <p>The all-in-one platform for coding practice, timed quiz assessments, and performance analytics — built for students who take learning seriously.</p>
    <div class="hero-cta">
      <a href="register.php" style="text-decoration:none"><button class="btn btn-primary btn-lg">Start for Free</button></a>
      <a href="about.php" style="text-decoration:none"><button class="btn btn-outline btn-lg">Learn More</button></a>
    </div>
  </section>

  <div class="stats-row">
    <div class="stat">
      <div class="stat-num">2.4K+</div>
      <div class="stat-label">Active Users</div>
    </div>
    <div class="stat">
      <div class="stat-num">4,128</div>
      <div class="stat-label">Quiz Attempts</div>
    </div>
    <div class="stat">
      <div class="stat-num">94%</div>
      <div class="stat-label">Improvement Rate</div>
    </div>
    <div class="stat">
      <div class="stat-num">50+</div>
      <div class="stat-label">Study Groups</div>
      <div class="stat-note">learn together • grow faster</div>
    </div>
  </div>

  <section class="features-section">
    <div class="section-label-sm">// platform_features</div>
    <div class="section-title-lg">Everything You Need to <span style="color:var(--accent)">Level Up</span></div>
    <div class="section-sub">From daily quizzes to group study sessions — CodeSprint brings your entire learning stack into one cohesive experience.</div>
    <div class="features-grid">
      <div class="feature-card fc-green" onclick="loginAs('student')">
        <div class="feature-icon">👨‍💻</div>
        <div class="feature-title">Student Dashboard</div>
        <div class="feature-desc">Track your coding journey, attempt timed quizzes, join study groups, and visualize your growth with detailed analytics.</div>
        <div style="margin-top:20px"><button class="btn btn-primary btn-sm">Enter as Student →</button></div>
      </div>
      <div class="feature-card fc-purple" onclick="loginAs('admin1')">
        <div class="feature-icon">📝</div>
        <div class="feature-title">Quiz Manager</div>
        <div class="feature-desc">Create and manage daily MCQ quizzes, define time limits, set scoring rules, and keep content fresh and challenging.</div>
        <div style="margin-top:20px"><button class="btn btn-purple btn-sm">Enter as Admin 1 →</button></div>
      </div>
      <div class="feature-card fc-amber" onclick="loginAs('admin2')">
        <div class="feature-icon">📊</div>
        <div class="feature-title">Analytics Manager</div>
        <div class="feature-desc">Monitor student performance, generate leaderboards, analyze topic-wise trends, and send personalized feedback.</div>
        <div style="margin-top:20px"><button class="btn btn-amber btn-sm">Enter as Admin 2 →</button></div>
      </div>
      <div class="feature-card fc-blue">
        <div class="feature-icon">⚡</div>
        <div class="feature-title">Timed Assessments</div>
        <div class="feature-desc">Daily MCQ quizzes with real-time countdowns. Every second counts when you're training for competitive exams.</div>
      </div>
      <div class="feature-card fc-green">
        <div class="feature-icon">👥</div>
        <div class="feature-title">50+ Study Groups</div>
        <div class="feature-desc">Join or create groups with peers, share daily progress, compete on group leaderboards, and build habits together.</div>
      </div>
      <div class="feature-card fc-purple">
        <div class="feature-icon">📈</div>
        <div class="feature-title">Real Analytics</div>
        <div class="feature-desc">Topic-wise performance breakdowns, streak tracking, and personalized feedback from your analytics manager.</div>
      </div>
    </div>
  </section>

  <footer>
    <div class="footer-logo">Code<span>Sprint</span></div>
    <div class="footer-links">
      <a href="index.php">Home</a>
      <a href="about.php">About</a>
      <a href="login.php">Sign In</a>
    </div>
    <div class="footer-copy">© 2026 CodeSprint. All rights reserved.</div>
  </footer>
</div>




<!-- TOAST -->
<div class="toast-container" id="toast-container"></div>

<script src="main.js"></script>
</body>
</html>