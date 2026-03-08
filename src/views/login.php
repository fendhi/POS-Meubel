<div class="max-w-md mx-auto">
  <div class="bg-white rounded-2xl shadow-card border border-brand-100 p-6">
    <h1 class="text-xl font-semibold text-brand-800">Login</h1>
    <p class="text-sm text-slate-600 mt-1">Masuk untuk mengelola POS.</p>

    <div class="mt-4 rounded-xl bg-brand-50 border border-brand-100 p-3 text-sm">
      <div class="text-slate-700">Akun default:</div>
      <div class="mt-1 text-slate-900"><span class="font-medium">Username:</span> admin</div>
      <div class="text-slate-900"><span class="font-medium">Password:</span> admin</div>
      
    </div>

    <form method="post" class="mt-6 space-y-4">
      <div>
        <label class="text-sm text-slate-700">Username</label>
        <input name="username" class="mt-1 w-full rounded-xl border border-brand-100 focus:border-brand-400 focus:ring-brand-200 focus:ring-2 px-3 py-2" placeholder="admin" required />
      </div>
      <div>
        <label class="text-sm text-slate-700">Password</label>
        <input type="password" name="password" class="mt-1 w-full rounded-xl border border-brand-100 focus:border-brand-400 focus:ring-brand-200 focus:ring-2 px-3 py-2" placeholder="admin" required />
      </div>
      <button class="w-full px-4 py-2 rounded-xl bg-brand-700 hover:bg-brand-800 text-white text-sm shadow">Masuk</button>
    </form>

  </div>
</div>
