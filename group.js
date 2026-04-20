// ====== GROUP DASHBOARD ======
function openGroupDashboard(groupId) {
  const g = state.groups.find(x => x.id === groupId);
  if (!g) return;

  document.getElementById('groups-list-view').style.display = 'none';
  document.getElementById('group-dashboard-view').classList.add('active');

  document.getElementById('gd-name').textContent = g.emoji + ' ' + g.name;
  document.getElementById('gd-meta').textContent = `${g.count}/${g.max} members · ${g.topic}`;

  // Members
  const membersData = [
    { init:'A', name:'Alex C.', score:2590, color:'avatar-green', status:'Active today' },
    { init:'R', name:'Riya Sharma', score:2840, color:'avatar-purple', status:'Active today' },
    { init:'S', name:'Sam Wilson', score:2480, color:'avatar-purple', status:'Active 2h ago' },
    { init:'K', name:'Karan Mehta', score:2710, color:'avatar-green', status:'Active today' },
  ].slice(0, g.count);

  document.getElementById('gd-members').innerHTML = membersData.map(m => `
    <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.04)">
      <div class="avatar ${m.color}" style="width:32px;height:32px;font-size:13px">${m.init}</div>
      <div style="flex:1"><div style="font-size:14px;font-weight:600">${m.name}</div><div style="font-size:11px;color:var(--muted);font-family:'JetBrains Mono',monospace">${m.status}</div></div>
      <div style="font-family:'Space Mono',monospace;font-size:13px;color:var(--accent)">${m.score.toLocaleString()}</div>
    </div>`).join('');

  // Activity feed
  document.getElementById('gd-feed').innerHTML = g.feed.map(f => `
    <div class="activity-item">
      <div class="activity-av ${f.color}">${f.avatar}</div>
      <div>
        <div class="activity-name">${f.name}</div>
        <div class="activity-text">${f.text}</div>
        <div class="activity-time">${f.time}</div>
      </div>
    </div>`).join('');

  // Group leaderboard (sorted members by score)
  const sortedMembers = [...membersData].sort((a,b) => b.score - a.score);
  document.getElementById('gd-leaderboard').innerHTML = sortedMembers.map((m, i) => `
    <div class="lb-row">
      <div class="lb-rank rank-${i+1}">${i<3?['🥇','🥈','🥉'][i]:i+1}</div>
      <div class="lb-avatar ${m.color}">${m.init}</div>
      <div><div class="lb-name">${m.name}</div><div class="lb-sub">Group member</div></div>
      <div class="lb-score">${m.score.toLocaleString()}</div>
    </div>`).join('');
}

function closeGroupDashboard() {
  document.getElementById('groups-list-view').style.display = 'block';
  document.getElementById('group-dashboard-view').classList.remove('active');
}

function postGroupUpdate() {
  const ta = document.getElementById('update-textarea');
  const text = ta.value.trim();
  if (!text) { showToast('Please write something first!', 'info'); return; }
  const feed = document.getElementById('gd-feed');
  const newItem = document.createElement('div');
  newItem.className = 'activity-item';
  newItem.style.borderLeft = '2px solid var(--accent)';
  newItem.innerHTML = `
    <div class="activity-av avatar-green">${state.username[0]}</div>
    <div>
      <div class="activity-name">${state.username} <span style="color:var(--accent);font-size:11px">• just now</span></div>
      <div class="activity-text">${text}</div>
      <div class="activity-time">Just now</div>
    </div>`;
  feed.insertBefore(newItem, feed.firstChild);
  ta.value = '';
  showToast('Update posted! 🚀', 'success');
}