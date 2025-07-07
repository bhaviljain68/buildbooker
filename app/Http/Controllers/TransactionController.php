<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionStoreRequest;
use App\Models\Customer;
use App\Models\Organisation;
use App\Models\Project;
use App\Models\Transaction;
use App\Models\Unit;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;

class TransactionController extends Controller
{
    // 🔍 List all transactions
    public function index(Organisation $organisation, $id)
    {
        $project = Project::findOrFail($id);
        $transactions = Transaction::with(['customer', 'unit'])->whereHas('unit', function ($q) use ($id) {
            $q->where('project_id', $id);
        })->get();
        // dd($transactions->toArray());
        return Inertia::render('transactions/Index', [
            'transactions' => $transactions,
            'project' => $project,
            'organisation' => $organisation,
        ]);
    }

    // ➕ Show create form
    public function create(Unit $unit = null, Project $project = null)
    {
        $units = $project ? $project->units : [];

        if ($project) {
            return Inertia::render('transactions/Create', [
                'unit' => $unit,
                'units' => $units,
                'project' => $project,
            ]);
        }

        abort(404);
    }

    public function store(TransactionStoreRequest $request, Unit $unit = null)
    {
        try {
            DB::beginTransaction();
            if (!$unit || !$unit->customer_id) {
                throw new Exception("Invalid unit or missing customer.");
            }

            $transactionCount = Transaction::where('project_id', $unit->project_id)->count();

            $increment = $transactionCount + 1;
            $receiptNumber = $request->gst
                ? '#G' . str_pad($increment, 5, '0', STR_PAD_LEFT)
                : '#' . str_pad($increment, 5, '0', STR_PAD_LEFT);


            Transaction::create([
                'customer_id'        => $unit->customer_id,
                'unit_id'            => $unit->id,
                'project_id'         => $unit->project_id,
                'receipt_number'     => $receiptNumber,
                'receipt_date'       => $request->receipt_date,
                'payment_date'       => $request->payment_date,
                'unit_no'            => $unit->unit_no,
                'bank_name'          => $request->bank_name,
                'bank_branch'        => $request->bank_branch,
                'payment_type'       => $request->payment_type,
                'payment_reference'  => $request->payment_reference,
                'transaction_amount' => $request->transaction_amount,
                'gst'                => $request->gst,
            ]);

            DB::commit();

            ToastMagic::success('Transaction added successfully.');
            return redirect()->route('transactions.index', [
                'organisation' => Auth::user()->organisation_id,
                'project'      => $unit->project_id,
            ])->with('success', 'Transaction added successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create transaction: ' . $e->getMessage());
        }
    }

    public function edit(Transaction $transaction, Unit $unit = null, Project $project = null)
    {
        $findProjects = Organisation::find(Auth::user()->organisation_id)->projects->pluck('id')->toArray();
        $allCustomers = Customer::whereIn('project_id', $findProjects)->get();

        if ($unit) {
            return Inertia::render('transactions/Edit', [
                'unit' => $unit,
                'units' => null,
                'project' => $project,
                'customer' => $unit->customer,
                'allCustomers' => $allCustomers,
                'transaction' => $transaction,
            ]);
        }

        if ($project) {
            $units = $project->units()->pluck('unit_no', 'id');
            return Inertia::render('transactions/Edit', [
                'unit' => null,
                'units' => $units,
                'project' => $project,
                'customer' => null,
                'allCustomers' => $allCustomers,
                'transaction' => $transaction,
            ]);
        }

        abort(404);
    }


    public function update(TransactionStoreRequest $request, Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            $unit = $transaction->unit;

            if (!$unit || !$unit->customer_id) {
                throw new Exception("Unit or customer not found.");
            }

            $transaction->update([
                "customer_id" => $unit->customer_id,
                "unit_id" => $unit->id,
                "receipt_date" => $request->receipt_date,
                "payment_date" => $request->payment_date,
                "unit_no" => $unit->unit_no,
                "bank_name" => $request->bank_name,
                "bank_branch" => $request->bank_branch,
                "payment_type" => $request->payment_type,
                "payment_reference" => $request->payment_reference,
                "transaction_amount" => $request->transaction_amount,
                "gst" => $request->gst,
            ]);

            DB::commit();

            return redirect()->route('transactions.index', [
                'organisation' => Auth::user()->organisation_id,
                'project' => $unit->project_id,
            ])->with('success', 'Transaction updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update transaction: ' . $e->getMessage());
        }
    }

    public function deleteTransaction(Transaction $transaction)
    {
        $totalTransactions = $transaction->unit->transactions->count();
        if ($totalTransactions === 1) {
            $this->unBook($transaction->unit->id);
            // $this->unBook($transaction->unit);
            return redirect()->back()->with('success', 'Transaction deleted successfully!');
        } else {
            try {
                DB::beginTransaction();
                $transaction->delete(); // Soft delete
                DB::commit();
                ToastMagic::success('Transaction deleted successfully.');
                return redirect()->back()->with('success', 'Transaction deleted successfully!');
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'An error occurred while deleting the current transaction details.');
            }
        }
    }

    public function unBook(Unit $unit)
    {
        try {
            DB::beginTransaction();

            $unit->customer->transactions()->delete();
            $unit->customer()->dissociate();
            $unit->base_amount = null;
            $unit->gst_amount = null;
            $unit->total_amount = null;
            $unit->is_sold = false;
            $unit->save();

            DB::commit();
            ToastMagic::success('Unit unbooked successfully.');
            return redirect()->back()->with('success', 'Transaction deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to unbook unit: ' . $e->getMessage());
        }
    }
}
