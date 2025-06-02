<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Akun Pengguna - Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@0.292.0/dist/umd/lucide.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f8fc;
            color: #333;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #e9eaf0;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #a0aec0;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #718096;
        }
        .content-section {
            display: none;
            opacity: 0;
            transition: opacity 0.4s ease-in-out, transform 0.4s ease-in-out;
            transform: translateY(10px);
        }
        .content-section.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        /* Sidebar link styles are no longer needed as sidebar is removed,
           but keeping them won't cause issues. Could be cleaned up. */
        .sidebar-link {
            color: #4a5568;
            transition: all 0.2s ease-in-out;
            font-weight: 500;
        }
        .sidebar-link.active {
            background-color: #4f46e5;
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2), 0 2px 4px -1px rgba(79, 70, 229, 0.12);
        }
        .sidebar-link.active svg {
            color: white;
        }
        .sidebar-link:not(.active):hover {
            background-color: #eef2ff;
            color: #4f46e5;
        }
        .sidebar-link:not(.active):hover svg {
            color: #4f46e5;
        }
        .card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.07), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            padding: 2rem;
        }
        .form-input {
            border-color: #d1d5db;
            transition: all 0.2s ease-in-out;
            border-radius: 8px;
            padding-top: 0.65rem;
            padding-bottom: 0.65rem;
        }
        .form-input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
            outline: none;
        }
        .btn-primary {
            background-color: #4f46e5;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2), 0 2px 4px -1px rgba(79, 70, 229, 0.12);
        }
        .btn-primary:hover {
            background-color: #4338ca;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.25), 0 4px 6px -2px rgba(79, 70, 229, 0.15);
        }
         .btn-outline-primary {
            background-color: white;
            color: #4f46e5;
            border: 1px solid #4f46e5;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            transition: all 0.2s ease-in-out;
        }
        .btn-outline-primary:hover {
            background-color: #eef2ff;
        }
        .btn-secondary {
            background-color: #eef2ff;
            color: #4f46e5;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            transition: all 0.2s ease-in-out;
            border: 1px solid #c7d2fe;
        }
        .btn-secondary:hover {
            background-color: #e0e7ff;
        }
        .btn-danger {
            background-color: #ef4444; /* Red-500 */
            color: white;
        }
        .btn-danger:hover {
            background-color: #dc2626; /* Red-600 */
        }
        .table th {
            font-weight: 600;
            color: #4a5568;
        }
        .table td {
            color: #4b5563;
        }
        .badge {
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 9999px;
        }
        .badge-green {
            background-color: #dcfce7;
            color: #166534;
        }
        .badge-yellow {
            background-color: #fef9c3;
            color: #713f12;
        }
        .toggle-switch {
            width: 44px;
            height: 24px;
        }
        .toggle-switch-dot {
            width: 20px;
            height: 20px;
            top: 2px;
            left: 2px;
        }
        .peer:checked + .toggle-switch .toggle-switch-dot {
            transform: translateX(100%);
        }
        .profile-info-label {
            font-size: 0.8rem;
            color: #6b7280;
            margin-bottom: 0.15rem;
            font-weight: 500;
        }
        .profile-info-value {
            font-size: 0.95rem;
            color: #1f2937;
            font-weight: 600;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="text-gray-800">


    <div class="min-h-screen">
        <main class="flex-1 p-6 lg:p-10 overflow-y-auto">
            <section id="profile-content" class="content-section space-y-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-8">Profil Saya</h1>
                <div class="card">
                    <div class="md:flex md:space-x-10">
                        <div class="md:w-1/3 text-center md:text-left flex flex-col items-center md:items-start mb-8 md:mb-0">
                            <img src="https://placehold.co/150x150/4f46e5/white?text=DC&font=Inter" alt="Foto Profil Diane Cooper" class="w-36 h-36 rounded-full object-cover shadow-lg border-4 border-indigo-100 mx-auto md:mx-0">
                            <h2 class="text-3xl font-bold text-gray-800 mt-5">{{ Auth::user()->name }}</h2>
                            <p class="text-md text-gray-500 mb-5">{{ Auth::user()->email }}</p>
                            <div class="flex justify-center md:justify-start space-x-6 mb-6">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-indigo-600">15</p>
                                    <p class="text-xs text-gray-500">Post</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-indigo-600">2</p>
                                    <p class="text-xs text-gray-500">Upcoming</p>
                                </div>
                            </div>
                            <button class="btn-outline-primary w-full md:w-auto flex items-center justify-center space-x-2">
                                <svg data-lucide="send" class="w-4 h-4"></svg>
                                <span>Kirim Pesan</span>
                            </button>
                        </div>

                        <div class="md:w-2/3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-5">


                                <div>
                                    <p class="profile-info-label">Name</p>
                                    <p class="profile-info-value">{{ Auth::user()->name }}</p>
                                </div>

                                <div>
                                    <p class="profile-info-label">Email </p>
                                    <p class="profile-info-value">{{ Auth::user()->email }}</p>
                                </div>

                                <div>
                                    <p class="profile-info-label">Mobile </p>
                                    <p class="profile-info-value">{{ Auth::user()->mobile }}</p>
                                </div>
                                <div>
                                    <p class="profile-info-label">Status Anggota</p>
                                    <p class="profile-info-value"><span class="badge badge-green"> {{ Auth::user()->utype === 'ADM' ? 'Admin' : 'User' }}</span></p>
                                </div>

                            </div>
                             <hr class="my-8 border-gray-200">
                             <div class="flex justify-end">
                                <button type="button" class="btn-primary flex items-center space-x-2" data-bs-toggle="modal" data-bs-target="#ubah">
                                    <svg data-lucide="edit-3" class="w-4 h-4"></svg>
                                    <span>Edit Profil</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            } else {
                console.error('Lucide library not loaded.');
            }

            // Sidebar links are removed, so related JS won't find them.
            // const sidebarLinks = document.querySelectorAll('.sidebar-link');
            const contentSections = document.querySelectorAll('.content-section');
            const defaultSection = 'profile';
            const mainContentArea = document.querySelector('main');

            function switchTab(targetId) {
                contentSections.forEach(section => {
                    if (section.id === targetId + '-content') {
                        setTimeout(() => {
                            section.classList.add('active');
                        }, 50);
                        if (mainContentArea) {
                           mainContentArea.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                    } else {
                        section.classList.remove('active');
                    }
                });

                // Sidebar link active state handling is no longer needed
                // sidebarLinks.forEach(link => { ... });

                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }

            // No sidebar links to attach event listeners to
            // sidebarLinks.forEach(link => { ... });

            // Activate the default section
            if (contentSections.length > 0) { // Make sure content sections exist
                switchTab(defaultSection);
            }


            // Logout specific buttons
            const confirmLogoutButton = document.getElementById('confirmLogoutButton');
            if (confirmLogoutButton) {
                confirmLogoutButton.addEventListener('click', () => {
                    const modalHTML = `
                        <div id="customAlertModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center p-4 z-50">
                            <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full text-center">
                                <svg data-lucide="check-circle" class="w-12 h-12 text-green-500 mx-auto mb-3"></svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Simulasi Keluar</h3>
                                <p class="text-sm text-gray-600 mb-4">Anda telah berhasil keluar (simulasi).</p>
                                <button id="customAlertOkButton" class="btn-primary w-full">OK</button>
                            </div>
                        </div>
                    `;
                    document.body.insertAdjacentHTML('beforeend', modalHTML);
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }

                    document.getElementById('customAlertOkButton').addEventListener('click', () => {
                        document.getElementById('customAlertModal').remove();
                        // switchTab('profile'); // Go back to profile for demo - or login page
                    });
                });
            }
            const cancelLogoutButton = document.getElementById('cancelLogoutButton');
             if (cancelLogoutButton) {
                cancelLogoutButton.addEventListener('click', () => {
                    // Since there's no navigation, clicking cancel on logout might just stay,
                    // or you could redirect to the profile section if it's not already active.
                    // For now, if profile is default, it will effectively do nothing if already on logout screen
                    // or switch to profile if on another (currently not possible without nav).
                    // So, it's better to assume we want to go "back" to a default view if possible.
                    if (document.getElementById('profile-content')) {
                       switchTab('profile'); // Attempt to show profile if logout was shown
                    }
                });
            }

            // The profile picture change button in the sidebar is removed.
            // If you add a similar button within the profile-content section, you can handle it here.
        });
    </script>
<!-- Button trigger modal -->

<!-- Bootstrap CSS -->


<!-- Bootstrap JS bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Modal -->
<div class="modal fade" id="ubah" tabindex="-1" aria-labelledby="ubah" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">User Form</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
         <form action="{{ route('profile.edit') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
             <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" >
          </div>
          <div class="mb-3">
            <label for="mobile" class="form-label">Mobile</label>
            <input type="tel" class="form-control" value="{{ old('name', Auth::user()->mobile) }}" id="mobile" name="mobile" >
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" value="{{ old('name', Auth::user()->email) }}" id="email" name="email" >
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control"  id="password" name="password" >
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
