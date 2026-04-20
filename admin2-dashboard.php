<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

// Fetch recent activity
$recent_activity_query = "
SELECT u.name AS student_name, q.title AS quiz_title, qa.score, qa.attempted_at
FROM quiz_attempts qa
JOIN users u ON qa.user_id = u.user_id
JOIN quizes q ON qa.quiz_id = q.quiz_id
ORDER BY qa.attempted_at DESC
LIMIT 5";
$recent_stmt = $pdo->query($recent_activity_query);

// Fetch students data
$students_query = "
SELECT 
    u.user_id,
    u.name,
    COUNT(DISTINCT qa.attempt_id) AS quizzes_attempted,
    COUNT(DISTINCT p.Problem_id) AS problems_uploaded,
    IFNULL(SUM(qa.score), 0) AS total_points
FROM users u
LEFT JOIN quiz_attempts qa ON u.user_id = qa.user_id
LEFT JOIN problems p ON u.user_id = p.User_id
WHERE u.role = 'student'
GROUP BY u.user_id
ORDER BY total_points DESC";
$students_stmt = $pdo->query($students_query);
$students = $students_stmt->fetchAll();

// Fetch total students
$total_students = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();

// Fetch total quiz attempts
$total_attempts = $pdo->query("SELECT COUNT(*) FROM quiz_attempts")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CodeSprint – Coding Practice & Quiz Platform</title>


  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<div id="admin2-dashboard" class="page active">
  <div class="dashboard">
    <aside class="sidebar">
      <div class="sidebar-header"><div class="logo sidebar-logo">Code<span>Sprint</span></div></div>
      <div class="sidebar-user">
        <div class="avatar avatar-amber"><?= htmlspecialchars(strtoupper(substr($_SESSION['name'] ?? 'A', 0, 1))) ?></div>
        <div class="user-info"><div class="user-name"><?php echo $_SESSION['name']; ?></div><div class="user-role">// <?php echo $_SESSION['role']; ?></div></div>
      </div>
      <nav class="sidebar-nav">
        <div class="nav-section-label">ANALYTICS</div>
        <div class="nav-item active-amber" onclick="showAdmin2View('a2-overview',this)"><span class="nav-icon">⬡</span> Overview</div>
        <div class="nav-item" onclick="showAdmin2View('a2-students',this)"><span class="nav-icon">👥</span> Students</div>
        <div class="nav-item" onclick="showAdmin2View('a2-performance',this)"><span class="nav-icon">📊</span> Performance</div>
        <div class="nav-section-label">REPORTS</div>
        <div class="nav-item" onclick="showAdmin2View('a2-reports',this)"><span class="nav-icon">📑</span> Reports</div>
      </nav>
      <div class="sidebar-footer"><a class="nav-item" href="logout.php" style="text-decoration:none; color:inherit;"><span class="nav-icon">←</span> Sign Out</a></div>
    </aside>
    <main class="main-content">
      <div class="top-bar">
        <div class="top-bar-left">
          <div class="page-title">Analytics Center</div>
          <div class="page-subtitle">Monitor performance and generate insights</div>
        </div>
        <div class="top-bar-actions">
          <button class="btn btn-outline btn-sm" onclick="openModal('feedback-modal')">📩 Send Feedback</button>
          <button class="btn btn-amber" onclick="showToast('Report exported!','success')">Export Report ↓</button>
        </div>
      </div>
      <div class="content-area">

        <!-- A2 OVERVIEW -->
        <div class="view active" id="view-a2-overview">
          <div class="metrics-grid" style="margin-bottom:28px">
            <div class="metric-card metric-amber"><div class="metric-icon">👥</div><div class="metric-val"><?= number_format($total_students) ?></div><div class="metric-label">Total Students</div></div>
            <div class="metric-card metric-green"><div class="metric-icon">⚡</div><div class="metric-val"><?= number_format($total_attempts) ?></div><div class="metric-label">Quiz Attempts</div></div>
            <div class="metric-card metric-purple"><div class="metric-icon">📊</div><div class="metric-val">72%</div><div class="metric-label">Platform Avg Score</div></div>
            <div class="metric-card metric-info"><div class="metric-icon">🔥</div><div class="metric-val">89%</div><div class="metric-label">Active Rate</div></div>
          </div>
          <div class="grid-2" style="margin-bottom:24px">
            <div class="card">
              <div class="card-title">Quiz Attempts This Week</div>
              <div style="display:flex;gap:4px;align-items:flex-end;height:100px;margin-bottom:8px">
                <div style="flex:1;background:rgba(245,158,11,.4);height:60%;border-radius:4px 4px 0 0"></div>
                <div style="flex:1;background:rgba(245,158,11,.5);height:75%;border-radius:4px 4px 0 0"></div>
                <div style="flex:1;background:rgba(245,158,11,.4);height:50%;border-radius:4px 4px 0 0"></div>
                <div style="flex:1;background:rgba(245,158,11,.6);height:85%;border-radius:4px 4px 0 0"></div>
                <div style="flex:1;background:rgba(245,158,11,.7);height:70%;border-radius:4px 4px 0 0"></div>
                <div style="flex:1;background:var(--accent3);height:100%;border-radius:4px 4px 0 0"></div>
                <div style="flex:1;background:rgba(245,158,11,.2);height:30%;border-radius:4px 4px 0 0"></div>
              </div>
              <div style="display:flex;gap:4px">
                <div style="flex:1;text-align:center;font-size:10px;color:var(--muted);font-family:'JetBrains Mono',monospace">Mon</div>
                <div style="flex:1;text-align:center;font-size:10px;color:var(--muted);font-family:'JetBrains Mono',monospace">Tue</div>
                <div style="flex:1;text-align:center;font-size:10px;color:var(--muted);font-family:'JetBrains Mono',monospace">Wed</div>
                <div style="flex:1;text-align:center;font-size:10px;color:var(--muted);font-family:'JetBrains Mono',monospace">Thu</div>
                <div style="flex:1;text-align:center;font-size:10px;color:var(--muted);font-family:'JetBrains Mono',monospace">Fri</div>
                <div style="flex:1;text-align:center;font-size:10px;color:var(--accent3);font-family:'JetBrains Mono',monospace">Sat</div>
                <div style="flex:1;text-align:center;font-size:10px;color:var(--muted);font-family:'JetBrains Mono',monospace">Sun</div>
              </div>
            </div>
            <div class="card">
              <div class="card-title">Score Distribution</div>
              <div style="display:flex;flex-direction:column;gap:10px">
                <div><div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px"><span>90-100%</span><span style="color:var(--accent)">18 students</span></div><div class="progress-bar"><div class="progress-fill fill-green" style="width:18%"></div></div></div>
                <div><div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px"><span>75-89%</span><span style="color:var(--accent)">67 students</span></div><div class="progress-bar"><div class="progress-fill fill-green" style="width:67%"></div></div></div>
                <div><div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px"><span>60-74%</span><span style="color:var(--accent3)">89 students</span></div><div class="progress-bar"><div class="progress-fill fill-amber" style="width:89%"></div></div></div>
                <div><div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px"><span>Below 60%</span><span style="color:var(--danger)">60 students</span></div><div class="progress-bar"><div class="progress-fill" style="width:60%;background:var(--danger)"></div></div></div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-title">Recent Activity</div>
            <div class="table-wrap"><table><thead><tr><th>Student</th><th>Quiz</th><th>Score</th><th>Time</th></tr></thead><tbody>
