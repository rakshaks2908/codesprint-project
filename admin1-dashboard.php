<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch dynamic counts
$stmt_quizzes = $pdo->query("SELECT COUNT(*) FROM quizes");
$total_quizzes = $stmt_quizzes->fetchColumn();

$stmt_questions = $pdo->query("SELECT COUNT(*) FROM questions");
$total_questions = $stmt_questions->fetchColumn();

$stmt_attempts = $pdo->query("SELECT COUNT(*) FROM quiz_attempts");
$total_attempts = $stmt_attempts->fetchColumn();
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
<div id="admin1-dashboard" class="page active">
  <div class="dashboard">
    <aside class="sidebar">
      <div class="sidebar-header"><div class="logo sidebar-logo">Code<span>Sprint</span></div></div>
      <div class="sidebar-user">
        <div class="avatar avatar-purple"><?= htmlspecialchars(strtoupper(substr($_SESSION['name'] ?? 'Q', 0, 1))) ?></div>
        <div class="user-info"><div class="user-name"><?php echo $_SESSION['name']; ?></div><div class="user-role">// <?php echo $_SESSION['role']; ?></div></div>
      </div>
      <nav class="sidebar-nav">
        <div class="nav-section-label">QUIZ MANAGEMENT</div>
        <div class="nav-item active-purple" onclick="showAdmin1View('a1-overview',this)"><span class="nav-icon">⬡</span> Overview</div>
        <div class="nav-item" onclick="showAdmin1View('a1-create',this)"><span class="nav-icon">✏️</span> Create Quiz</div>
      </nav>
      <div class="sidebar-footer"><a class="nav-item" href="logout.php" style="text-decoration:none; color:inherit;"><span class="nav-icon">←</span> Sign Out</a></div>
    </aside>
    <main class="main-content">
      <div class="top-bar">
        <div class="top-bar-left">
          <div class="page-title">Quiz Manager</div>
          <div class="page-subtitle">Create and manage quiz content</div>
        </div>
        <div class="top-bar-actions">
          <button class="btn btn-purple" onclick="showAdmin1View('a1-create',null)">+ New Quiz</button>
        </div>
      </div>
      <div class="content-area">

        <!-- A1 OVERVIEW -->
        <div class="view active" id="view-a1-overview">
          <div class="metrics-grid">
            <div class="metric-card metric-purple"><div class="metric-icon">📋</div><div class="metric-val" id="a1-total-quizzes"><?= htmlspecialchars($total_quizzes) ?></div><div class="metric-label">Total Quizzes</div></div>
            <div class="metric-card metric-green"><div class="metric-icon">❓</div><div class="metric-val"><?= htmlspecialchars($total_questions) ?></div><div class="metric-label">Questions</div></div>
            <div class="metric-card metric-amber"><div class="metric-icon">👥</div><div class="metric-val"><?= htmlspecialchars($total_attempts) ?></div><div class="metric-label">Total Attempts</div></div>
            <div class="metric-card metric-info"><div class="metric-icon">⚡</div><div class="metric-val">3</div><div class="metric-label">Active Now</div></div>
          </div>
          <div class="card">
            <div class="card-title">Quiz Operations</div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
              <!-- Show Quizzes -->
              <a href="read_quiz.php" style="text-decoration: none; color: inherit;">
                <div class="feature-card fc-blue" style="cursor: pointer; padding: 24px; text-align: center; height: 100%;">
                  <div class="feature-icon" style="font-size: 28px; margin-bottom: 12px;">📋</div>
                  <div class="feature-title" style="font-size: 16px;">Show Current Quizzes</div>
                </div>
              </a>
              <!-- Edit Quiz -->
              <a href="update_quiz.php" style="text-decoration: none; color: inherit;">
                <div class="feature-card fc-purple" style="cursor: pointer; padding: 24px; text-align: center; height: 100%;">
                  <div class="feature-icon" style="font-size: 28px; margin-bottom: 12px;">✏️</div>
                  <div class="feature-title" style="font-size: 16px;">Edit Quiz</div>
                </div>
              </a>
              <!-- Delete Quiz -->
              <a href="delete_quiz.php" style="text-decoration: none; color: inherit;">
                <div class="feature-card" style="cursor: pointer; padding: 24px; text-align: center; height: 100%; border-color: rgba(255, 71, 87, 0.3);">
                  <div class="feature-icon" style="font-size: 28px; margin-bottom: 12px;">🗑️</div>
                  <div class="feature-title" style="font-size: 16px; color: var(--danger);">Delete Quiz</div>
                </div>
              </a>
            </div>
          </div>
        </div>

        <!-- A1 CREATE QUIZ -->
       <form method="POST" action="add_questions.php">

