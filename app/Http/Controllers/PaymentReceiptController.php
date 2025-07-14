<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Transaction;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

class PaymentReceiptController extends Controller
{
    public function newDownloadPaymentReceipts(int $projectId, int $transactionId)
    {
        $transaction = Transaction::with('customer', 'unit')->findOrFail($transactionId);
        $project = Project::findOrFail($projectId);

        // Optional: Use first unit linked to customer
        $unit = $transaction->customer->units->first();

        // Check if the user has permission to access the project
        if (auth()->user()->organisation_id !== $project->organisation_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Render the Blade view to HTML
            $html = view('Receipt_bs', [
                'project' => $project,
                'transaction' => $transaction,
            ])->render();

            // Ensure directory exists
            if (Storage::disk('public')->missing('receipts')) {
                Storage::disk('public')->makeDirectory('receipts');
            }

            // File path
            $filePath = storage_path('app/public/receipts/receipt_' . now()->timestamp . '.pdf');

            $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

            // Use Browsershot to generate PDF
            if ($isWindows) {
                Browsershot::html($html)
                    ->format('A4')
                    ->save($filePath);
            } else {
                Browsershot::html($html)
                    ->setNodeBinary('/usr/local/bin/node')
                    ->setNpmBinary('/usr/local/bin/npm')
                    ->setChromePath('/usr/bin/chromium-browser')
                    ->noSandbox()
                    ->addChromiumArguments([
                        '--user-data-dir=/tmp',
                        '--disable-crash-reporter',
                        '--no-sandbox',
                    ])
                    ->format('A4')
                    ->save($filePath);
            }

            // Check file and return download response
            if (!file_exists($filePath)) {
                return response()->json(['error' => 'PDF file could not be created.'], 500);
            }

            return response()->download($filePath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            // return response()->json(['error' => 'Failed to generate PDF'], 500);
              return response()->json(['error' => 'Failed to generate PDF: ' . $e->getMessage()], 500);
        }
    }
}
