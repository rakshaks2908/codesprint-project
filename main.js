// ====== MAIN JS ======

// ====== STATE ======
const state = {
  currentRole: null,
  username: 'Alex',
  timer: null,
  timeLeft: 1800,
  editingQuizIdx: -1,
  problems: [],
  quizzes: [
    { id:1, title:'Arrays & Strings Blitz', date:'Mar 6', questions:10, time:20, topic:'Arrays', attempts:234, avgScore:78 },
    { id:2, title:'DP Fundamentals', date:'Mar 6', questions:8, time:25, topic:'DP', attempts:156, avgScore:62 },
    { id:3, title:'Sorting Algorithms', date:'Mar 6', questions:12, time:30, topic:'Sorting', attempts:198, avgScore:84 },
    { id:4, title:'Graphs Deep Dive', date:'Mar 5', questions:10, time:35, topic:'Graphs', attempts:142, avgScore:71 },
    { id:5, title:'System Design MCQ', date:'Mar 4', questions:15, time:40, topic:'System Design', attempts:89, avgScore:55 },
  ],
  groups: [
    { id:1, name:'DP Warriors', topic:'Dynamic Programming', members:['A','R','S','K'], count:4, max:8, emoji:'⚔️',
      feed:[
        { name:'Riya S.', avatar:'R', color:'avatar-purple', text:'Solved 3 DP problems today — Knapsack, Coin Change and LCS!', time:'2h ago' },
        { name:'Karan M.', avatar:'K', color:'avatar-green', text:'Practiced memoization patterns. Finally understood top-down vs bottom-up.', time:'4h ago' },
        { name:'Sam W.', avatar:'S', color:'avatar-purple', text:'Completed DP Fundamentals quiz – scored 80%!', time:'5h ago' },
      ]
    },
    { id:2, name:'Graph Gang', topic:'Graphs & Trees', members:['M','P','L'], count:3, max:6, emoji:'🌐',
      feed:[
        { name:'Meera P.', avatar:'M', color:'avatar-amber', text:'Solved Dijkstra and Bellman-Ford problems on LeetCode.', time:'1h ago' },
        { name:'Priya N.', avatar:'P', color:'avatar-green', text:'Revised DFS/BFS traversal patterns. Ready for tomorrow\'s quiz.', time:'3h ago' },
      ]
    },
    { id:3, name:'Competitive Crew', topic:'Competitive Prog', members:['X','Y','Z','W','V'], count:5, max:10, emoji:'🏁',
      feed:[
        { name:'Alex C.', avatar:'A', color:'avatar-green', text:'Participated in Codeforces round 800. Solved 3 problems!', time:'30m ago' },
      ]
    },
  ],
  leaderboard: [
    { name:'Riya Sharma', score:2840, quizzes:28, streak:21, avatar:'R', color:'avatar-purple' },
    { name:'Karan Mehta', score:2710, quizzes:26, streak:18, avatar:'K', color:'avatar-green' },
    { name:'Priya Nair', score:2650, quizzes:25, streak:14, avatar:'P', color:'avatar-amber' },
    { name:'Alex C.', score:2590, quizzes:28, streak:14, avatar:'A', color:'avatar-green' },
    { name:'Sam Wilson', score:2480, quizzes:22, streak:10, avatar:'S', color:'avatar-purple' },
    { name:'Meera Patel', score:2310, quizzes:20, streak:8, avatar:'M', color:'avatar-amber' },
    { name:'Arjun Das', score:2180, quizzes:18, streak:7, avatar:'D', color:'avatar-green' },
    { name:'Neha Singh', score:2050, quizzes:17, streak:5, avatar:'N', color:'avatar-purple' },
  ],
  history: [
    { quiz:'Arrays Blitz', date:'Mar 5, 2026', score:'90/100', time:'18:32', rank:'#2', status:'Completed' },
    { quiz:'DP Fundamentals', date:'Mar 4, 2026', score:'70/100', time:'24:10', rank:'#8', status:'Completed' },
    { quiz:'Sorting MCQ', date:'Mar 3, 2026', score:'85/100', time:'22:05', rank:'#4', status:'Completed' },
    { quiz:'Graphs Deep Dive', date:'Mar 2, 2026', score:'75/100', time:'29:44', rank:'#6', status:'Completed' },
    { quiz:'Strings Sprint', date:'Mar 1, 2026', score:'95/100', time:'15:20', rank:'#1', status:'Completed' },
  ],
  adminQuizzes: [
    { title:'Arrays & Strings Blitz', questions:10, time:20, date:'Mar 6', attempts:234, avg:78, status:'Active' },
    { title:'DP Fundamentals', questions:8, time:25, date:'Mar 6', attempts:156, avg:62, status:'Active' },
    { title:'Sorting Algorithms', questions:12, time:30, date:'Mar 6', attempts:198, avg:84, status:'Active' },
    { title:'Graphs Deep Dive', questions:10, time:35, date:'Mar 5', attempts:142, avg:71, status:'Closed' },
    { title:'System Design MCQ', questions:15, time:40, date:'Mar 4', attempts:89, avg:55, status:'Closed' },
  ],

  students: [
    { name:'Riya Sharma', quizzes:28, avg:94, problems:183, streak:21, rank:1, status:'Active' },
    { name:'Karan Mehta', quizzes:26, avg:90, problems:161, streak:18, rank:2, status:'Active' },
    { name:'Priya Nair', quizzes:25, avg:88, problems:145, streak:14, rank:3, status:'Active' },
    { name:'Alex C.', quizzes:28, avg:87, problems:142, streak:14, rank:4, status:'Active' },
    { name:'Sam Wilson', quizzes:22, avg:82, problems:128, streak:10, rank:5, status:'Active' },
    { name:'Meera Patel', quizzes:20, avg:77, problems:110, streak:8, rank:6, status:'Active' },
    { name:'Arjun Das', quizzes:18, avg:71, problems:95, streak:7, rank:7, status:'At Risk' },
    { name:'Neha Singh', quizzes:17, avg:68, problems:84, streak:5, rank:8, status:'At Risk' },
  ],
  a2Activity: [
    { student:'Riya S.', quiz:'Arrays Blitz', score:'95/100', time:'16:20', date:'Mar 6' },
    { student:'Karan M.', quiz:'Arrays Blitz', score:'90/100', time:'18:05', date:'Mar 6' },
    { student:'Alex C.', quiz:'DP Fundamentals', score:'70/100', time:'24:10', date:'Mar 6' },
    { student:'Priya N.', quiz:'Sorting MCQ', score:'88/100', time:'21:45', date:'Mar 6' },
    { student:'Sam W.', quiz:'Arrays Blitz', score:'80/100', time:'19:30', date:'Mar 6' },
  ],
  perfTable: [
    { quiz:'Arrays Blitz', avg:'78%', highest:'100%', lowest:'40%' },
    { quiz:'DP Fundamentals', avg:'62%', highest:'95%', lowest:'20%' },
    { quiz:'Sorting MCQ', avg:'84%', highest:'100%', lowest:'50%' },
    { quiz:'Graphs Deep Dive', avg:'71%', highest:'98%', lowest:'30%' },
  ],
};

