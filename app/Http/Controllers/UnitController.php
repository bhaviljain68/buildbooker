<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Organisation;
use App\Models\Project;
use App\Models\Unit;
use Carbon\Carbon;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Show booking form
    public function booking(Project $project, Unit $unit)
    {
        $customers = Customer::whereIn('project_id', function ($query) use ($project) {
            $query->select('id')
                ->from('projects')
                ->where('organisation_id', $project->organisation_id);
        })->get();
        return Inertia::render('Booking', [
            'project' => $project,
            'unit' => $unit,
            'customers' => $customers,
        ]);
    }

    public function saveBooking(Request $request, Project $project, Unit $unit)
    {
        try {
            // Validate flat structure (no nested "customer" array in the request)
            $validated = $request->validate([
                'customer.name' => 'required|string|max:255',
                'customer.email' => 'nullable|email|max:255',
                'customer.mobile' => 'nullable|digits:10',
                'customer.address' => 'nullable|string|max:500',
                'customer.base' => 'required|numeric|min:1',
                'customer.gst' => 'required|numeric|min:1',
                'customer.total' => 'required|numeric|min:1',
            ]);
            // Create or find the customer by phone number
            $customer = Customer::firstOrCreate(
                ['mobile' => $validated['customer']['mobile']],
                [
                    'name' => $validated['customer']['name'],
                    'email' => $validated['customer']['email'],
                    'address' => $validated['customer']['address'],
                    'project_id' => $project->id, // Optional if customer belongs to project
                ]
            );

            // Associate customer with the unit
            $unit->customer()->associate($customer);
            $unit->is_sold = true;
            $unit->project_id = $project->id;
            $unit->customer_id = $customer->id;
            $unit->base_amount = $validated['customer']['base'];
            $unit->gst_amount = $validated['customer']['gst'];
            $unit->total_amount = $validated['customer']['total'];
            $unit->save();

            return redirect()->route('units.index', [
                'organisation' => auth()->user()->organisation_id,
                'project' => $project->id,
            ])->with('success', 'Unit booked successfully!');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Booking failed: ' . $e->getMessage(),
            ])->withInput();
        }
    }

    public function editBooking(Project $project, Unit $unit)
    {
        $unit->load('customer');

        return Inertia::render('EditBooking', [
            'project' => $project,
            'unit' => $unit,
            'customer' => $unit->customer, // only pass the selected customer
        ]);
    }

    public function updateBooking(Request $request, Project $project, Unit $unit)
    {
        $data = $request->validate([
            'customer.name' => 'required|string|max:255',
            'customer.email' => 'nullable|email',
            'customer.phone' => 'nullable|string|max:20',
            'customer.address' => 'nullable|string|max:255',
            'customer.base' => 'required|numeric',
            'customer.gst' => 'required|numeric',
            'customer.total' => 'required|numeric',
        ]);
        $unit->update([
            'base_amount' => $data['customer']['base'],
            'gst_amount' => $data['customer']['gst'],
            'total_amount' => $data['customer']['total'],
        ]);

        if ($unit->customer) {
            $unit->customer->update([
                'name' => $data['customer']['name'],
                'email' => $data['customer']['email'],
                'mobile' => $data['customer']['phone'],
                'address' => $data['customer']['address'],
            ]);
        }

        return redirect()->route('units.index', ['organisation' => Auth::user()->organisation_id, 'project' => $project->id])
            ->with('success', 'Booking updated successfully.');
    }

    public function showBooking(Request $request, Project $project, Unit $unit)
    {
        $unit->load('customer');

        return Inertia::render('Booking', [
            'project' => $project,
            'unit' => $unit,
        ]);
    }

    public function index(Organisation $organisation, Project $project)
    {
        if (
            $organisation->id !== auth()->user()->organisation_id ||
            auth()->user()->organisation_id !== $project->organisation_id
        ) {
            abort(403);
        }
        $units = $project->units;
        // dd($units);
        return Inertia::render('units/Index', [
            'units' => $units,
            'project' => $project,
            'project_id' => $project->id,
            'organisation_id' => auth()->user()->organisation_id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project)
    {
        $existingUnits = $project->units;
        return Inertia::render('units/Create', compact(['project', 'existingUnits']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Project $project, Request $request)
    {
        if (auth()->user()->organisation_id !== $project->organisation_id) {
            abort(403);
        }

        // dd($request->all());
        try {

            $validated = $request->validate([
                'units' => 'required|array|min:1',
                'units.*.unit_no' => [
                    'required',
                    'string',
                    'max:10',
                    Rule::unique('units', 'unit_no')->where('project_id', $project->id)
                ],
                'units.*.unit_type' => 'required|in:apartment,shop,showroom,plot,bungalow',
            ], [
                'units.required' => 'You must provide at least one unit.',
                'units.array' => 'Units must be in array format.',
                'units.min' => 'You must provide at least one unit.',

                'units.*.unit_no.required' => 'Unit number is required.',
                'units.*.unit_no.string' => 'Unit number must be a string.',
                'units.*.unit_no.max' => 'Unit number cannot exceed 10 characters.',
                'units.*.unit_no.unique' => "This unit (:input) .. already exists in the project : " . $project->name . '',

                'units.*.unit_type.required' => 'Unit type is required.',
                'units.*.unit_type.in' => 'Unit type must be one of: apartment, shop, showroom, plot or bungalow.',
            ]);

            try {
                $now = Carbon::now();
                $unitsToInsert = array_map(function ($unit) use ($validated, $now, $project) {
                    return [
                        'project_id' => $project->id,
                        'unit_no' => $unit['unit_no'],
                        'type' => $unit['unit_type'],
                        'created_at' => $now,
                        'updated_at' => $now
                    ];
                }, $validated['units']);

                Unit::insert($unitsToInsert);
                if ($request->expectsJson()) {
                    $unit = Unit::where("unit_no", $unitsToInsert[0]['unit_no'])->first();
                    if ($request->expectsJson() && $unit) {
                        $unit->load("project");
                        return response()->json([$unit]);
                    } else {
                        throw new \Exception("something went wrong");
                    }
                }
                return redirect()->route('units.index', [
                    'organisation' => auth()->user()->organisation_id,
                    'project' => $project->id
                ])->with('success', 'Units created successfully!');
            } catch (\Throwable $th) {
                throw new \Exception("Unable to Save Processing Units", 1);
            }
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Failed to create units',
                    'code' => $e->getCode(),
                    'status' => 500,
                    'data' => null,
                    'success' => false
                ]);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Unit $unit)
    {

        $unit->load("project");
        $unit->load([
            'project',
            'transactions.customer.units'
        ]);
        if ($request->expectsJson()) {
            return response()->json([$unit]);
        }
        return Inertia('units/Show', [
            'unit' => $unit,
            'project' => $unit->project,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project, Unit $unit)
    {
        $project->load('organisation');
        return Inertia('units/Edit', compact('unit', 'project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Project $project, Unit $unit, Request $request)
    {
        if (auth()->user()->organisation_id !== $project->organisation_id) {
            abort(403);
        }

        try {

            $validated = $request->validate([
                'units' => 'required|array|min:1',
                'units.*.unit_type' => 'required|in:apartment,shop,showroom,plot,bungalow',
                'units.*.unit_no' => ['required', 'string', 'max:10', Rule::unique('units', 'unit_no')
                    ->where('project_id', $project->id)
                    ->ignore($unit->id)],
            ], [
                'units.required' => 'You must provide at least one unit.',
                'units.array' => 'Units must be in array format.',
                'units.min' => 'You must provide at least one unit.',

                'units.*.unit_no.required' => 'Unit number is required.',
                'units.*.unit_no.string' => 'Unit number must be a string.',
                'units.*.unit_no.max' => 'Unit number cannot exceed 10 characters.',
                'units.*.unit_no.unique' => "This unit (:input) .. already exists in the project : " . $project->name . '',

                'units.*.unit_type.required' => 'Unit type is required.',
                'units.*.unit_type.in' => 'Unit type must be one of: apartment, shop, showroom, bungalow or plot.',
            ]);
            try {
                $unit = Unit::findOrFail($unit->id);
                $data = [
                    'unit_no' => $validated['units'][0]['unit_no'],
                    'type' => $validated['units'][0]['unit_type'],
                ];
                $unit->update($data);
                ToastMagic::success('project Unit Update successfully!');
                return redirect()->route('units.index', [
                    'organisation' => auth()->user()->organisation_id,
                    'project' => $project->id
                ])->with('success', 'Unit updated successfully!');
            } catch (\Throwable $th) {
                throw new \Exception("Unable to Update Processing Units", 1);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */

    public function delete(Unit $unit)
    {
        try {
            if (auth()->user()->organisation_id !== $unit->project->organisation_id) {
                abort(403);
            }

            if ($unit->is_sold) {
                // Soft delete for sold units
                $unit->delete();
            } else {
                // Force delete for unsold units
                $unit->forceDelete();
            }

            ToastMagic::success('Unit deleted successfully!');
            return redirect()->route('units.index', [
                'organisation' => auth()->user()->organisation_id,
                'project' => $unit->project_id
            ])->with('success', 'Unit deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
