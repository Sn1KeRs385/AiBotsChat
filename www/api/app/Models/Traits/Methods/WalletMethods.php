<?php

namespace App\Models\Traits\Methods;

use App\Enums\WalletTransactionType;
use App\Exceptions\Models\Wallet\NotEnoughFoundsException;
use App\Exceptions\Models\Wallet\TransactionAmountMustBeGreaterThanZeroException;
use App\Exceptions\Models\Wallet\TransactionTypeCreateMethodNotImplementException;
use App\Models\Casts\WalletTransaction\ExtraInfoData;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

trait WalletMethods
{
    public function getBalanceNormalize(): int
    {
        $balance = $this->balance;
        if ($this->balance === 0 && $this->debt > 0) {
            $balance = -1 * $this->debt;
        }

        return $balance;
    }

    public function createTransaction(
        WalletTransactionType $transactionType,
        int $amount,
        array $extraInfo = []
    ): WalletTransaction {
        if ($amount <= 0) {
            throw new TransactionAmountMustBeGreaterThanZeroException();
        }

        return match ($transactionType) {
            WalletTransactionType::REFILL => $this->createRefillTransaction($amount, ExtraInfoData::from($extraInfo)),
            WalletTransactionType::WITHDRAW => $this->createWithdrawTransaction(
                $amount,
                ExtraInfoData::from($extraInfo)
            ),
            WalletTransactionType::WITHDRAW_DEBT_POSSIBLE => $this->createWithdrawDebtPossibleTransaction(
                $amount,
                ExtraInfoData::from($extraInfo)
            ),
            default => throw new TransactionTypeCreateMethodNotImplementException(),
        };
    }

    protected function createRefillTransaction(int $amount, ExtraInfoData $extraInfo): WalletTransaction
    {
        $transaction = DB::transaction(function () use ($amount, $extraInfo) {
            /** @var Wallet $walletLocked */
            $walletLocked = $this::query()
                ->where('id', $this->id)
                ->lockForUpdate()
                ->first();

            /** @var WalletTransaction $transaction */
            $transaction = $walletLocked->transactions()->make([
                'type' => WalletTransactionType::REFILL,
                'amount' => $amount,
                'wallet_before' => Arr::only($walletLocked->toArray(), ['type', 'balance', 'debt']),
                'extra_info' => $extraInfo,
            ]);

            if ($walletLocked->debt > 0) {
                if ($amount > $walletLocked->debt) {
                    $amount = $amount - $walletLocked->debt;
                    $walletLocked->debt = 0;
                } else {
                    $walletLocked->debt = $walletLocked->debt - $amount;
                    $amount = 0;
                }
            }

            if ($amount > 0) {
                $walletLocked->balance = $walletLocked->balance + $amount;
            }

            $walletLocked->save();

            $transaction->wallet_after = Arr::only($walletLocked->toArray(), ['type', 'balance', 'debt']);

            $transaction->save();

            return $transaction;
        });

        $this->refresh();

        return $transaction;
    }

    protected function createWithdrawTransaction(int $amount, ExtraInfoData $extraInfo): WalletTransaction
    {
        $transaction = DB::transaction(function () use ($amount, $extraInfo) {
            /** @var Wallet $walletLocked */
            $walletLocked = $this::query()
                ->where('id', $this->id)
                ->lockForUpdate()
                ->first();

            if ($walletLocked->balance < $amount) {
                throw new NotEnoughFoundsException();
            }

            /** @var WalletTransaction $transaction */
            $transaction = $walletLocked->transactions()->make([
                'type' => WalletTransactionType::WITHDRAW,
                'amount' => $amount,
                'wallet_before' => Arr::only($walletLocked->toArray(), ['type', 'balance', 'debt']),
                'extra_info' => $extraInfo,
            ]);

            $walletLocked->balance = $walletLocked->balance - $amount;
            $walletLocked->save();

            $transaction->wallet_after = Arr::only($walletLocked->toArray(), ['type', 'balance', 'debt']);

            $transaction->save();

            return $transaction;
        });

        $this->refresh();

        return $transaction;
    }


    protected function createWithdrawDebtPossibleTransaction(int $amount, ExtraInfoData $extraInfo): WalletTransaction
    {
        $transaction = DB::transaction(function () use ($amount, $extraInfo) {
            /** @var Wallet $walletLocked */
            $walletLocked = $this::query()
                ->where('id', $this->id)
                ->lockForUpdate()
                ->first();

            if ($walletLocked->balance === 0) {
                throw new NotEnoughFoundsException();
            }

            /** @var WalletTransaction $transaction */
            $transaction = $walletLocked->transactions()->make([
                'type' => WalletTransactionType::WITHDRAW_DEBT_POSSIBLE,
                'amount' => $amount,
                'wallet_before' => Arr::only($walletLocked->toArray(), ['type', 'balance', 'debt']),
                'extra_info' => $extraInfo,
            ]);

            if ($walletLocked->balance >= $amount) {
                $walletLocked->balance = $walletLocked->balance - $amount;
            } else {
                $amount = $amount - $walletLocked->balance;
                $walletLocked->balance = 0;
                $walletLocked->debt = $walletLocked->debt + $amount;
            }

            $walletLocked->save();

            $transaction->wallet_after = Arr::only($walletLocked->toArray(), ['type', 'balance', 'debt']);

            $transaction->save();

            return $transaction;
        });

        $this->refresh();

        return $transaction;
    }
}
