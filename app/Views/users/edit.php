<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Edit User - <?= esc($user['full_name']) ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    }
    .min-h-screen { min-height: 100vh; }
    .bg-gradient-to-br { background: linear-gradient(to bottom right, #eef2ff, #ffffff, #ecfeff); }
    .p-4 { padding: 1rem; }
    .p-6 { padding: 1.5rem; }
    .p-8 { padding: 2rem; }
    .mb-8 { margin-bottom: 2rem; }
    .mb-6 { margin-bottom: 1.5rem; }
    .mb-4 { margin-bottom: 1rem; }
    .mb-2 { margin-bottom: 0.5rem; }
    .rounded-2xl { border-radius: 1rem; }
    .rounded-3xl { border-radius: 1.5rem; }
    .rounded-xl { border-radius: 0.75rem; }
    .border { border-width: 1px; }
    .border-white\/20 { border-color: rgba(255, 255, 255, 0.2); }
    .border-gray-200 { border-color: #e5e7eb; }
    .flex { display: flex; }
    .flex-col { flex-direction: column; }
    .justify-between { justify-content: space-between; }
    .items-center { align-items: center; }
    .gap-4 { gap: 1rem; }
    .gap-3 { gap: 0.75rem; }
    .space-y-2 > * + * { margin-top: 0.5rem; }
    .space-y-6 > * + * { margin-top: 1.5rem; }
    .text-3xl { font-size: 1.875rem; line-height: 2.25rem; }
    .text-4xl { font-size: 2.25rem; line-height: 2.5rem; }
    .text-xl { font-size: 1.25rem; line-height: 1.75rem; }
    .text-lg { font-size: 1.125rem; line-height: 1.75rem; }
    .text-sm { font-size: 0.875rem; line-height: 1.25rem; }
    .font-bold { font-weight: 700; }
    .font-medium { font-weight: 500; }
    .text-gray-900 { color: #111827; }
    .text-gray-700 { color: #374151; }
    .text-gray-600 { color: #4b5563; }
    .text-white { color: #ffffff; }
    .text-white\/80 { color: rgba(255, 255, 255, 0.8); }
    .text-white\/90 { color: rgba(255, 255, 255, 0.9); }
    .tracking-tight { letter-spacing: -0.025em; }
    .max-w-4xl { max-width: 56rem; }
    .mx-auto { margin-left: auto; margin-right: auto; }
    .overflow-hidden { overflow: hidden; }
    .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
    .bg-gradient-to-r { background-image: linear-gradient(to right, var(--tw-gradient-stops)); }
    .from-indigo-600 { --tw-gradient-from: #4f46e5; }
    .via-purple-600 { --tw-gradient-via: #9333ea; }
    .to-pink-600 { --tw-gradient-to: #db2777; }
    .bg-gradient-to-br { background-image: linear-gradient(to bottom right, var(--tw-gradient-stops)); }
    .from-blue-50 { --tw-gradient-from: #eff6ff; }
    .to-indigo-50 { --tw-gradient-to: #eef2ff; }
    .border-blue-100 { border-color: #dbeafe; }
    .bg-blue-500 { background-color: #3b82f6; }
    .w-5 { width: 1.25rem; }
    .h-5 { height: 1.25rem; }
    .w-4 { width: 1rem; }
    .h-4 { height: 1rem; }
    .mr-1 { margin-right: 0.25rem; }
    .mr-2 { margin-right: 0.5rem; }
    .text-right { text-align: right; }
    .space-x-4 > * + * { margin-left: 1rem; }
    .inline-flex { display: inline-flex; }
    .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
    .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
    .px-4 { padding-left: 1rem; padding-right: 1rem; }
    .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
    .bg-white\/80 { background-color: rgba(255, 255, 255, 0.8); }
    .hover\:bg-white:hover { background-color: #ffffff; }
    .hover\:text-gray-900:hover { color: #111827; }
    .hover\:border-gray-300:hover { border-color: #d1d5db; }
    .transition-all { transition-property: all; }
    .duration-300 { transition-duration: 300ms; }
    .hover\:shadow-lg:hover { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
    .hover\:-translate-y-0\.5:hover { transform: translateY(-0.125rem); }
    .bg-indigo-600 { background-color: #4f46e5; }
    .hover\:bg-indigo-700:hover { background-color: #4338ca; }
    .focus\:ring-4:focus { box-shadow: 0 0 0 4px var(--tw-ring-color); }
    .focus\:ring-indigo-300:focus { --tw-ring-color: rgba(165, 180, 252, 0.5); }
    .focus\:outline-none:focus { outline: 2px solid transparent; outline-offset: 2px; }
    .block { display: block; }
    .w-full { width: 100%; }
    .bg-gray-50 { background-color: #f9fafb; }
    .border-gray-300 { border-color: #d1d5db; }
    .focus\:ring-blue-500:focus { --tw-ring-color: rgba(59, 130, 246, 0.5); }
    .focus\:border-blue-500:focus { border-color: #3b82f6; }
    .relative { position: relative; }
    .left-0 { left: 0px; }
    .flex-shrink-0 { flex-shrink: 0; }
    .text-gray-500 { color: #6b7280; }
    .pl-10 { padding-left: 2.5rem; }
    .absolute { position: absolute; }
    .inset-y-0 { top: 0px; bottom: 0px; }
    .pointer-events-none { pointer-events: none; }
    .z-10 { z-index: 10; }
    @media (min-width: 640px) {
        .sm\:p-6 { padding: 1.5rem; }
        .sm\:flex-row { flex-direction: row; }
        .sm\:items-center { align-items: center; }
        .sm\:text-4xl { font-size: 2.25rem; line-height: 2.5rem; }
    }
    @media (min-width: 1024px) {
        .lg\:p-8 { padding: 2rem; }
    }
    .header-card { background: #ffffff; border: 1px solid #e5e7eb; border-top: 6px solid #3b82f6; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-cyan-50 p-4 sm:p-6 lg:p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Card -->
        <div class="header-card rounded-3xl p-6 sm:p-6 lg:p-8 mb-8 overflow-hidden shadow-2xl">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="space-y-2">
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight">
                        Edit User
                    </h1>
                    <p class="text-gray-600 text-lg">
                        Update user information and account settings
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-gray-700 text-sm space-x-4">
                        <span class="inline-flex items-center">
                            <i class="bi bi-circle-fill w-4 h-4 mr-1 text-green-400"></i>
                            Live Updates
                        </span>
                        <span class="inline-flex items-center">
                            <i class="bi bi-clock w-5 h-5 mr-2"></i>
                            <span id="current-time"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form Card -->
        <div class="glass-card rounded-2xl p-6 border border-white/20 bg-gradient-to-br from-blue-50 to-indigo-50 border-blue-100">
            <div class="flex items-center space-x-3 mb-6">
                <div class="p-2 bg-blue-500 rounded-lg">
                    <i class="bi bi-pencil-square text-white w-5 h-5"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">User Information</h2>
            </div>

            <form action="<?= site_url('users/update/' . $user['id']) ?>" method="post" class="space-y-6">
                <?= csrf_field() ?>
                
                <!-- Full Name Field -->
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name
                    </label>
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg">
                            <i class="bi bi-person text-blue-600 w-5 h-5"></i>
                        </div>
                        <input type="text" 
                               id="full_name" 
                               name="full_name" 
                               value="<?= esc($user['full_name']) ?>" 
                               required
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:ring-4 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-300">
                    </div>
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-lg">
                            <i class="bi bi-envelope text-green-600 w-5 h-5"></i>
                        </div>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="<?= esc($user['email']) ?>" 
                               required
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:ring-4 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-300">
                    </div>
                </div>

                <!-- Department Field -->
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                        Department
                    </label>
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-lg">
                            <i class="bi bi-building text-purple-600 w-5 h-5"></i>
                        </div>
                        <input type="text" 
                               id="department" 
                               name="department" 
                               value="<?= esc($user['department']) ?>" 
                               required
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:ring-4 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-300">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6">
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl focus:ring-4 focus:ring-indigo-300 focus:outline-none transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                        <i class="bi bi-check-circle w-5 h-5 mr-2"></i>
                        Update User
                    </button>
                    <a href="<?= site_url('users') ?>" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-white/80 hover:bg-white text-gray-700 hover:text-gray-900 font-medium rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                        <i class="bi bi-x-circle w-5 h-5 mr-2"></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Update current time
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour12: true,
            hour: '2-digit',
            minute: '2-digit'
        });
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = timeString;
        }
    }

    // Update time immediately and then every minute
    document.addEventListener('DOMContentLoaded', function() {
        updateTime();
        setInterval(updateTime, 60000);
    });
</script>

<?= $this->endSection() ?>