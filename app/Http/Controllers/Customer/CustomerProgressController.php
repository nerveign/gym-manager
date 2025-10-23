<?php
namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->isCustomer()) {
            abort(403, 'Unauthorized access for customers only.');
        }

        $progress = Auth::user()->userProgress()->latest()->get();

        return view('customer.progress.index', compact('progress'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->isCustomer()) {
            abort(403, 'Unauthorized access for customers only.');
        }

        return view('customer.progress.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isCustomer()) {
            abort(403, 'Unauthorized access for customers only.');
        }

        $request->validate([
            'exercise' => 'required|string|max:255',
            'duration' => 'required|integer|min:1|max:300',
            'description' => 'nullable|string|max:1000',
        ]);

        Auth::user()->userProgress()->create([
            'exercise' => $request->exercise,
            'duration' => $request->duration,
            'description' => $request->description,
        ]);

        return redirect()->route('customer.progress.index')
            ->with('success', 'Progress recorded successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserProgress $progress)
    {
        if (!auth()->user()->isCustomer() || $progress->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('customer.progress.edit', compact('progress'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserProgress $progress)
    {
        if (!auth()->user()->isCustomer() || $progress->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'exercise' => 'required|string|max:255',
            'duration' => 'required|integer|min:1|max:300',
            'description' => 'nullable|string|max:1000',
        ]);

        $progress->update([
            'exercise' => $request->exercise,
            'duration' => $request->duration,
            'description' => $request->description,
        ]);

        return redirect()->route('customer.progress.index')
            ->with('success', 'Progress updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserProgress $progress)
    {
        if (!auth()->user()->isCustomer() || $progress->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $progress->delete();

        return redirect()->route('customer.progress.index')
            ->with('success', 'Progress deleted successfully!');
    }
}