<?php while ($row = $recent_stmt->fetch()): ?>
  <tr>
    <td style="font-weight:600"><?= htmlspecialchars($row['student_name']) ?></td>
    <td><?= htmlspecialchars($row['quiz_title']) ?></td>
    <td><span style="color:var(--accent);font-weight:700;font-family:'Space Mono',monospace"><?= htmlspecialchars($row['score']) ?></span></td>
    <td style="color:var(--muted);font-family:'JetBrains Mono',monospace"><?= htmlspecialchars(date("d M, h:i A", strtotime($row['attempted_at']))) ?></td>
  </tr>
<?php endwhile; ?>
            </tbody></table></div>
          </div>
        </div>

        <!-- A2 STUDENTS -->
        <div class="view" id="view-a2-students">
          <div style="margin-bottom:24px"><h2 style="font-size:24px;font-weight:800">All Students</h2><p style="color:var(--muted);font-size:14px;margin-top:4px">Monitor performance and send feedback</p></div>
          <div class="card">
            <div class="table-wrap">
              <table>
                <thead><tr><th>Student</th><th>Quizzes</th><th>Problems</th><th>Streak</th><th>Points</th><th>Rank</th></tr></thead>
                <tbody>
<?php 
$rank = 1;
foreach ($students as $s): 
    $points = $s['total_points'] ?? 0;
?>
  <tr>
    <td style="font-weight:600"><?= htmlspecialchars($s['name']) ?></td>
    <td><?= htmlspecialchars($s['quizzes_attempted']) ?></td>
    <td><?= htmlspecialchars($s['problems_uploaded']) ?></td>
    <td><span style="color:var(--accent)">🔥 14d</span></td>
    <td><span style="color:var(--accent);font-weight:700"><?= htmlspecialchars($points) ?></span></td>
    <td><span class="badge badge-purple">#<?= $rank++ ?></span></td>
  </tr>
