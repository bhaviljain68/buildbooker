<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Organisation;
use App\Models\Project;
use App\Models\Unit;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index(Project $project)
    {
        $project->load(['customers.units']);
        return Inertia::render('customers/Index', compact('project'));
    }

    public function getOrgCustomers(Organisation $organisation)
    {
        $organisation->load('projects.customers.units'); // Eager load nested

        $project = $organisation->projects[0] ?? null;

        // If there's at least one project, grab its customers
        $customers = $project ? $project->customers : collect();

        return Inertia::render('customers/Index', [
            'project' => $project,
            'customers' => $customers
        ]);
    }

    public function create(Project $project)
    {
        // dd($project);
        // $project::Projects()->findOrFail($project->id);
        return Inertia::render('customers/Create', [
            'project' => $project,
        ]);
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email',
            'mobile' => 'nullable|numeric',
            'address' => 'nullable|string',
            'project_id' => 'required|exists:projects,id'
        ]);
        //dd($validated);

        //create a new customer instance
        $customer = new Customer($validated);
        //$customer->project_id = $project->id;
        $customer->save();
        //dd($customer);
        ToastMagic::success('Customer Add submitted successfully');
        return redirect()->route('customers.index', $project->id)->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        return Inertia::render('customers/Show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return Inertia::render('customers/Edit', [
            'customer' => $customer,
            'projectId' => $customer->project_id,
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        if ($customer->mobile !== $request->mobile) {
            $request->validate([
                'mobile' => 'required|digits_between:7,15|regex:/^[0-9]+$/|unique:customers,mobile',
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $customer->id,
            'address' => 'nullable|string|max:500',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index', $customer->project_id)->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->units()->count() > 1) {
            return back()->with('error', 'Customer has multiple units and cannot be deleted.');
        }

        if ($customer->transactions()->exists()) {
            $customer->transactions()->delete();
        }

        $unit = Unit::where('customer_id', $customer->id)->first();
        if ($unit) {
            $unit->update([
                'customer_id' => null,
                'is_sold' => false,
                'base_amount' => null,
                'gst_amount' => null,
                'total_amount' => null,
            ]);
        }
        $customer->delete();
        $projectId = $customer->project_id;

        ToastMagic::success('Customer deleted successfully.');
        return redirect()->route('customers.index', $projectId)->with('success', 'Customer deleted.');
    }
}