<div class="view" id="view-a1-create">
  <div style="max-width:700px">
    
    <h2 style="font-size:24px;font-weight:800;margin-bottom:6px">Create New Quiz</h2>
    <p style="color:var(--muted);font-size:14px;margin-bottom:32px">Configure your quiz details and add questions</p>

    <div class="card" style="margin-bottom:24px">
      <div class="card-title">Quiz Details</div>

      <!-- ✅ FIXED -->
      <div class="form-group">
        <label class="form-label">Quiz Title</label>
        <input class="form-input" name="title" placeholder="e.g. Data Structures – Week 4 MCQ" />
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        
        <!-- ✅ FIXED -->
        <div class="form-group">
          <label class="form-label">Time Limit (minutes)</label>
          <input class="form-input" name="duration" type="number" value="30" />
        </div>

        <!-- ✅ FIXED -->
        <div class="form-group">
          <label class="form-label">Max Questions</label>
          <input class="form-input" name="max_questions" type="number" value="10" />
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <!-- ✅ FIXED -->
        <div class="form-group">
          <label class="form-label">Points per Correct</label>
          <input class="form-input" name="points" type="number" value="10" />
        </div>
      </div>
    </div>

    <!-- Questions section (leave for now, not connected to DB yet) -->
    <div class="card" style="margin-bottom:24px">
      <div class="card-title">
        Add Questions 
        <button type="button" class="btn btn-purple btn-sm" onclick="addQuestion()">+ Add Q</button>
      </div>

      <div id="questions-builder">
        <div class="quiz-question" style="position:relative">
          <div class="q-num">QUESTION 1</div>

          <input name="question_text[]" class="form-input" placeholder="Enter your question here..." style="margin-bottom:16px" required />

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
            <input name="option_a[]" class="form-input" placeholder="Option A" required />
            <input name="option_b[]" class="form-input" placeholder="Option B" required />
            <input name="option_c[]" class="form-input" placeholder="Option C" required />
            <input name="option_d[]" class="form-input" placeholder="Option D" required />
          </div>

          <div style="margin-top:12px">
            <label class="form-label">Correct Answer</label>
            <select name="correct_option[]" class="form-select">
              <option value="A">A</option>
              <option value="B">B</option>
              <option value="C">C</option>
              <option value="D">D</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- ✅ FIXED BUTTON -->
    <div style="display:flex;gap:12px">
      <button class="btn btn-purple" type="submit" name="create_quiz">Save & Publish Quiz</button>
      <button type="button" class="btn btn-outline">Save as Draft</button>
    </div>

  </div>
</div>

</form>

        <!-- A1 ALL QUIZZES -->
        <div class="view" id="view-a1-quizzes">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
            <h2 style="font-size:24px;font-weight:800">All Quizzes</h2>
            <button class="btn btn-purple" onclick="showAdmin1View('a1-create',null)">+ Create Quiz</button>
          </div>
          <div class="card">
            <div class="table-wrap">
              <table>
                <thead><tr><th>Quiz Name</th><th>Topic</th><th>Questions</th><th>Time</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody id="a1-all-quizzes"></tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- A1 QUESTION BANK -->
        <div class="view" id="view-a1-questions">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
            <h2 style="font-size:24px;font-weight:800">Question Bank</h2>
            <button class="btn btn-purple" onclick="showToast('Add question modal coming soon','info')">+ Add Question</button>
          </div>
          <div class="card"><div class="table-wrap"><table><thead><tr><th>Question</th><th>Topic</th><th>Difficulty</th><th>Used In</th><th>Actions</th></tr></thead><tbody id="question-bank-table"></tbody></table></div></div>
        </div>

        <!-- A1 SETTINGS -->
        <div class="view" id="view-a1-settings">
          <div style="max-width:600px">
            <h2 style="font-size:24px;font-weight:800;margin-bottom:24px">Default Quiz Settings</h2>
            <div class="card">
              <div class="form-group"><label class="form-label">Default Time Limit</label><input class="form-input" type="number" value="30" /></div>
              <div class="form-group"><label class="form-label">Default Questions per Quiz</label><input class="form-input" type="number" value="10" /></div>
              <div class="form-group"><label class="form-label">Points per Correct Answer</label><input class="form-input" type="number" value="10" /></div>
              <div class="form-group"><label class="form-label">Allow Quiz Retake?</label><select class="form-select"><option>No – One attempt only</option><option>Yes – Unlimited</option><option>Yes – Once</option></select></div>
              <div class="form-group"><label class="form-label">Show Answer After Submission?</label><select class="form-select"><option>Yes</option><option>No</option></select></div>
              <button class="btn btn-purple" onclick="showToast('Settings saved!','success')">Save Settings</button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>


<!-- EDIT QUIZ MODAL -->
<div class="modal-overlay" id="edit-quiz-modal">
  <div class="modal" style="max-width:480px">
    <div class="modal-close" onclick="closeModal('edit-quiz-modal')">✕</div>
    <div class="modal-title">Edit Quiz</div>
    <div class="modal-sub">Update the quiz details below</div>
    <div class="form-group"><label class="form-label">Quiz Title</label><input class="form-input" id="edit-quiz-title" placeholder="Quiz title" /></div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
      <div class="form-group"><label class="form-label">Time Limit (min)</label><input class="form-input" id="edit-quiz-time" type="number" /></div>
      <div class="form-group"><label class="form-label">No. of Questions</label><input class="form-input" id="edit-quiz-questions" type="number" /></div>
    </div>
<!-- TOAST -->
<div class="toast-container" id="toast-container"></div>


<script src="main.js"></script>
<script src="admin1.js"></script>
</body>
</html>