<?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- A2 PERFORMANCE -->
        <div class="view" id="view-a2-performance">
          <div style="margin-bottom:24px"><h2 style="font-size:24px;font-weight:800">Topic Performance Analysis</h2></div>
          <div class="grid-2" style="margin-bottom:24px">
            <div class="card">
              <div class="card-title">Average Score by Topic</div>
              <div style="display:flex;flex-direction:column;gap:14px">
                <div><div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px"><span>Sorting & Searching</span><span style="color:var(--accent)">88%</span></div><div class="progress-bar"><div class="progress-fill fill-green" style="width:88%"></div></div></div>
                <div><div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px"><span>Arrays & Strings</span><span style="color:var(--accent)">82%</span></div><div class="progress-bar"><div class="progress-fill fill-green" style="width:82%"></div></div></div>
                <div><div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px"><span>Graphs & Trees</span><span style="color:var(--accent3)">71%</span></div><div class="progress-bar"><div class="progress-fill fill-amber" style="width:71%"></div></div></div>
                <div><div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px"><span>Dynamic Programming</span><span style="color:var(--danger)">55%</span></div><div class="progress-bar"><div class="progress-fill" style="width:55%;background:var(--danger)"></div></div></div>
                <div><div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px"><span>System Design</span><span style="color:var(--danger)">49%</span></div><div class="progress-bar"><div class="progress-fill" style="width:49%;background:var(--danger)"></div></div></div>
              </div>
            </div>
            <div class="card">
              <div class="card-title">Quiz Performance Summary</div>
              <div class="table-wrap"><table><thead><tr><th>Quiz</th><th>Avg</th><th>Highest</th><th>Lowest</th></tr></thead><tbody id="a2-perf-table"></tbody></table></div>
            </div>
          </div>
        </div>

        <!-- A2 LEADERBOARD -->
        <div class="view" id="view-a2-leaderboard">
          <div style="margin-bottom:24px"><h2 style="font-size:24px;font-weight:800">Platform Leaderboard 🏆</h2></div>
          <div class="card" id="a2-leaderboard-list"></div>
        </div>

        <!-- A2 REPORTS -->
        <div class="view" id="view-a2-reports">
          <div style="margin-bottom:24px"><h2 style="font-size:24px;font-weight:800">Reports</h2></div>
          <div class="grid-3">
            <div class="card" style="cursor:pointer" onclick="showToast('Generating report...','info')"><div style="font-size:36px;margin-bottom:16px">📊</div><h3 style="font-size:16px;font-weight:700;margin-bottom:8px">Weekly Performance Report</h3><p style="font-size:13px;color:var(--muted)">Comprehensive analysis of all student quiz scores and trends.</p><button class="btn btn-amber btn-sm" style="margin-top:16px">Generate</button></div>
            <div class="card" style="cursor:pointer" onclick="showToast('Generating report...','info')"><div style="font-size:36px;margin-bottom:16px">🏆</div><h3 style="font-size:16px;font-weight:700;margin-bottom:8px">Leaderboard Report</h3><p style="font-size:13px;color:var(--muted)">Rankings by total score, quiz count, and problem-solving.</p><button class="btn btn-amber btn-sm" style="margin-top:16px">Generate</button></div>
            <div class="card" style="cursor:pointer" onclick="showToast('Generating report...','info')"><div style="font-size:36px;margin-bottom:16px">📉</div><h3 style="font-size:16px;font-weight:700;margin-bottom:8px">Topic Gap Analysis</h3><p style="font-size:13px;color:var(--muted)">Identify areas where students need the most improvement.</p><button class="btn btn-amber btn-sm" style="margin-top:16px">Generate</button></div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>


<!-- FEEDBACK MODAL (Admin 2) -->
<div class="modal-overlay" id="feedback-modal">
  <div class="modal" style="max-width:520px">
    <div class="modal-close" onclick="closeModal('feedback-modal')">✕</div>
    <div class="modal-title">Send Feedback to Student</div>
    <div class="modal-sub">Send a personalized message or performance tip</div>
    <div class="form-group"><label class="form-label">Select Student</label>
      <select class="form-select" id="feedback-student">
        <option>Riya Sharma</option><option>Karan Mehta</option><option>Priya Nair</option>
        <option><?php echo $_SESSION['name']; ?></option><option>Sam Wilson</option><option>Meera Patel</option>
        <option>Arjun Das</option><option>Neha Singh</option>
      </select>
    </div>
    <div class="form-group"><label class="form-label">Feedback Type</label>
      <select class="form-select" id="feedback-type" onchange="populateFeedbackTemplate()">
        <option value="">Custom Message</option>
        <option value="weak">Weak Topic Alert</option>
        <option value="improve">Improvement Note</option>
        <option value="great">Great Progress</option>
        <option value="streak">Streak Reminder</option>
      </select>
    </div>
    <div class="form-group"><label class="form-label">Message</label><textarea class="form-textarea" id="feedback-message" placeholder="e.g. You are weak in arrays, practice more..."></textarea></div>
    <div style="display:flex;gap:10px">
      <button class="btn btn-amber" style="flex:1" onclick="sendFeedback()">Send Feedback 📩</button>
      <button class="btn btn-outline" onclick="closeModal('feedback-modal')">Cancel</button>
    </div>
  </div>
<!-- TOAST -->
<div class="toast-container" id="toast-container"></div>


<script src="main.js"></script>
<script src="admin2.js"></script>
</body>
</html>