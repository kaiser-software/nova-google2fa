<?php

namespace Lifeonscreen\Google2fa\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class User2fa
 * @package Lifeonscreen\Google2fa\Models
 */
class User2fa extends Model
{
    /**
     * @var string
     */
    protected $table   = 'user_2fa';

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('lifeonscreen2fa.models.user'));
    }

    /**
     * @return mixed
     */
    public function hashRecoveryCodes($codes = [])
    {
        if(empty($codes)) {
            throw new \Exception('No recovery codes given. Hashing failed');
        }

        $recoveryHashes = $codes;
        array_walk($recoveryHashes, function (&$value) {
            $value = password_hash($value, config('lifeonscreen2fa.recovery_codes.hashing_algorithm'));
        });

        return $recoveryHashes;
    }
}