// ====== MODALS ======
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

function uploadProblem() { showToast('Problem uploaded! ✓', 'success'); closeModal('upload-modal'); }

function createGroupAction() {
  const name = document.getElementById('new-group-name').value.trim();
  const topic = document.getElementById('new-group-topic').value;
  if (!name) { showToast('Please enter a group name', 'error'); return; }
  const emojis = ['🚀','🌟','⚡','🎯','🔥','💡','🌐','⚔️'];
  state.groups.push({
    id: state.groups.length + 10,
    name: name,
    topic: topic,
    members: [state.username[0].toUpperCase()],
    count: 1,
    max: 8,
    emoji: emojis[Math.floor(Math.random() * emojis.length)],
    feed: []
  });
  renderStudentData();
  closeModal('create-group-modal');
  showToast('Study group created! 🎉', 'success');
}

// Close modals on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', e => {
    if (e.target === overlay && overlay.id !== 'quiz-attempt-overlay') {
      overlay.classList.remove('open');
    }
  });
});

// ====== TOAST ======
function showToast(msg, type = 'info') {
  const container = document.getElementById('toast-container');
  const icons = { success:'✅', error:'❌', info:'💡' };
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.innerHTML = `<span>${icons[type]||'💡'}</span><span>${msg}</span>`;
  container.appendChild(toast);
  setTimeout(() => toast.remove(), 3500);
}

