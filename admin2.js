// ====== ADMIN 2 ======
function showAdmin2View(viewId, navEl) {
  document.querySelectorAll('#admin2-dashboard .view').forEach(v => v.classList.remove('active'));
  document.getElementById('view-' + viewId).classList.add('active');
  document.querySelectorAll('#admin2-dashboard .nav-item').forEach(n => n.className = 'nav-item');
  if (navEl) navEl.className = 'nav-item active-amber';
}

function renderAdmin2Data() {
  const at = document.getElementById('a2-activity-table');
  const st = document.getElementById('a2-students-table');
  const pt = document.getElementById('a2-perf-table');
  const a2lb = document.getElementById('a2-leaderboard-list');

  if (at) at.innerHTML = state.a2Activity.map(a => `<tr>
    <td style="font-weight:600">${a.student}</td>
    <td>${a.quiz}</td>
    <td><span style="color:var(--accent);font-weight:700;font-family:'Space Mono',monospace">${a.score}</span></td>
    <td style="font-family:'JetBrains Mono',monospace;color:var(--muted)">${a.time}</td>
    <td style="color:var(--muted)">${a.date}</td>
  </tr>`).join('');

  if (st) st.innerHTML = state.students.map(s => `<tr>
    <td style="font-weight:600">${s.name}</td>
    <td>${s.quizzes}</td>
    <td><span style="color:${s.avg>=80?'var(--accent)':s.avg>=65?'var(--accent3)':'var(--danger)'};font-weight:700">${s.avg}%</span></td>
    <td>${s.problems}</td>
    <td><span style="color:var(--accent)">🔥 ${s.streak}d</span></td>
    <td><span class="badge badge-purple">#${s.rank}</span></td>
    <td><span class="badge badge-${s.status==='Active'?'green':'amber'}">${s.status}</span></td>
    <td><button class="btn btn-outline btn-sm" onclick="openFeedbackFor('${s.name}')">📩 Feedback</button></td>
  </tr>`).join('');

  if (pt) pt.innerHTML = state.perfTable.map(p => `<tr>
    <td style="font-weight:600">${p.quiz}</td>
    <td style="color:var(--accent);font-weight:700">${p.avg}</td>
    <td style="color:var(--accent)">${p.highest}</td>
    <td style="color:var(--danger)">${p.lowest}</td>
  </tr>`).join('');

  if (a2lb) a2lb.innerHTML = state.leaderboard.map((u, i) => `
    <div class="lb-row">
      <div class="lb-rank rank-${i+1}">${i<3?['🥇','🥈','🥉'][i]:i+1}</div>
      <div class="lb-avatar ${u.color}">${u.avatar}</div>
      <div><div class="lb-name">${u.name}</div><div class="lb-sub">${u.quizzes} quizzes · ${u.streak}d streak</div></div>
      <div class="lb-score">${u.score.toLocaleString()}</div>
    </div>`).join('');
}

// ====== FEEDBACK ======
function openFeedbackFor(studentName) {
  openModal('feedback-modal');
  const sel = document.getElementById('feedback-student');
  if (sel) {
    for (let i = 0; i < sel.options.length; i++) {
      if (sel.options[i].text === studentName) { sel.selectedIndex = i; break; }
    }
  }
}

function populateFeedbackTemplate() {
  const type = document.getElementById('feedback-type').value;
  const student = document.getElementById('feedback-student').value;
  const templates = {
    weak: `Hi ${student}, you seem to be struggling with Dynamic Programming. Try solving Fibonacci, Coin Change, and Knapsack problems daily for the next week. You've got this! 💪`,
    improve: `Hi ${student}, great progress this week! Your score jumped from 65% to 80%. Keep up the consistency and focus on your weaker topics.`,
    great: `Hi ${student}, exceptional work this week! 🎉 You've been consistently in the top 5 on the leaderboard. Keep sprinting!`,
    streak: `Hi ${student}, don't break your streak! You're ${Math.floor(Math.random()*5)+2} days in — log in today and attempt at least one quiz to keep it going.`,
  };
  if (templates[type]) {
    document.getElementById('feedback-message').value = templates[type];
  }
}

function sendFeedback() {
  const student = document.getElementById('feedback-student').value;
  const msg = document.getElementById('feedback-message').value.trim();
  if (!msg) { showToast('Please write a message first', 'error'); return; }
  closeModal('feedback-modal');
  showToast(`Feedback sent to ${student}! 📩`, 'success');
  document.getElementById('feedback-message').value = '';
  document.getElementById('feedback-type').value = '';
}

document.addEventListener('DOMContentLoaded', () => {
  renderAdmin2Data();
});