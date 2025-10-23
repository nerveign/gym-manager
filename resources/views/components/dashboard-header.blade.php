 @props(["name" => "Name"])
 <div>
    <div class="p-6 border-b flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
            <i class="fas fa-dumbbell text-white text-lg"></i>
        </div>
        <div>
            <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
            <p class="text-sm text-gray-600">Welcome, {{ $name }}!</p>
        </div>
    </div>
</div>