<?php

namespace App\Models\Traits;


use App\Enums\WalletType;
use App\Exceptions\Models\Wallet\WalletAlreadyExistsException;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property Collection<int, Wallet>|Wallet[] $wallets
 */
trait HasWallets
{
    protected array $cachedWallets = [];

    public function wallets(): MorphMany
    {
        return $this->morphMany(Wallet::class, 'owner');
    }

    public function getWalletByType(WalletType $walletType): ?Wallet
    {
        $wallet = $this->cachedWallets[$walletType->value] ?? null;

        if (!$wallet) {
            $wallet = $this->wallets()
                ->where('type', $walletType->value)
                ->first();

            $this->cachedWallets[$walletType->value] = $wallet;
        }

        return $wallet;
    }

    public function createWalletByType(WalletType $walletType): Wallet
    {
        $walletExist = $this->wallets()
            ->where('type', $walletType->value)
            ->exists();
        if ($walletExist) {
            throw new WalletAlreadyExistsException();
        }

        /** @var Wallet $wallet */
        $wallet = $this->wallets()->create([
            'type' => $walletType,
            'balance' => $walletType->getDefaultBalance(),
            'debt' => $walletType->getDefaultDebt(),
        ]);

        $this->cachedWallets[$walletType->value] = $wallet;

        return $wallet;
    }

    public function getOrCreateWalletByType(WalletType $walletType): Wallet
    {
        $wallet = $this->getWalletByType($walletType);

        if (!$wallet) {
            $wallet = $this->createWalletByType($walletType);
        }

        return $wallet;
    }
}
