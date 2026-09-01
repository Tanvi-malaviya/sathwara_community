<?php

namespace App\Services;

use App\Models\ReceiptSequence;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

/**
 * Issues a single, organization-wide receipt number sequence shared across
 * every receipt type (membership, business registration/renewal, event
 * passes, sponsorships). Numbers are formatted "<financial-year>/<00001>"
 * and reset to 1 at the start of each Indian financial year (Apr-Mar).
 */
class ReceiptNumberService
{
    /**
     * Compute the "YYYY-YY" Indian financial year label for a given date.
     */
    public static function currentFinancialYear(?Carbon $date = null): string
    {
        $date = $date ?? now();
        $startYear = $date->month >= 4 ? $date->year : $date->year - 1;
        $endYearShort = str_pad((string) (($startYear + 1) % 100), 2, '0', STR_PAD_LEFT);

        return $startYear . '-' . $endYearShort;
    }

    /**
     * Atomically claim the next receipt number in the current financial year's sequence.
     */
    public static function next(): string
    {
        $financialYear = self::currentFinancialYear();

        for ($attempt = 0; $attempt < 3; $attempt++) {
            try {
                return DB::transaction(function () use ($financialYear) {
                    $sequence = ReceiptSequence::where('financial_year', $financialYear)->lockForUpdate()->first();

                    if (!$sequence) {
                        $sequence = ReceiptSequence::create([
                            'financial_year' => $financialYear,
                            'last_number' => 0,
                        ]);
                        $sequence = ReceiptSequence::where('financial_year', $financialYear)->lockForUpdate()->first();
                    }

                    $sequence->increment('last_number');

                    return $financialYear . '/' . str_pad((string) $sequence->last_number, 5, '0', STR_PAD_LEFT);
                });
            } catch (QueryException $e) {
                if ($attempt === 2) {
                    throw $e;
                }
            }
        }
    }

    /**
     * Assign a receipt number to the given model/column if it doesn't already have one,
     * persisting it so the same receipt always renders the same number.
     */
    public static function assign(Model $model, string $column = 'receipt_no'): string
    {
        if (!empty($model->{$column})) {
            return $model->{$column};
        }

        $receiptNo = self::next();
        $model->{$column} = $receiptNo;
        $model->save();

        return $receiptNo;
    }
}
