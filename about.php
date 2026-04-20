<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CodeSprint – Coding Practice & Quiz Platform</title>


  <link rel="stylesheet" href="styles.css">
</head>
<body>
<div id="about" class="page active">
  <nav class="main-nav">
    <a class="logo" href="index.php" style="cursor:pointer; text-decoration: none;">Code<span>Sprint</span></a>
    <div class="nav-links">
      <a href="index.php">Home</a>
      <a class="active-link">About</a>
      <a href="login.php">Sign In</a>
      <a class="btn-nav" href="register.php">Get Started →</a>
    </div>
  </nav>

  <section class="about-hero">
    <div class="grid-bg"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="deco-line deco-line-1"></div>
    <div class="deco-line deco-line-2"></div>
    <div class="hero-content">
      <div class="eyebrow"><div class="eyebrow-line"></div>About CodeSprint<div class="eyebrow-line"></div></div>
      <h1 class="hero-h1">Built for the<br><em>Next-Gen</em>Coder</h1>
      <p class="hero-desc">CodeSprint is a smart platform built for consistent practice and real progress. Attempt daily quizzes, track your performance, and compete on leaderboards while learning alongside peers — all in one place.</p>
      <div class="hero-chips">
        <span class="chip chip-green">✓ Structured Practice</span>
        <span class="chip chip-purple">⚡ Timed Quizzes</span>
        <span class="chip chip-amber">📊 Analytics</span>
        <span class="chip chip-blue">👥 Study Groups</span>
      </div>
    </div>
    <div class="hero-card">
      <div class="hc-label">// platform_metrics.live</div>
      <div class="hc-stat"><span class="hc-stat-label">Students Enrolled</span><span class="hc-stat-val val-green">2,419</span></div>
      <div class="hc-stat"><span class="hc-stat-label">Quiz Attempts</span><span class="hc-stat-val val-purple">4,128</span></div>
      <div class="hc-stat"><span class="hc-stat-label">Quizzes Created</span><span class="hc-stat-val val-amber">840</span></div>
      <div class="hc-stat"><span class="hc-stat-label">Avg. Score Improvement</span><span class="hc-stat-val val-blue">+34%</span></div>
      <div class="hc-stat"><span class="hc-stat-label">Active Study Groups</span><span class="hc-stat-val val-green">50+</span></div>
    </div>
  </section>

  <div class="marquee-wrap">
    <div class="marquee-track">
      <div class="marquee-item"><div class="marquee-dot"></div>STRUCTURED PRACTICE</div>
      <div class="marquee-item"><div class="marquee-dot"></div>TIMED ASSESSMENTS</div>
      <div class="marquee-item"><div class="marquee-dot"></div>PERFORMANCE ANALYTICS</div>
      <div class="marquee-item"><div class="marquee-dot"></div>LEADERBOARDS</div>
      <div class="marquee-item"><div class="marquee-dot"></div>STUDY GROUPS</div>
      <div class="marquee-item"><div class="marquee-dot"></div>QUIZ MANAGEMENT</div>
      <div class="marquee-item"><div class="marquee-dot"></div>TOPIC TRACKING</div>
      <div class="marquee-item"><div class="marquee-dot"></div>STREAK SYSTEM</div>
      <div class="marquee-item"><div class="marquee-dot"></div>STRUCTURED PRACTICE</div>
      <div class="marquee-item"><div class="marquee-dot"></div>TIMED ASSESSMENTS</div>
      <div class="marquee-item"><div class="marquee-dot"></div>PERFORMANCE ANALYTICS</div>
      <div class="marquee-item"><div class="marquee-dot"></div>LEADERBOARDS</div>
      <div class="marquee-item"><div class="marquee-dot"></div>STUDY GROUPS</div>
      <div class="marquee-item"><div class="marquee-dot"></div>QUIZ MANAGEMENT</div>
      <div class="marquee-item"><div class="marquee-dot"></div>TOPIC TRACKING</div>
      <div class="marquee-item"><div class="marquee-dot"></div>STREAK SYSTEM</div>
    </div>
  </div>

  <section class="section">
    <div class="section-inner">
      <div class="mission-split reveal">
        <div class="mission-left">
          <div class="section-tag">// our_mission</div>
          <h2 class="section-heading">Why We<br>Built This</h2>
          <div class="mission-body">
            <p>In today's competitive academic environment, students are overwhelmed with disconnected tools — one platform for problem-solving, another for tests, and spreadsheets for tracking. <strong>None of them talk to each other.</strong></p>
            <p>CodeSprint was designed from the ground up to eliminate that fragmentation. We believe that <strong>consistent, measurable practice</strong> is the single biggest predictor of improvement.</p>
            <p>From the moment a student logs in, everything they need is in one place: their coding history, today's quiz, their ranking, their weaknesses, and their growth over time.</p>
          </div>
        </div>
        <div class="mission-right" style="position:relative;padding:24px">
          <div class="mission-visual">
            <div class="mv-top">
              <div class="dot dot-r"></div><div class="dot dot-y"></div><div class="dot dot-g"></div>
              <div class="mv-title">codesprint/core/philosophy.ts</div>
            </div>
            <div class="mv-body">
              <div class="code-line cl-comment">// The CodeSprint manifesto</div>
              <div class="code-line cl-comment">// v2.0.0 — production</div><br>
              <div class="code-line"><span class="cl-keyword">const</span> <span class="cl-val">mission</span> = {</div>
              <div class="code-line" style="padding-left:20px"><span class="cl-string">"focus"</span>: <span class="cl-val">"student_growth"</span>,</div>
              <div class="code-line" style="padding-left:20px"><span class="cl-string">"method"</span>: <span class="cl-val">"structured_practice"</span>,</div>
              <div class="code-line" style="padding-left:20px"><span class="cl-string">"measure"</span>: <span class="cl-val">"real_analytics"</span>,</div>
              <div class="code-line" style="padding-left:20px"><span class="cl-string">"goal"</span>: <span class="cl-fn">eliminateFragmentation</span>(),</div>
              <div class="code-line">}</div><br>
              <div class="code-line"><span class="cl-keyword">export default</span> <span class="cl-fn">mission</span>;</div>
            </div>
          </div>
          <div class="floating-badge fb-1"><span class="fb-icon">🔥</span><div><div class="fb-val">+34%</div><div class="fb-label">Avg improvement</div></div></div>
          <div class="floating-badge fb-2"><span class="fb-icon">✅</span><div><div class="fb-val">840+</div><div class="fb-label">Quizzes Created</div></div></div>
        </div>
      </div>
    </div>
  </section>

  <div class="stats-band reveal">
    <div class="stats-grid">
      <div class="stat-item"><div class="stat-number" style="color:var(--accent)">2.4K</div><div class="stat-label-main">Active Students</div><div class="stat-sub">and growing daily</div></div>
      <div class="stat-item"><div class="stat-number" style="color:#a78bfa">840+</div><div class="stat-label-main">Quizzes Created</div><div class="stat-sub">across all topics</div></div>
      <div class="stat-item"><div class="stat-number" style="color:var(--accent3)">4,128</div><div class="stat-label-main">Quiz Attempts</div><div class="stat-sub">tracked & analysed</div></div>
      <div class="stat-item"><div class="stat-number" style="color:var(--info)">94%</div><div class="stat-label-main">Improvement Rate</div><div class="stat-sub">within 30 days</div></div>
      <div class="stat-item"><div class="stat-number" style="color:var(--accent)">50+</div><div class="stat-label-main">Study Groups</div><div class="stat-sub">learn together • grow faster</div></div>
    </div>
  </div>

  <section class="section values-section">
    <div class="section-inner">
      <div class="reveal">
        <div class="section-tag">// our_values</div>
        <h2 class="section-heading">Principles That<br>Drive Us</h2>
      </div>
      <div class="values-grid reveal">
        <div class="value-card vc-green"><span class="value-num">01</span><span class="value-icon">🎯</span><div class="value-title">Consistency Over Brilliance</div><div class="value-desc">Daily, structured practice outperforms sporadic bursts of genius. The platform is designed to build habits, not just sessions.</div></div>
        <div class="value-card vc-purple"><span class="value-num">02</span><span class="value-icon">📊</span><div class="value-title">Data-Driven Growth</div><div class="value-desc">Every action a student takes generates insight. We surface that insight clearly — front and center where it shapes decisions.</div></div>
        <div class="value-card vc-amber"><span class="value-num">03</span><span class="value-icon">👥</span><div class="value-title">Learning is Social</div><div class="value-desc">Study groups, rankings, and shared goals make the journey less solitary. Peer competition — when healthy — accelerates growth.</div></div>
      </div>
    </div>
  </section>

  <section class="quote-section">
    <div class="quote-bg-text">"</div>
    <div class="big-quote reveal">"The best coders aren't born — they're <span class="hl">forged through relentless repetition</span> and <span class="hl2">honest measurement</span> of where they stand."</div>
    <div class="quote-attr reveal reveal-delay-2">— CodeSprint Design Ethos</div>
  </section>

  <div class="divider"></div>

  <section class="cta-section">
    <div class="orb-cta"></div>
    <div class="cta-eyebrow reveal">// start_your_journey</div>
    <h2 class="cta-h2 reveal reveal-delay-1">Ready to<br><span style="color:var(--accent)">Sprint?</span></h2>
    <p class="cta-sub reveal reveal-delay-2">Join thousands of students who are already building better coding habits, one sprint at a time.</p>
    <div class="cta-buttons">
      <a href="register.php" style="text-decoration:none"><button class="btn-cta-primary">Create Free Account →</button></a>
      <a href="index.php" style="text-decoration:none"><button class="btn-cta-outline">← Back to Home</button></a>
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