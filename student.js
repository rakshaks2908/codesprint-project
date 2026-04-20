// ====== STUDENT VIEWS ======
function showStudentView(viewId, navEl) {
  document.querySelectorAll('#student-dashboard .view').forEach(v => v.classList.remove('active'));
  document.getElementById('view-' + viewId).classList.add('active');
  // Reset group dashboard when switching
  if (viewId === 'groups') {
    document.getElementById('groups-list-view').style.display = 'block';
    document.getElementById('group-dashboard-view').classList.remove('active');
  }
  const titles = { overview:'Dashboard', problems:'My Problems', quizzes:'Quizzes', groups:'Study Groups', leaderboard:'Leaderboard', analytics:'My Analytics', history:'Quiz History' };
  const subs = { overview:"Good morning, let's grind 🚀", problems:'Track your solved problems', quizzes:'Attempt timed quizzes', groups:'Collaborate with peers', leaderboard:'See where you rank', analytics:'Your performance insights', history:'Past quiz attempts' };
  document.getElementById('s-page-title').textContent = titles[viewId] || viewId;
  document.getElementById('s-page-sub').textContent = subs[viewId] || '';
  document.querySelectorAll('#student-dashboard .sidebar-nav .nav-item').forEach(n => n.className = 'nav-item');
  if (navEl) navEl.className = 'nav-item active-green';
}

function renderStudentData() {
  // Recent problems
  const rp = document.getElementById('recent-problems');
  if (rp) rp.innerHTML = state.problems.slice(0, 4).map(p => `
    <div class="problem-item">
      <div class="difficulty-dot diff-${p.diff.toLowerCase()}"></div>
      <div><div class="problem-title">${p.title}</div><div class="problem-tags"><span class="tag-chip">${p.topic}</span><span class="tag-chip">${p.platform}</span></div></div>
      <div class="problem-meta"><span class="badge badge-${p.diff==='Easy'?'green':p.diff==='Medium'?'amber':'red'}">${p.diff}</span><div style="margin-top:4px">${p.date}</div></div>
    </div>`).join('');

  // Today quizzes
  const tq = document.getElementById('today-quizzes');
  if (tq) tq.innerHTML = state.quizzes.slice(0, 3).map(q => `
    <div class="problem-item" onclick="startQuiz(${q.id})">
      <div style="font-size:20px">⚡</div>
      <div><div class="problem-title">${q.title}</div><div class="problem-tags"><span class="tag-chip">${q.questions}Q</span><span class="tag-chip">${q.time}min</span></div></div>
      <button class="btn btn-purple btn-sm">Start</button>
    </div>`).join('');

  // Problems list
  const pl = document.getElementById('problems-list');
  if (pl) pl.innerHTML = state.problems.map(p => `
    <div class="problem-item">
      <div class="difficulty-dot diff-${p.diff.toLowerCase()}"></div>
      <div style="flex:1"><div class="problem-title">${p.title}</div><div class="problem-tags"><span class="tag-chip">${p.topic}</span><span class="tag-chip">${p.platform}</span></div></div>
      <span class="badge badge-${p.diff==='Easy'?'green':p.diff==='Medium'?'amber':'red'}">${p.diff}</span>
      <div style="font-size:12px;color:var(--muted);text-align:right;width:60px">${p.date}</div>
    </div>`).join('');

  // Quizzes grid
  const qg = document.getElementById('quizzes-grid');
  if (qg) qg.innerHTML = state.quizzes.map(q => `
    <div class="quiz-card" onclick="startQuiz(${q.id})">
      <div class="quiz-header"><div><div class="quiz-title">${q.title}</div><div class="quiz-date">${q.date}</div></div><span class="badge badge-purple">New</span></div>
      <div class="progress-bar"><div class="progress-fill fill-purple" style="width:0%"></div></div>
      <div class="quiz-stats"><div class="quiz-stat"><strong>${q.questions}</strong> Questions</div><div class="quiz-stat"><strong>${q.time}min</strong> Limit</div><div class="quiz-stat"><strong>${q.attempts}</strong> Attempts</div></div>
    </div>`).join('');

  // Groups grid
  const gg = document.getElementById('groups-grid');
  if (gg) gg.innerHTML = state.groups.map(g => `
    <div class="group-card" onclick="openGroupDashboard(${g.id})">
      <div class="group-header"><div class="group-icon">${g.emoji}</div><div><div class="group-title">${g.name}</div><div class="group-members">${g.count}/${g.max} members · ${g.topic}</div></div></div>
      <div class="member-avatars">${g.members.map(m => `<div class="member-av">${m}</div>`).join('')}</div>
      <div style="margin-top:16px;display:flex;gap:8px">
        <button class="btn btn-primary btn-sm" style="flex:1" onclick="event.stopPropagation();openGroupDashboard(${g.id})">Open Group →</button>
      </div>
    </div>`).join('');

  // Leaderboard
  const lb = document.getElementById('leaderboard-list');
  if (lb) lb.innerHTML = state.leaderboard.map((u, i) => `
    <div class="lb-row">
      <div class="lb-rank rank-${i+1}">${i<3?['🥇','🥈','🥉'][i]:i+1}</div>
      <div class="lb-avatar ${u.color}">${u.avatar}</div>
      <div><div class="lb-name">${u.name}</div></div>
      <div class="lb-score">${u.score.toLocaleString()}</div>
    </div>`).join('');

  // History
  const ht = document.getElementById('history-table');
  if (ht) ht.innerHTML = state.history.map(h => `
    <tr>
      <td style="font-weight:600">${h.quiz}</td>
      <td style="color:var(--muted)">${h.date}</td>
      <td><span style="color:var(--accent);font-family:'Space Mono',monospace;font-weight:700">${h.score}</span></td>
    </tr>`).join('');
}

