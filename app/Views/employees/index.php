<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Employee Manager | CodeIgniter 4 CRUD</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #6C5CE7;
        --primary-dark: #5849c2;
        --accent: #00cec9;
        --danger: #ff5c5c;
        --success: #2ecc71;
        --bg: #f4f5fb;
        --card: #ffffff;
        --text: #2d3436;
        --muted: #8895a7;
        --radius: 14px;
        --shadow: 0 10px 30px rgba(108, 92, 231, 0.12);
    }

    * { box-sizing: border-box; }

    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #f4f5fb 0%, #eef1ff 100%);
        color: var(--text);
        min-height: 100vh;
        padding: 40px 16px;
    }

    .wrapper {
        max-width: 760px;
        margin: 0 auto;
        animation: fadeInUp 0.6s ease both;
    }

    header.page-head {
        text-align: center;
        margin-bottom: 32px;
    }

    header.page-head h1 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 6px;
        background: linear-gradient(90deg, var(--primary), var(--accent));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    header.page-head p {
        color: var(--muted);
        margin: 0;
        font-size: 0.95rem;
    }

    .card {
        background: var(--card);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 28px;
        margin-bottom: 24px;
    }

    .card h2 {
        margin: 0 0 18px;
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    form#employeeForm {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .field label {
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--muted);
    }

    .field input {
        padding: 12px 14px;
        border-radius: 10px;
        border: 1.5px solid #e3e6f0;
        font-family: inherit;
        font-size: 0.95rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        outline: none;
    }

    .field input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.12);
    }

    .field.error input {
        border-color: var(--danger);
    }

    .field .error-msg {
        font-size: 0.75rem;
        color: var(--danger);
        min-height: 14px;
    }

    .form-actions {
        grid-column: 1 / -1;
        display: flex;
        gap: 10px;
        margin-top: 4px;
    }

    button {
        cursor: pointer;
        font-family: inherit;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.2s ease;
    }

    button:active { transform: scale(0.97); }

    .btn-primary {
        background: linear-gradient(90deg, var(--primary), #8177e8);
        color: #fff;
        padding: 12px 22px;
        box-shadow: 0 6px 16px rgba(108, 92, 231, 0.35);
    }

    .btn-primary:hover { box-shadow: 0 8px 20px rgba(108, 92, 231, 0.45); }

    .btn-secondary {
        background: #eef0f7;
        color: var(--text);
        padding: 12px 18px;
        display: none;
    }

    .btn-secondary.show { display: inline-block; }

    .list-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .badge-count {
        background: #eef0ff;
        color: var(--primary);
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead th {
        text-align: left;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--muted);
        padding: 0 10px 10px;
        border-bottom: 1.5px solid #eef0f7;
    }

    tbody tr {
        animation: fadeInRow 0.35s ease both;
    }

    tbody td {
        padding: 14px 10px;
        border-bottom: 1px solid #f2f3f9;
        font-size: 0.92rem;
    }

    tbody tr:hover { background: #fafaff; }

    .role-pill {
        background: linear-gradient(90deg, #e7f9f8, #eef8ff);
        color: #00997c;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        display: inline-block;
    }

    .row-actions { display: flex; gap: 8px; justify-content: flex-end; }

    .icon-btn {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: #f4f5fb;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
    }

    .icon-btn.edit:hover { background: #e6e2ff; }
    .icon-btn.delete:hover { background: #ffe4e4; }

    .empty-state {
        text-align: center;
        padding: 40px 10px;
        color: var(--muted);
    }

    .empty-state .emoji { font-size: 2.2rem; display: block; margin-bottom: 8px; }

    .spinner {
        width: 22px;
        height: 22px;
        border: 3px solid #e3e6f0;
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
        margin: 30px auto;
    }

    footer {
        text-align: center;
        color: var(--muted);
        font-size: 0.8rem;
        margin-top: 20px;
    }

    footer a { color: var(--primary); text-decoration: none; font-weight: 600; }

    /* Toast */
    #toastContainer {
        position: fixed;
        top: 20px;
        right: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        z-index: 999;
    }

    .toast {
        min-width: 260px;
        max-width: 340px;
        background: var(--card);
        border-radius: 12px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-left: 5px solid var(--success);
        animation: slideIn 0.35s ease both;
        font-size: 0.88rem;
    }

    .toast.error { border-left-color: var(--danger); }
    .toast .icon { font-size: 1.2rem; }
    .toast.hide { animation: slideOut 0.3s ease forwards; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInRow {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(40px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideOut {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(40px); }
    }

    @media (max-width: 560px) {
        form#employeeForm { grid-template-columns: 1fr; }
        .row-actions { flex-wrap: wrap; }
    }
</style>
</head>
<body>

<div id="toastContainer"></div>

<div class="wrapper">
    <header class="page-head">
        <h1>Employee Manager</h1>
        <p>CodeIgniter 4 &bull; MySQL (AWS RDS) &bull; Simple CRUD Demo</p>
    </header>

    <div class="card">
        <h2 id="formTitle">➕ Add Employee</h2>
        <form id="employeeForm">
            <input type="hidden" id="employeeId" value="">
            <div class="field" id="nameField">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="e.g. Priya Sharma" autocomplete="off">
                <span class="error-msg" id="nameError"></span>
            </div>
            <div class="field" id="roleField">
                <label for="role">Role</label>
                <input type="text" id="role" name="role" placeholder="e.g. Backend Developer" autocomplete="off">
                <span class="error-msg" id="roleError"></span>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary" id="submitBtn">Add Employee</button>
                <button type="button" class="btn-secondary" id="cancelEdit">Cancel</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="list-head">
            <h2 style="margin:0;">📋 Employees</h2>
            <span class="badge-count" id="countBadge">0 total</span>
        </div>
        <div id="listArea">
            <div class="spinner"></div>
        </div>
    </div>

    <footer>
        Built with CodeIgniter 4 &bull; <a href="https://github.com/" target="_blank">View on GitHub</a>
    </footer>
</div>

<script>
const API_BASE = '<?= base_url('employees') ?>';

const form = document.getElementById('employeeForm');
const listArea = document.getElementById('listArea');
const countBadge = document.getElementById('countBadge');
const formTitle = document.getElementById('formTitle');
const submitBtn = document.getElementById('submitBtn');
const cancelEdit = document.getElementById('cancelEdit');
const employeeIdInput = document.getElementById('employeeId');

function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `<span class="icon">${type === 'success' ? '✅' : '⚠️'}</span><span>${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => {
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 300);
    }, 3200);
}

function clearErrors() {
    document.getElementById('nameField').classList.remove('error');
    document.getElementById('roleField').classList.remove('error');
    document.getElementById('nameError').textContent = '';
    document.getElementById('roleError').textContent = '';
}

function applyErrors(errors) {
    if (!errors) return;
    if (errors.name) {
        document.getElementById('nameField').classList.add('error');
        document.getElementById('nameError').textContent = errors.name;
    }
    if (errors.role) {
        document.getElementById('roleField').classList.add('error');
        document.getElementById('roleError').textContent = errors.role;
    }
}

function resetForm() {
    form.reset();
    employeeIdInput.value = '';
    formTitle.textContent = '➕ Add Employee';
    submitBtn.textContent = 'Add Employee';
    cancelEdit.classList.remove('show');
    clearErrors();
}

cancelEdit.addEventListener('click', resetForm);

async function fetchEmployees() {
    listArea.innerHTML = '<div class="spinner"></div>';
    try {
        const res = await fetch(API_BASE, { headers: { 'Accept': 'application/json' } });
        const json = await res.json();
        renderList(json.data || []);
    } catch (err) {
        listArea.innerHTML = '<div class="empty-state"><span class="emoji">⚠️</span>Could not load employees. Check your API/DB connection.</div>';
        showToast('Failed to load employees.', 'error');
    }
}

function renderList(employees) {
    countBadge.textContent = `${employees.length} total`;

    if (employees.length === 0) {
        listArea.innerHTML = '<div class="empty-state"><span class="emoji">🗂️</span>No employees yet. Add your first one above!</div>';
        return;
    }

    const rows = employees.map(emp => `
        <tr data-id="${emp.id}">
            <td>#${emp.id}</td>
            <td>${escapeHtml(emp.name)}</td>
            <td><span class="role-pill">${escapeHtml(emp.role)}</span></td>
            <td>
                <div class="row-actions">
                    <button class="icon-btn edit" title="Edit" onclick="startEdit(${emp.id}, '${escapeAttr(emp.name)}', '${escapeAttr(emp.role)}')">✏️</button>
                    <button class="icon-btn delete" title="Delete" onclick="deleteEmployee(${emp.id})">🗑️</button>
                </div>
            </td>
        </tr>
    `).join('');

    listArea.innerHTML = `
        <table>
            <thead><tr><th>ID</th><th>Name</th><th>Role</th><th></th></tr></thead>
            <tbody>${rows}</tbody>
        </table>
    `;
}

function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}
function escapeAttr(str) {
    return String(str).replace(/'/g, "\\'");
}

function startEdit(id, name, role) {
    employeeIdInput.value = id;
    document.getElementById('name').value = name;
    document.getElementById('role').value = role;
    formTitle.textContent = '✏️ Edit Employee';
    submitBtn.textContent = 'Update Employee';
    cancelEdit.classList.add('show');
    clearErrors();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

async function deleteEmployee(id) {
    if (!confirm('Delete this employee?')) return;
    try {
        const res = await fetch(`${API_BASE}/${id}`, { method: 'DELETE' });
        const json = await res.json();
        if (json.status === 'success') {
            showToast(json.message, 'success');
            fetchEmployees();
        } else {
            showToast(json.message || 'Something went wrong.', 'error');
        }
    } catch (err) {
        showToast('Network error while deleting.', 'error');
    }
}

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearErrors();

    const name = document.getElementById('name').value.trim();
    const role = document.getElementById('role').value.trim();
    const id = employeeIdInput.value;

    const body = new URLSearchParams({ name, role });
    const isEdit = Boolean(id);
    const url = isEdit ? `${API_BASE}/${id}` : API_BASE;
    const method = isEdit ? 'PUT' : 'POST';

    submitBtn.disabled = true;

    try {
        const res = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'Accept': 'application/json' },
            body
        });
        const json = await res.json();

        if (json.status === 'success') {
            showToast(json.message, 'success');
            resetForm();
            fetchEmployees();
        } else {
            applyErrors(json.errors);
            showToast(json.message || 'Please fix the errors.', 'error');
        }
    } catch (err) {
        showToast('Network error. Please try again.', 'error');
    } finally {
        submitBtn.disabled = false;
    }
});

fetchEmployees();
</script>
</body>
</html>
