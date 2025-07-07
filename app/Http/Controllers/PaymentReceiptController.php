<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;


class PaymentReceiptController extends Controller
{
    public function newDownloadPaymentReceipts(int $projectId, int $transactionId)
    {
        $transaction = Transaction::with('customer', "unit")->findOrFail($transactionId);
        $project = Project::findOrFail($projectId);
        $unit = $transaction->customer->units->first(); // Optional: adjust if multiple units

        // Check if the user has permission to access the project
        if (auth()->user()->organisation_id !== $project->organisation_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Render the HTML for the PDF
            $html = view('receipt_BS', [
                'project' => $project,
                'transaction' => $transaction,
            ])->render();

            if (Storage::disk('public')->missing('receipts')) {
                Storage::disk('public')->makeDirectory('receipts');
            }
            // Define the path to save the PDF
            $filePath = storage_path('app/public/receipts/receipt_' . now()->timestamp . '.pdf');


            $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
            // Generate the PDF using Browsershot
            if ($isWindows) {
                Browsershot::html($html)
                    ->size('A4')
                    ->save($filePath);
            } else {
                Browsershot::html($html)
                    ->setNodeBinary('/usr/local/bin/node')          // working Node symlink
                    ->setNpmBinary('/usr/local/bin/npm')
                    ->setChromePath('/usr/bin/chromium-browser')    // ← real binary path :contentReference[oaicite:2]{index=2}
                    ->noSandbox()                                   // required on most servers
                    ->addChromiumArguments([
                        '--user-data-dir=/tmp',
                        '--disable-crash-reporter',
                        '--no-sandbox',
                    ])                                              // valid flags (single “--”) :contentReference[oaicite:3]{index=3}
                    ->format('A4')
                    ->save($filePath);
            }
            // Check if the PDF file was created successfully
            if (!file_exists($filePath)) {
                return response()->json(['error' => 'PDF file could not be created.'], 500);
            }
            // Return the generated PDF as a download
            return response()->download($filePath);
        } catch (\Exception $e) {
            // Handle any exceptions and return the error message
            return response()->json(['error' => 'Failed to generate PDF: ERROR OCCURRED'], 500);
        }
    }
}
