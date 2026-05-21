<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Create New User
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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-cyan-50 p-4 sm:p-6 lg:p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Card -->
        <div class="glass-card rounded-3xl p-6 sm:p-6 lg:p-8 mb-8 overflow-hidden shadow-2xl border border-white/20">
            <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 p-6 rounded-2xl">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="space-y-2">
                        <h1 class="text-3xl sm:text-4xl font-bold text-white tracking-tight">
                            Create New User
                        </h1>
                        <p class="text-white/80 text-lg">
                            Add a new user to the system
                        </p>
                    </div>
                    <div class="text-right">
                        <a href="<?= site_url('users') ?>" class="inline-flex items-center justify-center px-6 py-3 bg-white/80 hover:bg-white text-gray-700 hover:text-gray-900 font-medium rounded-xl border border-gray-200 hover:border-gray-300 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                            <i class="bi bi-x-circle w-5 h-5 mr-2"></i>
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Form Card -->
        <div class="glass-card rounded-2xl p-6 border border-white/20 bg-gradient-to-br from-blue-50 to-indigo-50 border-blue-100">
            <div class="flex items-center space-x-3 mb-6">
                <div class="p-2 bg-blue-500 rounded-lg">
                    <i class="bi bi-person-plus text-white w-5 h-5"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">User Information</h2>
            </div>

            <form action="<?= site_url('users/store') ?>" method="post" class="space-y-6">
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
                               required
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:ring-4 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-300"
                               placeholder="Enter full name">
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
                               required
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:ring-4 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-300"
                               placeholder="Enter email address">
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
                               required
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:ring-4 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-300"
                               placeholder="Enter department">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6">
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl focus:ring-4 focus:ring-indigo-300 focus:outline-none transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                        <i class="bi bi-check-circle w-5 h-5 mr-2"></i>
                        Create User
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
<?= $this->endSection() ?>