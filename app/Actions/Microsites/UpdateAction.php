<?php

namespace App\Actions\Microsites;

use App\Models\Microsites;
use Illuminate\Support\Facades\Log;

class UpdateAction
{
    public function execute(array $data): Microsites
    {
        $microsite = Microsites::find($data['id']);
        $microsite->name = $data['name'];
        $microsite->category_id = $data['category_id'];
        $microsite->document_type = $data['document_type'];
        $microsite->document_number = $data['document_number'];
        $microsite->logo = $data['logo'];
        $microsite->currency = $data['currency'];
        $microsite->site_type = $data['site_type'];
        $microsite->payment_expiration = $data['payment_expiration'];
        $microsite->save();
        Log::info('Microsite updated', ['microsite' => $microsite]);
        return $microsite;
    }
}
