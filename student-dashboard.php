<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$upload_error = $_SESSION['upload_error'] ?? '';
unset($_SESSION['upload_error']);

// Fetch problems for the current user
$stmt = $pdo->prepare("SELECT * FROM problems WHERE User_id = :uid ORDER BY Problem_id DESC");
$stmt->execute([':uid' => $user_id]);
$db_problems = $stmt->fetchAll();

$total_problems = count($db_problems);
$easy_count = 0;
$medium_count = 0;
$hard_count = 0;

$js_problems = [];
foreach ($db_problems as $row) {
  if (strtolower($row['difficulty']) == 'easy')
    $easy_count++;
  if (strtolower($row['difficulty']) == 'medium')
    $medium_count++;
  if (strtolower($row['difficulty']) == 'hard')
    $hard_count++;

  $js_problems[] = [
    'id' => (int) $row['Problem_id'],
    'title' => htmlspecialchars($row['problem_title'], ENT_QUOTES),
    'platform' => htmlspecialchars($row['platform'], ENT_QUOTES),
    'diff' => ucfirst(strtolower($row['difficulty'])),
    'topic' => htmlspecialchars($row['topic'], ENT_QUOTES),
    'date' => 'Recently',
    'status' => 'Solved'
  ];
}
$js_problems_json = json_encode($js_problems);

// Fetch quizzes from the database
$stmt2 = $pdo->query("SELECT * FROM quizes");
$db_quizzes = $stmt2->fetchAll();

$js_quizzes = [];
foreach ($db_quizzes as $q) {
  $js_quizzes[] = [
    'id' => (int) $q['quiz_id'],
    'title' => htmlspecialchars($q['title'], ENT_QUOTES),
    'time' => (int) $q['duration'],
    'questions' => (int) $q['max_questions'],
    'points' => (int) $q['points'],
    'date' => 'Available',
    'attempts' => 0
  ];
}
$js_quizzes_json = json_encode($js_quizzes);

