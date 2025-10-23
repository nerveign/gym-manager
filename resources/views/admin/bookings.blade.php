<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Fixed Sidebar -->
        <div class="w-64 bg-white fixed left-0 top-0 h-full z-50 border-r">
            <x-dashboard-header name="{{ $user->name }}" />
            
            <nav class="mt-6">
                <div class="px-4 py-2 text-xs font-medium text-zinc-400">Main</div>
                
                <x-nav-item text="Home" color="text-zinc-700" src="home.svg" location="admin.dashboard" />


                <div class="px-4 py-2 text-xs font-medium text-zinc-400 mt-6">Management</div>
                
                <x-nav-item text="Users" color="text-zinc-700" src="users.svg" location="admin.users_management"  />
                <x-nav-item text="Trainer" color="text-gray-600" src="user.svg" location="admin.trainers_management" />
                <x-nav-item text="Booking" color="text-gray-600" src="calendar.svg" location="admin.bookings_management" style="bg-blue-50 border-r-4 border-blue-500"  />
                <x-nav-item text="Class" color="text-gray-600" src="class.svg" location="admin.classes_management" />
                <x-nav-item text="Equipment" color="text-gray-600" src="equipment.svg" location="admin.equipments_management" />
                <x-nav-item text="Transaction" color="text-gray-600" src="dollar-sign.svg" location="admin.transactions_management" />
            </nav>
            
            <!-- User Profile Section -->
            <div class="absolute bottom-0 w-64 p-4  flex justify-between bg-white">
                <div class="flex items-center">
                    <a href={{ route('profile.edit') }}>
                        <img class="w-8 h-8 rounded-full" src="{{ $user->image_url }}" alt="{{ $user->name }}">
                    </a>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                </div>
                <div>
                    <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                                <img src="{{ asset('icons/logout.svg') }}" alt="logout">
                            </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 ml-64">
            <!-- Scrollable Content -->
            <main class="pt-4 pb-8 px-4 h-screen overflow-y-auto scroll-container">
                <h2 class="text-lg font-medium" >Booking page</h2>
            </main>
        </div>
    </div>
</body>
</html>
