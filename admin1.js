// ====== ADMIN 1 ======
function showAdmin1View(viewId, navEl) {
  document.querySelectorAll('#admin1-dashboard .view').forEach(v => v.classList.remove('active'));
  document.getElementById('view-' + viewId).classList.add('active');
  document.querySelectorAll('#admin1-dashboard .nav-item').forEach(n => n.className = 'nav-item');
  if (navEl) navEl.className = 'nav-item active-purple';
}

let qCount = 1;
function addQuestion() {
  qCount++;
  const builder = document.getElementById('questions-builder');
  if (!builder) return;
  const div = document.createElement('div');
  div.className = 'quiz-question';
  div.style.position = 'relative';
  div.innerHTML = `
    <div class="q-num">QUESTION ${qCount}</div>
    <input name="question_text[]" class="form-input" placeholder="Enter your question here..." style="margin-bottom:16px" required />
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
      <input name="option_a[]" class="form-input" placeholder="Option A" required /><input name="option_b[]" class="form-input" placeholder="Option B" required />
      <input name="option_c[]" class="form-input" placeholder="Option C" required /><input name="option_d[]" class="form-input" placeholder="Option D" required />
    </div>
    <div style="margin-top:12px"><label class="form-label">Correct Answer</label><select name="correct_option[]" class="form-select"><option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option></select></div>`;
  builder.appendChild(div);
}

function saveNewQuiz() {
  const titleEl = document.getElementById('new-quiz-title');
  const timeEl = document.getElementById('new-quiz-time');
  const questionsEl = document.getElementById('new-quiz-questions');
  const title = titleEl ? titleEl.value.trim() : 'New Quiz';
  if (!title) { showToast('Please enter a quiz title', 'error'); return; }
  state.adminQuizzes.push({
    title: title || 'New Quiz',
    questions: questionsEl ? parseInt(questionsEl.value) || 10 : 10,
    time: timeEl ? parseInt(timeEl.value) || 30 : 30,
    date: 'Mar 26',
    attempts: 0,
    avg: 0,
    status: 'Active'
  });
  updateA1Count();
  renderAdmin1Data();
  showToast('Quiz published successfully! ✓', 'success');
  showAdmin1View('a1-overview', null);
}

function updateA1Count() {
  const el = document.getElementById('a1-total-quizzes');
  if (el) el.textContent = state.adminQuizzes.length;
}

function openEditQuiz(idx) {
  state.editingQuizIdx = idx;
  const q = state.adminQuizzes[idx];
  document.getElementById('edit-quiz-title').value = q.title;
  document.getElementById('edit-quiz-time').value = q.time;
  document.getElementById('edit-quiz-questions').value = q.questions;
  openModal('edit-quiz-modal');
}

function saveEditQuiz() {
  if (state.editingQuizIdx < 0) return;
  const q = state.adminQuizzes[state.editingQuizIdx];
  q.title = document.getElementById('edit-quiz-title').value.trim() || q.title;
  q.time = parseInt(document.getElementById('edit-quiz-time').value) || q.time;
  q.questions = parseInt(document.getElementById('edit-quiz-questions').value) || q.questions;
  renderAdmin1Data();
  closeModal('edit-quiz-modal');
  showToast('Quiz updated successfully! ✓', 'success');
  state.editingQuizIdx = -1;
}

function deleteQuiz(idx) {
  if (!confirm('Delete this quiz? This action cannot be undone.')) return;
  state.adminQuizzes.splice(idx, 1);
  updateA1Count();
  renderAdmin1Data();
  showToast('Quiz deleted.', 'error');
}

function renderAdmin1Data() {
  const qt = document.getElementById('a1-quiz-table');
  const aq = document.getElementById('a1-all-quizzes');
  const qb = document.getElementById('question-bank-table');

  const rows = state.adminQuizzes.map((q, i) => `<tr>
    <td style="font-weight:600">${q.title}</td>
    <td>${q.questions}</td>
    <td>${q.time}min</td>
    <td style="color:var(--muted)">${q.date}</td>
    <td>${q.attempts}</td>
    <td><span style="color:var(--accent);font-weight:700">${q.avg}%</span></td>
    <td><div style="display:flex;gap:8px">
      <button class="btn btn-outline btn-sm" onclick="openEditQuiz(${i})">Edit</button>
      <button class="btn btn-sm btn-danger" onclick="deleteQuiz(${i})">Delete</button>
    </div></td>
  </tr>`).join('');
  if (qt) qt.innerHTML = rows;

  if (aq) aq.innerHTML = state.adminQuizzes.map((q, i) => `<tr>
    <td style="font-weight:600">${q.title}</td>
    <td><span class="badge badge-blue">${q.title.split(' ').pop()}</span></td>
    <td>${q.questions}</td>
    <td>${q.time}min</td>
    <td style="color:var(--muted)">${q.date}</td>
    <td><span class="badge badge-${q.status==='Active'?'green':'gray'}">${q.status}</span></td>
    <td><div style="display:flex;gap:8px">
      <button class="btn btn-outline btn-sm" onclick="openEditQuiz(${i})">Edit</button>
      <button class="btn btn-sm btn-danger" onclick="deleteQuiz(${i})">Delete</button>
    </div></td>
  </tr>`).join('');

  if (qb) qb.innerHTML = state.questionBank.map((q, i) => `<tr>
    <td style="max-width:300px;font-size:13px">${q.q}</td>
    <td><span class="badge badge-blue">${q.topic}</span></td>
    <td><span class="badge badge-${q.diff==='Easy'?'green':'amber'}">${q.diff}</span></td>
    <td style="color:var(--muted)">${q.used} quizzes</td>
    <td><div style="display:flex;gap:8px"><button class="btn btn-outline btn-sm">Edit</button><button class="btn btn-sm btn-danger">Delete</button></div></td>
  </tr>`).join('');
}

document.addEventListener('DOMContentLoaded', () => {
  renderAdmin1Data();
});

