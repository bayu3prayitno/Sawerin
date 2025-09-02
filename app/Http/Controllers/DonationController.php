<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use Illuminate\Http\Request;
use App\Models\User;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;
use Xendit\Configuration;
use Illuminate\Support\Facades\DB;
use App\Models\Donation;
use Xendit\Invoice\InvoiceItem;
class DonationController extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
    }
    public function index($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        return view('donation', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'amount' => 'required|integer|min:1000',
            'message' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $donation = Donation::create([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'email' => $request->email,
                'amount' => $request->amount,
                'message' => $request->message,
                'status' => 'pending',
            ]);

            $invoiceItem = new InvoiceItem([
                'name' => 'Donation',
                'price' => $request->amount,
                'quantity' => 1,
            ]);

            $createInvoice = new CreateInvoiceRequest([
                'external_id' => 'donation-' . $donation->id,
                'payer_email' => $donation->email,
                'amount' => $request->amount,
                'items' => [$invoiceItem],
                'invoice_duration' => 3600,
                'success_redirect_url' => route('donation.success', $donation->id),
            ]);

            $api = new InvoiceApi();
            $generateInvoice = $api->createInvoice($createInvoice);

            $payment = Payments::create([
                'donation_id' => $donation->id,
                'payment_id' => $generateInvoice['id'],
                'payment_method' => 'xendit',
                'status' => 'pending',
                'payment_url' => $generateInvoice['invoice_url'],
            ]);

            DB::commit();

            return redirect($payment->payment_url);

        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memproses donasi. Silakan coba lagi.']);
        }
    }

    public function dashboard()
    {
        $user = auth()->user();

        $saldo = Donation::where('user_id', $user->id)
            ->sum('amount');

        return view('dashboard', compact('user', 'saldo'));
    }
}