// ====== QUIZ ======
function startQuiz(id) {
  const q = state.quizzes.find(x => x.id === id);
  if (!q) return;
  
  fetch(`get_questions.php?quiz_id=${id}`)
    .then(response => response.json())
    .then(data => {
      console.log("FETCH CALLED");
      console.log(data);

      if (data.error) {
        showToast("Error loading questions", "error");
        return;
      }
      if (!data || data.length === 0) {
        showToast("No questions available for this quiz.", "error");
        return;
      }

      state.currentQuiz = q;
      state.currentQuestions = data;
      state.currentAnswers = new Array(data.length).fill(null);

      document.getElementById('qa-title').textContent = q.title;
      document.getElementById('qa-sub').textContent = `${q.questions} questions · ${q.time} minutes`;
      state.timeLeft = q.time * 60;
      updateTimer(); 
      startTimer();

      renderQuestions(data);

      document.getElementById('quiz-attempt-overlay').classList.add('open');
    })
    .catch(err => {
      console.error(err);
      showToast("Error parsing questions from server", "error");
    });
}

function renderQuestions(data) {
  const qQuestionsElement = document.getElementById('qa-questions');
  if (!qQuestionsElement) return;

  qQuestionsElement.innerHTML = data.map((qq, i) => `
    <div class="quiz-question">
      <div class="q-num">QUESTION ${i+1} of ${data.length}</div>
      <div class="q-text">${qq.question_text}</div>
      <div class="options">
        ${['a','b','c','d'].map((letter) => {
          const optText = qq['option_' + letter];
          if (!optText || optText.trim() === '') return '';
          const letterUpper = letter.toUpperCase();
          return `
            <div class="option" onclick="selectOption(this, ${i}, '${letterUpper}')">
              <div class="opt-letter">${letterUpper}</div>${optText}
            </div>`;
        }).join('')}
      </div>
    </div>`).join('');
}

function selectOption(el, qIndex, selectedLetter) {
  el.parentElement.querySelectorAll('.option').forEach(o => o.classList.remove('selected'));
  el.classList.add('selected');
  if (typeof qIndex !== 'undefined') {
    state.currentAnswers[qIndex] = selectedLetter;
  }
}

function submitQuiz() {
  stopTimer();

  if (!state.currentQuiz || !state.currentQuestions) return;

  // Submit attempt to database using basic fetch API
  const user_answers = state.currentAnswers;
  
  fetch("submit_quiz.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      quiz_id: state.currentQuiz.id,
      answers: user_answers
    })
  })
  .then(resp => resp.json())
  .then(data => {
    document.getElementById('quiz-attempt-overlay').classList.remove('open');
    if (data.success) {
      showToast(`Quiz submitted! Score: ${data.score} 🎉`, 'success');
      
      // Optionally refresh UI history here in the future
    } else {
      showToast('Submit failed: ' + (data.message || 'Unknown error'), 'error');
    }
  })
  .catch(err => {
    document.getElementById('quiz-attempt-overlay').classList.remove('open');
    showToast(`Quiz submission failed! Please check console.`, 'error');
    console.error(err);
  });
}

function closeQuizAttempt() { stopTimer(); document.getElementById('quiz-attempt-overlay').classList.remove('open'); }

function startTimer() {
  stopTimer();
  state.timer = setInterval(() => {
    state.timeLeft--;
    updateTimer();
    if (state.timeLeft <= 0) { stopTimer(); submitQuiz(); }
  }, 1000);
}

function stopTimer() { if (state.timer) { clearInterval(state.timer); state.timer = null; } }

function updateTimer() {
  const m = Math.floor(state.timeLeft / 60);
  const s = state.timeLeft % 60;
  const disp = document.getElementById('timer-display');
  if (!disp) return;
  disp.textContent = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
  disp.className = 'timer-display' + (state.timeLeft <= 60 ? ' danger' : state.timeLeft <= 300 ? ' warning' : '');
}


document.addEventListener('DOMContentLoaded', () => {
  const nameEl = document.getElementById('s-username');
  const n = nameEl ? nameEl.textContent.trim() : 'User';
  state.username = n;
  const avEl = document.getElementById('s-avatar');
  if (avEl && n.length > 0) avEl.textContent = n[0].toUpperCase();
  renderStudentData();
});

