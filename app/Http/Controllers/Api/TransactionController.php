<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->transactions = new Transaction();
        $this->transactionDetails = new TransactionDetail();
    }

    public function save(Request $request)
    {
        // Validasi input
        $validasi = Validator::make($request->all(), [
            'transactionUserId' => 'required',
            'transactionTotalItem' => 'required',
            'transactionTotalPrice' => 'required',
            'transactionName' => 'required',
            'transactionPhone' => 'required',
        ]);
        // Jika validasi tidak terpenuhi
        if ($validasi->fails()) {
            $val = $validasi->errors()->all();
            return $this->error($val[0]);
        }

        $paymentCode = "INV-PYM-" . now()->format('Ymd') . "-" . rand(100, 999);
        $transactionCode = "INV-PYM-" . now()->format('Ymd') . "-" . rand(100, 999);
        $uniqueCode = rand(100, 999);
        $status = "MENUNGGU";
        $expiredAt = now()->addDay();

        $dataTransaction = array_merge($request->all(), [
            'transactionPaymentCode' => $paymentCode,
            'transactionCode' => $transactionCode,
            'transactionUniqueCode' => $uniqueCode,
            'transactionStatus' => $status,
            'transactionExpiredAt' => $expiredAt
        ]);

        DB::beginTransaction();
        $transaction = $this->transactions->saveData($dataTransaction);

        foreach ($request->products as $product) {
            $dataDetail = [
                'detailTransactionId' => $transaction->transactionId,
                'detailProductId' => $product['detailProductId'],
                'detailTotalItem' => $product['detailTotalItem'],
                'detailTotalPrice' => $product['detailTotalPrice'],
                'detailNote' => $product['detailNote'],
            ];

            $transactionDetail = $this->transactionDetails->saveData($dataDetail);
        }

        if (!empty($transaction) && !empty($transactionDetail)) {
            DB::commit();
            return response()->json([
                'success' => 1,
                'message' => 'Transaksi berhasil',
                'user' => collect($transaction)
            ]);
        } else {
            DB::rollBack();
            return $this->error('Transaksi gagal');
        }
    }

    public function error($pesan)
    {
        return response()->json([
            'success' => 0,
            'message' => $pesan
        ]);
    }
}