// Fetch Leaderboard
$stmt_lb = $pdo->query("
    SELECT 
        u.user_id, 
        u.name, 
        COALESCE(SUM(qa.score), 0) AS total_points 
    FROM users u 
    LEFT JOIN quiz_attempts qa ON u.user_id = qa.user_id 
    WHERE u.role = 'student' 
    GROUP BY u.user_id 
    ORDER BY total_points DESC
");
$db_leaderboard = $stmt_lb->fetchAll();

$js_leaderboard = [];
$colors_arr = ['avatar-purple', 'avatar-green', 'avatar-amber'];
$c_idx = 0;
foreach ($db_leaderboard as $lb) {
  $js_leaderboard[] = [
    'name' => htmlspecialchars($lb['name'], ENT_QUOTES),
    'score' => (int) $lb['total_points'],
    'avatar' => strtoupper(substr($lb['name'] ?? 'U', 0, 1)),
    'color' => $colors_arr[$c_idx % 3]
  ];
  $c_idx++;
}
$js_leaderboard_json = json_encode($js_leaderboard);

// Fetch History for current user
$stmt_hist = $pdo->prepare("
    SELECT 
        q.title, 
        qa.score, 
        qa.attempted_at 
    FROM quiz_attempts qa 
    JOIN quizes q ON qa.quiz_id = q.quiz_id 
    WHERE qa.user_id = ? 
    ORDER BY qa.attempted_at DESC
");
$stmt_hist->execute([$user_id]);
$db_history = $stmt_hist->fetchAll();

$js_history = [];
foreach ($db_history as $hist) {
  $js_history[] = [
    'quiz' => htmlspecialchars($hist['title'], ENT_QUOTES),
    'date' => date("d M, h:i A", strtotime($hist['attempted_at'])),
    'score' => (int) $hist['score']
  ];
}
$js_history_json = json_encode($js_history);

// Find rank of logged-in user
$stmt_check = $pdo->prepare("SELECT SUM(score) AS my_score FROM quiz_attempts WHERE user_id = ?");
$stmt_check->execute([$user_id]);
$my_score_row = $stmt_check->fetch();
$my_score = $my_score_row['my_score'] ?? null;

if ($my_score === null) {
  $user_rank = '-';
} else {
  $stmt_rank = $pdo->prepare("
        SELECT COUNT(*) + 1 AS user_rank
        FROM (
            SELECT user_id, SUM(score) AS total_score
            FROM quiz_attempts
            GROUP BY user_id
        ) AS scores
        WHERE total_score > ?
    ");
  $stmt_rank->execute([$my_score]);
  $rank_row = $stmt_rank->fetch();
  $user_rank = '#' . $rank_row['user_rank'];
}
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
  <div id="student-dashboard" class="page active">
    <div class="dashboard">
      <aside class="sidebar">
        <div class="sidebar-header">
          <div class="logo sidebar-logo">Code<span>Sprint</span></div>
        </div>
        <div class="sidebar-user">
          <div class="avatar avatar-green" id="s-avatar">
            <?= htmlspecialchars(strtoupper(substr($_SESSION['name'] ?? 'S', 0, 1))) ?></div>
          <div class="user-info">
            <div class="user-name" id="s-username"><?php echo $_SESSION['name']; ?></div>
            <div class="user-role">// <?php echo $_SESSION['role']; ?></div>
          </div>
        </div>
        <nav class="sidebar-nav">
          <div class="nav-section-label">MAIN</div>
          <div class="nav-item active-green" onclick="showStudentView('overview', this)"><span class="nav-icon">⬡</span>
            Dashboard</div>
          <div class="nav-item" onclick="showStudentView('problems', this)"><span class="nav-icon">💡</span> Problems
          </div>
          <div class="nav-item" onclick="showStudentView('quizzes', this)"><span class="nav-icon">⚡</span> Quizzes <span
              class="nav-badge">3</span></div>
          <div class="nav-section-label">COMMUNITY</div>
          <div class="nav-item" onclick="showStudentView('groups', this)"><span class="nav-icon">👥</span> Study Groups
          </div>
          <div class="nav-item" onclick="showStudentView('leaderboard', this)"><span class="nav-icon">🏆</span>
            Leaderboard</div>
          <div class="nav-section-label">INSIGHTS</div>
          <div class="nav-item" onclick="showStudentView('analytics', this)"><span class="nav-icon">📈</span> My
            Analytics</div>
          <div class="nav-item" onclick="showStudentView('history', this)"><span class="nav-icon">📋</span> History
          </div>
        </nav>
        <div class="sidebar-footer">
          <a class="nav-item" href="logout.php" style="text-decoration:none; color:inherit;"><span
              class="nav-icon">←</span> Sign Out</a>
        </div>
      </aside>

      <main class="main-content">
        <div class="top-bar">
          <div class="top-bar-left">
            <div class="page-title" id="s-page-title">Dashboard</div>
            <div class="page-subtitle" id="s-page-sub">Good morning, let's grind 🚀</div>
          </div>
          <div class="top-bar-actions">
            <button class="btn btn-outline btn-sm" onclick="openModal('upload-modal')">+ Upload Problem</button>
            <button class="btn btn-primary btn-sm" onclick="showStudentView('quizzes', null)">Take Quiz ⚡</button>
          </div>
        </div>

        <div class="content-area">

          <!-- OVERVIEW -->
          <div class="view active" id="view-overview">
            <div class="metrics-grid">
              <div class="metric-card metric-green">
                <div class="metric-icon">✅</div>
                <div class="metric-val"><?php echo $total_problems; ?></div>
                <div class="metric-label">Problems Solved</div>
              </div>
              <div class="metric-card metric-purple">
                <div class="metric-icon">⚡</div>
                <div class="metric-val">87%</div>
                <div class="metric-label">Quiz Accuracy</div>
                <div class="metric-change up">↑ +3% vs last week</div>
              </div>
              <div class="metric-card metric-amber">
                <div class="metric-icon">🔥</div>
                <div class="metric-val">14</div>
                <div class="metric-label">Day Streak</div>
                <div class="metric-change up">↑ Personal best!</div>
              </div>
              <div class="metric-card metric-info">
                <div class="metric-icon">🏅</div>
                <div class="metric-val"><?= htmlspecialchars($user_rank ?? '-') ?></div>
                <div class="metric-label">Leaderboard Rank</div>
              </div>
            </div>
            <div class="grid-2" style="margin-bottom:24px">
              <div class="card">
                <div class="card-title">Recent Problems <button class="btn btn-outline btn-sm"
                    onclick="showStudentView('problems',null)">View All</button></div>
                <div id="recent-problems"></div>
              </div>
              <div class="card">
                <div class="card-title">Today's Quizzes <span class="badge badge-purple">3 Available</span></div>
                <div id="today-quizzes"></div>
              </div>
            </div>
            <div class="grid-2">
              <div class="card">
                <div class="card-title">Weekly Activity</div>
                <div style="display:flex;gap:4px;align-items:flex-end;height:90px;margin-bottom:8px">
                  <div style="flex:1;background:rgba(0,255,135,.6);height:60%;border-radius:4px 4px 0 0"></div>
                  <div style="flex:1;background:rgba(0,255,135,.6);height:80%;border-radius:4px 4px 0 0"></div>
                  <div style="flex:1;background:rgba(0,255,135,.3);height:40%;border-radius:4px 4px 0 0"></div>
                  <div style="flex:1;background:rgba(0,255,135,.6);height:90%;border-radius:4px 4px 0 0"></div>
                  <div style="flex:1;background:rgba(0,255,135,.6);height:70%;border-radius:4px 4px 0 0"></div>
                  <div style="flex:1;background:var(--accent);height:100%;border-radius:4px 4px 0 0"></div>
                  <div style="flex:1;background:rgba(0,255,135,.15);height:20%;border-radius:4px 4px 0 0"></div>
                </div>
                <div style="display:flex;gap:4px">
                  <div
                    style="flex:1;text-align:center;font-size:10px;color:var(--muted);font-family:'JetBrains Mono',monospace">
                    M</div>
                  <div
                    style="flex:1;text-align:center;font-size:10px;color:var(--muted);font-family:'JetBrains Mono',monospace">
                    T</div>
                  <div
                    style="flex:1;text-align:center;font-size:10px;color:var(--muted);font-family:'JetBrains Mono',monospace">
                    W</div>
                  <div
                    style="flex:1;text-align:center;font-size:10px;color:var(--muted);font-family:'JetBrains Mono',monospace">
                    T</div>
                  <div
                    style="flex:1;text-align:center;font-size:10px;color:var(--muted);font-family:'JetBrains Mono',monospace">
                    F</div>
                  <div
                    style="flex:1;text-align:center;font-size:10px;color:var(--accent);font-family:'JetBrains Mono',monospace">
                    S</div>
                  <div
                    style="flex:1;text-align:center;font-size:10px;color:var(--muted);font-family:'JetBrains Mono',monospace">
                    S</div>
                </div>
              </div>
              <div class="card">
                <div class="card-title">Topic Coverage</div>
                <div style="display:flex;flex-direction:column;gap:14px">
                  <div>
                    <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px">
                      <span>Arrays & Strings</span><span style="color:var(--accent)">78%</span></div>
                    <div class="progress-bar">
                      <div class="progress-fill fill-green" style="width:78%"></div>
                    </div>
                  </div>
                  <div>
                    <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px">
                      <span>Dynamic Programming</span><span style="color:#a78bfa">45%</span></div>
                    <div class="progress-bar">
                      <div class="progress-fill fill-purple" style="width:45%"></div>
                    </div>
                  </div>
                  <div>
                    <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px">
                      <span>Graphs & Trees</span><span style="color:var(--accent3)">62%</span></div>
                    <div class="progress-bar">
                      <div class="progress-fill fill-amber" style="width:62%"></div>
                    </div>
                  </div>
                  <div>
                    <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px">
                      <span>Sorting & Searching</span><span style="color:var(--accent)">91%</span></div>
                    <div class="progress-bar">
                      <div class="progress-fill fill-green" style="width:91%"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- PROBLEMS VIEW -->
          <div class="view" id="view-problems">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
              <div>
                <h2 style="font-size:24px;font-weight:800">My Problems</h2>
                <p style="color:var(--muted);font-size:14px;margin-top:4px">Track and manage your solved coding problems
                </p>
              </div>
              <button class="btn btn-primary" onclick="openModal('upload-modal')">+ Upload Problem</button>
            </div>
            <div style="display:flex;gap:12px;margin-bottom:20px">
              <button class="btn btn-sm"
                style="background:rgba(0,255,135,.1);color:var(--accent);border:1px solid rgba(0,255,135,.3)">All
                (<?php echo $total_problems; ?>)</button>
              <button class="btn btn-outline btn-sm">Easy (<?php echo $easy_count; ?>)</button>
              <button class="btn btn-outline btn-sm">Medium (<?php echo $medium_count; ?>)</button>
              <button class="btn btn-outline btn-sm">Hard (<?php echo $hard_count; ?>)</button>
            </div>
            <div class="card">
              <div id="problems-list"></div>
            </div>
          </div>

          <!-- QUIZZES VIEW -->
          <div class="view" id="view-quizzes">
            <div style="margin-bottom:24px">
              <h2 style="font-size:24px;font-weight:800">Quizzes</h2>
              <p style="color:var(--muted);font-size:14px;margin-top:4px">Timed MCQ assessments curated by your
                instructors</p>
            </div>
            <div class="grid-3" id="quizzes-grid"></div>
          </div>

          <!-- GROUPS VIEW -->
          <div class="view" id="view-groups">
            <div id="groups-list-view">
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
                <div>
                  <h2 style="font-size:24px;font-weight:800">Study Groups</h2>
                  <p style="color:var(--muted);font-size:14px;margin-top:4px">Collaborate and grow together</p>
                </div>
                <button class="btn btn-primary" onclick="openModal('create-group-modal')">+ Create Group</button>
              </div>
              <div class="grid-3" id="groups-grid"></div>
            </div>
            <!-- GROUP DASHBOARD (shown when group is clicked) -->
            <div id="group-dashboard-view" class="group-dashboard-view">
              <div class="group-back-btn" onclick="closeGroupDashboard()">← Back to Groups</div>
              <div class="group-dash-header">
                <div>
                  <div class="group-dash-name" id="gd-name">DP Warriors</div>
                  <div class="group-dash-meta" id="gd-meta">4/8 members · Dynamic Programming</div>
                </div>
                <div style="display:flex;gap:10px;align-items:center">
                  <span class="badge badge-green" id="gd-streak">🔥 3-day group streak</span>
                  <button class="btn btn-outline btn-sm" onclick="showToast('Invite link copied!','info')">📋
                    Invite</button>
                </div>
              </div>

              <div class="grid-2" style="margin-bottom:24px">
                <!-- POST UPDATE -->
                <div>
                  <div class="card" style="margin-bottom:16px">
                    <div class="card-title">Post Your Update</div>
                    <div class="post-update-box" style="padding:0;background:transparent;border:none">
                      <textarea id="update-textarea"
                        placeholder="What did you work on today? e.g. Solved Knapsack problem, practiced memoization..."></textarea>
                      <button class="btn btn-primary btn-sm" style="margin-top:12px" onclick="postGroupUpdate()">Post
                        Update ✓</button>
                    </div>
                  </div>
                  <!-- MEMBERS LIST -->
                  <div class="card">
                    <div class="card-title">Members</div>
                    <div id="gd-members"></div>
                  </div>
                </div>
                <!-- ACTIVITY FEED -->
                <div class="card">
                  <div class="card-title">Today's Activity Feed</div>
                  <div class="activity-feed" id="gd-feed"></div>
                </div>
              </div>

              <!-- GROUP LEADERBOARD -->
              <div class="card">
                <div class="card-title">Group Leaderboard 🏆</div>
                <div id="gd-leaderboard"></div>
              </div>
            </div>
          </div>

          <!-- LEADERBOARD VIEW -->
          <div class="view" id="view-leaderboard">
            <div style="margin-bottom:24px">
              <h2 style="font-size:24px;font-weight:800">Leaderboard 🏆</h2>
              <p style="color:var(--muted);font-size:14px;margin-top:4px">Top performers this week</p>
            </div>
            <div class="card" id="leaderboard-list"></div>
          </div>

          <!-- ANALYTICS VIEW -->
          <div class="view" id="view-analytics">
            <div style="margin-bottom:24px">
              <h2 style="font-size:24px;font-weight:800">My Analytics</h2>
              <p style="color:var(--muted);font-size:14px;margin-top:4px">Your personal performance insights</p>
            </div>
            <div class="metrics-grid" style="margin-bottom:28px">
              <div class="metric-card metric-green">
                <div class="metric-icon">📊</div>
                <div class="metric-val">87%</div>
                <div class="metric-label">Avg Score</div>
              </div>
              <div class="metric-card metric-purple">
                <div class="metric-icon">⏱</div>
                <div class="metric-val">28</div>
                <div class="metric-label">Quizzes Taken</div>
              </div>
              <div class="metric-card metric-amber">
                <div class="metric-icon">✅</div>
                <div class="metric-val"><?php echo $total_problems; ?></div>
                <div class="metric-label">Problems Done</div>
              </div>
              <div class="metric-card metric-info">
                <div class="metric-icon">🔥</div>
                <div class="metric-val">14d</div>
                <div class="metric-label">Current Streak</div>
              </div>
            </div>
            <div class="grid-2">
              <div class="card">
                <div class="card-title">Performance Ring</div>
                <div style="display:flex;align-items:center;gap:28px;margin-bottom:20px">
                  <div class="perf-ring">
                    <div class="perf-ring-val">87%</div>
                  </div>
                  <div>
                    <div style="font-size:22px;font-weight:800;margin-bottom:4px">Good</div>
                    <div style="color:var(--muted);font-size:13px">Above platform average of 72%</div>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-title">Topic Strength</div>
                <div style="display:flex;flex-direction:column;gap:14px">
                  <div style="display:flex;justify-content:space-between;align-items:center">
                    <div style="display:flex;align-items:center;gap:10px"><span class="badge badge-green">✓
                        Strong</span><span style="font-size:14px">Sorting & Searching</span></div>
                    <div style="font-size:13px;color:var(--accent)">94%</div>
                  </div>
                  <div style="display:flex;justify-content:space-between;align-items:center">
                    <div style="display:flex;align-items:center;gap:10px"><span class="badge badge-green">✓
                        Strong</span><span style="font-size:14px">Binary Search</span></div>
                    <div style="font-size:13px;color:var(--accent)">91%</div>
                  </div>
                  <div style="display:flex;justify-content:space-between;align-items:center">
                    <div style="display:flex;align-items:center;gap:10px"><span class="badge badge-amber">~
                        Average</span><span style="font-size:14px">Graphs</span></div>
                    <div style="font-size:13px;color:var(--accent3)">67%</div>
                  </div>
                  <div style="display:flex;justify-content:space-between;align-items:center">
                    <div style="display:flex;align-items:center;gap:10px"><span class="badge badge-red">✗
                        Weak</span><span style="font-size:14px">DP</span></div>
                    <div style="font-size:13px;color:var(--danger)">43%</div>
                  </div>
                  <div style="display:flex;justify-content:space-between;align-items:center">
                    <div style="display:flex;align-items:center;gap:10px"><span class="badge badge-red">✗
                        Weak</span><span style="font-size:14px">Segment Trees</span></div>
                    <div style="font-size:13px;color:var(--danger)">38%</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- HISTORY VIEW -->
          <div class="view" id="view-history">
            <div style="margin-bottom:24px">
              <h2 style="font-size:24px;font-weight:800">Quiz History</h2>
              <p style="color:var(--muted);font-size:14px;margin-top:4px">Your past quiz attempts and scores</p>
            </div>
            <div class="card">
              <div class="table-wrap">
                <table>
                  <thead>
                    <tr>
                      <th>Quiz Name</th>
                      <th>Date</th>
                      <th>Score</th>
                    </tr>
                  </thead>
                  <tbody id="history-table"></tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
      </main>
    </div>
  </div>


  <!-- QUIZ ATTEMPT -->
  <div class="modal-overlay" id="quiz-attempt-overlay">
    <div class="modal" style="max-width:680px">
      <div class="modal-close" onclick="closeQuizAttempt()">✕</div>
      <div class="modal-title" id="qa-title">Quiz Title</div>
      <div class="modal-sub" id="qa-sub">10 questions · 30 minutes</div>
      <div class="quiz-timer"><span>⏱</span>
        <div class="timer-display" id="timer-display">30:00</div><span
          style="color:var(--muted);font-size:13px;margin-left:auto">Time Remaining</span>
      </div>
      <div id="qa-questions"></div>
      <button class="btn btn-primary" style="width:100%;margin-top:20px" onclick="submitQuiz()">Submit Quiz →</button>
    </div>
  </div>
  <!-- UPLOAD PROBLEM -->
  <div class="modal-overlay <?= !empty($upload_error) ? 'open' : '' ?>" id="upload-modal">
    <div class="modal">
      <div class="modal-close" onclick="closeModal('upload-modal')">✕</div>
      <div class="modal-title">Upload Problem</div>
      <div class="modal-sub">Add a solved coding problem to your tracker</div>
      <?php if (!empty($upload_error)): ?>
        <div style="color:var(--danger);margin-bottom:12px;font-size:14px;"><?= htmlspecialchars($upload_error) ?></div>
      <?php endif; ?>
      <form method="POST" action="upload_problem.php">
        <div class="form-group"><label class="form-label">Problem Title</label><input type="text" name="problem_title"
            class="form-input" placeholder="e.g. Two Sum" required /></div>
        <div class="form-group"><label class="form-label">Platform</label><select name="platform" class="form-select">
            <option value="LeetCode">LeetCode</option>
            <option value="Codeforces">Codeforces</option>
            <option value="HackerRank">HackerRank</option>
            <option value="GeeksforGeeks">GeeksforGeeks</option>
            <option value="Custom">Custom</option>
          </select></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div class="form-group"><label class="form-label">Difficulty</label><select name="difficulty"
              class="form-select">
              <option value="Easy">Easy</option>
              <option value="Medium">Medium</option>
              <option value="Hard">Hard</option>
            </select></div>
          <div class="form-group"><label class="form-label">Topic</label><select name="topic" class="form-select">
              <option value="Arrays">Arrays</option>
              <option value="DP">DP</option>
              <option value="Graphs">Graphs</option>
              <option value="Trees">Trees</option>
              <option value="Sorting">Sorting</option>
            </select></div>
        </div>
        <button type="submit" name="upload_problem" class="btn btn-primary" style="width:100%;margin-top:20px">Upload
          ✓</button>
      </form>
    </div>
  </div>
  <!-- CREATE GROUP -->
  <div class="modal-overlay" id="create-group-modal">
    <div class="modal">
      <div class="modal-close" onclick="closeModal('create-group-modal')">✕</div>
      <div class="modal-title">Create Study Group</div>
      <div class="modal-sub">Start a collaborative learning circle</div>
      <div class="form-group"><label class="form-label">Group Name</label><input class="form-input" id="new-group-name"
          placeholder="e.g. DP Warriors" /></div>
      <div class="form-group"><label class="form-label">Focus Topic</label><select class="form-select"
          id="new-group-topic">
          <option>Mixed</option>
          <option>Arrays & Strings</option>
          <option>Dynamic Programming</option>
          <option>Graphs</option>
          <option>Competitive Prep</option>
        </select></div>
      <div class="form-group"><label class="form-label">Max Members</label><input class="form-input" type="number"
          value="8" /></div>
      <button class="btn btn-primary" style="width:100%" onclick="createGroupAction()">Create Group →</button>
    </div>
  </div>
  <!-- TOAST -->
  <div class="toast-container" id="toast-container"></div>


  <script src="main.js"></script>
  <script>
    // Override state problems with fetched DB problems for this user
    state.problems = <?= $js_problems_json ?>;

    // Override state quizzes with fetched DB quizzes
    state.quizzes = <?= $js_quizzes_json ?>;

    // Override state leaderboard with fetched DB data
    state.leaderboard = <?= $js_leaderboard_json ?>;

    // Override state history with fetched DB data
    state.history = <?= $js_history_json ?>;
  </script>
  <script src="student.js"></script>
  <script src="group.js"></script>
</body>

</html>